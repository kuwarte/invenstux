DELIMITER //

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
