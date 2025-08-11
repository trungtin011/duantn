@extends('layouts.admin')

@section('title', 'Thống kê Cửa hàng')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 min-h-screen">
    <!-- Header -->
    <div class="bg-white rounded-xl p-6 mb-6 shadow-md">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-chart-bar text-indigo-600"></i>
                Thống kê Cửa hàng
            </h1>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.shops.index') }}" class="px-4 py-2 bg-gray-600 text-white font-semibold rounded-md shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-75 flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Tổng cửa hàng</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statusDistribution->sum('count') }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-store text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Cửa hàng mới (30 ngày)</p>
                    <p class="text-2xl font-bold text-green-600">{{ $shopGrowth->where('date', '>=', now()->subDays(30)->format('Y-m-d'))->sum('count') }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-plus text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Tỷ lệ duyệt</p>
                    @php
                        $totalShops = $statusDistribution->sum('count');
                        $activeShops = $statusDistribution->where('shop_status', 'active')->first()->count ?? 0;
                        $approvalRate = $totalShops > 0 ? round(($activeShops / $totalShops) * 100, 1) : 0;
                    @endphp
                    <p class="text-2xl font-bold text-indigo-600">{{ $approvalRate }}%</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                    <i class="fas fa-percentage text-indigo-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Tổng doanh thu</p>
                    @php
                        $totalRevenue = $topShops->sum('total_sales');
                    @endphp
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Shop Growth Chart -->
        <div class="bg-white rounded-xl p-6 shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tăng trưởng cửa hàng (6 tháng gần đây)</h3>
            <div class="relative h-64">
                <canvas id="shopGrowthChart"></canvas>
            </div>
        </div>

        <!-- Status Distribution Chart -->
        <div class="bg-white rounded-xl p-6 shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Phân bố trạng thái cửa hàng</h3>
            <div class="relative h-64">
                <canvas id="statusDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Performing Shops -->
    <div class="bg-white rounded-xl p-6 shadow-md mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Top 10 cửa hàng doanh thu cao nhất</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cửa hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chủ sở hữu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đánh giá</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doanh thu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topShops as $shop)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-lg object-cover" src="{{ $shop->shop_logo ? asset('storage/' . $shop->shop_logo) : asset('images/default-shop.png') }}" alt="{{ $shop->shop_name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $shop->shop_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $shop->owner->fullname ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="fas fa-star text-yellow-400 mr-1"></i>
                                <span class="text-sm text-gray-900">{{ number_format($shop->shop_rating, 1) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                            {{ number_format($shop->total_sales, 0, ',', '.') }} VNĐ
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $shop->total_products }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Không có dữ liệu cửa hàng.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Shop Registrations -->
    <div class="bg-white rounded-xl p-6 shadow-md">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Cửa hàng đăng ký gần đây</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cửa hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chủ sở hữu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đăng ký</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentShops as $shop)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-lg object-cover" src="{{ $shop->shop_logo ? asset('storage/' . $shop->shop_logo) : asset('images/default-shop.png') }}" alt="{{ $shop->shop_name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $shop->shop_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $shop->owner->fullname ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($shop->shop_status && $shop->shop_status->value == 'active')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Hoạt động
                                </span>
                            @elseif($shop->shop_status && $shop->shop_status->value == 'inactive')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Chờ duyệt
                                </span>
                            @elseif($shop->shop_status && $shop->shop_status->value == 'banned')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-ban mr-1"></i>Đã cấm
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    <i class="fas fa-question-circle mr-1"></i>Không xác định ({{ $shop->shop_status ? $shop->shop_status->value : 'null' }})
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $shop->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Không có cửa hàng nào đăng ký gần đây.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script type="application/json" id="shop-growth-data">
        {!! json_encode($shopGrowth) !!}
    </script>

    <script type="application/json" id="status-distribution-data">
        {!! json_encode($statusDistribution) !!}
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Parse data from hidden scripts
        const shopGrowthData = JSON.parse(document.getElementById('shop-growth-data').textContent);
        const statusDistributionData = JSON.parse(document.getElementById('status-distribution-data').textContent);

        // Shop Growth Chart
        const growthCtx = document.getElementById('shopGrowthChart').getContext('2d');
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: shopGrowthData.map(item => item.date),
                datasets: [{
                    label: 'Số cửa hàng mới',
                    data: shopGrowthData.map(item => item.count),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Status Distribution Chart
        const statusCtx = document.getElementById('statusDistributionChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusDistributionData.map(item => item.shop_status),
                datasets: [{
                    data: statusDistributionData.map(item => item.count),
                    backgroundColor: [
                        '#10b981', // green for active
                        '#f59e0b', // yellow for inactive
                        '#ef4444'  // red for banned
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
    </script>
@endsection 