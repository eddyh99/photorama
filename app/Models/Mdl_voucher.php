<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Mdl_voucher extends Model
{
    protected $server_tz = "Asia/Singapore";

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function allVoucher() {
        return $this->db->query('SELECT * FROM voucher')->getResult();
    }

    public function voucherByKode($kode)
    {
        $sql = "SELECT * FROM voucher WHERE kode_voucher = ?";
        return $this->db->query($sql, [$kode])->getRow();
    }

    public function createVoucher($mdata) {
        try {
            $voc = $this->db->table("voucher");

            // Insert data into 'pengguna' table
            if (!$voc->insert($mdata)) {
                // Handle case when insert fails (not due to exception)
                return (object) array(
                    "code"      => 400,
                    "message"   => "Gagal membuat voucher"
                );
            }
        } catch (DatabaseException $e) {
            // For other database-related errors, return generic server error
            return (object) array(
                "code"      => 500,
                "message"   => "Periksa inputan anda."
            );
        } catch (\Exception $e) {
            // Handle any other general exceptions
            return (object) array(
                "code"      => 500,
                "message"   => "Terjadi kesalahan pada server"
            );
        }

        return (object) array(
            "code"      => 201,
            "message"   => "voucher berhasil dibuat"
        );
    }

    public function deleteBykode($kode) {
        $voc = $this->db->table("voucher");
        $voc->where("kode_voucher", $kode);

        if (!$voc->delete()) {
            return (object) array(
                "code"      => 400,
                "message"   => "Gagal menghapus Voucher"
            );
        }

        return (object) array(
            "code"      => 200,
            "message"   => "Voucher berhasil dihapus"
        );
    }
}
