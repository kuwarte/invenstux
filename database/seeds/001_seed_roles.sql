INSERT INTO roles (name, description) VALUES
('admin',   'Full system access'),
('manager', 'Can approve and manage inventory'),
('staff',   'Basic stock operations only'),
('cashier', 'POS and sales operations')
ON DUPLICATE KEY UPDATE
description = VALUES(description);
