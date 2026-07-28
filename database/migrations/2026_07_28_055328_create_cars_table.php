<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('brand_name');
            $table->string('model_name');
            $table->string('year')->nullable()->default('2024');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->decimal('rate_per_day', 10, 2);
            $table->string('location')->nullable()->default('Mumbai');
            $table->integer('seats')->nullable()->default(5);
            $table->string('fuel_type')->nullable()->default('Petrol');
            $table->string('transmission')->nullable()->default('Automatic');
            $table->string('image')->nullable();
            $table->string('status')->default('Available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
