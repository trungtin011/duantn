<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('cart')->insert([
            [
                'id' => 1,
                'userID' => 3,
                'productID' => 1,
                'variantID' => 1,
                'quantity' => 2,
                'price' => 1800000,
                'total_price' => 3600000,
                'session_id' => 'SESSION123456',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}