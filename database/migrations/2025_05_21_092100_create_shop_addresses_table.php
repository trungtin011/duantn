<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shopID');
            $table->string('shop_address',255);
            $table->string('shop_province',100);
            $table->string('shop_district',100);
            $table->string('shop_ward',100);
            $table->text('note')->nullable();
            $table->boolean('is_default')->default(false);
            $table->foreign('shopID')->references('id')->on('shops')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('shop_addresses');
    }
}; 