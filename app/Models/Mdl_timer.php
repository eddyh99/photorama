<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Mdl_timer extends Model
{
    protected $server_tz = "Asia/Singapore";

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // public function getByUsername($username)
    // {
    //     $sql = "SELECT * FROM cabang WHERE username=?";
    //     $query = $this->db->query($sql, [$username])->getRow();
        
    //     if (!$query) {
	//         $error=[
	//             "code"       => "400",
	//             "message"    => "Username not found"
	//         ];
    //         return (object) $error;
    //     }

    //     $response=[
    //         "code"       => "200",
    //         "message"    => $query
    //     ];
    //     return (object) $response;
    // }

    public function allTimer()
    {
        $sql = "SELECT
                    timer.id,
                    timer.display,
                    timer.time,
                    timer.cabang_id,
                    cabang.nama_cabang
                FROM
                    timer
                    INNER JOIN cabang ON cabang.id = timer.cabang_id
                ORDER BY cabang.id";
        return $this->db->query($sql)->getResult();
    }

    public function get_byCabang_andScreen($screen, $cabang) {
        $sql = "SELECT
                    time
                FROM
                    timer
                WHERE
                    display = ?
                AND cabang_id = ?";
        return $this->db->query($sql, [$screen, $cabang])->getRow()->time ?? null;
    }

    // public function getBy_Id($id)
    // {
    //     $sql = "SELECT
    //                 *
    //             FROM
    //                 harga
    //             WHERE id = ?";
    //     return $this->db->query($sql, [$id])->getRow();
    // }

    // public function getBy_cabang($cabang_id)
    // {
    //     $sql = "SELECT
    //                 harga.harga
    //             FROM
    //                 harga
    //                 INNER JOIN cabang ON cabang.id = harga.cabang_id
    //             WHERE harga.cabang_id = ?";
    //     return $this->db->query($sql, [$cabang_id])->getRow()->harga ?? 999999;
    // }


    public function deleteById($id)
    {

        $timer = $this->db->table("timer")->where("id", $id);
        if (!$timer->delete()) {
            return (object) array(
                "code"      => 400,
                "message"   => "Gagal menghapus timer"
            );
        }

        return (object) array(
            "code"      => 201,
            "message"   => "timer berhasil dihapus"
        );
    }

    public function insertTimer($mdata) {
        try {
            $timer = $this->db->table("timer");

            // Insert data into 'pengguna' table
            if (!$timer->insertBatch($mdata)) {
                // Handle case when insert fails (not due to exception)
                return (object) array(
                    "code"      => 400,
                    "message"   => "Gagal menyimpan Timer"
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
            "message"   => "Timer berhasil disimpan"
        );
    }

    // public function updatePrice($mdata, $id) {
    //     try {
    //         $price = $this->db->table("harga")->where('id', $id);

    //         // Insert data into 'pengguna' table
    //         if (!$price->update($mdata)) {
    //             // Handle case when insert fails (not due to exception)
    //             return (object) array(
    //                 "code"      => 400,
    //                 "message"   => "Gagal memperbarui harga"
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
    //         "message"   => "harga berhasil diperbarui"
    //     );
    // }
}
