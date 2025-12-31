<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        // Addresses table
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('label'); // Home, Work, etc.
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('city');
            $table->string('postcode', 20);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            
            $table->index(['user_id', 'is_default']);
        });

        // Orders table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('order_number')->unique();
            $table->enum('type', ['delivery', 'pickup'])->default('delivery');
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'ready', 'out_for_delivery', 'delivered', 'cancelled'])->default('pending');
            
            // Customer info
            $table->string('customer_name');
            $table->string('customer_phone', 20);
            $table->string('customer_email')->nullable();
            
            // Delivery address (if delivery)
            $table->foreignId('address_id')->nullable()->constrained()->onDelete('set null');
            $table->text('delivery_address')->nullable();
            $table->text('delivery_instructions')->nullable();
            
            // Pickup info (if pickup)
            $table->string('pickup_time')->nullable();
            
            // Payment
            $table->enum('payment_method', ['card', 'cash'])->default('card');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            
            // Pricing
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('vat', 10, 2);
            $table->decimal('total', 10, 2);
            
            // Special instructions
            $table->text('special_instructions')->nullable();
            
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index('order_number');
        });

        // Order items table
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_item_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->json('customizations')->nullable();
            $table->timestamps();
            
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('addresses');
    }
};