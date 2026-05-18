<?php

class SalesRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getTotalSalesCount(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM sales');
        return (int) $stmt->fetchColumn();
    }

    public function processSale(
        int $userId,
        string $cartJson,
        float $paymentAmount
    ): array {
        $stmt = $this->db->prepare('
            CALL sp_process_sale(
                :user_id,
                :cart_json,
                :payment_amount,
                @sale_id,
                @total_amount,
                @change_amount,
                @status,
                @message
            )
        ');

        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':cart_json', $cartJson);
        $stmt->bindValue(':payment_amount', $paymentAmount);

        $stmt->execute();
        $stmt->closeCursor();

        return $this->db->query('
            SELECT
                @sale_id       AS sale_id,
                @total_amount  AS total_amount,
                @change_amount AS change_amount,
                @status        AS status,
                @message       AS message
        ')->fetch(PDO::FETCH_ASSOC);
    }

    public function getSaleById(int $saleId): ?array
    {
        $stmt = $this->db->prepare('
            SELECT
                s.id AS sale_id,
                s.user_id,
                s.payment_amount,
                s.created_at,
                u.full_name AS cashier_name,
                COALESCE(SUM(si.quantity * si.price), 0) AS total_amount,
                (
                    s.payment_amount -
                    COALESCE(SUM(si.quantity * si.price), 0)
                ) AS change_amount
            FROM sales s
            LEFT JOIN users u
                ON s.user_id = u.id
            LEFT JOIN sale_items si
                ON s.id = si.sale_id
            WHERE s.id = ?
            GROUP BY
                s.id,
                s.user_id,
                s.payment_amount,
                s.created_at,
                u.full_name
        ');

        $stmt->execute([$saleId]);

        $sale = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$sale) {
            return null;
        }

        $sale['items'] = $this->getSaleItems($saleId);

        return $sale;
    }

    public function getSaleItems(int $saleId): array
    {
        $stmt = $this->db->prepare('
            SELECT
                si.*,
                p.name AS product_name,
                p.sku,
                (si.quantity * si.price) AS subtotal
            FROM sale_items si
            INNER JOIN products p
                ON si.product_id = p.id
            WHERE si.sale_id = ?
        ');

        $stmt->execute([$saleId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllSales(
        int $limit = 50,
        int $offset = 0
    ): array {
        $stmt = $this->db->prepare('
            SELECT *
            FROM vw_sales_dashboard
            LIMIT :limit OFFSET :offset
        ');

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFiltered(string $dateFrom, string $dateTo): array
    {
        $sql = 'SELECT * FROM vw_sales_dashboard WHERE 1=1';
        $params = [];

        if (!empty($dateFrom)) {
            $sql .= ' AND sale_date_only >= ?';
            $params[] = $dateFrom;
        }

        if (!empty($dateTo)) {
            $sql .= ' AND sale_date_only <= ?';
            $params[] = $dateTo;
        }

        $sql .= ' ORDER BY sale_date DESC LIMIT 100';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTodayStats(): array
    {
        $stmt = $this->db->query('
            SELECT
                COUNT(DISTINCT s.id) AS total_sales,
                COALESCE(SUM(si.quantity * si.price), 0) AS total_revenue,
                COALESCE(SUM(si.quantity), 0) AS total_items_sold,
                COALESCE(AVG(si.quantity * si.price), 0) AS avg_transaction
            FROM sales s
            LEFT JOIN sale_items si
                ON s.id = si.sale_id
            WHERE DATE(s.created_at) = CURDATE()
        ');

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTopSellingProducts(
        int $limit = 10,
        int $days = 30
    ): array {
        $stmt = $this->db->prepare('
            SELECT
                p.id,
                p.name,
                p.sku,
                SUM(si.quantity) AS total_sold,
                SUM(si.quantity * si.price) AS total_revenue,
                COUNT(DISTINCT si.sale_id) AS transaction_count
            FROM products p
            INNER JOIN sale_items si
                ON p.id = si.product_id
            INNER JOIN sales s
                ON si.sale_id = s.id
            WHERE s.created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
            GROUP BY p.id, p.name, p.sku
            ORDER BY total_sold DESC
            LIMIT :limit
        ');

        $stmt->bindValue(':days', $days, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDailySalesSummary(
        int $days = 30
    ): array {
        $stmt = $this->db->prepare('
            SELECT *
            FROM vw_daily_sales_summary
            WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
            ORDER BY sale_date DESC
        ');

        $stmt->bindValue(':days', $days, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSalesByDateRange(
        string $startDate,
        string $endDate
    ): array {
        $stmt = $this->db->prepare('
            SELECT *
            FROM vw_sales_dashboard
            WHERE sale_date_only
                BETWEEN :start_date AND :end_date
            ORDER BY sale_date DESC
        ');

        $stmt->bindValue(':start_date', $startDate);
        $stmt->bindValue(':end_date', $endDate);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function validateCartItem(
        int $productId,
        int $warehouseId,
        int $quantity
    ): array {
        if ($quantity <= 0) {
            return [
                'valid' => false,
                'message' => 'Quantity must be greater than zero'
            ];
        }

        $stmt = $this->db->prepare('
            SELECT
                pw.quantity,
                p.is_active,
                p.name
            FROM product_warehouse pw
            INNER JOIN products p
                ON pw.product_id = p.id
            WHERE pw.product_id = ?
            AND pw.warehouse_id = ?
        ');

        $stmt->execute([
            $productId,
            $warehouseId
        ]);

        $stock = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$stock) {
            return [
                'valid' => false,
                'message' => 'Product not available in warehouse'
            ];
        }

        if (!$stock['is_active']) {
            return [
                'valid' => false,
                'message' => 'Product is inactive'
            ];
        }

        if ($stock['quantity'] < $quantity) {
            return [
                'valid' => false,
                'message' => "Only {$stock['quantity']} available"
            ];
        }

        return [
            'valid' => true
        ];
    }
}
