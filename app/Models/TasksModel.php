<?php

namespace App\Models;
use CodeIgniter\Model;

class TasksModel extends Model
{
    protected $table = 'tasks'; // Your database table name
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'description', 'category', 'deadline', 'user_id']; // Define editable fields
}
