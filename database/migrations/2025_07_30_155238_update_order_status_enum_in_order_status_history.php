<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Xóa cột order_status cũ
        Schema::table('order_status_history', function (Blueprint $table) {
            $table->dropColumn('order_status');
        });

        Schema::table('order_status_history', function (Blueprint $table) {
            $table->enum('order_status', [
                'pending',
                'processing',
                'completed',
                'cancelled',
                'shipping_failed',
                'returned',
                'damage',   
                'lost'      
            ])->default('pending')->after('order_id');
        });
    }

    public function down(): void
    {
        // Xóa cột order_status mới
        Schema::table('order_status_history', function (Blueprint $table) {
            $table->dropColumn('order_status');
        });

        // Thêm lại cột order_status với enum cũ (bạn cần điền đúng các giá trị cũ)
        Schema::table('order_status_history', function (Blueprint $table) {
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
            ])->default('pending')->after('order_id');
        });
    }
};