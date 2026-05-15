INSERT INTO categories (id, name, description) VALUES
(1, 'Electronics', 'Electronic gadgets and devices'),
(2, 'Furniture', 'Home and office furniture'),
(3, 'Groceries', 'Daily essential food items')
ON DUPLICATE KEY UPDATE name=VALUES(name), description=VALUES(description);

INSERT INTO warehouses (id, name, location, is_active) VALUES
(1, 'Main Warehouse', '123 Logistics Way, Downtown', 1),
(2, 'North Branch', '456 Northern Blvd, North Side', 1),
(3, 'South Branch', '789 Southern Ave, South Side', 1)
ON DUPLICATE KEY UPDATE name=VALUES(name), location=VALUES(location);

INSERT INTO products (id, category_id, sku, name, description, unit_cost) VALUES
(1, 1, 'ELEC-001', 'MacBook Pro 14', 'Apple M2 Pro Chip, 16GB RAM, 512GB SSD', 1999.00),
(2, 1, 'ELEC-002', 'iPhone 15 Pro', '128GB, Natural Titanium', 999.00),
(3, 1, 'ELEC-003', 'Sony WH-1000XM5', 'Wireless Noise Cancelling Headphones', 348.00),
(4, 2, 'FURN-001', 'Ergonomic Office Chair', 'Breathable mesh back with lumbar support', 249.99),
(5, 2, 'FURN-002', 'Standing Desk', 'Electric height adjustable desk, 140x70cm', 450.00),
(6, 3, 'GROC-001', 'Organic Whole Milk', '1 Gallon, Fresh from farm', 5.50),
(7, 3, 'GROC-002', 'Whole Wheat Bread', 'Freshly baked daily', 3.25)
ON DUPLICATE KEY UPDATE name=VALUES(name), description=VALUES(description), unit_cost=VALUES(unit_cost);

INSERT INTO product_warehouse (product_id, warehouse_id, quantity, min_stock, max_stock) VALUES
-- MacBook Pro 14
(1, 1, 15, 5, 50),
(1, 2, 5, 2, 20),
-- iPhone 15 Pro
(2, 1, 25, 10, 100),
(2, 2, 12, 5, 40),
(2, 3, 8, 5, 40),
-- Sony Headphones
(3, 1, 30, 15, 120),
(3, 3, 20, 10, 80),
-- Office Chair
(4, 1, 10, 5, 30),
(4, 3, 15, 5, 30),
-- Standing Desk
(5, 1, 8, 3, 15),
(5, 2, 4, 2, 10),
-- Milk
(6, 1, 200, 50, 500),
(6, 2, 100, 30, 200),
(6, 3, 100, 30, 200),
-- Bread
(7, 1, 150, 40, 300),
(7, 2, 80, 20, 150),
(7, 3, 80, 20, 150)
ON DUPLICATE KEY UPDATE quantity=VALUES(quantity), min_stock=VALUES(min_stock), max_stock=VALUES(max_stock);
