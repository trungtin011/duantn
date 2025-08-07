<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Helpers\DashboardHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Lấy tất cả dữ liệu dashboard
     */
    public function getDashboardData($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now()->endOfDay();
        
        return [
            'totalRevenue' => $this->getTotalRevenue($startDate, $endDate),
            'revenueGrowth' => $this->calculateRevenueGrowth($startDate, $endDate),
            'totalOrders' => $this->getTotalOrders($startDate, $endDate),
            'orderGrowth' => $this->calculateOrderGrowth($startDate, $endDate),
            'totalProducts' => $this->getTotalProducts(),
            'productGrowth' => $this->calculateProductGrowth($startDate, $endDate),
            'topSellingProducts' => $this->getTopSellingProducts($startDate, $endDate),
            'totalUsers' => $this->getTotalUsers(),
            'userGrowth' => $this->calculateUserGrowth($startDate, $endDate),
            'totalShops' => $this->getTotalShops(),
            'shopGrowth' => $this->calculateShopGrowth($startDate, $endDate),
            'monthlyRevenueData' => $this->getMonthlyRevenueData($startDate, $endDate),
            'orderStatusData' => $this->getOrderStatusData($startDate, $endDate),
            'recentOrders' => $this->getRecentOrders(),
            'quickStats' => $this->getQuickStats($startDate, $endDate),
            'dateRange' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
                'start_formatted' => $startDate->format('d/m/Y'),
                'end_formatted' => $endDate->format('d/m/Y')
            ]
        ];
    }

    /**
     * Lấy tổng doanh thu
     */
    private function getTotalRevenue($startDate = null, $endDate = null)
    {
        $query = Order::whereIn('payment_status', ['paid', 'cod_paid']);
        
        if ($startDate && $endDate) {
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        }
        
        return $query->sum('total_price');
    }

    /**
     * Lấy tổng số đơn hàng
     */
    private function getTotalOrders($startDate = null, $endDate = null)
    {
        $query = Order::query();
        
        if ($startDate && $endDate) {
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        }
        
        return $query->count();
    }

    /**
     * Lấy tổng sản phẩm đang bán
     */
    private function getTotalProducts()
    {
        return Product::where('status', 'active')->count();
    }

    /**
     * Lấy sản phẩm bán chạy nhất
     */
    private function getTopSellingProducts($startDate = null, $endDate = null)
    {
        $query = Product::select('id', 'name', 'sale_price', 'sold_quantity', 'stock_total')
            ->where('status', 'active');
            
        if ($startDate && $endDate) {
            $query->whereHas('orders', function($q) use ($startDate, $endDate) {
                $q->whereBetween('orders.created_at', [$startDate, $endDate]);
            });
        }
        
        return $query->orderBy('sold_quantity', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->sale_price,
                    'sold_quantity' => $product->sold_quantity,
                    'stock_total' => $product->stock_total,
                    'revenue' => $product->sale_price * $product->sold_quantity,
                    'image_url' => $product->images->first()?->image_url ?? '/assets/images/products/default.jpg'
                ];
            });
    }

    /**
     * Lấy tổng người dùng
     */
    private function getTotalUsers()
    {
        return User::where('role', 'customer')->count();
    }

    /**
     * Lấy tổng shop đang hoạt động
     */
    private function getTotalShops()
    {
        return Shop::where('shop_status', 'active')->count();
    }

    /**
     * Lấy dữ liệu doanh thu theo tháng
     */
    private function getMonthlyRevenueData($startDate = null, $endDate = null)
    {
        $query = Order::selectRaw('
                MONTH(orders.created_at) as month,
                YEAR(orders.created_at) as year,
                SUM(total_price) as revenue,
                COUNT(*) as order_count
            ')
            ->whereIn('payment_status', ['paid', 'cod_paid']);
            
        if ($startDate && $endDate) {
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        } else {
            $query->whereYear('orders.created_at', date('Y'));
        }
        
        $data = $query->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $months = [];
        $revenues = [];
        $orderCounts = [];

        if ($startDate && $endDate) {
            // Tạo dữ liệu theo khoảng thời gian thực tế
            $current = $startDate->copy();
            while ($current <= $endDate) {
                $monthData = $data->where('month', $current->month)->where('year', $current->year)->first();
                $months[] = $current->format('M Y');
                $revenues[] = $monthData ? (float) $monthData->revenue : 0;
                $orderCounts[] = $monthData ? $monthData->order_count : 0;
                $current->addMonth();
            }
        } else {
            // Tạo dữ liệu cho 12 tháng
            for ($i = 1; $i <= 12; $i++) {
                $monthData = $data->where('month', $i)->first();
                $months[] = Carbon::create()->month($i)->format('M');
                $revenues[] = $monthData ? (float) $monthData->revenue : 0;
                $orderCounts[] = $monthData ? $monthData->order_count : 0;
            }
        }

        return [
            'labels' => $months,
            'revenues' => $revenues,
            'order_counts' => $orderCounts
        ];
    }

    /**
     * Lấy dữ liệu đơn hàng theo trạng thái
     */
    private function getOrderStatusData($startDate = null, $endDate = null)
    {
        $query = Order::selectRaw('order_status, COUNT(*) as count');
        
        if ($startDate && $endDate) {
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        }
        
        $data = $query->groupBy('order_status')->get();

        $labels = [];
        $values = [];
        $colors = [
            'pending' => '#FFA500',
            'confirmed' => '#007BFF',
            'processing' => '#17A2B8',
            'shipped' => '#28A745',
            'delivered' => '#20C997',
            'cancelled' => '#DC3545',
            'refunded' => '#6C757D'
        ];

        foreach ($data as $item) {
            $labels[] = DashboardHelper::getOrderStatusLabel($item->order_status);
            $values[] = $item->count;
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'colors' => array_values($colors)
        ];
    }

    /**
     * Lấy đơn hàng gần đây
     */
    private function getRecentOrders()
    {
        return Order::with(['user:id,fullname,email', 'items.product:id,name'])
            ->orderBy('orders.created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_code' => $order->order_code,
                    'customer_name' => $order->user->fullname ?? 'N/A',
                    'total_price' => $order->total_price,
                    'order_status' => $order->order_status,
                    'order_status_label' => DashboardHelper::getOrderStatusLabel($order->order_status),
                    'order_status_badge' => DashboardHelper::getOrderStatusBadge($order->order_status),
                    'payment_status' => $order->payment_status,
                    'created_at' => $order->created_at->format('d/m/Y H:i'),
                    'items_count' => $order->items->count()
                ];
            });
    }

    /**
     * Lấy thống kê nhanh
     */
    private function getQuickStats($startDate = null, $endDate = null)
    {
        if ($startDate && $endDate) {
            $periodRevenue = Order::whereIn('payment_status', ['paid', 'cod_paid'])
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->sum('total_price');
                
            $periodOrders = Order::whereBetween('orders.created_at', [$startDate, $endDate])
                ->count();
                
            $periodUsers = User::where('role', 'customer')
                ->whereBetween('users.created_at', [$startDate, $endDate])
                ->count();
        } else {
            $currentMonth = Carbon::now();
            $periodRevenue = Order::whereIn('payment_status', ['paid', 'cod_paid'])
                ->whereMonth('orders.created_at', $currentMonth->month)
                ->whereYear('orders.created_at', $currentMonth->year)
                ->sum('total_price');
            $periodOrders = Order::whereMonth('orders.created_at', $currentMonth->month)
                ->whereYear('orders.created_at', $currentMonth->year)
                ->count();
            $periodUsers = User::where('role', 'customer')
                ->whereMonth('users.created_at', $currentMonth->month)
                ->whereYear('users.created_at', $currentMonth->year)
                ->count();
        }

        return [
            'period_revenue' => $periodRevenue,
            'period_orders' => $periodOrders,
            'period_users' => $periodUsers,
            'avg_order_value' => Order::whereIn('payment_status', ['paid', 'cod_paid'])
                ->avg('orders.total_price') ?? 0
        ];
    }

    /**
     * Tính tăng trưởng doanh thu
     */
    private function calculateRevenueGrowth($startDate = null, $endDate = null)
    {
        if ($startDate && $endDate) {
            $periodDuration = $startDate->diffInDays($endDate);
            $previousStartDate = $startDate->copy()->subDays($periodDuration);
            $previousEndDate = $startDate->copy()->subDay();
            
            $currentRevenue = Order::whereIn('payment_status', ['paid', 'cod_paid'])
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->sum('total_price');

            $previousRevenue = Order::whereIn('payment_status', ['paid', 'cod_paid'])
                ->whereBetween('orders.created_at', [$previousStartDate, $previousEndDate])
                ->sum('total_price');
        } else {
            $currentMonth = Carbon::now();
            $lastMonth = Carbon::now()->subMonth();

            $currentRevenue = Order::whereIn('payment_status', ['paid', 'cod_paid'])
                ->whereMonth('orders.created_at', $currentMonth->month)
                ->whereYear('orders.created_at', $currentMonth->year)
                ->sum('total_price');

            $previousRevenue = Order::whereIn('payment_status', ['paid', 'cod_paid'])
                ->whereMonth('orders.created_at', $lastMonth->month)
                ->whereYear('orders.created_at', $lastMonth->year)
                ->sum('total_price');
        }

        return DashboardHelper::calculateGrowth($currentRevenue, $previousRevenue);
    }

    /**
     * Tính tăng trưởng đơn hàng
     */
    private function calculateOrderGrowth($startDate = null, $endDate = null)
    {
        if ($startDate && $endDate) {
            $periodDuration = $startDate->diffInDays($endDate);
            $previousStartDate = $startDate->copy()->subDays($periodDuration);
            $previousEndDate = $startDate->copy()->subDay();
            
            $currentOrders = Order::whereBetween('orders.created_at', [$startDate, $endDate])
                ->count();

            $previousOrders = Order::whereBetween('orders.created_at', [$previousStartDate, $previousEndDate])
                ->count();
        } else {
            $currentMonth = Carbon::now();
            $lastMonth = Carbon::now()->subMonth();

            $currentOrders = Order::whereMonth('orders.created_at', $currentMonth->month)
                ->whereYear('orders.created_at', $currentMonth->year)
                ->count();

            $previousOrders = Order::whereMonth('orders.created_at', $lastMonth->month)
                ->whereYear('orders.created_at', $lastMonth->year)
                ->count();
        }

        return DashboardHelper::calculateGrowth($currentOrders, $previousOrders);
    }

    /**
     * Tính tăng trưởng sản phẩm
     */
    private function calculateProductGrowth($startDate = null, $endDate = null)
    {
        if ($startDate && $endDate) {
            $periodDuration = $startDate->diffInDays($endDate);
            $previousStartDate = $startDate->copy()->subDays($periodDuration);
            $previousEndDate = $startDate->copy()->subDay();
            
            $currentProducts = Product::where('status', 'active')
                ->whereBetween('products.created_at', [$startDate, $endDate])
                ->count();

            $previousProducts = Product::where('status', 'active')
                ->whereBetween('products.created_at', [$previousStartDate, $previousEndDate])
                ->count();
        } else {
            $currentMonth = Carbon::now();
            $lastMonth = Carbon::now()->subMonth();

            $currentProducts = Product::where('status', 'active')
                ->whereMonth('products.created_at', $currentMonth->month)
                ->whereYear('products.created_at', $currentMonth->year)
                ->count();

            $previousProducts = Product::where('status', 'active')
                ->whereMonth('products.created_at', $lastMonth->month)
                ->whereYear('products.created_at', $lastMonth->year)
                ->count();
        }

        return DashboardHelper::calculateGrowth($currentProducts, $previousProducts);
    }

    /**
     * Tính tăng trưởng người dùng
     */
    private function calculateUserGrowth($startDate = null, $endDate = null)
    {
        if ($startDate && $endDate) {
            $periodDuration = $startDate->diffInDays($endDate);
            $previousStartDate = $startDate->copy()->subDays($periodDuration);
            $previousEndDate = $startDate->copy()->subDay();
            
            $currentUsers = User::where('role', 'customer')
                ->whereBetween('users.created_at', [$startDate, $endDate])
                ->count();

            $previousUsers = User::where('role', 'customer')
                ->whereBetween('users.created_at', [$previousStartDate, $previousEndDate])
                ->count();
        } else {
            $currentMonth = Carbon::now();
            $lastMonth = Carbon::now()->subMonth();

            $currentUsers = User::where('role', 'customer')
                ->whereMonth('users.created_at', $currentMonth->month)
                ->whereYear('users.created_at', $currentMonth->year)
                ->count();

            $previousUsers = User::where('role', 'customer')
                ->whereMonth('users.created_at', $lastMonth->month)
                ->whereYear('users.created_at', $lastMonth->year)
                ->count();
        }

        return DashboardHelper::calculateGrowth($currentUsers, $previousUsers);
    }

    /**
     * Tính tăng trưởng shop
     */
    private function calculateShopGrowth($startDate = null, $endDate = null)
    {
        if ($startDate && $endDate) {
            $periodDuration = $startDate->diffInDays($endDate);
            $previousStartDate = $startDate->copy()->subDays($periodDuration);
            $previousEndDate = $startDate->copy()->subDay();
            
            $currentShops = Shop::where('shop_status', 'active')
                ->whereBetween('shops.created_at', [$startDate, $endDate])
                ->count();

            $previousShops = Shop::where('shop_status', 'active')
                ->whereBetween('shops.created_at', [$previousStartDate, $previousEndDate])
                ->count();
        } else {
            $currentMonth = Carbon::now();
            $lastMonth = Carbon::now()->subMonth();

            $currentShops = Shop::where('shop_status', 'active')
                ->whereMonth('shops.created_at', $currentMonth->month)
                ->whereYear('shops.created_at', $currentMonth->year)
                ->count();

            $previousShops = Shop::where('shop_status', 'active')
                ->whereMonth('shops.created_at', $lastMonth->month)
                ->whereYear('shops.created_at', $lastMonth->year)
                ->count();
        }

        return DashboardHelper::calculateGrowth($currentShops, $previousShops);
    }
} 