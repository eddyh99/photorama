<?php

namespace App\Controllers;

use App\Controllers\Admin\Frame;

class Home extends BaseController
{

    public function __construct()
    {   
        $this->background       = model('App\Models\Mdl_background');
        $this->frame            = model('App\Models\Mdl_frame');
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
        $mdata = [
            'title'         => 'Beranda - ' . NAMETITLE,
            'content'       => 'guest/order/index',
            'extra'     => 'guest/order/js/_js_index',
            'background'    =>  $background
        ];

        return view('guest/wrapper', $mdata);
    }

    public function payment($price) {
        $price = base64_decode($price);
        if(!is_numeric($price)) return redirect()->to(BASE_URL .'order');
        $result = $this->background->backgroundByScreen('Screen 2');
        $background = $result ? BASE_URL .'assets/img/'.$result->file : null;
        $mdata = [
            'title'         => 'Beranda - ' . NAMETITLE,
            'content'       => 'guest/payment/index',
            'extra'         => 'guest/payment/js/_js_index',
            'background'    =>  $background,
            'price'         =>  $price
        ];

        return view('guest/wrapper', $mdata);
    }

    public function frame() {
        $result = $this->background->backgroundByScreen('Screen 2');
        $background = $result ? BASE_URL .'assets/img/'.$result->file : null;
        $frame = $this->frame->allFrame();

        $mdata = [
            'title'         => 'Beranda - ' . NAMETITLE,
            'content'       => 'guest/frame/index',
            'extra'         => 'guest/frame/js/_js_index',
            'background'    =>  $background,
            'frame'         =>  $frame
        ];

        return view('guest/wrapper', $mdata);
    }
}
