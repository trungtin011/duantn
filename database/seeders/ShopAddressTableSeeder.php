<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShopAddress;

class ShopAddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShopAddress::create([
            'shopID' => 1,
            'shop_address' => '13 Lý Thái Tổ',
            'shop_province' => 'Thành phố Hồ Chí Minh',
            'shop_district' => 'Quận 1',
            'shop_ward' => 'Cầu Kho',
            'is_default' => true,
        ]);
    }
}
