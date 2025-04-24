<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Logout extends Controller
{
    public function index()
    {
        // Destroy the user's session completely
        $session = session();
        $session->destroy();
        
        // Redirect to the login page (or another landing page) with cookies cleared
        return redirect()->route('login')->withCookies();
    }
}
