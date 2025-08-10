@extends('layouts.admin')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Quản Lý Mã Giảm Giá</h1>
        <div class="admin-breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="admin-breadcrumb-link">Dashboard</a> / Mã Giảm Giá
        </div>
    </div>

    <!-- Thống Kê Tổng Quan -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-tag text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tổng Mã Giảm Giá</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalCoupons }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Đang Hoạt Động</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $activeCoupons }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sắp Hết Hạn</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $expiringSoon }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Đã Hết Hạn</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $expiredCoupons }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Thanh Tìm Kiếm và Lọc -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <input type="text" id="search" placeholder="Tìm kiếm mã giảm giá..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-2">
                <select id="status-filter" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tất Cả Trạng Thái</option>
                    <option value="active">Đang Hoạt Động</option>
                    <option value="inactive">Không Hoạt Động</option>
                    <option value="expired">Đã Hết Hạn</option>
                    <option value="expiring">Sắp Hết Hạn</option>
                </select>
                
                <select id="type-filter" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tất Cả Loại</option>
                    <option value="percentage">Phần Trăm</option>
                    <option value="fixed">Số Tiền Cố Định</option>
                </select>
                
                <a href="{{ route('admin.coupon.create') }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Thêm Mới
                </a>
            </div>
        </div>
    </div>

    <!-- Bảng Danh Sách -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mã Giảm Giá
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tên
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Giá Trị
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Trạng Thái
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ngày Hết Hạn
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Số Lần Sử Dụng
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thao Tác
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($coupons as $coupon)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($coupon->image)
                                        <img src="{{ asset('storage/' . $coupon->image) }}" alt="{{ $coupon->name }}" 
                                             class="w-8 h-8 rounded object-cover mr-3">
                                    @else
                                        <div class="w-8 h-8 bg-gradient-to-r from-yellow-400 to-orange-500 rounded flex items-center justify-center mr-3">
                                            <i class="fas fa-tag text-white text-sm"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $coupon->code }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($coupon->description, 30) }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $coupon->name }}</div>
                                <div class="text-sm text-gray-500">
                                    @if($coupon->is_public)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            Công Khai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            Riêng Tư
                                        </span>
                                    @endif
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    @if($coupon->discount_type == 'percentage')
                                        {{ $coupon->discount_value }}%
                                    @else
                                        {{ number_format($coupon->discount_value) }}đ
                                    @endif
                                </div>
                                @if($coupon->max_discount_amount)
                                    <div class="text-sm text-gray-500">
                                        Tối đa: {{ number_format($coupon->max_discount_amount) }}đ
                                    </div>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($coupon->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Đang Hoạt Động
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Không Hoạt Động
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $coupon->end_date ? $coupon->end_date->format('d/m/Y') : 'N/A' }}
                                </div>
                                @if($coupon->end_date)
                                    @if($coupon->end_date->isPast())
                                        <div class="text-sm text-red-500">Đã hết hạn</div>
                                    @elseif($coupon->end_date->diffInDays(now()) <= 7)
                                        <div class="text-sm text-yellow-500">Sắp hết hạn</div>
                                    @endif
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $coupon->used_count ?? 0 }} / {{ $coupon->max_uses_total ?? '∞' }}
                                </div>
                                @if($coupon->max_uses_per_user)
                                    <div class="text-sm text-gray-500">
                                        {{ $coupon->max_uses_per_user }} lần/người
                                    </div>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.coupon.edit', $coupon->id) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <button onclick="toggleCouponStatus({{ $coupon->id }})" 
                                            class="text-{{ $coupon->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $coupon->is_active ? 'yellow' : 'green' }}-900">
                                        <i class="fas fa-{{ $coupon->is_active ? 'pause' : 'play' }}"></i>
                                    </button>
                                    
                                    <button onclick="deleteCoupon({{ $coupon->id }})" 
                                            class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-tag text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">Chưa có mã giảm giá nào</p>
                                    <p class="text-sm">Bắt đầu tạo mã giảm giá đầu tiên của bạn</p>
                                    <a href="{{ route('admin.coupon.create') }}" 
                                       class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tạo Mã Giảm Giá
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($coupons->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    // Tìm kiếm và lọc
    document.getElementById('search').addEventListener('input', function() {
        filterCoupons();
    });
    
    document.getElementById('status-filter').addEventListener('change', function() {
        filterCoupons();
    });
    
    document.getElementById('type-filter').addEventListener('change', function() {
        filterCoupons();
    });
    
    function filterCoupons() {
        const search = document.getElementById('search').value.toLowerCase();
        const status = document.getElementById('status-filter').value;
        const type = document.getElementById('type-filter').value;
        
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            if (row.cells.length === 1) return; // Skip empty row
            
            const code = row.cells[0].textContent.toLowerCase();
            const name = row.cells[1].textContent.toLowerCase();
            const discountType = row.cells[2].textContent.toLowerCase();
            const isActive = row.cells[3].textContent.includes('Đang Hoạt Động');
            
            let show = true;
            
            // Tìm kiếm
            if (search && !code.includes(search) && !name.includes(search)) {
                show = false;
            }
            
            // Lọc trạng thái
            if (status) {
                if (status === 'active' && !isActive) show = false;
                if (status === 'inactive' && isActive) show = false;
                if (status === 'expired' && !row.cells[4].textContent.includes('Đã hết hạn')) show = false;
                if (status === 'expiring' && !row.cells[4].textContent.includes('Sắp hết hạn')) show = false;
            }
            
            // Lọc loại
            if (type) {
                if (type === 'percentage' && !discountType.includes('%')) show = false;
                if (type === 'fixed' && !discountType.includes('đ')) show = false;
            }
            
            row.style.display = show ? '' : 'none';
        });
    }
    
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
                    location.reload();
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