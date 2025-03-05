<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
// use CodeIgniter\HTTP\ResponseInterface;

class Photo extends BaseController
{

    public function __construct()
    {
        $this->setting       = model('App\Models\Mdl_settings');
        $this->cabang       = model('App\Models\Mdl_cabang');
    }

    public function index()
    {
        $auto_print = $this->setting->value('auto_print');
        $cabang = $this->cabang->allCabang();
        $mdata = [
            'title'     => 'Photos User- ' . NAMETITLE,
            'content'   => 'admin/foto/index',
            'extra'     => 'admin/foto/js/_js_index',
            'menuactive_photo'   => 'active open',
            'auto_print' => filter_var($auto_print, FILTER_VALIDATE_BOOLEAN),
            'cabang'    => $cabang
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function list()
    {
        $mdata = [];
        $cabang = $this->request->getVar('cabang');
        $is_event = filter_var($this->request->getVar('is_event'), FILTER_VALIDATE_BOOLEAN);

        if(!$cabang) {
            return $this->response->setJSON($mdata);
        }
        
        $path = FCPATH . "assets" . DIRECTORY_SEPARATOR . "photobooth" . DIRECTORY_SEPARATOR . ($is_event ? $cabang . DIRECTORY_SEPARATOR : null);
        $pattern = $path . $cabang . '*';
        $folders = glob($pattern, GLOB_ONLYDIR); 


        foreach ($folders as $folder) {
            $folderName = basename($folder);
            $time = explode("-", $folderName);
            array_push($mdata, [
                'user' => $folderName,
                'date' => date("Y-m-d H:i:s", end($time)),
                'thumbnail' => ($is_event ? "$cabang/" : '') . $folderName . '/photos-1.jpg',
                'url_download' => base_url(  $is_event ? 'browse/' . $cabang : "download/" . base64_encode($folderName)),
                'url_delete' => base_url("delete/". base64_encode( ($is_event ? "$cabang/" : '') . $folderName)),
                'is_event' => $is_event
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
