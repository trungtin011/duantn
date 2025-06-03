<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('brand')->insert([
            [
                'id' => 1,
                'name' => 'Nike',
                'slug' => 'nike',
                'description' => 'Thương hiệu thời trang thể thao hàng đầu',
                'image_path' => 'brands/nike.jpg',
                'meta_title' => 'Nike',
                'meta_description' => 'Sản phẩm thời trang thể thao Nike',
                'meta_keywords' => 'nike, thể thao, giày dép',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Samsung',
                'slug' => 'samsung',
                'description' => 'Thương hiệu điện tử nổi tiếng',
                'image_path' => 'brands/samsung.jpg',
                'meta_title' => 'Samsung',
                'meta_description' => 'Sản phẩm điện tử Samsung',
                'meta_keywords' => 'samsung, điện thoại, tivi',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}