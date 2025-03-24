<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Branch extends BaseController
{
    public function __construct()
    {   
        $this->cabang       = model('App\Models\Mdl_cabang');
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

    public function add()
    {
        $mdata = [
            'title'     => 'Cabang - ' . NAMETITLE,
            'content'   => 'admin/cabang/create',
            'extra'     => 'admin/cabang/js/_js_create',
            'menuactive_cabang'   => 'active open'
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function edit($id)
    {

        $result = $this->cabang->getCabang_byId(base64_decode($id));
        $result->id = $id;
        $mdata = [
            'title'     => 'Cabang - ' . NAMETITLE,
            'content'   => 'admin/cabang/edit',
            'extra'     => 'admin/cabang/js/_js_edit',
            'menuactive_bg'   => 'active open',
            'user'      => $result
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function store() {
        $rules = $this->validate([
            'nama_cabang' => [
                'label' => 'Nama Cabang',
                'rules' => 'required'
            ],
            'username' => [
                'label' => 'Username',
                'rules' => 'required'
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required'
            ],
            'konfirmasi_password' => [
                'label' => 'Konfirmasi Password',
                'rules' => 'required|matches[password]'
            ],
            'lokasi' => [
                'label' => 'Lokasi',
                'rules' => 'required'
            ]
        ]);

        // Checking Validation
        if (!$rules){
            session()->setFlashdata('failed', $this->validation->listErrors());
            return redirect()->to(BASE_URL . "admin/branch/add")->withInput();
        }


        $mdata = [
            'nama_cabang' => $this->request->getVar('nama_cabang'),
            'username' => $this->request->getVar('username'),
            'password' => sha1($this->request->getVar('password')),
            'lokasi' => $this->request->getVar('lokasi'),
            'is_event' => $this->request->getVar('is_event') ? true : false,
            'payment_status' => $this->request->getVar('payment_status') ? true : false,
            'retake_status' => $this->request->getVar('retake_status') ? true : false,
        ];

        $result = $this->cabang->insertCabang($mdata);

        if ($result->code == 201) {
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL . "admin/branch");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL . "admin/branch/add")->withInput();
        }
    }

    public function update($id) {
        $rules = $this->validate([
            'nama_cabang' => [
                'label' => 'Nama Cabang',
                'rules' => 'required'
            ],
            'username' => [
                'label' => 'Username',
                'rules' => 'required'
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'permit_empty|min_length[7]'
            ],
            'konfirmasi_password' => [
                'label' => 'Konfirmasi Password',
                'rules' => 'matches[password]'
            ],
            'lokasi' => [
                'label' => 'Lokasi',
                'rules' => 'required'
            ]
        ]);

        // Checking Validation
        if (!$rules){
            session()->setFlashdata('failed', $this->validation->listErrors());
            return redirect()->to(BASE_URL . "admin/branch/edit/$id")->withInput();
        }

        $pwd = $this->request->getVar('password');
        $mdata = [
            'nama_cabang' => $this->request->getVar('nama_cabang'),
            'username' => $this->request->getVar('username'),
            'lokasi' => $this->request->getVar('lokasi')
        ];

        if(!empty($pwd)) {
            $mdata['password'] = sha1($pwd);
        }

        $result = $this->cabang->updateCabang($mdata, base64_decode($id));

        if ($result->code == 201) {
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL . "admin/branch");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL . "admin/branch/edit/$id")->withInput();
        }
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

    public function update_status_payment() {
        $mdata = [
            'id' => $this->request->getVar('id'),
            'payment_status' => $this->request->getVar('payment_status') ? true : false,
        ];

        $result = $this->cabang->update_status($mdata);
        if ($result->code == 201) {
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL . "admin/branch");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL . "admin/branch");
        }
    }


    public function update_status_retake() {
        $mdata = [
            'id' => $this->request->getVar('id'),
            'retake_status' => $this->request->getVar('retake_status') ? true : false,
        ];

        $result = $this->cabang->update_status($mdata);
        if ($result->code == 201) {
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL . "admin/branch");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL . "admin/branch");
        }
    }

    public function update_status_print() {
        $mdata = [
            'id' => $this->request->getVar('id'),
            'print_status' => $this->request->getVar('print_status') ? true : false,
        ];

        $result = $this->cabang->update_status($mdata);
        if ($result->code == 201) {
            session()->setFlashdata('success', $result->message);
            return redirect()->to(BASE_URL . "admin/branch");
        }else{
            session()->setFlashdata('failed', $result->message);
            return redirect()->to(BASE_URL . "admin/branch");
        }
    }
}
