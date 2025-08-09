<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdsCampaign;
use App\Models\Shop;

class AdsCampaignAdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $shopId = $request->get('shop_id');
        $filterDate = $request->get('filter_date');

        $campaigns = AdsCampaign::with(['shop'])
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($shopId, function ($q) use ($shopId) {
                $q->where('shop_id', $shopId);
            })
            ->when($filterDate, function ($q) use ($filterDate) {
                $q->whereDate('created_at', $filterDate);
            })
            ->orderByDesc('id')
            ->paginate(20);

        $shops = Shop::select('id', 'shop_name')->orderBy('shop_name')->get();

        return view('admin.ads_campaigns.index', compact('campaigns', 'search', 'status', 'shopId', 'filterDate', 'shops'));
    }

    public function approve($id)
    {
        $campaign = AdsCampaign::findOrFail($id);

        // Chỉ cho phép duyệt khi đang pending hoặc cancelled
        if (!in_array($campaign->status, ['pending', 'cancelled'])) {
            return back()->with('error', 'Chỉ có thể duyệt chiến dịch ở trạng thái chờ duyệt hoặc đã hủy.');
        }

        $campaign->status = 'active';
        $campaign->save();

        return back()->with('success', 'Đã duyệt và kích hoạt chiến dịch quảng cáo.');
    }

    public function reject(Request $request, $id)
    {
        $campaign = AdsCampaign::findOrFail($id);

        // Gán trạng thái bị hủy (cancelled) như là từ chối
        $campaign->status = 'cancelled';
        $campaign->save();

        return back()->with('success', 'Đã từ chối chiến dịch quảng cáo.');
    }

    public function ajaxList(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $shopId = $request->get('shop_id');
        $filterDate = $request->get('filter_date');

        $campaigns = AdsCampaign::with(['shop'])
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($shopId, function ($q) use ($shopId) {
                $q->where('shop_id', $shopId);
            })
            ->when($filterDate, function ($q) use ($filterDate) {
                $q->whereDate('created_at', $filterDate);
            })
            ->orderByDesc('id')
            ->paginate(20);

        // Trả về phần thân bảng để thay thế bằng AJAX
        return view('admin.ads_campaigns._table_body', compact('campaigns'));
    }
}


