<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //======================================================================
        // SẢN PHẨM 1: Laptop Pro
        //======================================================================

        // Bảng products
        DB::table('products')->insert([
            [
                'id' => 2,
                'shopID' => 1,
                'name' => 'Laptop Pro',
                'slug' => 'laptop-pro',
                'description' => 'Powerful laptop for professionals',
                'price' => 30000000,
                'purchase_price' => 25000000,
                'sale_price' => 28000000,
                'sold_quantity' => 5,
                'stock_total' => 30,
                'sku' => 'LPPRO001',
                'brand' => 'Brand B',
                'category' => 'Electronics',
                'sub_category' => 'Laptops',
                'sub_brand' => null,
                'status' => 'active',
                'meta_title' => null,
                'meta_description' => null,
                'meta_keywords' => null,
                'is_featured' => 1,
                'is_variant' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng product_variants
        DB::table('product_variants')->insert([
            // Biến thể 1: Silver 256GB SSD
            [
                'id' => 2,
                'productID' => 2,
                'variant_name' => 'Silver 256GB SSD',
                'price' => 30000000,
                'purchase_price' => 25000000,
                'sale_price' => 28000000,
                'stock' => 15,
                'sku' => 'LPPRO001-SVR-256',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Biến thể 2: Silver 512GB SSD
            [
                'id' => 3,
                'productID' => 2,
                'variant_name' => 'Silver 512GB SSD',
                'price' => 35000000,
                'purchase_price' => 29000000,
                'sale_price' => 33000000,
                'stock' => 10,
                'sku' => 'LPPRO001-SVR-512',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Biến thể 3: Space Gray 512GB SSD
            [
                'id' => 4,
                'productID' => 2,
                'variant_name' => 'Space Gray 512GB SSD',
                'price' => 35000000,
                'purchase_price' => 29000000,
                'sale_price' => 33000000,
                'stock' => 5,
                'sku' => 'LPPRO001-GRY-512',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng product_images (Thêm ảnh cho từng biến thể)
        DB::table('product_images')->insert([
            ['productID' => 2, 'image_path' => '/products/laptop-pro-silver.png', 'variantID' => 2, 'is_default' => 1, 'display_order' => 1, 'alt_text' => 'Laptop Pro Silver', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['productID' => 2, 'image_path' => '/products/laptop-pro-silver-2.png', 'variantID' => 3, 'is_default' => 1, 'display_order' => 1, 'alt_text' => 'Laptop Pro Silver', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['productID' => 2, 'image_path' => '/products/laptop-pro-gray.png', 'variantID' => 4, 'is_default' => 1, 'display_order' => 1, 'alt_text' => 'Laptop Pro Space Gray', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
        
        // Bảng product_dimensions
        DB::table('product_dimensions')->insert([
            ['productID' => 2, 'variantID' => 2, 'length' => 30.41, 'width' => 21.24, 'height' => 1.56, 'weight' => 1.29, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['productID' => 2, 'variantID' => 3, 'length' => 30.41, 'width' => 21.24, 'height' => 1.56, 'weight' => 1.29, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['productID' => 2, 'variantID' => 4, 'length' => 30.41, 'width' => 21.24, 'height' => 1.56, 'weight' => 1.29, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);


        //======================================================================
        // SẢN PHẨM 2: Wireless Headphones
        //======================================================================

        // Bảng products
        DB::table('products')->insert([
            [
                'id' => 3,
                'shopID' => 1,
                'name' => 'Wireless Headphones',
                'slug' => 'wireless-headphones',
                'description' => 'Noise-cancelling over-ear headphones',
                'price' => 5000000,
                'purchase_price' => 3500000,
                'sale_price' => 4500000,
                'sold_quantity' => 20,
                'stock_total' => 100,
                'sku' => 'HDPHN002',
                'brand' => 'Brand C',
                'category' => 'Electronics',
                'sub_category' => 'Audio',
                'sub_brand' => null,
                'status' => 'active',
                'meta_title' => null,
                'meta_description' => null,
                'meta_keywords' => null,
                'is_featured' => 0,
                'is_variant' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng product_variants
        DB::table('product_variants')->insert([
            // Biến thể 1: Matte Black
            [
                'id' => 5,
                'productID' => 3,
                'variant_name' => 'Matte Black',
                'price' => 5000000,
                'purchase_price' => 3500000,
                'sale_price' => 4500000,
                'stock' => 50,
                'sku' => 'HDPHN002-BLK',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Biến thể 2: Midnight Blue
            [
                'id' => 6,
                'productID' => 3,
                'variant_name' => 'Midnight Blue',
                'price' => 5200000,
                'purchase_price' => 3700000,
                'sale_price' => 4700000,
                'stock' => 30,
                'sku' => 'HDPHN002-BLU',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Biến thể 3: Pearl White
            [
                'id' => 7,
                'productID' => 3,
                'variant_name' => 'Pearl White',
                'price' => 5200000,
                'purchase_price' => 3700000,
                'sale_price' => 4700000,
                'stock' => 20,
                'sku' => 'HDPHN002-WHT',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
        
        // Bảng product_images
        DB::table('product_images')->insert([
            ['productID' => 3, 'image_path' => '/products/headphones-black.png', 'variantID' => 5, 'is_default' => 1, 'display_order' => 1, 'alt_text' => 'Headphones Black', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['productID' => 3, 'image_path' => '/products/headphones-blue.png', 'variantID' => 6, 'is_default' => 1, 'display_order' => 1, 'alt_text' => 'Headphones Blue', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['productID' => 3, 'image_path' => '/products/headphones-white.png', 'variantID' => 7, 'is_default' => 1, 'display_order' => 1, 'alt_text' => 'Headphones White', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
        
        // Bảng product_dimensions
        DB::table('product_dimensions')->insert([
            ['productID' => 3, 'variantID' => 5, 'length' => 18.0, 'width' => 16.5, 'height' => 8.0, 'weight' => 0.25, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['productID' => 3, 'variantID' => 6, 'length' => 18.0, 'width' => 16.5, 'height' => 8.0, 'weight' => 0.25, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['productID' => 3, 'variantID' => 7, 'length' => 18.0, 'width' => 16.5, 'height' => 8.0, 'weight' => 0.25, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);


        //======================================================================
        // SẢN PHẨM 3: Smartwatch 2
        //======================================================================

        // Bảng products
        DB::table('products')->insert([
            [
                'id' => 4,
                'shopID' => 1,
                'name' => 'Smartwatch 2',
                'slug' => 'smartwatch-2',
                'description' => 'Next generation smartwatch with health tracking',
                'price' => 8000000,
                'purchase_price' => 6000000,
                'sale_price' => 7500000,
                'sold_quantity' => 15,
                'stock_total' => 60,
                'sku' => 'SMWCH003',
                'brand' => 'Brand A',
                'category' => 'Wearables',
                'sub_category' => 'Smartwatches',
                'sub_brand' => null,
                'status' => 'active',
                'meta_title' => null,
                'meta_description' => null,
                'meta_keywords' => null,
                'is_featured' => 1,
                'is_variant' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Bảng product_variants
        DB::table('product_variants')->insert([
            // Biến thể 1: Silver Aluminum
            [
                'id' => 8,
                'productID' => 4,
                'variant_name' => 'Silver Aluminum',
                'price' => 8000000,
                'purchase_price' => 6000000,
                'sale_price' => 7500000,
                'stock' => 30,
                'sku' => 'SMWCH003-SVR-AL',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Biến thể 2: Gold Aluminum
            [
                'id' => 9,
                'productID' => 4,
                'variant_name' => 'Gold Aluminum',
                'price' => 8500000,
                'purchase_price' => 6500000,
                'sale_price' => 8000000,
                'stock' => 20,
                'sku' => 'SMWCH003-GLD-AL',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Biến thể 3: Graphite Stainless Steel
            [
                'id' => 10,
                'productID' => 4,
                'variant_name' => 'Graphite Stainless Steel',
                'price' => 10000000,
                'purchase_price' => 8000000,
                'sale_price' => 9500000,
                'stock' => 10,
                'sku' => 'SMWCH003-GPH-SS',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
        
        // Bảng product_images
        DB::table('product_images')->insert([
            ['productID' => 4, 'image_path' => '/products/smartwatch-silver.png', 'variantID' => 8, 'is_default' => 1, 'display_order' => 1, 'alt_text' => 'Smartwatch Silver', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['productID' => 4, 'image_path' => '/products/smartwatch-gold.png', 'variantID' => 9, 'is_default' => 1, 'display_order' => 1, 'alt_text' => 'Smartwatch Gold', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['productID' => 4, 'image_path' => '/products/smartwatch-graphite.png', 'variantID' => 10, 'is_default' => 1, 'display_order' => 1, 'alt_text' => 'Smartwatch Graphite', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        // Bảng product_dimensions
        DB::table('product_dimensions')->insert([
            ['productID' => 4, 'variantID' => 8, 'length' => 4.4, 'width' => 3.8, 'height' => 1.07, 'weight' => 0.036, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['productID' => 4, 'variantID' => 9, 'length' => 4.4, 'width' => 3.8, 'height' => 1.07, 'weight' => 0.036, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['productID' => 4, 'variantID' => 10, 'length' => 4.4, 'width' => 3.8, 'height' => 1.07, 'weight' => 0.047, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);


        //======================================================================
        // THUỘC TÍNH VÀ GIÁ TRỊ THUỘC TÍNH (Cho các sản phẩm mới)
        //======================================================================
        
        // Bảng attributes (Thêm 'Storage' và 'Material' nếu chưa có)
        DB::table('attributes')->insert([
            ['id' => 2, 'name' => 'Storage', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'name' => 'Material', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        // Bảng attribute_values
        DB::table('attribute_values')->insert([
            // Values for Color (thêm màu mới)
            ['id' => 2, 'attribute_id' => 1, 'value' => 'Silver', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'attribute_id' => 1, 'value' => 'Space Gray', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'attribute_id' => 1, 'value' => 'Midnight Blue', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 5, 'attribute_id' => 1, 'value' => 'Pearl White', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 6, 'attribute_id' => 1, 'value' => 'Gold', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 7, 'attribute_id' => 1, 'value' => 'Graphite', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Values for Storage
            ['id' => 8, 'attribute_id' => 2, 'value' => '256GB SSD', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 9, 'attribute_id' => 2, 'value' => '512GB SSD', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            
            // Values for Material
            ['id' => 10, 'attribute_id' => 3, 'value' => 'Aluminum', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 11, 'attribute_id' => 3, 'value' => 'Stainless Steel', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        // Bảng product_variant_attribute_values (Liên kết biến thể với giá trị thuộc tính)
        DB::table('product_variant_attribute_values')->insert([
            // Laptop Pro Variants
            ['product_variant_id' => 2, 'attribute_value_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()], // Silver
            ['product_variant_id' => 2, 'attribute_value_id' => 8, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()], // 256GB
            ['product_variant_id' => 3, 'attribute_value_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()], // Silver
            ['product_variant_id' => 3, 'attribute_value_id' => 9, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()], // 512GB
            ['product_variant_id' => 4, 'attribute_value_id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()], // Space Gray
            ['product_variant_id' => 4, 'attribute_value_id' => 9, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()], // 512GB

            // Headphones Variants
            ['product_variant_id' => 5, 'attribute_value_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()], // Black (assuming 'Matte Black' uses the 'Black' value)
            ['product_variant_id' => 6, 'attribute_value_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()], // Midnight Blue
            ['product_variant_id' => 7, 'attribute_value_id' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()], // Pearl White

            // Smartwatch Variants
            ['product_variant_id' => 8, 'attribute_value_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()], // Silver
            ['product_variant_id' => 8, 'attribute_value_id' => 10, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],// Aluminum
            ['product_variant_id' => 9, 'attribute_value_id' => 6, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()], // Gold
            ['product_variant_id' => 9, 'attribute_value_id' => 10, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],// Aluminum
            ['product_variant_id' => 10, 'attribute_value_id' => 7, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],// Graphite
            ['product_variant_id' => 10, 'attribute_value_id' => 11, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],// Stainless Steel
        ]);
    }
}