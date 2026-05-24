<?php
class ProductRepository
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
                product_id AS id,
                sku,
                product_name AS name,
                description,
                unit_of_measure,
                unit_cost,
                is_active,
                category_name,
                category_id
            FROM vw_product_performance
            ORDER BY product_name
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT 
                product_id AS id,
                sku,
                product_name AS name,
                description,
                unit_of_measure,
                unit_cost,
                is_active,
                category_name,
                category_id
            FROM vw_product_performance
            WHERE product_id = ?
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function getProductsWithWarehouseStock(?int $warehouseId = null): array
    {
        if ($warehouseId) {
            $stmt = $this->db->prepare("
                SELECT p.*, c.name as category_name, 
                       COALESCE(pw.quantity, 0) as stock
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN product_warehouse pw 
                       ON p.id = pw.product_id 
                       AND pw.warehouse_id = ?
                WHERE COALESCE(pw.quantity, 0) > 0
                       AND p.is_active = 1
                ORDER BY p.name
            ");
            $stmt->execute([$warehouseId]);
        } else {
            $stmt = $this->db->query("
                SELECT p.*, c.name as category_name, 
                       COALESCE(pw.quantity, 0) as stock
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN product_warehouse pw ON p.id = pw.product_id
                       WHERE p.is_active = 1
                ORDER BY p.name
            ");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO products (category_id, sku, name, description, unit_of_measure, unit_cost, is_active)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['category_id'] ?? null,
            $data['sku'],
            $data['name'],
            $data['description'] ?? '',
            $data['unit_of_measure'] ?? 'pcs',
            $data['unit_cost'] ?? 0,
            $data['is_active'] ?? 1
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare("
            UPDATE products 
            SET category_id = ?, 
                sku = ?, 
                name = ?, 
                description = ?, 
                unit_of_measure = ?, 
                unit_cost = ?,
                is_active = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $data['category_id'] ?? null,
            $data['sku'],
            $data['name'],
            $data['description'] ?? '',
            $data['unit_of_measure'] ?? 'pcs',
            $data['unit_cost'] ?? 0,
            $data['is_active'] ?? 1,
            $id
        ]);
    }

    public function deactivate(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE products SET is_active = 0 WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function search(string $query): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM vw_product_performance
            WHERE (product_name LIKE :query 
                OR sku LIKE :query)
                AND is_active = 1
            LIMIT 20
        ");
        $searchTerm = "%$query%";
        $stmt->bindValue(':query', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActive(): array
    {
        $stmt = $this->db->query("
            SELECT 
                product_id AS id,
                sku,
                product_name AS name,
                description,
                unit_of_measure,
                unit_cost,
                is_active,
                category_name,
                category_id
            FROM vw_product_performance
            WHERE is_active = 1
            ORDER BY product_name
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFiltered(bool $showInactive, string $search, string $categoryId): array
    {
        $sql = "SELECT 
                product_id AS id,
                sku,
                product_name AS name,
                description,
                unit_of_measure,
                unit_cost,
                is_active,
                category_name,
                category_id
            FROM vw_product_performance
            WHERE 1=1";
        
        $params = [];
        
        if (!$showInactive) {
            $sql .= " AND is_active = 1";
        }
        
        if (!empty($search)) {
            $sql .= " AND (product_name LIKE ? OR sku LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if (!empty($categoryId)) {
            $sql .= " AND category_id = ?";
            $params[] = $categoryId;
        }
        
        $sql .= " ORDER BY product_name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function skuExists(string $sku): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM products WHERE sku = ? LIMIT 1");
        $stmt->execute([$sku]);
        return $stmt->fetch() !== false;
    }

    public function countActive(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM products WHERE is_active = 1");
        return (int) $stmt->fetch()['count'];
    }
}
