CREATE TABLE stock_movements (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id   INT UNSIGNED NOT NULL,
    warehouse_id INT UNSIGNED NOT NULL,
    type         ENUM('IN', 'OUT', 'SALE', 'ADJUSTMENT', 'TRANSFER_IN', 'TRANSFER_OUT') NOT NULL,
    quantity     INT NOT NULL,
    reference_id INT UNSIGNED NULL COMMENT 'sale_id for SALE movements; source/dest movement id for TRANSFER pairs',
    user_id      INT UNSIGNED NOT NULL,
    notes        TEXT,
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id)   REFERENCES products(id),
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id),
    FOREIGN KEY (user_id)      REFERENCES users(id)
);
