<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Check if the user is logged in.
        if (! $session->has('isLoggedIn')) {
            // Not logged in; redirect to login.

            return redirect_to_pager("login", array(), [
                'id' => 'flash-message', 
                'type' => 'error', 
                'position' => 'bottom-right', 
                'dismiss' => false, 
                'message' => "You must login!"
            ]);
            return redirect()->route('login');
        }

        // Check if the user has the admin role.
        // This assumes you've set a 'role' property in session data.
        if ($session->get('role') !== 'admin') {
            // Optionally, set a flash error message.
            return redirect_to_pager("login", array(), [
                'id' => 'flash-message', 
                'type' => 'error', 
                'position' => 'bottom-right', 
                'dismiss' => false, 
                'message' => "You do not have admin access!"
            ]);
            
            $session->setFlashdata('error', 'You are not authorized to access this page.');
            // Not an admin; redirect to a default page (or an access denied page).
            return redirect()->to('/');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Additional logic after the controller is executed can go here.
    }
}
