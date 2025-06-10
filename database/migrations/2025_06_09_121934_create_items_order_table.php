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
        Schema::create('items_order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orderID')->constrained('orders');
            $table->foreignId('shop_orderID')->constrained('shop_order');
            $table->foreignId('productID')->constrained('products');
            $table->foreignId('variantID')->constrained('product_variants');
            $table->string('product_name')->nullable();
            $table->string('brand')->nullable();
            $table->string('category')->nullable();
            $table->string('sub_category')->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('variant_name')->nullable();
            $table->text('product_image')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('unit_price', 12, 2)->nullable();
            $table->decimal('total_price', 12, 2)->nullable();
            $table->decimal('discount_amount', 12, 2)->default(0)->nullable();
            $table->timestamps();

            $table->index('orderID');
            $table->index('shop_orderID');
            $table->index('productID');
            $table->index('variantID');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items_order');
    }
};
