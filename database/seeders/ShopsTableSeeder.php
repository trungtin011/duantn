<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('shops')->insert([
            [
                'id' => 1,
                'ownerID' => 2,
                'shop_name' => 'Cửa hàng Online 01',
                'shop_phone' => '0923456789',
                'shop_email' => 'shop01@duantn.com',
                'shop_description' => 'Cửa hàng bán hàng thời trang chất lượng cao',
                'shop_rating' => 4.50,
                'total_ratings' => 10,
                'total_products' => 5,
                'total_sales' => 10000000.00,
                'total_followers' => 100,
                'opening_hours' => json_encode(['mon-fri' => '08:00-17:00', 'sat' => '09:00-15:00']),
                'social_media_links' => json_encode(['facebook' => 'fb.com/shop01', 'instagram' => 'insta.com/shop01']),
                'shop_logo' => 'logos/shop01.jpg',
                'shop_banner' => 'banners/shop01.jpg',
                'shop_status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}