<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CartTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('cart')->insert([
            // Sản phẩm 1: Chỉ có 1 biến thể
            [
                'userID'      => 3,
                'productID'   => 1, // ID sản phẩm 1
                'variantID'   => 1, // Biến thể duy nhất của sản phẩm 1
                'quantity'    => 1,
                'price'       => 8500000,
                'total_price' => 8500000,
                'session_id'  => 'session123',
                'buying_flag' => false,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],

            // Sản phẩm 2: Có 2 biến thể
            [
                'userID'      => 3,
                'productID'   => 2, // ID sản phẩm 2
                'variantID'   => 2, // Biến thể thứ nhất của sản phẩm 2
                'quantity'    => 1,
                'price'       => 30000000,
                'total_price' => 30000000,
                'session_id'  => 'session123',
                'buying_flag' => false,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'userID'      => 3,
                'productID'   => 2, // ID sản phẩm 2
                'variantID'   => 3, // Biến thể thứ hai của sản phẩm 2
                'quantity'    => 2,
                'price'       => 33000000,
                'total_price' => 66000000, // 2 * 33000000
                'session_id'  => 'session123',
                'buying_flag' => false,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
        ]);
    }
}
