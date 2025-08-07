<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tạo bảng product_brands
        Schema::create('product_brands', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('brand_id');
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brand')->onDelete('cascade');
            $table->unique(['product_id', 'brand_id'], 'product_brands_unique');
        });

        // Tạo bảng product_categories
        Schema::create('product_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unique(['product_id', 'category_id'], 'product_categories_unique');
        });

        // Di chuyển dữ liệu cũ từ cột brand và category
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            // Xử lý brands
            if (!empty($product->brand)) {
                $brandNames = explode(',', $product->brand);
                $brandIds = DB::table('brand')->whereIn('name', $brandNames)->pluck('id')->toArray();
                foreach ($brandIds as $brandId) {
                    DB::table('product_brands')->insert([
                        'product_id' => $product->id,
                        'brand_id' => $brandId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Xử lý categories
            if (!empty($product->category)) {
                $categoryNames = explode(',', $product->category);
                $categoryIds = DB::table('categories')->whereIn('name', $categoryNames)->pluck('id')->toArray();
                foreach ($categoryIds as $categoryId) {
                    DB::table('product_categories')->insert([
                        'product_id' => $product->id,
                        'category_id' => $categoryId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // Xóa cột brand và category khỏi bảng products
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['brand', 'category']);
        });
    }

    public function down(): void
    {
        // Thêm lại cột brand và category
        Schema::table('products', function (Blueprint $table) {
            $table->string('brand', 100)->after('sku')->nullable();
            $table->string('category', 100)->after('brand')->nullable();
        });

        // Di chuyển dữ liệu từ bảng product_brands và product_categories về cột brand và category
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            // Lấy brands
            $brandNames = DB::table('product_brands')
                ->join('brand', 'product_brands.brand_id', '=', 'brand.id')
                ->where('product_brands.product_id', $product->id)
                ->pluck('brand.name')
                ->toArray();
            if (!empty($brandNames)) {
                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['brand' => implode(',', $brandNames)]);
            }

            // Lấy categories
            $categoryNames = DB::table('product_categories')
                ->join('categories', 'product_categories.category_id', '=', 'categories.id')
                ->where('product_categories.product_id', $product->id)
                ->pluck('categories.name')
                ->toArray();
            if (!empty($categoryNames)) {
                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['category' => implode(',', $categoryNames)]);
            }
        }

        // Xóa bảng product_brands và product_categories
        Schema::dropIfExists('product_brands');
        Schema::dropIfExists('product_categories');
    }
};
