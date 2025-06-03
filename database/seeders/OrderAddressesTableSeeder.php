<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderAddressesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('order_addresses')->insert([
            [
                'id' => 1,
                'order_id' => 1,
                'receiver_name' => 'Lê Văn Khách',
                'receiver_phone' => '0934567890',
                'address' => '123 Đường Láng',
                'province' => 'Hà Nội',
                'district' => 'Đống Đa',
                'ward' => 'Láng Thượng',
                'zip_code' => '100000',
                'address_type' => 'home',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}