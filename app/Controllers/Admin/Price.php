<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Price extends BaseController
{
    public function __construct()
    {   
        $this->price       = model('App\Models\Mdl_price');
        $this->cabang       = model('App\Models\Mdl_cabang');
        $this->setting       = model('App\Models\Mdl_settings');
	}

    public function index()
    {

        $mdata = [
            'title'     => 'Price - ' . NAMETITLE,
            'content'   => 'admin/price/index',
            'extra'     => 'admin/price/js/_js_index',
            'menuactive_price'   => 'active open'
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
             'menuactive_price'   => 'active open',
            'cabang'    => $cabang
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function edit($id)
    {
        $price = $this->price->getBy_Id(base64_decode($id));
        $price->id = $id;
        $cabang = $this->cabang->allCabang();
        $mdata = [
            'title'     => 'Price - ' . NAMETITLE,
            'content'   => 'admin/price/edit',
            'extra'     => 'admin/price/js/_js_create',
            'menuactive_bg'   => 'active open',
            'price'     => $price,
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

    public function update($id) {
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
            return redirect()->to(BASE_URL . "admin/price/edit/$id")->withInput();
        }


        $mdata = [
            'harga' => $this->request->getVar('harga'),
            'cabang_id' => $this->request->getVar('cabang_id'),
        ];
        $result = $this->price->updatePrice($mdata, base64_decode($id));

        if ($result->code == 201) {
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL . "admin/price");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL . "admin/price/edit/$id")->withInput();
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

    public function image() {
        $image_order = $this->setting->value('img_order');
        $mdata = [
            'title'     => 'Image Order - ' . NAMETITLE,
            'content'   => 'admin/price/image',
            'extra'     => 'admin/price/js/_js_image',
            'menuactive_price'   => 'active open',
            'image'     => $image_order
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function image_edit() {
        $rules = $this->validate([
            'img_order' => [
                'label' => 'Image Order',
                'rules' => 'uploaded[img_order]|is_image[img_order]|mime_in[img_order,image/png]'
            ]
        ]);

        // Checking Validation
        if (!$rules){
            session()->setFlashdata('failed', $this->validation->listErrors());
            return redirect()->to(BASE_URL . "admin/price/image")->withInput();
        }

        $file = $this->request->getFile('img_order');
        if ($file && $file->isValid()) {
            $file_name = "image_order.png";
            $file->move('assets/img/order', $file_name, true);
        }

        $result = $this->setting->store('img_order', 'order/' . $file_name);

        if ($result->code == 201) {
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL . "admin/price");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL . "admin/price/image")->withInput();
        }
    }
}
