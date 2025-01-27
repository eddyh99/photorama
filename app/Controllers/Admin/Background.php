<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Background extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'     => 'Backround - ' . NAMETITLE,
            'content'   => 'admin/bg/index',
            // 'extra'     => 'admin/barang/js/_js_index',
            'menuactive_bg'   => 'active open'
        ];

        return view('admin/layout/wrapper', $mdata);
    }
}
