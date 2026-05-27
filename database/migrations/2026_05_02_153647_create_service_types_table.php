<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_types', function (Blueprint $table) {
            $table->id('service_type_id');
            $table->foreignId('service_category_id')
                ->constrained('service_categories', 'service_category_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('service_name', 100);
            $table->decimal('price', 10, 2);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['service_category_id', 'service_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_types');
    }
};