<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Price extends BaseController
{
    public function __construct()
    {   
        $this->price       = model('App\Models\Mdl_Price');
        $this->cabang       = model('App\Models\Mdl_cabang');
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

    public function add()
    {
        $cabang = $this->cabang->getCabang_notHaving_price();
        $mdata = [
            'title'     => 'Price - ' . NAMETITLE,
            'content'   => 'admin/price/create',
            'extra'     => 'admin/price/js/_js_create',
            'menuactive_bg'   => 'active open',
            'cabang'    => $cabang
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function store() {
        $rules = $this->validate([
            'harga' => [
                'label' => 'Harga',
                'rules' => 'required'
            ],
            'cabang_id' => [
                'label' => 'Cabang',
                'rules' => 'required'
            ]
        ]);

        // Checking Validation
        if (!$rules){
            session()->setFlashdata('failed', $this->validation->listErrors());
            return redirect()->to(BASE_URL . "admin/price/add")->withInput();
        }


        $mdata = [
            'harga' => $this->request->getVar('harga'),
            'cabang_id' => $this->request->getVar('cabang_id'),
        ];

        $result = $this->price->insertPrice($mdata);

        if ($result->code == 201) {
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL . "admin/price");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL . "admin/price/add")->withInput();
        }
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
