<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
// use CodeIgniter\HTTP\ResponseInterface;

class Photo extends BaseController
{

    public function __construct()
    {
        $this->setting       = model('App\Models\Mdl_settings');
    }

    public function index()
    {
        $auto_print = $this->setting->value('auto_print');
        $mdata = [
            'title'     => 'Photos User- ' . NAMETITLE,
            'content'   => 'admin/foto/index',
            'extra'     => 'admin/foto/js/_js_index',
            'menuactive_photo'   => 'active open',
            'auto_print' => filter_var($auto_print, FILTER_VALIDATE_BOOLEAN)
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function list()
    {
        
        $path = FCPATH . "assets" . DIRECTORY_SEPARATOR . "photobooth" . DIRECTORY_SEPARATOR;
        $items = array_diff(scandir($path), array('.', '..'));

        // Filter hanya folder
        $folders = array_filter($items, function ($item) use ($path) {
            return is_dir($path . $item);
        });
        $mdata = [];

        foreach ($folders as $folder) {
            $time = explode("-", $folder);
            array_push($mdata, [
                'user' => $folder,
                'date' => date("Y-m-d H:i:s", end($time)),
                'thumbnail' => $folder . '/photos-1.jpg',
                'url_download' => base_url("download/" . base64_encode($folder)),
                'url_delete' => base_url("delete/" . base64_encode($folder)),
            ]);
        }

        return $this->response->setJSON($mdata);
    }

    public function delete_userFiles($folder) {
        $path = FCPATH . "assets/photobooth/" . base64_decode($folder);
        
        if (!is_dir($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (delete_files($path, true)) {
            rmdir($path);
            session()->setFlashdata('success', 'Files Berhasil dihapus');
            return redirect()->to(base_url("admin/photo"));
        } else {
            session()->setFlashdata('failed', 'Files gagal dihapus');
            return redirect()->to(base_url("admin/photo"));
        }
    }

    public function print_setting() {
        $status = filter_var($this->request->getVar('auto-print'), FILTER_VALIDATE_BOOLEAN);
        $this->setting->store('auto_print', $status);
        return redirect()->to(base_url("admin/photo"));
    }
}
