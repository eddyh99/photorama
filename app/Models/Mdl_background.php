<?php

namespace App\Models;

use CodeIgniter\Model;

class Mdl_background extends Model
{
    protected $server_tz = "Asia/Singapore";

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function allBackground()
    {
        $sql = "select * from background";
        return $this->db->query($sql)->getResult();
    }

    public function deleteById($id)
    {

        $bg = $this->db->table("background");
        $bg->where("id", $id);

        if (!$bg->delete()) {
            return (object) array(
                "code"      => 400,
                "message"   => "Gagal menghapus background"
            );
        }

        return (object) array(
            "code"      => 200,
            "message"   => "Background berhasil dihapus"
        );
    }
}
