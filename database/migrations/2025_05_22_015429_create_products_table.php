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

            // Add indexes
            $table->index('name');
            $table->index('category');
            $table->index('brand');
            $table->index('status');
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('productID');
            $table->text('image_path');
            $table->unsignedBigInteger('variantID')->nullable();
            $table->boolean('is_default')->default(false);
            $table->integer('display_order')->default(0);
            $table->string('alt_text',100);
            $table->foreign('productID')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variantID')->references('id')->on('product_variants')->onDelete('cascade');
            
            $table->timestamps();
        });

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


        Schema::create('product_dimensions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('productID');
            $table->unsignedBigInteger('variantID')->nullable();
            $table->decimal('length');
            $table->decimal('width');
            $table->decimal('height');
            $table->decimal('weight');
            $table->foreign('productID')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variantID')->references('id')->on('product_variants')->onDelete('cascade');
            $table->timestamps();
        });
        
    }


    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
