<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Mdl_frame extends Model
{
    protected $server_tz = "Asia/Singapore";

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function allFrame()
    {
        $sql = "SELECT
                    frame.id,
                    frame.name,
                    frame.file,
                    cabang.nama_cabang
                FROM
                    frame
                    JOIN frame_koordinat fk ON fk.frame_id = frame.id
                    INNER JOIN cabang ON cabang.id = frame.cabang_id
                GROUP BY frame.id";
        return $this->db->query($sql)->getResult();
    }

    public function getById($id)
    {
        $sql = "SELECT
                    frame.file,
                    fk.x,
                    fk.y,
                    fk.width,
                    fk.height
                FROM
                    frame
                    JOIN frame_koordinat fk ON fk.frame_id = frame.id
                WHERE
                    frame.id = ?";
        return $this->db->query($sql, [$id])->getResult() ?? null;
    }

    public function getByFile($file)
    {
        $sql = "SELECT
                    frame.file,
                    fk.x,
                    fk.y,
                    fk.width,
                    fk.height,
                    fk.index
                FROM
                    frame
                    JOIN frame_koordinat fk ON fk.frame_id = frame.id
                WHERE
                    frame.file = ?";
        return $this->db->query($sql, [$file])->getResult() ?? null;
    }


    public function getByCabang($id_cabang)
    {
        $sql = "SELECT
                    frame.id,
                    frame.name,
                    frame.file
                FROM
                    frame
                    JOIN frame_koordinat fk ON fk.frame_id = frame.id
                WHERE frame.cabang_id = ?
                GROUP BY frame.id";
        return $this->db->query($sql, [$id_cabang])->getResult();
    }

    // public function backgroundByScreen($screen) {
    //     $sql = "SELECT file from background WHERE display = ?";
    //     return $this->db->query($sql, [$screen])->getRow();
    // }

    public function deleteById($id)
    {

        $bg = $this->db->table("frame")->where("id", $id);
        $file = $bg->get()->getRow()->file ?? null;

        $bg->where("id", $id);
        if (!$bg->delete()) {
            return (object) array(
                "code"      => 400,
                "message"   => "Gagal menghapus frame"
            );
        }

        return (object) array(
            "code"      => 200,
            "file"      => $file,
            "message"   => "Frame berhasil dihapus"
        );
    }

    public function insertFrame($mdata) {
        try {
            $this->db->transBegin();
            $fr = $this->db->table("frame");
            $koordinat = $this->db->table("frame_koordinat");

            // Insert data into 'pengguna' table
            if (!$fr->insert($mdata['frame'])) {
                // Handle case when insert fails (not due to exception)
                $this->db->transRollback();
                return (object) array(
                    "code"      => 400,
                    "message"   => "Gagal menyimpan frame"
                );
            }

            $frame_id = $this->db->insertID();
            // Tambahkan frame_id ke setiap koordinat
            foreach ($mdata['koordinat'] as &$k) {
                $k->frame_id = $frame_id;
            }

            // InsertBatch into 'koordinat'
            if (!$koordinat->insertBatch($mdata['koordinat'])) {
                // Rollback if 'penjualan_detail' insertion fails
                $this->db->transRollback();
                return (object) [
                    "code"    => 500,
                    "message" => "Gagal menyimpan detail penjualan"
                ];
            }
    
            $this->db->transCommit();

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
            "message"   => "frame berhasil ditambahkan"
        );
    }
}
