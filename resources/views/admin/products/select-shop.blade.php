@extends('layouts.admin')

@section('title', 'Chọn cửa hàng')

@section('content')
<div class="mx-auto max-w-4xl">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Chọn cửa hàng</h1>
            <div class="text-sm text-gray-500">
                <a href="{{ route('admin.dashboard') }}" class="hover:underline">Trang chủ</a> / 
                <a href="{{ route('admin.products.index') }}" class="hover:underline">Sản phẩm</a> / 
                Chọn cửa hàng
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Shop Selection Form -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h2 class="text-xl font-semibold mb-4">Chọn cửa hàng để thêm sản phẩm</h2>
        
        @if($shops->count() > 0)
            <form action="{{ route('admin.products.create') }}" method="GET">
                <div class="mb-6">
                    <label for="shop_id" class="block text-gray-700 font-medium mb-2">
                        Cửa hàng <span class="text-red-500">*</span>
                    </label>
                    <select name="shop_id" id="shop_id" required 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Chọn cửa hàng --</option>
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}" 
                                    data-shop-name="{{ $shop->shop_name }}"
                                    data-shop-email="{{ $shop->shop_email }}"
                                    data-shop-phone="{{ $shop->shop_phone }}">
                                {{ $shop->shop_name }} 
                                @if($shop->shop_email)
                                    ({{ $shop->shop_email }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Shop Details Preview -->
                <div id="shop-details" class="mb-6 p-4 bg-gray-50 rounded-lg hidden">
                    <h3 class="font-semibold text-gray-800 mb-2">Thông tin cửa hàng:</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-600">Tên cửa hàng:</span>
                            <span id="preview-shop-name" class="ml-2 text-gray-800"></span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Email:</span>
                            <span id="preview-shop-email" class="ml-2 text-gray-800"></span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Số điện thoại:</span>
                            <span id="preview-shop-phone" class="ml-2 text-gray-800"></span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.products.index') }}" 
                       class="border border-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-100">
                        Hủy
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                        Tiếp tục
                    </button>
                </div>
            </form>
        @else
            <div class="text-center py-8">
                <div class="text-gray-400 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Không có cửa hàng nào</h3>
                <p class="text-gray-500 mb-4">Hiện tại không có cửa hàng nào hoạt động để thêm sản phẩm.</p>
                <a href="{{ route('admin.products.index') }}" 
                   class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                    Quay lại danh sách sản phẩm
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const shopSelect = document.getElementById('shop_id');
    const shopDetails = document.getElementById('shop-details');
    const previewShopName = document.getElementById('preview-shop-name');
    const previewShopEmail = document.getElementById('preview-shop-email');
    const previewShopPhone = document.getElementById('preview-shop-phone');

    shopSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            // Hiển thị thông tin cửa hàng
            previewShopName.textContent = selectedOption.dataset.shopName || 'N/A';
            previewShopEmail.textContent = selectedOption.dataset.shopEmail || 'N/A';
            previewShopPhone.textContent = selectedOption.dataset.shopPhone || 'N/A';
            shopDetails.classList.remove('hidden');
        } else {
            // Ẩn thông tin cửa hàng
            shopDetails.classList.add('hidden');
        }
    });
});
</script>
@endpush
@endsection 