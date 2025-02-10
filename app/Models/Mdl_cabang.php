<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Mdl_cabang extends Model
{
    protected $server_tz = "Asia/Singapore";

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getByUsername($username)
    {
        $sql = "SELECT * FROM cabang WHERE username=?";
        $query = $this->db->query($sql, [$username])->getRow();
        
        if (!$query) {
	        $error=[
	            "code"       => "400",
	            "message"    => "Username not found"
	        ];
            return (object) $error;
        }

        $response=[
            "code"       => "200",
            "message"    => $query
        ];
        return (object) $response;
    }

    public function allCabang()
    {
        $sql = "SELECT
                    user.username as user,
                    cabang.*
                FROM
                    cabang
                    INNER JOIN user ON user.cabang_id = cabang.id";
        return $this->db->query($sql)->getResult();
    }

    // public function backgroundByScreen($screen, $cabang) {
    //     $sql = "SELECT file from background WHERE display = ? AND cabang_id = ?";
    //     return $this->db->query($sql, [$screen, $cabang])->getRow();
    // }

    public function deleteById($id)
    {

        $cabang = $this->db->table("cabang")->where("id", $id);
        if (!$cabang->delete()) {
            return (object) array(
                "code"      => 400,
                "message"   => "Gagal menghapus cabang"
            );
        }

        return (object) array(
            "code"      => 200,
            "message"   => "Cabang berhasil dihapus"
        );
    }

    // public function insertBg($mdata) {
    //     try {
    //         $bg = $this->db->table("background");

    //         // Insert data into 'pengguna' table
    //         if (!$bg->insert($mdata)) {
    //             // Handle case when insert fails (not due to exception)
    //             return (object) array(
    //                 "code"      => 400,
    //                 "message"   => "Gagal menyimpan pengguna"
    //             );
    //         }
    //     } catch (DatabaseException $e) {
    //         // For other database-related errors, return generic server error
    //         return (object) array(
    //             "code"      => 500,
    //             "message"   => "Terjadi kesalahan pada server"
    //         );
    //     } catch (\Exception $e) {
    //         // Handle any other general exceptions
    //         return (object) array(
    //             "code"      => 500,
    //             "message"   => "Terjadi kesalahan pada server"
    //         );
    //     }

    //     return (object) array(
    //         "code"      => 201,
    //         "message"   => "background berhasil ditambahkan"
    //     );
    // }
}
