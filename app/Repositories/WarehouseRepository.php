<?php

class WarehouseRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll(bool $showInactive = false): array
    {
        $query = "SELECT w.*, u.full_name as manager_name FROM warehouses w LEFT JOIN users u ON w.manager_id = u.id";
        if (!$showInactive) {
            $query .= " WHERE w.is_active = 1";
        }
        $query .= " ORDER BY w.name";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll();
    }

    public function getFiltered(bool $showInactive, string $search): array
    {
        $sql = "SELECT w.*, u.full_name as manager_name FROM warehouses w LEFT JOIN users u ON w.manager_id = u.id WHERE 1=1";
        $params = [];
        
        if (!$showInactive) {
            $sql .= " AND w.is_active = 1";
        }
        
        if (!empty($search)) {
            $sql .= " AND (w.name LIKE ? OR w.location LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        $sql .= " ORDER BY w.name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM warehouses WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO warehouses (manager_id, name, location, is_active) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['manager_id'] ?? null,
            $data['name'],
            $data['location'],
            $data['is_active'] ?? 1
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare("UPDATE warehouses SET manager_id = ?, name = ?, location = ?, is_active = ? WHERE id = ?");
        $stmt->execute([
            $data['manager_id'] ?? null,
            $data['name'],
            $data['location'],
            $data['is_active'] ?? 1,
            $id
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE warehouses SET is_active = 0 WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function countActive(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM warehouses WHERE is_active = 1");
        return (int) $stmt->fetch()['count'];
    }
}
