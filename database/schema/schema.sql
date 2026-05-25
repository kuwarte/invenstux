SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS roles (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(50)  NOT NULL UNIQUE,
    description TEXT,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id       INT UNSIGNED NOT NULL,
    username      VARCHAR(80)  NOT NULL UNIQUE,
    email         VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name     VARCHAR(150),
    is_active     TINYINT(1) DEFAULT 1,
    last_login_at DATETIME,
    created_at    DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

CREATE TABLE IF NOT EXISTS categories (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id   INT UNSIGNED DEFAULT NULL,
    name        VARCHAR(100) NOT NULL,
    description TEXT,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS warehouses (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    manager_id INT UNSIGNED DEFAULT NULL,
    name       VARCHAR(150) NOT NULL,
    location   VARCHAR(255),
    is_active  TINYINT(1) DEFAULT 1,
    FOREIGN KEY (manager_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS products (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id     INT UNSIGNED DEFAULT NULL,
    sku             VARCHAR(60)  NOT NULL UNIQUE,
    name            VARCHAR(200) NOT NULL,
    description     TEXT,
    unit_of_measure VARCHAR(30)  DEFAULT 'pcs',
    unit_cost       DECIMAL(12,2) DEFAULT 0.00,
    is_active       TINYINT(1) DEFAULT 1,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS product_warehouse (
    product_id   INT UNSIGNED NOT NULL,
    warehouse_id INT UNSIGNED NOT NULL,
    quantity     INT          DEFAULT 0,
    min_stock    INT UNSIGNED DEFAULT 0,
    max_stock    INT UNSIGNED DEFAULT 0,
    PRIMARY KEY (product_id, warehouse_id),
    FOREIGN KEY (product_id)   REFERENCES products(id)   ON DELETE CASCADE,
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS permissions (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(50) NOT NULL UNIQUE,
    description TEXT
);

CREATE TABLE IF NOT EXISTS role_permissions (
    role_id       INT UNSIGNED NOT NULL,
    permission_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id)       REFERENCES roles(id)       ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS sales (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id        INT UNSIGNED  NOT NULL,
    payment_amount DECIMAL(12,2) NOT NULL,
    created_at     DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS sale_items (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sale_id      INT UNSIGNED  NOT NULL,
    product_id   INT UNSIGNED  NOT NULL,
    warehouse_id INT UNSIGNED  NOT NULL,
    quantity     INT           NOT NULL,
    price        DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (sale_id)      REFERENCES sales(id)      ON DELETE CASCADE,
    FOREIGN KEY (product_id)   REFERENCES products(id),
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id)
);

CREATE TABLE IF NOT EXISTS stock_movements (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id   INT UNSIGNED NOT NULL,
    warehouse_id INT UNSIGNED NOT NULL,
    type         ENUM('IN','OUT','SALE','ADJUSTMENT','TRANSFER_IN','TRANSFER_OUT') NOT NULL,
    quantity     INT          NOT NULL,
    reference_id INT UNSIGNED NULL,
    user_id      INT UNSIGNED NOT NULL,
    notes        TEXT,
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id)   REFERENCES products(id),
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id),
    FOREIGN KEY (user_id)      REFERENCES users(id)
);

SET FOREIGN_KEY_CHECKS = 1;


-- Triggers

DELIMITER //

-- Guard 1: Before inserting a sale item — validate product is active and stock is sufficient
CREATE TRIGGER trg_before_sale_item_insert
BEFORE INSERT ON sale_items
FOR EACH ROW
BEGIN
    DECLARE v_is_active     TINYINT;
    DECLARE v_current_stock INT;

    SELECT is_active INTO v_is_active
    FROM products
    WHERE id = NEW.product_id;

    IF v_is_active = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cannot sell inactive product';
    END IF;

    SELECT quantity INTO v_current_stock
    FROM product_warehouse
    WHERE product_id   = NEW.product_id
      AND warehouse_id = NEW.warehouse_id
    FOR UPDATE;

    IF v_current_stock IS NULL OR v_current_stock < NEW.quantity THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Insufficient stock';
    END IF;
END//

DELIMITER ;

DELIMITER //

-- Guard 2: After inserting a sale item — auto-log SALE movement and decrement stock
CREATE TRIGGER trg_after_sale_item_insert
AFTER INSERT ON sale_items
FOR EACH ROW
BEGIN
    DECLARE v_user_id INT;

    SELECT user_id INTO v_user_id
    FROM sales
    WHERE id = NEW.sale_id;

    INSERT INTO stock_movements (
        product_id, warehouse_id, type, quantity,
        reference_id, user_id, notes, created_at
    )
    VALUES (
        NEW.product_id,
        NEW.warehouse_id,
        'SALE',
        -NEW.quantity,
        NEW.sale_id,
        v_user_id,
        CONCAT('Sale #', NEW.sale_id),
        NOW()
    );

    UPDATE product_warehouse
    SET quantity = quantity - NEW.quantity
    WHERE product_id   = NEW.product_id
      AND warehouse_id = NEW.warehouse_id;
END//

DELIMITER ;

DELIMITER //

-- Guard 3: Before updating product_warehouse — prevent quantity from going negative
CREATE TRIGGER trg_before_product_warehouse_update
BEFORE UPDATE ON product_warehouse
FOR EACH ROW
BEGIN
    IF NEW.quantity < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Stock quantity cannot be negative';
    END IF;
END//

DELIMITER ;

DELIMITER //

-- Guard 4: Before hard-deleting a product — block if it has sales history
CREATE TRIGGER trg_before_product_delete
BEFORE DELETE ON products
FOR EACH ROW
BEGIN
    DECLARE v_sale_count INT;

    SELECT COUNT(*) INTO v_sale_count
    FROM sale_items
    WHERE product_id = OLD.id;

    IF v_sale_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cannot delete a product with sales history. Deactivate it instead.';
    END IF;
END//

DELIMITER ;


-- Stored Procedures

DELIMITER //

-- Procedure 1: Process a full POS sale atomically
CREATE PROCEDURE sp_process_sale(
    IN  p_user_id        INT,
    IN  p_cart_json      JSON,
    IN  p_payment_amount DECIMAL(12,2),
    OUT p_sale_id        INT,
    OUT p_total_amount   DECIMAL(12,2),
    OUT p_change_amount  DECIMAL(12,2),
    OUT p_status         VARCHAR(50),
    OUT p_message        VARCHAR(255)
)
BEGIN
    DECLARE v_error_msg VARCHAR(255) DEFAULT 'Transaction failed';

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1 v_error_msg = MESSAGE_TEXT;
        ROLLBACK;
        SET p_status  = 'ERROR';
        SET p_message = v_error_msg;
    END;

    START TRANSACTION;

    SELECT SUM(quantity * price)
    INTO p_total_amount
    FROM JSON_TABLE(p_cart_json, '$[*]'
        COLUMNS (
            quantity INT           PATH '$.quantity',
            price    DECIMAL(12,2) PATH '$.price'
        )
    ) jt;

    IF p_total_amount IS NULL OR p_total_amount = 0 THEN
        ROLLBACK;
        SET p_status  = 'ERROR';
        SET p_message = 'Cart is empty';
    ELSEIF p_payment_amount < p_total_amount THEN
        ROLLBACK;
        SET p_status  = 'ERROR';
        SET p_message = 'Insufficient payment';
    ELSE
        INSERT INTO sales (user_id, payment_amount, created_at)
        VALUES (p_user_id, p_payment_amount, NOW());

        SET p_sale_id       = LAST_INSERT_ID();
        SET p_change_amount = p_payment_amount - p_total_amount;

        -- trg_before_sale_item_insert validates stock
        -- trg_after_sale_item_insert decrements stock and logs movement
        INSERT INTO sale_items (sale_id, product_id, warehouse_id, quantity, price)
        SELECT
            p_sale_id,
            product_id,
            warehouse_id,
            quantity,
            price
        FROM JSON_TABLE(p_cart_json, '$[*]'
            COLUMNS (
                product_id   INT           PATH '$.product_id',
                warehouse_id INT           PATH '$.warehouse_id',
                quantity     INT           PATH '$.quantity',
                price        DECIMAL(12,2) PATH '$.price'
            )
        ) jt;

        COMMIT;

        SET p_status  = 'SUCCESS';
        SET p_message = 'Sale completed';
    END IF;
END//

DELIMITER ;

DELIMITER //

-- Procedure 2: Adjust stock (IN / OUT / ADJUSTMENT) with movement logging
CREATE PROCEDURE sp_adjust_stock(
    IN  p_product_id      INT,
    IN  p_warehouse_id    INT,
    IN  p_adjustment_type ENUM('IN','OUT','ADJUSTMENT'),
    IN  p_quantity        INT,
    IN  p_user_id         INT,
    IN  p_notes           TEXT,
    OUT p_new_quantity    INT,
    OUT p_status          VARCHAR(50),
    OUT p_message         VARCHAR(255)
)
BEGIN
    DECLARE v_stock     INT;
    DECLARE v_error_msg VARCHAR(255) DEFAULT 'Stock adjustment failed';

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1 v_error_msg = MESSAGE_TEXT;
        ROLLBACK;
        SET p_status  = 'ERROR';
        SET p_message = v_error_msg;
    END;

    START TRANSACTION;

    SELECT quantity INTO v_stock
    FROM product_warehouse
    WHERE product_id   = p_product_id
      AND warehouse_id = p_warehouse_id
    FOR UPDATE;

    IF v_stock IS NULL THEN
        ROLLBACK;
        SET p_status  = 'ERROR';
        SET p_message = 'Product-warehouse record not found';
    ELSE
        SET p_new_quantity =
            CASE
                WHEN p_adjustment_type = 'IN'         THEN v_stock + p_quantity
                WHEN p_adjustment_type = 'OUT'        THEN v_stock - p_quantity
                ELSE p_quantity   -- ADJUSTMENT sets absolute value
            END;

        IF p_new_quantity < 0 THEN
            ROLLBACK;
            SET p_status  = 'ERROR';
            SET p_message = 'Insufficient stock';
        ELSE
            UPDATE product_warehouse
            SET quantity = p_new_quantity
            WHERE product_id   = p_product_id
              AND warehouse_id = p_warehouse_id;

            INSERT INTO stock_movements (
                product_id, warehouse_id, type, quantity, user_id, notes, created_at
            )
            VALUES (
                p_product_id,
                p_warehouse_id,
                p_adjustment_type,
                CASE
                    WHEN p_adjustment_type = 'IN'  THEN  p_quantity
                    WHEN p_adjustment_type = 'OUT' THEN -p_quantity
                    ELSE (p_quantity - v_stock)
                END,
                p_user_id,
                p_notes,
                NOW()
            );

            COMMIT;

            SET p_status  = 'SUCCESS';
            SET p_message = 'Stock updated';
        END IF;
    END IF;
END//

DELIMITER ;

DELIMITER //

-- Procedure 3: Transfer stock between two warehouses atomically
CREATE PROCEDURE sp_transfer_stock(
    IN  p_product_id     INT,
    IN  p_from_warehouse INT,
    IN  p_to_warehouse   INT,
    IN  p_quantity       INT,
    IN  p_user_id        INT,
    IN  p_notes          TEXT,
    OUT p_status         VARCHAR(50),
    OUT p_message        VARCHAR(255)
)
BEGIN
    DECLARE v_source_stock    INT;
    DECLARE v_out_movement_id INT;
    DECLARE v_error_msg       VARCHAR(255) DEFAULT 'Transfer failed';

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1 v_error_msg = MESSAGE_TEXT;
        ROLLBACK;
        SET p_status  = 'ERROR';
        SET p_message = v_error_msg;
    END;

    IF p_from_warehouse = p_to_warehouse THEN
        SET p_status  = 'ERROR';
        SET p_message = 'Source and destination warehouse must be different';
    ELSEIF p_quantity <= 0 THEN
        SET p_status  = 'ERROR';
        SET p_message = 'Transfer quantity must be greater than zero';
    ELSE
        START TRANSACTION;

        SELECT quantity INTO v_source_stock
        FROM product_warehouse
        WHERE product_id   = p_product_id
          AND warehouse_id = p_from_warehouse
        FOR UPDATE;

        IF v_source_stock IS NULL THEN
            ROLLBACK;
            SET p_status  = 'ERROR';
            SET p_message = 'Product not found in source warehouse';
        ELSEIF v_source_stock < p_quantity THEN
            ROLLBACK;
            SET p_status  = 'ERROR';
            SET p_message = 'Insufficient stock in source warehouse';
        ELSE
            -- Deduct from source
            UPDATE product_warehouse
            SET quantity = quantity - p_quantity
            WHERE product_id   = p_product_id
              AND warehouse_id = p_from_warehouse;

            -- Add to destination (upsert)
            INSERT INTO product_warehouse (product_id, warehouse_id, quantity, min_stock, max_stock)
            VALUES (p_product_id, p_to_warehouse, p_quantity, 0, 0)
            ON DUPLICATE KEY UPDATE quantity = quantity + p_quantity;

            -- Log TRANSFER_OUT
            INSERT INTO stock_movements (
                product_id, warehouse_id, type, quantity, user_id, notes, created_at
            )
            VALUES (
                p_product_id, p_from_warehouse, 'TRANSFER_OUT', -p_quantity,
                p_user_id,
                COALESCE(p_notes, CONCAT('Transfer to warehouse #', p_to_warehouse)),
                NOW()
            );

            SET v_out_movement_id = LAST_INSERT_ID();

            -- Log TRANSFER_IN (cross-reference the OUT movement)
            INSERT INTO stock_movements (
                product_id, warehouse_id, type, quantity, reference_id, user_id, notes, created_at
            )
            VALUES (
                p_product_id, p_to_warehouse, 'TRANSFER_IN', p_quantity,
                v_out_movement_id,
                p_user_id,
                COALESCE(p_notes, CONCAT('Transfer from warehouse #', p_from_warehouse)),
                NOW()
            );

            -- Back-fill reference_id on the OUT movement
            UPDATE stock_movements
            SET reference_id = LAST_INSERT_ID()
            WHERE id = v_out_movement_id;

            COMMIT;

            SET p_status  = 'SUCCESS';
            SET p_message = 'Transfer completed';
        END IF;
    END IF;
END//

DELIMITER ;

-- Views

-- Global dashboard counters
CREATE OR REPLACE VIEW vw_dashboard_global_counters AS
SELECT
    (SELECT COUNT(*) FROM products   WHERE is_active = 1)                                  AS total_products,
    (SELECT COUNT(*) FROM warehouses WHERE is_active = 1)                                  AS total_warehouses,
    (SELECT COUNT(*) FROM categories)                                                       AS total_categories,
    (SELECT COUNT(*) FROM product_warehouse WHERE quantity <= min_stock AND min_stock > 0) AS critical_low_stock;

-- Per-sale-item stream used by dashboard charts and top-product queries
CREATE OR REPLACE VIEW vw_dashboard_sales_stream AS
SELECT
    s.id                                             AS sale_id,
    s.created_at                                     AS sale_date,
    si.product_id,
    COALESCE(p.name, 'Unknown Product')              AS product_name,
    COALESCE(p.sku,  'N/A')                          AS product_sku,
    COALESCE(w.name, 'Unassigned Location')          AS warehouse_name,
    si.quantity                                      AS units_sold,
    si.price                                         AS unit_price,
    (si.quantity * si.price)                         AS total_item_revenue
FROM sales s
JOIN      sale_items si ON s.id          = si.sale_id
LEFT JOIN products   p  ON si.product_id = p.id
LEFT JOIN warehouses w  ON si.warehouse_id = w.id;

-- Full sales list with cashier info and totals — used by SalesRepository
CREATE OR REPLACE VIEW vw_sales_dashboard AS
SELECT
    s.id                                                AS sale_id,
    s.created_at                                        AS sale_date,
    DATE(s.created_at)                                  AS sale_date_only,
    u.full_name                                         AS cashier_name,
    u.email                                             AS cashier_email,
    r.name                                              AS cashier_role,
    s.payment_amount,
    SUM(si.quantity * si.price)                         AS total_amount,
    (s.payment_amount - SUM(si.quantity * si.price))    AS change_amount,
    COUNT(si.id)                                        AS items_count,
    SUM(si.quantity)                                    AS total_items_quantity
FROM sales s
INNER JOIN users      u  ON s.user_id    = u.id
INNER JOIN roles      r  ON u.role_id    = r.id
LEFT  JOIN sale_items si ON s.id         = si.sale_id
GROUP BY s.id, s.created_at, u.full_name, u.email, r.name, s.payment_amount
ORDER BY s.created_at DESC;

-- Product catalog with sales analytics — used by ProductRepository
CREATE OR REPLACE VIEW vw_product_performance AS
SELECT
    p.id                                         AS product_id,
    p.name                                       AS product_name,
    p.sku,
    p.description,
    p.unit_of_measure,
    p.category_id,
    c.name                                       AS category_name,
    p.unit_cost,
    p.is_active,
    COALESCE(SUM(si.quantity), 0)                AS total_sold,
    COALESCE(SUM(si.quantity * si.price), 0)     AS total_revenue,
    COALESCE(AVG(si.price), 0)                   AS avg_selling_price,
    COUNT(DISTINCT si.sale_id)                   AS transaction_count
FROM products p
LEFT JOIN categories c  ON p.category_id = c.id
LEFT JOIN sale_items si ON p.id          = si.product_id
GROUP BY p.id, p.name, p.sku, p.description, p.unit_of_measure,
         p.category_id, c.name, p.unit_cost, p.is_active
ORDER BY product_name;

-- Low-stock alert list — used by StockRepository and dashboard
CREATE OR REPLACE VIEW vw_low_stock_alert AS
SELECT
    p.id                             AS product_id,
    p.name                           AS product_name,
    p.sku,
    c.name                           AS category_name,
    w.id                             AS warehouse_id,
    w.name                           AS warehouse_name,
    pw.quantity                      AS current_stock,
    pw.min_stock                     AS minimum_threshold,
    pw.max_stock                     AS maximum_threshold,
    (pw.min_stock - pw.quantity)     AS units_below_threshold,
    CASE
        WHEN pw.quantity = 0                     THEN 'OUT_OF_STOCK'
        WHEN pw.quantity <= (pw.min_stock * 0.5) THEN 'CRITICAL'
        WHEN pw.quantity <= pw.min_stock         THEN 'LOW'
        ELSE 'NORMAL'
    END                              AS stock_status
FROM product_warehouse pw
INNER JOIN products   p ON pw.product_id   = p.id
INNER JOIN categories c ON p.category_id   = c.id
INNER JOIN warehouses w ON pw.warehouse_id = w.id
WHERE pw.quantity <= pw.min_stock AND pw.min_stock > 0
ORDER BY stock_status DESC, units_below_threshold DESC;

-- Detailed stock movement audit trail — used by StockRepository
CREATE OR REPLACE VIEW vw_stock_movements_detailed AS
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
INNER JOIN products   p ON sm.product_id   = p.id
INNER JOIN warehouses w ON sm.warehouse_id = w.id
INNER JOIN users      u ON sm.user_id      = u.id
LEFT  JOIN sales      s ON sm.reference_id = s.id AND sm.type = 'SALE'
ORDER BY sm.created_at DESC;

-- Per-warehouse inventory summary — used by StockRepository
CREATE OR REPLACE VIEW vw_warehouse_stock_summary AS
SELECT
    w.id                                                                              AS warehouse_id,
    w.name                                                                            AS warehouse_name,
    w.location                                                                        AS warehouse_location,
    COUNT(DISTINCT pw.product_id)                                                     AS total_products,
    SUM(pw.quantity)                                                                  AS total_units,
    SUM(pw.quantity * p.unit_cost)                                                    AS total_inventory_value,
    SUM(CASE WHEN pw.quantity <= pw.min_stock AND pw.min_stock > 0 THEN 1 ELSE 0 END) AS low_stock_items,
    SUM(CASE WHEN pw.quantity = 0 THEN 1 ELSE 0 END)                                 AS out_of_stock_items
FROM warehouses w
LEFT JOIN product_warehouse pw ON w.id          = pw.warehouse_id
LEFT JOIN products          p  ON pw.product_id = p.id
WHERE w.is_active = 1
GROUP BY w.id, w.name, w.location
ORDER BY total_inventory_value DESC;

-- Daily sales summary — used by DashboardRepository
CREATE OR REPLACE VIEW vw_daily_sales_summary AS
SELECT
    DATE(s.created_at)           AS sale_date,
    COUNT(DISTINCT s.id)         AS total_transactions,
    SUM(si.quantity * si.price)  AS total_revenue,
    AVG(si.quantity * si.price)  AS avg_transaction_value,
    SUM(si.quantity)             AS total_items_sold,
    COUNT(DISTINCT s.user_id)    AS active_cashiers
FROM sales s
LEFT JOIN sale_items si ON s.id = si.sale_id
GROUP BY DATE(s.created_at)
ORDER BY sale_date DESC;

-- Indexes

-- products: full-text search on name
CREATE FULLTEXT INDEX idx_products_name_fulltext       ON products(name);

-- products: category filter + active flag + name sort
CREATE INDEX idx_products_category                     ON products(category_id);
CREATE INDEX idx_products_category_active              ON products(category_id, is_active, name);

-- categories: parent lookup
CREATE INDEX idx_categories_parent                     ON categories(parent_id);

-- sales: cashier + date range queries
CREATE INDEX idx_sales_user_date                       ON sales(user_id, created_at);

-- sale_items: join on sale + product
CREATE INDEX idx_sale_items_sale_products              ON sale_items(sale_id, product_id);

-- stock_movements: product history, warehouse history, type filter, transfer cross-ref
CREATE INDEX idx_stock_movements_product_date          ON stock_movements(product_id, created_at);
CREATE INDEX idx_stock_movements_warehouse_date        ON stock_movements(warehouse_id, created_at);
CREATE INDEX idx_stock_movements_type_date             ON stock_movements(type, created_at);
CREATE INDEX idx_stock_movements_reference             ON stock_movements(reference_id, type);

-- product_warehouse: low-stock alert queries and quantity lookups
CREATE INDEX idx_product_warehouse_low_stock           ON product_warehouse(warehouse_id, quantity, min_stock);
CREATE INDEX idx_product_warehouse_quantity            ON product_warehouse(warehouse_id, quantity);
