## Database Triggers

The system includes MySQL triggers for database-level validation and computation.

Trigger files:

- `database/migrations/2026_05_07_000001_create_project_triggers.php`
- `database/sql/triggers.sql`

Implemented triggers:

1. `trg_before_insert_transactions`
    - Validates service and wash-load rules.
    - Computes base transaction total.

2. `trg_before_insert_transaction_extra_items`
    - Validates extra item quantity.
    - Computes subtotal automatically.

3. `trg_before_insert_payments`
    - Validates payment status and method.
    - Syncs payment amount with transaction total.
