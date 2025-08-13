@extends('layouts.seller_home')
@section('title', 'Cài đặt cửa hàng')
@section('content')
    <div class="flex-1 space-y-6 overflow-y-auto">
        <div class="w-full mx-0">
            <div class="admin-page-header mb-5">
                <h1 class="admin-page-title text-2xl">Cài đặt cửa hàng</h1>
                <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / Cập nhật thông tin cửa
                </div>
            </div>

            @include('seller.partials.account_submenu')

            <form action="{{ route('seller.settings') }}" method="POST" enctype="multipart/form-data"
                class="bg-white rounded-lg p-4 shadow-sm space-y-6">
                @csrf
                @method('POST')

                <!-- Thông tin cơ bản -->
                <section class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs text-gray-600 font-medium mb-1">Tên cửa hàng <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="shop_name" required
                                class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="{{ old('shop_name', $shop->shop_name ?? '') }}">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-600 font-medium mb-1">Số điện thoại</label>
                                <input type="text" name="shop_phone"
                                    class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ old('shop_phone', $shop->shop_phone ?? '') }}">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 font-medium mb-1">Email cửa hàng</label>
                                <input type="email" name="shop_email"
                                    class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ old('shop_email', $shop->shop_email ?? '') }}">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 font-medium mb-1">Mô tả cửa hàng</label>
                            <textarea name="shop_description" rows="3"
                                class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('shop_description', $shop->shop_description ?? '') }}</textarea>
                        </div>
                    </div>
                </section>

                <!-- Ảnh đại diện & Banner -->
                <section class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-600 font-medium mb-1">Logo cửa hàng</label>
                            <input type="file" name="shop_logo" accept="image/*"
                                class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full">
                            @if (!empty($shop->shop_logo))
                                <div class="mt-2 border rounded-md p-2 bg-gray-50">
                                    <img src="{{ asset('storage/' . $shop->shop_logo) }}" alt="Logo"
                                        class="h-16 rounded object-cover">
                                </div>
                            @endif
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 font-medium mb-1">Banner cửa hàng</label>
                            <input type="file" name="shop_banner" accept="image/*"
                                class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full">
                            @if (!empty($shop->shop_banner))
                                <div class="mt-2 border rounded-md p-2 bg-gray-50">
                                    <img src="{{ asset('storage/' . $shop->shop_banner) }}" alt="Banner"
                                        class="h-16 rounded object-cover w-full">
                                </div>
                            @endif
                        </div>
                    </div>
                </section>

                <!-- Trạng thái -->
                <section class="space-y-3">
                    <div>
                        <label class="block text-xs text-gray-600 font-medium mb-1">Trạng thái</label>
                        <select name="shop_status"
                            class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="active" @if (old('shop_status', $shop->shop_status ?? '') == 'active') selected @endif>Đang hoạt động</option>
                            <option value="inactive" @if (old('shop_status', $shop->shop_status ?? '') == 'inactive') selected @endif>Ngừng hoạt động
                            </option>
                        </select>
                    </div>
                </section>

                <!-- Actions -->
                <div class="flex justify-end pt-2">
                    <button type="submit"
                        class="inline-flex items-center text-xs bg-[#f42f46] hover:bg-[#d62a3e] text-white px-5 py-2 rounded-md shadow-sm transition-colors">
                        <i class="fas fa-save mr-2"></i> Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
