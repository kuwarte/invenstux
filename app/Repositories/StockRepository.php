<?php

class StockRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function adjustStock($productId, $warehouseId, $type, $quantity, $userId, $notes = '')
    {
        $stmt = $this->db->prepare('
            CALL sp_adjust_stock(
                :product_id,
                :warehouse_id,
                :type,
                :quantity,
                :user_id,
                :notes,
                @new_quantity,
                @status,
                @message
            )
        ');

        $stmt->bindValue(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindValue(':warehouse_id', $warehouseId, PDO::PARAM_INT);
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);
        $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':notes', $notes, PDO::PARAM_STR);
        $stmt->execute();

        $result = $this->db->query('
            SELECT @new_quantity AS new_quantity,
                   @status AS status,
                   @message AS message
        ')->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function transferStock(
        int $productId,
        int $fromWarehouseId,
        int $toWarehouseId,
        int $quantity,
        int $userId,
        string $notes = ''
    ): array {
        $stmt = $this->db->prepare('
            CALL sp_transfer_stock(
                :product_id,
                :from_warehouse,
                :to_warehouse,
                :quantity,
                :user_id,
                :notes,
                @status,
                @message
            )
        ');

        $stmt->bindValue(':product_id',    $productId,       PDO::PARAM_INT);
        $stmt->bindValue(':from_warehouse', $fromWarehouseId, PDO::PARAM_INT);
        $stmt->bindValue(':to_warehouse',   $toWarehouseId,   PDO::PARAM_INT);
        $stmt->bindValue(':quantity',       $quantity,        PDO::PARAM_INT);
        $stmt->bindValue(':user_id',        $userId,          PDO::PARAM_INT);
        $stmt->bindValue(':notes',          $notes,           PDO::PARAM_STR);
        $stmt->execute();

        return $this->db->query('
            SELECT @status AS status, @message AS message
        ')->fetch(PDO::FETCH_ASSOC);
    }

    public function getLowStock()
    {
        $stmt = $this->db->query('
            SELECT * FROM vw_low_stock_alert
            ORDER BY stock_status DESC, units_below_threshold DESC
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWarehouseSummary()
    {
        $stmt = $this->db->query('
            SELECT * FROM vw_warehouse_stock_summary
            ORDER BY total_inventory_value DESC
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMovements($limit = 100, $offset = 0)
    {
        $stmt = $this->db->prepare('
            SELECT * FROM vw_stock_movements_detailed
            LIMIT :limit OFFSET :offset
        ');
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMovementsByProduct($productId, $limit = 50)
    {
        $stmt = $this->db->prepare('
            SELECT * FROM vw_stock_movements_detailed
            WHERE product_id = :product_id
            ORDER BY movement_date DESC
            LIMIT :limit
        ');
        $stmt->bindValue(':product_id', (int)$productId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMovementsByWarehouse($warehouseId, $limit = 50)
    {
        $stmt = $this->db->prepare('
            SELECT * FROM vw_stock_movements_detailed
            WHERE warehouse_id = :warehouse_id
            ORDER BY movement_date DESC
            LIMIT :limit
        ');
        $stmt->bindValue(':warehouse_id', (int)$warehouseId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStock($productId, $warehouseId)
    {
        $stmt = $this->db->prepare('
            SELECT * FROM product_warehouse 
            WHERE product_id = ? AND warehouse_id = ?
        ');
        $stmt->execute([$productId, $warehouseId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateThresholds($productId, $warehouseId, $minStock, $maxStock)
    {
        $stmt = $this->db->prepare('
            UPDATE product_warehouse 
            SET min_stock = ?, max_stock = ? 
            WHERE product_id = ? AND warehouse_id = ?
        ');
        $stmt->execute([$minStock, $maxStock, $productId, $warehouseId]);
    }
}
