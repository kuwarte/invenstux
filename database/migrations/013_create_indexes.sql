-- products idx (crit)
CREATE FULLTEXT INDEX idx_products_name_fulltext ON products(name);


-- sales idx
CREATE INDEX idx_sales_user_date ON sales(user_id, created_at);


-- sale_items idx
CREATE INDEX idx_sale_items_sale_products ON sale_items(sale_id, product_id);


-- stock_movements idx
CREATE INDEX idx_stock_movements_product_date ON stock_movements(product_id, created_at);
CREATE INDEX idx_stock_movements_warehouse_date ON stock_movements(warehouse_id, created_at);
CREATE INDEX idx_stock_movements_type_date ON stock_movements(type, created_at);
CREATE INDEX idx_stock_movements_reference ON stock_movements(reference_id, type);


-- categories idx
CREATE INDEX idx_categories_parent ON categories(parent_id);


-- products idx
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_category_active ON products(category_id, is_active, name);


-- product_warehouse idx
CREATE INDEX idx_product_warehouse_low_stock ON product_warehouse(warehouse_id, quantity, min_stock);
CREATE INDEX idx_product_warehouse_quantity ON product_warehouse(warehouse_id, quantity);
