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
        // Create combo table
        Schema::create('combo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shopID');
            $table->string('combo_name', 100);
            $table->text('combo_description')->nullable();
            $table->decimal('total_price', 12, 2);
            $table->decimal('discount_value', 12, 2)->default(0.00);
            $table->enum('discount_type', ['percentage', 'fixed'])->nullable();
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('active');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('shopID')->references('id')->on('shops')->onDelete('cascade');
        });

        // Create combo_products table
        Schema::create('combo_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comboID');
            $table->unsignedBigInteger('productID');
            $table->unsignedBigInteger('variantID')->nullable();
            $table->integer('quantity')->default(1);
            $table->timestamps();

            // Unique constraint
            $table->unique(['comboID', 'productID', 'variantID'], 'combo_products_comboid_productid_variantid_unique');

            // Foreign key constraints
            $table->foreign('comboID')->references('id')->on('combo')->onDelete('cascade');
            $table->foreign('productID')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variantID')->references('id')->on('product_variants')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop combo_products table first due to foreign key dependency
        Schema::dropIfExists('combo_products');
        // Drop combo table
        Schema::dropIfExists('combo');
    }
};