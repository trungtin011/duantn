<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessLicensesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('business_licenses')->insert([
            [
                'id' => 1,
                'business_license_number' => 'BL123456789',
                'tax_number' => 'TAX987654321',
                'business_ID' => 'BID123456',
                'business_name' => 'Công ty TNHH Bán Hàng Online',
                'business_license_date' => '2023-01-01',
                'expiry_date' => '2028-01-01',
                'status' => 'approved',
                'license_file_path' => 'licenses/bl123456789.pdf',
                'is_active' => 1,
                'verified_by' => 1,
                'verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}