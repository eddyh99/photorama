<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

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
