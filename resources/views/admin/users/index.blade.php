@extends('layouts.admin')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/user.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Người dùng</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / Danh sách người dùng
        </div>
    </div>

    <section class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4 h-[72px]">
            <form class="w-full md:w-[223px] relative" method="GET" action="{{ route('admin.users.index') }}">
                <input name="search"
                    class="w-full h-[42px] border border-[#F2F2F6] rounded-md py-2 pl-10 pr-4 text-xs placeholder:text-gray-400 focus:outline-none"
                    placeholder="Tìm kiếm theo tên đăng nhập hoặc email" type="text" value="{{ request('search') }}" />
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">
                    <i class="fas fa-search text-[#55585b]"></i>
                </span>
            </form>

            <div class="flex gap-4 items-center h-full">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-4">
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Trạng thái:</span>
                        <select name="status" id="statusFilter"
                            class="dropdown border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none w-[100px]">
                            <option value="">Tất cả</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động
                            </option>
                            <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Đã bị khoá
                            </option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Quyền:</span>
                        <select name="role" id="roleFilter"
                            class="dropdown border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none w-[120px]">
                            <option value="">Tất cả</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                            <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Khách hàng
                            </option>
                            <option value="seller" {{ request('role') == 'seller' ? 'selected' : '' }}>Người bán</option>
                        </select>
                    </div>
                    <button id="resetFilterBtn" type="button"
                        class="text-xs text-white bg-red-500 px-3 py-2 rounded-md hover:bg-red-600 hover:text-white transition-colors"
                        style="display: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <table class="w-full text-xs text-left text-gray-400 border-t border-gray-100">
            <thead class="text-gray-300 font-semibold border-b border-gray-100">
                <tr>
                    <th class="w-6 py-3 pr-6">
                        <input id="select-all" class="w-[18px] h-[18px]" aria-label="Chọn tất cả người dùng"
                            type="checkbox" />
                    </th>
                    <th scope="col">ID</th>
                    <th scope="col">Tên đăng nhập</th>
                    <th scope="col">Họ và tên</th>
                    <th scope="col">Số điện thoại</th>
                    <th scope="col">Email</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Giới tính</th>
                    <th scope="col">Ngày sinh</th>
                    <th scope="col">Quyền</th>
                    <th scope="col" class="text-right pr-[24px]">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                @foreach ($users as $user)
                    <tr>
                        <td class="py-4 pr-6">
                            <input class="select-item w-[18px] h-[18px]" aria-label="Chọn {{ $user->username }}"
                                type="checkbox" />
                        </td>
                        <td class="py-4 text-[13px]">{{ $user->id }}</td>
                        <td class="py-4 text-[13px]">{{ $user->username }}</td>
                        <td class="py-4 text-[13px]">
                            <div class="flex items-center">
                                @include('partials.user-avatar', [
                                    'user' => $user,
                                    'size' => 'sm',
                                    'showName' => true,
                                ])
                            </div>
                        </td>
                        <td class="py-4 text-[13px]">{{ $user->phone }}</td>
                        <td class="py-4 text-[13px]">{{ $user->email }}</td>
                        <td class="py-4">
                            @switch($user->status->value)
                                @case('active')
                                    <span
                                        class="inline-block bg-green-100 text-green-600 text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                        Hoạt động
                                    </span>
                                @break

                                @case('inactive')
                                    <span
                                        class="inline-block bg-red-100 text-red-600 text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                        Không hoạt động
                                    </span>
                                @case('banned')
                                    <span
                                        class="inline-block bg-yellow-100 text-yellow-600 text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                        Đã bị khoá
                                    </span>
                                @break
                            @endswitch
                        </td>
                        <td class="py-4 text-[13px]">
                            @switch($user->gender->value)
                                @case('male')
                                    Nam
                                @break

                                @case('female')
                                    Nữ
                                @break

                                @case('other')
                                    Khác
                                @break

                                @default
                                    -
                            @endswitch
                        </td>
                        <td class="py-4 text-[13px]">{{ $user->birthday ? $user->birthday->format('d/m/Y') : '-' }}</td>
                        <td class="py-4 text-[13px]">
                            @switch($user->role->value)
                                @case('admin')
                                    Quản trị viên
                                    @break
                                @case('customer')
                                    Khách hàng
                                    @break
                                @case('seller')
                                    Người bán
                                    @break
                                @default
                                    -
                            @endswitch
                        </td>
                        <td class="py-4 pr-6 flex items-center justify-end">
                            <div
                                class="bg-[#f2f2f6] hover:bg-[#E8A252] hover:text-white w-[37px] h-[35px] rounded-md flex items-center justify-center mr-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="transition-all duration-300">
                                    <i class="fas fa-edit" title="Chỉnh sửa"></i>
                                </a>
                            </div>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline mr-2"
                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">
                                @csrf
                                @method('DELETE')
                                <div
                                    class="bg-[#f2f2f6] hover:bg-red-500 hover:text-white w-[37px] h-[35px] rounded-md flex items-center justify-center">
                                    <button class="transition-all duration-300">
                                        <i class="fas fa-trash" title="Xóa"></i>
                                    </button>
                                </div>
                            </form>
                            @if($user->status->value == 'banned')
                                <form action="{{ route('admin.users.unban', $user->id) }}" method="POST" class="d-inline unban-form">
                                    @csrf
                                    <div
                                        class="bg-[#f2f2f6] hover:bg-green-500 hover:text-white w-[37px] h-[35px] rounded-md flex items-center justify-center">
                                        <button class="transition-all duration-300" type="button" onclick="showUnbanConfirm(this)">
                                            <i class="fas fa-unlock" title="Mở khóa"></i>
                                        </button>
                                    </div>
                                </form>
                            @else
                                <form action="{{ route('admin.users.ban', $user->id) }}" method="POST" class="d-inline ban-form">
                                    @csrf
                                    <div
                                        class="bg-[#f2f2f6] hover:bg-yellow-500 hover:text-white w-[37px] h-[35px] rounded-md flex items-center justify-center">
                                        <button class="transition-all duration-300" type="button" onclick="showBanConfirm(this)">
                                            <i class="fas fa-ban" title="Ban"></i>
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                @if ($users->isEmpty())
                    <tr>
                        <td colspan="11" class="text-center text-gray-400 py-4">Không tìm thấy người dùng</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="mt-6 flex items-center justify-between text-[11px] text-gray-500 select-none">
            <div>
                Hiển thị {{ $users->count() }} người dùng trên {{ $users->total() }} người dùng
            </div>
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        function showBanConfirm(btn) {
            Swal.fire({
                title: 'Xác nhận ban người dùng?',
                text: 'Bạn có chắc chắn muốn ban người dùng này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ban',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.closest('form').submit();
                }
            });
        }

        function showUnbanConfirm(btn) {
            Swal.fire({
                title: 'Xác nhận mở khóa người dùng?',
                text: 'Bạn có chắc chắn muốn chuyển trạng thái sang hoạt động?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Mở khóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.closest('form').submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const statusFilter = document.getElementById('statusFilter');
            const roleFilter = document.getElementById('roleFilter');
            const searchInput = document.querySelector('input[name="search"]');
            const resetFilterBtn = document.getElementById('resetFilterBtn');
            const tbody = document.querySelector('table tbody');

            function checkShowResetBtn() {
                const hasFilter =
                    (searchInput && searchInput.value) ||
                    (statusFilter && statusFilter.value) ||
                    (roleFilter && roleFilter.value);

                if (resetFilterBtn) {
                    resetFilterBtn.style.display = hasFilter ? 'inline-flex' : 'none';
                }
            }

            function submitFilters() {
                const params = new URLSearchParams();
                if (searchInput && searchInput.value) params.append('search', searchInput.value);
                if (statusFilter && statusFilter.value) params.append('status', statusFilter.value);
                if (roleFilter && roleFilter.value) params.append('role', roleFilter.value);

                fetch("{{ route('admin.users.ajax') }}?" + params.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.text())
                    .then(html => {
                        tbody.innerHTML = html;
                        checkShowResetBtn();
                    });
            }

            if (statusFilter) statusFilter.addEventListener('change', function() {
                submitFilters();
                checkShowResetBtn();
            });
            if (roleFilter) roleFilter.addEventListener('change', function() {
                submitFilters();
                checkShowResetBtn();
            });
            if (searchInput) searchInput.addEventListener('input', function() {
                clearTimeout(this._timer);
                this._timer = setTimeout(function() {
                    submitFilters();
                    checkShowResetBtn();
                }, 400);
            });
            if (resetFilterBtn) {
                resetFilterBtn.addEventListener('click', function() {
                    if (searchInput) searchInput.value = '';
                    if (statusFilter) statusFilter.value = '';
                    if (roleFilter) roleFilter.value = '';
                    submitFilters();
                    checkShowResetBtn();
                });
                checkShowResetBtn();
            }

            @if(session('success'))
                Swal.fire({
                    position: 'top-end',
                    toast: true,
                    icon: 'success',
                    title: 'Thành công',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false,
                    confirmButtonColor: '#3085d6'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    position: 'top-end',
                    toast: true,
                    icon: 'error',
                    title: 'Lỗi',
                    text: '{{ session('error') }}',
                    timer: 3000,
                    showConfirmButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonColor: '#d33'
                });
            @endif
        });
    </script>
@endsection
