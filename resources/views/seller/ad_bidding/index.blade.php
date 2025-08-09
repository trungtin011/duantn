@extends('layouts.seller_home')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Quản Lý Đấu Giá Quảng Cáo</h1>
                <p class="text-gray-600 mt-2">Quản lý giá thầu và thống kê quảng cáo của shop</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('seller.ad_bidding.stats') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i>Thống Kê
                </a>
                <a href="{{ route('seller.ads_campaigns.index') }}" 
                   class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Tạo Chiến Dịch
                </a>
            </div>
        </div>
    </div>

    <!-- Thống kê tổng quan -->
    @if($stats)
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-campaign text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">Tổng chiến dịch</p>
                    <p class="text-xl font-bold text-gray-800">{{ $stats->total_campaigns ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-gavel text-green-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">Giá thầu TB</p>
                    <p class="text-xl font-bold text-gray-800">{{ number_format($stats->avg_bid ?? 0) }}đ</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-eye text-yellow-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">Lượt xem</p>
                    <p class="text-xl font-bold text-gray-800">{{ number_format($stats->total_impressions ?? 0) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-money-bill text-red-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">Đã chi</p>
                    <p class="text-xl font-bold text-gray-800">{{ number_format($stats->total_spent ?? 0) }}đ</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Danh sách chiến dịch -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Chiến Dịch Quảng Cáo</h2>
        </div>

        @if($campaigns->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Chiến Dịch
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Giá Thầu
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thống Kê
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Trạng Thái
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thao Tác
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($campaigns as $campaign)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $campaign->name }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $campaign->adsCampaignItems->count() }} sản phẩm
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $campaign->start_date->format('d/m/Y') }} - {{ $campaign->end_date->format('d/m/Y') }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-lg font-bold text-blue-600">{{ number_format($campaign->bid_amount) }}đ</span>
                                @if($campaign->bid_amount >= 1000)
                                    <span class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Cao</span>
                                @elseif($campaign->bid_amount >= 500)
                                    <span class="ml-2 px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Trung bình</span>
                                @else
                                    <span class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Thấp</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <div>👁️ {{ number_format($campaign->impressions) }} lượt xem</div>
                                <div>🖱️ {{ number_format($campaign->clicks) }} lượt click</div>
                                <div>💰 {{ number_format($campaign->total_spent) }}đ đã chi</div>
                                @if($campaign->impressions > 0)
                                    <div class="text-xs text-gray-500">
                                        CTR: {{ number_format(($campaign->clicks / $campaign->impressions) * 100, 2) }}%
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($campaign->status === 'active')
                                <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                    Đang chạy
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">
                                    Tạm dừng
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('seller.ad_bidding.edit', $campaign->id) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit mr-1"></i>Sửa giá
                                </a>
                                <a href="{{ route('seller.ads_campaigns.edit', $campaign->id) }}" 
                                   class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-cog mr-1"></i>Cấu hình
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $campaigns->links() }}
        </div>
        @else
        <div class="p-6 text-center">
            <div class="text-gray-500 mb-4">
                <i class="fas fa-campaign text-4xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có chiến dịch quảng cáo</h3>
            <p class="text-gray-500 mb-4">Tạo chiến dịch quảng cáo đầu tiên để bắt đầu đấu giá</p>
            <a href="{{ route('seller.ads_campaigns.create') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>Tạo Chiến Dịch
            </a>
        </div>
        @endif
    </div>
</div>

<script>
// Auto refresh thống kê mỗi 30 giây
setInterval(function() {
    location.reload();
}, 30000);
</script>
@endsection
