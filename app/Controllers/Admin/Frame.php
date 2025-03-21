<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use PhpParser\Node\Expr\FuncCall;

class Frame extends BaseController
{

    public function __construct()
    {   
        $this->frame       = model('App\Models\Mdl_frame');
        $this->cabang       = model('App\Models\Mdl_cabang');
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
        $cabang = $this->cabang->allCabang();
        $mdata = [
            'title'     => 'Frame - ' . NAMETITLE,
            'content'   => 'admin/frame/create',
            'extra'     => 'admin/frame/js/_js_create',
            'cabang'    => $cabang,
            'menuactive_fr'   => 'active open'
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function edit($id)
    {
        $frame = $this->frame->getById(base64_decode($id));

        $mdata = [
            'title'     => 'Frame - ' . NAMETITLE,
            'content'   => 'admin/frame/edit',
            'extra'     => 'admin/frame/js/_js_create',
            'frame'    => $frame[0],
            'id_frame'  => $id,
            'menuactive_fr'   => 'active open'
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function store() {
        $rules = $this->validate([
            'cabang_id' => [
                'label' => 'Cabang',
                'rules' => 'required|is_array'
            ],
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

        $cabang = $this->request->getVar('cabang_id');

        foreach ($cabang as $cab) {
            $mdata = [
                'frame' => [
                    'file'      => 'frame/' . $frameName,
                    'name'      => $name,
                    'cabang_id' => $cab
                ],
                'koordinat' => json_decode($this->request->getVar('koordinat')) ?? []
            ];

            $result = $this->frame->insertFrame($mdata);
            if ($result->code != 201) {
                session()->setFlashdata('failed', 'Gagal menyimpan frame');
                return redirect()->to(BASE_URL . "admin/frame/add");
            }
        }

        session()->setFlashdata('success', "Berhasil menyimpan frame");
        return redirect()->to(BASE_URL . "admin/frame");
    }

    public function update($id) {
        $rules = $this->validate([
            'file' => [
                'label' => 'Frame',
                'rules' => 'uploaded[file]|is_image[file]|mime_in[file,image/png]',
                'errors' => [
                    'uploaded' => 'Pastikan frame diperbarui.',
                ]
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
            return redirect()->to(BASE_URL . "admin/frame/edit/$id")->withInput();
        }

        $fileFrame = $this->request->getFile('file');
        $name = $this->request->getVar('name');
        if ($fileFrame && $fileFrame->isValid()) {
            $frameName = basename($this->request->getVar('old_frame'));
            $fileFrame->move('assets/img/frame', $frameName, true);
        }

        $mdata = [
            'frame' => [
                'id' => base64_decode($id),
                'name' => $name,
            ],
            'koordinat' => json_decode($this->request->getVar('koordinat')) ?? []
        ];

        $result = $this->frame->updateFrame($mdata);

        if ($result->code == 201) {
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL . "admin/frame");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL . "admin/frame/edit/$id")->withInput();
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
            $file_path = FCPATH . 'assets/img/' . $result->file;

            // Hapus file jika ada
            if (!empty($result->file) && file_exists($file_path)) {
                unlink($file_path);
            }
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL."admin/frame");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL."admin/frame");
        }
    }
}
