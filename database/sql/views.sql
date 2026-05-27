DROP VIEW IF EXISTS vw_transaction_details;
DROP VIEW IF EXISTS vw_payment_details;
DROP VIEW IF EXISTS vw_customer_transaction_records;
DROP VIEW IF EXISTS vw_service_category_records;
DROP VIEW IF EXISTS vw_transaction_extra_items;

CREATE VIEW vw_customer_transaction_records AS
SELECT
    customers.customer_id,
    CONCAT_WS(' ', customers.cf_name, customers.cm_name, customers.cl_name) AS customer_name,
    customers.contact_number,
    transactions.transaction_id,
    service_types.service_name,
    statuses.status_name,
    transactions.weight_kg,
    transactions.number_of_loads,
    transactions.total_amount,
    transactions.created_at AS transaction_date
FROM customers
LEFT JOIN transactions
    ON customers.customer_id = transactions.customer_id
    AND transactions.deleted_at IS NULL
LEFT JOIN service_types
    ON transactions.service_type_id = service_types.service_type_id
    AND service_types.deleted_at IS NULL
LEFT JOIN statuses
    ON transactions.status_id = statuses.status_id
    AND statuses.deleted_at IS NULL
WHERE customers.deleted_at IS NULL;

CREATE VIEW vw_service_category_records AS
SELECT
    service_categories.service_category_id,
    service_categories.category_name,
    service_categories.description AS category_description,
    service_types.service_type_id,
    service_types.service_name,
    service_types.price,
    service_types.description AS service_description
FROM service_categories
LEFT JOIN service_types
    ON service_categories.service_category_id = service_types.service_category_id
    AND service_types.deleted_at IS NULL
WHERE service_categories.deleted_at IS NULL;

CREATE VIEW vw_transaction_extra_items AS
SELECT
    transaction_extra_items.transaction_extra_item_id,
    transactions.transaction_id,
    CONCAT_WS(' ', customers.cf_name, customers.cm_name, customers.cl_name) AS customer_name,
    extra_items.item_name,
    extra_items.price AS item_price,
    transaction_extra_items.quantity,
    transaction_extra_items.subtotal,
    transaction_extra_items.created_at
FROM transaction_extra_items
INNER JOIN transactions
    ON transaction_extra_items.transaction_id = transactions.transaction_id
INNER JOIN customers
    ON transactions.customer_id = customers.customer_id
INNER JOIN extra_items
    ON transaction_extra_items.extra_item_id = extra_items.extra_item_id
WHERE transaction_extra_items.deleted_at IS NULL
  AND transactions.deleted_at IS NULL
  AND customers.deleted_at IS NULL
  AND extra_items.deleted_at IS NULL;