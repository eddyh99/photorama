<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Price extends BaseController
{
    public function __construct()
    {   
        $this->price       = model('App\Models\Mdl_Price');
	}

    public function index()
    {

        $mdata = [
            'title'     => 'Price - ' . NAMETITLE,
            'content'   => 'admin/price/index',
            'extra'     => 'admin/price/js/_js_index',
            'menuactive_price'   => 'active open',
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function get_all() {
        $result = $this->price->allPrices();
        echo json_encode($result);
    }

    public function destroy($id) {
        $result = $this->price->deleteById(base64_decode($id));
        if($result->code == 200){
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL."admin/price");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL."admin/price");
        }
    }
}
