<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Mdl_qris extends Model
{
    protected $server_tz = "Asia/Singapore";

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function allBg()
    {
        $sql = "SELECT
                    qris.id,
                    qris.background,
                    cabang.nama_cabang
                FROM
                    qris
                    INNER JOIN cabang ON cabang.id = qris.cabang_id";
        return $this->db->query($sql)->getResult();
    }

    public function deleteById($id)
    {

        $bg = $this->db->table("qris")->where("id", $id);
        $file = $bg->get()->getRow()->background ?? null;

        $bg->where("id", $id);
        if (!$bg->delete()) {
            return (object) array(
                "code"      => 400,
                "message"   => "Gagal menghapus Qris"
            );
        }

        return (object) array(
            "code"      => 200,
            "file"      => $file,
            "message"   => "Qris berhasil dihapus"
        );
    }

    public function getBy_cabang($cabang_id)
    {
        $sql = "SELECT
                    qris.background
                FROM
                    qris
                    INNER JOIN cabang ON cabang.id = qris.cabang_id
                WHERE qris.cabang_id = ?";
        return $this->db->query($sql, [$cabang_id])->getRow()->background ?? null;
    }

    public function insertQrisBg($mdata) {
        try {
            $qris = $this->db->table("qris");

            // Insert data into 'qris' table
            if (!$qris->insert($mdata)) {
                // Handle case when insert fails (not due to exception)
                $this->db->transRollback();
                return (object) array(
                    "code"      => 400,
                    "message"   => "Gagal menyimpan Background Qris"
                );
            }

        } catch (DatabaseException $e) {
            // For other database-related errors, return generic server error
            return (object) array(
                "code"      => 500,
                "message"   => "Terjadi kesalahan pada server"
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
            "message"   => "Background Qris berhasil ditambahkan"
        );
    }

}
