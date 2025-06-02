<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('products')->insert([
            [
                'id' => 1,
                'shopID' => 1,
                'name' => 'Giày Nike Air Max',
                'slug' => 'giay-nike-air-max',
                'description' => 'Giày thể thao Nike Air Max chính hãng',
                'price' => 2000000,
                'purchase_price' => 1500000,
                'sale_price' => 1800000,
                'sold_quantity' => 50,
                'stock_total' => 100,
                'sku' => 'SKU-NIKE001',
                'brand' => 'Nike',
                'category' => 'Thời trang',
                'sub_category' => 'Giày dép',
                'status' => 'active',
                'meta_title' => 'Giày Nike Air Max',
                'meta_description' => 'Giày thể thao chất lượng cao',
                'meta_keywords' => 'giày, nike, air max',
                'is_featured' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'shopID' => 1,
                'name' => 'Điện thoại Samsung Galaxy',
                'slug' => 'dien-thoai-samsung-galaxy',
                'description' => 'Điện thoại Samsung Galaxy cao cấp',
                'price' => 10000000,
                'purchase_price' => 8000000,
                'sale_price' => 9500000,
                'sold_quantity' => 20,
                'stock_total' => 50,
                'sku' => 'SKU-SAMSUNG001',
                'brand' => 'Samsung',
                'category' => 'Điện tử',
                'sub_category' => 'Điện thoại',
                'status' => 'active',
                'meta_title' => 'Samsung Galaxy',
                'meta_description' => 'Điện thoại thông minh cao cấp',
                'meta_keywords' => 'samsung, galaxy, điện thoại',
                'is_featured' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}