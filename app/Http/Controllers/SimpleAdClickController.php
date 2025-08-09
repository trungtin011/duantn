<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdClick;
use App\Models\ShopWallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SimpleAdClickController extends Controller
{
    /**
     * Xử lý click quảng cáo và trừ tiền từ ví shop
     */
    public function handleClick(Request $request)
    {
        // Validate input
        $request->validate([
            'ad_click_type' => 'required|string',
            'shop_id' => 'required|integer',
            'campaign_id' => 'required|integer',
            'product_id' => 'nullable|integer'
        ]);

        $shopId = $request->shop_id;
        $campaignId = $request->campaign_id;
        $productId = $request->product_id;
        $clickType = $request->ad_click_type;
        $userId = Auth::id();
        $ip = $request->ip();
        $userAgent = $request->userAgent();

        try {
            DB::beginTransaction();

            // 1. Kiểm tra ví shop
            $shopWallet = ShopWallet::where('shop_id', $shopId)->first();
            if (!$shopWallet) {
                return redirect()->back()->with('ad_click_error', 'Ví shop không tồn tại!');
            }

            // 2. Kiểm tra số dư
            $costPerClick = 1000; // 1000 VND per click
            if ($shopWallet->balance < $costPerClick) {
                return redirect()->back()->with('ad_click_error', 'Số dư ví không đủ để trả phí quảng cáo!');
            }

            // 3. Kiểm tra xem đã click chưa (chỉ cho phép click 1 lần) - Sử dụng FOR UPDATE để tránh race condition
            $existingClick = AdClick::where('user_id', $userId)
                ->where('shop_id', $shopId)
                ->where('ads_campaign_id', $campaignId)
                ->lockForUpdate() // Khóa để tránh race condition
                ->first();

            if ($existingClick) {
                DB::rollBack();
                // Nếu đã click rồi thì im lặng chuyển hướng, không báo lỗi
                if ($clickType === 'shop_detail') {
                    return redirect("/customer/shop/{$shopId}");
                } elseif ($clickType === 'product_detail' && $productId) {
                    $product = \App\Models\Product::find($productId);
                    if ($product) {
                        return redirect(route('product.show', $product->slug));
                    }
                }
                return redirect()->back();
            }

            // 4. Tạo record click
            $adClick = AdClick::create([
                'user_id' => $userId,
                'shop_id' => $shopId,
                'ads_campaign_id' => $campaignId,
                'product_id' => $productId,
                'click_type' => $clickType,
                'user_ip' => $ip,
                'user_agent' => $userAgent,
                'clicked_at' => now(),
                'cost_per_click' => $costPerClick,
                'is_charged' => false
            ]);

            // 5. Trừ tiền từ ví shop
            $shopWallet->decrement('balance', $costPerClick);

            // 6. Tạo giao dịch ví
            $walletTransaction = WalletTransaction::create([
                'shop_wallet_id' => $shopWallet->id,
                'amount' => $costPerClick,
                'direction' => 'out',
                'type' => 'advertising',
                'description' => "Phí click quảng cáo - {$clickType}",
                'status' => 'completed',
            ]);

            // 7. Cập nhật ad click với wallet transaction
            $adClick->update([
                'is_charged' => true,
                'wallet_transaction_id' => $walletTransaction->id,
            ]);

            DB::commit();

            // 8. Chuyển hướng dựa vào loại click (chỉ hiển thị thông báo cho lần click đầu tiên)
            if ($clickType === 'shop_detail') {
                return redirect("/customer/shop/{$shopId}")->with('ad_click_success', 'Click quảng cáo đã được ghi nhận và trừ 1000đ từ ví shop!');
            } elseif ($clickType === 'product_detail' && $productId) {
                $product = \App\Models\Product::find($productId);
                if ($product) {
                    return redirect(route('product.show', $product->slug))->with('ad_click_success', 'Click quảng cáo đã được ghi nhận và trừ 1000đ từ ví shop!');
                }
            }

            return redirect()->back()->with('ad_click_success', 'Click quảng cáo đã được ghi nhận và trừ 1000đ từ ví shop!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Lỗi ad click: ' . $e->getMessage());
            return redirect()->back()->with('ad_click_error', 'Có lỗi xảy ra khi ghi nhận click quảng cáo!');
        }
    }

    /**
     * Test đơn giản - chỉ ghi nhận click và trừ tiền
     */
    public function testClick(Request $request)
    {
        $shopId = $request->get('shop_id', 1);
        $campaignId = $request->get('campaign_id', 1);
        $userId = Auth::id();

        try {
            DB::beginTransaction();

            // Kiểm tra ví shop
            $shopWallet = ShopWallet::where('shop_id', $shopId)->first();
            if (!$shopWallet) {
                return response()->json(['error' => 'Ví shop không tồn tại'], 400);
            }

            // Kiểm tra số dư
            $costPerClick = 1000;
            if ($shopWallet->balance < $costPerClick) {
                return response()->json(['error' => 'Số dư ví không đủ'], 400);
            }

            // Kiểm tra đã click chưa - Sử dụng FOR UPDATE để tránh race condition
            $existingClick = AdClick::where('user_id', $userId)
                ->where('shop_id', $shopId)
                ->where('ads_campaign_id', $campaignId)
                ->lockForUpdate() // Khóa để tránh race condition
                ->first();

            if ($existingClick) {
                DB::rollBack();
                // Đã click rồi thì im lặng trả về success
                return response()->json(['message' => 'Đã click trước đó', 'charged' => false]);
            }

            // Tạo record click
            $adClick = AdClick::create([
                'user_id' => $userId,
                'shop_id' => $shopId,
                'ads_campaign_id' => $campaignId,
                'click_type' => 'test',
                'user_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'clicked_at' => now(),
                'cost_per_click' => $costPerClick,
                'is_charged' => false
            ]);

            // Trừ tiền
            $shopWallet->decrement('balance', $costPerClick);

            // Tạo giao dịch
            $walletTransaction = WalletTransaction::create([
                'shop_wallet_id' => $shopWallet->id,
                'amount' => $costPerClick,
                'direction' => 'out',
                'type' => 'advertising',
                'description' => 'Phí click quảng cáo - test',
                'status' => 'completed',
            ]);

            // Cập nhật
            $adClick->update([
                'is_charged' => true,
                'wallet_transaction_id' => $walletTransaction->id,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Click thành công và trừ 1000đ',
                'remaining_balance' => $shopWallet->balance,
                'charged' => true
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Hiển thị thống kê click quảng cáo
     */
    public function showStats(Request $request)
    {
        $shopId = $request->get('shop_id', 1);
        
        // Lấy thống kê từ database
        $stats = DB::table('ad_clicks')
            ->where('shop_id', $shopId)
            ->selectRaw('
                COUNT(*) as total_clicks,
                SUM(cost_per_click) as total_cost,
                COUNT(CASE WHEN is_charged = 1 THEN 1 END) as charged_clicks,
                COUNT(CASE WHEN click_type = "shop_detail" THEN 1 END) as shop_detail_clicks,
                COUNT(CASE WHEN click_type = "product_detail" THEN 1 END) as product_detail_clicks
            ')
            ->first();

        // Lấy lịch sử gần đây
        $recentClicks = DB::table('ad_clicks')
            ->where('shop_id', $shopId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('ad_click_stats', compact('stats', 'recentClicks', 'shopId'));
    }

    /**
     * API để lấy thống kê
     */
    public function getStatsApi(Request $request)
    {
        $shopId = $request->get('shop_id', 1);
        
        $stats = DB::table('ad_clicks')
            ->where('shop_id', $shopId)
            ->selectRaw('
                COUNT(*) as total_clicks,
                SUM(cost_per_click) as total_cost,
                COUNT(CASE WHEN is_charged = 1 THEN 1 END) as charged_clicks
            ')
            ->first();

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Reset dữ liệu test (chỉ dành cho test)
     */
    public function resetTestData(Request $request)
    {
        $shopId = $request->get('shop_id', 1);
        $userId = Auth::id();

        try {
            // Xóa tất cả click của user này cho shop này
            $deletedClicks = AdClick::where('user_id', $userId)
                ->where('shop_id', $shopId)
                ->delete();

            // Hoàn tiền cho shop (nếu cần)
            $shopWallet = ShopWallet::where('shop_id', $shopId)->first();
            if ($shopWallet) {
                $totalCharged = AdClick::where('shop_id', $shopId)
                    ->where('is_charged', true)
                    ->sum('cost_per_click');
                
                // Cập nhật số dư
                $shopWallet->update(['balance' => $shopWallet->balance + $totalCharged]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã reset dữ liệu test',
                'deleted_clicks' => $deletedClicks
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Debug - xem dữ liệu click hiện tại
     */
    public function debugClicks(Request $request)
    {
        $shopId = $request->get('shop_id', 1);
        $userId = Auth::id();

        $clicks = AdClick::where('user_id', $userId)
            ->where('shop_id', $shopId)
            ->orderBy('created_at', 'desc')
            ->get();

        $shopWallet = ShopWallet::where('shop_id', $shopId)->first();

        return response()->json([
            'user_id' => $userId,
            'shop_id' => $shopId,
            'wallet_balance' => $shopWallet ? $shopWallet->balance : 0,
            'total_clicks' => $clicks->count(),
            'charged_clicks' => $clicks->where('is_charged', true)->count(),
            'clicks' => $clicks->map(function($click) {
                return [
                    'id' => $click->id,
                    'click_type' => $click->click_type,
                    'is_charged' => $click->is_charged,
                    'cost_per_click' => $click->cost_per_click,
                    'created_at' => $click->created_at,
                    'wallet_transaction_id' => $click->wallet_transaction_id
                ];
            })
        ]);
    }
}
