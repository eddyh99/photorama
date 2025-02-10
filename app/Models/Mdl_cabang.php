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
                    cabang.*
                FROM
                    cabang";
        return $this->db->query($sql)->getResult();
    }

    public function getCabang_byId($id)
    {
        $sql = "SELECT
                    cabang.*
                FROM
                    cabang
                WHERE
                    id = ?";
        return $this->db->query($sql, [$id])->getRow();
    }

    public function getCabang_notHaving_price() {
        $sql = "SELECT
                    cabang.*
                FROM
                    cabang
                    LEFT JOIN harga ON harga.cabang_id = cabang.id
                WHERE
                    harga.cabang_id IS NULL
                    AND username = 'user'";
        return $this->db->query($sql)->getResult();
    }


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

    public function insertCabang($mdata) {
        try {
            $bg = $this->db->table("cabang");

            // Insert data into 'pengguna' table
            if (!$bg->insert($mdata)) {
                // Handle case when insert fails (not due to exception)
                return (object) array(
                    "code"      => 400,
                    "message"   => "Gagal menyimpan cabang"
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
            "message"   => "cabang berhasil ditambahkan"
        );
    }

    public function updateCabang($mdata, $id) {
        try {
            $cabang = $this->db->table("cabang")->where('id', $id);

            // Insert data into 'pengguna' table
            if (!$cabang->update($mdata)) {
                // Handle case when insert fails (not due to exception)
                return (object) array(
                    "code"      => 400,
                    "message"   => "Gagal memperbarui cabang"
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
            "message"   => "cabang berhasil diperbarui"
        );
    }
}
