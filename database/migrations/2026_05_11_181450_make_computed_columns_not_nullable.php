<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure no existing NULL values remain before enforcing NOT NULL
        DB::statement("
            UPDATE transactions
            SET total_amount = 0
            WHERE total_amount IS NULL
        ");

        DB::statement("
            UPDATE transaction_extra_items
            SET subtotal = 0
            WHERE subtotal IS NULL
        ");

        DB::statement("
            UPDATE payments
            SET payment_amount = 0
            WHERE payment_amount IS NULL
        ");

        // Make computed columns NOT NULL
        DB::statement("
            ALTER TABLE transactions
            MODIFY total_amount DECIMAL(10,2) NOT NULL
        ");

        DB::statement("
            ALTER TABLE transaction_extra_items
            MODIFY subtotal DECIMAL(10,2) NOT NULL
        ");

        DB::statement("
            ALTER TABLE payments
            MODIFY payment_amount DECIMAL(10,2) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE transactions
            MODIFY total_amount DECIMAL(10,2) NULL
        ");

        DB::statement("
            ALTER TABLE transaction_extra_items
            MODIFY subtotal DECIMAL(10,2) NULL
        ");

        DB::statement("
            ALTER TABLE payments
            MODIFY payment_amount DECIMAL(10,2) NULL
        ");
    }
};