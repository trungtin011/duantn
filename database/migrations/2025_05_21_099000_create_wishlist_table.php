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
        Schema::create('wishlist', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userID');
            $table->unsignedBigInteger('productID');
            $table->unsignedBigInteger('shopID');
            $table->text('note')->nullable();
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('productID')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('shopID')->references('id')->on('shops')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['userID', 'productID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlist');
    }
};
