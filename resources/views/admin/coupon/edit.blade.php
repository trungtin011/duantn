@extends('layouts.admin')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Chỉnh Sửa Mã Giảm Giá</h1>
        <div class="admin-breadcrumb">
            <a href="{{ route('admin.coupon.index') }}" class="admin-breadcrumb-link">Mã Giảm Giá</a> / Chỉnh Sửa
        </div>
    </div>

    <section class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.coupon.update', $coupon->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Cột Trái - Nội Dung Chính -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mã Giảm Giá *</label>
                            <input type="text" name="code" value="{{ old('code', $coupon->code) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Nhập mã giảm giá" required>
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tên Mã Giảm Giá *</label>
                            <input type="text" name="name" value="{{ old('name', $coupon->name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Nhập tên mã giảm giá" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mô Tả</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Nhập mô tả mã giảm giá">{{ old('description', $coupon->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Giá Trị Giảm Giá *</label>
                            <input type="number" name="discount_value" value="{{ old('discount_value', $coupon->discount_value) }}" step="0.01"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Nhập giá trị giảm giá" required>
                            @error('discount_value')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Loại Giảm Giá *</label>
                            <select name="discount_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="percentage" {{ old('discount_type', $coupon->discount_type) == 'percentage' ? 'selected' : '' }}>Phần Trăm</option>
                                <option value="fixed" {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>Số Tiền Cố Định</option>
                            </select>
                            @error('discount_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Số Tiền Giảm Tối Đa</label>
                            <input type="number" name="max_discount_amount" value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}" step="0.01"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Nhập số tiền giảm tối đa">
                            @error('max_discount_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Giá Trị Đơn Hàng Tối Thiểu</label>
                            <input type="number" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" step="0.01"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Nhập giá trị đơn hàng tối thiểu">
                            @error('min_order_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Số Lượng</label>
                            <input type="number" name="quantity" value="{{ old('quantity', $coupon->quantity) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Nhập số lượng">
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Số Lần Sử Dụng Tối Đa Mỗi Người</label>
                            <input type="number" name="max_uses_per_user" value="{{ old('max_uses_per_user', $coupon->max_uses_per_user) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Nhập số lần sử dụng tối đa mỗi người">
                            @error('max_uses_per_user')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tổng Số Lần Sử Dụng Tối Đa</label>
                        <input type="number" name="max_uses_total" value="{{ old('max_uses_total', $coupon->max_uses_total) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Nhập tổng số lần sử dụng tối đa">
                        @error('max_uses_total')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ngày Bắt Đầu *</label>
                            <input type="date" name="start_date" value="{{ old('start_date', $coupon->start_date) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ngày Kết Thúc *</label>
                            <input type="date" name="end_date" value="{{ old('end_date', $coupon->end_date) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Cột Phải - Thanh Bên -->
                <div class="space-y-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-4">Cài Đặt Mã Giảm Giá</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Giới Hạn Hạng</label>
                                <select name="rank_limit"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="all" {{ old('rank_limit', $coupon->rank_limit) == 'all' ? 'selected' : '' }}>Tất Cả Hạng</option>
                                    <option value="gold" {{ old('rank_limit', $coupon->rank_limit) == 'gold' ? 'selected' : '' }}>Vàng</option>
                                    <option value="silver" {{ old('rank_limit', $coupon->rank_limit) == 'silver' ? 'selected' : '' }}>Bạc</option>
                                    <option value="bronze" {{ old('rank_limit', $coupon->rank_limit) == 'bronze' ? 'selected' : '' }}>Đồng</option>
                                    <option value="diamond" {{ old('rank_limit', $coupon->rank_limit) == 'diamond' ? 'selected' : '' }}>Kim Cương</option>
                                </select>
                                @error('rank_limit')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label class="ml-2 text-sm text-gray-700">Kích Hoạt</label>
                                </div>
                                @error('is_active')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                <div class="flex items-center">
                                    <input type="checkbox" name="is_public" value="1" {{ old('is_public', $coupon->is_public) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label class="ml-2 text-sm text-gray-700">Công Khai</label>
                                </div>
                                @error('is_public')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-blue-700 mb-2">Gợi Ý</h3>
                        <ul class="text-xs text-blue-600 space-y-1">
                            <li>• Sử dụng mã giảm giá rõ ràng và dễ nhớ</li>
                            <li>• Đặt giá trị giảm giá hợp lý</li>
                            <li>• Cân nhắc giá trị đơn hàng tối thiểu</li>
                            <li>• Thiết lập giới hạn sử dụng phù hợp</li>
                        </ul>
                    </div>

                    <div class="bg-yellow-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-yellow-700 mb-2">Hình Ảnh Mã Giảm Giá</h3>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                            <div class="space-y-2">
                                @if($coupon->image)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $coupon->image) }}" alt="Hình ảnh hiện tại" class="w-20 h-20 object-cover rounded mx-auto">
                                        <p class="text-xs text-gray-500 mt-1">Hình ảnh hiện tại</p>
                                    </div>
                                @endif
                                <div class="w-16 h-16 mx-auto bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-tag text-white text-xl"></i>
                                </div>
                                <div>
                                    <label for="image" class="cursor-pointer">
                                        <span class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition-colors">
                                            Chọn Hình Ảnh Mới
                                        </span>
                                        <input type="file" id="image" name="image" class="hidden" accept="image/*">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG tối đa 2MB</p>
                            </div>
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.coupon.index') }}" 
                   class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                    Hủy
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Cập Nhật Mã Giảm Giá
                </button>
            </div>
        </form>
    </section>
@endsection