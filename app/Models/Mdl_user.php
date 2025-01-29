<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Mdl_user extends Model
{
    protected $server_tz = "Asia/Singapore";

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getByUsername($username)
    {
        $sql = "SELECT * FROM user WHERE username=?";
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
}
