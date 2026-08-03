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
        Schema::table('bookings', function (Blueprint $table) {
            $table->text('pickup_address')->nullable()->after('dropoff_location_id');
            $table->decimal('pickup_lat', 10, 8)->nullable()->after('pickup_address');
            $table->decimal('pickup_lng', 11, 8)->nullable()->after('pickup_lat');
            $table->text('dropoff_address')->nullable()->after('pickup_lng');
            $table->decimal('dropoff_lat', 10, 8)->nullable()->after('dropoff_address');
            $table->decimal('dropoff_lng', 11, 8)->nullable()->after('dropoff_lat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'pickup_address',
                'pickup_lat',
                'pickup_lng',
                'dropoff_address',
                'dropoff_lat',
                'dropoff_lng',
            ]);
        });
    }
};
