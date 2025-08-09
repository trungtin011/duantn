<?php

namespace App\Http\Controllers;

use App\Models\AdClick;
use App\Services\AdClickService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdClickController extends Controller
{
    /**
     * Track ad click và chuyển hướng
     */
    public function track(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'ad_click_type' => 'required|string',
                'shop_id' => 'required|integer',
                'campaign_id' => 'required|integer',
                'product_id' => 'nullable|integer'
            ]);

            $userId = Auth::id();
            $ip = $request->ip();
            
            // Kiểm tra rate limit
            if (!AdClick::checkRateLimit($ip, 5, 1)) {
                session()->flash('ad_click_error', 'Quá nhiều yêu cầu! Vui lòng thử lại sau.');
                return redirect()->back();
            }

            // Kiểm tra xem user đã click quảng cáo này chưa (chỉ kiểm tra, không báo lỗi)
            if (AdClick::hasUserClicked($userId, $request->campaign_id, $request->shop_id)) {
                // Đã click rồi - im lặng chuyển hướng, không báo lỗi
                if ($request->ad_click_type === 'shop_detail') {
                    return redirect("/customer/shop/{$request->shop_id}");
                } elseif ($request->ad_click_type === 'product_detail' && $request->product_id) {
                    $product = \App\Models\Product::find($request->product_id);
                    if ($product) {
                        return redirect(route('product.show', $product->slug));
                    }
                }
                return redirect()->back();
            }

            // Sử dụng AdBiddingService cho quảng cáo đấu giá
            $result = \App\Services\AdBiddingService::processAdClick(
                $request->campaign_id,
                $request->shop_id,
                $userId
            );

            if ($result['success']) {
                if (isset($result['charged']) && $result['charged'] === true) {
                    // Hiển thị thông báo thành công với giá thầu
                    $bidAmount = $result['bid_amount'] ?? 1000;
                    session()->flash('ad_click_success', "Click quảng cáo đã được ghi nhận và trừ {$bidAmount}đ từ ví shop!");
                }
                // Nếu charged là false (đã click trước đó), không làm gì cả (im lặng chuyển hướng)
            } else {
                session()->flash('ad_click_error', $result['message']);
            }

            // Chuyển hướng dựa vào loại click
            if ($request->ad_click_type === 'shop_detail') {
                return redirect("/customer/shop/{$request->shop_id}");
            } elseif ($request->ad_click_type === 'product_detail' && $request->product_id) {
                // Lấy product slug để redirect
                $product = \App\Models\Product::find($request->product_id);
                if ($product) {
                    return redirect(route('product.show', $product->slug));
                }
            }

            // Fallback redirect
            return redirect()->back();

        } catch (\Exception $e) {
            Log::error('Lỗi tracking ad click: ' . $e->getMessage());
            session()->flash('ad_click_error', 'Có lỗi xảy ra khi ghi nhận click quảng cáo!');
            return redirect()->back();
        }
    }

    /**
     * Kiểm tra trạng thái click của user
     */
    public function checkStatus(Request $request)
    {
        $request->validate([
            'campaign_id' => 'required|integer',
            'shop_id' => 'required|integer'
        ]);

        $userId = Auth::id() ?? session()->getId();
        $campaignId = $request->campaign_id;
        $shopId = $request->shop_id;

        $hasClicked = AdClick::where('user_id', $userId)
            ->where('ads_campaign_id', $campaignId)
            ->where('shop_id', $shopId)
            ->exists();

        return response()->json([
            'has_clicked' => $hasClicked,
            'message' => $hasClicked ? 'Đã xem quảng cáo' : 'Chưa xem quảng cáo'
        ]);
    }

    /**
     * Reset click status (chỉ dành cho admin)
     */
    public function resetStatus(Request $request)
    {
        // Chỉ admin mới được reset
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Không có quyền'], 403);
        }

        $request->validate([
            'campaign_id' => 'required|integer',
            'shop_id' => 'required|integer',
            'user_id' => 'nullable|integer'
        ]);

        $campaignId = $request->campaign_id;
        $shopId = $request->shop_id;
        $userId = $request->user_id;

        if ($userId) {
            // Reset cho user cụ thể
            AdClick::where('user_id', $userId)
                   ->where('ads_campaign_id', $campaignId)
                   ->where('shop_id', $shopId)
                   ->delete();
        } else {
            // Reset tất cả
            AdClick::where('ads_campaign_id', $campaignId)
                   ->where('shop_id', $shopId)
                   ->delete();
        }

        return response()->json(['success' => 'Đã reset trạng thái click']);
    }

    /**
     * Lấy thống kê click quảng cáo của shop (cho seller)
     */
    public function getShopStats(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date'
        ]);

        $shopId = $request->shop_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $stats = AdClickService::getShopAdStats($shopId, $startDate, $endDate);

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Lấy lịch sử click quảng cáo của shop (cho seller)
     */
    public function getShopHistory(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|integer',
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        $shopId = $request->shop_id;
        $limit = $request->limit ?? 20;

        $history = AdClickService::getShopAdClickHistory($shopId, $limit);

        return response()->json([
            'success' => true,
            'history' => $history
        ]);
    }
}
