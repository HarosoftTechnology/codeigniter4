<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;

class User extends BaseController
{
    public function index()
    {
        //
    }

    public function edit($id = null)
    {
        $model = new UserModel();

        // If an ID is not provided, default to the logged-in user.
        // (Assuming the user's ID is stored in the session under 'user_id')
        if ($id === null) {
            $id = session()->get('user_id');
        }

        // Retrieve the user record from the database
        $user = $model->find($id);
        if (!$user) {
            throw new PageNotFoundException('User not found');
            return $this->response->setStatusCode(404, 'Task not found');
        }

        // Check if the form is submitted (i.e., the request method is POST)
        if ($this->request->getPost()) {
            // Gather updated data from the form
            $updateData = [
                'firstname' => $this->request->getPost('firstname'),
                'lastname'  => $this->request->getPost('lastname'),
                'email'     => $this->request->getPost('email'),
            ];

            // Only update the password if a new one is provided.
            // Otherwise, leave it unchanged.
            $password = $this->request->getPost('password');
            if (!empty($password)) {
                $updateData['password'] = $password;
            }

            // Attempt to update the record. The model's callbacks
            // (like hashPassword) will automatically handle any transformations.
            if ($model->update($id, $updateData)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'type'     => 'success', 
                        'redirect' => url_to_pager('tasks'), 
                        'message'  => 'User updated successfully!'
                    ]);
                }
                return redirect_to_pager("tasks", [], [
                    'id'       => 'flash-message',
                    'type'     => 'success',
                    'position' => 'bottom-right',
                    'dismiss'  => false,
                    'message'  => "User updated successfully!"
                ]);
            }

            // If update fails, collect error messages to show again
            $data['errors'] = $model->errors();
        }

        // Prepare data for the view
        $data['user']      = $user;
        $data['pageTitle'] = 'Edit User';

        return $this->renderView('user/edit', $data);
    }
}
