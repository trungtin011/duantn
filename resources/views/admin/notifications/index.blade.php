@extends('layouts.admin')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Thông báo</h1>
        <div class="admin-breadcrumb"><a href="{{ route('admin.dashboard') }}" class="admin-breadcrumb-link">Trang chủ</a> /
            Thông báo</div>
    </div>

    <section class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4 h-[72px]">
            <form method="GET" action="{{ route('admin.notifications.index') }}" class="w-full md:w-[223px] relative">
                <input name="search"
                    class="w-full h-[42px] border border-[#F2F2F6] rounded-md py-2 pl-10 pr-4 text-xs placeholder:text-gray-400 focus:outline-none"
                    placeholder="Tìm kiếm theo tên người dùng" type="text" value="{{ request('search') }}" />
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">
                    <i class="fas fa-search text-[#55585b]"></i>
                </span>
            </form>

            <div class="flex gap-4 items-center h-full">
                <form method="GET" action="{{ route('admin.notifications.index') }}" class="flex items-center gap-4">
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Loại người nhận:</span>
                        <select name="receiver_type" id="receiverTypeFilter"
                            class="dropdown border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="all" {{ request('receiver_type') == 'all' ? 'selected' : '' }}>Tất cả</option>
                            <option value="user" {{ request('receiver_type') == 'user' ? 'selected' : '' }}>Người dùng</option>
                            <option value="shop" {{ request('receiver_type') == 'shop' ? 'selected' : '' }}>Cửa hàng</option>
                            <option value="admin" {{ request('receiver_type') == 'admin' ? 'selected' : '' }}>Quản trị
                            </option>
                            <option value="employee" {{ request('receiver_type') == 'employee' ? 'selected' : '' }}>Nhân viên
                            </option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Trạng thái:</span>
                        <select name="status" id="statusFilter"
                            class="dropdown border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Tất cả</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                Không hoạt động</option>
                            </option>
                        </select>
                    </div>
                </form>
                <a href="{{ route('admin.notifications.create') }}"
                    class="h-[44px] text-[15px] bg-blue-600 text-white px-4 py-2 flex items-center justify-center rounded-md hover:bg-blue-700 focus:outline-none">
                    Thêm thông báo
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-gray-400 border-t border-gray-100">
                <thead class="text-gray-300 font-semibold border-b border-gray-100">
                    <tr>
                        <th class="w-8 py-3 px-2">
                            <input id="select-all" class="w-[16px] h-[16px]" aria-label="Select all notifications"
                                type="checkbox" />
                        </th>
                        <th class="w-16 py-3 px-2 text-center">Ảnh</th>
                        <th class="w-48 py-3 px-2">Tiêu đề</th>
                        <th class="w-64 py-3 px-2">Nội dung</th>
                        <th class="w-32 py-3 px-2">Người gửi</th>
                        <th class="w-28 py-3 px-2">Người nhận</th>
                        <th class="w-24 py-3 px-2">Độ ưu tiên</th>
                        <th class="w-28 py-3 px-2">Loại</th>
                        <th class="w-24 py-3 px-2">Trạng thái</th>
                        <th class="w-32 py-3 px-2">Ngày tạo</th>
                        <th class="w-20 py-3 px-2 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                    @foreach ($notifications as $notification)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-2">
                                <input class="select-item w-[16px] h-[16px]" aria-label="Select {{ $notification['title'] }}"
                                    type="checkbox" />
                            </td>
                            <td class="py-3 px-2 text-center">
                                @if($notification['image_path'])
                                    <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-md overflow-hidden bg-gray-100">
                                        <img src="{{ asset('images/notifications/' . $notification['image_path']) }}" 
                                             alt="Notification Image" 
                                             class="w-full h-full object-contain">
                                    </div>
                                @else
                                    @if(empty($notification['shop_id']))
                                        <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-md overflow-hidden bg-gray-100">
                                            <img src="{{ asset('images/logo.jpg') }}" 
                                                 alt="Platform Default" 
                                                 class="w-full h-full object-contain">
                                        </div>
                                    @else
                                        @php
                                            $shop = \App\Models\Shop::find($notification['shop_id']);
                                        @endphp
                                        @if($shop && $shop->logo)
                                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-md overflow-hidden bg-gray-100">
                                                <img src="{{ asset('storage/' . $shop->logo) }}" 
                                                     alt="Shop Logo" 
                                                     class="w-full h-full object-contain">
                                            </div>
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-md flex items-center justify-center mx-auto">
                                                <i class="fas fa-image text-gray-400 text-lg"></i>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            </td>
                            <td class="py-3 px-2">
                                <span class="font-semibold text-[12px] line-clamp-2">{{ $notification['title'] }}</span>
                            </td>
                            <td class="py-3 px-2">
                                <div class="text-[12px] line-clamp-3 max-h-12 overflow-hidden">
                                    {{ $notification['content'] }}
                                </div>
                            </td>
                            <td class="py-3 px-2">
                                <span class="text-[12px]">{{ $notification['sender']?->username ?? 'N/A' }}</span>
                                @if(!empty($notification['sender_id']))
                                    <span class="ml-1 text-gray-500">(ID: {{ $notification['sender_id'] }})</span>
                                @endif
                            </td>
                            <td class="py-3 px-2">
                                <span class="text-[12px]">
                                    @switch($notification['receiver_type'])
                                        @case('user')
                                            Khách
                                            @break
                                        @case('shop')
                                            Cửa hàng
                                            @break
                                        @case('admin')
                                            Quản trị
                                            @break
                                        @case('employee')
                                            Nhân viên
                                            @break
                                        @case('all')
                                            Tất cả
                                            @break
                                        @default
                                            {{ $notification['receiver_type'] }}
                                    @endswitch
                                    @if(!empty($notification['reference_id']))
                                        <span class="ml-1 text-gray-500">(ID: {{ $notification['reference_id'] }})</span>
                                    @endif
                                </span>
                            </td>
                            <td class="py-3 px-2">
                                <span
                                    class="inline-block {{ $notification['priority'] == 'high' ? 'bg-red-100 text-red-600' : ($notification['priority'] == 'normal' ? 'bg-yellow-100 text-yellow-600' : 'bg-green-100 text-green-600') }} text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                    @switch($notification['priority'])
                                        @case('high')
                                            Cao
                                            @break
                                        @case('normal')
                                            Bình thường
                                            @break
                                        @case('low')
                                            Thấp
                                            @break
                                        @default
                                            {{ ucfirst($notification['priority']) }}
                                    @endswitch
                                </span>
                            </td>
                            <td class="py-3 px-2">
                                <span class="text-[12px]">
                                    @switch($notification['type'])
                                        @case('order')
                                            Đơn hàng
                                            @break
                                        @case('promotion')
                                            Khuyến mãi
                                            @break
                                        @case('system')
                                            Hệ thống
                                            @break
                                        @case('chat')
                                            Tin nhắn
                                            @break
                                        @case('review')
                                            Đánh giá
                                            @break
                                        @case('payment')
                                            Thanh toán
                                            @break
                                        @case('shipping')
                                            Vận chuyển
                                            @break
                                        @default
                                            {{ ucfirst($notification['type']) }}
                                    @endswitch
                                </span>
                            </td>
                            <td class="py-3 px-2">
                                <form action="{{ route('admin.notifications.toggleStatus', $notification['id']) }}" method="POST" class="inline" id="toggle-status-form-{{ $notification['id'] }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button"
                                        onclick="toggleStatus({{ $notification['id'] }})"
                                        class="inline-block focus:outline-none {{ $notification['status'] == 'active' ? 'bg-green-100 text-green-600' : ($notification['status'] == 'inactive' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600') }} text-[10px] font-semibold px-2 py-0.5 rounded-md select-none transition hover:shadow"
                                        title="Đổi trạng thái">
                                        @switch($notification['status'])
                                            @case('active')
                                                Hoạt động
                                                @break
                                            @case('inactive')
                                                Không hoạt động
                                                @break
                                            @case('pending')
                                                Chờ xác nhận
                                                @break
                                            @case('failed')
                                                Thất bại
                                                @break
                                            @default
                                                {{ ucfirst($notification['status']) }}
                                        @endswitch
                                        <i class="fas fa-exchange-alt ml-1"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="py-3 px-2">
                                <span class="text-[11px] text-gray-600">{{ \Carbon\Carbon::parse($notification['created_at'])->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="py-3 px-2 text-right">
                                <div class="flex items-center gap-1 justify-end">
                                    <a href="{{ route('admin.notifications.edit', $notification['id']) }}"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white p-1.5 rounded-md focus:outline-none">
                                        <i class="fas fa-pencil-alt text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.notifications.destroy', $notification['id']) }}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa thông báo này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" aria-label="Delete {{ $notification['title'] }}"
                                            class="bg-red-500 hover:bg-red-600 text-white p-1.5 rounded-md focus:outline-none">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if ($notifications->isEmpty())
                        <tr>
                            <td colspan="11" class="text-center text-gray-400 py-8">Không tìm thấy thông báo nào</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="mt-6 flex items-center justify-between text-[11px] text-gray-500 select-none">
            <div>
                Hiển thị {{ $notifications->count() }} thông báo trên {{ $notifications->total() }} thông báo
            </div>
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
    </section>

    @push('scripts')
        <script>
            function toggleStatus(id) {
                event.preventDefault();
                
                Swal.fire({
                    title: 'Xác nhận',
                    text: 'Bạn có chắc muốn đổi trạng thái thông báo này?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('toggle-status-form-' + id);
                        const formData = new FormData(form);
                        
                        fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Thành công!',
                                    text: data.message,
                                    icon: 'success',
                                    timer: 1500
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Lỗi!',
                                    text: data.message,
                                    icon: 'error'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Lỗi!',
                                text: 'Có lỗi xảy ra khi cập nhật trạng thái',
                                icon: 'error'
                            });
                        });
                    }
                });
            }

            document.querySelectorAll('.receiver-ids').forEach(function(element) {
                element.addEventListener('click', function() {
                    if (this.style.whiteSpace === 'normal') {
                        this.style.whiteSpace = 'nowrap';
                        this.style.textOverflow = 'ellipsis';
                    } else {
                        this.style.whiteSpace = 'normal';
                        this.style.textOverflow = 'inherit';
                    }
                });
            });
        </script>
    @endpush
@endsection
