@extends('layouts.seller_home')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/coupon.css') }}">
    @endpush
@endsection

@section('title', 'Thêm mã giảm giá')

@section('content')
    <div class="admin-page-header mb-5">
        <h1 class="admin-page-title text-2xl">Thêm mã giảm giá</h1>
        <div class="admin-breadcrumb">
            <a href="{{ route('seller.coupon.index') }}" class="admin-breadcrumb-link">Mã giảm giá</a> / Thêm mới
        </div>
    </div>
    @include('layouts.notification')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm p-8">
            <form action="{{ route('seller.coupon.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div>
                    <label for="code" class="form-label">Mã <span class="text-red-500">*</span></label>
                    <input type="text" class="form-control @error('code') border-red-500 @enderror" id="code"
                        name="code" value="{{ old('code') }}" placeholder="Nhập mã giảm giá">
                    @error('code')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="name" class="form-label">Tên <span class="text-red-500">*</span></label>
                    <input type="text" class="form-control @error('name') border-red-500 @enderror" id="name"
                        name="name" value="{{ old('name') }}" placeholder="Tên mã giảm giá">
                    @error('name')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea class="form-control @error('description') border-red-500 @enderror" id="description" name="description"
                        rows="2" placeholder="Mô tả ngắn về mã giảm giá">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="image" class="form-label">Ảnh mã giảm giá (tùy chọn)</label>
                    <input type="file" class="form-control-file @error('image') border-red-500 @enderror" id="image"
                        name="image" accept="image/*">
                    <p class="text-sm text-gray-500 mt-1">Để trống nếu không muốn thêm ảnh.</p>
                    @error('image')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="discount_value" class="form-label">Giá trị giảm giá <span
                                class="text-red-500">*</span></label>
                        <input type="number" class="form-control @error('discount_value') border-red-500 @enderror"
                            id="discount_value" name="discount_value" value="{{ old('discount_value') }}" step="0.01"
                            placeholder="Nhập giá trị">
                        @error('discount_value')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="discount_type" class="form-label">Loại giảm giá <span
                                class="text-red-500">*</span></label>
                        <select class="form-select form-select-admin @error('discount_type') border-red-500 @enderror"
                            id="discount_type" name="discount_type">
                            <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>
                                Phần trăm</option>
                            <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Cố định
                            </option>
                        </select>
                        @error('discount_type')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="max_discount_amount" class="form-label">Số tiền giảm tối đa (tùy chọn)</label>
                        <input type="number" class="form-control @error('max_discount_amount') border-red-500 @enderror"
                            id="max_discount_amount" name="max_discount_amount" value="{{ old('max_discount_amount') }}"
                            step="0.01" placeholder="Nhập số tiền tối đa (nếu có)">
                        @error('max_discount_amount')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="min_order_amount" class="form-label">Đơn hàng tối thiểu (tùy chọn)</label>
                        <input type="number" class="form-control @error('min_order_amount') border-red-500 @enderror"
                            id="min_order_amount" name="min_order_amount" value="{{ old('min_order_amount') }}"
                            step="0.01" placeholder="Nhập số tiền tối thiểu (nếu có)">
                        @error('min_order_amount')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="quantity" class="form-label">Số lượng <span class="text-red-500">*</span></label>
                        <input type="number" class="form-control @error('quantity') border-red-500 @enderror"
                            id="quantity" name="quantity" value="{{ old('quantity') }}" placeholder="Số lượng">
                        @error('quantity')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="max_uses_per_user" class="form-label">Số lần dùng mỗi người (tùy chọn)</label>
                        <input type="number" class="form-control @error('max_uses_per_user') border-red-500 @enderror"
                            id="max_uses_per_user" name="max_uses_per_user" value="{{ old('max_uses_per_user') }}"
                            placeholder="Nhập số lần tối đa/người">
                        @error('max_uses_per_user')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="max_uses_total" class="form-label">Tổng số lần sử dụng (tùy chọn)</label>
                        <input type="number" class="form-control @error('max_uses_total') border-red-500 @enderror"
                            id="max_uses_total" name="max_uses_total" value="{{ old('max_uses_total') }}"
                            placeholder="Nhập tổng số lần">
                        @error('max_uses_total')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="rank_limit" class="form-label">Hạn chế theo hạng</label>
                        <select class="form-select form-select-admin @error('rank_limit') border-red-500 @enderror"
                            id="rank_limit" name="rank_limit">
                            <option value="all" {{ old('rank_limit') == 'all' ? 'selected' : '' }}>Tất cả</option>
                            <option value="gold" {{ old('rank_limit') == 'gold' ? 'selected' : '' }}>Vàng</option>
                            <option value="silver" {{ old('rank_limit') == 'silver' ? 'selected' : '' }}>Bạc</option>
                            <option value="bronze" {{ old('rank_limit') == 'bronze' ? 'selected' : '' }}>Đồng</option>
                            <option value="diamond" {{ old('rank_limit') == 'diamond' ? 'selected' : '' }}>Kim cương
                            </option>
                        </select>
                        @error('rank_limit')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!-- Ngày bắt đầu -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="start_day" class="form-label">Ngày bắt đầu <span
                                class="text-red-500">*</span></label>
                        <select class="form-select form-select-admin @error('start_day') border-red-500 @enderror"
                            id="start_day" name="start_day">
                            <option value="">Chọn ngày</option>
                            @for ($day = 1; $day <= 31; $day++)
                                <option value="{{ $day }}" {{ old('start_day') == $day ? 'selected' : '' }}>
                                    {{ $day }}</option>
                            @endfor
                        </select>
                        @error('start_day')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="start_month" class="form-label">Tháng <span class="text-red-500">*</span></label>
                        <select class="form-select form-select-admin @error('start_month') border-red-500 @enderror"
                            id="start_month" name="start_month">
                            <option value="">Chọn tháng</option>
                            @for ($month = 1; $month <= 12; $month++)
                                <option value="{{ $month }}" {{ old('start_month') == $month ? 'selected' : '' }}>
                                    {{ $month }}</option>
                            @endfor
                        </select>
                        @error('start_month')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="start_year" class="form-label">Năm <span class="text-red-500">*</span></label>
                        <select class="form-select form-select-admin @error('start_year') border-red-500 @enderror"
                            id="start_year" name="start_year">
                            <option value="">Chọn năm</option>
                            @for ($year = now()->year; $year <= now()->year + 5; $year++)
                                <option value="{{ $year }}" {{ old('start_year') == $year ? 'selected' : '' }}>
                                    {{ $year }}</option>
                            @endfor
                        </select>
                        @error('start_year')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!-- Ngày kết thúc -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="end_day" class="form-label">Ngày kết thúc <span
                                class="text-red-500">*</span></label>
                        <select class="form-select form-select-admin @error('end_day') border-red-500 @enderror"
                            id="end_day" name="end_day">
                            <option value="">Chọn ngày</option>
                            @for ($day = 1; $day <= 31; $day++)
                                <option value="{{ $day }}" {{ old('end_day') == $day ? 'selected' : '' }}>
                                    {{ $day }}</option>
                            @endfor
                        </select>
                        @error('end_day')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="end_month" class="form-label">Tháng <span class="text-red-500">*</span></label>
                        <select class="form-select form-select-admin @error('end_month') border-red-500 @enderror"
                            id="end_month" name="end_month">
                            <option value="">Chọn tháng</option>
                            @for ($month = 1; $month <= 12; $month++)
                                <option value="{{ $month }}" {{ old('end_month') == $month ? 'selected' : '' }}>
                                    {{ $month }}</option>
                            @endfor
                        </select>
                        @error('end_month')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="end_year" class="form-label">Năm <span class="text-red-500">*</span></label>
                        <select class="form-select form-select-admin @error('end_year') border-red-500 @enderror"
                            id="end_year" name="end_year">
                            <option value="">Chọn năm</option>
                            @for ($year = now()->year; $year <= now()->year + 5; $year++)
                                <option value="{{ $year }}" {{ old('end_year') == $year ? 'selected' : '' }}>
                                    {{ $year }}</option>
                            @endfor
                        </select>
                        @error('end_year')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="flex gap-6 items-center mt-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}
                            class="form-checkbox">
                        <span class="ml-2">Kích hoạt</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}
                            class="form-checkbox">
                        <span class="ml-2">Công khai</span>
                    </label>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <a href="{{ route('seller.coupon.index') }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded-md transition-all duration-300">Hủy</a>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition-all duration-300 font-semibold">Thêm
                        mã giảm giá</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const startDay = document.getElementById('start_day');
            const startMonth = document.getElementById('start_month');
            const startYear = document.getElementById('start_year');
            const endDay = document.getElementById('end_day');
            const endMonth = document.getElementById('end_month');
            const endYear = document.getElementById('end_year');

            function validateDates() {
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                // Kiểm tra start date
                if (!startDay.value || !startMonth.value || !startYear.value) {
                    startDay.setCustomValidity('Vui lòng chọn đầy đủ ngày, tháng, năm bắt đầu.');
                    return false;
                }

                const startDate = new Date(startYear.value, startMonth.value - 1, startDay.value);
                if (isNaN(startDate.getTime()) || startDate < today) {
                    startDay.setCustomValidity('Ngày bắt đầu không hợp lệ hoặc trước ngày hiện tại.');
                    return false;
                } else {
                    startDay.setCustomValidity('');
                }

                // Kiểm tra end date
                if (!endDay.value || !endMonth.value || !endYear.value) {
                    endDay.setCustomValidity('Vui lòng chọn đầy đủ ngày, tháng, năm kết thúc.');
                    return false;
                }

                const endDate = new Date(endYear.value, endMonth.value - 1, endDay.value);
                if (isNaN(endDate.getTime())) {
                    endDay.setCustomValidity('Ngày kết thúc không hợp lệ.');
                    return false;
                }

                if (endDate <= startDate) {
                    endDay.setCustomValidity('Ngày kết thúc phải sau ngày bắt đầu.');
                    return false;
                } else {
                    endDay.setCustomValidity('');
                }

                return true;
            }

            [startDay, startMonth, startYear, endDay, endMonth, endYear].forEach(input => {
                input.addEventListener('change', validateDates);
            });

            form.addEventListener('submit', function(e) {
                console.log('Form submitting with dates:', {
                    start_day: startDay.value,
                    start_month: startMonth.value,
                    start_year: startYear.value,
                    end_day: endDay.value,
                    end_month: endMonth.value,
                    end_year: endYear.value
                });

                if (!validateDates()) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
