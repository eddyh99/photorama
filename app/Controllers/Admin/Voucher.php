<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Voucher extends BaseController
{
    public function __construct()
    {   
        $this->voucher       = model('App\Models\Mdl_voucher');
        $this->setting       = model('App\Models\Mdl_settings');
	}

    public function index()
    {
        $price = $this->setting->value('price');
        $mdata = [
            'title'     => 'Voucher - ' . NAMETITLE,
            'content'   => 'admin/voucher/index',
            'extra'     => 'admin/voucher/js/_js_index',
            'menuactive_voc'   => 'active open',
            'price'     => $price
        ];

        return view('admin/layout/wrapper', $mdata);
    }

    public function store() {

        $kd_voucher = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8));
        $diskon = $this->request->getVar('diskon');

        $mdata = [
            'kode_voucher'   => $kd_voucher,
            'potongan_harga' => !empty($diskon) ? $diskon : null,
            'expired'        => date('Y-m-d', strtotime($this->request->getVar('expired')))
        ];

        $result = $this->voucher->createVoucher($mdata);
        return json_encode($result);
    }


    public function cekVoucher($voucher)
    {
        // Mengambil data voucher berdasarkan kode
        $result = $this->voucher->voucherByKode($voucher);
    
        // Cek apakah voucher ditemukan
        if (empty($result)) {
            return (object) [
                "success" => false,
                "message" => "Voucher tidak tersedia."
            ];
        }
    
        // Cek apakah voucher sudah kedaluwarsa
        if ($result->expired <= date('Y-m-d')) {
            return (object) [
                "success" => false,
                "message" => "Voucher kadaluarsa."
            ];
        }
    
        // Jika voucher valid, kembalikan data voucher
        return (object) [
            "success" => true,
            "message" => "Voucher berhasil digunakan senilai IDR " .$result->potongan_harga,
            "data" => $result // Menyertakan data voucher yang valid
        ];
    }

    public function getAll() {
        $result = $this->voucher->allVoucher();
        return json_encode($result);
    }

    public function destroy($voucher) {
        $result = $this->voucher->deleteBykode($voucher);
        return json_encode($result);
    }
}
