<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userID')->nullable(); 
            $table->string('order_code', 100)->unique();
            $table->decimal('total_price', 12, 2);
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->decimal('coupon_discount', 12, 2)->default(0);
            $table->string('payment_method', 100);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded']);
            $table->enum('order_status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded']);
            $table->text('order_note')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            $table->foreign('userID')->references('id')->on('users')->onDelete('set null'); // Thay cascade thành set null
            $table->timestamps();

            $table->index('order_code');
            $table->index('payment_status');
            $table->index('order_status');
            $table->index('created_at');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('order_addresses');
        Schema::dropIfExists('order_status_history');
    }
};
