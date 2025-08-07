@foreach ($orders as $order)
    <tr data-order-id="{{ $order->id }}">
        <!-- ...các cột như trong index.blade.php... -->
        <td class="py-4 pr-6">
            <input class="select-item w-[18px] h-[18px]" aria-label="Chọn {{ $order->order_code }}" type="checkbox" />
        </td>
        <td class="py-4 text-[13px]">{{ $order->order_code }}</td>
        <td class="py-4 text-[13px]">{{ $order->user ? $order->user->fullname : 'Khách vãng lai' }}</td>
        <td class="py-4 text-[13px]">
            {{ optional($order->shopOrders->first())->shop ? $order->shopOrders->first()->shop->shop_name : 'Không xác định' }}
        </td>
        <td class="py-4 text-[13px]">{{ $order->items->sum('quantity') }}</td>
        <td class="py-4 text-[13px]">{{ number_format($order->final_price, 2) }} VNĐ</td>
        <td class="py-4">
            @php
                $shopOrder = $order->shopOrders->first();
                $shopOrderStatus = $shopOrder ? $shopOrder->status : null;
                switch ($shopOrderStatus) {
                    case 'pending': $statusClass = 'bg-yellow-100 text-yellow-600'; $statusText = 'Chờ xác nhận'; break;
                    case 'confirmed': $statusClass = 'bg-blue-100 text-blue-600'; $statusText = 'Đã xác nhận'; break;
                    case 'ready_to_pick': $statusClass = 'bg-blue-100 text-blue-600'; $statusText = 'Sẵn sàng lấy hàng'; break;
                    case 'picked': $statusClass = 'bg-purple-100 text-purple-600'; $statusText = 'Đã lấy hàng'; break;
                    case 'shipping': $statusClass = 'bg-purple-100 text-purple-600'; $statusText = 'Đang giao hàng'; break;
                    case 'delivered': $statusClass = 'bg-green-100 text-green-600'; $statusText = 'Đã giao hàng'; break;
                    case 'cancelled': $statusClass = 'bg-red-100 text-red-600'; $statusText = 'Đã hủy'; break;
                    case 'shipping_failed': $statusClass = 'bg-red-100 text-red-600'; $statusText = 'Giao hàng thất bại'; break;
                    case 'returned': $statusClass = 'bg-gray-200 text-gray-600'; $statusText = 'Đã trả hàng'; break;
                    case 'completed': $statusClass = 'bg-green-100 text-green-600'; $statusText = 'Hoàn thành'; break;
                    case 'damage': $statusClass = 'bg-red-100 text-red-600'; $statusText = 'Hư hỏng'; break;
                    case 'lost': $statusClass = 'bg-red-100 text-red-600'; $statusText = 'Thất lạc'; break;
                    default: $statusClass = 'bg-gray-100 text-gray-600'; $statusText = 'Không xác định';
                }
            @endphp
            <span class="inline-block {{ $statusClass }} text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                {{ $statusText }}
            </span>
        </td>
        <td class="py-4 text-[13px]">{{ $order->created_at->format('d/m/Y') }}</td>
        <td class="py-4 pr-6 flex items-center justify-end">
            <div class="bg-[#f2f2f6] hover:bg-[#0B8AFF] hover:text-white w-[37px] h-[35px] rounded-md flex items-center justify-center">
                <a href="{{ route('admin.orders.show', $order->id) }}" class="transition-all duration-300">
                    <i class="fas fa-eye" title="Xem chi tiết"></i>
                </a>
            </div>
        </td>
    </tr>
@endforeach
@if ($orders->isEmpty())
    <tr>
        <td colspan="9" class="text-center text-gray-400 py-4">
            @if (request('search') || request('status') || request('shop_id'))
                Không tìm thấy đơn hàng nào phù hợp với bộ lọc hiện tại
            @else
                Không tìm thấy đơn hàng nào
            @endif
        </td>
    </tr>
@endif