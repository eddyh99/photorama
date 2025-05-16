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
        $excludedFields = ['container_frame', 'container_filter', 'container_print'];

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
            // skip
            if (in_array($field, $excludedFields)) {
                continue;
            }

            $rules_bg[$field] = 'uploaded[' . $field . ']|is_image[' . $field . ']|mime_in[' . $field . ',image/jpg,image/jpeg,image/png]|max_size[' . $field . ',2048]';
        }

        $rules = $this->validate($rules_bg);
        // Jalankan validasi
        if (!$rules) {
            session()->setFlashdata('failed', $this->validation->listErrors());
            return redirect()->to(BASE_URL . "admin/background/add")->withInput();
        }


        foreach ($_FILES as $field => $file) {

            if (in_array($field, $excludedFields)) {
                $mdata[$field] = [
                    'display' => $field,
                    'cabang_id' => $cabangId,
                    'file' => 'default'
                ];
                continue;
            }

            // Jika ada file yang diupload (bukan kosong dan tidak error)
            if ($file['error'] !== UPLOAD_ERR_NO_FILE && !empty($file['tmp_name'])) {
                $bg_name = $field . "_" . $cabangId . '.png';
                $mdata[$field] = [
                    'display' => $field,
                    'cabang_id' => $cabangId,
                    'file'     => 'background/' . $bg_name
                ];
                move_uploaded_file($file['tmp_name'], FCPATH . 'assets/img/background/' . $bg_name);
            }
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
    public function get_all_bg()
    {
        $result = $this->background->allBackground();
        echo json_encode($result);
    }

    public function edit($id_cabang)
    {
        $background = $this->background->backgroundByIdCabang($id_cabang);

        $mdata = [
            'title'     => 'Background - ' . NAMETITLE,
            'content'   => 'admin/bg/edit',
            'extra'     => 'admin/bg/js/_js_create',
            'menuactive_bg'   => 'active open',
            'id_cabang' => $id_cabang,
            'nama_cabang' => $background[0]->nama_cabang ?? null,
            'backgrounds' => $background
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function update($id_cabang)
    {
        $postData = $this->request->getPost();
        $rules_bg = [];
        $mdata = [];
        $hasFile = false;
        $includedFields = ['container_frame', 'container_filter', 'container_print'];

        foreach ($_FILES as $field => $file) {

            // Pastikan ada file yang diunggah dan tidak terjadi kesalahan saat upload
            if (isset($file['name']) && $file['error'] === UPLOAD_ERR_OK) {
                // dd('masuk');
                $rules_bg[$field] = 'is_image[' . $field . ']|mime_in[' . $field . ',image/jpg,image/jpeg,image/png]|max_size[' . $field . ',2048]';
                $hasFile = true;
            }
        }

        if (!$hasFile) {
            session()->setFlashdata('failed', 'Nothing to update');
            return redirect()->to(base_url("admin/background/edit/" . $id_cabang))->withInput();
        }

        $rules = $this->validate($rules_bg);
        // Jalankan validasi
        if (!$rules) {
            session()->setFlashdata('failed', $this->validation->listErrors());
            return redirect()->to(base_url("admin/background/edit/" . $id_cabang))->withInput();
        }


        foreach ($_FILES as $field => $file) {
            if (isset($file['name']) && $file['error'] === UPLOAD_ERR_OK) {
                $bg_name = $field . "_" . $id_cabang . '.png';
                $newBg = 'assets/img/background/' . $bg_name; // Path file baru
                $oldBg = FCPATH . $newBg;

                if (file_exists($oldBg)) {
                    unlink($oldBg);
                }

                // Simpan file baru
                if (!move_uploaded_file($file['tmp_name'], $oldBg)) {
                    session()->setFlashdata('failed', "Gagal mengunggah file {$file['name']}");
                    return redirect()->to(base_url("admin/background/edit/" . $id_cabang))->withInput();
                }

                if(in_array($field, $includedFields)) {
                    $mdata[] = [
                        'cabang_id' => $this->request->getVar('idcabang'),
                        'display'   => $field,
                        'file'      => 'background/' . $bg_name
                    ];
                }
            }
        }


        $result = (object) ['code' => 201];
        // dd($mdata);
        if(!empty($mdata)) {
            $result = $this->background->updateBg($mdata);
        }

        if($result->code != 201) {
            session()->setFlashdata('failed',  $result->message);
            return redirect()->to(BASE_URL . "admin/background");
        }
        session()->setFlashdata('success', 'Background cabang ' . $postData["cabang"]  . ' berhasil diupdate');
        return redirect()->to(BASE_URL . "admin/background");
    }

    public function destroy($id)
    {
        $result = $this->background->deleteById(base64_decode($id));
        if ($result->code == 200) {
            $file_path = FCPATH . 'assets/img/' . $result->file;

            // Hapus file jika ada
            if (!empty($result->file) && file_exists($file_path)) {
                unlink($file_path);
            }
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL . "admin/background");
        } else {
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL . "admin/background");
        }
    }
}
