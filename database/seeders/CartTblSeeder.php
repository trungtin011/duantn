<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;

class CartTblSeeder extends Seeder
{
    public function run()
    {
        Cart::insert([
            [
                'userID' => 3,
                'productID' => 2,
                'variantID' => 2,
                'quantity' => 2,
                'price' => 10000000,
                'total_price' => 20000000,
                'session_id' => 'SESSION123456',
                'buying_flag' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}