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
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'phone_verified_at')) {
                $table->timestamp('phone_verified_at')->nullable()->after('email');
            }
            
            // Update phone to be unique if it's not already, and ensure it's string(20)
            // We need to be careful with existing data if we add unique constraint.
            // For now, let's just make sure the column exists or modify it.
            // Since it's an update, let's just add the index if possible or modify.
            // The previous migration made it nullable. This one wants it unique and length 20.
            if (Schema::hasColumn('customers', 'phone')) {
                 $table->string('phone', 20)->unique()->change();
            } else {
                 $table->string('phone', 20)->unique();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
             if (Schema::hasColumn('customers', 'phone_verified_at')) {
                $table->dropColumn('phone_verified_at');
            }
            // Reverting phone change is complex depending on original state, 
            // but we can try to make it nullable and drop unique if needed.
            // For safety in this context, we might just leave the phone col modification or revert strictly.
             $table->string('phone')->nullable()->change();
             $table->dropUnique(['phone']);
        });
    }
};
