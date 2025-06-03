<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('orders')->insert([
            [
                'id' => 1,
                'userID' => 3,
                'shopID' => 1,
                'order_code' => 'ORDER001',
                'total_price' => 3600000.00,
                'coupon_id' => null,
                'coupon_discount' => 0.00,
                'payment_method' => 'cod',
                'payment_status' => 'paid',
                'order_status' => 'delivered',
                'order_note' => 'Giao hàng nhanh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}