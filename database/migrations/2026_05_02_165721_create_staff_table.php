<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id('staff_id');
            $table->string('sf_name', 50);
            $table->string('sm_name', 50)->nullable();
            $table->string('sl_name', 50);
            $table->date('date_of_birth');
            $table->unsignedInteger('age');
            $table->string('contact_number', 20);
            $table->string('address', 255);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};