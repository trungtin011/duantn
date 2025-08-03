@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="dashboard-container">
    <!-- Header với bộ lọc ngày -->
    <div class="dashboard-header">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-chart-line"></i>
                Dashboard Analytics
            </h1>
            <div class="date-filter-container">
                <div class="date-filter">
                    <label for="start_date">Từ ngày:</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $dateRange['start'] ?? '' }}" class="date-input">
            </div>
                <div class="date-filter">
                    <label for="end_date">Đến ngày:</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $dateRange['end'] ?? '' }}" class="date-input">
        </div>
                <button id="apply-filter" class="btn btn-primary">
                    <i class="fas fa-filter"></i>
                    Áp dụng
                </button>
                <button id="reset-filter" class="btn btn-secondary">
                    <i class="fas fa-undo"></i>
                    Reset
                </button>
    </div>
        </div>
        <div class="date-range-display">
            <span class="date-range-text">
                <i class="fas fa-calendar-alt"></i>
                {{ $dateRange['start_formatted'] ?? '01/01/2024' }} - {{ $dateRange['end_formatted'] ?? '31/12/2024' }}
                    </span>
                </div>
                </div>

    <!-- Loading Spinner -->
    <div id="loading-spinner" class="loading-spinner" style="display: none;">
        <div class="spinner"></div>
        <p>Đang tải dữ liệu...</p>
            </div>

    <!-- KPI Cards -->
    <div class="kpi-cards-container">
        <div class="kpi-card revenue-card" data-aos="fade-up" data-aos-delay="100">
            <div class="card-icon">
                <i class="fas fa-dollar-sign"></i>
        </div>
            <div class="card-content">
                <h3 class="card-title">Tổng Doanh Thu</h3>
                <div class="card-value" id="total-revenue">{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</div>
                <div class="card-growth {{ $revenueGrowth >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-{{ $revenueGrowth >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                    {{ number_format(abs($revenueGrowth), 1) }}%
                </div>
                </div>
            </div>

        <div class="kpi-card orders-card" data-aos="fade-up" data-aos-delay="200">
            <div class="card-icon">
                <i class="fas fa-shopping-cart"></i>
        </div>
            <div class="card-content">
                <h3 class="card-title">Tổng Đơn Hàng</h3>
                <div class="card-value" id="total-orders">{{ number_format($totalOrders, 0, ',', '.') }}</div>
                <div class="card-growth {{ $orderGrowth >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-{{ $orderGrowth >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                    {{ number_format(abs($orderGrowth), 1) }}%
                </div>
                </div>
            </div>

        <div class="kpi-card products-card" data-aos="fade-up" data-aos-delay="300">
            <div class="card-icon">
                <i class="fas fa-box"></i>
        </div>
            <div class="card-content">
                <h3 class="card-title">Sản Phẩm Đang Bán</h3>
                <div class="card-value" id="total-products">{{ number_format($totalProducts, 0, ',', '.') }}</div>
                <div class="card-growth {{ $productGrowth >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-{{ $productGrowth >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                    {{ number_format(abs($productGrowth), 1) }}%
            </div>
        </div>
    </div>

        <div class="kpi-card users-card" data-aos="fade-up" data-aos-delay="400">
            <div class="card-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-content">
                <h3 class="card-title">Tổng Người Dùng</h3>
                <div class="card-value" id="total-users">{{ number_format($totalUsers, 0, ',', '.') }}</div>
                <div class="card-growth {{ $userGrowth >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-{{ $userGrowth >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                    {{ number_format(abs($userGrowth), 1) }}%
        </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <div class="chart-container" data-aos="fade-up" data-aos-delay="500">
            <div class="chart-header">
                <h3><i class="fas fa-chart-line"></i> Biểu Đồ Doanh Thu Theo Tháng</h3>
                <div class="chart-controls">
                    <button class="btn btn-sm btn-outline-primary" onclick="downloadChart('revenueChart')">
                        <i class="fas fa-download"></i> Tải xuống
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="printChart('revenueChart')">
                        <i class="fas fa-print"></i> In
                    </button>
            </div>
        </div>
            <div class="chart-wrapper">
                <canvas id="revenueChart" width="400" height="200"></canvas>
            </div>
    </div>

        <div class="chart-container" data-aos="fade-up" data-aos-delay="600">
            <div class="chart-header">
                <h3><i class="fas fa-chart-pie"></i> Phân Bố Trạng Thái Đơn Hàng</h3>
                <div class="chart-controls">
                    <button class="btn btn-sm btn-outline-primary" onclick="downloadChart('orderStatusChart')">
                        <i class="fas fa-download"></i> Tải xuống
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="printChart('orderStatusChart')">
                        <i class="fas fa-print"></i> In
                    </button>
            </div>
                            </div>
            <div class="chart-wrapper">
                <canvas id="orderStatusChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>

    <!-- Data Tables Section -->
    <div class="data-tables-section">
        <div class="table-container" data-aos="fade-up" data-aos-delay="700">
            <div class="table-header">
                <h3><i class="fas fa-star"></i> Sản Phẩm Bán Chạy Nhất</h3>
                <div class="table-controls">
                    <button class="btn btn-sm btn-outline-primary" onclick="exportTable('top-products-table')">
                        <i class="fas fa-file-excel"></i> Export
                    </button>
            </div>
        </div>
            <div class="table-responsive">
                <table class="table table-hover" id="top-products-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Đã bán</th>
                            <th>Doanh thu</th>
                            <th>Tồn kho</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topSellingProducts as $product)
                        <tr class="table-row-animate">
                            <td>
                                <div class="product-info">
                                    <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}" class="product-image">
                                    <div class="product-details">
                                        <div class="product-name">{{ Str::limit($product['name'], 30) }}</div>
                                        <div class="product-price">{{ number_format($product['price'], 0, ',', '.') }} VNĐ</div>
                                    </div>
                                </div>
                                </td>
                            <td class="text-center">
                                <span class="badge badge-info">{{ number_format($product['sold_quantity']) }}</span>
                            </td>
                            <td class="text-right">
                                <span class="revenue-amount">{{ number_format($product['revenue'], 0, ',', '.') }} VNĐ</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-{{ $product['stock_total'] > 0 ? 'success' : 'danger' }}">
                                    {{ $product['stock_total'] }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-{{ $product['stock_total'] > 0 ? 'success' : 'danger' }}">
                                    {{ $product['stock_total'] > 0 ? 'Còn hàng' : 'Hết hàng' }}
                                </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="table-container" data-aos="fade-up" data-aos-delay="800">
            <div class="table-header">
                <h3><i class="fas fa-clock"></i> Đơn Hàng Gần Đây</h3>
                <div class="table-controls">
                    <button class="btn btn-sm btn-outline-primary" onclick="exportTable('recent-orders-table')">
                        <i class="fas fa-file-excel"></i> Export
                    </button>
                    </div>
                    </div>
            <div class="table-responsive">
                <table class="table table-hover" id="recent-orders-table">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr class="table-row-animate">
                            <td>
                                <span class="order-code">{{ $order['order_code'] }}</span>
                            </td>
                            <td>
                                <div class="customer-info">
                                    <i class="fas fa-user"></i>
                                    {{ $order['customer_name'] }}
                </div>
                            </td>
                            <td class="text-right">
                                <span class="order-amount">{{ number_format($order['total_price'], 0, ',', '.') }} VNĐ</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-{{ $order['order_status_badge'] }}">
                                    {{ $order['order_status_label'] }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="order-date">{{ $order['created_at'] }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Stats Section -->
    <div class="quick-stats-section" data-aos="fade-up" data-aos-delay="900">
        <h3><i class="fas fa-tachometer-alt"></i> Thống Kê Nhanh</h3>
        <div class="quick-stats-grid">
            <div class="quick-stat-card">
                <div class="stat-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Doanh thu trong kỳ</div>
                    <div class="stat-value">{{ number_format($quickStats['period_revenue'], 0, ',', '.') }} VNĐ</div>
                                </div>
                            </div>
            <div class="quick-stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-bag"></i>
                        </div>
                <div class="stat-content">
                    <div class="stat-label">Đơn hàng trong kỳ</div>
                    <div class="stat-value">{{ number_format($quickStats['period_orders'], 0, ',', '.') }}</div>
                    </div>
                                </div>
            <div class="quick-stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-plus"></i>
                            </div>
                <div class="stat-content">
                    <div class="stat-label">Người dùng mới</div>
                    <div class="stat-value">{{ number_format($quickStats['period_users'], 0, ',', '.') }}</div>
                        </div>
                    </div>
            <div class="quick-stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-bar"></i>
                            </div>
                <div class="stat-content">
                    <div class="stat-label">Giá trị đơn hàng TB</div>
                    <div class="stat-value">{{ number_format($quickStats['avg_order_value'], 0, ',', '.') }} VNĐ</div>
                    </div>
                </div>
            </div>
                                        </div>
                </div>

<!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- AOS Animation -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<style>
/* Dashboard Styles */
.dashboard-container {
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}

.dashboard-header {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.dashboard-title {
    color: #2c3e50;
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.dashboard-title i {
    color: #667eea;
}

.date-filter-container {
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
}

.date-filter {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.date-filter label {
    font-size: 0.9rem;
    color: #666;
    font-weight: 500;
}

.date-input {
    padding: 8px 12px;
    border: 2px solid #e1e8ed;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.date-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

.date-range-display {
    margin-top: 15px;
    text-align: center;
}

.date-range-text {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

/* Loading Spinner */
.loading-spinner {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* KPI Cards */
.kpi-cards-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.kpi-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.kpi-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.kpi-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.card-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    font-size: 1.5rem;
    color: white;
}

.revenue-card .card-icon {
    background: linear-gradient(135deg, #667eea, #764ba2);
}

.orders-card .card-icon {
    background: linear-gradient(135deg, #f093fb, #f5576c);
}

.products-card .card-icon {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
}

.users-card .card-icon {
    background: linear-gradient(135deg, #43e97b, #38f9d7);
}

.card-title {
    font-size: 0.9rem;
    color: #666;
    margin: 0 0 10px 0;
    font-weight: 600;
}

.card-value {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 10px;
}

.card-growth {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.9rem;
    font-weight: 600;
}

.card-growth.positive {
    color: #28a745;
}

.card-growth.negative {
    color: #dc3545;
}

/* Charts Section */
.charts-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.chart-container {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.chart-header h3 {
    color: #2c3e50;
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.chart-controls {
    display: flex;
    gap: 10px;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 0.8rem;
}

.btn-outline-primary {
    border: 2px solid #667eea;
    color: #667eea;
    background: transparent;
}

.btn-outline-primary:hover {
    background: #667eea;
    color: white;
}

.btn-outline-secondary {
    border: 2px solid #6c757d;
    color: #6c757d;
    background: transparent;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
}

.chart-wrapper {
    position: relative;
    height: 300px;
}

/* Data Tables Section */
.data-tables-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(600px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.table-container {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.table-header h3 {
    color: #2c3e50;
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.table-controls {
    display: flex;
    gap: 10px;
}

.table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.table th {
    background: #f8f9fa;
    color: #495057;
    font-weight: 600;
    padding: 12px;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    padding: 12px;
    border-bottom: 1px solid #dee2e6;
    vertical-align: middle;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background: rgba(102, 126, 234, 0.05);
    transform: scale(1.01);
}

.product-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.product-image {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    object-fit: cover;
}

.product-details {
    display: flex;
    flex-direction: column;
}

.product-name {
    font-weight: 600;
    color: #2c3e50;
}

.product-price {
    font-size: 0.8rem;
    color: #666;
}

.customer-info {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #2c3e50;
}

.badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
}

.badge-info {
    background: #17a2b8;
    color: white;
}

.badge-success {
    background: #28a745;
    color: white;
}

.badge-danger {
    background: #dc3545;
    color: white;
}

.badge-warning {
    background: #ffc107;
    color: #212529;
}

.badge-primary {
    background: #007bff;
    color: white;
}

.badge-secondary {
    background: #6c757d;
    color: white;
}

/* Quick Stats Section */
.quick-stats-section {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
}

.quick-stats-section h3 {
    color: #2c3e50;
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0 0 20px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.quick-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.quick-stat-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.quick-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.stat-content {
    flex: 1;
}

.stat-label {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 5px;
}

.stat-value {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2c3e50;
}

/* Animations */
.table-row-animate {
    animation: slideInFromLeft 0.5s ease-out;
}

@keyframes slideInFromLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 10px;
    }
    
    .header-content {
        flex-direction: column;
        align-items: stretch;
    }
    
    .date-filter-container {
        justify-content: center;
    }
    
    .kpi-cards-container {
        grid-template-columns: 1fr;
    }
    
    .charts-section {
        grid-template-columns: 1fr;
    }
    
    .data-tables-section {
        grid-template-columns: 1fr;
    }
    
    .quick-stats-grid {
        grid-template-columns: 1fr;
    }
    
    .dashboard-title {
        font-size: 1.5rem;
    }
}
</style>

    <script>
// Initialize AOS
AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true
});

// Chart data
const chartData = {
    revenue: {
        labels: @json($monthlyRevenueData['labels']),
        revenues: @json($monthlyRevenueData['revenues']),
        orderCounts: @json($monthlyRevenueData['order_counts'])
    },
    orderStatus: {
        labels: @json($orderStatusData['labels']),
        values: @json($orderStatusData['values']),
        colors: @json($orderStatusData['colors'])
    }
};

// Initialize Charts
let revenueChart, orderStatusChart;

document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    initializeEventListeners();
    animateNumbers();
});

function initializeCharts() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
            labels: chartData.revenue.labels,
                datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: chartData.revenue.revenues,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                        fill: true,
                tension: 0.4
            }, {
                label: 'Số đơn hàng',
                data: chartData.revenue.orderCounts,
                borderColor: '#f093fb',
                backgroundColor: 'rgba(240, 147, 251, 0.1)',
                borderWidth: 3,
                fill: false,
                tension: 0.4,
                yAxisID: 'y1'
            }]
            },
            options: {
                responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
                plugins: {
                    legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Thống kê doanh thu và đơn hàng'
                    }
                },
                scales: {
                    y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Doanh thu (VNĐ)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Số đơn hàng'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                    }
                }
            }
        });

    // Order Status Chart
    const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
    orderStatusChart = new Chart(orderStatusCtx, {
        type: 'doughnut',
            data: {
            labels: chartData.orderStatus.labels,
                datasets: [{
                data: chartData.orderStatus.values,
                backgroundColor: chartData.orderStatus.colors,
                borderWidth: 2,
                borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
            maintainAspectRatio: false,
                plugins: {
                    legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Phân bố trạng thái đơn hàng'
                    }
                }
            }
        });
}

function initializeEventListeners() {
    // Date filter
    document.getElementById('apply-filter').addEventListener('click', function() {
        applyDateFilter();
    });

    document.getElementById('reset-filter').addEventListener('click', function() {
        resetDateFilter();
    });

    // Enter key on date inputs
    document.getElementById('start_date').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') applyDateFilter();
    });

    document.getElementById('end_date').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') applyDateFilter();
    });
}

function applyDateFilter() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;

    if (!startDate || !endDate) {
        alert('Vui lòng chọn cả ngày bắt đầu và ngày kết thúc!');
        return;
    }

    if (new Date(startDate) > new Date(endDate)) {
        alert('Ngày bắt đầu không thể lớn hơn ngày kết thúc!');
        return;
    }

    showLoading();
    
    const url = new URL(window.location);
    url.searchParams.set('start_date', startDate);
    url.searchParams.set('end_date', endDate);
    
    window.location.href = url.toString();
}

function resetDateFilter() {
    const url = new URL(window.location);
    url.searchParams.delete('start_date');
    url.searchParams.delete('end_date');
    
    window.location.href = url.toString();
}

function showLoading() {
    document.getElementById('loading-spinner').style.display = 'flex';
}

function animateNumbers() {
    const numberElements = document.querySelectorAll('.card-value');
    
    numberElements.forEach(element => {
        const finalValue = element.textContent;
        const numericValue = parseFloat(finalValue.replace(/[^\d.-]/g, ''));
        
        if (!isNaN(numericValue)) {
            animateNumber(element, 0, numericValue, 2000, finalValue);
        }
    });
}

function animateNumber(element, start, end, duration, finalText) {
    const startTime = performance.now();
    
    function updateNumber(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        const current = start + (end - start) * easeOutQuart(progress);
        
        if (finalText.includes('VNĐ')) {
            element.textContent = numberWithCommas(Math.floor(current)) + ' VNĐ';
        } else {
            element.textContent = numberWithCommas(Math.floor(current));
        }
        
        if (progress < 1) {
            requestAnimationFrame(updateNumber);
        } else {
            element.textContent = finalText;
        }
    }
    
    requestAnimationFrame(updateNumber);
}

function easeOutQuart(t) {
    return 1 - Math.pow(1 - t, 4);
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function downloadChart(chartId) {
    const canvas = document.getElementById(chartId);
    const link = document.createElement('a');
    link.download = chartId + '.png';
    link.href = canvas.toDataURL();
    link.click();
}

function printChart(chartId) {
    const canvas = document.getElementById(chartId);
    const win = window.open('', '_blank');
    win.document.write('<html><head><title>Print Chart</title></head><body>');
    win.document.write('<img src="' + canvas.toDataURL() + '" style="width: 100%; height: auto;">');
    win.document.write('</body></html>');
    win.document.close();
    win.print();
}

function exportTable(tableId) {
    const table = document.getElementById(tableId);
    const rows = Array.from(table.querySelectorAll('tr'));
    
    let csv = [];
    rows.forEach(row => {
        const cols = Array.from(row.querySelectorAll('td, th'));
        const rowData = cols.map(col => {
            // Remove HTML tags and get text content
            let text = col.textContent || col.innerText || '';
            // Escape quotes and wrap in quotes if contains comma
            if (text.includes(',') || text.includes('"')) {
                text = '"' + text.replace(/"/g, '""') + '"';
            }
            return text;
        });
        csv.push(rowData.join(','));
    });
    
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', tableId + '.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Auto refresh every 5 minutes
setInterval(function() {
    // Only refresh if user is not interacting
    if (!document.hasFocus()) {
        location.reload();
    }
}, 300000);
    </script>
@endsection
