<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');

            $table->foreignId('transaction_id')
                ->unique()
                ->constrained('transactions', 'transaction_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->decimal('payment_amount', 10, 2);
            $table->string('payment_method', 50)->nullable();
            $table->string('payment_status', 30);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};