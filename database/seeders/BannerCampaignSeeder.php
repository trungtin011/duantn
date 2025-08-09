<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdsCampaign;

class BannerCampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo campaign cho banner quảng cáo (shop_id = 0)
        $bannerCampaign = AdsCampaign::firstOrCreate(
            ['shop_id' => 0, 'name' => 'Banner Advertisement Campaign'],
            [
                'start_date' => now(),
                'end_date' => now()->addYear(),
                'status' => 'active',
                'bid_amount' => 0.50, // 0.50 VNĐ cho banner
                'impressions' => 0,
                'clicks' => 0,
                'total_spent' => 0.00,
            ]
        );

        $this->command->info("Banner campaign created/updated: ID {$bannerCampaign->id}");
    }
}
