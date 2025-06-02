<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SellersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('sellers')->insert([
            [
                'id' => 1,
                'userID' => 2,
                'status' => 'active',
                'identity_card' => 123456789012,
                'identity_card_date' => '2010-05-15',
                'identity_card_place' => 'Hà Nội',
                'bank_account' => '1234567890',
                'bank_name' => 'Vietcombank',
                'bank_account_name' => 'Trần Thị Bán Hàng',
                'business_license_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
