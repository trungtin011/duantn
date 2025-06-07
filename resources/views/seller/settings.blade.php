@extends('layouts.seller_home')
@section('title', 'Cài đặt cửa hàng')
@section('content')
<div class="container mx-auto max-w-2xl py-8">
    <h2 class="text-2xl font-bold mb-6 text-orange-600 flex items-center gap-2">
        <i class="fas fa-store-alt"></i> Cập nhật thông tin cửa hàng
    </h2>
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center gap-2">
            <i class="fas fa-check-circle"></i> <span>{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="mb-0 pl-5 list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('seller.settings') }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-lg shadow-lg space-y-6">
        @csrf
        @method('POST')
        <div class="grid grid-cols-1 gap-5">
            <div>
                <label class="block font-semibold mb-1 text-gray-700">Tên cửa hàng <span class="text-red-500">*</span></label>
                <input type="text" name="shop_name" class="form-input w-full border-gray-300 rounded focus:ring-orange-500" value="{{ old('shop_name', $shop->shop_name ?? '') }}" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1 text-gray-700">Số điện thoại</label>
                    <input type="text" name="shop_phone" class="form-input w-full border-gray-300 rounded focus:ring-orange-500" value="{{ old('shop_phone', $shop->shop_phone ?? '') }}">
                </div>
                <div>
                    <label class="block font-semibold mb-1 text-gray-700">Email cửa hàng</label>
                    <input type="email" name="shop_email" class="form-input w-full border-gray-300 rounded focus:ring-orange-500" value="{{ old('shop_email', $shop->shop_email ?? '') }}">
                </div>
            </div>
            <div>
                <label class="block font-semibold mb-1 text-gray-700">Mô tả cửa hàng</label>
                <textarea name="shop_description" class="form-input w-full border-gray-300 rounded focus:ring-orange-500" rows="3">{{ old('shop_description', $shop->shop_description ?? '') }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1 text-gray-700">Logo cửa hàng</label>
                    <input type="file" name="shop_logo" accept="image/*" class="form-input w-full border-gray-300 rounded">
                    @if(!empty($shop->shop_logo))
                        <img src="{{ asset('storage/'.$shop->shop_logo) }}" alt="Logo" class="h-20 mt-2 rounded shadow border">
                    @endif
                </div>
                <div>
                    <label class="block font-semibold mb-1 text-gray-700">Banner cửa hàng</label>
                    <input type="file" name="shop_banner" accept="image/*" class="form-input w-full border-gray-300 rounded">
                    @if(!empty($shop->shop_banner))
                        <img src="{{ asset('storage/'.$shop->shop_banner) }}" alt="Banner" class="h-20 mt-2 rounded shadow border">
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1 text-gray-700">Tổng số sản phẩm</label>
                    <input type="number" name="total_products" class="form-input w-full border-gray-300 rounded focus:ring-orange-500" value="{{ old('total_products', $shop->total_products ?? 0) }}" min="0" readonly>
                </div>
                <div>
                    <label class="block font-semibold mb-1 text-gray-700">Tổng số lượt theo dõi</label>
                    <input type="number" name="total_followers" class="form-input w-full border-gray-300 rounded focus:ring-orange-500" value="{{ old('total_followers', $shop->total_followers ?? 0) }}" min="0" readonly>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1 text-gray-700">Tổng doanh thu</label>
                    <input type="number" name="total_sales" class="form-input w-full border-gray-300 rounded focus:ring-orange-500" value="{{ old('total_sales', $shop->total_sales ?? 0) }}" min="0" step="1000" readonly>
                </div>
                <div>
                    <label class="block font-semibold mb-1 text-gray-700">Đánh giá trung bình</label>
                    <input type="number" name="shop_rating" class="form-input w-full border-gray-300 rounded focus:ring-orange-500" value="{{ old('shop_rating', $shop->shop_rating ?? 0) }}" min="0" max="5" step="0.01" readonly>
                </div>
            </div>
            <div>
                <label class="block font-semibold mb-1 text-gray-700">Trạng thái</label>
                <select name="shop_status" class="form-input w-full border-gray-300 rounded focus:ring-orange-500">
                    <option value="active" @if(($shop->shop_status ?? '')=='active') selected @endif>Đang hoạt động</option>
                    <option value="inactive" @if(($shop->shop_status ?? '')=='inactive') selected @endif>Tạm dừng</option>
                    <option value="banned" @if(($shop->shop_status ?? '')=='banned') selected @endif>Bị khóa</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-2 rounded font-semibold shadow transition">Lưu thay đổi</button>
        </div>
    </form>
</div>
@endsection
