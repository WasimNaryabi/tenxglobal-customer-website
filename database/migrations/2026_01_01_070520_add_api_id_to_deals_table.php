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
        if (!Schema::hasTable('deals')) {
            Schema::create('deals', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->decimal('price', 10, 2)->default(0);
                $table->text('description')->nullable();
                $table->unsignedBigInteger('upload_id')->nullable();
                $table->boolean('status')->default(1);
                $table->boolean('is_taxable')->default(0);
                $table->string('label_color')->nullable();
                $table->unsignedBigInteger('category_id')->nullable();
                $table->unsignedBigInteger('subcategory_id')->nullable();
                $table->unsignedBigInteger('api_id')->nullable()->unique();
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            Schema::table('deals', function (Blueprint $table) {
                if (!Schema::hasColumn('deals', 'api_id')) {
                    $table->unsignedBigInteger('api_id')->nullable()->unique()->after('id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deals', function (Blueprint $table) {
             if (Schema::hasColumn('deals', 'api_id')) {
                $table->dropColumn('api_id');
             }
        });
    }
};
