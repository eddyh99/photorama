<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Services;

class Background extends BaseController
{
    public function __construct()
    {   
        $this->background       = model('App\Models\Mdl_background');
        $this->cabang       = model('App\Models\Mdl_cabang');
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
        $cabang = $this->cabang->getCabang_notHaving_bg();
        $mdata = [
            'title'     => 'Background - ' . NAMETITLE,
            'content'   => 'admin/bg/create',
            'extra'     => 'admin/bg/js/_js_create',
            'menuactive_bg'   => 'active open',
            'cabang'     => $cabang
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function store()
    {

        $postData = $this->request->getPost();
        $mdata = [];

        // validasi cabang
        if (!isset($postData['cabang_id'])) {
            session()->setFlashdata('failed', 'Cabang ID harus diisi!');
            return redirect()->to(base_url("admin/background/add"))->withInput();
        }

        $cabangId = $postData['cabang_id'];
        unset($postData['cabang_id']); //hapus cabang_id

        // Loop semua input selain cabang_id untuk menyusun array data
        $rules_bg = [];
        foreach ($_FILES as $field => $file) {
            // Pastikan ada file yang diunggah dan tidak terjadi kesalahan saat upload
            if (isset($file['name']) && $file['error'] === UPLOAD_ERR_OK) {
                $rules_bg[$field] = 'uploaded[' . $field . ']|is_image[' . $field . ']|mime_in[' . $field . ',image/jpg,image/jpeg,image/png]|max_size[' . $field . ',2048]';

                $mdata[$field] = [
                    'display' => $field,
                    'cabang_id' => $cabangId
                ];
            } else {
                session()->setFlashdata('failed', 'Harap lengkapi semua background screen');
                return redirect()->to(base_url("admin/background/add"))->withInput();
            }
        }

        $rules = $this->validate($rules_bg);
        // Jalankan validasi
        if (!$rules) {
            session()->setFlashdata('failed', $this->validation->listErrors());
            return redirect()->to(BASE_URL . "admin/background/add")->withInput();
        }


        foreach ($_FILES as $field => $file) {
            $bg_name = "$field" . time() . '.png';
            $mdata[$field]['file'] = 'background/' . $bg_name;
            move_uploaded_file($file['tmp_name'], FCPATH . 'assets/img/background/' . $bg_name);
        }

        // dd($mdata);

        $result = $this->background->insertBg($mdata);

        if ($result->code == 201) {
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL . "admin/background");
        } else {
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
            $file_path = FCPATH . 'assets/img/' . $result->file;

            // Hapus file jika ada
            if (!empty($result->file) && file_exists($file_path)) {
                unlink($file_path);
            }
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL."admin/background");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL."admin/background");
        }
    }
}
