<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Frame extends BaseController
{

    public function __construct()
    {   
        $this->frame       = model('App\Models\Mdl_frame');
	}
    public function index()
    {
        $mdata = [
            'title'     => 'Bingkai - ' . NAMETITLE,
            'content'   => 'admin/frame/index',
            'extra'     => 'admin/frame/js/_js_index',
            'menuactive_fr'   => 'active open'
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function add()
    {
        $mdata = [
            'title'     => 'Frame - ' . NAMETITLE,
            'content'   => 'admin/frame/create',
            'extra'     => 'admin/frame/js/_js_create',
            'menuactive_frame'   => 'active open'
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function store() {
        $rules = $this->validate([
            'file' => [
                'label' => 'Frame',
                'rules' => 'uploaded[file]|is_image[file]|mime_in[file,image/png]'
            ],
            'name'     => [
                'label'     => 'Nama',
                'rules'     => 'required'
            ],
            'koordinat'     => [
                'label'     => 'Koordinat',
                'rules'     => 'required'
            ]
        ]);

        // Checking Validation
        if (!$rules){
            session()->setFlashdata('failed', $this->validation->listErrors());
            return redirect()->to(BASE_URL . "admin/frame/add")->withInput();
        }

        $fileFrame = $this->request->getFile('file');
        $name = $this->request->getVar('name');
        if ($fileFrame && $fileFrame->isValid()) {
            $frameName = $name.time() .'.png';
            $fileFrame->move('assets/img/frame', $frameName);
        }

        $mdata = [
            'frame' => [
                'file'    => 'frame/' . $frameName,
                'name' => $name
            ],
            'koordinat' => json_decode($this->request->getVar('koordinat')) ?? []
        ];

        $result = $this->frame->insertFrame($mdata);

        if ($result->code == 201) {
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL . "admin/frame");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL . "admin/frame/add")->withInput();
        }
    }

    // access data
    public function get_all_frame() {
        $result = $this->frame->allFrame();
        echo json_encode($result);
    }

    public function destroy($id) {
        $result = $this->frame->deleteById(base64_decode($id));
        if($result->code == 200){
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL."admin/frame");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL."admin/frame");
        }
    }
}
