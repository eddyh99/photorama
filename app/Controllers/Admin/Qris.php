<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Qris extends BaseController
{
    
    public function __construct()
    {   
        $this->qris       = model('App\Models\Mdl_qris');
        $this->cabang       = model('App\Models\Mdl_cabang');
	}

    public function index()
    {
        $mdata = [
            'title'     => 'Qris - ' . NAMETITLE,
            'content'   => 'admin/qris/index',
            'extra'     => 'admin/qris/js/_js_index',
            'menuactive_qr'   => 'active open'
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    
    public function add()
    {
        $cabang = $this->cabang->getCabang_notHaving_qris();
        $mdata = [
            'title'     => 'Background - ' . NAMETITLE,
            'content'   => 'admin/qris/create',
            'extra'     => 'admin/qris/js/_js_create',
            'menuactive_bg'   => 'active open',
            'cabang'     => $cabang
        ];

        return view('admin/layout/wrapper', $mdata);
    }


    public function store() {
        $rules = $this->validate([
            'background' => [
                'label' => 'Background Qris',
                'rules' => 'uploaded[background]|is_image[background]|mime_in[background,image/png]'
            ],
            'cabang_id'     => [
                'label'     => 'Cabang',
                'rules'     => 'required'
            ]
        ]);

        // Checking Validation
        if (!$rules){
            session()->setFlashdata('failed', $this->validation->listErrors());
            return redirect()->to(BASE_URL . "admin/qris/add")->withInput();
        }

        $bg = $this->request->getFile('background');
        if ($bg && $bg->isValid()) {
            $bgName = 'Qris-'.time() .'.png';
            $bg->move('assets/img/background-qris', $bgName);
        }

        $mdata = [
            'background'    => 'background-qris/' . $bgName,
            'cabang_id' => $this->request->getVar('cabang_id')
        ];

        $result = $this->qris->insertQrisBg($mdata);

        if ($result->code == 201) {
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL . "admin/qris");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL . "admin/qris/add")->withInput();
        }
    }

    public function get_all_bg() {
        $result = $this->qris->allBg();
        echo json_encode($result);
    }

    public function destroy($id) {
        $result = $this->qris->deleteById(base64_decode($id));
        if($result->code == 200){
            $file_path = FCPATH . 'assets/img/' . $result->file;

            // Hapus file jika ada
            if (!empty($result->file) && file_exists($file_path)) {
                unlink($file_path);
            }
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL."admin/qris");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL."admin/qris");
        }
    }
}
