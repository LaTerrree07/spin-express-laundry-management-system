<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transaction_id');

            $table->foreignId('customer_id')
                ->constrained('customers', 'customer_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('service_type_id')
                ->constrained('service_types', 'service_type_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('staff_id')
                ->constrained('staff', 'staff_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('status_id')
                ->constrained('statuses', 'status_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->decimal('weight_kg', 10, 2)->nullable();
            $table->unsignedInteger('number_of_loads');
            $table->decimal('total_amount', 10, 2);
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};