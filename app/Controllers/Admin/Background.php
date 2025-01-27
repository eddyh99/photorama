<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Background extends BaseController
{
    public function __construct()
    {   
        $this->background       = model('App\Models\Mdl_background');
	}

    public function index()
    {
        $mdata = [
            'title'     => 'Background - ' . NAMETITLE,
            'content'   => 'admin/bg/index',
            'extra'     => 'admin/bg/js/_js_index',
            'menuactive_bg'   => 'active open'
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function add()
    {
        $mdata = [
            'title'     => 'Background - ' . NAMETITLE,
            'content'   => 'admin/bg/create',
            'extra'     => 'admin/bg/js/_js_create',
            'menuactive_bg'   => 'active open'
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function store() {
        $rules = $this->validate([
            'file' => [
                'label' => 'Background',
                'rules' => 'uploaded[file]|is_image[file]|mime_in[file,image/png]'
            ],
            'display'     => [
                'label'     => 'Tampilan',
                'rules'     => 'required'
            ]
        ]);

        // Checking Validation
        if (!$rules){
            session()->setFlashdata('failed', $this->validation->listErrors());
            return redirect()->to(BASE_URL . "admin/background/add")->withInput();
        }

        $fileBg = $this->request->getFile('file');
        $screen = $this->request->getVar('display');
        if ($fileBg && $fileBg->isValid()) {
            $bgName = $screen.time() .'.png';
            $fileBg->move('assets/img/background', $bgName);
        }

        $mdata = [
            'file'    => 'background/' .$bgName,
            'display' => $screen
        ];

        $result = $this->background->insertBg($mdata);

        if ($result->code == 201) {
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL . "admin/background");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL . "admin/background/add")->withInput();
        }
    }


    // access data
    public function get_all_bg() {
        $result = $this->background->allBackground();
        echo json_encode($result);
    }

    public function destroy($id) {
        $result = $this->background->deleteById(base64_decode($id));
        if($result->code == 200){
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL."admin/background");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL."admin/background");
        }
    }
}
