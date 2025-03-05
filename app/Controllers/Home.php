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
        $payment_status = $this->cabang->get_status('payment_status', $this->id_cabang)->message;
        $mdata = [
            'title'         => 'Beranda - ' . NAMETITLE,
            'extra'         => 'guest/beranda/js/_js_index',
            'content'       => 'guest/beranda/index',
            'payment_on' => filter_var($payment_status, FILTER_VALIDATE_BOOLEAN),
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
        $bg_container = $this->background->backgroundByScreen('container_frame', $this->id_cabang);
        $frame = $this->frame->allFrame();
        $timer = $this->timer->get_byCabang_andScreen('screen_frame', $this->id_cabang);

        $mdata = [
            'title'         => 'Beranda - ' . NAMETITLE,
            'content'       => 'guest/frame/index',
            'extra'         => 'guest/frame/js/_js_index',
            'background'    =>  $background ?? null,
            'bg_container'  =>  BASE_URL . 'assets/img/' . ($bg_container ?? 'background/default.jpg'),
            'frame'         =>  $frame,
            'timer'         => $timer
        ];

        return view('guest/wrapper', $mdata);
    }

    public function camera() {
        $background = $this->background->backgroundByScreen('screen_select_camera', $this->id_cabang);
        $timer = $this->timer->get_byCabang_andScreen('screen_select_camera', $this->id_cabang);

        $mdata = [
            'title'         => 'Camera - ' . NAMETITLE,
            'content'       => 'guest/camera/index',
            'extra'         => 'guest/camera/js/_js_index',
            'background'    =>  $background ?? null,
            'timer'         => $timer
        ];

        return view('guest/wrapper', $mdata);
    }

    public function capture() {
        $background = $this->background->backgroundByScreen('screen_capture_photo', $this->id_cabang);
        $timer = $this->timer->get_byCabang_andScreen('screen_capture_photo', $this->id_cabang);
        $is_retake = $this->cabang->get_status('retake_status', $this->id_cabang)->message;

        $mdata = [
            'title'         => 'Take Photo - ' . NAMETITLE,
            'content'       => 'guest/capture/index',
            'extra'         => 'guest/capture/js/_js_index',
            'background'    =>  $background ?? null,
            'retake'        => filter_var($is_retake, FILTER_VALIDATE_BOOLEAN),
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
                $uploadFile = "$uploadDir/" . ($file['type'] == "image/jpeg" ? "$key.jpg" : "$key.mp4");
                
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
    
        $success = move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . "photos.jpg");
        return json_encode(['success' => $success]);
    }

    public function filter($dir) {
        $background = $this->background->backgroundByScreen('screen_filter', $this->id_cabang);
        $bg_container = $this->background->backgroundByScreen('container_filter', $this->id_cabang);
        $timer = $this->timer->get_byCabang_andScreen('screen_filter', $this->id_cabang);

        $mdata = [
            'title'         => 'Make Filter - ' . NAMETITLE,
            'content'       => 'guest/filter/index',
            'extra'         => 'guest/filter/js/_js_index',
            'background'    =>  $background ?? null,
            'bg_container'  =>  BASE_URL . 'assets/img/' . ($bg_container ?? 'background/default.jpg'),
            'timer'         => $timer,
            'dir'           => base64_decode($dir)
        ];

        return view('guest/wrapper', $mdata);

    }

    public function print($dir) {
        $background = $this->background->backgroundByScreen('screen_print', $this->id_cabang);
        $bg_container = $this->background->backgroundByScreen('container_print', $this->id_cabang);
        $timer = $this->timer->get_byCabang_andScreen('screen_print', $this->id_cabang);
        $qrcode = new Generator;
        $auto_print = $this->setting->value('auto_print');
        $dir = base64_decode($dir);
        $videos = glob(FCPATH . 'assets/photobooth/'. $dir . '/video*', GLOB_BRACE);
        $videos = array_map(function ($video) use ($dir) {
            return BASE_URL . 'assets/photobooth/' . $dir . '/' . basename($video);
        }, $videos);

        $mdata = [
            'title'         => 'Print - ' . NAMETITLE,
            'content'       => 'guest/print/index',
            'extra'         => 'guest/print/js/_js_index',
            'background'    =>  $background ?? null,
            'bg_container'  =>  BASE_URL . 'assets/img/' . ($bg_container ?? 'background/default.jpg'),
            'timer'         => $timer,
            'dir'           => $dir,
            'videos'        => $videos,
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
            'extra'         => 'guest/finish/js/_js_index',
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

    public function browseFiles($folder)
    {
        $path = FCPATH . "assets/photobooth/" .$folder;
        
        if (!is_dir($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $files = array_diff(scandir($path), array('.', '..'));

        $mdata = [
            'title'         => 'My Files - ' . NAMETITLE,
            'content'       => 'guest/browse/index',
            'files'         =>  $files,
            'event'         => $folder
        ];

        return view('guest/wrapper', $mdata);
    }

    public function get_coordinates() {
        $frame = $this->request->getVar('frame');
        $result = $this->frame->getByFile(urldecode($frame));
        echo json_encode($result);
    }

    public function get_payment_status() {
        $payment_status = $this->cabang->get_status('payment_status', $this->id_cabang)->message ?? true;
        $result = ['status' => filter_var($payment_status, FILTER_VALIDATE_BOOLEAN)];
        return $this->response->setJSON($result);
    }

    public function download_all($dir)
    {
        // Tentukan path folder yang akan di-zip
        $folderPath = FCPATH . 'assets/photobooth/' . $dir;

        // Pastikan folder ada
        if (!is_dir($folderPath)) {
            return redirect()->to('/')->with('error', 'Folder tidak ditemukan.');
        }

        // Nama file zip yang akan dihasilkan
        $zipFileName = $dir . '.zip';
        $zipFilePath = $folderPath . $zipFileName;

        // Membuat objek ZipArchive
        $zip = new \ZipArchive();

        // Buat zip file
        if ($zip->open($zipFilePath, \ZipArchive::CREATE) !== TRUE) {
            return redirect()->to('/')->with('error', 'Gagal membuat zip file.');
        }

        // Menambahkan folder ke dalam zip
        $this->addFolderToZip($folderPath, $zip, $dir);

        // Tutup zip file
        $zip->close();

        // Kirim file zip ke browser
        return $this->response->download($zipFilePath, null)->setFileName($zipFileName);
    }

    // Fungsi untuk menambahkan folder dan isinya ke dalam zip
    private function addFolderToZip($folderPath, $zip, $zipFolderName)
    {
        // Ambil daftar file dan folder dalam folder
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($folderPath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                // Ambil nama relatif file terhadap folder
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($folderPath) + 1);

                // Tambahkan file ke zip
                $zip->addFile($filePath, $zipFolderName . '/' . $relativePath);
            }
        }
    }

}
