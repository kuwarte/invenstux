<?php

class DashboardRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    private function buildDateCondition(string $range): array
    {
        switch ($range) {
            case 'today':
                return [
                    'sale_date >= CURDATE() AND sale_date < CURDATE() + INTERVAL 1 DAY',
                    []
                ];

            case '7days':
                return [
                    'sale_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND sale_date < CURDATE() + INTERVAL 1 DAY',
                    []
                ];

            case '30days':
                return [
                    'sale_date >= DATE_SUB(CURDATE(), INTERVAL 29 DAY) AND sale_date < CURDATE() + INTERVAL 1 DAY',
                    []
                ];

            default:
                return [
                    'sale_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND sale_date < CURDATE() + INTERVAL 1 DAY',
                    []
                ];
        }
    }

    public function getAllTopProducts(string $range): array
    {
        list($dateSql, $params) = $this->buildDateCondition($range);

        $sql = "SELECT 
                    product_id,
                    product_name AS name,
                    product_sku AS sku,
                    SUM(units_sold) AS total_sold,
                    COALESCE(SUM(total_item_revenue), 0) AS total_revenue
                FROM vw_dashboard_sales_stream
                WHERE {$dateSql}
                GROUP BY product_id, product_name, product_sku
                ORDER BY total_revenue DESC"; 

        error_log("Subpage Detailed Query - Range: {$range}");

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getDashboardData(string $range): array
    {
        $globalStmt = $this->db->query('SELECT * FROM vw_dashboard_global_counters');
        $globalCounters = $globalStmt->fetch(PDO::FETCH_ASSOC) ?: [
            'total_products' => 0,
            'total_warehouses' => 0,
            'total_categories' => 0,
            'critical_low_stock' => 0
        ];

        list($dateSql, $params) = $this->buildDateCondition($range);

        $salesSql = "SELECT 
                        COUNT(DISTINCT sale_id) AS total_sales,
                        COALESCE(SUM(total_item_revenue), 0) AS total_revenue
                     FROM vw_dashboard_sales_stream 
                     WHERE {$dateSql}";

        $salesStmt = $this->db->prepare($salesSql);
        $salesStmt->execute($params);
        $salesStats = $salesStmt->fetch(PDO::FETCH_ASSOC) ?: ['total_sales' => 0, 'total_revenue' => 0];

        $topProductsSql = "SELECT 
                                product_id,
                                product_name AS name,
                                product_sku AS sku,
                                SUM(units_sold) AS total_sold,
                                COALESCE(SUM(total_item_revenue), 0) AS total_revenue
                           FROM vw_dashboard_sales_stream
                           WHERE {$dateSql}
                           GROUP BY product_id, product_name, product_sku
                           ORDER BY total_revenue DESC
                           LIMIT 5";

        $topProductsStmt = $this->db->prepare($topProductsSql);
        $topProductsStmt->execute($params);
        $topProducts = $topProductsStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return [
            'globalCounters' => $globalCounters,
            'salesStats'     => $salesStats,
            'topProducts'    => $topProducts
        ];
    }

    public function getStats(): array
    {
        $sql = "SELECT 
                    total_products, 
                    total_warehouses, 
                    total_categories 
                FROM vw_dashboard_global_counters 
                LIMIT 1";
                
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [
            'total_products' => 0,
            'total_warehouses' => 0,
            'total_categories' => 0
        ];
    }
}
