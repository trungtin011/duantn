@extends('layouts.seller_home')

@section('title', 'Thống kê Click Quảng cáo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Thống kê Click Quảng cáo</h1>
        <p class="text-gray-600">Theo dõi hiệu quả chiến dịch quảng cáo của bạn</p>
    </div>

    <!-- Thống kê tổng quan -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-mouse-pointer text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tổng lượt click</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalClicks) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-bullhorn text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tổng chiến dịch</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalCampaigns) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-play-circle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Chiến dịch đang chạy</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($activeCampaigns) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tỷ lệ click trung bình</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $totalCampaigns > 0 ? number_format($totalClicks / $totalCampaigns, 1) : 0 }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ thống kê -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Biểu đồ click theo ngày -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Click theo ngày (7 ngày gần nhất)</h3>
            <canvas id="dailyChart" width="400" height="200"></canvas>
        </div>

        <!-- Biểu đồ click theo giờ -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Click theo giờ (24h gần nhất)</h3>
            <canvas id="hourlyChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Thống kê chi tiết -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Thống kê theo chiến dịch -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Click theo chiến dịch</h3>
            <div class="space-y-3">
                @foreach($campaignStats as $stat)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <span class="font-medium text-gray-800">Chiến dịch #{{ $stat->campaign_id }}</span>
                    </div>
                    <span class="text-lg font-bold text-blue-600">{{ number_format($stat->clicks) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Thống kê theo sản phẩm -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Top sản phẩm được click</h3>
            <div class="space-y-3">
                @foreach($productStats as $stat)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="font-medium text-gray-800 truncate">Sản phẩm #{{ $stat->product_id }}</span>
                    <span class="text-lg font-bold text-green-600">{{ number_format($stat->clicks) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Bảng chi tiết click -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        @if(session('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-700">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 rounded bg-red-100 text-red-700">{{ session('error') }}</div>
        @endif
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Chi tiết lượt click</h3>
            <div class="flex space-x-3 items-center">
                <form method="GET" class="flex space-x-3 items-center">
                    @php $period = request('period', 7); @endphp
                    <select name="period" class="border border-gray-300 rounded-lg px-3 py-2">
                        <option value="7" {{ $period == 7 ? 'selected' : '' }}>7 ngày qua</option>
                        <option value="30" {{ $period == 30 ? 'selected' : '' }}>30 ngày qua</option>
                        <option value="90" {{ $period == 90 ? 'selected' : '' }}>90 ngày qua</option>
                    </select>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Lọc
                    </button>
                </form>
                <a href="{{ route('seller.ad_click_stats.export', ['period' => $period]) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-download mr-2"></i>Export CSV
                </a>
                <form method="POST" action="{{ route('seller.ad_click_stats.settle') }}">
                    @csrf
                    <input type="hidden" name="period" value="{{ $period }}" />
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                        Trừ phí (₫1.000/click)
                    </button>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chiến dịch</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại click</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($clicks as $click)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ optional($click->clicked_at)->format('Y-m-d H:i:s') ?? $click->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $click->adsCampaign->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $click->click_type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $click->user_ip }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $click->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Chưa có dữ liệu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $clicks->appends(['period' => $period])->links() }}
        </div>
    </div>
</div>

<!-- Không dùng JS/AJAX/Chart.js theo yêu cầu -->
@endsection
