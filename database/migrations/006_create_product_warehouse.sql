CREATE TABLE IF NOT EXISTS product_warehouse (
    product_id   INT UNSIGNED NOT NULL,
    warehouse_id INT UNSIGNED NOT NULL,
    quantity     INT DEFAULT 0,
    min_stock    INT UNSIGNED DEFAULT 0,
    max_stock    INT UNSIGNED DEFAULT 0,
    PRIMARY KEY (product_id, warehouse_id),
    FOREIGN KEY (product_id)   REFERENCES products(id)   ON DELETE CASCADE,
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE CASCADE
);
