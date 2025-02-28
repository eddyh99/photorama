<?php

namespace App\Controllers;

use App\Controllers\Admin\Voucher;
use SimpleSoftwareIO\QrCode\Generator;

class Home extends BaseController
{

    public function __construct()
    {   
        $user = $_COOKIE['logged_user'] ?? null;
        $this->id_cabang = $user ? json_decode($user)->id_cabang: null;
        $this->cabang       = model('App\Models\Mdl_cabang');
        $this->qris       = model('App\Models\Mdl_qris');
        $this->background       = model('App\Models\Mdl_background');
        $this->frame            = model('App\Models\Mdl_frame');
        $this->price       = model('App\Models\Mdl_price');
        $this->setting       = model('App\Models\Mdl_settings');
        $this->timer       = model('App\Models\Mdl_timer');
        $this->pembayaran       = model('App\Models\Mdl_pembayaran');
	}

    public function testing() {
        return view('guest/script');
    }

    public function index()
    {
        session()->set('print', 0);
        $background = $this->background->backgroundByScreen('screen_start', $this->id_cabang);
        // dd($background);
        $mdata = [
            'title'         => 'Beranda - ' . NAMETITLE,
            'extra'         => 'guest/beranda/js/_js_index',
            'content'       => 'guest/beranda/index',
            'background'    =>  $background ?? null
        ];

        return view('guest/wrapper', $mdata);
    }

    public function order() {
        $background = $this->background->backgroundByScreen('screen_order', $this->id_cabang);
        $price = $this->price->getBy_cabang($this->id_cabang);
        $timer = $this->timer->get_byCabang_andScreen('screen_order', $this->id_cabang);
        $image_order = $this->setting->value('img_order');
        $mdata = [
            'title'         => 'Beranda - ' . NAMETITLE,
            'content'       => 'guest/order/index',
            'extra'         => 'guest/order/js/_js_index',
            'background'    =>  $background ?? null,
            'price'         => $price,
            'image'         => BASE_URL . 'assets/img/' . ($image_order ?? 'order/img-default.png'),
            'timer'         => $timer
        ];

        return view('guest/wrapper', $mdata);
    }

    public function payment($price, $print = null) {
        $price = base64_decode($price);
        if(!is_numeric($price) || is_null($print)) return redirect()->to(BASE_URL .'order');

        $kd_voucher = $this->request->getVar('voucher');
        if(!empty($kd_voucher)) {
            $voucher = new Voucher;
            $result = $voucher->cekVoucher($kd_voucher, $this->id_cabang);

            if (!$result->success) {
                session()->setFlashdata('failed', $result->message);
                return redirect()->to(BASE_URL . "order");
            }
            session()->setFlashdata('success', $result->message);
            $price =  $price - $result->data->potongan_harga;

        }
        $payment =  Payment::QRIS($price);
        if(!$payment->success) {
            session()->setFlashdata('failed', 'Maaf, coba kembali beberapa saat lagi.');
            return redirect()->to(BASE_URL. 'order');
        }
        $cabang = $this->cabang->getCabang_byId($this->id_cabang);

        $inv = [
            'invoice' => $payment->data->invoice,
            'amount' => $price,
            'tanggal' => $payment->data->tanggal,
            'status' => 'pending',
            'cabang' => '-'
        ];

        $this->pembayaran->addInvoice($inv);
        $background = $this->background->backgroundByScreen('screen_payment', $this->id_cabang);
        $qris_bg = $this->qris->getBy_cabang($this->id_cabang);
        $timer = $this->timer->get_byCabang_andScreen('screen_payment', $this->id_cabang);
        $mdata = [
            'title'         => 'Beranda - ' . NAMETITLE,
            'content'       => 'guest/payment/index',
            'extra'         => 'guest/payment/js/_js_index',
            'background'    =>  $background ?? null,
            'price'         =>  $price,
            'print'         =>  $print,
            'timer'         =>  $timer,
            'qris'          =>  $payment->data->qris,
            'bg_qris'       =>  $qris_bg ?? null,
            'inv'           =>  $payment->data->invoice,
            'cabang'        => $cabang->lokasi ?? ''
        ];
        return view('guest/wrapper', $mdata);
    }

    public function frame() {
        $background = $this->background->backgroundByScreen('screen_frame', $this->id_cabang);
        $frame = $this->frame->allFrame();
        $timer = $this->timer->get_byCabang_andScreen('screen_frame', $this->id_cabang);

        $mdata = [
            'title'         => 'Beranda - ' . NAMETITLE,
            'content'       => 'guest/frame/index',
            'extra'         => 'guest/frame/js/_js_index',
            'background'    =>  $background ?? null,
            'frame'         =>  $frame,
            'timer'         => $timer
        ];

        return view('guest/wrapper', $mdata);
    }

    public function camera($frame) {
        $background = $this->background->backgroundByScreen('screen_select_camera', $this->id_cabang);
        $timer = $this->timer->get_byCabang_andScreen('screen_select_camera', $this->id_cabang);

        $mdata = [
            'title'         => 'Camera - ' . NAMETITLE,
            'content'       => 'guest/camera/index',
            'extra'         => 'guest/camera/js/_js_index',
            'background'    =>  $background ?? null,
            'timer'         => $timer,
            'frame'         => $frame
        ];

        return view('guest/wrapper', $mdata);
    }

    public function capture($frame) {
        $frame = $this->frame->getById(base64_decode($frame));
        if(!$frame) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $background = $this->background->backgroundByScreen('screen_capture_photo', $this->id_cabang);
        $timer = $this->timer->get_byCabang_andScreen('screen_capture_photo', $this->id_cabang);

        $mdata = [
            'title'         => 'Take Photo - ' . NAMETITLE,
            'content'       => 'guest/capture/index',
            'extra'         => 'guest/capture/js/_js_index',
            'background'    =>  $background ?? null,
            'frame'         => $frame,
            'timer'         => $timer
        ];

        return view('guest/wrapper', $mdata);
    }

    public function saveRecords()
    {
        $time = time();
        $cabang = $this->cabang->getCabang_byId($this->id_cabang)->nama_cabang ?? 'unknown';
        $uploadDir = "assets/photobooth/" .$cabang. "-$time/";

        // Buat direktori jika belum ada
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $response = ['success' => false];
        $response += ['form' => $_FILES];
        foreach ($_FILES as $key => $file) {
            if ($file['error'] === UPLOAD_ERR_OK) {
                $uploadFile = "$uploadDir/" . ($file['type'] == "image/png" ? "$key.png" : "$key.mp4");
                
                if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                    $response = [
                        'success' => true,
                        'folder'  => base64_encode($cabang . '-' .$time),
                        'form'    => null
                    ];
                }
            }
        }

        return json_encode($response);
    }

    public function updateRecord($dir)
    {
        $uploadDir = "assets/photobooth/" . base64_decode($dir) . "/";
        if (!is_dir($uploadDir)) return json_encode(['success' => false]);
    
        $success = move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . "photos.png");
        return json_encode(['success' => $success]);
    }

    public function filter($dir) {
        // dd(base64_decode($dir));
        $background = $this->background->backgroundByScreen('screen_filter', $this->id_cabang);
        $timer = $this->timer->get_byCabang_andScreen('screen_filter', $this->id_cabang);

        $mdata = [
            'title'         => 'Make Filter - ' . NAMETITLE,
            'content'       => 'guest/filter/index',
            'extra'         => 'guest/filter/js/_js_index',
            'background'    =>  $background ?? null,
            'timer'         => $timer,
            'dir'           => base64_decode($dir)
        ];

        return view('guest/wrapper', $mdata);

    }

    public function print($dir) {
        $background = $this->background->backgroundByScreen('screen_print', $this->id_cabang);
        $timer = $this->timer->get_byCabang_andScreen('screen_print', $this->id_cabang);
        $qrcode = new Generator;
        $auto_print = $this->setting->value('auto_print');

        $mdata = [
            'title'         => 'Print - ' . NAMETITLE,
            'content'       => 'guest/print/index',
            'extra'         => 'guest/print/js/_js_index',
            'background'    =>  $background ?? null,
            'timer'         => $timer,
            'dir'           => base64_decode($dir),
            'auto_print'    => $auto_print ? filter_var($auto_print, FILTER_VALIDATE_BOOLEAN) : false,
            'qrcode'        => $qrcode->size(250)->generate(base_url("download/$dir"))
        ];

        return view('guest/wrapper', $mdata);

    }

    public function finish() {
        $background = $this->background->backgroundByScreen('screen_finish', $this->id_cabang);
        $timer = $this->timer->get_byCabang_andScreen('screen_finish', $this->id_cabang);
        $mdata = [
            'title'         => 'Print - ' . NAMETITLE,
            'content'       => 'guest/finish/index',
            'background'    =>  $background ?? 'thx.png',
            'timer'         => $timer
        ];

        return view('guest/wrapper', $mdata);
    }

    public function userFiles($folder)
    {
        $path = FCPATH . "assets/photobooth/" . base64_decode($folder);
        
        if (!is_dir($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $files = array_diff(scandir($path), array('.', '..'));

        $mdata = [
            'title'         => 'My Files - ' . NAMETITLE,
            'extra'         => 'guest/download/js/_js_index',
            'content'       => 'guest/download/index',
            'files'    =>  $files,
            'folder'    =>  base64_decode($folder),
        ];

        return view('guest/wrapper', $mdata);
    }
}
