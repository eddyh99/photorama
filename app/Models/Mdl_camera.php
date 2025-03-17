<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Mdl_camera extends Model
{
    protected $server_tz = "Asia/Singapore";

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function allcamera()
    {
        $sql = "SELECT
                    camera.id,
                    camera.camera1,
                    camera.camera2,
                    cabang.nama_cabang,
                    cabang.lokasi
                FROM
                    camera
                    INNER JOIN cabang ON cabang.id = camera.cabang_id";
        return $this->db->query($sql)->getResult();
    }

    public function getBy_Id($id)
    {
        $sql = "SELECT
                    *
                FROM
                    camera
                WHERE id = ?";
        return $this->db->query($sql, [$id])->getRow();
    }

    public function getBy_cabang($cabang_id)
    {
        $sql = "SELECT
                    camera.camera1,
                    camera.camera2
                FROM
                    camera
                    INNER JOIN cabang ON cabang.id = camera.cabang_id
                WHERE harga.cabang_id = ?";
        return $this->db->query($sql, [$cabang_id])->getResult();
    }


    public function deleteById($id)
    {

        $harga = $this->db->table("camera")->where("id", $id);
        if (!$harga->delete()) {
            return (object) array(
                "code"      => 400,
                "message"   => "Gagal menghapus Camera"
            );
        }

        return (object) array(
            "code"      => 200,
            "message"   => "Harga berhasil dihapus"
        );
    }

    public function insertcamera($mdata) {
        try {
            $price = $this->db->table("camera");

            // Insert data into 'pengguna' table
            if (!$price->insert($mdata)) {
                // Handle case when insert fails (not due to exception)
                return (object) array(
                    "code"      => 400,
                    "message"   => "Gagal menyimpan Camera"
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
            "message"   => "Harga berhasil disimpan"
        );
    }

    public function updateCamera($mdata, $id) {
        try {
            $price = $this->db->table("camera")->where('id', $id);

            // Insert data into 'pengguna' table
            if (!$price->update($mdata)) {
                // Handle case when insert fails (not due to exception)
                return (object) array(
                    "code"      => 400,
                    "message"   => "Gagal memperbarui Camera"
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
            "message"   => "harga berhasil diperbarui"
        );
    }
}
