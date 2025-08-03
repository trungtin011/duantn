<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class DashboardTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Tạo admin user
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com'
        ]);
    }

    /** @test */
    public function admin_can_access_dashboard()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    /** @test */
    public function dashboard_shows_correct_statistics()
    {
        // Tạo test data
        $this->createTestData();

        $response = $this->actingAs($this->admin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        
        // Kiểm tra các biến được truyền vào view
        $response->assertViewHas('totalRevenue');
        $response->assertViewHas('totalOrders');
        $response->assertViewHas('totalProducts');
        $response->assertViewHas('totalUsers');
        $response->assertViewHas('totalShops');
        $response->assertViewHas('monthlyRevenueData');
        $response->assertViewHas('orderStatusData');
        $response->assertViewHas('recentOrders');
        $response->assertViewHas('topSellingProducts');
    }

    /** @test */
    public function dashboard_calculates_revenue_correctly()
    {
        // Tạo đơn hàng với payment_status = 'paid'
        Order::factory()->create([
            'total_price' => 1000000,
            'payment_status' => 'paid'
        ]);

        Order::factory()->create([
            'total_price' => 500000,
            'payment_status' => 'paid'
        ]);

        // Đơn hàng chưa thanh toán không được tính
        Order::factory()->create([
            'total_price' => 200000,
            'payment_status' => 'pending'
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        
        // Tổng doanh thu phải là 1.500.000
        $this->assertEquals(1500000, $response->viewData('totalRevenue'));
    }

    /** @test */
    public function dashboard_shows_top_selling_products()
    {
        // Tạo sản phẩm với số lượng bán khác nhau
        Product::factory()->create([
            'name' => 'Product A',
            'sold_quantity' => 100,
            'status' => 'active'
        ]);

        Product::factory()->create([
            'name' => 'Product B',
            'sold_quantity' => 50,
            'status' => 'active'
        ]);

        Product::factory()->create([
            'name' => 'Product C',
            'sold_quantity' => 200,
            'status' => 'active'
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        
        $topProducts = $response->viewData('topSellingProducts');
        
        // Kiểm tra sản phẩm đầu tiên phải có sold_quantity cao nhất
        $this->assertEquals(200, $topProducts->first()['sold_quantity']);
        $this->assertEquals('Product C', $topProducts->first()['name']);
    }

    /** @test */
    public function dashboard_shows_recent_orders()
    {
        // Tạo đơn hàng gần đây
        $recentOrder = Order::factory()->create([
            'order_code' => 'ORD001',
            'created_at' => now()
        ]);

        $oldOrder = Order::factory()->create([
            'order_code' => 'ORD002',
            'created_at' => now()->subDays(10)
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        
        $recentOrders = $response->viewData('recentOrders');
        
        // Kiểm tra đơn hàng gần đây nhất phải là ORD001
        $this->assertEquals('ORD001', $recentOrders->first()['order_code']);
    }

    /** @test */
    public function dashboard_calculates_growth_correctly()
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

        $response = $this->actingAs($this->admin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        
        // Tăng trưởng phải là 50%
        $this->assertEquals(50, $response->viewData('revenueGrowth'));
    }

    /** @test */
    public function dashboard_only_counts_active_products()
    {
        // Sản phẩm active
        Product::factory()->create(['status' => 'active']);
        Product::factory()->create(['status' => 'active']);
        
        // Sản phẩm inactive
        Product::factory()->create(['status' => 'inactive']);
        Product::factory()->create(['status' => 'deleted']);

        $response = $this->actingAs($this->admin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        
        // Chỉ đếm sản phẩm active
        $this->assertEquals(2, $response->viewData('totalProducts'));
    }

    /** @test */
    public function dashboard_only_counts_customer_users()
    {
        // Khách hàng
        User::factory()->create(['role' => 'customer']);
        User::factory()->create(['role' => 'customer']);
        
        // Admin và seller không được đếm
        User::factory()->create(['role' => 'admin']);
        User::factory()->create(['role' => 'seller']);

        $response = $this->actingAs($this->admin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        
        // Chỉ đếm customer
        $this->assertEquals(2, $response->viewData('totalUsers'));
    }

    /** @test */
    public function dashboard_only_counts_active_shops()
    {
        // Shop active
        Shop::factory()->create(['shop_status' => 'active']);
        Shop::factory()->create(['shop_status' => 'active']);
        
        // Shop inactive
        Shop::factory()->create(['shop_status' => 'inactive']);
        Shop::factory()->create(['shop_status' => 'suspended']);

        $response = $this->actingAs($this->admin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        
        // Chỉ đếm shop active
        $this->assertEquals(2, $response->viewData('totalShops'));
    }

    private function createTestData()
    {
        // Tạo users
        User::factory()->count(5)->create(['role' => 'customer']);
        
        // Tạo shops
        Shop::factory()->count(3)->create(['shop_status' => 'active']);
        
        // Tạo products
        Product::factory()->count(10)->create(['status' => 'active']);
        
        // Tạo orders
        Order::factory()->count(20)->create([
            'payment_status' => 'paid',
            'total_price' => $this->faker->numberBetween(100000, 5000000)
        ]);
    }
} 