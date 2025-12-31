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
        // Check if table exists
        if (!Schema::hasTable('addon_groups')) {
            return;
        }

        // Add missing columns if they don't exist
        Schema::table('addon_groups', function (Blueprint $table) {
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('addon_groups', 'api_id')) {
                $table->integer('api_id')->default(0)->after('id');
            }
           
        });

        // Do the same for addons table
        if (Schema::hasTable('addons')) {
            Schema::table('addons', function (Blueprint $table) {
                // Add description column if it doesn't exist
                if (!Schema::hasColumn('addons', 'api_id')) {
                $table->integer('api_id')->default(0)->after('id');
            }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
