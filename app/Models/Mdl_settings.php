<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Mdl_settings extends Model
{
    protected $server_tz = "Asia/Singapore";

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function value($name) {
        $sql = "SELECT value from setting WHERE name = ?";

        return $this->db->query($sql, [$name])->getRow()->value ?? null;
    }

    public function store($name, $value) {
        $sql = "INSERT INTO setting (name, value)
                VALUES (?, ?)
                ON DUPLICATE KEY UPDATE value = ?";

        try {
            // Insert data into 'pengguna' table
            if (!$this->db->query($sql, [$name, $value, $value])) {
                // Handle case when insert fails (not due to exception)
                return (object) array(
                    "code"      => 400,
                    "message"   => "Gagal"
                );
            }
        } catch (DatabaseException $e) {
            // For other database-related errors, return generic server error
            return (object) array(
                "code"      => 500,
                "message"   => "Periksa inputan anda."
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
            "message"   => "$name berhasil diset."
        );
    }
    
}
