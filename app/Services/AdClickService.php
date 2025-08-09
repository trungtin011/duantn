<?php

namespace App\Services;

use App\Models\AdClick;
use App\Models\ShopWallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AdClickService
{
    const COST_PER_CLICK = 1000; // 1000 VND per click

    /**
     * Ghi nhận click quảng cáo và trừ phí từ ví shop
     */
    public static function recordClick(Request $request, $shopId, $adsCampaignId, $productId = null, $clickType = 'shop_detail')
    {
        try {
            DB::beginTransaction();

            $userId = Auth::id();

            // Kiểm tra xem shop có đủ tiền không
            $shopWallet = ShopWallet::where('shop_id', $shopId)->first();
            
            if (!$shopWallet) {
                return ['success' => false, 'message' => 'Ví shop không tồn tại'];
            }

            if ($shopWallet->balance < self::COST_PER_CLICK) {
                return ['success' => false, 'message' => 'Số dư ví không đủ để trả phí quảng cáo'];
            }

            // Kiểm tra xem đã click chưa (chỉ cho phép click 1 lần)
            $existingClick = AdClick::where('user_id', $userId)
                ->where('shop_id', $shopId)
                ->where('ads_campaign_id', $adsCampaignId)
                ->lockForUpdate() // Khóa để tránh race condition
                ->first();

            if ($existingClick) {
                DB::rollBack();
                // Đã click rồi - im lặng trả về success nhưng không trừ tiền
                return ['success' => true, 'message' => 'Đã click trước đó', 'charged' => false];
            }

            // Ghi nhận click
            $adClick = AdClick::create([
                'user_id' => $userId,
                'shop_id' => $shopId,
                'ads_campaign_id' => $adsCampaignId,
                'product_id' => $productId,
                'click_type' => $clickType,
                'user_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'clicked_at' => now(),
                'cost_per_click' => self::COST_PER_CLICK,
                'is_charged' => false,
            ]);

            // Trừ tiền từ ví shop
            $shopWallet->decrement('balance', self::COST_PER_CLICK);

            // Tạo giao dịch ví
            $walletTransaction = WalletTransaction::create([
                'shop_wallet_id' => $shopWallet->id,
                'amount' => self::COST_PER_CLICK,
                'direction' => 'out',
                'type' => 'advertising',
                'description' => "Phí click quảng cáo - {$clickType}",
                'status' => 'completed',
            ]);

            // Cập nhật ad click với wallet transaction
            $adClick->update([
                'is_charged' => true,
                'wallet_transaction_id' => $walletTransaction->id,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Click quảng cáo đã được ghi nhận và trừ 1000đ từ ví shop!',
                'wallet_transaction_id' => $walletTransaction->id,
                'remaining_balance' => $shopWallet->balance,
                'ad_click_id' => $adClick->id,
                'charged' => true
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error recording ad click", [
                'shop_id' => $shopId,
                'ads_campaign_id' => $adsCampaignId,
                'error' => $e->getMessage()
            ]);

            return ['success' => false, 'message' => 'Có lỗi xảy ra khi ghi nhận click quảng cáo: ' . $e->getMessage()];
        }
    }

    /**
     * Lấy thống kê click quảng cáo của shop
     */
    public static function getShopAdStats($shopId, $startDate = null, $endDate = null)
    {
        $query = AdClick::where('shop_id', $shopId);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $stats = $query->selectRaw('
            COUNT(*) as total_clicks,
            SUM(cost_per_click) as total_cost,
            COUNT(CASE WHEN is_charged = 1 THEN 1 END) as charged_clicks,
            COUNT(CASE WHEN click_type = "shop_detail" THEN 1 END) as shop_detail_clicks,
            COUNT(CASE WHEN click_type = "product_detail" THEN 1 END) as product_detail_clicks,
            COUNT(CASE WHEN click_type = "modal_view" THEN 1 END) as modal_view_clicks
        ')->first();

        return $stats;
    }

    /**
     * Lấy lịch sử click quảng cáo của shop
     */
    public static function getShopAdClickHistory($shopId, $limit = 20)
    {
        return AdClick::where('shop_id', $shopId)
            ->with(['adsCampaign', 'product', 'walletTransaction'])
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }
}
