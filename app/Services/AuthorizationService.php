<?php

require_once __DIR__ . '/../../core/Session.php';

class AuthorizationService
{
    private PermissionRepository $permissionRepo;

    public function __construct(PDO $db)
    {
        require_once __DIR__ . '/../Repositories/PermissionRepository.php';
        $this->permissionRepo = new PermissionRepository($db);
    }

    public function can(string $permissionName): bool
    {
        $roleId = Session::get('user_role');
        
        if (!$roleId) {
            return false;
        }

        return $this->permissionRepo->roleHasPermission($roleId, $permissionName);
    }

    public function requirePermission(string $permissionName): bool
    {
        return $this->can($permissionName);
    }

    public function canAny(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->can($permission)) {
                return true;
            }
        }
        return false;
    }

    public function getUserPermissions(): array
    {
        $roleId = Session::get('user_role');
        
        if (!$roleId) {
            return [];
        }

        return $this->permissionRepo->getPermissionsByRole($roleId);
    }
}
