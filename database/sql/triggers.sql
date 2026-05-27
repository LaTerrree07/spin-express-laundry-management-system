DROP TRIGGER IF EXISTS trg_before_insert_transactions;

DELIMITER $$

CREATE TRIGGER trg_before_insert_transactions
BEFORE INSERT ON transactions
FOR EACH ROW
BEGIN
    DECLARE v_service_name VARCHAR(100);
    DECLARE v_service_price DECIMAL(10,2);
    DECLARE v_required_loads INT;

    SELECT service_name, price
    INTO v_service_name, v_service_price
    FROM service_types
    WHERE service_type_id = NEW.service_type_id
    LIMIT 1;

    IF v_service_name IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid service type reference.';
    END IF;

    IF LOWER(v_service_name) LIKE '%wash%' THEN
        IF NEW.weight_kg IS NULL OR NEW.weight_kg <= 0 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Weight is required and must be greater than 0 for wash-based services.';
        END IF;

        SET v_required_loads = CEIL(NEW.weight_kg / 6);
        SET NEW.number_of_loads = v_required_loads;
    ELSE
        IF NEW.number_of_loads IS NULL OR NEW.number_of_loads < 1 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Number of loads must be at least 1 for non-wash services.';
        END IF;
    END IF;

    SET NEW.total_amount = v_service_price * NEW.number_of_loads;
END$$

DELIMITER ;


DROP TRIGGER IF EXISTS trg_before_insert_transaction_extra_items;

DELIMITER $$

CREATE TRIGGER trg_before_insert_transaction_extra_items
BEFORE INSERT ON transaction_extra_items
FOR EACH ROW
BEGIN
    DECLARE v_item_price DECIMAL(10,2);

    IF NEW.quantity IS NULL OR NEW.quantity <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Quantity must be greater than 0.';
    END IF;

    SELECT price
    INTO v_item_price
    FROM extra_items
    WHERE extra_item_id = NEW.extra_item_id
    LIMIT 1;

    IF v_item_price IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid extra item reference.';
    END IF;

    SET NEW.subtotal = v_item_price * NEW.quantity;
END$$

DELIMITER ;


DROP TRIGGER IF EXISTS trg_before_insert_payments;

DELIMITER $$

CREATE TRIGGER trg_before_insert_payments
BEFORE INSERT ON payments
FOR EACH ROW
BEGIN
    DECLARE v_total_amount DECIMAL(10,2);

    SELECT total_amount
    INTO v_total_amount
    FROM transactions
    WHERE transaction_id = NEW.transaction_id
    LIMIT 1;

    IF v_total_amount IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid transaction reference.';
    END IF;

    IF NEW.payment_status NOT IN ('Paid', 'Unpaid') THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Payment status must be either Paid or Unpaid.';
    END IF;

    SET NEW.payment_amount = v_total_amount;

    IF NEW.payment_status = 'Paid' THEN
        IF NEW.payment_method IS NULL OR NEW.payment_method NOT IN ('Cash', 'GCash') THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Paid payments must use Cash or GCash.';
        END IF;

        IF NEW.paid_at IS NULL THEN
            SET NEW.paid_at = NOW();
        END IF;
    ELSE
        SET NEW.payment_method = NULL;
        SET NEW.paid_at = NULL;
    END IF;

    IF NEW.payment_amount < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Payment amount cannot be negative.';
    END IF;
END$$

DELIMITER ;