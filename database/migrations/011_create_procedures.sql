DELIMITER //

-- Procedure 1: Process a full POS sale atomically
CREATE PROCEDURE sp_process_sale(
    IN  p_user_id        INT,
    IN  p_cart_json      JSON,
    IN  p_payment_amount DECIMAL(12,2),
    OUT p_sale_id        INT,
    OUT p_total_amount   DECIMAL(12,2),
    OUT p_change_amount  DECIMAL(12,2),
    OUT p_status         VARCHAR(50),
    OUT p_message        VARCHAR(255)
)
BEGIN
    DECLARE v_error_msg VARCHAR(255) DEFAULT 'Transaction failed';

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1 v_error_msg = MESSAGE_TEXT;
        ROLLBACK;
        SET p_status  = 'ERROR';
        SET p_message = v_error_msg;
    END;

    START TRANSACTION;

    SELECT SUM(quantity * price)
    INTO p_total_amount
    FROM JSON_TABLE(p_cart_json, '$[*]'
        COLUMNS (
            quantity INT          PATH '$.quantity',
            price    DECIMAL(12,2) PATH '$.price'
        )
    ) jt;

    IF p_total_amount IS NULL OR p_total_amount = 0 THEN
        ROLLBACK;
        SET p_status  = 'ERROR';
        SET p_message = 'Cart is empty';
    ELSEIF p_payment_amount < p_total_amount THEN
        ROLLBACK;
        SET p_status  = 'ERROR';
        SET p_message = 'Insufficient payment';
    ELSE
        INSERT INTO sales (user_id, payment_amount, created_at)
        VALUES (p_user_id, p_payment_amount, NOW());

        SET p_sale_id      = LAST_INSERT_ID();
        SET p_change_amount = p_payment_amount - p_total_amount;

        -- trg_before_sale_item_insert validates stock; trg_after_sale_item_insert decrements it
        INSERT INTO sale_items (sale_id, product_id, warehouse_id, quantity, price)
        SELECT
            p_sale_id,
            product_id,
            warehouse_id,
            quantity,
            price
        FROM JSON_TABLE(p_cart_json, '$[*]'
            COLUMNS (
                product_id   INT          PATH '$.product_id',
                warehouse_id INT          PATH '$.warehouse_id',
                quantity     INT          PATH '$.quantity',
                price        DECIMAL(12,2) PATH '$.price'
            )
        ) jt;

        COMMIT;

        SET p_status  = 'SUCCESS';
        SET p_message = 'Sale completed';
    END IF;
END//

DELIMITER ;

DELIMITER //

-- Procedure 2: Adjust stock (IN / OUT / ADJUSTMENT) with movement logging
CREATE PROCEDURE sp_adjust_stock(
    IN  p_product_id      INT,
    IN  p_warehouse_id    INT,
    IN  p_adjustment_type ENUM('IN', 'OUT', 'ADJUSTMENT'),
    IN  p_quantity        INT,
    IN  p_user_id         INT,
    IN  p_notes           TEXT,
    OUT p_new_quantity    INT,
    OUT p_status          VARCHAR(50),
    OUT p_message         VARCHAR(255)
)
BEGIN
    DECLARE v_stock INT;
    DECLARE v_error_msg VARCHAR(255) DEFAULT 'Stock adjustment failed';

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1 v_error_msg = MESSAGE_TEXT;
        ROLLBACK;
        SET p_status  = 'ERROR';
        SET p_message = v_error_msg;
    END;

    START TRANSACTION;

    SELECT quantity INTO v_stock
    FROM product_warehouse
    WHERE product_id   = p_product_id
      AND warehouse_id = p_warehouse_id
    FOR UPDATE;

    IF v_stock IS NULL THEN
        ROLLBACK;
        SET p_status  = 'ERROR';
        SET p_message = 'Product-warehouse record not found';
    ELSE
        SET p_new_quantity =
            CASE
                WHEN p_adjustment_type = 'IN'         THEN v_stock + p_quantity
                WHEN p_adjustment_type = 'OUT'        THEN v_stock - p_quantity
                ELSE p_quantity   -- ADJUSTMENT sets absolute value
            END;

        IF p_new_quantity < 0 THEN
            ROLLBACK;
            SET p_status  = 'ERROR';
            SET p_message = 'Insufficient stock';
        ELSE
            -- trg_before_product_warehouse_update will also guard against negatives
            UPDATE product_warehouse
            SET quantity = p_new_quantity
            WHERE product_id   = p_product_id
              AND warehouse_id = p_warehouse_id;

            INSERT INTO stock_movements (
                product_id, warehouse_id, type, quantity, user_id, notes, created_at
            )
            VALUES (
                p_product_id,
                p_warehouse_id,
                p_adjustment_type,
                CASE
                    WHEN p_adjustment_type = 'IN'  THEN  p_quantity
                    WHEN p_adjustment_type = 'OUT' THEN -p_quantity
                    ELSE (p_quantity - v_stock)
                END,
                p_user_id,
                p_notes,
                NOW()
            );

            COMMIT;

            SET p_status  = 'SUCCESS';
            SET p_message = 'Stock updated';
        END IF;
    END IF;
END//

DELIMITER ;

DELIMITER //

-- Procedure 3: Transfer stock between two warehouses atomically
CREATE PROCEDURE sp_transfer_stock(
    IN  p_product_id      INT,
    IN  p_from_warehouse  INT,
    IN  p_to_warehouse    INT,
    IN  p_quantity        INT,
    IN  p_user_id         INT,
    IN  p_notes           TEXT,
    OUT p_status          VARCHAR(50),
    OUT p_message         VARCHAR(255)
)
BEGIN
    DECLARE v_source_stock INT;
    DECLARE v_out_movement_id INT;
    DECLARE v_error_msg VARCHAR(255) DEFAULT 'Transfer failed';

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1 v_error_msg = MESSAGE_TEXT;
        ROLLBACK;
        SET p_status  = 'ERROR';
        SET p_message = v_error_msg;
    END;

    IF p_from_warehouse = p_to_warehouse THEN
        SET p_status  = 'ERROR';
        SET p_message = 'Source and destination warehouse must be different';
    ELSEIF p_quantity <= 0 THEN
        SET p_status  = 'ERROR';
        SET p_message = 'Transfer quantity must be greater than zero';
    ELSE
        START TRANSACTION;

        -- Lock source row and check availability
        SELECT quantity INTO v_source_stock
        FROM product_warehouse
        WHERE product_id   = p_product_id
          AND warehouse_id = p_from_warehouse
        FOR UPDATE;

        IF v_source_stock IS NULL THEN
            ROLLBACK;
            SET p_status  = 'ERROR';
            SET p_message = 'Product not found in source warehouse';
        ELSEIF v_source_stock < p_quantity THEN
            ROLLBACK;
            SET p_status  = 'ERROR';
            SET p_message = 'Insufficient stock in source warehouse';
        ELSE
            -- Deduct from source
            UPDATE product_warehouse
            SET quantity = quantity - p_quantity
            WHERE product_id   = p_product_id
              AND warehouse_id = p_from_warehouse;

            -- Add to destination (insert row if it doesn't exist yet)
            INSERT INTO product_warehouse (product_id, warehouse_id, quantity, min_stock, max_stock)
            VALUES (p_product_id, p_to_warehouse, p_quantity, 0, 0)
            ON DUPLICATE KEY UPDATE quantity = quantity + p_quantity;

            -- Log TRANSFER_OUT movement
            INSERT INTO stock_movements (
                product_id, warehouse_id, type, quantity, user_id, notes, created_at
            )
            VALUES (
                p_product_id, p_from_warehouse, 'TRANSFER_OUT', -p_quantity,
                p_user_id,
                COALESCE(p_notes, CONCAT('Transfer to warehouse #', p_to_warehouse)),
                NOW()
            );

            SET v_out_movement_id = LAST_INSERT_ID();

            -- Log TRANSFER_IN movement, cross-referencing the OUT movement
            INSERT INTO stock_movements (
                product_id, warehouse_id, type, quantity, reference_id, user_id, notes, created_at
            )
            VALUES (
                p_product_id, p_to_warehouse, 'TRANSFER_IN', p_quantity,
                v_out_movement_id,
                p_user_id,
                COALESCE(p_notes, CONCAT('Transfer from warehouse #', p_from_warehouse)),
                NOW()
            );

            -- Back-fill reference_id on the OUT movement to point to the IN movement
            UPDATE stock_movements
            SET reference_id = LAST_INSERT_ID()
            WHERE id = v_out_movement_id;

            COMMIT;

            SET p_status  = 'SUCCESS';
            SET p_message = 'Transfer completed';
        END IF;
    END IF;
END//

DELIMITER ;
