@extends('layouts.seller_home')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Chỉnh Sửa Giá Thầu</h1>
                <p class="text-gray-600 mt-2">Cập nhật giá thầu cho chiến dịch "{{ $campaign->name }}"</p>
            </div>
            <a href="{{ route('seller.ad_bidding.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Quay Lại
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form chỉnh sửa giá thầu -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Thông Tin Chiến Dịch</h2>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('seller.ad_bidding.update', $campaign->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tên Chiến Dịch
                            </label>
                            <input type="text" value="{{ $campaign->name }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50" 
                                   readonly>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Giá Thầu Hiện Tại
                            </label>
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl font-bold text-blue-600">{{ number_format($campaign->bid_amount) }}đ</span>
                                @if($campaign->bid_amount >= 1000)
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Cao</span>
                                @elseif($campaign->bid_amount >= 500)
                                    <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Trung bình</span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Thấp</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="bid_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Giá Thầu Mới <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       id="bid_amount" 
                                       name="bid_amount" 
                                       value="{{ old('bid_amount', $campaign->bid_amount) }}"
                                       min="1" 
                                       max="100000"
                                       step="1"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Nhập giá thầu (VND)">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500">đ</span>
                                </div>
                            </div>
                            @error('bid_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Giá thầu tối thiểu: 1đ, tối đa: 100,000đ</p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Gợi Ý Giá Thầu
                            </label>
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" onclick="setBidAmount(500)" 
                                        class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                                    500đ - Thấp
                                </button>
                                <button type="button" onclick="setBidAmount(1000)" 
                                        class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                                    1,000đ - Trung bình
                                </button>
                                <button type="button" onclick="setBidAmount(2000)" 
                                        class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                                    2,000đ - Cao
                                </button>
                            </div>
                        </div>

                        <div class="flex space-x-3">
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-colors">
                                <i class="fas fa-save mr-2"></i>Cập Nhật
                            </button>
                            <a href="{{ route('seller.ad_bidding.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg transition-colors">
                                Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Thông tin chiến dịch -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Thống Kê Chiến Dịch</h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Lượt xem:</span>
                        <span class="font-semibold">{{ number_format($campaign->impressions) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Lượt click:</span>
                        <span class="font-semibold">{{ number_format($campaign->clicks) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">CTR:</span>
                        <span class="font-semibold">
                            @if($campaign->impressions > 0)
                                {{ number_format(($campaign->clicks / $campaign->impressions) * 100, 2) }}%
                            @else
                                0%
                            @endif
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Đã chi:</span>
                        <span class="font-semibold text-red-600">{{ number_format($campaign->total_spent) }}đ</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Trạng thái:</span>
                        @if($campaign->status === 'active')
                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                Đang chạy
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">
                                Tạm dừng
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sản phẩm trong chiến dịch -->
            <div class="bg-white rounded-lg shadow mt-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Sản Phẩm Quảng Cáo</h3>
                </div>
                
                <div class="p-6">
                    @if($campaign->adsCampaignItems->count() > 0)
                        <div class="space-y-3">
                            @foreach($campaign->adsCampaignItems->take(5) as $item)
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $item->product->image_url }}" 
                                         alt="{{ $item->product->name }}"
                                         class="w-10 h-10 object-cover rounded">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $item->product->name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ number_format($item->product->getCurrentPriceAttribute()) }}đ
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($campaign->adsCampaignItems->count() > 5)
                                <p class="text-xs text-gray-500 text-center">
                                    +{{ $campaign->adsCampaignItems->count() - 5 }} sản phẩm khác
                                </p>
                            @endif
                        </div>
                    @else
                        <p class="text-gray-500 text-center">Chưa có sản phẩm nào</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function setBidAmount(amount) {
    document.getElementById('bid_amount').value = amount;
}

// Auto save draft
let autoSaveTimer;
document.getElementById('bid_amount').addEventListener('input', function() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(function() {
        // Có thể thêm auto save ở đây
        console.log('Auto save draft...');
    }, 2000);
});
</script>
@endsection
