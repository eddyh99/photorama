<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Timer extends BaseController
{
    public function __construct()
    {
        $this->setting = model('App\Models\Mdl_settings');
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

    public function getAll() {
        $result = $this->setting->timer();
        return json_encode($result);
    }

    public function destroy($name) {
        $result = $this->setting->destroy($name);
        if (@$result->code!=201){
            session()->setFlashdata('failed', $result->message);
	    } else {
            session()->setFlashdata('success', $result->message);
        }

        return redirect()->to(BASE_URL. 'admin/timer');
    }

    public function store() {
        $name = $this->request->getVar('name');
        $timer = $this->request->getVar('value');
        $result = $this->setting->store($name, $timer);
        
        if (@$result->code!=201){
            session()->setFlashdata('failed', $result->message);
	    } else {
            session()->setFlashdata('success', $result->message);
        }

        return redirect()->to(BASE_URL. 'admin/timer');
    }
}
