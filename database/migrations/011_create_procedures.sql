DELIMITER //

CREATE PROCEDURE sp_process_sale(
    IN p_user_id INT,
    IN p_cart_json JSON,
    IN p_payment_amount DECIMAL(12,2),
    OUT p_sale_id INT,
    OUT p_total_amount DECIMAL(12,2),
    OUT p_change_amount DECIMAL(12,2),
    OUT p_status VARCHAR(50),
    OUT p_message VARCHAR(255)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET p_status = 'ERROR';
        SET p_message = 'Transaction failed';
    END;

    START TRANSACTION;

    SELECT SUM(quantity * price)
    INTO p_total_amount
    FROM JSON_TABLE(p_cart_json, '$[*]'
        COLUMNS (
            quantity INT PATH '$.quantity',
            price DECIMAL(12,2) PATH '$.price'
        )
    ) jt;

    IF p_total_amount IS NULL OR p_total_amount = 0 THEN
        ROLLBACK;
        SET p_status = 'ERROR';
        SET p_message = 'Cart is empty';
    ELSEIF p_payment_amount < p_total_amount THEN
        ROLLBACK;
        SET p_status = 'ERROR';
        SET p_message = 'Insufficient payment';
    ELSE
        INSERT INTO sales (user_id, payment_amount, created_at)
        VALUES (p_user_id, p_payment_amount, NOW());

        SET p_sale_id = LAST_INSERT_ID();
        SET p_change_amount = p_payment_amount - p_total_amount;

        INSERT INTO sale_items (sale_id, product_id, warehouse_id, quantity, price)
        SELECT 
            p_sale_id,
            product_id,
            warehouse_id,
            quantity,
            price
        FROM JSON_TABLE(p_cart_json, '$[*]'
            COLUMNS (
                product_id INT PATH '$.product_id',
                warehouse_id INT PATH '$.warehouse_id',
                quantity INT PATH '$.quantity',
                price DECIMAL(12,2) PATH '$.price'
            )
        ) jt;

        COMMIT;

        SET p_status = 'SUCCESS';
        SET p_message = 'Sale completed';
    END IF;

END//

DELIMITER ;

DELIMITER //

CREATE PROCEDURE sp_adjust_stock(
    IN p_product_id INT,
    IN p_warehouse_id INT,
    IN p_adjustment_type ENUM('IN', 'OUT', 'ADJUSTMENT'),
    IN p_quantity INT,
    IN p_user_id INT,
    IN p_notes TEXT,
    OUT p_new_quantity INT,
    OUT p_status VARCHAR(50),
    OUT p_message VARCHAR(255)
)
BEGIN
    DECLARE v_stock INT;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET p_status = 'ERROR';
        SET p_message = 'Stock adjustment failed';
    END;

    START TRANSACTION;

    SELECT quantity INTO v_stock
    FROM product_warehouse
    WHERE product_id = p_product_id
    AND warehouse_id = p_warehouse_id;

    IF v_stock IS NULL THEN
        ROLLBACK;
        SET p_status = 'ERROR';
        SET p_message = 'Product not found';
    ELSE

        SET p_new_quantity = 
            CASE 
                WHEN p_adjustment_type = 'IN' THEN v_stock + p_quantity
                WHEN p_adjustment_type = 'OUT' THEN v_stock - p_quantity
                ELSE p_quantity
            END;

        IF p_new_quantity < 0 THEN
            ROLLBACK;
            SET p_status = 'ERROR';
            SET p_message = 'Insufficient stock';
        ELSE

            UPDATE product_warehouse
            SET quantity = p_new_quantity
            WHERE product_id = p_product_id
            AND warehouse_id = p_warehouse_id;

            INSERT INTO stock_movements (
                product_id,
                warehouse_id,
                type,
                quantity,
                user_id,
                notes,
                created_at
            )
            VALUES (
                p_product_id,
                p_warehouse_id,
                p_adjustment_type,
                CASE 
                    WHEN p_adjustment_type = 'IN' THEN p_quantity
                    WHEN p_adjustment_type = 'OUT' THEN -p_quantity
                    ELSE (p_quantity - v_stock)
                END,
                p_user_id,
                p_notes,
                NOW()
            );

            COMMIT;

            SET p_status = 'SUCCESS';
            SET p_message = 'Stock updated';

        END IF;
    END IF;

END//

DELIMITER ;
