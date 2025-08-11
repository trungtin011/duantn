<?php

namespace App\Services;

use App\Models\AdsCampaign;
use App\Models\ShopWallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdBiddingService
{
    /**
     * Lấy danh sách quảng cáo theo thứ tự đấu giá
     */
    public static function getRankedAds($query = null, $limit = 3)
    {
        try {
            $adsQuery = AdsCampaign::with([
                'shop' => function($q) {
                    $q->withCount('followers')
                      ->withCount('orderReviews')
                      ->withAvg('orderReviews', 'rating');
                },
                'adsCampaignItems.product.images'
            ])
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('bid_amount', '>', 0);

            // Nếu có từ khóa tìm kiếm, lọc theo sản phẩm
            if ($query) {
                $adsQuery->whereHas('adsCampaignItems.product', function($q) use ($query) {
                    $q->where('name', 'like', "%$query%");
                });
            }

            // Sắp xếp theo giá thầu cao nhất trước
            $rankedAds = $adsQuery->orderBy('bid_amount', 'desc')
                                 ->take($limit)
                                 ->get();

            // Cập nhật impressions cho các quảng cáo được hiển thị
            foreach ($rankedAds as $ad) {
                $ad->increment('impressions');
            }

            return $rankedAds;

        } catch (\Exception $e) {
            Log::error('Lỗi lấy danh sách quảng cáo đấu giá: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Xử lý click quảng cáo và trừ tiền theo giá thầu
     */
    public static function processAdClick($campaignId, $shopId, $userId)
    {
        try {
            DB::beginTransaction();

            $campaign = AdsCampaign::find($campaignId);
            if (!$campaign || $campaign->status !== 'active') {
                return ['success' => false, 'message' => 'Chiến dịch quảng cáo không hợp lệ'];
            }

            // Kiểm tra ví shop
            $shopWallet = ShopWallet::where('shop_id', $shopId)->first();
            if (!$shopWallet) {
                return ['success' => false, 'message' => 'Ví shop không tồn tại'];
            }

            if ($shopWallet->balance < $campaign->bid_amount) {
                return ['success' => false, 'message' => 'Số dư ví không đủ để trả phí quảng cáo'];
            }

            // Kiểm tra đã click chưa
            $existingClick = \App\Models\AdClick::where('user_id', $userId)
                ->where('shop_id', $shopId)
                ->where('ads_campaign_id', $campaignId)
                ->lockForUpdate()
                ->first();

            if ($existingClick) {
                DB::rollBack();
                return ['success' => true, 'message' => 'Đã click trước đó', 'charged' => false];
            }

            // Tạo giao dịch click
            $adClick = \App\Models\AdClick::create([
                'user_id' => $userId,
                'shop_id' => $shopId,
                'ads_campaign_id' => $campaignId,
                'click_type' => 'bidding_ad',
                'user_ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'clicked_at' => now(),
                'cost_per_click' => $campaign->bid_amount,
                'is_charged' => false,
            ]);

            // Trừ tiền từ ví shop
            $shopWallet->decrement('balance', $campaign->bid_amount);

            // Tạo giao dịch ví
            $walletTransaction = WalletTransaction::create([
                'shop_wallet_id' => $shopWallet->id,
                'amount' => $campaign->bid_amount,
                'direction' => 'out',
                'type' => 'advertising_bid',
                'description' => "Phí click quảng cáo đấu giá - {$campaign->name}",
                'status' => 'completed',
            ]);

            // Cập nhật ad click
            $adClick->update([
                'is_charged' => true,
                'wallet_transaction_id' => $walletTransaction->id,
            ]);

            // Cập nhật thống kê chiến dịch
            $campaign->increment('clicks');
            $campaign->increment('total_spent', $campaign->bid_amount);

            DB::commit();

            return [
                'success' => true,
                'message' => "Click quảng cáo đã được ghi nhận và trừ {$campaign->bid_amount}đ từ ví shop!",
                'wallet_transaction_id' => $walletTransaction->id,
                'remaining_balance' => $shopWallet->balance,
                'ad_click_id' => $adClick->id,
                'charged' => true,
                'bid_amount' => $campaign->bid_amount
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi xử lý click quảng cáo đấu giá: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Có lỗi xảy ra khi xử lý click quảng cáo: ' . $e->getMessage()];
        }
    }

    /**
     * Cập nhật giá thầu cho chiến dịch
     */
    public static function updateBidAmount($campaignId, $newBidAmount)
    {
        try {
            $campaign = AdsCampaign::find($campaignId);
            if (!$campaign) {
                return ['success' => false, 'message' => 'Chiến dịch không tồn tại'];
            }

            if ($newBidAmount < 1) {
                return ['success' => false, 'message' => 'Giá thầu tối thiểu là 1đ'];
            }

            $campaign->update(['bid_amount' => $newBidAmount]);

            return [
                'success' => true,
                'message' => 'Cập nhật giá thầu thành công',
                'new_bid_amount' => $newBidAmount
            ];

        } catch (\Exception $e) {
            Log::error('Lỗi cập nhật giá thầu: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật giá thầu'];
        }
    }

    /**
     * Lấy thống kê đấu giá
     */
    public static function getBiddingStats($shopId = null)
    {
        $query = AdsCampaign::query();

        if ($shopId) {
            $query->where('shop_id', $shopId);
        }

        return $query->selectRaw('
            COUNT(*) as total_campaigns,
            SUM(bid_amount) as total_bids,
            AVG(bid_amount) as avg_bid,
            MAX(bid_amount) as max_bid,
            MIN(bid_amount) as min_bid,
            SUM(impressions) as total_impressions,
            SUM(clicks) as total_clicks,
            SUM(total_spent) as total_spent
        ')->first();
    }
}
