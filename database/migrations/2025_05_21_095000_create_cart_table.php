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
        Schema::create('cart', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userID');
            $table->unsignedBigInteger('productID');
            $table->unsignedBigInteger('variantID')->nullable();
            $table->integer('quantity');
            $table->decimal('price', 12, 0);
            $table->decimal('total_price', 12, 0);
            $table->string('session_id',100);
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('productID')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variantID')->references('id')->on('product_variants')->onDelete('cascade');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
