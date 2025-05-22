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
            $table->unsignedBigInteger('userID');
            $table->unsignedBigInteger('shopID');
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

            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('shopID')->references('id')->on('shops')->onDelete('cascade');
            $table->timestamps();

            $table->index('order_code');
            $table->index('payment_status');
            $table->index('order_status');
            $table->index('created_at');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('productID');
            $table->unsignedBigInteger('variantID')->nullable();
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->string('sku', 100);
            $table->string('product_name', 255);
            $table->string('brand', 100);
            $table->string('category', 100);
            $table->string('sub_category', 100);
            $table->string('color', 100)->nullable();
            $table->string('size', 100)->nullable();
            $table->string('variant_name', 100)->nullable();
            $table->text('product_image')->nullable();
            $table->text('note')->nullable();
            $table->boolean('is_reviewed')->default(false);

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('productID')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variantID')->references('id')->on('product_variants')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('order_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('receiver_name', 100);
            $table->string('receiver_phone', 11);
            $table->string('receiver_email', 100)->nullable();
            $table->string('address', 255);
            $table->string('province', 100);
            $table->string('district', 100);
            $table->string('ward', 100);
            $table->string('zip_code', 10)->nullable();
            $table->text('note')->nullable();
            $table->enum('address_type', ['home', 'office', 'other'])->default('home');

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded']);
            $table->text('description')->nullable();
            $table->string('shipping_provider')->nullable();
            $table->text('note')->nullable();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->timestamps();
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
