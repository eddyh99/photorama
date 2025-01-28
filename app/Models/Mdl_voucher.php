<?php

namespace App\Models;

use CodeIgniter\Model;

class Mdl_voucher extends Model
{
    protected $server_tz = "Asia/Singapore";

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function voucherByKode($kode)
    {
        $sql = "SELECT * FROM voucher WHERE kode_voucher = ?";
        return $this->db->query($sql, [$kode])->getRow();
    }
}
