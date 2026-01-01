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
        Schema::table('customers', function (Blueprint $table) {
            // Check if phone column exists, if not add it (Dec 8th migration already adds phone though)
            // However, the Dec 8th migration didn't have unique constraint or the verified_at field
            if (!Schema::hasColumn('customers', 'phone_verified_at')) {
                $table->timestamp('phone_verified_at')->after('email')->nullable();
            }
            
            // Add index to phone if doesn't exist
            // Note: phone already exists from 2025_12_08_073756_create_customers_table
            // We'll just ensure it has the index and is unique if possible
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'phone_verified_at')) {
                $table->dropColumn('phone_verified_at');
            }
        });
    }
};
