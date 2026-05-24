DELIMITER //

-- Guard 1: Before inserting a sale item — validate product is active and stock is sufficient
CREATE TRIGGER trg_before_sale_item_insert
BEFORE INSERT ON sale_items
FOR EACH ROW
BEGIN
    DECLARE v_is_active TINYINT;
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
    WHERE product_id = NEW.product_id
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
        product_id,
        warehouse_id,
        type,
        quantity,
        reference_id,
        user_id,
        notes,
        created_at
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
    WHERE product_id = NEW.product_id
      AND warehouse_id = NEW.warehouse_id;
END//

DELIMITER ;

DELIMITER //

-- Guard 3: Before updating product_warehouse — prevent quantity from going negative
-- This catches any raw UPDATE that bypasses the stored procedures
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
-- (soft-delete via is_active = 0 is the correct path; this prevents accidental hard deletes)
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
