<?php

namespace App\Controllers;
use App\Models\TasksModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $model = new TasksModel();
        $userId = session()->get('user_id'); 
		$data['pageTitle'] = 'Dashboard';
        $data['tasks'] = $model->where('user_id', $userId)->findAll();
		
		return $this->render(view('tasks', $data));
    }
}

