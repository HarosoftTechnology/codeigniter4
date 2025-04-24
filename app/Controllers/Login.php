<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use Config\Services;

class Login extends BaseController
{
    public function __construct()
    {
        // Load required helpers
        helper(['form', 'url']);
    }

    public function index()
    {
        BaseController::removeMenu(true);
        BaseController::setTitle("Login");
        if (session()->get('isLoggedIn')) {
            return redirect()->route('dashboard'); // Redirect if already logged in
        }
        
        set_meta_tags([
            'description' => 'XXXXThis is a sample description for our website page.',
            'keywords'    => 'CodeIgniter4, Meta Tags, PHP, Example',
            'author'      => 'Your Name'
        ]);

        $data = [
            'pageTitle'  => "Login Page",
            'validation' => session()->getFlashdata('validation')
        ];

        if ($this->request->getPost()) {
            $validation = Services::validation();
            $session    = session();

            // Define validation rules
            $rules = [
                'email'    => 'required|valid_email',
                'password' => 'required|min_length[6]'
            ];

            // Run validation and respond accordingly
            if (!$this->validate($rules)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'type'    => 'error',
                        'message' => $validation->getErrors()
                    ]);
                }
                return redirect_to_pager("login", [], [
                    'id'       => 'flash-message',
                    'type'     => 'error',
                    'position' => 'bottom-right',
                    'dismiss'  => false,
                    'message'  => $validation->listErrors()
                ]);
            }

            // Get posted email and password
            $model    = new UserModel();
            $email    = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $user = $model->where('email', $email)->first();

            if ($user && password_verify($password, $user['password'])) {
                $session->set([
                    'user_id'      => $user['id'],
                    'isLoggedIn'   => true,
                    'last_activity'=> time()
                ]);

                // Check if a resume URL was stored (from a previous session timeout)
                $redirectUrl = !empty($user['resume']) ? $user['resume'] : url_to_pager('dashboard');

                // Clear the resume field after using it
                $model->update($user['id'], ['resume' => null]);

                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'type'     => 'success', 
                        'redirect' => url_to_pager('dashboard'), 
                        'message'  => 'Login successful'
                    ]);
                }
                return redirect_to_pager("dashboard", [], [
                    'id'       => 'flash-message',
                    'type'     => 'error',
                    'position' => 'bottom-right',
                    'dismiss'  => false,
                    'message'  => "Login successful"
                ]);
            }

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'type'     => 'error', 
                    'redirect' => url_to_pager('dashboard'), 
                    'message'  => 'Invalid email or password!'
                ]);
            }
            return redirect_to_pager("dashboard", [], [
                'id'       => 'flash-message',
                'type'     => 'error',
                'position' => 'bottom-right',
                'dismiss'  => false,
                'message'  => 'Invalid email or password!'
            ]);
        }
        
        return view('login', $data);
    }

    public function forgot_password()
    {
        BaseController::removeMenu(true);
        if (session()->get('isLoggedIn')) {
            return redirect()->route('dashboard'); // Redirect if already logged in
        }

        $data = [
            'pageTitle'  => "Login Page",
            'validation' => session()->getFlashdata('validation')
        ];

        if ($this->request->getPost()) {
            $validation = Services::validation();
            $session    = session();

            // Define validation rules
            $rules = ['email'    => 'required|valid_email'];

            // Run validation and respond accordingly
            if (!$this->validate($rules)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'type'    => 'error',
                        'message' => $validation->getErrors()
                    ]);
                }
                return redirect()->route('login')->with('validation', $validation->listErrors());
            }

            // Get posted email and password
            $model    = new UserModel();
            $email    = $this->request->getPost('email');

            $user = $model->where('email', $email)->first();

            if (true) {
                $session->set([
                    'user_id'      => $user['id'],
                    'isLoggedIn'   => true,
                    'last_activity'=> time()
                ]);

                // Clear the resume field after using it
                $model->update($user['id'], ['resume' => null]);

                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'type'     => 'success', 
                        'redirect' => url_to_pager('dashboard'), 
                        'message'  => 'Login successful'
                    ]);
                }
                return redirect_to_pager("dashboard", [], [
                    'id'       => 'flash-message',
                    'type'     => 'error',
                    'position' => 'bottom-right',
                    'dismiss'  => false,
                    'message'  => "Login successful"
                ]);
            }

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'type'     => 'success', 
                    'redirect' => url_to_pager('dashboard'), 
                    'message'  => 'Invalid email or password!'
                ]);
            }
            return redirect_to_pager("dashboard", [], [
                'id'       => 'flash-message',
                'type'     => 'success',
                'position' => 'bottom-right',
                'dismiss'  => false,
                'message'  => 'Invalid email or password!'
            ]);
        }
        
        return view('forgot-password', $data);
    }

    public function reset_password()
    {
        BaseController::removeMenu(true);
        if (session()->get('isLoggedIn')) {
            return redirect()->route('dashboard'); // Redirect if already logged in
        }

        $data = [
            'pageTitle'  => "Login Page",
            'validation' => session()->getFlashdata('validation')
        ];

        if ($this->request->getPost()) {
            
        }
        
        return view('reset-password', $data);
    }
}
