<?php

class PermissionRepository
{
    private PDO $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getPermissionsByRole(int $roleId): array
    {
        $stmt = $this->db->prepare("
            SELECT p.id, p.name, p.description 
            FROM permissions p
            INNER JOIN role_permissions rp ON p.id = rp.permission_id
            WHERE rp.role_id = ?
            ORDER BY p.name
        ");
        $stmt->execute([$roleId]);
        return $stmt->fetchAll();
    }

    public function roleHasPermission(int $roleId, string $permissionName): bool
    {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM role_permissions rp
            JOIN permissions p ON rp.permission_id = p.id
            WHERE rp.role_id = ? AND p.name = ?
            LIMIT 1
        ");
        $stmt->execute([$roleId, $permissionName]);
        return (bool) $stmt->fetchColumn();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT id, name, description 
            FROM permissions 
            WHERE ORDER BY name
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPermissionsByRoles(array $roleIds): array
    {
        if (empty($roleIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($roleIds), '?'));

        $stmt = $this->db->prepare("
            SELECT DISTINCT p.id, p.name
            FROM permissions p
            INNER JOIN role_permissions rp ON p.id = rp.permission_id
            WHERE rp.role_id IN ($placeholders)
            ORDER BY p.name
        ");

        $stmt->execute($roleIds);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
