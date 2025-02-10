<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Branch extends BaseController
{
    public function __construct()
    {   
        $this->cabang       = model('App\Models\Mdl_Cabang');
	}

    public function index()
    {
        $mdata = [
            'title'     => 'Cabang - ' . NAMETITLE,
            'content'   => 'admin/cabang/index',
            'extra'     => 'admin/cabang/js/_js_index',
            'menuactive_cabang'   => 'active open'
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function destroy($id) {
        $result = $this->cabang->deleteById(base64_decode($id));
        if($result->code == 200){
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL."admin/branch");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL."admin/branch");
        }
    }

    public function get_all() {
        $result = $this->cabang->allCabang();
        echo json_encode($result);
    }
}
