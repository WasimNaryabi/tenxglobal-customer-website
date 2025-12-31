<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add soft deletes to addon_groups if table exists and column doesn't
        if (Schema::hasTable('addon_groups') && !Schema::hasColumn('addon_groups', 'deleted_at')) {
            Schema::table('addon_groups', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add soft deletes to addons if table exists and column doesn't
        if (Schema::hasTable('addons') && !Schema::hasColumn('addons', 'deleted_at')) {
            Schema::table('addons', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('addon_groups', 'deleted_at')) {
            Schema::table('addon_groups', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasColumn('addons', 'deleted_at')) {
            Schema::table('addons', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};