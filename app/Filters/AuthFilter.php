<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if the user is authenticated; adjust this check to fit your auth logic.
        // For example, using sessions:
        if (! session()->has('isLoggedIn')) {
            // setFlashdata("You are not authorized to access admin page", [
            //     'type' => 'success', 
            //     'class' => 'text-red-500',
            // ]);
            // Redirect unauthenticated users to your 'login' route.
            return redirect_to_pager("login", array(), [
                'id' => 'flash-message', 
                'type' => 'error', 
                'position' => 'bottom-right', 
                'dismiss' => false, 
                'message' => "You must login to access that page"
            ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // You can leave this empty if there's nothing to do after the controller runs.
    }
}
