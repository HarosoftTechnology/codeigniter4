<?php

namespace App\Controllers;

use App\Models\TaskCategoryModel;
use Config\Services;

class TaskCategory extends BaseController
{
    public function index()
    {
        $model = new TaskCategoryModel();
        $data['pageTitle'] = 'Task Categories';
        $data['categories'] = $model->findAll(); // Fetch all task categories from the database
        
        return $this->renderView('task-categories', $data);
    }

    /**
     * Create a new task.
     * This method can handle both GET (to show the form) and POST (to process submission).
     */
    public function create()
    {
        $data['pageTitle'] = "Create Task Category";

        if ($this->request->getPost()) {
            $validation = Services::validation();
            // Define validation rules
            $rules = ['name' => 'required'];

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
            $model = new TaskCategoryModel();
            $data = ['name' => $this->request->getPost('name')];

            $created = $model->insert($data);

            if ($created) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'type'     => 'success', 
                        'redirect' => url_to_pager('task-categories'), 
                        'message'  => 'Category created successfully!'
                    ]);
                }
                return redirect_to_pager("task-categories", [], [
                    'id'       => 'flash-message',
                    'type'     => 'success',
                    'position' => 'bottom-right',
                    'dismiss'  => false,
                    'message'  => "Category created successfully!"
                ]);
            }

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'type'     => 'error', 
                    'redirect' => url_to_pager('tasks'), 
                    'message'  => 'Could not create task category! Please contact the administrator.'
                ]);
            }
            return redirect_to_pager("task-categories", [], [
                'id'       => 'flash-message',
                'type'     => 'error',
                'position' => 'bottom-right',
                'dismiss'  => false,
                'message'  => 'Could not create task category! Please contact the administrator.'
            ]);
        }
        
        return $this->renderView('add-category', $data);
    }

    /**
     * Update an existing task.
     * For a GET request, it returns the edit form populated with task data.
     * For a POST request, it updates the task and returns a JSON response.
     */
    public function update($id)
    {
        $model = new TaskCategoryModel();
        $category = $model->find($id);
        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $data['category'] = $category;
        $data['pageTitle'] = 'Edit Task Category';
        
        if ($this->request->getPost()) {

            $validation = Services::validation();
            // Define validation rules
            $rules = ['name' => 'required',];

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
                    'message'  => $validation->listErrors()
                ]);
            }

            $data = ['name' => $this->request->getPost('name')];
            
            $updated = $model->update($id, $data);
            if ($updated) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'type'     => 'success', 
                        'redirect' => url_to_pager('task-category'), 
                        'message'  => 'Task updated successfully!'
                    ]);
                }
                return redirect_to_pager("task-categories", [], [
                    'id'       => 'flash-message',
                    'type'     => 'success',
                    'position' => 'bottom-right',
                    'dismiss'  => false,
                    'message'  => "Task updated successfully!"
                ]);
            }

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'type'     => 'error', 
                    'redirect' => url_to_pager('task-categories'), 
                    'message'  => 'Could not update task! Please contact the administrator.'
                ]);
            }
            return redirect_back([
                'id'       => 'flash-message',
                'type'     => 'error',
                'position' => 'bottom-right',
                'dismiss'  => false,
                'message'  => 'Could not update task! Please contact the administrator.'
            ]);
        }
        
        // For a GET request, display the edit form.
        return $this->renderView('edit-category', $data);
    }

    /**
     * Delete a task.
     */
    public function delete($id)
    {
        $model = new TaskCategoryModel();

        // Check if the record exists
        $task = $model->find($id);
        if (!$task) {
            return $this->response->setStatusCode(404, 'Task not found');
        }

        $model->delete($id);
        return $this->response->setJSON([
            'type'    => 'success', 
            'message' => 'Task removed!',
            'csrf_token' => csrf_hash()  // Pass new token to frontend
        ]);
    }
}
