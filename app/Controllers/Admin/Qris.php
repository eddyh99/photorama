<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Qris extends BaseController
{
    
    public function __construct()
    {   
        $this->qris       = model('App\Models\Mdl_qris');
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
