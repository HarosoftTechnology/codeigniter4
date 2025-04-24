<?php

namespace App\Controllers;

use App\Models\TasksModel;
use App\Models\TaskCategoryModel;
use Config\Services;

class Task extends BaseController
{
    protected $categories;

    public function __construct()
    {
        $this->categories = new TaskCategoryModel();
    }

    public function index()
    {
        $model = new TasksModel();
        $userId = session()->get('user_id'); 
        $data['pageTitle'] = 'Tasks';
        // Fetch only tasks where the user_id equals the logged-in user's ID
        $data['tasks'] = $model->where('user_id', $userId)->findAll();
        
        return $this->renderView('tasks', $data);
    }

    /**
     * Create a new task.
     * This method can handle both GET (to show the form) and POST (to process submission).
     */
    public function create()
    {
        $data['pageTitle'] = "Create Task";
        $data['categories'] = $this->categories->findAll();

        if ($this->request->getPost()) {
            $validation = Services::validation();
            // Define validation rules
            $rules = [
                'title' => 'required',
                'description'     => 'required',
                'category'  => 'required',
                'deadline' => 'required'
            ];

            // Run validation
            if (!$this->validate($rules)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'type'    => 'error',
                        'message' => $validation->getErrors()
                    ]);
                }
                return redirect_back([
                    'id'       => 'flash-message',
                    'type'     => 'error',
                    'position' => 'bottom-right',
                    'dismiss'  => false,
                    'message'  => implode('<br>', $validation->getErrors()) // Convert errors to a readable format
                ]);
            }
            
            // Create a new task in the database
            $model = new TasksModel();
            $data = [
                'title' => $this->request->getPost('title'),
                'description'  => $this->request->getPost('description'),
                'category'     => $this->request->getPost('category'),
                'deadline'  => $this->request->getPost('deadline'),
                'user_id'  => session()->get('user_id')
            ];

            $created = $model->insert($data);

            if ($created) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'type'     => 'success', 
                        'redirect' => url_to_pager('tasks'), 
                        'message'  => 'Task created successfully!'
                    ]);
                }
                return redirect_to_pager("tasks", [], [
                    'id'       => 'flash-message',
                    'type'     => 'success',
                    'position' => 'bottom-right',
                    'dismiss'  => false,
                    'message'  => "Task created successfully!"
                ]);
            }

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'type'     => 'error', 
                    'redirect' => url_to_pager('tasks'), 
                    'message'  => 'Could not create task! Please contact the administrator.'
                ]);
            }
            return redirect_to_pager("tasks", [], [
                'id'       => 'flash-message',
                'type'     => 'error',
                'position' => 'bottom-right',
                'dismiss'  => false,
                'message'  => 'Could not create task! Please contact the administrator.'
            ]);
        }
        
        return $this->renderView('add-task', $data);
    }

    /**
     * Update an existing task.
     * For a GET request, it returns the edit form populated with task data.
     * For a POST request, it updates the task and returns a JSON response.
     */
    public function update($id)
    {
        $model = new TasksModel();
        $task = $model->find($id);
        if (!$task) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            // return $this->response->setStatusCode(404, 'Task not found');
        }
        $data['task'] = $task;
        $data['pageTitle'] = 'Edit Task';
        $data['categories'] = $this->categories->findAll();
        
        if ($this->request->getPost()) {

            $validation = Services::validation();
            // Define validation rules
            $rules = [
                'title' => 'required',
                'description'     => 'required',
                'category'  => 'required',
                'deadline' => 'required'
            ];

            // Run validation
            if (!$this->validate($rules)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'type'    => 'error',
                        'message' => $validation->getErrors()
                    ]);
                }
                // exit(url_to_pager('edit-task'));
                return redirect_back([
                    'id'       => 'flash-message',
                    'type'     => 'error',
                    'position' => 'bottom-right',
                    'dismiss'  => false,
                    'message'  => $validation->listErrors()
                ]);
            }

            $data = [
                'title'       => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'category'    => $this->request->getPost('category'),
                'deadline'    => $this->request->getPost('deadline'),
            ];
            
            $updated = $model->update($id, $data);
            if ($updated) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'type'     => 'success', 
                        'redirect' => url_to_pager('tasks'), 
                        'message'  => 'Task updated successfully!'
                    ]);
                }
                return redirect_to_pager("tasks", [], [
                    'id'       => 'flash-message',
                    'type'     => 'success',
                    'position' => 'bottom-right',
                    'dismiss'  => false,
                    'message'  => "Task updated successfully!"
                ]);
            }

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'type'     => 'success', 
                    'redirect' => url_to_pager('tasks'), 
                    'message'  => 'Could not update task! Please contact the administrator.'
                ]);
            }
            return redirect_to_pager("tasks", [], [
                'id'       => 'flash-message',
                'type'     => 'success',
                'position' => 'bottom-right',
                'dismiss'  => false,
                'message'  => 'Could not update task! Please contact the administrator.'
            ]);
        }
        
        // For a GET request, display the edit form.
        return $this->renderView('edit-task', $data);
    }

    /**
     * Delete a task.
     */
    public function delete($id)
    {
        $model = new TasksModel();

        // Check if the record exists
        $task = $model->find($id);
        if (!$task) {
            return $this->response->setStatusCode(404, 'Task not found');
        }

        $model->delete($id);
        return $this->response->setJSON([
            'type'    => 'success', 
            'message' => 'Task deleted!',
            'csrf_token' => csrf_hash()  // Pass new token to frontend
        ]);
    }
}
