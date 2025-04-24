<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskCategoryModel extends Model
{
    protected $table = 'task_categories';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name']; 
}

