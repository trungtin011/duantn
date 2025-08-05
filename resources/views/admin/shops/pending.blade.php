@extends('layouts.admin')

@section('title', 'Duyệt Cửa Hàng')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 bg-gray-100 min-h-screen">
    <!-- Header -->
    <div class="bg-white rounded-xl p-6 mb-6 shadow-md">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Duyệt Cửa hàng</h1>
                    <p class="text-gray-600">Danh sách cửa hàng chờ phê duyệt</p>
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

    <!-- Alerts -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2" role="alert">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 border border-red-300 p-4 rounded-lg mb-6">
            <div class="flex items-center gap-2 mb-2">
                <i class="fas fa-exclamation-triangle"></i>
                <span class="font-medium">Có lỗi xảy ra:</span>
            </div>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Content -->
    @if($shops->isEmpty())
        <div class="bg-white rounded-xl p-8 shadow-md text-center">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-store text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Không có cửa hàng nào đang chờ duyệt</h3>
            <p class="text-gray-600">Tất cả cửa hàng đã được xử lý hoặc chưa có đăng ký mới.</p>
        </div>
    @else
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
                                Trạng thái
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày đăng ký
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hành động
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($shops as $shop)
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
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-2"></i>Chờ duyệt
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
                                    
                                    <form action="{{ route('admin.shops.approve', $shop) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50 transition-colors duration-200" 
                                                title="Duyệt cửa hàng"
                                                onclick="return confirm('Bạn có chắc muốn duyệt cửa hàng này?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    
                                    <button type="button" 
                                            data-shop-id="{{ $shop->id }}" 
                                            data-shop-name="{{ $shop->shop_name }}" 
                                            class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors duration-200 reject-shop-btn" 
                                            title="Từ chối">
                                        <i class="fas fa-times"></i>
                                    </button>
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
    @endif
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
        <div class="mt-3">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Từ chối cửa hàng</h3>
            </div>
            <p class="text-gray-600 mb-4">Bạn sắp từ chối cửa hàng <span id="shopNameReject" class="font-semibold text-gray-800"></span></p>
            
            <form id="rejectForm" method="POST" action="">
                @csrf
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">Lý do từ chối</label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                              placeholder="Nhập lý do từ chối (bắt buộc)" required></textarea>
                </div>
                
                <div class="flex gap-3">
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-red-600 text-white font-semibold rounded-lg shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-75">
                        <i class="fas fa-times mr-2"></i>Gửi từ chối
                    </button>
                    <button type="button" 
                            onclick="document.getElementById('rejectModal').classList.add('hidden')" 
                            class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-75">
                        Hủy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.querySelectorAll('.reject-shop-btn').forEach(button => {
        button.addEventListener('click', function() {
            const shopId = this.dataset.shopId;
            const shopName = this.dataset.shopName;
            showRejectModal(shopId, shopName);
        });
    });

    function showRejectModal(shopId, shopName) {
        const modal = document.getElementById('rejectModal');
        const shopNameSpan = document.getElementById('shopNameReject');
        const rejectForm = document.getElementById('rejectForm');
        
        shopNameSpan.textContent = shopName;
        rejectForm.action = '/admin/shops/' + shopId + '/reject';
        modal.classList.remove('hidden');
    }
</script>
@endsection 