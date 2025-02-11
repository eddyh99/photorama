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

}
