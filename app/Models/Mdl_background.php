<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\Exceptions\DatabaseException;

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

    public function backgroundByScreen($screen) {
        $sql = "SELECT file from background WHERE display = ?";
        return $this->db->query($sql, [$screen])->getRow();
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

    public function insertBg($mdata) {
        try {
            $bg = $this->db->table("background");

            // Insert data into 'pengguna' table
            if (!$bg->insert($mdata)) {
                // Handle case when insert fails (not due to exception)
                return (object) array(
                    "code"      => 400,
                    "message"   => "Gagal menyimpan pengguna"
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
            "message"   => "background berhasil ditambahkan"
        );
    }
}
