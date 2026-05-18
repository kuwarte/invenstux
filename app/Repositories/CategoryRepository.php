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

    public function getFiltered(string $search): array
    {
        if (empty($search)) {
            
            $stmt = $this->db->query("
                SELECT 
                    c.*,
                    p.name AS parent_name,
                    COUNT(pr.id) AS product_count
                FROM categories c
                LEFT JOIN categories p ON c.parent_id = p.id
                LEFT JOIN products pr ON c.id = pr.category_id
                GROUP BY c.id, c.name, c.description, c.parent_id, c.created_at, p.name
                ORDER BY c.parent_id IS NULL DESC, c.parent_id, c.name
            ");
            return $stmt->fetchAll();
        }

        $stmt = $this->db->prepare("
            WITH RECURSIVE
            direct_matches AS (
                SELECT id
                FROM categories
                WHERE name LIKE ? OR description LIKE ?
            ),
            descendants AS (
                SELECT c.id
                FROM categories c
                INNER JOIN direct_matches dm ON c.id = dm.id
                UNION ALL
                SELECT c.id
                FROM categories c
                INNER JOIN descendants d ON c.parent_id = d.id
            ),
            all_ids AS (
                SELECT id FROM descendants
                UNION
                SELECT parent_id AS id FROM categories
                WHERE id IN (SELECT id FROM descendants) AND parent_id IS NOT NULL
            )
            SELECT 
                c.*,
                p.name AS parent_name,
                COUNT(pr.id) AS product_count
            FROM categories c
            LEFT JOIN categories p ON c.parent_id = p.id
            LEFT JOIN products pr ON c.id = pr.category_id
            WHERE c.id IN (SELECT id FROM all_ids)
            GROUP BY c.id, c.name, c.description, c.parent_id, c.created_at, p.name
            ORDER BY c.parent_id IS NULL DESC, c.parent_id, c.name
        ");

        $term = '%' . $search . '%';
        $stmt->execute([$term, $term]);
        return $stmt->fetchAll();
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
