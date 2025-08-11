@extends('layouts.admin')

@section('title', 'Cửa hàng đã bị cấm')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 min-h-screen">
    <!-- Header -->
    <div class="bg-white rounded-xl p-6 mb-6 shadow-md">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-ban text-red-600"></i>
                Cửa hàng đã bị cấm
            </h1>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.shops.index') }}" class="px-4 py-2 bg-gray-600 text-white font-semibold rounded-md shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-75 flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Quay lại
                </a>
            </div>
        </div>
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
                            Ngày bị cấm
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
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $shop->updated_at->format('d/m/Y H:i') }}
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.shops.show', $shop->id) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <form action="{{ route('admin.shops.unban', $shop) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Mở lại" 
                                            onclick="return confirm('Bạn có chắc muốn mở lại cửa hàng này?')">
                                        <i class="fas fa-unlock"></i>
                                    </button>
                                </form>
                                
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
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Không có cửa hàng nào bị cấm.
                        </td>
                    </tr>
                    @endforelse
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