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
        $sql = "select background.*, cabang.nama_cabang  from background inner join cabang on cabang.id = background.cabang_id WHERE background.file != 'default'";
        return $this->db->query($sql)->getResult();
    }

    public function backgroundByScreen($screen, $cabang) {
        $sql = "SELECT file from background WHERE display = ? AND cabang_id = ?";
        return $this->db->query($sql, [$screen, $cabang])->getRow()->file ?? null;
    }

    public function backgroundByIdCabang($id_cabang) {
        $sql = "SELECT
                    b.display,
                    b.file,
                    c.nama_cabang
                FROM
                    background b
                    INNER JOIN cabang c ON c.id = b.cabang_id
                WHERE
                    b.cabang_id = ?";

        return $this->db->query($sql, [$id_cabang])->getResult();
    }

    public function deleteById($id)
    {

        $bg = $this->db->table("background")->where("id", $id);
        $file = $bg->get()->getRow()->file ?? null;

        $bg->where("id", $id);
        if (!$bg->delete()) {
            return (object) array(
                "code"      => 400,
                "message"   => "Gagal menghapus background"
            );
        }

        return (object) array(
            "code"      => 200,
            "file"      => $file,
            "message"   => "Background berhasil dihapus"
        );
    }

    public function insertBg($mdata) {
        try {
            $bg = $this->db->table("background");

            // Insert data into 'pengguna' table
            if (!$bg->insertBatch($mdata)) {
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
                "message"   => "Terjadi kesalahan pada server" . $e
            );
        } catch (\Exception $e) {
            // Handle any other general exceptions
            return (object) array(
                "code"      => 500,
                "message"   => "Terjadi kesalahan pada server" .$e
            );
        }

        return (object) array(
            "code"      => 201,
            "message"   => "background berhasil ditambahkan"
        );
    }


    public function updateBg(array $mdata)
    {
        try {
    
            $builder = $this->db->table("background");
    
            // Melakukan update batch berdasarkan kombinasi key: id_cabang + display
            if (!$builder->updateBatch($mdata, ['cabang_id', 'display'])) {
                return (object)[
                    "code"    => 400,
                    "message" => "Gagal mengupdate background"
                ];
            }
    
            return (object)[
                "code"    => 201,
                "message" => "Background berhasil diperbarui."
            ];
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            return (object)[
                "code"    => 500,
                "message" => "Kesalahan database: " . $e->getMessage()
            ];
        } catch (\Exception $e) {
            return (object)[
                "code"    => 500,
                "message" => "Kesalahan server: " . $e->getMessage()
            ];
        }
    }
    
}
