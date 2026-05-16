<?php

class StockMovementRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function logMovement(int $productId, int $warehouseId, string $type, int $quantity, int $userId, ?int $referenceId = null, ?string $notes = null)
    {
        $stmt = $this->db->prepare('
            INSERT INTO stock_movements 
            (product_id, warehouse_id, type, quantity, reference_id, user_id, notes)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $productId,
            $warehouseId,
            $type,
            $quantity,
            $referenceId,
            $userId,
            $notes
        ]);
        return $this->db->lastInsertId();
    }

    public function getMovementsByProduct(int $productId, int $limit = 100)
    {
        $stmt = $this->db->prepare('
            SELECT sm.*, 
                   p.name as product_name, 
                   w.name as warehouse_name,
                   u.full_name as user_name
            FROM stock_movements sm
            INNER JOIN products p ON sm.product_id = p.id
            INNER JOIN warehouses w ON sm.warehouse_id = w.id
            INNER JOIN users u ON sm.user_id = u.id
            WHERE sm.product_id = :product_id
            ORDER BY sm.created_at DESC
            LIMIT :limit
        ');
        $stmt->bindValue(':product_id', (int)$productId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getMovementsByWarehouse(int $warehouseId, int $limit = 100)
    {
        $stmt = $this->db->prepare('
            SELECT sm.*, 
                   p.name as product_name, 
                   w.name as warehouse_name,
                   u.full_name as user_name
            FROM stock_movements sm
            INNER JOIN products p ON sm.product_id = p.id
            INNER JOIN warehouses w ON sm.warehouse_id = w.id
            INNER JOIN users u ON sm.user_id = u.id
            WHERE sm.warehouse_id = :warehouse_id
            ORDER BY sm.created_at DESC
            LIMIT :limit
        ');
        $stmt->bindValue(':warehouse_id', (int)$warehouseId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllMovements(?int $type = null, int $limit = 100, int $offset = 0)
    {
        $sql = '
            SELECT sm.*, 
                   p.name as product_name, 
                   w.name as warehouse_name,
                   u.full_name as user_name
            FROM stock_movements sm
            INNER JOIN products p ON sm.product_id = p.id
            INNER JOIN warehouses w ON sm.warehouse_id = w.id
            INNER JOIN users u ON sm.user_id = u.id
        ';

        if ($type) {
            $sql .= ' WHERE sm.type = :type';
        }

        $sql .= ' ORDER BY sm.created_at DESC LIMIT :limit OFFSET :offset';

        $stmt = $this->db->prepare($sql);

        if ($type) {
            $stmt->bindValue(':type', $type, PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
