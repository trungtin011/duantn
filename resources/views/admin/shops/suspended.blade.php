@extends('layouts.admin')

@section('title', 'Cửa hàng Tạm ngưng')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 min-h-screen">
    <!-- Header -->
    <div class="bg-white rounded-xl p-6 mb-6 shadow-md">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
                    <i class="fas fa-pause text-orange-600 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Cửa hàng Tạm ngưng</h1>
                    <p class="text-gray-600">Danh sách các cửa hàng đã được duyệt nhưng đang tạm ngưng</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.shops.index') }}" class="px-4 py-2 bg-gray-600 text-white font-semibold rounded-md shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-75 flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    @if($shops->count() > 0)
        <!-- Shops Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Danh sách cửa hàng tạm ngưng ({{ $shops->total() }})</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Cửa hàng
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Chủ sở hữu
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thông tin liên hệ
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Đánh giá & Doanh thu
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày tạo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hành động
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($shops as $shop)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($shop->shop_logo)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $shop->shop_logo) }}" alt="{{ $shop->shop_name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <i class="fas fa-store text-indigo-600"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('admin.shops.show', $shop->id) }}" class="hover:text-indigo-600">
                                                {{ $shop->shop_name }}
                                            </a>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            ID: {{ $shop->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($shop->owner)
                                    <div class="text-sm text-gray-900">{{ $shop->owner->fullname ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $shop->owner->email ?? 'N/A' }}</div>
                                @else
                                    <div class="text-sm text-gray-500">Không có thông tin</div>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $shop->shop_email ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $shop->shop_phone ?? 'N/A' }}</div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-star text-yellow-400"></i>
                                        <span>{{ number_format($shop->shop_rating, 1) }}</span>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ number_format($shop->total_sales, 0, ',', '.') }} VNĐ
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    <i class="fas fa-pause mr-1"></i>Tạm ngưng
                                </span>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $shop->created_at->format('d/m/Y H:i') }}
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.shops.show', $shop->id) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50 transition-colors duration-200" 
                                       title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.shops.activate', $shop) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50 transition-colors duration-200" 
                                                title="Kích hoạt cửa hàng"
                                                onclick="return confirm('Bạn có chắc muốn kích hoạt cửa hàng này?')">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($shops->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $shops->links() }}
            </div>
            @endif
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl p-12 text-center shadow-md">
            <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-orange-100 flex items-center justify-center">
                <i class="fas fa-pause text-orange-600 text-3xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Không có cửa hàng nào tạm ngưng</h3>
            <p class="text-gray-500 mb-6">Hiện tại không có cửa hàng nào ở trạng thái tạm ngưng.</p>
            <a href="{{ route('admin.shops.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-75">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại danh sách
            </a>
        </div>
    @endif
</div>
@endsection
