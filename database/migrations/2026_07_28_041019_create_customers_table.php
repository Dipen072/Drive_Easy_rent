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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            
            // Basic Details
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->date('dob')->nullable();
            $table->string('profile_picture')->nullable();
            
            // Account Security
            $table->string('password');
            
            // Address Details
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable()->default('India');
            $table->string('zip_code')->nullable();
            
            // Driving License & Alternate ID Verification Details
            $table->boolean('has_dl')->default(true);
            $table->string('dl_number')->nullable();
            $table->date('dl_expiry')->nullable();
            $table->string('dl_file')->nullable();
            $table->string('alt_id_type')->nullable(); // Aadhaar Card, Passport, Voter ID
            $table->string('alt_id_number')->nullable();
            $table->string('alt_id_file')->nullable();
            
            // Status & Timestamps
            $table->string('status')->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
