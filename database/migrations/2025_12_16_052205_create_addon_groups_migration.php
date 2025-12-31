<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Addon Groups table
        Schema::create('addon_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('min_select')->default(0);
            $table->integer('max_select')->default(1);
            $table->string('status')->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes(); // Add soft deletes
        });

        // Addons table
        Schema::create('addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('addon_group_id')->constrained('addon_groups')->onDelete('cascade');
            $table->string('name');
            $table->decimal('price', 10, 2)->default(0);
            $table->string('description')->nullable();
            $table->string('status')->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes(); // Add soft deletes
        });

        // Menu Item Addon Groups pivot table
        Schema::create('menu_item_addon_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('addon_group_id')->constrained('addon_groups')->onDelete('cascade');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->unique(['menu_item_id', 'addon_group_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_item_addon_group');
        Schema::dropIfExists('addons');
        Schema::dropIfExists('addon_groups');
    }
};