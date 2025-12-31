<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if table exists
        if (!Schema::hasTable('addon_groups')) {
            return;
        }

        // Add missing columns if they don't exist
        Schema::table('addon_groups', function (Blueprint $table) {
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('addon_groups', 'status')) {
                $table->string('status')->default('active')->after('max_selections');
            }
            
            // Add sort_order if it doesn't exist
            if (!Schema::hasColumn('addon_groups', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('status');
            }
            
            // Add deleted_at if it doesn't exist
            if (!Schema::hasColumn('addon_groups', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Do the same for addons table
        if (Schema::hasTable('addons')) {
            Schema::table('addons', function (Blueprint $table) {
                // Add description column if it doesn't exist
                if (!Schema::hasColumn('addons', 'description')) {
                    $table->string('description')->nullable()->after('name');
                }
                
                // Add status column if it doesn't exist
                if (!Schema::hasColumn('addons', 'status')) {
                    $table->string('status')->default('active')->after('price');
                }
                
                // Add sort_order if it doesn't exist
                if (!Schema::hasColumn('addons', 'sort_order')) {
                    $table->integer('sort_order')->default(0)->after('status');
                }
                
                // Add deleted_at if it doesn't exist
                if (!Schema::hasColumn('addons', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }

        // Update existing records to have 'active' status if they don't
        DB::table('addon_groups')->whereNull('status')->update(['status' => 'active']);
        DB::table('addons')->whereNull('status')->update(['status' => 'active']);
    }

    public function down(): void
    {
        // We won't drop columns in down() to prevent data loss
        // If you need to rollback, do it manually
    }
};