<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserAddressesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('user_addresses')->insert([
            [
                'id' => 1,
                'userID' => 3,
                'receiver_name' => 'Lê Văn Khách',
                'receiver_phone' => '0934567890',
                'address' => '123 Đường Láng',
                'province' => 'Hà Nội',
                'district' => 'Đống Đa',
                'ward' => 'Láng Thượng',
                'zip_code' => '100000',
                'address_type' => 'home',
                'is_default' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}