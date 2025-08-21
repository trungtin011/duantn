<?php

namespace App\Http\Controllers;

use App\Models\AdClick;
use App\Models\AdsCampaign;
use App\Models\ShopWallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdClickController extends Controller
{
    /**
     * Xử lý khi người dùng click quảng cáo
     */
    public function trackClick(Request $request)
    {
        try {
            $request->validate([
                'campaign_id' => 'required|exists:ads_campaigns,id',
                'product_id' => 'nullable|exists:products,id',
                'click_type' => 'required|in:banner,product,search',
                'shop_id' => 'required|integer|min:0',
            ]);

            $campaignId = $request->campaign_id;
            $shopId = $request->shop_id;
            $userId = Auth::id();
            $userIp = $request->ip();

            // Kiểm tra chiến dịch có active không
            $campaignQuery = AdsCampaign::where('id', $campaignId)
                ->where('status', 'active');
            
            // Nếu là banner quảng cáo (shop_id = 0), không cần kiểm tra shop_id
            if ($shopId > 0) {
                $campaignQuery = $campaignQuery->where('shop_id', $shopId);
            }
            
            $campaign = $campaignQuery->first();

            if (!$campaign) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chiến dịch quảng cáo không tồn tại hoặc đã dừng'
                ], 400);
            }

            // Kiểm tra user đã click quảng cáo này chưa (trong 24h)
            if (AdClick::hasUserClicked($userId, $campaignId, $shopId)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Click đã được ghi nhận trước đó'
                ]);
            }

            // Kiểm tra rate limit cho IP
            if (!AdClick::checkRateLimit($userIp, 10, 1)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Click quá nhanh, vui lòng thử lại sau'
                ], 429);
            }

            // Tạo click tracking
            $click = AdClick::createClick([
                'user_id' => $userId,
                'shop_id' => $shopId,
                'campaign_id' => $campaignId,
                'product_id' => $request->product_id,
                'click_type' => $request->click_type,
                'ip' => $userIp,
                'user_agent' => $request->userAgent(),
            ]);

            // Xử lý trừ phí quảng cáo
            $this->processAdClickPayment($click, $campaign);

            return response()->json([
                'success' => true,
                'message' => 'Click quảng cáo đã được ghi nhận',
                'cost' => $campaign->bid_amount
            ]);

        } catch (\Exception $e) {
            Log::error('Error tracking ad click: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý click quảng cáo'
            ], 500);
        }
    }

    /**
     * Xử lý trừ phí quảng cáo vào ví seller
     */
    private function processAdClickPayment(AdClick $click, AdsCampaign $campaign)
    {
        try {
            DB::transaction(function () use ($click, $campaign) {
                // Lock ví shop để tránh race condition
                $shopWallet = ShopWallet::where('shop_id', $campaign->shop_id)
                    ->lockForUpdate()
                    ->first();

                if (!$shopWallet) {
                    // Tạo ví mới nếu chưa có
                    $shopWallet = ShopWallet::create([
                        'shop_id' => $campaign->shop_id,
                        'balance' => 0
                    ]);
                }

                $bidAmount = $campaign->bid_amount ?? 1.00; // Giá thầu từ chiến dịch

                // Kiểm tra số dư
                if ($shopWallet->balance < $bidAmount) {
                    // Không đủ tiền, đánh dấu click nhưng không trừ tiền
                    $click->update([
                        'cost_per_click' => $bidAmount,
                        'is_charged' => false,
                        'wallet_transaction_id' => null
                    ]);

                    // Tự động tạm dừng chiến dịch cho tới khi số dư >= giá thầu
                    if ($campaign->status === 'active') {
                        $campaign->update(['status' => 'pending']);
                    }

                    Log::warning("Shop {$campaign->shop_id} không đủ tiền để trừ phí quảng cáo. Số dư: {$shopWallet->balance}, Cần: {$bidAmount}");
                    return;
                }

                // Trừ tiền từ ví shop
                $shopWallet->decrement('balance', $bidAmount);

                // Tạo giao dịch ví
                $walletTransaction = WalletTransaction::create([
                    'shop_wallet_id' => $shopWallet->id,
                    'amount' => $bidAmount,
                    'direction' => 'out',
                    'type' => 'advertising',
                    'description' => "Phí click quảng cáo: {$campaign->name} (Giá thầu: {$bidAmount} VNĐ)",
                    'status' => 'completed',
                    'reference_id' => $click->id,
                    'reference_type' => 'ad_click'
                ]);

                // Cập nhật click với thông tin chi phí
                $click->update([
                    'cost_per_click' => $bidAmount,
                    'is_charged' => true,
                    'wallet_transaction_id' => $walletTransaction->id
                ]);

                // Cập nhật thống kê chiến dịch
                $campaign->increment('clicks');
                $campaign->increment('total_spent', $bidAmount);

                Log::info("Đã trừ phí quảng cáo: Shop {$campaign->shop_id}, Chiến dịch {$campaign->name}, Số tiền: {$bidAmount} VNĐ");
            });

        } catch (\Exception $e) {
            Log::error("Lỗi xử lý trừ phí quảng cáo: " . $e->getMessage());
            
            // Đánh dấu click nhưng không trừ tiền nếu có lỗi
            $click->update([
                'cost_per_click' => $campaign->bid_amount ?? 1.00,
                'is_charged' => false,
                'wallet_transaction_id' => null
            ]);
        }
    }

    /**
     * API để lấy thông tin chi phí quảng cáo
     */
    public function getAdCost(Request $request)
    {
        $request->validate([
            'campaign_id' => 'required|exists:ads_campaigns,id',
        ]);

        $campaign = AdsCampaign::find($request->campaign_id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'campaign_name' => $campaign->name,
                'bid_amount' => $campaign->bid_amount,
                'status' => $campaign->status,
                'total_spent' => $campaign->total_spent,
                'clicks' => $campaign->clicks
            ]
        ]);
    }
}
