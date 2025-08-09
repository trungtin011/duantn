<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdsCampaign;
use Carbon\Carbon;

class AdBiddingSeeder extends Seeder
{
    public function run(): void
    {
        // Cập nhật các chiến dịch hiện có với giá thầu
        $campaigns = AdsCampaign::all();
        
        foreach ($campaigns as $index => $campaign) {
            // Giá thầu từ 1đ đến 5000đ
            $bidAmount = rand(1, 5000);
            
            $campaign->update([
                'bid_amount' => $bidAmount,
                'impressions' => rand(0, 1000),
                'clicks' => rand(0, 100),
                'total_spent' => $campaign->clicks * $bidAmount
            ]);
        }

        $this->command->info('✅ Đã cập nhật giá thầu cho ' . $campaigns->count() . ' chiến dịch quảng cáo!');
        $this->command->info('💰 Giá thầu từ 1đ đến 5000đ');
        $this->command->info('📊 Đã thêm dữ liệu impressions, clicks, total_spent');
    }
}
