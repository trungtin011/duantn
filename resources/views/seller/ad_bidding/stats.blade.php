@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Thống Kê Đấu Giá Quảng Cáo</h1>
                <p class="text-gray-600 mt-2">Phân tích hiệu quả quảng cáo và chi phí</p>
            </div>
            <a href="{{ route('seller.ad_bidding.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Quay Lại
            </a>
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

    <!-- Chi tiết thống kê -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top chiến dịch theo giá thầu -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Top Chiến Dịch Theo Giá Thầu</h2>
            </div>
            
            <div class="p-6">
                @if($topCampaigns->count() > 0)
                    <div class="space-y-4">
                        @foreach($topCampaigns as $index => $campaign)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-bold text-blue-600">{{ $index + 1 }}</span>
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $campaign->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $campaign->adsCampaignItems->count() }} sản phẩm</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-blue-600">{{ number_format($campaign->bid_amount) }}đ</div>
                                    <div class="text-xs text-gray-500">
                                        {{ number_format($campaign->clicks) }} clicks
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center">Chưa có chiến dịch nào</p>
                @endif
            </div>
        </div>

        <!-- Thống kê chi tiết -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Thống Kê Chi Tiết</h2>
            </div>
            
            <div class="p-6 space-y-4">
                @if($stats)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Giá thầu cao nhất:</span>
                        <span class="font-semibold text-green-600">{{ number_format($stats->max_bid ?? 0) }}đ</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Giá thầu thấp nhất:</span>
                        <span class="font-semibold text-red-600">{{ number_format($stats->min_bid ?? 0) }}đ</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Tổng lượt click:</span>
                        <span class="font-semibold">{{ number_format($stats->total_clicks ?? 0) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">CTR trung bình:</span>
                        <span class="font-semibold">
                            @if($stats->total_impressions > 0)
                                {{ number_format(($stats->total_clicks / $stats->total_impressions) * 100, 2) }}%
                            @else
                                0%
                            @endif
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Chi phí trung bình/click:</span>
                        <span class="font-semibold text-blue-600">
                            @if($stats->total_clicks > 0)
                                {{ number_format($stats->total_spent / $stats->total_clicks) }}đ
                            @else
                                0đ
                            @endif
                        </span>
                    </div>
                @else
                    <p class="text-gray-500 text-center">Chưa có dữ liệu thống kê</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Biểu đồ và phân tích -->
    <div class="mt-6 bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Phân Tích Hiệu Quả</h2>
        </div>
        
        <div class="p-6">
            @if($stats && $stats->total_campaigns > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Hiệu quả theo giá thầu -->
                    <div class="text-center p-4 border border-gray-200 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600 mb-2">
                            @if($stats->avg_bid >= 1000)
                                ⭐⭐⭐
                            @elseif($stats->avg_bid >= 500)
                                ⭐⭐
                            @else
                                ⭐
                            @endif
                        </div>
                        <h3 class="font-medium text-gray-900 mb-1">Mức Độ Cạnh Tranh</h3>
                        <p class="text-sm text-gray-500">
                            @if($stats->avg_bid >= 1000)
                                Cao - Có khả năng hiển thị tốt
                            @elseif($stats->avg_bid >= 500)
                                Trung bình - Cần tăng giá thầu
                            @else
                                Thấp - Khó hiển thị
                            @endif
                        </p>
                    </div>

                    <!-- Hiệu quả theo CTR -->
                    <div class="text-center p-4 border border-gray-200 rounded-lg">
                        <div class="text-2xl font-bold text-green-600 mb-2">
                            @if($stats->total_impressions > 0)
                                @php $ctr = ($stats->total_clicks / $stats->total_impressions) * 100; @endphp
                                @if($ctr >= 5)
                                    🎯
                                @elseif($ctr >= 2)
                                    📈
                                @else
                                    📉
                                @endif
                            @else
                                📊
                            @endif
                        </div>
                        <h3 class="font-medium text-gray-900 mb-1">Tỷ Lệ Click</h3>
                        <p class="text-sm text-gray-500">
                            @if($stats->total_impressions > 0)
                                @php $ctr = ($stats->total_clicks / $stats->total_impressions) * 100; @endphp
                                {{ number_format($ctr, 2) }}% - 
                                @if($ctr >= 5)
                                    Rất tốt
                                @elseif($ctr >= 2)
                                    Khá tốt
                                @else
                                    Cần cải thiện
                                @endif
                            @else
                                Chưa có dữ liệu
                            @endif
                        </p>
                    </div>

                    <!-- Hiệu quả chi phí -->
                    <div class="text-center p-4 border border-gray-200 rounded-lg">
                        <div class="text-2xl font-bold text-red-600 mb-2">
                            @if($stats->total_clicks > 0)
                                @php $avgCost = $stats->total_spent / $stats->total_clicks; @endphp
                                @if($avgCost <= 1000)
                                    💰
                                @elseif($avgCost <= 2000)
                                    💸
                                @else
                                    💸💸
                                @endif
                            @else
                                💰
                            @endif
                        </div>
                        <h3 class="font-medium text-gray-900 mb-1">Chi Phí Trung Bình</h3>
                        <p class="text-sm text-gray-500">
                            @if($stats->total_clicks > 0)
                                @php $avgCost = $stats->total_spent / $stats->total_clicks; @endphp
                                {{ number_format($avgCost) }}đ/click - 
                                @if($avgCost <= 1000)
                                    Hiệu quả
                                @elseif($avgCost <= 2000)
                                    Chấp nhận được
                                @else
                                    Cần tối ưu
                                @endif
                            @else
                                Chưa có dữ liệu
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Khuyến nghị -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="font-medium text-blue-900 mb-2">💡 Khuyến Nghị</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        @if($stats->avg_bid < 500)
                            <li>• Tăng giá thầu để cải thiện vị trí hiển thị</li>
                        @endif
                        @if($stats->total_impressions > 0 && ($stats->total_clicks / $stats->total_impressions) * 100 < 2)
                            <li>• Cải thiện chất lượng quảng cáo để tăng CTR</li>
                        @endif
                        @if($stats->total_clicks > 0 && ($stats->total_spent / $stats->total_clicks) > 2000)
                            <li>• Tối ưu giá thầu để giảm chi phí/click</li>
                        @endif
                        <li>• Theo dõi thống kê thường xuyên để điều chỉnh chiến lược</li>
                    </ul>
                </div>
            @else
                <p class="text-gray-500 text-center">Chưa có dữ liệu để phân tích</p>
            @endif
        </div>
    </div>
</div>

<script>
// Auto refresh thống kê mỗi 60 giây
setInterval(function() {
    location.reload();
}, 60000);
</script>
@endsection
