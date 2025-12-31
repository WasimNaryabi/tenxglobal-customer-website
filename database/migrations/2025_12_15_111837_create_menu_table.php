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
        Schema::create('menu_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_id')->unique();
            $table->string('name');
            $table->string('slug')->index();
            $table->string('image_url')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('item_count')->default(0);
            $table->integer('total_items')->default(0);
            $table->json('subcategories')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_id')->unique();
            $table->string('name');
            $table->string('slug')->index();
            $table->text('description')->nullable();
            $table->foreignId('menu_category_id')->constrained('menu_categories')->onDelete('cascade');
            $table->string('category_name');
            $table->string('category_slug')->index();
            $table->decimal('price', 10, 2);
            $table->decimal('original_price', 10, 2);
            $table->integer('discount')->default(0);
            $table->string('image')->nullable();
            $table->boolean('is_new')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->integer('reviews')->default(0);
            $table->boolean('is_taxable')->default(false);
            $table->string('label_color')->nullable();
            $table->json('meals')->nullable();
            $table->json('ingredients')->nullable();
            $table->json('nutrition')->nullable();
            $table->json('allergies')->nullable();
            $table->json('tags')->nullable();
            $table->json('variants')->nullable();
            $table->unsignedBigInteger('addon_group_id')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['category_slug', 'active']);
            $table->index(['is_featured', 'active']);
        });

        Schema::create('menu_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['success', 'failed', 'partial'])->default('success');
            $table->integer('items_synced')->default(0);
            $table->integer('categories_synced')->default(0);
            $table->text('message')->nullable();
            $table->json('errors')->nullable();
            $table->timestamp('synced_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menu_categories');
        Schema::dropIfExists('menu_sync_logs');
    }
};