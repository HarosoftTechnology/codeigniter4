<?php 

namespace App\Libraries;

use App\Models\UserModel;
use App\Models\UserRoleModel;
use App\Models\RolePermissionModel;

class PermissionChecker
{
    protected $user;
    protected $role;
    protected $permissions = [];

    /**
     * Initialize with the given user ID.
     *
     * @param int $userId
     * @throws \Exception if the user or role cannot be found.
     */
    public function __construct($userId)
    {
        $this->loadUserData($userId);
    }

    /**
     * Load user data along with role and permissions.
     *
     * @param int $userId
     * @throws \Exception when user or role cannot be found.
     */
    protected function loadUserData($userId)
    {
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            throw new \Exception("User not found");
        }

        $this->user = $user;

        // The user record should contain a 'role' field referencing the user_roles table.
        if (!isset($user['role'])) {
            throw new \Exception("User role not set");
        }
        
        $roleModel = new UserRoleModel();
        $role    = $roleModel->find($user['role']);

        if (!$role) {
            throw new \Exception("User role not found");
        }

        $this->role = $role;

        // Parse the comma-separated permission IDs
        if (!empty($role['permission'])) {
            $permissionIds = array_map('trim', explode(',', $role['permission']));
            
            $rolePermissionModel = new RolePermissionModel();
            foreach ($permissionIds as $permId) {
                $permission = $rolePermissionModel->find($permId);
                if ($permission) {
                    // Save the alias for easier permission checks.
                    $this->permissions[] = $permission['alias'];
                }
            }
        }
    }

    /**
     * Check if the user has a specific permission based on its alias.
     *
     * @param string $alias The alias of the permission (e.g., 'access-admin').
     * @return bool
     */
    public function hasPermission($alias)
    {
        return in_array($alias, $this->permissions);
    }

    /**
     * Get all permission aliases for the user.
     *
     * @return array
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * Get the role title.
     *
     * @return string
     */
    public function getRoleTitle()
    {
        return $this->role['role_title'];
    }
}
