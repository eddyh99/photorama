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
}
