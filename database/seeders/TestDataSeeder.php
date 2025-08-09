<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdsCampaign;
use App\Models\ShopWallet;
use App\Models\Shop;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo shop test nếu chưa có
        $shop = Shop::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Test Shop',
                'description' => 'Shop để test',
                'address' => 'Test Address',
                'phone' => '0123456789',
                'email' => 'test@shop.com',
                'status' => 'active'
            ]
        );

        // Tạo ví shop test
        ShopWallet::firstOrCreate(
            ['shop_id' => 1],
            [
                'shop_id' => 1,
                'balance' => 10000 // 10,000 VND để test
            ]
        );

        // Tạo ads campaign test
        AdsCampaign::firstOrCreate(
            ['id' => 1],
            [
                'id' => 1,
                'shop_id' => 1,
                'name' => 'Test Campaign',
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'status' => 'active'
            ]
        );

        $this->command->info('Test data created successfully!');
        $this->command->info('- Shop ID: 1');
        $this->command->info('- Campaign ID: 1');
        $this->command->info('- Wallet Balance: 10,000 VND');
    }
}
