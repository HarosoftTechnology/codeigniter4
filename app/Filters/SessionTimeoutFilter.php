<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\UserModel;

class SessionTimeoutFilter implements FilterInterface
{
    // Timeout duration in seconds (e.g., 1800 seconds = 30 minutes)
    protected $timeout = 1800;

    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Only check timeout for authenticated users.
        if ($session->has('user_id')) {

            // Initialize last activity if not set.
            if (!$session->has('last_activity')) {
                $session->set('last_activity', time());
            }

            $lastActivity = $session->get('last_activity');
            $currentTime  = time();

            // If the time difference exceeds the timeout...
            if (($currentTime - $lastActivity) > $this->timeout) {
                $user_id = $session->get('user_id');
                $currentUrl = current_url(); // Capture the current URL

                // If a user is logged in, update their resume field.
                if ($user_id) {
                    $userModel = new UserModel();
                    $userModel->update($user_id, ['resume' => $currentUrl]);
                }

                // Destroy the session.
                // $session->destroy();
                $session->remove(['user_id', 'username', 'isLoggedIn']);


                // Set flashdata to notify the user.
                // setFlashdata("Session timeout! Login again.", [
                //     'type' => 'error', 
                //     'class' => 'text-red-500', 
                // ]);
                return redirect_to_pager("login", array(), [
                    'id' => 'flash-message', 
                    'type' => 'error', 
                    'position' => 'bottom-right', 
                    'dismiss' => false, 
                    'message' => "Session timeout! Login again."
                ]);
                // $session->setFlashdata('timeout_message', 'Session timeout! Login again.');
                
                // Return a Response to halt further processing.
                return redirect()->route('login');
            }

            // Update the activity timestamp for each request.
            $session->set('last_activity', $currentTime);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed here.
    }
}
