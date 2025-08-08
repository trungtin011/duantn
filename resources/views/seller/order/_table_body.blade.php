@foreach ($orders as $order)
    @php
        $statusClasses = [
            'pending' => 'bg-yellow-100 text-yellow-600',
            'confirmed' => 'bg-blue-100 text-blue-600',
            'ready_to_pick' => 'bg-purple-100 text-purple-600',
            'picked' => 'bg-green-100 text-green-600',
            'shipping' => 'bg-green-100 text-green-600',
            'delivered' => 'bg-red-100 text-red-600',
            'cancelled' => 'bg-red-100 text-red-600',
            'shipping_failed' => 'bg-red-100 text-red-600',
            'returned' => 'bg-red-100 text-red-600',
            'completed' => 'bg-green-100 text-green-600',
        ];
        $currentStatusClass = $statusClasses[$order->status] ?? 'bg-gray-200 text-gray-500';

        $statusLabels = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'ready_to_pick' => 'Sẵn sàng giao',
            'picked' => 'Đã lấy hàng',
            'shipping' => 'Đang giao',
            'delivered' => 'Đã giao',
            'cancelled' => 'Đã hủy',
            'shipping_failed' => 'Giao thất bại',
            'returned' => 'Đã trả',
            'completed' => 'Hoàn thành',
        ];
        $statusLabel = $statusLabels[$order->status] ?? ucfirst($order->status);
    @endphp
    <tr>
        <td class="py-4">
            <span class="font-semibold text-[13px]">{{ $order->code }}</span>
        </td>
        <td class="py-4">
            <div class="text-[13px]">
                <p class="font-semibold">{{ $order->order->address->receiver_name ?? 'Khách vãng lai' }}</p>
                @if ($order->order->address)
                    <p class="text-gray-500 text-[11px]">{{ $order->order->address->receiver_phone }}</p>
                @endif
            </div>
        </td>
        <td class="py-4 text-[13px]">
            <span class="font-semibold">₫{{ number_format($order->items->sum('unit_price'), 0, ',', '.') }}</span>
        </td>
        <td class="py-4">
            <div class="space-y-2">
                @foreach ($order->items as $item)
                    <div class="flex items-center gap-3">
                        <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" class="w-8 h-8 object-cover rounded">
                        <div class="text-[11px]">
                            <p class="font-semibold">{{ $item->product_name }}</p>
                            <p class="text-gray-500">{{ $item->quantity }} x ₫{{ number_format($item->unit_price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </td>
        <td class="py-4">
            <span class="inline-block text-[10px] font-semibold px-2 py-0.5 rounded-md select-none {{ $currentStatusClass }}">
                {{ $statusLabel }}
            </span>
        </td>
        <td class="py-4 text-[13px]">{{ $order->created_at->format('d/m/Y H:i') }}</td>
        <td class="py-4 pr-6 text-right">
            <a href="{{ route('seller.order.show', $order->order->order_code) }}"
                class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-md focus:outline-none">
                <i class="fas fa-eye text-xs" title="Xem chi tiết"></i>
            </a>
        </td>
    </tr>
@endforeach

@if ($orders->isEmpty())
    <tr>
        <td colspan="7" class="text-center text-gray-400 py-4">
            @if (request('search') || request('status') || request('filter_date'))
                Không tìm thấy đơn hàng nào phù hợp với bộ lọc hiện tại
            @else
                Không có đơn hàng nào.
            @endif
        </td>
    </tr>
@endif

