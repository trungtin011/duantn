<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\AdsCampaign;
use App\Services\AdBiddingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdBiddingController extends Controller
{
    /**
     * Hiển thị danh sách chiến dịch quảng cáo của seller
     */
    public function index()
    {
        $seller = Auth::user();
        $shop = $seller->shop;

        if (!$shop) {
            return redirect()->back()->with('error', 'Bạn chưa có shop!');
        }

        $campaigns = AdsCampaign::where('shop_id', $shop->id)
            ->with(['adsCampaignItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = AdBiddingService::getBiddingStats($shop->id);

        return view('seller.ad_bidding.index', compact('campaigns', 'stats'));
    }

    /**
     * Hiển thị form chỉnh sửa giá thầu
     */
    public function edit($id)
    {
        $seller = Auth::user();
        $shop = $seller->shop;

        $campaign = AdsCampaign::where('shop_id', $shop->id)
            ->where('id', $id)
            ->with(['adsCampaignItems.product'])
            ->firstOrFail();

        return view('seller.ad_bidding.edit', compact('campaign'));
    }

    /**
     * Cập nhật giá thầu
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'bid_amount' => 'required|numeric|min:1|max:100000'
        ]);

        $seller = Auth::user();
        $shop = $seller->shop;

        $campaign = AdsCampaign::where('shop_id', $shop->id)
            ->where('id', $id)
            ->firstOrFail();

        $result = AdBiddingService::updateBidAmount($id, $request->bid_amount);

        if ($result['success']) {
            return redirect()->route('seller.ad_bidding.index')
                ->with('success', 'Cập nhật giá thầu thành công!');
        } else {
            return redirect()->back()
                ->with('error', $result['message'])
                ->withInput();
        }
    }

    /**
     * Hiển thị thống kê chi tiết
     */
    public function stats()
    {
        $seller = Auth::user();
        $shop = $seller->shop;

        $stats = AdBiddingService::getBiddingStats($shop->id);
        
        $topCampaigns = AdsCampaign::where('shop_id', $shop->id)
            ->orderBy('bid_amount', 'desc')
            ->take(5)
            ->get();

        return view('seller.ad_bidding.stats', compact('stats', 'topCampaigns'));
    }

    /**
     * API cập nhật giá thầu
     */
    public function updateBid(Request $request)
    {
        $request->validate([
            'campaign_id' => 'required|integer',
            'bid_amount' => 'required|numeric|min:1|max:100000'
        ]);

        $seller = Auth::user();
        $shop = $seller->shop;

        // Kiểm tra campaign thuộc về shop
        $campaign = AdsCampaign::where('shop_id', $shop->id)
            ->where('id', $request->campaign_id)
            ->first();

        if (!$campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Chiến dịch không tồn tại hoặc không thuộc về shop của bạn'
            ]);
        }

        $result = AdBiddingService::updateBidAmount($request->campaign_id, $request->bid_amount);

        return response()->json($result);
    }
}
