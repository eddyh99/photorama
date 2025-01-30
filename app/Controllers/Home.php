<?php

namespace App\Controllers;

use App\Controllers\Admin\Frame;
use App\Controllers\Admin\Voucher;

class Home extends BaseController
{

    public function __construct()
    {   
        $this->background       = model('App\Models\Mdl_background');
        $this->frame            = model('App\Models\Mdl_frame');
        $this->setting       = model('App\Models\Mdl_settings');
	}

    public function testing() {

        return view('guest/script');
    }

    public function index()
    {
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

    public function payment($price) {
        $price = base64_decode($price);
        if(!is_numeric($price)) return redirect()->to(BASE_URL .'order');

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
        $result = $this->background->backgroundByScreen('Screen 2');
        $background = $result ? BASE_URL .'assets/img/'.$result->file : null;
        $timer = $this->setting->value('timer_payment');
        $mdata = [
            'title'         => 'Beranda - ' . NAMETITLE,
            'content'       => 'guest/payment/index',
            'extra'         => 'guest/payment/js/_js_index',
            'background'    =>  $background,
            'price'         =>  $price,
            'timer'         => $timer
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
            'timer'         => $timer
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
}
