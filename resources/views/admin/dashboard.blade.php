@extends('layouts.admin')

@section('title', 'Thống kê')

@section('content')
    <div class="p-4 sm:p-6 lg:p-8 min-h-screen">
        <!-- Header với bộ lọc ngày -->
        <div class="bg-white rounded-xl p-5 mb-6 shadow-md">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-chart-line text-indigo-600"></i>
                    Thống kê
                </h1>
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex flex-col">
                        <label for="start_date" class="text-sm text-gray-600 font-medium">Từ ngày:</label>
                        <input type="date" id="start_date" name="start_date" value="{{ $dateRange['start'] ?? '' }}"
                            class="p-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="flex flex-col">
                        <label for="end_date" class="text-sm text-gray-600 font-medium">Đến ngày:</label>
                        <input type="date" id="end_date" name="end_date" value="{{ $dateRange['end'] ?? '' }}"
                            class="p-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <button id="apply-filter"
                        class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-75 flex items-center gap-2">
                        <i class="fas fa-filter"></i>
                        Áp dụng
                    </button>
                    <button id="reset-filter"
                        class="px-4 py-2 bg-gray-600 text-white font-semibold rounded-md shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-75 flex items-center gap-2">
                        <i class="fas fa-undo"></i>
                        Reset
                    </button>
                </div>
            </div>
            <div class="mt-4 text-center">
                <span
                    class="inline-flex items-center gap-2 bg-indigo-100 text-indigo-700 px-4 py-2 rounded-full font-semibold text-sm">
                    <i class="fas fa-calendar-alt"></i>
                    {{ $dateRange['start_formatted'] ?? '01/01/2024' }} - {{ $dateRange['end_formatted'] ?? '31/12/2024' }}
                </span>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div id="loading-spinner"
            class="fixed inset-0 bg-white bg-opacity-90 flex flex-col justify-center items-center z-[9999]"
            style="display: none;">
            <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-indigo-600"></div>
            <p class="mt-3 text-gray-700">Đang tải dữ liệu...</p>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div
                class="bg-white rounded-xl p-6 shadow-md transition-transform duration-300 hover:scale-[1.02] relative overflow-hidden group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                <div class="flex items-center space-x-4">
                    <div
                        class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-xl">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-600 font-semibold mb-1">Tổng Doanh Thu</h3>
                        <div class="text-3xl font-bold text-gray-900" id="total-revenue">
                            {{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</div>
                        <div
                            class="flex items-center text-sm font-medium mt-1 {{ $revenueGrowth >= 0 ? 'text-green-500' : 'text-red-500' }}">
                            <i class="fas fa-{{ $revenueGrowth >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                            {{ number_format(abs($revenueGrowth), 1) }}%
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-xl p-6 shadow-md transition-transform duration-300 hover:scale-[1.02] relative overflow-hidden group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-pink-500 to-red-500"></div>
                <div class="flex items-center space-x-4">
                    <div
                        class="w-14 h-14 rounded-full bg-gradient-to-br from-pink-500 to-red-500 flex items-center justify-center text-white text-xl">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-600 font-semibold mb-1">Tổng Đơn Hàng</h3>
                        <div class="text-3xl font-bold text-gray-900" id="total-orders">
                            {{ number_format($totalOrders, 0, ',', '.') }}</div>
                        <div
                            class="flex items-center text-sm font-medium mt-1 {{ $orderGrowth >= 0 ? 'text-green-500' : 'text-red-500' }}">
                            <i class="fas fa-{{ $orderGrowth >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                            {{ number_format(abs($orderGrowth), 1) }}%
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-xl p-6 shadow-md transition-transform duration-300 hover:scale-[1.02] relative overflow-hidden group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-400 to-cyan-400"></div>
                <div class="flex items-center space-x-4">
                    <div
                        class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-400 to-cyan-400 flex items-center justify-center text-white text-xl">
                        <i class="fas fa-box"></i>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-600 font-semibold mb-1">Sản Phẩm Đang Bán</h3>
                        <div class="text-3xl font-bold text-gray-900" id="total-products">
                            {{ number_format($totalProducts, 0, ',', '.') }}</div>
                        <div
                            class="flex items-center text-sm font-medium mt-1 {{ $productGrowth >= 0 ? 'text-green-500' : 'text-red-500' }}">
                            <i class="fas fa-{{ $productGrowth >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                            {{ number_format(abs($productGrowth), 1) }}%
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-xl p-6 shadow-md transition-transform duration-300 hover:scale-[1.02] relative overflow-hidden group">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-green-400 to-teal-400"></div>
                <div class="flex items-center space-x-4">
                    <div
                        class="w-14 h-14 rounded-full bg-gradient-to-br from-green-400 to-teal-400 flex items-center justify-center text-white text-xl">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-600 font-semibold mb-1">Tổng Người Dùng</h3>
                        <div class="text-3xl font-bold text-gray-900" id="total-users">
                            {{ number_format($totalUsers, 0, ',', '.') }}</div>
                        <div
                            class="flex items-center text-sm font-medium mt-1 {{ $userGrowth >= 0 ? 'text-green-500' : 'text-red-500' }}">
                            <i class="fas fa-{{ $userGrowth >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                            {{ number_format(abs($userGrowth), 1) }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2"><i
                            class="fas fa-chart-line"></i> Biểu Đồ Doanh Thu Theo Tháng</h3>
                    <div class="flex gap-2">
                        <button
                            class="px-3 py-1.5 text-sm border border-indigo-500 text-indigo-600 rounded-md hover:bg-indigo-50 transition-colors duration-200"
                            onclick="downloadChart('revenueChart')">
                            <i class="fas fa-download mr-1"></i> Tải xuống
                        </button>
                        <button
                            class="px-3 py-1.5 text-sm border border-gray-400 text-gray-700 rounded-md hover:bg-gray-100 transition-colors duration-200"
                            onclick="printChart('revenueChart')">
                            <i class="fas fa-print mr-1"></i> In
                        </button>
                    </div>
                </div>
                <div class="relative h-72">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2"><i class="fas fa-chart-pie"></i>
                        Phân Bố Trạng Thái Đơn Hàng</h3>
                    <div class="flex gap-2">
                        <button
                            class="px-3 py-1.5 text-sm border border-indigo-500 text-indigo-600 rounded-md hover:bg-indigo-50 transition-colors duration-200"
                            onclick="downloadChart('orderStatusChart')">
                            <i class="fas fa-download mr-1"></i> Tải xuống
                        </button>
                        <button
                            class="px-3 py-1.5 text-sm border border-gray-400 text-gray-700 rounded-md hover:bg-gray-100 transition-colors duration-200"
                            onclick="printChart('orderStatusChart')">
                            <i class="fas fa-print mr-1"></i> In
                        </button>
                    </div>
                </div>
                <div class="relative h-72">
                    <canvas id="orderStatusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Data Tables Section -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2"><i class="fas fa-star"></i>
                        Sản Phẩm Bán Chạy Nhất</h3>
                    <button
                        class="px-3 py-1.5 text-sm border border-indigo-500 text-indigo-600 rounded-md hover:bg-indigo-50 transition-colors duration-200"
                        onclick="XuấtTable('top-products-table')">
                        <i class="fas fa-file-excel mr-1"></i> Xuất
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="top-products-table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sản phẩm</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Đã bán</th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Doanh thu</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tồn kho</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($topSellingProducts as $product)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-md object-cover"
                                                    src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ Str::limit($product['name'], 30) }}</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ number_format($product['price'], 0, ',', '.') }} VNĐ</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ number_format($product['sold_quantity']) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                        {{ number_format($product['revenue'], 0, ',', '.') }} VNĐ
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product['stock_total'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $product['stock_total'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product['stock_total'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $product['stock_total'] > 0 ? 'Còn hàng' : 'Hết hàng' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if(method_exists($topSellingProducts, 'links'))
                        <div class="mt-3">
                            {{ $topSellingProducts->appends(request()->except('top_products_page'))->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2"><i class="fas fa-clock"></i>
                        Đơn Hàng Gần Đây</h3>
                    <button
                        class="px-3 py-1.5 text-sm border border-indigo-500 text-indigo-600 rounded-md hover:bg-indigo-50 transition-colors duration-200"
                        onclick="XuấtTable('recent-orders-table')">
                        <i class="fas fa-file-excel mr-1"></i> Xuất
                    </button>
                </div>
                <div class="overflow-x-auto h-[273px]">
                    <table class="min-w-full h-full divide-y divide-gray-200" id="recent-orders-table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mã đơn</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Khách hàng</th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tổng tiền</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Trạng thái</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($recentOrders as $order)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $order['order_code'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <i class="fas fa-user mr-2 text-gray-400"></i>
                                            {{ $order['customer_name'] }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                        {{ number_format($order['total_price'], 0, ',', '.') }} VNĐ
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if ($order['order_status_badge'] == 'success') bg-green-100 text-green-800
                                            @elseif($order['order_status_badge'] == 'danger') bg-red-100 text-red-800
                                            @elseif($order['order_status_badge'] == 'warning') bg-yellow-100 text-yellow-800
                                            @elseif($order['order_status_badge'] == 'info') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @switch($order['order_status_label'])
                                                @case('pending')
                                                    Đang chờ xử lý
                                                    @break
                                                @case('confirmed')
                                                    Đã xác nhận
                                                    @break
                                                @case('processing')
                                                    Đang xử lý
                                                    @break
                                                @case('shipped')
                                                    Đã gửi hàng
                                                    @break
                                                @case('delivered')
                                                    Đã giao hàng
                                                    @break
                                                @case('cancelled')
                                                    Đã hủy
                                                    @break
                                                @case('refunded')
                                                    Đã hoàn tiền
                                                    @break
                                                @case('completed')
                                                    Hoàn thành
                                                    @break
                                                @case('failed')
                                                    Thất bại
                                                    @break
                                                @default
                                                    {{ $order['order_status_label'] }}
                                            @endswitch
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                        {{ $order['created_at'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if(method_exists($recentOrders, 'links'))
                        <div class="mt-3">
                            {{ $recentOrders->appends(request()->except('recent_orders_page'))->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Stats Section -->
        <div class="bg-white rounded-xl p-6 shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2"><i
                    class="fas fa-tachometer-alt"></i> Thống Kê Nhanh</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg shadow-sm">
                    <div
                        class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-400 to-blue-400 flex items-center justify-center text-white text-lg">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Doanh thu trong kỳ</div>
                        <div class="text-xl font-bold text-gray-900">
                            {{ number_format($quickStats['period_revenue'], 0, ',', '.') }} VNĐ</div>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg shadow-sm">
                    <div
                        class="w-12 h-12 rounded-full bg-gradient-to-br from-pink-400 to-red-400 flex items-center justify-center text-white text-lg">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Đơn hàng trong kỳ</div>
                        <div class="text-xl font-bold text-gray-900">
                            {{ number_format($quickStats['period_orders'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg shadow-sm">
                    <div
                        class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-teal-400 flex items-center justify-center text-white text-lg">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Người dùng mới</div>
                        <div class="text-xl font-bold text-gray-900">
                            {{ number_format($quickStats['period_users'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg shadow-sm">
                    <div
                        class="w-12 h-12 rounded-full bg-gradient-to-br from-yellow-400 to-orange-400 flex items-center justify-center text-white text-lg">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Giá trị đơn hàng TB</div>
                        <div class="text-xl font-bold text-gray-900">
                            {{ number_format($quickStats['avg_order_value'], 0, ',', '.') }} VNĐ</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <!-- AOS Animation -->
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

        <script type="application/json" id="monthly-revenue-data">
        {!! json_encode($monthlyRevenueData) !!}
    </script>

        <script type="application/json" id="order-status-data">
        {!! json_encode($orderStatusData) !!}
    </script>

        <script>
            // Initialize AOS
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });

            // Chart data
            const monthlyRevenueData = JSON.parse(document.getElementById('monthly-revenue-data').textContent);
            const orderStatusData = JSON.parse(document.getElementById('order-status-data').textContent);

            const chartData = {
                revenue: {
                    labels: monthlyRevenueData.labels,
                    revenues: monthlyRevenueData.revenues,
                    orderCounts: monthlyRevenueData.order_counts
                },
                orderStatus: {
                    labels: orderStatusData.labels,
                    values: orderStatusData.values,
                    colors: orderStatusData.colors
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
                            borderColor: '#6366f1', // indigo-500
                            backgroundColor: 'rgba(99, 102, 241, 0.1)', // indigo-500 with opacity
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }, {
                            label: 'Số đơn hàng',
                            data: chartData.revenue.orderCounts,
                            borderColor: '#ec4899', // pink-500
                            backgroundColor: 'rgba(236, 72, 153, 0.1)', // pink-500 with opacity
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
                const blob = new Blob([csvContent], {
                    type: 'text/csv;charset=utf-8;'
                });
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
