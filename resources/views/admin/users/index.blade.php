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
                <form method="GET" action="{{ route('admin.users.index') }}">
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Trạng thái:</span>
                        <select name="status" id="statusFilter"
                            class="dropdown px-3 py-2 text-gray-600 text-xs focus:outline-none w-[100px]">
                            <option value="">Tất cả</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động
                            </option>
                        </select>
                    </div>
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
                    <th scope="col">Thao tác</th>
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
                        <td class="py-4 text-[13px]">
                            <img src="">
                            {{ $user->username }}
                        </td>
                        <td class="py-4 text-[13px]">{{ $user->fullname }}</td>
                        <td class="py-4 text-[13px]">{{ $user->phone }}</td>
                        <td class="py-4 text-[13px]">{{ $user->email }}</td>
                        <td class="py-4">
                            <span
                                class="inline-block {{ $user->status->value == 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                {{ $user->status->value == 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                            </span>
                        </td>
                        <td class="py-4 text-[13px]">{{ $user->gender == 'male' ? 'Nam' : 'Nữ' }}</td>
                        <td class="py-4 text-[13px]">{{ $user->birthday ? $user->birthday->format('d/m/Y') : '-' }}</td>
                        <td class="py-4 text-[13px]">{{ $user->role == 'admin' ? 'Quản trị viên' : 'Khách hàng' }}</td>
                        <td class="py-4 pr-6 flex items-center justify-end">
                            <div
                                class="bg-[#f2f2f6] hover:bg-[#0B8AFF] hover:text-white w-[37px] h-[35px] rounded-md flex items-center justify-center mr-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="transition-all duration-300">
                                    <i class="fas fa-edit" title="Chỉnh sửa"></i>
                                </a>
                            </div>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline"
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
