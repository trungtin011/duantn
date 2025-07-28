<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformRevenueModel;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductCategories;

class DashboardController extends Controller
{
    public function index()
    {
        $deliveredOrders = DB::table('orders')
            ->count();

        $deliveredGrowth = $this->calculateGrowth('orders', 'payment_status', 'paid');

        // Tính doanh thu trung bình mỗi ngày trong tháng này (theo số ngày của tháng hiện tại)
        $currentMonthDays = now()->daysInMonth;
        $avgDailyRevenue = DB::table('orders')
            ->whereIn('payment_status', ['paid', 'cod_paid'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->selectRaw('ROUND(SUM(total_price) / ?, 2) as avg_daily_revenue', [$currentMonthDays])
            ->value('avg_daily_revenue') ?? 0;

        $revenueGrowth = $this->calculateRevenueGrowth();

        // Khách hàng mới trong tháng này
        $newCustomers = DB::table('users')
            ->where('role', 'customer')
            ->where('created_at', '>=', now()->subMonth())
            ->count();

        $customerGrowth = $this->calculateGrowth('users', 'role', 'customer');

        // hoa hồng từ các đơn hàng 
        $platfrom_revenue = PlatformRevenueModel::totalPlatformRevenue();
        
        $platfromGrowth = $this->calculateGrowth('platform_revenues', 'commission_amount', 'paid');

        // Sales Chart
        $salesData = DB::select("
            SELECT 
                MONTH(o.created_at) AS month,
                SUM(o.total_price) AS sales,
                COUNT(DISTINCT vh.id) AS visitors,
                SUM(p.sold_quantity) AS products_sold
            FROM orders o
            LEFT JOIN view_history vh 
                ON MONTH(vh.created_at) = MONTH(o.created_at) 
                AND YEAR(vh.created_at) = YEAR(o.created_at)
            LEFT JOIN products p 
                ON MONTH(p.updated_at) = MONTH(o.created_at) 
                AND YEAR(p.updated_at) = YEAR(o.created_at)
            WHERE YEAR(o.created_at) = YEAR(CURDATE())
            AND o.payment_status = 'paid'
            GROUP BY MONTH(o.created_at)
            ORDER BY MONTH(o.created_at)
        ");
        $labels = array_map(function ($item) {
            return date('M', mktime(0, 0, 0, $item->month, 1));
        }, $salesData);
        $sales = array_map(function ($item) {
            return $item->sales ?? 0;
        }, $salesData);
        $visitors = array_map(function ($item) {
            return $item->visitors ?? 0;
        }, $salesData);
        $products = array_map(function ($item) {
            return $item->products_sold ?? 0;
        }, $salesData);

        // Category Chart
        $categoryData = DB::select("
            SELECT c.name AS category_name, SUM(p.sold_quantity) AS total_sold 
            FROM products p 
            JOIN product_categories pc ON p.id = pc.product_id
            JOIN categories c ON pc.category_id = c.id
            GROUP BY c.id, c.name 
            ORDER BY total_sold DESC
        ");
        $categoryLabels = array_map(function ($item) {
            return $item->category_name;
        }, $categoryData);
        $categoryValues = array_map(function ($item) {
            return $item->total_sold ?? 0;
        }, $categoryData);

        // Brand Chart
        $brandData = DB::select("
            SELECT b.name AS brand_name, SUM(p.sold_quantity) AS total_sold 
            FROM products p 
            JOIN product_brands pb ON p.id = pb.product_id
            JOIN brand b ON pb.brand_id = b.id
            GROUP BY b.id, b.name 
            ORDER BY total_sold DESC
        ");
        $brandLabels = array_map(function ($item) {
            return $item->brand_name;
        }, $brandData);
        $brandValues = array_map(function ($item) {
            return $item->total_sold ?? 0;
        }, $brandData);

        // Recent Orders
        $recentOrders = DB::table('orders as o')
            ->join('items_order as io', 'o.id', '=', 'io.orderID')
            ->join('products as p', 'io.productID', '=', 'p.id')
            ->join('users as u', 'o.userID', '=', 'u.id')
            ->select(
                'o.order_code as order_id',
                'u.fullname as customer_name',
                'o.created_at',
                'o.total_price as amount',
                'o.order_status as status',
                'p.name as product_name',
                'p.sale_price as price'
            )
            ->orderByDesc('o.created_at')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                $order->created_at = \Carbon\Carbon::parse($order->created_at);
                return $order;
            });

        // Product List
        $products = DB::select("
            SELECT p.name, p.sku AS product_id, p.sale_price AS price, p.status, 
                   MIN(c.name) AS category_name
            FROM products p 
            LEFT JOIN product_categories pc ON p.id = pc.product_id
            LEFT JOIN categories c ON pc.category_id = c.id
            GROUP BY p.id, p.name, p.sku, p.sale_price, p.status
            ORDER BY p.created_at DESC 
            LIMIT 10
        ");

        // Categories for filter
        $categories = DB::table('categories')->select('id', 'name')->get();

        return view('admin.dashboard', compact(
            'deliveredOrders',
            'deliveredGrowth',
            'avgDailyRevenue',
            'revenueGrowth',
            'newCustomers',
            'customerGrowth',
            'platfrom_revenue',
            'platfromGrowth',
            'labels',
            'sales',
            'visitors',
            'products',
            'categoryLabels',
            'categoryValues',
            'brandLabels',
            'brandValues',
            'recentOrders',
            'categories'
        ));
    }

    private function calculateGrowth($table, $column, $condition)
    {
        $currentCount = DB::table($table)
            ->where($column, is_array($condition) ? 'IN' : '=', is_array($condition) ? $condition : [$condition])
            ->where('created_at', '>=', now()->subMonth())
            ->count();

        if ($currentCount == 0) {
            return 0;
        }

        $previousCount = DB::table($table)
            ->where($column, is_array($condition) ? 'IN' : '=', is_array($condition) ? $condition : [$condition])
            ->whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])
            ->count();

        return $previousCount > 0 ? round((($currentCount - $previousCount) / $previousCount) * 100, 2) : 0;
    }

    private function calculateRevenueGrowth()
    {
        $currentRevenue = DB::table('orders')
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonth())
            ->selectRaw('ROUND(SUM(total_price) / DAY(LAST_DAY(NOW())), 2) as avg_daily_revenue')
            ->value('avg_daily_revenue') ?? 0;

        $previousRevenue = DB::table('orders')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])
            ->selectRaw('ROUND(SUM(total_price) / DAY(LAST_DAY(DATE_SUB(NOW(), INTERVAL 1 MONTH))), 2) as avg_daily_revenue')
            ->value('avg_daily_revenue') ?? 0;

        return $previousRevenue > 0 ? round((($currentRevenue - $previousRevenue) / $previousRevenue) * 100, 2) : 0;
    }
}
