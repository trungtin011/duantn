<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\DashboardService;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class DashboardServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $dashboardService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dashboardService = new DashboardService();
    }

    /** @test */
    public function it_returns_dashboard_data_structure()
    {
        $data = $this->dashboardService->getDashboardData();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('totalRevenue', $data);
        $this->assertArrayHasKey('revenueGrowth', $data);
        $this->assertArrayHasKey('totalOrders', $data);
        $this->assertArrayHasKey('orderGrowth', $data);
        $this->assertArrayHasKey('totalProducts', $data);
        $this->assertArrayHasKey('productGrowth', $data);
        $this->assertArrayHasKey('topSellingProducts', $data);
        $this->assertArrayHasKey('totalUsers', $data);
        $this->assertArrayHasKey('userGrowth', $data);
        $this->assertArrayHasKey('totalShops', $data);
        $this->assertArrayHasKey('shopGrowth', $data);
        $this->assertArrayHasKey('monthlyRevenueData', $data);
        $this->assertArrayHasKey('orderStatusData', $data);
        $this->assertArrayHasKey('recentOrders', $data);
        $this->assertArrayHasKey('quickStats', $data);
    }

    /** @test */
    public function it_calculates_total_revenue_correctly()
    {
        // Tạo đơn hàng đã thanh toán
        Order::factory()->create([
            'total_price' => 1000000,
            'payment_status' => 'paid'
        ]);

        Order::factory()->create([
            'total_price' => 500000,
            'payment_status' => 'cod_paid'
        ]);

        // Đơn hàng chưa thanh toán
        Order::factory()->create([
            'total_price' => 200000,
            'payment_status' => 'pending'
        ]);

        $data = $this->dashboardService->getDashboardData();

        $this->assertEquals(1500000, $data['totalRevenue']);
    }

    /** @test */
    public function it_counts_only_active_products()
    {
        Product::factory()->create(['status' => 'active']);
        Product::factory()->create(['status' => 'active']);
        Product::factory()->create(['status' => 'inactive']);
        Product::factory()->create(['status' => 'deleted']);

        $data = $this->dashboardService->getDashboardData();

        $this->assertEquals(2, $data['totalProducts']);
    }

    /** @test */
    public function it_counts_only_customer_users()
    {
        User::factory()->create(['role' => 'customer']);
        User::factory()->create(['role' => 'customer']);
        User::factory()->create(['role' => 'admin']);
        User::factory()->create(['role' => 'seller']);

        $data = $this->dashboardService->getDashboardData();

        $this->assertEquals(2, $data['totalUsers']);
    }

    /** @test */
    public function it_counts_only_active_shops()
    {
        Shop::factory()->create(['shop_status' => 'active']);
        Shop::factory()->create(['shop_status' => 'active']);
        Shop::factory()->create(['shop_status' => 'inactive']);
        Shop::factory()->create(['shop_status' => 'suspended']);

        $data = $this->dashboardService->getDashboardData();

        $this->assertEquals(2, $data['totalShops']);
    }

    /** @test */
    public function it_returns_top_selling_products_ordered_by_sold_quantity()
    {
        Product::factory()->create([
            'name' => 'Product A',
            'sold_quantity' => 100,
            'status' => 'active'
        ]);

        Product::factory()->create([
            'name' => 'Product B',
            'sold_quantity' => 200,
            'status' => 'active'
        ]);

        Product::factory()->create([
            'name' => 'Product C',
            'sold_quantity' => 50,
            'status' => 'active'
        ]);

        $data = $this->dashboardService->getDashboardData();
        $topProducts = $data['topSellingProducts'];

        $this->assertEquals(3, $topProducts->count());
        $this->assertEquals('Product B', $topProducts->first()['name']);
        $this->assertEquals(200, $topProducts->first()['sold_quantity']);
    }

    /** @test */
    public function it_returns_monthly_revenue_data_structure()
    {
        $data = $this->dashboardService->getDashboardData();
        $monthlyData = $data['monthlyRevenueData'];

        $this->assertArrayHasKey('labels', $monthlyData);
        $this->assertArrayHasKey('revenues', $monthlyData);
        $this->assertArrayHasKey('order_counts', $monthlyData);
        $this->assertCount(12, $monthlyData['labels']);
        $this->assertCount(12, $monthlyData['revenues']);
        $this->assertCount(12, $monthlyData['order_counts']);
    }

    /** @test */
    public function it_returns_order_status_data_structure()
    {
        // Tạo đơn hàng với các trạng thái khác nhau
        Order::factory()->create(['order_status' => 'pending']);
        Order::factory()->create(['order_status' => 'delivered']);
        Order::factory()->create(['order_status' => 'cancelled']);

        $data = $this->dashboardService->getDashboardData();
        $orderStatusData = $data['orderStatusData'];

        $this->assertArrayHasKey('labels', $orderStatusData);
        $this->assertArrayHasKey('values', $orderStatusData);
        $this->assertArrayHasKey('colors', $orderStatusData);
        $this->assertCount(3, $orderStatusData['labels']);
        $this->assertCount(3, $orderStatusData['values']);
    }

    /** @test */
    public function it_returns_recent_orders_with_correct_structure()
    {
        Order::factory()->create([
            'order_code' => 'ORD001',
            'created_at' => now()
        ]);

        Order::factory()->create([
            'order_code' => 'ORD002',
            'created_at' => now()->subDays(1)
        ]);

        $data = $this->dashboardService->getDashboardData();
        $recentOrders = $data['recentOrders'];

        $this->assertCount(2, $recentOrders);
        $this->assertArrayHasKey('order_code', $recentOrders->first());
        $this->assertArrayHasKey('customer_name', $recentOrders->first());
        $this->assertArrayHasKey('total_price', $recentOrders->first());
        $this->assertArrayHasKey('order_status_label', $recentOrders->first());
        $this->assertArrayHasKey('order_status_badge', $recentOrders->first());
        $this->assertEquals('ORD001', $recentOrders->first()['order_code']);
    }

    /** @test */
    public function it_returns_quick_stats_structure()
    {
        $data = $this->dashboardService->getDashboardData();
        $quickStats = $data['quickStats'];

        $this->assertArrayHasKey('current_month_revenue', $quickStats);
        $this->assertArrayHasKey('current_month_orders', $quickStats);
        $this->assertArrayHasKey('current_month_users', $quickStats);
        $this->assertArrayHasKey('avg_order_value', $quickStats);
    }

    /** @test */
    public function it_calculates_growth_correctly()
    {
        // Tạo dữ liệu tháng trước
        Order::factory()->create([
            'total_price' => 1000000,
            'payment_status' => 'paid',
            'created_at' => now()->subMonth()
        ]);

        // Tạo dữ liệu tháng này
        Order::factory()->create([
            'total_price' => 1500000,
            'payment_status' => 'paid',
            'created_at' => now()
        ]);

        $data = $this->dashboardService->getDashboardData();

        // Tăng trưởng phải là 50%
        $this->assertEquals(50, $data['revenueGrowth']);
    }

    /** @test */
    public function it_handles_zero_previous_data_for_growth_calculation()
    {
        // Chỉ có dữ liệu tháng này
        Order::factory()->create([
            'total_price' => 1000000,
            'payment_status' => 'paid',
            'created_at' => now()
        ]);

        $data = $this->dashboardService->getDashboardData();

        // Khi không có dữ liệu tháng trước, tăng trưởng phải là 100%
        $this->assertEquals(100, $data['revenueGrowth']);
    }
} 