<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductVariantsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('product_variants')->insert([
            [
                'id' => 1,
                'productID' => 1,
                'variant_name' => 'Nike Air Max Đen 42',
                'price' => 2000000,
                'purchase_price' => 1500000,
                'sale_price' => 1800000,
                'stock' => 50,
                'sku' => 'SKU-NIKE001-BLACK42',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'productID' => 2,
                'variant_name' => 'Samsung Galaxy Bạc',
                'price' => 10000000,
                'purchase_price' => 8000000,
                'sale_price' => 9500000,
                'stock' => 30,
                'sku' => 'SKU-SAMSUNG001-SILVER',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}