<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shop;
use App\Models\ShopCategory;
use Illuminate\Support\Str;

class ShopCategorySeeder extends Seeder
{
    public function run(): void
    {
        $shops = Shop::all();

        foreach ($shops as $shop) {
            // Mỗi shop tạo ngẫu nhiên 3 danh mục
            for ($i = 1; $i <= 3; $i++) {
                ShopCategory::create([
                    'shop_id' => $shop->id,
                    'name' => 'Danh mục ' . $i . ' của ' . $shop->shop_name,
                ]);
            }
        }
    }
}
