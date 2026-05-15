<?php

class CategoryRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT 
                c.*,
                p.name AS parent_name
            FROM categories c
            LEFT JOIN categories p ON c.parent_id = p.id
            ORDER BY c.parent_id IS NULL DESC, c.parent_id, c.name
        ");
    
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO categories (parent_id, name, description) VALUES (?, ?, ?)");
        $stmt->execute([
            $data['parent_id'] ?? null,
            $data['name'],
            $data['description'] ?? ''
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare("UPDATE categories SET parent_id = ?, name = ?, description = ? WHERE id = ?");
        $stmt->execute([
            $data['parent_id'] ?? null,
            $data['name'],
            $data['description'] ?? '',
            $id
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function getRootCategories(): array
    {
        $stmt = $this->db->query("SELECT * FROM categories WHERE parent_id IS NULL ORDER BY name");
        return $stmt->fetchAll();
    }

    public function getChildrenOf(int $parentId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE parent_id = ? ORDER BY name");
        $stmt->execute([$parentId]);
        return $stmt->fetchAll();
    }

    public function count(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM categories");
        return (int) $stmt->fetch()['count'];
    }
}
