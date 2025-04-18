<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Mdl_price extends Model
{
    protected $server_tz = "Asia/Singapore";

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function allPrices()
    {
        $sql = "SELECT
                    harga.id,
                    harga.harga,
                    cabang.nama_cabang,
                    cabang.lokasi
                FROM
                    harga
                    INNER JOIN cabang ON cabang.id = harga.cabang_id";
        return $this->db->query($sql)->getResult();
    }

    public function getBy_Id($id)
    {
        $sql = "SELECT
                    *
                FROM
                    harga
                WHERE id = ?";
        return $this->db->query($sql, [$id])->getRow();
    }

    public function getBy_cabang($cabang_id)
    {
        $sql = "SELECT
                    harga.harga
                FROM
                    harga
                    INNER JOIN cabang ON cabang.id = harga.cabang_id
                WHERE harga.cabang_id = ?";
        return $this->db->query($sql, [$cabang_id])->getRow()->harga ?? 999999;
    }


    public function deleteById($id)
    {

        $harga = $this->db->table("harga")->where("id", $id);
        if (!$harga->delete()) {
            return (object) array(
                "code"      => 400,
                "message"   => "Gagal menghapus harga"
            );
        }

        return (object) array(
            "code"      => 200,
            "message"   => "Harga berhasil dihapus"
        );
    }

    public function insertPrice($mdata) {
        try {
            $price = $this->db->table("harga");

            // Insert data into 'pengguna' table
            if (!$price->insert($mdata)) {
                // Handle case when insert fails (not due to exception)
                return (object) array(
                    "code"      => 400,
                    "message"   => "Gagal menyimpan Harga"
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

    public function updatePrice($mdata, $id) {
        try {
            $price = $this->db->table("harga")->where('id', $id);

            // Insert data into 'pengguna' table
            if (!$price->update($mdata)) {
                // Handle case when insert fails (not due to exception)
                return (object) array(
                    "code"      => 400,
                    "message"   => "Gagal memperbarui harga"
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
