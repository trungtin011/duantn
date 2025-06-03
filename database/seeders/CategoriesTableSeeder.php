<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            [
                'id' => 1,
                'name' => 'Thời trang',
                'slug' => 'thoi-trang',
                'description' => 'Danh mục thời trang nam và nữ',
                'image_path' => 'categories/thoi-trang.jpg',
                'meta_title' => 'Thời trang',
                'meta_description' => 'Danh mục thời trang chất lượng cao',
                'meta_keywords' => 'thời trang, quần áo, phụ kiện',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Điện tử',
                'slug' => 'dien-tu',
                'description' => 'Danh mục sản phẩm điện tử',
                'image_path' => 'categories/dien-tu.jpg',
                'meta_title' => 'Điện tử',
                'meta_description' => 'Sản phẩm điện tử hiện đại',
                'meta_keywords' => 'điện tử, điện thoại, laptop',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}