<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\AdClick;
use App\Models\AdsCampaign;
use App\Models\Shop;
use App\Models\ShopWallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdClickStatsController extends Controller
{
    /**
     * Hiển thị trang thống kê click quảng cáo
     */
    public function index()
    {
        $shopId = $this->getCurrentSellerShopId();
        if (!$shopId) {
            return view('seller.ad_click_stats', [
                'campaigns' => collect(),
                'totalClicks' => 0,
                'totalCampaigns' => 0,
                'activeCampaigns' => 0,
                'dailyStats' => collect(),
                'campaignStats' => collect(),
                'productStats' => collect(),
                'hourlyStats' => collect(),
                'clicks' => collect(),
                'period' => request('period', 7),
            ]);
        }
        $period = request('period', 7);
        $startDate = now()->subDays((int)$period);

        // Lấy danh sách chiến dịch quảng cáo của shop
        $campaigns = AdsCampaign::where('shop_id', $shopId)
            ->withCount(['adClicks'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Thống kê tổng quan (tổng lượt click là số user duy nhất trong period)
        $totalClicks = (int) AdClick::where('ad_clicks.shop_id', $shopId)
            ->whereRaw('COALESCE(clicked_at, created_at) >= ?', [$startDate])
            ->selectRaw("COUNT(DISTINCT IFNULL(CAST(user_id AS CHAR), CONCAT('ip:', user_ip))) as cnt")
            ->value('cnt');
        $totalCampaigns = $campaigns->count();
        $activeCampaigns = $campaigns->where('status', 'active')->count();

        // Thống kê click theo ngày (theo period)
        $dailyStats = AdClick::where('ad_clicks.shop_id', $shopId)
            ->whereRaw('COALESCE(clicked_at, created_at) >= ?', [$startDate])
            ->selectRaw('DATE(COALESCE(clicked_at, created_at)) as date, COUNT(*) as clicks')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Thống kê click theo chiến dịch
        // Thống kê theo chiến dịch (dựa vào bảng ad_clicks)
        $campaignStats = AdClick::where('ad_clicks.shop_id', $shopId)
            ->whereRaw('COALESCE(clicked_at, created_at) >= ?', [$startDate])
            ->selectRaw("ads_campaign_id as campaign_id, COUNT(DISTINCT IFNULL(CAST(user_id AS CHAR), CONCAT('ip:', user_ip))) as clicks")
            ->groupBy('ads_campaign_id')
            ->orderByDesc('clicks')
            ->get();

        // Thống kê click theo sản phẩm
        // Thống kê theo sản phẩm (dựa vào bảng ad_clicks)
        $productStats = AdClick::where('ad_clicks.shop_id', $shopId)
            ->whereRaw('COALESCE(clicked_at, created_at) >= ?', [$startDate])
            ->whereNotNull('product_id')
            ->selectRaw("product_id, COUNT(DISTINCT IFNULL(CAST(user_id AS CHAR), CONCAT('ip:', user_ip))) as clicks")
            ->groupBy('product_id')
            ->orderByDesc('clicks')
            ->limit(10)
            ->get();

        // Thống kê click theo giờ trong 24h gần nhất
        $hourlyStats = AdClick::where('ad_clicks.shop_id', $shopId)
            ->whereRaw('COALESCE(clicked_at, created_at) >= ?', [now()->subDay()])
            ->selectRaw('HOUR(COALESCE(clicked_at, created_at)) as hour, COUNT(*) as clicks')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Dữ liệu bảng chi tiết + phân trang (mỗi user chỉ hiển thị 1 bản ghi - bản ghi mới nhất)
        $identityExpr = DB::raw("IFNULL(CAST(user_id AS CHAR), CONCAT('ip:', user_ip))");
        $subLatestIds = DB::table('ad_clicks')
            ->where('shop_id', $shopId)
            ->when($startDate, function($q) use ($startDate) {
                $q->whereRaw('COALESCE(clicked_at, created_at) >= ?', [$startDate]);
            })
            ->selectRaw('MAX(id) as id')
            ->groupBy($identityExpr);

        $clicks = AdClick::from('ad_clicks as ac')
            ->joinSub($subLatestIds, 'g', 'g.id', '=', 'ac.id')
            ->with(['adsCampaign'])
            ->orderByDesc(DB::raw('COALESCE(ac.clicked_at, ac.created_at)'))
            ->paginate(15, ['ac.*']);

        return view('seller.ad_click_stats', compact(
            'campaigns',
            'totalClicks',
            'totalCampaigns',
            'activeCampaigns',
            'dailyStats',
            'campaignStats',
            'productStats',
            'hourlyStats',
            'clicks',
            'period'
        ));
    }

    /**
     * Lấy dữ liệu thống kê theo AJAX
     */
    // Không dùng AJAX ở môi trường PHP thuần

    /**
     * Export dữ liệu thống kê
     */
    public function export(Request $request)
    {
        $shopId = $this->getCurrentSellerShopId();
        $campaignId = $request->get('campaign_id');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $period = (int) $request->get('period');

        $query = AdClick::where('shop_id', $shopId)
            ->with(['adsCampaign', 'product', 'user']);

        if ($campaignId) {
            $query->where('ads_campaign_id', $campaignId);
        }

        if ($startDate && $endDate) {
            $query->whereRaw('COALESCE(clicked_at, created_at) BETWEEN ? AND ?', [$startDate, $endDate]);
        } elseif ($period) {
            $query->whereRaw('COALESCE(clicked_at, created_at) >= ?', [now()->subDays($period)]);
        }

        $clicks = $query->orderBy('clicked_at', 'desc')->get();

        // Tạo CSV content
        $csvContent = "Ngày click,Chiến dịch,Sản phẩm,Loại click,IP,User Agent\n";
        
        foreach ($clicks as $click) {
            $csvContent .= sprintf(
                "%s,%s,%s,%s,%s,%s\n",
                $click->clicked_at->format('Y-m-d H:i:s'),
                $click->adsCampaign->name ?? 'N/A',
                $click->product->name ?? 'N/A',
                $click->click_type,
                $click->user_ip,
                $click->user_agent
            );
        }

        $filename = "ad_clicks_stats_" . date('Y-m-d_H-i-s') . ".csv";

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }

    /**
     * Trừ phí quảng cáo 1.000đ cho mỗi user unique trong period đã chọn
     */
    public function settle(Request $request)
    {
        $shopId = $this->getCurrentSellerShopId();
        if (!$shopId) {
            return redirect()->back()->with('error', 'Không xác định được Shop');
        }

        $period = (int) $request->get('period', 7);
        $startDate = now()->subDays($period);

        $identityExpr = DB::raw("IFNULL(CAST(user_id AS CHAR), CONCAT('ip:', user_ip))");

        $charged = 0;
        $skipped = 0;

        try {
            DB::transaction(function () use ($shopId, $startDate, $identityExpr, &$charged, &$skipped) {
                // Lock ví trong transaction
                $shopWallet = ShopWallet::where('shop_id', $shopId)->lockForUpdate()->first();
                if (!$shopWallet) {
                    $shopWallet = ShopWallet::create(['shop_id' => $shopId, 'balance' => 0]);
                    // Không có số dư thì sẽ skip ở vòng lặp
                }

                // Xác định các click cần charge (unique theo user/campaign trong period)
                $subToCharge = DB::table('ad_clicks')
                    ->where('shop_id', $shopId)
                    ->where('is_charged', false)
                    ->whereRaw('COALESCE(clicked_at, created_at) >= ?', [$startDate])
                    ->selectRaw('MIN(id) as id')
                    ->groupBy('ads_campaign_id', $identityExpr);

                $clicksToCharge = AdClick::from('ad_clicks as ac')
                    ->joinSub($subToCharge, 'g', 'g.id', '=', 'ac.id')
                    ->orderBy('ac.id')
                    ->get(['ac.*']);

                foreach ($clicksToCharge as $click) {
                    if ($shopWallet->balance < 1000) {
                        $skipped++;
                        continue;
                    }

                    // Trừ tiền
                    $shopWallet->decrement('balance', 1000);

                    // Giao dịch ví
                    $tx = WalletTransaction::create([
                        'shop_wallet_id' => $shopWallet->id,
                        'amount' => 1000,
                        'direction' => 'out',
                        'type' => 'advertising',
                        'description' => 'Phí click quảng cáo (settlement)',
                        'status' => 'completed',
                    ]);

                    // Đánh dấu đã charge ở click đầu tiên của user/campaign
                    AdClick::where('id', $click->id)->update([
                        'is_charged' => true,
                        'cost_per_click' => 1000,
                        'wallet_transaction_id' => $tx->id,
                    ]);

                    $charged++;
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi settle phí quảng cáo: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', "Đã trừ phí {$charged} click. Hết tiền/skip: {$skipped}.");
    }

    private function getCurrentSellerShopId(): ?int
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }
        if (session()->has('current_shop_id')) {
            return (int) session('current_shop_id');
        }
        $shop = Shop::where('ownerID', $user->id)->first();
        return $shop?->id;
    }
}
