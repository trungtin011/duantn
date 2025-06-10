<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shopID')->constrained('shops');
            $table->foreignId('orderID')->constrained('orders');
            $table->string('shipping_provider')->nullable();
            $table->string('shipping_fee')->nullable();
            $table->string('tracking_code')->nullable();
            $table->dateTime('expected_delivery_date')->nullable();
            $table->dateTime('actual_delivery_date')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'shipping', 'delivered', 'cancelled_by_shop', 'cancelled_by_customer','cancelled_by_admin','shipping_failed','returned'])->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_order');
    }
};
