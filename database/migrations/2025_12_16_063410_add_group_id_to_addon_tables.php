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
        // Do the same for addons table
        if (Schema::hasTable('addons')) {
            Schema::table('addons', function (Blueprint $table) {
                // Add description column if it doesn't exist
                if (!Schema::hasColumn('addons', 'api_group_id')) {
                $table->integer('api_group_id')->default(0)->after('addon_group_id');
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
