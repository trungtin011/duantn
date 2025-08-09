<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductView;
use App\Services\ProductViewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductViewStatsController extends Controller
{
    protected $productViewService;

    public function __construct(ProductViewService $productViewService)
    {
        $this->productViewService = $productViewService;
    }

    /**
     * Hiển thị trang thống kê lượt xem sản phẩm
     */
    public function index(Request $request)
    {
        $timeRange = $request->get('time_range', 'all');
        $sortBy = $request->get('sort_by', 'view_count');
        $sortOrder = $request->get('sort_order', 'desc');
        $search = $request->get('search', '');

        // Lấy danh sách sản phẩm với số lượt xem
        $products = Product::with(['shop', 'images'])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->get()
            ->map(function ($product) use ($timeRange) {
                $product->view_count = $this->productViewService->getViewCountByTimeRange($product, $timeRange);
                $product->today_views = $this->productViewService->getViewCountByTimeRange($product, 'today');
                $product->week_views = $this->productViewService->getViewCountByTimeRange($product, 'week');
                $product->month_views = $this->productViewService->getViewCountByTimeRange($product, 'month');
                return $product;
            })
            ->when($sortBy === 'view_count', function ($collection) use ($sortOrder) {
                return $sortOrder === 'desc' 
                    ? $collection->sortByDesc('view_count')
                    : $collection->sortBy('view_count');
            })
            ->when($sortBy === 'name', function ($collection) use ($sortOrder) {
                return $sortOrder === 'desc' 
                    ? $collection->sortByDesc('name')
                    : $collection->sortBy('name');
            })
            ->when($sortBy === 'today_views', function ($collection) use ($sortOrder) {
                return $sortOrder === 'desc' 
                    ? $collection->sortByDesc('today_views')
                    : $collection->sortBy('today_views');
            })
            ->paginate(20);

        // Thống kê tổng quan
        $totalViews = ProductView::count();
        $todayViews = ProductView::today()->count();
        $weekViews = ProductView::thisWeek()->count();
        $monthViews = ProductView::thisMonth()->count();
        $totalProducts = Product::count();
        $productsWithViews = Product::whereHas('productViews')->count();

        // Top sản phẩm được xem nhiều nhất
        $topViewedProducts = $this->productViewService->getMostViewedProducts(10, $timeRange);

        // Thống kê theo ngày (7 ngày gần nhất)
        $dailyStats = $this->getDailyStats(7);

        return view('admin.product_view_stats.index', compact(
            'products',
            'timeRange',
            'sortBy',
            'sortOrder',
            'search',
            'totalViews',
            'todayViews',
            'weekViews',
            'monthViews',
            'totalProducts',
            'productsWithViews',
            'topViewedProducts',
            'dailyStats'
        ));
    }

    /**
     * Hiển thị chi tiết lượt xem của một sản phẩm
     */
    public function show(Product $product)
    {
        // Lấy thống kê lượt xem theo thời gian
        $viewStats = [
            'total' => $this->productViewService->getViewCount($product),
            'today' => $this->productViewService->getViewCountByTimeRange($product, 'today'),
            'week' => $this->productViewService->getViewCountByTimeRange($product, 'week'),
            'month' => $this->productViewService->getViewCountByTimeRange($product, 'month'),
        ];

        // Lấy thống kê theo ngày
        $dailyStats = $this->productViewService->getViewStatsByDay($product, 30);

        // Lấy danh sách user đã xem sản phẩm
        $recentViews = ProductView::with('user')
            ->where('product_id', $product->id)
            ->orderByDesc('viewed_at')
            ->limit(50)
            ->get();

        // Thống kê theo user agent
        $userAgentStats = ProductView::where('product_id', $product->id)
            ->selectRaw('user_agent, COUNT(*) as count')
            ->groupBy('user_agent')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return view('admin.product_view_stats.show', compact(
            'product',
            'viewStats',
            'dailyStats',
            'recentViews',
            'userAgentStats'
        ));
    }

    /**
     * Export dữ liệu thống kê
     */
    public function export(Request $request)
    {
        $timeRange = $request->get('time_range', 'all');
        $format = $request->get('format', 'csv');

        $products = Product::with(['shop'])
            ->get()
            ->map(function ($product) use ($timeRange) {
                return [
                    'ID' => $product->id,
                    'Tên sản phẩm' => $product->name,
                    'SKU' => $product->sku,
                    'Shop' => $product->shop->shop_name ?? 'N/A',
                    'Tổng lượt xem' => $this->productViewService->getViewCount($product),
                    'Lượt xem hôm nay' => $this->productViewService->getViewCountByTimeRange($product, 'today'),
                    'Lượt xem tuần này' => $this->productViewService->getViewCountByTimeRange($product, 'week'),
                    'Lượt xem tháng này' => $this->productViewService->getViewCountByTimeRange($product, 'month'),
                    'Trạng thái' => $product->status,
                    'Ngày tạo' => $product->created_at->format('d/m/Y H:i:s'),
                ];
            });

        if ($format === 'csv') {
            return $this->exportToCsv($products, 'product_view_stats_' . date('Y-m-d') . '.csv');
        }

        return response()->json($products);
    }

    /**
     * Lấy thống kê theo ngày
     */
    private function getDailyStats(int $days = 7): array
    {
        $stats = [];
        $startDate = now()->subDays($days - 1)->startOfDay();

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $count = ProductView::whereDate('viewed_at', $date)->count();

            $stats[] = [
                'date' => $date->format('Y-m-d'),
                'count' => $count,
            ];
        }

        return $stats;
    }

    /**
     * Export dữ liệu ra CSV
     */
    private function exportToCsv($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            
            // Thêm BOM để hỗ trợ tiếng Việt
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            if (!empty($data)) {
                fputcsv($file, array_keys($data[0]));
            }
            
            // Data
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
