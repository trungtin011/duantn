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
        // Drop the old order_status column from orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_status');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('order_status', [
                'pending',
                'processing', 
                'completed',
                'returned',
                'cancelled'
            ])->default('pending')->after('payment_status');
        });
    }
    
    public function down(): void
    {
        // Drop the new columns
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_status');
        });
        
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('order_status', [
                'pending',
                'partially_confirmed',
                'confirmed',
                'partially_ready_to_pick',
                'ready_to_pick',
                'partially_picked',
                'picked',
                'partially_shipping',
                'shipping',
                'partially_delivered',
                'delivered',
                'cancelled',
                'shipping_failed',
                'returned',
                'completed'
            ])->default('pending')->after('payment_status');
        });
    }
};
