<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_sync_logs', function (Blueprint $table) {
            $table->integer('addon_groups_synced')->default(0)->after('categories_synced');
            $table->integer('addons_synced')->default(0)->after('addon_groups_synced');
        });
    }

    public function down(): void
    {
        Schema::table('menu_sync_logs', function (Blueprint $table) {
            $table->dropColumn(['addon_groups_synced', 'addons_synced']);
        });
    }
};