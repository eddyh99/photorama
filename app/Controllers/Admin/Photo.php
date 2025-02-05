<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Photo extends BaseController
{
    public function index()
    {
        $mdata = [
            'title'     => 'Photos User- ' . NAMETITLE,
            'content'   => 'admin/foto/index',
            'extra'     => 'admin/foto/js/_js_index',
            'menuactive_photo'   => 'active open'
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function list()
    {
        $path = FCPATH . "assets/photobooth/";
        $items = array_diff(scandir($path), array('.', '..'));

        // Filter hanya folder
        $folders = array_filter($items, function ($item) use ($path) {
            return is_dir($path . $item);
        });
        $mdata = [];

        foreach ($folders as $folder) {
            array_push($mdata, [
                'user' => 'user-' . $folder,
                'date' => date('d-m-Y', $folder),
                'url' => base_url("download/" . base64_encode($folder))
            ]);
        }

        return $this->response->setJSON($mdata);
    }
}
