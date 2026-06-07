INSERT INTO permissions (name, description) VALUES
('manage_products', 'Create, edit, delete products'),
('manage_stock', 'Stock in/out operations'),
('access_pos', 'Access Point of Sale system'),
('view_reports', 'View sales and inventory reports'),
('manage_users', 'Manage system users'),
('manage_warehouses', 'Manage warehouse locations'),
('manage_categories', 'Manage product categories')
ON DUPLICATE KEY UPDATE description = VALUES(description);

DELETE FROM role_permissions;

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
JOIN permissions p
WHERE r.name = 'admin';

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
JOIN permissions p
WHERE r.name = 'manager'
AND p.name IN (
    'view_reports'
);

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
JOIN permissions p
WHERE r.name = 'staff'
AND p.name IN (
    'manage_products',
    'manage_stock',
    'manage_categories'
);

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
JOIN permissions p
WHERE r.name = 'cashier'
AND p.name IN (
    'access_pos',
    'view_reports'
);
