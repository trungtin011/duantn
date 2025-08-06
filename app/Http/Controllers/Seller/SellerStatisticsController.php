<?php
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Order;
use App\Models\ShopOrder;
use App\Models\OrderReview;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SellerStatisticsController extends Controller
{
    public function index(Request $request)
    {
        // Validate input data
        $validated = $request->validate([
            'filter_type' => 'nullable|in:date,month,year',
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'year' => 'nullable|integer|min:1900|max:' . Carbon::today()->year,
            'month' => 'nullable|integer|min:1|max:12',
        ]);

        // Get the shop of the authenticated seller
        $shop = Shop::where('ownerID', Auth::id())->firstOrFail();

        // Determine filter type and set date range
        $filterType = $request->input('filter_type', 'date');
        $today = Carbon::today()->endOfDay();
        $currentYear = Carbon::today()->year;
        $currentMonth = Carbon::today()->month;
        $currentDay = Carbon::today()->day;
        $error = null;

        if ($filterType === 'year') {
            $year = $request->input('year', $currentYear);
            $month = null; // Define $month as null for year filter
            if ($year > $currentYear) {
                $error = 'Không thể chọn năm trong tương lai.';
                $year = $currentYear; // Fallback to current year
            }
            $startDate = Carbon::create($year, 1, 1)->startOfDay()->toDateString();
            $endDate = Carbon::create($year, $year == $currentYear ? $currentMonth : 12, 1)->endOfMonth()->endOfDay()->toDateString();
        } elseif ($filterType === 'month') {
            $year = $request->input('year', $currentYear);
            $month = $request->input('month', $currentMonth);
            if ($year > $currentYear || ($year == $currentYear && $month > $currentMonth)) {
                $error = 'Không thể chọn tháng trong tương lai.';
                $year = $currentYear;
                $month = $currentMonth; // Fallback to current month
            }
            $startDate = Carbon::create($year, $month, 1)->startOfDay()->toDateString();
            // For current year and month, end at current day; otherwise, end at month end
            $endDate = ($year == $currentYear && $month == $currentMonth)
                ? Carbon::create($year, $month, $currentDay)->endOfDay()->toDateString()
                : Carbon::create($year, $month, 1)->endOfMonth()->endOfDay()->toDateString();
        } else {
            $startDate = $request->input('start_date', Carbon::now()->subDays(30)->startOfDay()->toDateString());
            $endDate = $request->input('end_date', Carbon::now()->endOfDay()->toDateString());
            $year = Carbon::parse($startDate)->year;
            $month = Carbon::parse($startDate)->month;

            // Server-side validation for date range
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
            if ($end->diffInDays($start) + 1 > 31) {
                $error = 'Khoảng thời gian được chọn không được vượt quá 31 ngày.';
                $startDate = Carbon::now()->subDays(30)->startOfDay()->toDateString();
                $endDate = Carbon::now()->endOfDay()->toDateString();
                $year = Carbon::parse($startDate)->year;
                $month = Carbon::parse($startDate)->month;
            }
        }

        // Order statistics
        $orderStats = Cache::remember('seller_orders_' . $shop->id . '_' . ($startDate ?? 'all') . '_' . ($endDate ?? 'all'), now()->addMinutes(60), function () use ($shop, $startDate, $endDate) {
            $query = ShopOrder::where('shopID', $shop->id)
                ->join('orders', 'shop_order.orderID', '=', 'orders.id')
                ->select('orders.order_status', DB::raw('count(*) as total'))
                ->groupBy('orders.order_status');

            if ($startDate && $endDate) {
                $query->whereBetween('orders.created_at', [$startDate, $endDate]);
            }

            $shopOrders = $query->get()->pluck('total', 'order_status')->toArray();

            return [
                'total_orders' => array_sum($shopOrders),
                'pending' => $shopOrders['pending'] ?? 0,
                'processing' => $shopOrders['processing'] ?? 0,
                'completed' => $shopOrders['completed'] ?? 0,
                'cancelled' => $shopOrders['cancelled'] ?? 0,
                'returned' => $shopOrders['returned'] ?? 0,
            ];
        });

        // Cancellation rate
        $cancellationRate = $orderStats['total_orders'] > 0
            ? round($orderStats['cancelled'] / $orderStats['total_orders'] * 100, 2)
            : 0.0;

        // Review statistics
        $reviewStats = Cache::remember('seller_reviews_' . $shop->id, now()->addMinutes(60), function () use ($shop) {
            $reviews = OrderReview::where('shop_id', $shop->id)
                ->select('rating', DB::raw('count(*) as total'))
                ->groupBy('rating')
                ->get()
                ->pluck('total', 'rating')
                ->toArray();

            $totalReviews = array_sum($reviews);
            $totalRatingPoints = array_reduce(
                array_keys($reviews),
                fn($carry, $rating) => $carry + $rating * $reviews[$rating],
                0
            );

            return [
                'total_reviews' => $totalReviews,
                'average_rating' => $totalReviews > 0 ? round($totalRatingPoints / $totalReviews, 1) : 0.0,
                'rating_distribution' => [
                    '5' => $reviews[5] ?? 0,
                    '4' => $reviews[4] ?? 0,
                    '3' => $reviews[3] ?? 0,
                    '2' => $reviews[2] ?? 0,
                    '1' => $reviews[1] ?? 0,
                ],
            ];
        });

        // Inventory statistics
        $inventoryStats = Cache::remember('seller_inventory_' . $shop->id, now()->addMinutes(60), function () use ($shop) {
            $products = Product::where('shopID', $shop->id)
                ->select('status', DB::raw('sum(stock_total) as total_stock'))
                ->groupBy('status')
                ->get()
                ->pluck('total_stock', 'status')
                ->toArray();

            $variants = ProductVariant::whereIn('productID', function ($query) use ($shop) {
                $query->select('id')->from('products')->where('shopID', $shop->id);
            })
                ->select('status', DB::raw('sum(stock) as total_stock'))
                ->groupBy('status')
                ->get()
                ->pluck('total_stock', 'status')
                ->toArray();

            $productsWithoutVariantsStock = Product::where('shopID', $shop->id)
                ->whereDoesntHave('variants')
                ->sum('stock_total');

            $totalStock = array_sum($products) + array_sum($variants) + $productsWithoutVariantsStock;

            return [
                'total_stock' => $totalStock,
                'active_stock' => ($products['active'] ?? 0) + ($variants['active'] ?? 0),
                'out_of_stock' => ($products['out_of_stock'] ?? 0) + ($variants['out_of_stock'] ?? 0),
            ];
        });

        // Top 5 selling products with profit
        $topProducts = Cache::remember('seller_top_products_' . $shop->id . '_' . ($startDate ?? 'all') . '_' . ($endDate ?? 'all'), now()->addMinutes(60), function () use ($shop, $startDate, $endDate) {
            $query = Product::where('products.shopID', $shop->id)
                ->join('items_order', 'products.id', '=', 'items_order.productID')
                ->join('orders', 'items_order.orderID', '=', 'orders.id')
                ->leftJoin('product_variants', 'items_order.variantID', '=', 'product_variants.id')
                ->whereIn('orders.order_status', ['completed', 'processing'])
                ->select(
                    'products.id',
                    'products.name',
                    'products.sku',
                    'products.stock_total',
                    DB::raw('SUM(items_order.quantity) as total_sold'),
                    DB::raw('SUM(items_order.total_price) as total_revenue'),
                    DB::raw('SUM(items_order.total_price - (items_order.quantity * COALESCE(product_variants.purchase_price, products.purchase_price, 0))) as total_profit')
                )
                ->groupBy('products.id', 'products.name', 'products.sku', 'products.stock_total')
                ->orderByDesc('total_sold')
                ->take(5);

            if ($startDate && $endDate) {
                $query->whereBetween('orders.created_at', [$startDate, $endDate]);
            }

            $results = $query->get();

            return $results->map(fn($product) => [
                'product_id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'stock_total' => $product->stock_total ?? 0,
                'total_sold' => (int) $product->total_sold,
                'total_revenue' => round($product->total_revenue ?? 0, 2),
                'total_profit' => round($product->total_profit ?? 0, 2),
                'image_path' => $product->defaultImage ? Storage::url($product->defaultImage->image_path) : 'https://placehold.co/50x50',
            ])->toArray();
        });

        // Profit calculation
        $profitStats = Cache::remember('seller_profit_' . $shop->id . '_' . ($startDate ?? 'all') . '_' . ($endDate ?? 'all'), now()->addMinutes(60), function () use ($shop, $startDate, $endDate) {
            $query = OrderItem::whereIn('orderID', function ($subQuery) use ($shop) {
                $subQuery->select('orderID')
                    ->from('shop_order')
                    ->where('shopID', $shop->id);
            })
            ->join('products', 'items_order.productID', '=', 'products.id')
            ->leftJoin('product_variants', 'items_order.variantID', '=', 'product_variants.id')
            ->join('orders', 'items_order.orderID', '=', 'orders.id')
            ->where('orders.order_status', 'completed')
            ->select(
                DB::raw('SUM(items_order.total_price) as total_revenue'),
                DB::raw('SUM(items_order.quantity * COALESCE(product_variants.purchase_price, products.purchase_price, 0)) as total_cost'),
                DB::raw('SUM(items_order.total_price - (items_order.quantity * COALESCE(product_variants.purchase_price, products.purchase_price, 0))) as total_profit')
            );

            if ($startDate && $endDate) {
                $query->whereBetween('orders.created_at', [$startDate, $endDate]);
            }

            $result = $query->first();

            return [
                'total_revenue' => round($result->total_revenue ?? 0, 2),
                'total_cost' => round($result->total_cost ?? 0, 2),
                'profit' => round($result->total_profit ?? 0, 2),
            ];
        });

        // Revenue data for chart
        $revenueData = Cache::remember('seller_revenue_' . $filterType . '_' . $shop->id . '_' . ($startDate ?? 'all') . '_' . ($endDate ?? 'all'), now()->addMinutes(60), function () use ($shop, $startDate, $endDate, $filterType, $currentYear, $currentMonth, $currentDay) {
            if ($filterType === 'year') {
                $query = OrderItem::whereIn('orderID', function ($subQuery) use ($shop) {
                    $subQuery->select('orderID')
                        ->from('shop_order')
                        ->where('shopID', $shop->id);
                })
                ->join('orders', 'items_order.orderID', '=', 'orders.id')
                ->where('orders.order_status', 'completed')
                ->select(
                    DB::raw('MONTH(orders.created_at) as month'),
                    DB::raw('SUM(items_order.total_price) as revenue')
                )
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->groupBy('month')
                ->orderBy('month');

                $results = $query->get();

                $maxMonth = (Carbon::parse($startDate)->year == $currentYear) ? $currentMonth : 12;
                $months = [];
                $revenues = [];
                for ($m = 1; $m <= $maxMonth; $m++) {
                    $months[] = $m;
                    $revenue = $results->firstWhere('month', $m);
                    $revenues[] = $revenue ? round($revenue->revenue, 2) : 0;
                }

                return [
                    'labels' => $months,
                    'values' => $revenues,
                ];
            } else {
                // Daily data for date or month filter
                $query = OrderItem::whereIn('orderID', function ($subQuery) use ($shop) {
                    $subQuery->select('orderID')
                        ->from('shop_order')
                        ->where('shopID', $shop->id);
                })
                ->join('orders', 'items_order.orderID', '=', 'orders.id')
                ->where('orders.order_status', 'completed')
                ->select(
                    DB::raw('DATE(orders.created_at) as date'),
                    DB::raw('SUM(items_order.total_price) as revenue')
                )
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date');

                $results = $query->get();

                $start = Carbon::parse($startDate);
                $end = Carbon::parse($endDate);
                $dates = [];
                $revenues = [];
                for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                    $dateStr = $date->toDateString();
                    $dates[] = $dateStr;
                    $revenue = $results->firstWhere('date', $dateStr);
                    $revenues[] = $revenue ? round($revenue->revenue, 2) : 0;
                }

                return [
                    'labels' => $dates,
                    'values' => $revenues,
                ];
            }
        });

        // Low stock products
        $lowStockProducts = Cache::remember('seller_low_stock_' . $shop->id, now()->addMinutes(60), function () use ($shop) {
            return Product::with(['variants', 'defaultImage'])
                ->where('shopID', $shop->id)
                ->where('status', '!=', 'out_of_stock')
                ->get()
                ->filter(function ($product) {
                    $totalStock = ($product->variants && $product->variants->count() > 0)
                        ? $product->variants->sum('stock')
                        : $product->stock_total;
                    return $totalStock <= 10;
                })
                ->map(function ($product) {
                    $totalStock = ($product->variants && $product->variants->count() > 0)
                        ? $product->variants->sum('stock')
                        : $product->stock_total;

                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'stock_total' => $totalStock,
                        'image_path' => $product->defaultImage
                            ? Storage::url($product->defaultImage->image_path)
                            : 'https://placehold.co/50x50',
                    ];
                })
                ->values()
                ->toArray();
        });

        $lowStockCount = count($lowStockProducts);

        // Combine all statistics
        $statistics = [
            'order_statistics' => $orderStats,
            'cancellation_rate' => $cancellationRate,
            'review_statistics' => $reviewStats,
            'inventory_statistics' => $inventoryStats,
            'top_selling_products' => $topProducts,
            'profit' => $profitStats['profit'],
            'total_revenue' => $profitStats['total_revenue'],
            'low_stock_products' => $lowStockProducts,
            'revenue_data' => $revenueData,
        ];

        // Pass current month and day for month filter
        $maxMonth = ($year == $currentYear) ? $currentMonth : 12;

        return view('seller.home', compact('statistics', 'shop', 'startDate', 'endDate', 'filterType', 'year', 'month', 'error', 'maxMonth', 'lowStockCount'));
    }
}