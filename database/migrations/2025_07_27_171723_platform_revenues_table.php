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
        Schema::create('platform_revenues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_order_id')->constrained('shop_order');
            $table->foreignId('order_id')->constrained('orders');
            $table->foreignId('shop_id')->constrained('shops');
            $table->string('shop_name');
            $table->string('payment_method');
            $table->decimal('commission_rate', 10, 2);
            $table->decimal('commission_amount', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('net_revenue', 10, 2);
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded']);
            $table->dateTime('confirmed_at')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_revenues');
    }
};
