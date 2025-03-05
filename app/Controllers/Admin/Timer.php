<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Services;

class Timer extends BaseController
{
    public function __construct()
    {
        $this->setting = model('App\Models\Mdl_settings');
        $this->timer = model('App\Models\Mdl_timer');
        $this->cabang       = model('App\Models\Mdl_cabang');
    }
    public function index()
    {
        $mdata = [
            'title'     => 'TImer - ' . NAMETITLE,
            'content'   => 'admin/timer/index',
            'extra'     => 'admin/timer/js/_js_index',
            'menuactive_timer'   => 'active open'
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function set()
    {
        $cabang = $this->cabang->getCabang_notHaving_timer();
        $mdata = [
            'title'     => 'Cabang - ' . NAMETITLE,
            'content'   => 'admin/timer/create',
            'extra'     => 'admin/timer/js/_js_create',
            'menuactive_timer'   => 'active open',
            'cabang'    => $cabang
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function edit($id_cabang)
    {
        $cabang = $this->cabang->allCabang();
        $timer = $this->timer->getBy_cabang(base64_decode($id_cabang));
        $mdata = [
            'title'     => 'Cabang - ' . NAMETITLE,
            'content'   => 'admin/timer/edit',
            'extra'     => 'admin/timer/js/_js_create',
            'menuactive_bg'   => 'active open',
            'cabang'    => $cabang,
            'timer_screen'     => $timer
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function get_all() {
        $result = $this->timer->allTimer();
        return json_encode($result);
    }

    public function destroy($id) {
        $result = $this->timer->deleteById(base64_decode($id));
        if (@$result->code!=201){
            session()->setFlashdata('failed', $result->message);
	    } else {
            session()->setFlashdata('success', $result->message);
        }

        return redirect()->to(BASE_URL. 'admin/timer');
    }

    public function store() {
        $validation = Services::validation(); // Ambil instance validasi
        $postData = $this->request->getPost();
        $mdata = [];

        // validasi cabang
        if (!isset($postData['cabang_id'])) {
            session()->setFlashdata('failed', 'Cabang ID harus diisi!');
            return redirect()->to(base_url("admin/timer/set"))->withInput();
        }

        $cabangId = $postData['cabang_id'];
        unset($postData['cabang_id']); //hapus cabang_id

        // Loop semua input selain cabang_id untuk menyusun array data
        foreach ($postData as $field => $value) {
            $validation->setRule($field, ucfirst(str_replace('_', ' ', $field)), 'required');

            // Setiap screen menjadi array sendiri dengan cabang_id
            $mdata[] = [
                "display" => (string) $field,
                "time"    => $value,
                "cabang_id" => (string) $cabangId
            ];
        }

        // Jalankan validasi
        if (!$validation->withRequest($this->request)->run()) {
            session()->setFlashdata('failed', $validation->listErrors());
            return redirect()->to(base_url("admin/timer/set"))->withInput();
        }

        $result = $this->timer->insertTimer($mdata);

        if ($result->code == 201) {
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL . "admin/timer");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL . "admin/timer/set")->withInput();
        }
    }

    public function update() {
        $validation = Services::validation(); // Ambil instance validasi
        $postData = $this->request->getPost();
        $mdata = [];

        // validasi cabang
        if (!isset($postData['cabang_id'])) {
            session()->setFlashdata('failed', 'Cabang ID harus diisi!');
            return redirect()->to(base_url("admin/timer/set"))->withInput();
        }

        $cabangId = $postData['cabang_id'];
        unset($postData['cabang_id']); //hapus cabang_id

        // Loop semua input selain cabang_id untuk menyusun array data
        foreach ($postData as $field => $value) {
            $validation->setRule($field, ucfirst(str_replace('_', ' ', $field)), 'required');

            // Setiap screen menjadi array sendiri dengan cabang_id
            $mdata[] = [
                "display" => (string) $field,
                "time"    => $value,
                "cabang_id" => (string) $cabangId
            ];
        }

        // Jalankan validasi
        if (!$validation->withRequest($this->request)->run()) {
            session()->setFlashdata('failed', $validation->listErrors());
            return redirect()->to(base_url("admin/timer/set"))->withInput();
        }

        $result = $this->timer->updateTimer($mdata);

        if ($result->code == 201) {
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL . "admin/timer");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL . "admin/timer/set")->withInput();
        }
    }
}
