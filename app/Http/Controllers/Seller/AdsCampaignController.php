<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdsCampaign;
use App\Models\AdsCampaignItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Services\CampaignBudgetEnforcer;

class AdsCampaignController extends Controller
{
    public function index()
    {
        $shopId = Auth::user()->shop->id;
        $campaigns = AdsCampaign::where('shop_id', $shopId)->latest()->get();
        return view('seller.ads_campaign.index', compact('campaigns'));
    }

    public function create()
    {
        $walletBalance = optional(Auth::user()->shop->wallet)->balance ?? 0;
        return view('seller.ads_campaign.create', compact('walletBalance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'bid_amount' => 'required|numeric|min:0',
        ]);

        $walletBalance = optional(Auth::user()->shop->wallet)->balance ?? 0;
        if ($request->bid_amount > $walletBalance) {
            return back()
                ->withErrors(['bid_amount' => 'Giá thầu không được lớn hơn số dư ví hiện tại (' . number_format($walletBalance, 0, ',', '.') . ' VND).'])
                ->withInput();
        }

        $campaign = AdsCampaign::create([
            'shop_id' => Auth::user()->shop->id,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'bid_amount' => $request->bid_amount,
            'status' => 'pending', // Default status
        ]);

        return redirect()->route('seller.ads_campaigns.add_products', $campaign->id)
            ->with('success', 'Chiến dịch quảng cáo đã được tạo. Vui lòng thêm sản phẩm.');
    }

    public function addProducts($campaign_id)
    {
        $shopId = Auth::user()->shop->id;
        // Debug: Kiểm tra giá trị shopId của người dùng hiện tại
        // dd('Shop ID của người dùng hiện tại:', $shopId);

        $campaign = AdsCampaign::where('shop_id', $shopId)->findOrFail($campaign_id);
        $products = Product::where('shopID', $shopId)->get();
        // Debug: Kiểm tra các sản phẩm được lấy ra
        // dd('Sản phẩm của shop:', $products->pluck('shopID', 'id'));

        $selectedProductIds = $campaign->adsCampaignItems->pluck('product_id')->toArray();

        return view('seller.ads_campaign.add_products', compact('campaign', 'products', 'selectedProductIds'));
    }

    public function storeProducts(Request $request, $campaign_id)
    {
        $shopId = Auth::user()->shop->id;
        $campaign = AdsCampaign::where('shop_id', $shopId)->findOrFail($campaign_id);

        $request->validate([
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $campaign->adsCampaignItems()->delete(); // Remove existing products

        if ($request->has('product_ids')) {
            foreach ($request->product_ids as $productId) {
                AdsCampaignItem::create([
                    'ads_campaign_id' => $campaign->id,
                    'product_id' => $productId,
                ]);
            }
        }

        return redirect()->route('seller.ads_campaigns.index')
            ->with('success', 'Sản phẩm đã được thêm vào chiến dịch thành công.');
    }

    public function edit($id)
    {
        $shopId = Auth::user()->shop->id;
        $campaign = AdsCampaign::where('shop_id', $shopId)->findOrFail($id);
        $walletBalance = optional(Auth::user()->shop->wallet)->balance ?? 0;
        return view('seller.ads_campaign.edit', compact('campaign', 'walletBalance'));
    }

    public function update(Request $request, $id)
    {
        $shopId = Auth::user()->shop->id;
        $campaign = AdsCampaign::where('shop_id', $shopId)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'bid_amount' => 'required|numeric|min:0',
        ]);

        $walletBalance = optional(Auth::user()->shop->wallet)->balance ?? 0;
        if ($request->bid_amount > $walletBalance) {
            return back()
                ->withErrors(['bid_amount' => 'Giá thầu không được lớn hơn số dư ví hiện tại (' . number_format($walletBalance, 0, ',', '.') . ' VND).'])
                ->withInput();
        }

        $campaign->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'bid_amount' => $request->bid_amount,
        ]);

        // Áp dụng quy tắc dừng/kích hoạt theo số dư ví
        CampaignBudgetEnforcer::enforceForCampaign($campaign);

        return redirect()->route('seller.ads_campaigns.index')
            ->with('success', 'Chiến dịch quảng cáo đã được cập nhật.');
    }

    public function destroy($id)
    {
        $shopId = Auth::user()->shop->id;
        $campaign = AdsCampaign::where('shop_id', $shopId)->findOrFail($id);
        $campaign->delete();

        return redirect()->route('seller.ads_campaigns.index')
            ->with('success', 'Chiến dịch quảng cáo đã bị xóa.');
    }

    public function toggleStatus($id)
    {
        $shopId = Auth::user()->shop->id;
        $campaign = AdsCampaign::where('shop_id', $shopId)->findOrFail($id);

        // Seller chỉ được quyền tắt chiến dịch đang active (đưa về pending),
        // và KHÔNG được tự bật active nếu đang pending/cancelled.
        if ($campaign->status === 'active') {
            $campaign->update(['status' => 'pending']);
            return back()->with('success', 'Đã tắt chiến dịch.');
        }

        return back()->with('error', 'Chiến dịch đang chờ duyệt hoặc đã bị từ chối. Vui lòng đợi admin duyệt để kích hoạt.');
    }
}
