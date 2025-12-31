<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            if (!Schema::hasColumn('addon_groups', 'description')) {
                $table->string('description')->default('description')->after('name');
            }
            
            
        });

        // Update existing records to have 'active' status if they don't
        DB::table('addon_groups')->whereNull('description')->update(['description' => 'description']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
