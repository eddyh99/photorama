<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Voucher extends BaseController
{
    public function __construct()
    {   
        $this->voucher       = model('App\Models\Mdl_voucher');
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
}
