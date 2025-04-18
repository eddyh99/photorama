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
                    cabang
                WHERE nama_cabang<>'admin'";
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
                    AND role = 'user'";
        return $this->db->query($sql)->getResult();
    }

    public function getCabang_notHaving_bg() {
        $sql = "SELECT
                    cabang.*
                FROM
                    cabang
                    LEFT JOIN background ON background.cabang_id = cabang.id
                WHERE
                    background.cabang_id IS NULL
                    AND role = 'user'";
        return $this->db->query($sql)->getResult();
    }

    public function getCabang_notHaving_qris() {
        $sql = "SELECT
                    cabang.*
                FROM
                    cabang
                    LEFT JOIN qris ON qris.cabang_id = cabang.id
                WHERE
                    qris.cabang_id IS NULL
                    AND role = 'user'";
        return $this->db->query($sql)->getResult();
    }

    public function getCabang_notHaving_timer() {
        $sql = "SELECT
                    cabang.*
                FROM
                    cabang
                    LEFT JOIN timer ON timer.cabang_id = cabang.id
                WHERE
                    timer.cabang_id IS NULL
                    AND role = 'user'";
        return $this->db->query($sql)->getResult();
    }

    public function getCabang_notHaving_camera(){
        $sql = "SELECT
                    cabang.*
                FROM
                    cabang
                    LEFT JOIN camera ON camera.cabang_id = cabang.id
                WHERE
                    camera.cabang_id IS NULL
                    AND role = 'user'";
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

    public function update_status($mdata)
    {
        try {
            $cabang = $this->db->table("cabang")->where('id', $mdata['id']);

            // Insert data into 'pengguna' table
            if (!$cabang->update($mdata)) {
                // Handle case when insert fails (not due to exception)
                return (object) array(
                    "code"      => 400,
                    "message"   => "Gagal memperbarui status"
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
            "message"   => "status berhasil diperbarui"
        );
    }

    public function get_status($status, $id)
    {
        $sql = "SELECT * FROM cabang WHERE id=?";
        $query = $this->db->query($sql, [$id])->getRow();
        
        if (!$query) {
	        $error=[
	            "code"       => "400",
	            "message"    => null
	        ];
            return (object) $error;
        }

        $response=[
            "code"       => "200",
            "message"    => $query->$status
        ];
        return (object) $response;
    }
}
