<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Services;

class Camera extends BaseController
{
    public function __construct()
    {
        $this->camera = model('App\Models\Mdl_camera');
        $this->cabang = model('App\Models\Mdl_cabang');
    }
    public function index()
    {
        $mdata = [
            'title'     => 'Camera - ' . NAMETITLE,
            'content'   => 'admin/camera/index',
            'extra'     => 'admin/camera/js/_js_index',
            'menuactive_camera'   => 'active open'
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function add()
    {
        $cabang = $this->cabang->getCabang_notHaving_camera();
        $mdata = [
            'title'     => 'Camera - ' . NAMETITLE,
            'content'   => 'admin/camera/create',
            'extra'     => 'admin/camera/js/_js_create',
            'menuactive_camera'   => 'active open',
            'cabang'    => $cabang
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function edit($id_cabang)
    {
        $cabang = $this->cabang->allCabang();
        $camera = $this->camera->getBy_cabang(base64_decode($id_cabang));
        $mdata = [
            'title'     => 'Cabang - ' . NAMETITLE,
            'content'   => 'admin/camera/edit',
            'extra'     => 'admin/camera/js/_js_create',
            'menuactive_bg'   => 'active open',
            'cabang'    => $cabang,
            'camera'    => $camera
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function get_all() {
        $result = $this->camera->allCamera();
        return json_encode($result);
    }

    public function destroy($id) {
        $result = $this->timer->deleteById(base64_decode($id));
        if (@$result->code!=201){
            session()->setFlashdata('failed', $result->message);
	    } else {
            session()->setFlashdata('success', $result->message);
        }

        return redirect()->to(BASE_URL. 'admin/camera');
    }

    public function store() {
        $validation = Services::validation(); // Ambil instance validasi
        $postData = $this->request->getPost();

        $mdata = [];
        // validasi cabang
        if (!isset($postData['cabang_id'])) {
            session()->setFlashdata('failed', 'Cabang ID harus diisi!');
            return redirect()->to(base_url("admin/camera/set"))->withInput();
        }

        // Loop semua input selain cabang_id untuk menyusun array data
        $validation->setRules([
            'camera1' => 'required',
            'camera2' => 'required',
            'cabang_id' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            session()->setFlashdata('failed', $validation->listErrors());
            return redirect()->to(base_url("admin/camera/set"))->withInput();
        }
        
        // Prepare data for insertion
        $mdata = [
            "camera1" => (string) $postData['camera1'],
            "camera2" => (string) $postData['camera2'],
            "cabang_id" => (string) $postData['cabang_id']
        ];

        $result = $this->camera->insertcamera($mdata);

        if ($result->code == 201) {
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL . "admin/camera");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL . "admin/camera/set")->withInput();
        }
    }

    public function update() {
        $validation = Services::validation(); // Ambil instance validasi
        $postData = $this->request->getPost();
        $mdata = [];

        // validasi cabang
        if (!isset($postData['cabang_id'])) {
            session()->setFlashdata('failed', 'Cabang ID harus diisi!');
            return redirect()->to(base_url("admin/camera/set"))->withInput();
        }

        $cabangId = $postData['cabang_id'];
        unset($postData['cabang_id']); //hapus cabang_id

        // Loop semua input selain cabang_id untuk menyusun array data
        foreach ($postData as $field => $value) {
            $validation->setRule($field, ucfirst(str_replace('_', ' ', $field)), 'required');

            // Setiap screen menjadi array sendiri dengan cabang_id
            $mdata[] = [
                "display" => (string) $field,
                "time"    => $value,
                "cabang_id" => (string) $cabangId
            ];
        }

        // Jalankan validasi
        if (!$validation->withRequest($this->request)->run()) {
            session()->setFlashdata('failed', $validation->listErrors());
            return redirect()->to(base_url("admin/camera/set"))->withInput();
        }

        $result = $this->camera->updatecamera($mdata);

        if ($result->code == 201) {
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL . "admin/camera");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL . "admin/camera/set")->withInput();
        }
    }
}
