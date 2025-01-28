<?php

namespace App\Controllers;


class Home extends BaseController
{

    public function __construct()
    {   
        $this->background       = model('App\Models\Mdl_background');
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
}
