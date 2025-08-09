<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ShopWallet;
use App\Models\AdClick;
use App\Models\WalletTransaction;
use App\Services\AdClickService;
use Illuminate\Http\Request;

class TestAdClickSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:ad-click {shop_id=1} {campaign_id=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test hệ thống ad click và trừ tiền từ ví shop';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $shopId = $this->argument('shop_id');
        $campaignId = $this->argument('campaign_id');

        $this->info("Testing Ad Click System for Shop ID: {$shopId}, Campaign ID: {$campaignId}");

        // Kiểm tra ví shop
        $shopWallet = ShopWallet::where('shop_id', $shopId)->first();
        
        if (!$shopWallet) {
            $this->error("Shop wallet không tồn tại cho shop ID: {$shopId}");
            return 1;
        }

        $this->info("Số dư ví hiện tại: " . number_format($shopWallet->balance) . " VND");

        // Tạo request giả lập
        $request = new Request();
        $request->merge([
            'ad_click_type' => 'shop_detail',
            'shop_id' => $shopId,
            'campaign_id' => $campaignId,
            'product_id' => null
        ]);

        // Test ghi nhận click
        $this->info("Đang test ghi nhận click...");
        
        try {
            $result = AdClickService::recordClick(
                $request,
                $shopId,
                $campaignId,
                null,
                'shop_detail'
            );

            if ($result['success']) {
                $this->info("✅ Click đã được ghi nhận thành công!");
                $this->info("Số dư ví sau khi trừ: " . number_format($result['remaining_balance']) . " VND");
                $this->info("Wallet Transaction ID: " . $result['wallet_transaction_id']);
                $this->info("Ad Click ID: " . $result['ad_click_id']);
            } else {
                $this->error("❌ Lỗi: " . $result['message']);
                return 1;
            }

        } catch (\Exception $e) {
            $this->error("❌ Exception: " . $e->getMessage());
            return 1;
        }

        // Hiển thị thống kê
        $this->info("\n📊 Thống kê click quảng cáo:");
        $stats = AdClickService::getShopAdStats($shopId);
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Tổng số click', $stats->total_clicks ?? 0],
                ['Tổng chi phí', number_format($stats->total_cost ?? 0) . ' VND'],
                ['Click đã tính phí', $stats->charged_clicks ?? 0],
                ['Click shop detail', $stats->shop_detail_clicks ?? 0],
                ['Click product detail', $stats->product_detail_clicks ?? 0],
            ]
        );

        // Hiển thị lịch sử gần đây
        $this->info("\n📋 Lịch sử click gần đây:");
        $history = AdClickService::getShopAdClickHistory($shopId, 5);
        
        if ($history->count() > 0) {
            $rows = [];
            foreach ($history as $click) {
                $rows[] = [
                    $click->id,
                    $click->click_type,
                    $click->cost_per_click . ' VND',
                    $click->is_charged ? 'Đã tính' : 'Chưa tính',
                    $click->created_at->format('Y-m-d H:i:s')
                ];
            }
            
            $this->table(
                ['ID', 'Type', 'Cost', 'Charged', 'Created At'],
                $rows
            );
        } else {
            $this->info("Chưa có lịch sử click nào.");
        }

        $this->info("\n✅ Test hoàn thành!");
        return 0;
    }
}
