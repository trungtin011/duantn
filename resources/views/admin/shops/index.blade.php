@extends('layouts.admin')

@section('title', 'Quản lý Cửa hàng')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 min-h-screen">
    <!-- Header -->
    <div class="bg-white rounded-xl p-6 mb-6 shadow-md">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-store text-indigo-600"></i>
                Quản lý Cửa hàng
            </h1>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.shops.analytics') }}" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-75 flex items-center gap-2">
                    <i class="fas fa-chart-bar"></i>
                    Thống kê
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Tổng cửa hàng</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-store text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Đang hoạt động</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Chờ duyệt</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['inactive'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Đã cấm</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['banned'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-ban text-red-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Tạm ngưng</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $stats['suspended'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
                    <i class="fas fa-pause text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-xl p-6 mb-6 shadow-md">
        <form method="GET" action="{{ route('admin.shops.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="Tên shop, email, số điện thoại...">
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Tất cả</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Chờ duyệt</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Tạm ngưng</option>
                    <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Đã cấm</option>
                </select>
            </div>
            
            <div>
                <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-2">Sắp xếp theo</label>
                <select name="sort_by" id="sort_by" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Ngày tạo</option>
                    <option value="shop_name" {{ request('sort_by') == 'shop_name' ? 'selected' : '' }}>Tên shop</option>
                    <option value="total_sales" {{ request('sort_by') == 'total_sales' ? 'selected' : '' }}>Doanh thu</option>
                    <option value="shop_rating" {{ request('sort_by') == 'shop_rating' ? 'selected' : '' }}>Đánh giá</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-75">
                    <i class="fas fa-search mr-2"></i>Tìm kiếm
                </button>
            </div>
        </form>
    </div>

    <!-- Shops Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
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
                            Liên hệ
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thống kê
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
                    @forelse($shops as $shop)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <img class="h-12 w-12 rounded-lg object-cover" src="{{ $shop->shop_logo ? asset('storage/' . $shop->shop_logo) : asset('images/default-shop.png') }}" alt="{{ $shop->shop_name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $shop->shop_name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($shop->shop_description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $shop->owner->fullname ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $shop->owner->email ?? 'N/A' }}</div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $shop->shop_phone }}</div>
                            <div class="text-sm text-gray-500">{{ $shop->shop_email }}</div>
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
                            @elseif($shop->shop_status && $shop->shop_status->value == 'suspended')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    <i class="fas fa-pause mr-1"></i>Tạm ngưng
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
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.shops.show', $shop->id) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($shop->shop_status && $shop->shop_status->value == 'inactive')
                                    <form action="{{ route('admin.shops.approve', $shop) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900" title="Duyệt">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                @if($shop->shop_status && $shop->shop_status->value == 'active')
                                    <form action="{{ route('admin.shops.deactivate', $shop) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-orange-600 hover:text-orange-900" title="Tạm ngưng">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                @if($shop->shop_status && $shop->shop_status->value == 'inactive')
                                    <form action="{{ route('admin.shops.reactivate', $shop) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900" title="Kích hoạt lại">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                @if($shop->shop_status && $shop->shop_status->value == 'banned')
                                    <form action="{{ route('admin.shops.unban', $shop) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900" title="Mở lại" 
                                                onclick="return confirm('Bạn có chắc muốn mở lại cửa hàng này?')">
                                            <i class="fas fa-unlock"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                @if($shop->shop_status && $shop->shop_status->value == 'suspended')
                                    <form action="{{ route('admin.shops.activate', $shop) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900" title="Kích hoạt" 
                                                onclick="return confirm('Bạn có chắc muốn kích hoạt cửa hàng này?')">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                @if($shop->shop_status && $shop->shop_status->value != 'banned')
                                    <form action="{{ route('admin.shops.ban', $shop) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Cấm" 
                                                onclick="return confirm('Bạn có chắc muốn cấm cửa hàng này?')">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('admin.shops.destroy', $shop) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Xóa" 
                                            onclick="return confirm('Bạn có chắc muốn xóa cửa hàng này?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Không có cửa hàng nào được tìm thấy.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($shops->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $shops->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var successMessage = "{{ session('success') }}";
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: successMessage,
                timer: 3000,
                showConfirmButton: false
            });
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var errorMessage = "{{ session('error') }}";
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: errorMessage,
                timer: 3000,
                showConfirmButton: false
            });
        });
    </script>
@endif
@endsection 