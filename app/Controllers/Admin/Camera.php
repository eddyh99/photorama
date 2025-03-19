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
        $camera = $this->camera->getBy_Id(base64_decode($id_cabang));
        $mdata = [
            'title'     => 'Cabang - ' . NAMETITLE,
            'content'   => 'admin/camera/edit',
            'extra'     => 'admin/camera/js/_js_create',
            'menuactive_bg'   => 'active open',
            'camera'    => $camera
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function get_all() {
        $result = $this->camera->allCamera();
        return json_encode($result);
    }

    public function destroy($id) {
        $result = $this->camera->deleteById(base64_decode($id));
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

    public function update($id) {
        $rules = $this->validate([
            'camera1' => 'required|integer',
            'camera2' => 'required|integer',
        ]);

        // Checking Validation
        if (!$rules){
            session()->setFlashdata('failed', $this->validation->listErrors());
            return redirect()->to(BASE_URL . "admin/qris/add")->withInput();
        }

        $mdata = [
            'camera1'    => $this->request->getVar('camera1'),
            'camera2' =>  $this->request->getVar('camera2')
        ];

        $result = $this->camera->updateCamera($mdata, $id);

        if ($result->code == 201) {
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL . "admin/camera");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL . "admin/camera/edit/" .$id)->withInput();
        }
    }
}
