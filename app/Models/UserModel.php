<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'firstname', 'lastname', 'email', 
        'password', 'resume',  // your custom fields
        'role'               // add this field if it doesn’t already exist
    ]; 
    protected $useTimestamps = true;

    // Automatically hash the password before inserting or updating a record
    protected $beforeInsert  = ['hashPassword'];
    protected $beforeUpdate  = ['hashPassword'];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Hash the user's password.
     *
     * @param array $data
     * @return array
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            // Hash the password using the default algorithm (usually bcrypt)
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

}