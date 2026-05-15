<?php

class ProductWarehouseRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getStock(int $productId, int $warehouseId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM product_warehouse WHERE product_id = ? AND warehouse_id = ?");
        $stmt->execute([$productId, $warehouseId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function updateQuantity(int $productId, int $warehouseId, int $quantity): void
    {
        $existing = $this->getStock($productId, $warehouseId);
        if ($existing) {
            $stmt = $this->db->prepare("UPDATE product_warehouse SET quantity = quantity + ? WHERE product_id = ? AND warehouse_id = ?");
            $stmt->execute([$quantity, $productId, $warehouseId]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO product_warehouse (product_id, warehouse_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$productId, $warehouseId, $quantity]);
        }
    }

    public function getLowStock(): array
    {
        $stmt = $this->db->query("
            SELECT p.name as product_name, w.name as warehouse_name, pw.quantity, pw.min_stock 
            FROM product_warehouse pw 
            JOIN products p ON pw.product_id = p.id 
            JOIN warehouses w ON pw.warehouse_id = w.id 
            WHERE pw.quantity <= pw.min_stock AND pw.min_stock > 0
        ");
        return $stmt->fetchAll();
    }

    public function getAllStock(array $filters = []): array
    {
        $sql = "
            SELECT p.name as product_name, p.sku, w.name as warehouse_name, w.id as warehouse_id, pw.quantity, pw.min_stock, pw.max_stock, c.name as category_name
            FROM product_warehouse pw 
            JOIN products p ON pw.product_id = p.id 
            JOIN warehouses w ON pw.warehouse_id = w.id 
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE 1=1
        ";
        
        $params = [];
        
        if (!empty($filters['warehouse_id'])) {
            $sql .= " AND pw.warehouse_id = ?";
            $params[] = $filters['warehouse_id'];
        }
        
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'critical') {
                $sql .= " AND pw.quantity <= pw.min_stock";
            } elseif ($filters['status'] === 'full') {
                $sql .= " AND pw.quantity >= pw.max_stock";
            } elseif ($filters['status'] === 'optimal') {
                $sql .= " AND pw.quantity > pw.min_stock AND pw.quantity < pw.max_stock";
            }
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (p.name LIKE ? OR p.sku LIKE ? OR c.name LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $sql .= " ORDER BY p.name, w.name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM product_warehouse");
        return $stmt->fetchAll();
    }

    public function updateThresholds(int $productId, int $warehouseId, int $minStock, int $maxStock): void
    {
        $existing = $this->getStock($productId, $warehouseId);
        if ($existing) {
            $stmt = $this->db->prepare("UPDATE product_warehouse SET min_stock = ?, max_stock = ? WHERE product_id = ? AND warehouse_id = ?");
            $stmt->execute([$minStock, $maxStock, $productId, $warehouseId]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO product_warehouse (product_id, warehouse_id, quantity, min_stock, max_stock) VALUES (?, ?, 0, ?, ?)");
            $stmt->execute([$productId, $warehouseId, $minStock, $maxStock]);
        }
    }

    public function countLowStock(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM product_warehouse WHERE quantity <= min_stock AND min_stock > 0");
        return (int) $stmt->fetch()['count'];
    }
}
