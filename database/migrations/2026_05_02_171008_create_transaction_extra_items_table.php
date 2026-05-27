<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_extra_items', function (Blueprint $table) {
            $table->id('transaction_extra_item_id');

            $table->foreignId('transaction_id')
                ->constrained('transactions', 'transaction_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('extra_item_id')
                ->constrained('extra_items', 'extra_item_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unsignedInteger('quantity');
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_extra_items');
    }
};