<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('productID');
            $table->string('color',100);
            $table->string('color_code',100);
            $table->string('size',100);
            $table->string('variant_name',100);
            $table->decimal('price', 12, 0);
            $table->decimal('purchase_price', 12, 0);
            $table->decimal('sale_price', 12, 0);
            $table->integer('stock');
            $table->string('sku',100)->unique();
            $table->enum('status',['active','out_of_stock','deleted','draft']);
            $table->foreign('productID')->references('id')->on('products')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
}; 