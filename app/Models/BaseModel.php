<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
    protected $db;
    protected $session;
    protected $request;

    public function __construct()
    {
        parent::__construct(); // Ensure the parent constructor runs

        $this->db = \Config\Database::connect(); // No need for 'default' explicitly unless using multiple DBs
        $this->session = session(); // Uses CodeIgniter’s built-in session helper
        $this->request = \Config\Services::request(); // Access request object properly
    }
}
