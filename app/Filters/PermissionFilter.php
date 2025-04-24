<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Libraries\PermissionChecker;

class PermissionFilter implements FilterInterface
{
    /**
     * Called before the controller is executed.
     *
     * @param RequestInterface $request
     * @param array|null $arguments This should contain the required permission alias.
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Retrieve the user ID from session.
        $session = session();
        $userId  = $session->get('user_id');

        if (!$userId) {
            // Not logged in; redirect to login.
            return redirect()->to('/login');
        }

        // The first argument should be the required permission alias.
        $requiredPermission = $arguments[0] ?? null;

        if (!$requiredPermission) {
            // If no permission is required, let the request continue.
            return;
        }

        try {
            $permissionChecker = new PermissionChecker($userId);
        } catch (\Exception $e) {
            // If user or role data cannot be loaded, redirect to login with error.
            return redirect()->to('/login')->with('error', $e->getMessage());
        }

        // Check if the user has the required permission.
        if (!$permissionChecker->hasPermission($requiredPermission)) {
            // You can choose to either show a custom 403 view or redirect.
            // Here, we'll return a 403 response with a custom error view.
            $response = service('response');
            $response->setStatusCode(403);
            echo view('errors/html/error_403', array('message' => "Access Denied!")); // Create this view to display an appropriate message.
            exit;
        }
    }

    /**
     * Called after the controller is executed.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array|null $arguments
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No actions are needed after the controller.
    }
}
