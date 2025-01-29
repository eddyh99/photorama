<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    public function __construct()
    {   
        $this->user       = model('App\Models\Mdl_user');
	}
    public function index()
    {
        $session = session();
        if($session->has('logged_user')){
            return redirect()->to(BASE_URL . "admin/background");
            exit();
        }

        $mdata = [
            'title'     => 'Sign in - ' . NAMETITLE,
            'content'   => 'auth/index',
            'extra'     => 'auth/js/_js_index',
        ];

        return view('auth/layout/wrapper', $mdata);
    }

    public function signin_proccess()
    {
        
        // Validation Field
        $rules = $this->validate([
            'username'     => [
                'label'     => 'Username or Email',
                'rules'     => 'required'
            ],
            'password'     => [
                'label'     => 'Password',
                'rules'     => 'required'
            ],
        ]);

        // Checking Validation
        if(!$rules){
            session()->setFlashdata('failed', $this->validation->listErrors());
            return redirect()->to(BASE_URL. 'login')->withInput();
        }
        
        // Initial Data 
        // FILTER HTML SPECIAL CHARS
        // FILTER TRIM CHARS
        // ENCRPT SHA1 PASSWORD
        $mdata = [
            'username'  => trim(htmlspecialchars($this->request->getVar('username'))),
            'password'  => sha1(htmlspecialchars($this->request->getVar('password'))),
        ];
        
        $user = $this->user->getByUsername($mdata['username']);
        if (@$user->code==400){
            session()->setFlashdata('failed', $user->message);
            return redirect()->to(BASE_URL. 'login')->withInput();
	    }

        if ($mdata['password'] != $user->message->passwd) {
            session()->setFlashdata('failed', 'Invalid username or password');
            return redirect()->to(BASE_URL. 'login')->withInput();
        }

        // Set SESSION logged_user
        $this->session->set('logged_user', $mdata);

        // If Success set session and redirect
        session()->setFlashdata('success', "Selamat datang <b>".$mdata['username']."</b>");
        return redirect()->to(BASE_URL . "admin/background");
        exit();
    }

    public function logout(){
        // unset($_SESSION['item']);
        session()->destroy();
        return redirect()->to(BASE_URL. "login")->withInput();
        exit;
    }
}
