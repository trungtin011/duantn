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
            // Sản phẩm 1: Thêm từ ví dụ của bạn
            [
                'userID' => 3,
                'productID' => 1, // Smartphone X
                'variantID' => 1, // Black 128GB
                'quantity' => 1,
                'price' => 8500000, // Giá của biến thể
                'total_price' => 8500000,
                'session_id' => 'session123',
                'buying_flag' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Sản phẩm 2: Thêm 1 Laptop Pro (biến thể Silver 512GB SSD)
            [
                'userID' => 3, // Cùng người dùng
                'productID' => 2, // ID của Laptop Pro
                'variantID' => 3, // ID của biến thể 'Silver 512GB SSD'
                'quantity' => 1,
                'price' => 33000000, // Giá bán (sale_price) của biến thể này
                'total_price' => 33000000, // quantity * price
                'session_id' => 'session123', // Cùng session
                'buying_flag' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Sản phẩm 3: Thêm 2 Smartwatch 2 (biến thể Graphite Stainless Steel)
            [
                'userID' => 3, // Cùng người dùng
                'productID' => 4, // ID của Smartwatch 2
                'variantID' => 10, // ID của biến thể 'Graphite Stainless Steel'
                'quantity' => 2,
                'price' => 9500000, // Giá bán (sale_price) của biến thể này
                'total_price' => 19000000, // 2 * 9500000
                'session_id' => 'session123', // Cùng session
                'buying_flag' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}