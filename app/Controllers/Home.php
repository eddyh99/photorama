<?php

namespace App\Controllers;

use App\Controllers\Admin\Voucher;
use SimpleSoftwareIO\QrCode\Generator;
use Dompdf\Dompdf;
use Dompdf\Options;

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
        $this->camera       = model('App\Models\Mdl_camera');
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
    
    public function sign()
    {
        // Make sure it’s a POST request
        if ($this->request->getMethod() !== 'post') {
            return $this->fail('Invalid request method.');
        }
    
        // Get JSON data from POST body
        $json = $this->request->getJSON();
        if (!$json || !isset($json->data)) {
            return $this->fail('Missing data to sign.');
        }
    
        $requestData = $json->data;
        $cabang = $this->cabang->getCabang_byId($this->id_cabang);
        $privateKeyPath = $cabang->private_key ? WRITEPATH . $cabang->private_key : null; // adjust if needed
    
        if (!file_exists($privateKeyPath)) {
            return $this->fail('Private key not found.');
        }
    
        $privateKey = openssl_get_privatekey(file_get_contents($privateKeyPath));
    
        if (!$privateKey) {
            return $this->fail('Unable to load private key.');
        }
    
        $signature = null;
        $success = openssl_sign($requestData, $signature, $privateKey, 'sha512');
    
        if ($success && $signature) {
            return $this->response
                ->setHeader('Content-Type', 'text/plain')
                ->setBody(base64_encode($signature));
        }
    
        return $this->fail('Error signing message.');
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
            'image'         => BASE_URL . 'assets/img/' . ($image_order ?? 'order/bg-default.jpg'),
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
        $frame = $this->frame->getByCabang($this->id_cabang);
        $frames = [];
        if(!empty($frame)) {
            foreach ($frame as $row) {
                $frameId = $row->id;
    
                // Jika frame belum ada di array, tambahkan data frame
                if (!isset($frames[$frameId])) {
                    $frames[$frameId] = (object) [
                        'id' => $row->id,
                        'name' => $row->name,
                        'file' => $row->file,
                        'coordinates' => [] // Array kosong untuk koordinat
                    ];
                }
    
                // Tambahkan koordinat ke dalam array frame yang sesuai
                $frames[$frameId]->coordinates[] = (object) [
                    'x' => $row->x,
                    'y' => $row->y,
                    'width' => $row->width,
                    'height' => $row->height,
                    'rotation' => $row->rotation,
                    'index' => $row->index
                ];
            }
        }
        $timer = $this->timer->get_byCabang_andScreen('screen_frame', $this->id_cabang);


        $mdata = [
            'title'         => 'Beranda - ' . NAMETITLE,
            'content'       => 'guest/frame/index',
            'extra'         => 'guest/frame/js/_js_index',
            'background'    =>  $background ?? null,
            'bg_container'  =>  BASE_URL . 'assets/img/' . ($bg_container ?? 'background/default.jpg'),
            'frame'         =>  $frames,
            'timer'         => $timer
        ];

        return view('guest/wrapper', $mdata);
    }

    public function camera() {
        $background = $this->background->backgroundByScreen('screen_select_camera', $this->id_cabang);
        $timer = $this->timer->get_byCabang_andScreen('screen_select_camera', $this->id_cabang);
        $camera_rotation = $this->camera->getBy_cabang($this->id_cabang);

        $mdata = [
            'title'         => 'Camera - ' . NAMETITLE,
            'content'       => 'guest/camera/index',
            'extra'         => 'guest/camera/js/_js_index',
            'background'    =>  $background ?? null,
            'timer'         => $timer,
            'camera_rotation' => $camera_rotation ?? []
        ];

        return view('guest/wrapper', $mdata);
    }

    public function capture() {
        $background = $this->background->backgroundByScreen('screen_capture_photo', $this->id_cabang);
        $timer = $this->timer->get_byCabang_andScreen('screen_capture_photo', $this->id_cabang);
        $countdown = $this->timer->get_byCabang_andScreen('countdown', $this->id_cabang);
        $is_retake = $this->cabang->get_status('retake_status', $this->id_cabang)->message;
        $camera_rotation = $this->camera->getBy_cabang($this->id_cabang);

        $mdata = [
            'title'         => 'Take Photo - ' . NAMETITLE,
            'content'       => 'guest/capture/index',
            'extra'         => 'guest/capture/js/_js_index',
            'background'    =>  $background ?? null,
            'retake'        => filter_var($is_retake, FILTER_VALIDATE_BOOLEAN),
            'timer'         => $timer,
            'countdown'     => $countdown,
            'camera_rotation' => $camera_rotation ?? []
        ];

        return view('guest/wrapper', $mdata);
    }

    public function saveRecords()
    {
        $time = time();
        $cabang = $this->cabang->getCabang_byId($this->id_cabang);
        $is_event = filter_var($cabang->is_event, FILTER_VALIDATE_BOOLEAN);
        $nama_cabang= $cabang->nama_cabang ?? 'unknown';
        $uploadDir = "assets/photobooth/" . ($is_event ? "$nama_cabang/" : '' ) .$nama_cabang. "-$time/";
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
                        'folder'  => base64_encode(($is_event ? "$nama_cabang/" : '') . $nama_cabang . '-' .$time),
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
    
        // Jika direktori tidak ada, kembalikan false
        if (!is_dir($uploadDir)) {
            return json_encode(['success' => false, 'message' => 'Folder tidak ditemukan']);
        }
    
        $success = false; // Default gagal
    
        // Proses unggah foto jika ada
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $success = move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . "photos.jpg") || $success;
        }
    
        // Proses unggah video jika ada
        if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
            $success = move_uploaded_file($_FILES['video']['tmp_name'], $uploadDir . "video.mp4") || $success;
        }
    
        return json_encode(['success' => $success]);
    }
    

    public function filter($dir) {
        $background = $this->background->backgroundByScreen('screen_filter', $this->id_cabang);
        $bg_container = $this->background->backgroundByScreen('container_filter', $this->id_cabang);
        $timer = $this->timer->get_byCabang_andScreen('screen_filter', $this->id_cabang);
        $path = FCPATH . "assets/photobooth/".base64_decode($dir) . "/";
        
        $input = $path . 'video.mp4';
        $temp = $path . 'temp.mp4';
        $ffmpeg = '/usr/bin/ffmpeg';
        
        $command = "$ffmpeg -y -i \"{$input}\" -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -shortest -vf \"scale=1024:768,format=yuv420p\" -c:v libx264 -profile:v baseline -level 3.0 -pix_fmt yuv420p -c:a aac -b:a 128k -crf 26 -preset fast -movflags +faststart \"{$temp}\" 2>&1 && mv -f \"{$temp}\" \"{$input}\"";

        exec($command, $output, $return_var);
        // echo "<pre>";
        // print_r($output);
        // echo "</pre>";
        
        // if ($return_var === 0) {
        //     echo "Video converted and replaced successfully.";
        // } else {
        //     echo "Conversion failed.";
        // }
        // die;
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
        $print = $this->cabang->get_status('print_status', $this->id_cabang)->message;
        $printer = $this->cabang->get_status('printer_name', $this->id_cabang)->message;;
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
            'print'         => filter_var($print, FILTER_VALIDATE_BOOLEAN),
            'printer'       => !empty($printer) ? $printer : DEFAULT_PRINTER,
            'qrcode'        => $qrcode->size(250)->generate(base_url("download/" . base64_encode($dir)))
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
            'files'         =>  $files,
            'folder'        =>  base64_decode($folder),
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

    public function cetakPDF($img, $print)
    {
        $date = date('Y-m-d-His');
        $rotatedPath = null;

        // Konfigurasi Dompdf
        $options = new Options();
        $options->set('defaultFont', 'NotaFonts');
        
        // Buat instance Dompdf
        $dompdf = new Dompdf($options);
        $image = FCPATH . 'assets/photobooth/' . base64_decode($img) . '/photos.jpg';

        if (!file_exists($image)) {
            return json_encode(["status" => "false"]);
        }

        list($width, $height) = getimagesize($image);

        // Jika landscape, putar gambar
        if ($width > $height) {
            $imageResource = imagecreatefromjpeg($image);
            $rotatedImage = imagerotate($imageResource, 90, 0);
            $rotatedPath = FCPATH . 'assets/photobooth/temp/' . base64_decode($img) . '.jpg';
            imagejpeg($rotatedImage, $rotatedPath, 100); // Simpan gambar baru
            imagedestroy($imageResource);
            imagedestroy($rotatedImage);
            $image = $rotatedPath;
        }

        // Konversi gambar ke Base64
        $imageData = base64_encode(file_get_contents($image));
        $imageSrc = "data:.jpg;base64,$imageData";
    
        // Generate HTML dengan jumlah halaman sesuai $print
        $html = '<html><head>
        <style>
            body { margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
            @page { size: 4in 6in; margin: 0; }
            img { width: 4in; height: 6in; object-fit: contain; } 
        </style>
        </head><body>';

        for ($i = 0; $i < $print; $i++) {
            $html .= '<img src="' . $imageSrc . '" alt="Gambar 4R">';
            if ($i < $print - 1) {
                $html .= '<div style="page-break-before: always;"></div>'; // Pisahkan halaman
            }
        }

        $html .= '</body></html>';
        
        $dompdf->loadHtml($html);
        
        // Set ukuran kertas ke 4R (4x6 inci)
        $dompdf->setPaper([4 * 25.4, 6 * 25.4]);
        
        // Render PDF
        $dompdf->render();
        
        // Simpan file PDF ke dalam folder
        $folderPath = 'assets/pdf/';
        $fileName = "photorama-$date.pdf";
        
        // Pastikan folder tersedia
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
        
        file_put_contents($folderPath . $fileName, $dompdf->output());

        // Eksekusi print
        // exec("lp '$folderPath . $fileName' > /dev/null 2>&1 &");
        // unlink($folderPath . $fileName); //hapus file pdf

        // hapus foto yang di rotasi
        if (file_exists($rotatedPath)) {
            unlink($rotatedPath);
        }

        // Return response
        return json_encode([
            "status" => "success",
            "pdf_url" => BASE_URL . $folderPath . $fileName
        ]);
    }

}
