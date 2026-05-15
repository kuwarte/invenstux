<?php

class DashboardRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getStats(): array
    {
        $stmt = $this->db->query("
            SELECT 
                (SELECT COUNT(*) FROM products WHERE is_active = 1) as total_products,
                (SELECT COUNT(*) FROM warehouses WHERE is_active = 1) as total_warehouses,
                (SELECT COUNT(*) FROM categories) as total_categories,
                (SELECT COUNT(*) FROM product_warehouse WHERE quantity <= min_stock AND min_stock > 0) as low_stock_items
        ");
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
