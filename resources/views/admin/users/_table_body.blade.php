@foreach ($users as $user)
    <tr>
        <td class="py-4 pr-6">
            <input class="select-item w-[18px] h-[18px]" aria-label="Chọn {{ $user->username }}" type="checkbox" />
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
            @if ($user->status->value == 'banned')
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
        <td colspan="10" class="text-center text-gray-400 py-4">Không tìm thấy người dùng</td>
    </tr>
@endif
