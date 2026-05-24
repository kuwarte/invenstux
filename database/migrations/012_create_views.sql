-- Global dashboard counters
CREATE VIEW vw_dashboard_global_counters AS
SELECT
    (SELECT COUNT(*) FROM products   WHERE is_active = 1)                                    AS total_products,
    (SELECT COUNT(*) FROM warehouses WHERE is_active = 1)                                    AS total_warehouses,
    (SELECT COUNT(*) FROM categories)                                                         AS total_categories,
    (SELECT COUNT(*) FROM product_warehouse WHERE quantity <= min_stock AND min_stock > 0)   AS critical_low_stock;

-- Per-sale-item stream used by dashboard charts and top-product queries
CREATE VIEW vw_dashboard_sales_stream AS
SELECT
    s.id                              AS sale_id,
    s.created_at                      AS sale_date,
    si.product_id,
    COALESCE(p.name,  'Unknown Product')     AS product_name,
    COALESCE(p.sku,   'N/A')                 AS product_sku,
    COALESCE(w.name,  'Unassigned Location') AS warehouse_name,
    si.quantity                       AS units_sold,
    si.price                          AS unit_price,
    (si.quantity * si.price)          AS total_item_revenue
FROM sales s
JOIN      sale_items si ON s.id  = si.sale_id
LEFT JOIN products   p  ON si.product_id   = p.id
LEFT JOIN warehouses w  ON si.warehouse_id = w.id;

-- Full sales list with cashier info and totals — used by SalesRepository
CREATE VIEW vw_sales_dashboard AS
SELECT
    s.id                                          AS sale_id,
    s.created_at                                  AS sale_date,
    DATE(s.created_at)                            AS sale_date_only,
    u.full_name                                   AS cashier_name,
    u.email                                       AS cashier_email,
    r.name                                        AS cashier_role,
    s.payment_amount,
    SUM(si.quantity * si.price)                   AS total_amount,
    (s.payment_amount - SUM(si.quantity * si.price)) AS change_amount,
    COUNT(si.id)                                  AS items_count,
    SUM(si.quantity)                              AS total_items_quantity
FROM sales s
INNER JOIN users     u  ON s.user_id   = u.id
INNER JOIN roles     r  ON u.role_id   = r.id
LEFT  JOIN sale_items si ON s.id       = si.sale_id
GROUP BY s.id, s.created_at, u.full_name, u.email, r.name, s.payment_amount
ORDER BY s.created_at DESC;

-- Product catalog with sales analytics — used by ProductRepository
CREATE VIEW vw_product_performance AS
SELECT
    p.id                                          AS product_id,
    p.name                                        AS product_name,
    p.sku,
    p.description,
    p.unit_of_measure,
    p.category_id,
    c.name                                        AS category_name,
    p.unit_cost,
    p.is_active,
    COALESCE(SUM(si.quantity), 0)                 AS total_sold,
    COALESCE(SUM(si.quantity * si.price), 0)      AS total_revenue,
    COALESCE(AVG(si.price), 0)                    AS avg_selling_price,
    COUNT(DISTINCT si.sale_id)                    AS transaction_count
FROM products p
LEFT JOIN categories c  ON p.category_id = c.id
LEFT JOIN sale_items si ON p.id          = si.product_id
GROUP BY p.id, p.name, p.sku, p.description, p.unit_of_measure,
         p.category_id, c.name, p.unit_cost, p.is_active
ORDER BY product_name;

-- Low-stock alert list — used by StockRepository and dashboard
CREATE VIEW vw_low_stock_alert AS
SELECT
    p.id                                AS product_id,
    p.name                              AS product_name,
    p.sku,
    c.name                              AS category_name,
    w.id                                AS warehouse_id,
    w.name                              AS warehouse_name,
    pw.quantity                         AS current_stock,
    pw.min_stock                        AS minimum_threshold,
    pw.max_stock                        AS maximum_threshold,
    (pw.min_stock - pw.quantity)        AS units_below_threshold,
    CASE
        WHEN pw.quantity = 0                        THEN 'OUT_OF_STOCK'
        WHEN pw.quantity <= (pw.min_stock * 0.5)    THEN 'CRITICAL'
        WHEN pw.quantity <= pw.min_stock            THEN 'LOW'
        ELSE 'NORMAL'
    END                                 AS stock_status
FROM product_warehouse pw
INNER JOIN products    p ON pw.product_id   = p.id
INNER JOIN categories  c ON p.category_id   = c.id
INNER JOIN warehouses  w ON pw.warehouse_id = w.id
WHERE pw.quantity <= pw.min_stock AND pw.min_stock > 0
ORDER BY stock_status DESC, units_below_threshold DESC;

-- Detailed stock movement audit trail — used by StockRepository
CREATE VIEW vw_stock_movements_detailed AS
SELECT
    sm.id                   AS movement_id,
    sm.created_at           AS movement_date,
    sm.type                 AS movement_type,
    sm.quantity             AS quantity_changed,
    sm.reference_id,
    sm.notes,
    p.id                    AS product_id,
    p.name                  AS product_name,
    p.sku                   AS product_sku,
    w.id                    AS warehouse_id,
    w.name                  AS warehouse_name,
    u.id                    AS user_id,
    u.full_name             AS user_name,
    u.email                 AS user_email,
    CASE
        WHEN sm.type = 'SALE'
        THEN (SELECT SUM(si.quantity * si.price) FROM sale_items si WHERE si.sale_id = s.id)
        ELSE NULL
    END                     AS related_sale_amount
FROM stock_movements sm
INNER JOIN products    p ON sm.product_id   = p.id
INNER JOIN warehouses  w ON sm.warehouse_id = w.id
INNER JOIN users       u ON sm.user_id      = u.id
LEFT  JOIN sales       s ON sm.reference_id = s.id AND sm.type = 'SALE'
ORDER BY sm.created_at DESC;

-- Per-warehouse inventory summary — used by StockRepository
CREATE VIEW vw_warehouse_stock_summary AS
SELECT
    w.id                                                                        AS warehouse_id,
    w.name                                                                      AS warehouse_name,
    w.location                                                                  AS warehouse_location,
    COUNT(DISTINCT pw.product_id)                                               AS total_products,
    SUM(pw.quantity)                                                            AS total_units,
    SUM(pw.quantity * p.unit_cost)                                              AS total_inventory_value,
    SUM(CASE WHEN pw.quantity <= pw.min_stock AND pw.min_stock > 0 THEN 1 ELSE 0 END) AS low_stock_items,
    SUM(CASE WHEN pw.quantity = 0 THEN 1 ELSE 0 END)                           AS out_of_stock_items
FROM warehouses w
LEFT JOIN product_warehouse pw ON w.id          = pw.warehouse_id
LEFT JOIN products          p  ON pw.product_id = p.id
WHERE w.is_active = 1
GROUP BY w.id, w.name, w.location
ORDER BY total_inventory_value DESC;

-- Daily sales summary — used by DashboardRepository
CREATE VIEW vw_daily_sales_summary AS
SELECT
    DATE(s.created_at)              AS sale_date,
    COUNT(DISTINCT s.id)            AS total_transactions,
    SUM(si.quantity * si.price)     AS total_revenue,
    AVG(si.quantity * si.price)     AS avg_transaction_value,
    SUM(si.quantity)                AS total_items_sold,
    COUNT(DISTINCT s.user_id)       AS active_cashiers
FROM sales s
LEFT JOIN sale_items si ON s.id = si.sale_id
GROUP BY DATE(s.created_at)
ORDER BY sale_date DESC;
