@php
    use Carbon\Carbon;
@endphp
@extends('layouts.seller_home')
@push('css')
    <style>
        .stat-card {
            @apply border rounded p-3 text-center;
        }

        .stat-value {
            @apply text-blue-600 font-semibold text-xl;
        }

        .stat-label {
            @apply text-gray-600 text-xs;
        }

        .form-group {
            @apply mb-2;
        }

        .error-message {
            @apply text-red-500 text-xs mt-2;
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
@endpush
@section('title', 'Trang chủ Seller')
@section('content')
    <div class="flex-1 space-y-6 overflow-y-auto">
        <!-- Error Messages -->
        @if (session('error') || $error)
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') ?? $error }}</span>
            </div>
        @endif

        <!-- Danh sách cần làm -->
        <section
            class="bg-white rounded-lg px-4 shadow-sm flex flex-col sm:flex-row justify-between text-center sm:text-left space-y-4 sm:space-y-0 sm:space-x-10">
            <h2
                class="text-lg font-semibold w-full h-[88px] flex items-center sm:w-auto sm:flex-shrink-0 sm:self-center border-r pr-5 border-gray-400 border-dashed">
                Thống kê đơn hàng
            </h2>
            <div class="flex justify-around sm:justify-start flex-1 space-x-10 text-gray-600 text-xs my-4">
                <div>
                    <div class="stat-value text-sm">{{ $statistics['order_statistics']['pending'] }}</div>
                    <div class="stat-label text-sm">Chờ Xác Nhận</div>
                </div>
                <div>
                    <div class="stat-value text-sm">{{ $statistics['order_statistics']['completed'] }}</div>
                    <div class="stat-label text-sm">Đã Hoàn Thành</div>
                </div>
                <div>
                    <div class="stat-value text-sm">
                        {{ $statistics['order_statistics']['cancelled'] + $statistics['order_statistics']['returned'] }}
                    </div>
                    <div class="stat-label text-sm">Đơn Trả Hàng/Hủy</div>
                </div>
                <div>
                    <div class="stat-value text-sm">{{ $lowStockCount }}</div>
                    <div class="stat-label text-sm">Sản Phẩm Sắp Hết Hàng</div>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('seller.order.index') }}" class="text-[#f42f46] hover:underline ">
                    Xem thêm <i class="fas fa-chevron-right text-[10px]"></i>
                </a>
                <div class="mt-2">
                    <a href="{{ route('seller.ad_click_stats.index') }}"
                       class="inline-flex items-center text-xs bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded-md">
                        <i class="fas fa-bullhorn mr-2"></i>
                        Thống kê click quảng cáo
                    </a>
                </div>
            </div>
        </section>

        <div class="flex gap-[24px]">
            <!-- Phân Tích Bán Hàng -->
            <section class="bg-white rounded-lg p-4 shadow-sm space-y-3">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold">Phân Tích Bán Hàng</h2>
                    <div class="flex flex-col items-end space-y-2">
                        <form method="GET" action="{{ route('seller.dashboard') }}" id="dateFilterForm"
                            class="bg-gray-50 p-3 rounded-lg">
                            <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-2 lg:space-y-0">
                                <div class="flex-1">
                                    <div class="form-group">
                                        <label for="filter_type" class="text-xs text-gray-600 font-medium">Loại bộ
                                            lọc:</label>
                                        <select name="filter_type" id="filter_type"
                                            class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="date" {{ $filterType == 'date' ? 'selected' : '' }}>Theo ngày
                                            </option>
                                            <option value="month" {{ $filterType == 'month' ? 'selected' : '' }}>Theo tháng
                                            </option>
                                            <option value="year" {{ $filterType == 'year' ? 'selected' : '' }}>Theo năm
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="flex-1" id="year_group">
                                    <div class="form-group">
                                        <label for="year" class="text-xs text-gray-600 font-medium">Năm:</label>
                                        <select name="year" id="year"
                                            class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            @for ($y = Carbon::today()->year; $y >= Carbon::today()->year - 5; $y--)
                                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                                    {{ $y }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="flex-1" id="month_group">
                                    <div class="form-group">
                                        <label for="month" class="text-xs text-gray-600 font-medium">Tháng:</label>
                                        <select name="month" id="month"
                                            class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            @for ($m = 1; $m <= ($year == Carbon::today()->year ? Carbon::today()->month : 12); $m++)
                                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                                    {{ $m }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="flex-1" id="start_date_group">
                                    <div class="form-group">
                                        <label for="start_date" class="text-xs text-gray-600 font-medium">Từ ngày:</label>
                                        <input type="date" name="start_date" id="start_date"
                                            value="{{ $filterType == 'date' ? Carbon::parse($startDate)->format('Y-m-d') : '' }}"
                                            max="{{ Carbon::today()->format('Y-m-d') }}"
                                            class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                                <div class="flex-1" id="end_date_group">
                                    <div class="form-group">
                                        <label for="end_date" class="text-xs text-gray-600 font-medium">Đến ngày:</label>
                                        <input type="date" name="end_date" id="end_date"
                                            value="{{ $filterType == 'date' ? Carbon::parse($endDate)->format('Y-m-d') : '' }}"
                                            max="{{ Carbon::today()->format('Y-m-d') }}"
                                            class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                                <div class="flex items-end">
                                    <button type="submit"
                                        class="text-xs text-white bg-blue-600 rounded-md px-4 py-2 hover:bg-blue-700 transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        <i class="fas fa-filter mr-1"></i>Lọc
                                    </button>
                                </div>
                            </div>
                            <div id="dateError" class="error-message hidden mt-2"></div>
                        </form>
                        <div class="text-xs text-gray-500 whitespace-nowrap">
                            <i class="fas fa-clock mr-1"></i>{{ now()->format('d/m/Y H:i') }} GMT+7
                            <span class="text-gray-400 ml-1">(Cập nhật thời gian thực)</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-sm font-semibold text-gray-700">Biểu đồ doanh thu</h3>
                        <div class="text-xs text-gray-500">
                            {{ $filterType == 'year' ? $year : Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="relative">
                        <canvas id="salesChart" height="500"></canvas>
                    </div>
                </div>
                <div
                    class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-center text-gray-600 text-xs font-normal border-t border-b border-gray-200 py-3">
                    <div>
                        <div class="flex justify-center items-center space-x-1">
                            <span>Doanh thu</span>
                            <i class="fas fa-chart-line text-[10px] text-green-500"></i>
                        </div>
                        <div class="font-semibold text-base mt-1 sales text-green-600">
                            ₫{{ number_format($statistics['total_revenue'], 2) }}
                        </div>
                        <div class="text-gray-400 text-[10px] mt-0.5">{{ $statistics['revenue_change'] ?? '-0.00%' }}
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-center items-center space-x-1">
                            <span>Lợi nhuận</span>
                            <i class="fas fa-coins text-[10px] text-blue-500"></i>
                        </div>
                        <div class="font-semibold text-base mt-1 profit text-blue-600">
                            ₫{{ number_format($statistics['profit'], 2) }}
                        </div>
                        <div class="text-gray-400 text-[10px] mt-0.5">{{ $statistics['profit_change'] ?? '-0.00%' }}</div>
                    </div>
                    <div>
                        <div class="flex justify-center items-center space-x-1">
                            <span>Lượt theo dõi</span>
                            <i class="fas fa-users text-[10px] text-purple-500"></i>
                        </div>
                        <div class="font-semibold text-base mt-1 visits text-purple-600">
                            {{ $statistics['followers_count'] ?? 0 }}
                        </div>
                        <div class="text-gray-400 text-[10px] mt-0.5">{{ $statistics['visits_change'] ?? '-0.00%' }}</div>
                    </div>
                    <div>
                        <div class="flex justify-center items-center space-x-1">
                            <span>Đơn hàng</span>
                            <i class="fas fa-shopping-cart text-[10px] text-orange-500"></i>
                        </div>
                        <div class="font-semibold text-base mt-1 orders text-orange-600">
                            {{ $statistics['completed_orders_count'] ?? 0 }}
                        </div>
                        <div class="text-gray-400 text-[10px] mt-0.5">{{ $statistics['orders_change'] ?? '-0.00%' }}</div>
                    </div>
                </div>
            </section>

            <!-- Đánh giá khách hàng -->
            <section class="bg-white rounded-lg p-4 shadow-sm space-y-10">
                <div class="">
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="section-title">Đánh giá khách hàng</h2>
                        <div class="">
                            <a href="{{ route('seller.reviews.index') }}" class="text-[#f42f46] hover:underline ">
                                Xem thêm <i class="fas fa-chevron-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-center text-gray-600 text-xs font-normal border-t border-b border-gray-200 py-3">
                        <div>
                            <div class="flex justify-center items-center space-x-1">
                                <span>Số lượng đánh giá</span>
                                <i class="fas fa-star text-[10px]"></i>
                            </div>
                            <div class="font-semibold text-base mt-1">
                                {{ $statistics['review_statistics']['total_reviews'] }}
                            </div>
                            <div class="text-gray-400 text-[10px] mt-0.5">Tổng cộng</div>
                        </div>
                        <div>
                            <div class="flex justify-center items-center space-x-1">
                                <span>Điểm trung bình</span>
                                <i class="fas fa-star text-[10px]"></i>
                            </div>
                            <div class="font-semibold text-base mt-1">
                                {{ number_format($statistics['review_statistics']['average_rating'], 1) }}/5</div>
                            <div class="text-gray-400 text-[10px] mt-0.5">Đánh giá</div>
                        </div>
                    </div>
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-sm font-semibold text-gray-700">Phân bố đánh giá</h3>
                            <div class="text-xs text-gray-500">Tồn kho:
                                {{ $statistics['inventory_statistics']['total_stock'] }} sản phẩm</div>
                        </div>
                        <div class="relative">
                            <canvas id="reviewChart" height="120"></canvas>
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="section-title">Phân tích tồn kho</h2>
                        <div class="">
                            <a href="{{ route('seller.products.index') }}" class="text-[#f42f46] hover:underline ">
                                Xem thêm <i class="fas fa-chevron-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                    <div
                        class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-center text-gray-600 text-xs font-normal border-t border-b border-gray-200 py-3">
                        <div>
                            <div class="flex justify-center space-x-1">
                                <span>Tổng sản phẩm</span>
                                <i class="fas fa-boxes text-[10px]"></i>
                            </div>
                            <div class="font-semibold text-base mt-1">
                                {{ $statistics['inventory_statistics']['total_products'] ?? 0 }}</div>
                            <div class="text-gray-400 text-[10px] mt-0.5">Sản phẩm</div>
                        </div>
                        <div>
                            <div class="flex justify-center items-center space-x-1">
                                <span>Tổng tồn kho</span>
                                <i class="fas fa-warehouse text-[10px]"></i>
                            </div>
                            <div class="font-semibold text-base mt-1">
                                {{ $statistics['inventory_statistics']['total_stock'] }}</div>
                            <div class="text-gray-400 text-[10px] mt-0.5">Đơn vị</div>
                        </div>
                        <div>
                            <div class="flex justify-center items-center space-x-1">
                                <span>Sắp hết hàng</span>
                                <i class="fas fa-exclamation-triangle text-[10px]"></i>
                            </div>
                            <div class="font-semibold text-base mt-1 text-orange-600">{{ $lowStockCount }}</div>
                            <div class="text-gray-400 text-[10px] mt-0.5">Sản phẩm</div>
                        </div>
                        <div>
                            <div class="flex justify-center items-center space-x-1">
                                <span>Hết hàng</span>
                                <i class="fas fa-times-circle text-[10px]"></i>
                            </div>
                            <div class="font-semibold text-base mt-1 text-red-600">
                                {{ $statistics['inventory_statistics']['out_of_stock'] ?? 0 }}</div>
                            <div class="text-gray-400 text-[10px] mt-0.5">Sản phẩm</div>
                        </div>
                    </div>
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-sm font-semibold text-gray-700">Tình trạng tồn kho</h3>
                            <div class="text-xs text-gray-500">Cập nhật: {{ now()->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="relative">
                            <canvas id="inventoryChart" height="120"></canvas>
                        </div>
                    </div>
                </div>
            </section>
        </div>


        <!-- Sản Phẩm Bán Chạy -->
        <section class="bg-white rounded-lg p-4 shadow-sm space-y-3">
            <div class="flex justify-between items-center">
                <h2 class="section-title">Sản Phẩm Bán Chạy</h2>
                <a href="{{ route('seller.products.index') }}" class="text-[#f42f46] hover:underline ">
                    Xem thêm
                    <i class="fas fa-chevron-right text-[10px]"></i></a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sản phẩm
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                SKU
                            </th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Đã bán
                            </th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Doanh thu
                            </th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Lợi nhuận
                            </th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tồn kho
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($statistics['top_selling_products'] as $product)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img src="{{ $product['image_path'] ?? 'https://placehold.co/50x50' }}"
                                            alt="Product image" class="w-10 h-10 rounded object-cover mr-3">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $product['name'] }}</div>
                                            <div class="text-xs text-gray-500">
                                                ID: {{ $product['product_id'] }}
                                                @if ($product['is_variant'] ?? false)
                                                    <span
                                                        class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <i class="fas fa-tag mr-1"></i>Biến thể
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                    {{ $product['sku'] }}
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $product['total_sold'] }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap text-center text-sm text-gray-900">
                                    ₫{{ number_format($product['total_revenue'], 0) }}
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap text-center text-sm text-gray-900">
                                    ₫{{ number_format($product['total_profit'], 0) }}
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap text-center">
                                    @if ($product['stock_total'] <= 10)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ $product['stock_total'] }}
                                        </span>
                                    @elseif($product['stock_total'] <= 50)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ $product['stock_total'] }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $product['stock_total'] }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-4 text-center text-sm text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-box-open text-2xl text-gray-300 mb-2"></i>
                                        Chưa có sản phẩm bán chạy
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Initialize sales chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            let salesChart = new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: @json($statistics['revenue_data']['labels']),
                    datasets: [{
                        label: 'Doanh thu',
                        data: @json($statistics['revenue_data']['values']),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return '₫' + value.toLocaleString('vi-VN');
                                },
                                font: {
                                    size: 11,
                                    family: 'Inter, sans-serif'
                                },
                                color: '#6b7280'
                            },
                            title: {
                                display: true,
                                text: 'Doanh thu (VND)',
                                font: {
                                    size: 12,
                                    family: 'Inter, sans-serif'
                                },
                                color: '#374151'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            title: {
                                display: true,
                                text: '{{ $filterType == 'year' ? 'Tháng' : 'Ngày' }}',
                                font: {
                                    size: 12,
                                    family: 'Inter, sans-serif'
                                },
                                color: '#374151'
                            },
                            ticks: {
                                font: {
                                    size: 11,
                                    family: 'Inter, sans-serif'
                                },
                                color: '#6b7280'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#ffffff',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                title: function(tooltipItems) {
                                    return tooltipItems[0].label;
                                },
                                label: function(context) {
                                    return `Doanh thu: ₫${context.parsed.y.toLocaleString('vi-VN')}`;
                                }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });

            // Initialize review chart
            const reviewCtx = document.getElementById('reviewChart').getContext('2d');
            new Chart(reviewCtx, {
                type: 'doughnut',
                data: {
                    labels: ['5 sao', '4 sao', '3 sao', '2 sao', '1 sao'],
                    datasets: [{
                        data: [
                            {{ $statistics['review_statistics']['rating_distribution']['5'] }},
                            {{ $statistics['review_statistics']['rating_distribution']['4'] }},
                            {{ $statistics['review_statistics']['rating_distribution']['3'] }},
                            {{ $statistics['review_statistics']['rating_distribution']['2'] }},
                            {{ $statistics['review_statistics']['rating_distribution']['1'] }}
                        ],
                        backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#f97316', '#ef4444'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    size: 11,
                                    family: 'Inter, sans-serif'
                                },
                                color: '#374151'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#ffffff',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                    return `${context.label}: ${context.parsed} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // Initialize inventory chart
            const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
            new Chart(inventoryCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Còn hàng', 'Sắp hết hàng', 'Hết hàng'],
                    datasets: [{
                        data: [
                            {{ ($statistics['inventory_statistics']['total_products'] ?? 0) - ($lowStockCount + ($statistics['inventory_statistics']['out_of_stock'] ?? 0)) }},
                            {{ $lowStockCount }},
                            {{ $statistics['inventory_statistics']['out_of_stock'] ?? 0 }}
                        ],
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    size: 11,
                                    family: 'Inter, sans-serif'
                                },
                                color: '#374151'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#ffffff',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                    return `${context.label}: ${context.parsed} sản phẩm (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // Client-side form handling
            document.getElementById('filter_type').addEventListener('change', function() {
                const filterType = this.value;
                const yearGroup = document.getElementById('year_group');
                const monthGroup = document.getElementById('month_group');
                const startDateGroup = document.getElementById('start_date_group');
                const endDateGroup = document.getElementById('end_date_group');

                yearGroup.style.display = filterType === 'year' || filterType === 'month' ? 'block' : 'none';
                monthGroup.style.display = filterType === 'month' ? 'block' : 'none';
                startDateGroup.style.display = filterType === 'date' ? 'block' : 'none';
                endDateGroup.style.display = filterType === 'date' ? 'block' : 'none';

                // Clear date inputs when switching to year or month
                if (filterType !== 'date') {
                    document.getElementById('start_date').value = '';
                    document.getElementById('end_date').value = '';
                }

                // Update month dropdown based on selected year
                updateMonthOptions();
            });

            // Update month dropdown based on year selection
            document.getElementById('year').addEventListener('change', function() {
                updateMonthOptions();
            });

            function updateMonthOptions() {
                const year = parseInt(document.getElementById('year').value);
                const currentYear = {{ Carbon::today()->year }};
                const currentMonth = {{ Carbon::today()->month }};
                const monthSelect = document.getElementById('month');
                const selectedMonth = monthSelect.value;

                // Clear current options
                monthSelect.innerHTML = '';

                // Set max month based on year
                const maxMonth = (year === currentYear) ? currentMonth : 12;

                // Populate month options
                for (let m = 1; m <= maxMonth; m++) {
                    const option = document.createElement('option');
                    option.value = m;
                    option.textContent = m;
                    if (m == selectedMonth) {
                        option.selected = true;
                    }
                    monthSelect.appendChild(option);
                }
            }

            // Client-side date validation
            document.getElementById('dateFilterForm').addEventListener('submit', function(e) {
                const filterType = document.getElementById('filter_type').value;
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');
                const yearInput = document.getElementById('year');
                const monthInput = document.getElementById('month');
                const errorDiv = document.getElementById('dateError');
                const today = new Date('{{ Carbon::today()->format('Y-m-d') }}');

                // Reset error message
                errorDiv.classList.add('hidden');
                errorDiv.textContent = '';

                if (filterType === 'date') {
                    const startDate = new Date(startDateInput.value);
                    const endDate = new Date(endDateInput.value);

                    // Check for end date before start date
                    if (endDate < startDate) {
                        e.preventDefault();
                        errorDiv.textContent = 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.';
                        errorDiv.classList.remove('hidden');
                        return;
                    }

                    // Check for date range exceeding 31 days
                    const diffDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
                    if (diffDays > 31) {
                        e.preventDefault();
                        errorDiv.textContent = 'Khoảng thời gian được chọn không được vượt quá 31 ngày.';
                        errorDiv.classList.remove('hidden');
                        return;
                    }
                } else if (filterType === 'month') {
                    const year = parseInt(yearInput.value);
                    const month = parseInt(monthInput.value);
                    const currentYear = {{ Carbon::today()->year }};
                    const currentMonth = {{ Carbon::today()->month }};

                    // Prevent selecting future months
                    if (year > currentYear || (year === currentYear && month > currentMonth)) {
                        e.preventDefault();
                        errorDiv.textContent = 'Không thể chọn tháng trong tương lai.';
                        errorDiv.classList.remove('hidden');
                        monthInput.value = '';
                        return;
                    }
                } else if (filterType === 'year') {
                    const year = parseInt(yearInput.value);
                    const currentYear = {{ Carbon::today()->year }};

                    // Prevent selecting future years
                    if (year > currentYear) {
                        e.preventDefault();
                        errorDiv.textContent = 'Không thể chọn năm trong tương lai.';
                        errorDiv.classList.remove('hidden');
                        yearInput.value = '';
                        return;
                    }
                }
            });

            // Trigger change event on page load to set initial visibility and month options
            document.getElementById('filter_type').dispatchEvent(new Event('change'));
            updateMonthOptions();
        </script>
    @endpush
@endsection
