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
