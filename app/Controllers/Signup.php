<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use Config\Services;

class Signup extends BaseController
{
    public function index()
    {
        BaseController::removeMenu(true);
        if (session()->get('user_id')) {
            return redirect()->route('dashboard'); // Redirect if logged in
        }

        $data['pageTitle'] = "Signup Page";

        if ($this->request->getPost()) {
            $validation = Services::validation();
            // Define validation rules
            $rules = [
                'firstname' => 'required',
                'email'     => 'required|valid_email|is_unique[users.email]',
                'password'  => 'required|min_length[6]',
                'cpassword' => 'required|matches[password]'
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
                // return redirect()->route('signup')->withInput()->with('validation', $validation);
            }
            
            // Create a new user record in the database
            $model = new UserModel();
            $userData  = [
                'firstname' => $this->request->getPost('firstname'),
                'lastname'  => $this->request->getPost('lastname'),
                'email'     => $this->request->getPost('email'),
                // Pass the plain text password so that the callback can hash it.
                'password'  => $this->request->getPost('password'),
                'role'  => 3
            ];
            
            $created = $model->insert($userData );
            if ($created) {
                $userId = $model->insertID(); // Get the new user's ID

                // Auto-login the user by initializing session data
                $session = session();
                $session->set([
                    'user_id'    => $userId,
                    'user_name'  => $this->request->getPost('firstname'),
                    'user_email' => $this->request->getPost('email'),
                    'isLoggedIn' => true
                ]);

                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'type'     => 'success', 
                        'redirect' => url_to_pager('dashboard'), 
                        'message'  => 'Registration successful!'
                    ]);
                }
                return redirect_to_pager("dashboard", [], [
                    'id'       => 'flash-message',
                    'type'     => 'error',
                    'position' => 'bottom-right',
                    'dismiss'  => false,
                    'message'  => "Registration successful!"
                ]);
            }
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'type'     => 'success', 
                    'redirect' => url_to_pager('dashboard'), 
                    'message'  => 'Could not signup! Please contact the administrator.'
                ]);
            }
            return redirect_to_pager("dashboard", [], [
                'id'       => 'flash-message',
                'type'     => 'success',
                'position' => 'bottom-right',
                'dismiss'  => false,
                'message'  => 'Could not signup! Please contact the administrator.'
            ]);
        }
        
        return view('signup', $data);
    }
}
