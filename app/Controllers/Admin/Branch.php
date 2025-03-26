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
            'printer_name' => [
                'label' => 'Nama Printer',
                'rules' => 'required'
            ],
            'lokasi' => [
                'label' => 'Lokasi',
                'rules' => 'required'
            ],
            'private_key' => [
                'label' => 'Kunci Privat',
                'rules' => 'uploaded[private_key]|max_size[private_key,2048]'
            ],
            'certificate' => [
                'label' => 'Sertifikat',
                'rules' => 'uploaded[certificate]|ext_in[certificate,txt]|max_size[certificate,2048]'
            ]
        ]);

        // Checking Validation
        if (!$rules){
            session()->setFlashdata('failed', $this->validation->listErrors());
            return redirect()->to(BASE_URL . "admin/branch/add")->withInput();
        }

        $private_key = $this->request->getFile('private_key');
        $cert = $this->request->getFile('certificate');
        $pk_name = 'private-key-' . time() . '.pem';
        $cert_name = 'digital-cert-' . time() . '.txt';
        $private_key->move(WRITEPATH . 'certs/', $pk_name);
        $cert->move(FCPATH . 'assets/certs/', $cert_name);


        $mdata = [
            'nama_cabang' => $this->request->getVar('nama_cabang'),
            'username' => $this->request->getVar('username'),
            'password' => sha1($this->request->getVar('password')),
            'printer_name' => $this->request->getVar('printer_name'),
            'lokasi' => $this->request->getVar('lokasi'),
            'is_event' => $this->request->getVar('is_event') ? true : false,
            'payment_status' => $this->request->getVar('payment_status') ? true : false,
            'retake_status' => $this->request->getVar('retake_status') ? true : false,
            'print_status' => $this->request->getVar('print_status') ? true : false,
            'private_key' =>  'certs/' . $pk_name,
            'certificate' => 'certs/' . $cert_name
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
            'printer_name' => [
                'label' => 'Nama Printer',
                'rules' => 'required'
            ],
            'lokasi' => [
                'label' => 'Lokasi',
                'rules' => 'required'
            ],
            'private_key' => [
                'label' => 'Kunci Privat',
                'rules' => 'permit_empty|max_size[private_key,2048]'
            ],
            'certificate' => [
                'label' => 'Sertifikat',
                'rules' => 'permit_empty|ext_in[certificate,txt]|max_size[certificate,2048]'
            ]
        ]);

        // Checking Validation
        if (!$rules){
            session()->setFlashdata('failed', $this->validation->listErrors());
            return redirect()->to(BASE_URL . "admin/branch/edit/$id")->withInput();
        }

        $mdata = [];
        $private_key = $this->request->getFile('private_key');
        $cert = $this->request->getFile('certificate');
        $pk_name = basename($this->request->getVar('pk_name'));
        $cert_name = basename($this->request->getVar('cert_name'));

        if($private_key && $private_key->isValid()) {
            if(empty($pk_name)) {
                $pk_name = 'private-key-' . time() . '.pem';
                $mdata['private_key'] = $pk_name;
            }
            $private_key->move(WRITEPATH . 'certs/', $pk_name, true);
        }

        if($cert && $cert->isValid()) {
            if(empty($cert_name)) {
                $cert_name = 'digital-cert-' . time() . '.txt';
                $mdata['certificate'] = $cert_name;
            }
            $cert->move(FCPATH . 'assets/certs/', $cert_name, true);
        }

        $pwd = $this->request->getVar('password');
        $mdata += [
            'nama_cabang' => $this->request->getVar('nama_cabang'),
            'username' => $this->request->getVar('username'),
            'printer_name' => $this->request->getVar('printer_name'),
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
