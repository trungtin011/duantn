@extends('layouts.admin')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/coupon.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Thêm mã giảm giá</h1>
        <div class="admin-breadcrumb">
            <a href="{{ route('admin.coupon.index') }}" class="admin-breadcrumb-link">Mã giảm giá</a> / Thêm mới
        </div>
    </div>

    @include('layouts.notification')

    <div class="row g-3">
        <div class="col-md-12">
            <div class="admin-card">
                <form action="{{ route('admin.coupon.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="code" class="form-label">Mã</label>
                        <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}">
                        @error('code')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Tên</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                        @error('name')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Ảnh mã giảm giá</label>
                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="discount_value" class="form-label">Giá trị giảm giá</label>
                        <input type="number" class="form-control" id="discount_value" name="discount_value" value="{{ old('discount_value') }}" step="0.01">
                        @error('discount_value')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="discount_type" class="form-label">Loại giảm giá</label>
                        <select class="form-select form-select-admin" id="discount_type" name="discount_type">
                            <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Phần trăm</option>
                            <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Cố định</option>
                        </select>
                        @error('discount_type')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="max_discount_amount" class="form-label">Số tiền giảm tối đa</label>
                        <input type="number" class="form-control" id="max_discount_amount" name="max_discount_amount" value="{{ old('max_discount_amount') }}" step="0.01">
                        @error('max_discount_amount')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="min_order_amount" class="form-label">Số tiền đơn hàng tối thiểu</label>
                        <input type="number" class="form-control" id="min_order_amount" name="min_order_amount" value="{{ old('min_order_amount') }}" step="0.01">
                        @error('min_order_amount')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Số lượng</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity') }}">
                        @error('quantity')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="max_uses_per_user" class="form-label">Số lần sử dụng tối đa mỗi người</label>
                        <input type="number" class="form-control" id="max_uses_per_user" name="max_uses_per_user" value="{{ old('max_uses_per_user') }}">
                        @error('max_uses_per_user')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="max_uses_total" class="form-label">Tổng số lần sử dụng tối đa</label>
                        <input type="number" class="form-control" id="max_uses_total" name="max_uses_total" value="{{ old('max_uses_total') }}">
                        @error('max_uses_total')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="start_date" class="form-label">Ngày bắt đầu</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}">
                        @error('start_date')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="end_date" class="form-label">Ngày kết thúc</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}">
                        @error('end_date')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="rank_limit" class="form-label">Hạn chế theo hạng</label>
                        <select class="form-select form-select-admin" id="rank_limit" name="rank_limit">
                            <option value="all" {{ old('rank_limit') == 'all' ? 'selected' : '' }}>Tất cả</option>
                            <option value="gold" {{ old('rank_limit') == 'gold' ? 'selected' : '' }}>Vàng</option>
                            <option value="silver" {{ old('rank_limit') == 'silver' ? 'selected' : '' }}>Bạc</option>
                            <option value="bronze" {{ old('rank_limit') == 'bronze' ? 'selected' : '' }}>Đồng</option>
                            <option value="diamond" {{ old('rank_limit') == 'diamond' ? 'selected' : '' }}>Kim cương</option>
                        </select>
                        @error('rank_limit')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} class="form-checkbox">
                            <span class="ml-2">Kích hoạt</span>
                        </label>
                        @error('is_active')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }} class="form-checkbox">
                            <span class="ml-2">Công khai</span>
                        </label>
                        @error('is_public')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.coupon.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded-md transition-all duration-300">Hủy</a>
                        <button type="submit"
                            class="bg-[#28BCF9] hover:bg-[#3DA5F7] text-white py-2 px-4 rounded-md transition-all duration-300">Thêm mã giảm giá</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection