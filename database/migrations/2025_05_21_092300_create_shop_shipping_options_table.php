<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_shipping_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shopID');
            $table->string('shipping_type', 50);
            $table->boolean('cod_enabled')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('shopID')->references('id')->on('shops')->onDelete('cascade');
            $table->unique(['shopID', 'shipping_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_shipping_options');
    }
}; 