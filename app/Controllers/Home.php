<?php

namespace App\Controllers;

use App\Controllers\Admin\Voucher;
use SimpleSoftwareIO\QrCode\Generator;

class Home extends BaseController
{

    public function __construct()
    {   
        $this->background       = model('App\Models\Mdl_background');
        $this->frame            = model('App\Models\Mdl_frame');
        $this->setting       = model('App\Models\Mdl_settings');
        $this->pembayaran       = model('App\Models\Mdl_pembayaran');
	}

    public function testing() {

        return view('guest/script');
    }

    public function index()
    {
        session()->set('print', 0);
        $result = $this->background->backgroundByScreen('Screen 1');
        $background = $result ? BASE_URL .'assets/img/'.$result->file : null;
        $mdata = [
            'title'         => 'Beranda - ' . NAMETITLE,
            'content'       => 'guest/beranda/index',
            'extra'     => 'guest/beranda/js/_js_index',
            'background'    =>  $background
        ];

        return view('guest/wrapper', $mdata);
    }

    public function order() {
        $result = $this->background->backgroundByScreen('Screen 2');
        $background = $result ? BASE_URL .'assets/img/'.$result->file : null;
        $price = $this->setting->value('price');
        $timer = $this->setting->value('timer_order');
        $mdata = [
            'title'         => 'Beranda - ' . NAMETITLE,
            'content'       => 'guest/order/index',
            'extra'     => 'guest/order/js/_js_index',
            'background'    =>  $background,
            'price'         => $price,
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
            $result = $voucher->cekVoucher($kd_voucher);

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

        $inv = [
            'invoice' => $payment->data->invoice,
            'amount' => $price,
            'tanggal' => $payment->data->tanggal,
            'status' => 'pending',
            'cabang' => '-'
        ];

        $this->pembayaran->addInvoice($inv);
        $result = $this->background->backgroundByScreen('Screen 2');
        $background = $result ? BASE_URL .'assets/img/'.$result->file : null;
        $timer = $this->setting->value('timer_payment');
        $mdata = [
            'title'         => 'Beranda - ' . NAMETITLE,
            'content'       => 'guest/payment/index',
            'extra'         => 'guest/payment/js/_js_index',
            'background'    =>  $background,
            'price'         =>  $price,
            'print'         =>  $print,
            'timer'         =>  $timer,
            'qris'          =>  $payment->data->qris,
            'inv'           =>  $payment->data->invoice
        ];
        return view('guest/wrapper', $mdata);
    }

    public function frame() {
        $result = $this->background->backgroundByScreen('Screen 2');
        $background = $result ? BASE_URL .'assets/img/'.$result->file : null;
        $frame = $this->frame->allFrame();
        $timer = $this->setting->value('timer_frame');

        $mdata = [
            'title'         => 'Beranda - ' . NAMETITLE,
            'content'       => 'guest/frame/index',
            'extra'         => 'guest/frame/js/_js_index',
            'background'    =>  $background,
            'frame'         =>  $frame,
            'timer'         => $timer
        ];

        return view('guest/wrapper', $mdata);
    }

    public function camera($frame) {
        $result = $this->background->backgroundByScreen('Screen 2');
        $background = $result ? BASE_URL .'assets/img/'.$result->file : null;
        $timer = $this->setting->value('timer_camera');

        $mdata = [
            'title'         => 'Camera - ' . NAMETITLE,
            'content'       => 'guest/camera/index',
            'extra'         => 'guest/camera/js/_js_index',
            'background'    =>  $background,
            'timer'         => $timer,
            'frame'         => $frame
        ];

        return view('guest/wrapper', $mdata);
    }

    public function capture($frame) {
        $frame = $this->frame->getById(base64_decode($frame));
        if(!$frame) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $result = $this->background->backgroundByScreen('Screen 2');
        $background = $result ? BASE_URL .'assets/img/'.$result->file : null;
        $timer = $this->setting->value('timer_capture');

        $mdata = [
            'title'         => 'Take Photo - ' . NAMETITLE,
            'content'       => 'guest/capture/index',
            'extra'         => 'guest/capture/js/_js_index',
            'background'    =>  $background,
            'frame'         => $frame,
            'timer'         => $timer
        ];

        return view('guest/wrapper', $mdata);
    }

    public function saveRecords()
    {
        $time = time();
        $uploadDir = "assets/photobooth/$time/";

        // Buat direktori jika belum ada
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $response = ['success' => false];
        foreach ($_FILES as $key => $file) {
            if ($file['error'] === UPLOAD_ERR_OK) {
                $uploadFile = "$uploadDir/" . ($file['type'] == "image/png" ? "$key.png" : "$key.mp4");
                
                if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                    $response = [
                        'success' => true,
                        'folder'  => base64_encode($time)
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
        $result = $this->background->backgroundByScreen('Screen 2');
        $background = $result ? BASE_URL .'assets/img/'.$result->file : null;
        $timer = $this->setting->value('timer_capture');

        $mdata = [
            'title'         => 'Make Filter - ' . NAMETITLE,
            'content'       => 'guest/filter/index',
            'extra'         => 'guest/filter/js/_js_index',
            'background'    =>  $background,
            'timer'         => $timer,
            'dir'           => base64_decode($dir)
        ];

        return view('guest/wrapper', $mdata);

    }

    public function print($dir) {
        $result = $this->background->backgroundByScreen('Screen 2');
        $background = $result ? BASE_URL .'assets/img/'.$result->file : null;
        $timer = $this->setting->value('timer_capture');
        $qrcode = new Generator;

        $mdata = [
            'title'         => 'Print - ' . NAMETITLE,
            'content'       => 'guest/print/index',
            'extra'         => 'guest/print/js/_js_index',
            'background'    =>  $background,
            'timer'         => $timer,
            'dir'           => base64_decode($dir),
            'qrcode'        => $qrcode->size(250)->generate(base_url("download/$dir"))
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
