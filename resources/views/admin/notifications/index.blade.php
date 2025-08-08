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

        <table class="w-full text-xs text-left text-gray-400 border-t border-gray-100">
            <thead class="text-gray-300 font-semibold border-b border-gray-100">
                <tr>
                    <th class="w-6 py-3 pr-6">
                        <input id="select-all" class="w-[18px] h-[18px]" aria-label="Chọn tất cả thông báo"
                            type="checkbox" />
                    </th>
                    <th class="py-3">Tiêu đề</th>
                    <th class="py-3">Nội dung</th>
                    <th class="py-3">Người gửi</th>
                    <th class="py-3">Loại người nhận</th>
                    <th class="py-3">Độ ưu tiên</th>
                    <th class="py-3">Loại thông báo</th>
                    <th class="py-3">Trạng thái</th>
                    <th class="py-3">Ngày tạo</th>
                    <th class="py-3">Ngày cập nhật</th>
                    <th class="py-3 pr-6 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                @foreach ($notifications as $notification)
                    <tr>
                        <td class="py-4 pr-6">
                            <input class="select-item w-[18px] h-[18px]" aria-label="Chọn {{ $notification['title'] }}"
                                type="checkbox" />
                        </td>
                        <td class="py-4">
                            <span class="font-semibold text-[13px]">{{ $notification['title'] }}</span>
                        </td>
                        <td class="py-4 text-[13px]">{{ $notification['content'] }}</td>
                        <td class="py-4 text-[13px]">{{ $notification['sender']?->username ?? 'Không có' }}</td>
                        <td class="py-4 text-[13px]">{{ $notification['receiver_type'] }}</td>
                        <td class="py-4">
                            <span
                                class="inline-block {{ $notification['priority'] == 'high' ? 'bg-red-100 text-red-600' : ($notification['priority'] == 'medium' ? 'bg-yellow-100 text-yellow-600' : 'bg-green-100 text-green-600') }} text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                {{ $notification['priority'] == 'high' ? 'Cao' : ($notification['priority'] == 'medium' ? 'Trung bình' : 'Thấp') }}
                            </span>
                        </td>
                        <td class="py-4 text-[13px]">{{ $notification['type'] }}</td>
                        <td class="py-4">
                            <span
                                class="inline-block {{ $notification['status'] == 'active' ? 'bg-green-100 text-green-600' : ($notification['status'] == 'inactive' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600') }} text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                {{ ucfirst($notification['status']) }}
                            </span>
                        </td>
                        <td class="py-4 text-[13px]">{{ $notification['created_at'] }}</td>
                        <td class="py-4 text-[13px]">{{ $notification['updated_at'] }}</td>
                        <td class="py-4 pr-6 text-right flex items-center gap-2 justify-end">
                            <a href="{{ route('admin.notifications.edit', $notification['id']) }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-md focus:outline-none">
                                <i class="fas fa-pencil-alt text-xs"></i>
                            </a>
                            <form action="{{ route('admin.notifications.destroy', $notification['id']) }}" method="POST"
                                onsubmit="return confirm('Bạn có chắc muốn xóa thông báo này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="Delete {{ $notification['title'] }}"
                                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md focus:outline-none">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if ($notifications->isEmpty())
                    <tr>
                        <td colspan="11" class="text-center text-gray-400 py-4">No notifications found</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="mt-6 flex items-center justify-between text-[11px] text-gray-500 select-none">
            <div>
                Hiển thị {{ $notifications->count() }} thông báo trên {{ $notifications->total() }} thông báo
            </div>
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
    </section>

    @push('scripts')
        <script>
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
