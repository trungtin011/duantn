<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('order_items')->insert([
            [
                'id' => 1,
                'order_id' => 1,
                'productID' => 1,
                'variantID' => 1,
                'quantity' => 2,
                'unit_price' => 1800000.00,
                'total_price' => 3600000.00,
                'discount_amount' => 0.00,
                'sku' => 'SKU-NIKE001-BLACK42',
                'product_name' => 'Giày Nike Air Max',
                'brand' => 'Nike',
                'category' => 'Thời trang',
                'sub_category' => 'Giày dép',
                'color' => 'Đen',
                'size' => '42',
                'variant_name' => 'Nike Air Max Đen 42',
                'product_image' => 'products/nike-air-max-black42.jpg',
                'is_reviewed' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}