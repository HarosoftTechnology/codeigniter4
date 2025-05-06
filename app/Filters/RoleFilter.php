<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\UserModel;
use App\Models\UserRoleModel;

class RoleFilter implements FilterInterface
{
    /**
     * Called before the controller is executed.
     *
     * @param RequestInterface $request
     * @param array|null $arguments The first argument should be the required role alias (e.g., 'admin').
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $userId  = $session->get('user_id');

        // Redirect if the user is not authenticated.
        if (! session()->has('isLoggedIn')) {
            // Redirect unauthenticated users to your 'login' route.
            return redirect_to_pager("login", array(), [
                'id' => 'flash-message', 
                'type' => 'error', 
                'position' => 'bottom-right', 
                'dismiss' => false, 
                'message' => "You must login to access that page"
            ]);
        }

        // Ensure a required role alias is provided.
        if (empty($arguments) || empty($arguments[0])) {
            // If no role alias is provided, let the request continue.
            return;
        }

        $requiredAlias = trim($arguments[0]);

        // Load the user record using your existing UserModel.
        $userModel = new UserModel();
        $user      = $userModel->find($userId);

        if (!$user) {
            return redirect()->to('/login');
        }

        // Assuming your users table has a 'role' column linking to the user_roles table.
        if (!isset($user['role'])) {
            $response = service('response');
            $response->setStatusCode(403);
            // echo view('errors/403'); // Make sure you have this view for a friendly error message.
            exit;
        }

        // Retrieve the corresponding role record.
        $roleModel = new UserRoleModel();
        $role      = $roleModel->find($user['role']);

        if (!$role) {
            $response = service('response');
            $response->setStatusCode(403);
            echo view('errors/403');
            exit;
        }

        // Compare the role alias from the DB with the required alias (case-insensitive).
        if (strcasecmp(trim($role['alias']), $requiredAlias) !== 0) {
            $response = service('response');
            $response->setStatusCode(403);
            echo view('errors/html/error_403', array('message' => "Access Denied!"));
            exit;
        }

        // The role alias matches. Allow the request to continue.
        return;
    }

    /**
     * Called after the controller is executed.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No alterations needed after execution.
    }
}
