CREATE TABLE warehouses (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    manager_id INT UNSIGNED DEFAULT NULL,
    name       VARCHAR(150) NOT NULL,
    location   VARCHAR(255),
    is_active  TINYINT(1) DEFAULT 1,
    FOREIGN KEY (manager_id) REFERENCES users(id) ON DELETE SET NULL
);
