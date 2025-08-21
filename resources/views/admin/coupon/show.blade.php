@extends('layouts.admin')

@section('title', 'Chi Tiết Mã Giảm Giá')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Chi Tiết Mã Giảm Giá</h1>
        <div class="admin-breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="admin-breadcrumb-link">Dashboard</a> / 
            <a href="{{ route('admin.coupon.index') }}" class="admin-breadcrumb-link">Mã Giảm Giá</a> / 
            {{ $coupon->name }}
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Cột Trái - Thông Tin Chi Tiết -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Thông Tin Cơ Bản -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Thông Tin Cơ Bản</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Mã Giảm Giá</label>
                        <p class="mt-1 text-sm text-gray-900 font-mono bg-gray-50 px-3 py-2 rounded">{{ $coupon->code }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tên</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $coupon->name }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500">Mô Tả</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $coupon->description ?: 'Không có mô tả' }}</p>
                    </div>
                </div>
            </div>

            <!-- Thông Tin Giảm Giá -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Thông Tin Giảm Giá</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Giá Trị Giảm Giá</label>
                        <p class="mt-1 text-lg font-semibold text-blue-600">
                            @if($coupon->discount_type == 'percentage')
                                {{ $coupon->discount_value }}%
                            @else
                                {{ number_format($coupon->discount_value) }}đ
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Loại Giảm Giá</label>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($coupon->discount_type == 'percentage')
                                Phần Trăm
                            @else
                                Số Tiền Cố Định
                            @endif
                        </p>
                    </div>
                    @if($coupon->max_discount_amount)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Giảm Tối Đa</label>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($coupon->max_discount_amount) }}đ</p>
                        </div>
                    @endif
                    @if($coupon->min_order_amount)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Đơn Hàng Tối Thiểu</label>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($coupon->min_order_amount) }}đ</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Thông Tin Sử Dụng -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Thông Tin Sử Dụng</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Số Lượng</label>
                        <p class="mt-1 text-sm text-gray-900">{{ number_format($coupon->quantity) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Đã Sử Dụng</label>
                        <p class="mt-1 text-sm text-gray-900">{{ number_format($coupon->used_count ?? 0) }}</p>
                    </div>
                    @if($coupon->max_uses_per_user)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Sử Dụng Tối Đa/User</label>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($coupon->max_uses_per_user) }}</p>
                        </div>
                    @endif
                    @if($coupon->max_uses_total)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tổng Sử Dụng Tối Đa</label>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($coupon->max_uses_total) }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Thông Tin Thời Gian -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Thông Tin Thời Gian</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Ngày Bắt Đầu</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $coupon->start_date ? $coupon->start_date->format('d/m/Y H:i') : 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Ngày Kết Thúc</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $coupon->end_date ? $coupon->end_date->format('d/m/Y H:i') : 'N/A' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500">Trạng Thái Hết Hạn</label>
                        <p class="mt-1">
                            @if($coupon->end_date)
                                @if($coupon->end_date->isPast())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Đã Hết Hạn
                                    </span>
                                @elseif($coupon->end_date->diffInDays(now()) <= 7)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Sắp Hết Hạn ({{ $coupon->end_date->diffInDays(now()) }} ngày)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Còn Hạn ({{ $coupon->end_date->diffInDays(now()) }} ngày)
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-500">Không có ngày hết hạn</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cột Phải - Thanh Bên -->
        <div class="space-y-6">
            <!-- Hình Ảnh -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-700 mb-4">Hình Ảnh</h3>
                @if($coupon->image)
                    <div class="text-center">
                        <img src="{{ asset('storage/' . $coupon->image) }}" alt="{{ $coupon->name }}" 
                             class="w-32 h-32 object-cover rounded-lg mx-auto">
                    </div>
                @else
                    <div class="w-32 h-32 mx-auto bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tag text-white text-4xl"></i>
                    </div>
                @endif
            </div>

            <!-- Trạng Thái -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-700 mb-4">Trạng Thái</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Kích Hoạt:</span>
                        @if($coupon->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Đang Hoạt Động
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Không Hoạt Động
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Công Khai:</span>
                        @if($coupon->is_public)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Công Khai
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Riêng Tư
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Giới Hạn Hạng:</span>
                        <span class="text-sm font-medium text-gray-900">
                            @switch($coupon->rank_limit)
                                @case('gold')
                                    Vàng
                                    @break
                                @case('silver')
                                    Bạc
                                    @break
                                @case('bronze')
                                    Đồng
                                    @break
                                @case('diamond')
                                    Kim Cương
                                    @break
                                @default
                                    Tất Cả Hạng
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>

            <!-- Thông Tin Tạo -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-700 mb-4">Thông Tin Tạo</h3>
                <div class="space-y-2 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">Ngày tạo:</span>
                        <p>{{ $coupon->created_at ? $coupon->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="font-medium">Cập nhật lần cuối:</span>
                        <p>{{ $coupon->updated_at ? $coupon->updated_at->format('d/m/Y H:i') : 'N/A' }}</p>
                    </div>
                    @if($coupon->createdBy)
                        <div>
                            <span class="font-medium">Tạo bởi:</span>
                            <p>{{ $coupon->createdBy->name ?? 'N/A' }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Thao Tác -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-700 mb-4">Thao Tác</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.coupon.edit', $coupon->id) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Chỉnh Sửa
                    </a>
                    
                    <button onclick="toggleCouponStatus({{ $coupon->id }})" 
                            class="w-full flex items-center justify-center px-4 py-2 bg-{{ $coupon->is_active ? 'yellow' : 'green' }}-600 text-white rounded-md hover:bg-{{ $coupon->is_active ? 'yellow' : 'green' }}-700 transition-colors">
                        <i class="fas fa-{{ $coupon->is_active ? 'pause' : 'play' }} mr-2"></i>
                        {{ $coupon->is_active ? 'Tạm Dừng' : 'Kích Hoạt' }}
                    </button>
                    
                    <button onclick="deleteCoupon({{ $coupon->id }})" 
                            class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        <i class="fas fa-trash mr-2"></i>
                        Xóa
                    </button>
                    
                    <a href="{{ route('admin.coupon.index') }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Quay Lại
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Thay đổi trạng thái
    function toggleCouponStatus(couponId) {
        if (confirm('Bạn có chắc muốn thay đổi trạng thái mã giảm giá này?')) {
            fetch(`/admin/coupons/${couponId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi thay đổi trạng thái');
            });
        }
    }
    
    // Xóa mã giảm giá
    function deleteCoupon(couponId) {
        if (confirm('Bạn có chắc muốn xóa mã giảm giá này? Hành động này không thể hoàn tác.')) {
            fetch(`/admin/coupons/${couponId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '{{ route("admin.coupon.index") }}';
                } else {
                    alert('Có lỗi xảy ra: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi xóa mã giảm giá');
            });
        }
    }
</script>
@endpush
