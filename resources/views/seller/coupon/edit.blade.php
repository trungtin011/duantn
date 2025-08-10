@extends('layouts.seller_home')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/coupon.css') }}">
    @endpush
@endsection

@section('title', 'Sửa mã giảm giá')

@section('content')
    <div class="admin-page-header mb-5">
        <h1 class="admin-page-title text-2xl">Sửa mã giảm giá</h1>
        <div class="admin-breadcrumb"><a href="{{ route('seller.coupon.index') }}" class="admin-breadcrumb-link">Mã giảm giá</a> / Sửa</div>
    </div>
    @include('layouts.notification')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm p-8">
            <form action="{{ route('seller.coupon.update', $coupon->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label for="code" class="form-label">Mã <span class="text-red-500">*</span></label>
                    <input type="text" class="form-control @error('code') border-red-500 @enderror" id="code" name="code" value="{{ old('code', $coupon->code) }}" placeholder="Nhập mã giảm giá">
                    @error('code')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="name" class="form-label">Tên <span class="text-red-500">*</span></label>
                    <input type="text" class="form-control @error('name') border-red-500 @enderror" id="name" name="name" value="{{ old('name', $coupon->name) }}" placeholder="Tên mã giảm giá">
                    @error('name')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea class="form-control @error('description') border-red-500 @enderror" id="description" name="description" rows="2" placeholder="Mô tả ngắn về mã giảm giá">{{ old('description', $coupon->description) }}</textarea>
                    @error('description')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="image" class="form-label">Ảnh mã giảm giá</label>
                    <input type="file" class="form-control-file @error('image') border-red-500 @enderror" id="image" name="image" accept="image/*">
                    @error('image')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                    @if ($coupon->image)
                        <div class="mt-2">
                            <span class="form-label">Ảnh hiện tại:</span><br>
                            <img src="{{ asset('storage/' . $coupon->image) }}" alt="{{ $coupon->name }}" class="w-24 h-24 rounded-md object-cover border mt-1">
                        </div>
                    @endif
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="discount_value" class="form-label">Giá trị giảm giá <span class="text-red-500">*</span></label>
                        <input type="number" class="form-control @error('discount_value') border-red-500 @enderror" id="discount_value" name="discount_value" value="{{ old('discount_value', $coupon->discount_value) }}" step="0.01" min="0" placeholder="Nhập giá trị">
                        @error('discount_value')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="discount_type" class="form-label">Loại giảm giá <span class="text-red-500">*</span></label>
                        <select class="form-select form-select-admin @error('discount_type') border-red-500 @enderror" id="discount_type" name="discount_type">
                            <option value="percentage" {{ old('discount_type', $coupon->discount_type) == 'percentage' ? 'selected' : '' }}>Phần trăm</option>
                            <option value="fixed" {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>Cố định</option>
                        </select>
                        @error('discount_type')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="max_discount_amount" class="form-label">Số tiền giảm tối đa</label>
                        <input type="number" class="form-control @error('max_discount_amount') border-red-500 @enderror" id="max_discount_amount" name="max_discount_amount" value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}" step="0.01" min="0" placeholder="Tối đa (nếu có)">
                        @error('max_discount_amount')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="min_order_amount" class="form-label">Đơn hàng tối thiểu</label>
                        <input type="number" class="form-control @error('min_order_amount') border-red-500 @enderror" id="min_order_amount" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" step="0.01" min="0" placeholder="Tối thiểu (nếu có)">
                        @error('min_order_amount')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="quantity" class="form-label">Số lượng <span class="text-red-500">*</span></label>
                        <input type="number" class="form-control @error('quantity') border-red-500 @enderror" id="quantity" name="quantity" value="{{ old('quantity', $coupon->quantity) }}" placeholder="Số lượng" min="1" max="100000">
                        @error('quantity')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="max_uses_per_user" class="form-label">Số lần dùng mỗi người</label>
                        <input type="number" class="form-control @error('max_uses_per_user') border-red-500 @enderror" id="max_uses_per_user" name="max_uses_per_user" value="{{ old('max_uses_per_user', $coupon->max_uses_per_user) }}" placeholder="Tối đa/người" min="1">
                        @error('max_uses_per_user')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="max_uses_total" class="form-label">Tổng số lần sử dụng</label>
                        <input type="number" class="form-control @error('max_uses_total') border-red-500 @enderror" id="max_uses_total" name="max_uses_total" value="{{ old('max_uses_total', $coupon->max_uses_total) }}" placeholder="Tổng số lần" min="1">
                        @error('max_uses_total')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="rank_limit" class="form-label">Hạn chế theo hạng</label>
                        <select class="form-select form-select-admin @error('rank_limit') border-red-500 @enderror" id="rank_limit" name="rank_limit">
                            <option value="all" {{ old('rank_limit', $coupon->rank_limit) == 'all' ? 'selected' : '' }}>Tất cả</option>
                            <option value="gold" {{ old('rank_limit', $coupon->rank_limit) == 'gold' ? 'selected' : '' }}>Vàng</option>
                            <option value="silver" {{ old('rank_limit', $coupon->rank_limit) == 'silver' ? 'selected' : '' }}>Bạc</option>
                            <option value="bronze" {{ old('rank_limit', $coupon->rank_limit) == 'bronze' ? 'selected' : '' }}>Đồng</option>
                            <option value="diamond" {{ old('rank_limit', $coupon->rank_limit) == 'diamond' ? 'selected' : '' }}>Kim cương</option>
                        </select>
                        @error('rank_limit')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="form-label">Ngày bắt đầu <span class="text-red-500">*</span></label>
                        <input type="date" class="form-control @error('start_date') border-red-500 @enderror" id="start_date" name="start_date" value="{{ old('start_date', $coupon->start_date ? $coupon->start_date->format('Y-m-d') : '') }}" min="{{ date('Y-m-d') }}">
                        @error('start_date')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="end_date" class="form-label">Ngày kết thúc <span class="text-red-500">*</span></label>
                        <input type="date" class="form-control @error('end_date') border-red-500 @enderror" id="end_date" name="end_date" value="{{ old('end_date', $coupon->end_date ? $coupon->end_date->format('Y-m-d') : '') }}" min="{{ date('Y-m-d') }}">
                        @error('end_date')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="flex gap-6 items-center mt-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }} class="form-checkbox">
                        <span class="ml-2">Kích hoạt</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_public" value="1" {{ old('is_public', $coupon->is_public) ? 'checked' : '' }} class="form-checkbox">
                        <span class="ml-2">Công khai</span>
                    </label>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <a href="{{ route('seller.coupon.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded-md transition-all duration-300">Hủy</a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition-all duration-300 font-semibold">Cập nhật mã giảm giá</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    // Validation cho ngày
    function validateDates() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Reset min date cho end_date
        if (startDateInput.value) {
            endDateInput.min = startDateInput.value;
        }

        // Validation
        if (startDateInput.value && startDate < today) {
            startDateInput.setCustomValidity('Ngày bắt đầu phải từ hôm nay trở đi');
            return false;
        } else {
            startDateInput.setCustomValidity('');
        }

        if (endDateInput.value && endDate < startDate) {
            endDateInput.setCustomValidity('Ngày kết thúc phải từ ngày bắt đầu trở đi');
            return false;
        } else {
            endDateInput.setCustomValidity('');
        }

        return true;
    }

    startDateInput.addEventListener('change', validateDates);
    endDateInput.addEventListener('change', validateDates);

    // Form validation
    form.addEventListener('submit', function(e) {
        if (!validateDates()) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endsection