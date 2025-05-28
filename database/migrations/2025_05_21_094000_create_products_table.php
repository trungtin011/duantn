<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shopID');
            $table->string('name',100);
            $table->string('slug',100);
            $table->text('description');
            $table->decimal('price', 12, 0);
            $table->decimal('purchase_price', 12, 0);
            $table->decimal('sale_price', 12, 0);
            $table->integer('sold_quantity');
            $table->integer('stock_total');
            $table->string('sku',100)->unique();
            $table->string('brand',100);
            $table->string('category',100);
            $table->string('sub_category',100);
            $table->enum('status',['active','out_of_stock','deleted']);
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->foreign('shopID')->references('id')->on('shops')->onDelete('cascade');
            $table->timestamps();
            $table->index('name');
            $table->index('category');
            $table->index('brand');
            $table->index('status');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
}; 