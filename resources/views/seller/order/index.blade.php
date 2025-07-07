@extends('layouts.seller_home')

@section('title', 'Quản lý đơn hàng')


@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <div class="container mx-auto py-5">
        <h1 class="text-2xl font-bold mb-6">Danh sách đơn hàng</h1>

        @if ($orders->isEmpty())
            <p class="p-4 text-gray-600">Không có đơn hàng nào.</p>
        @else
            <table class="w-full table-auto border-collapse border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border border-gray-300 py-2 px-4">Mã đơn hàng</th>
                        <th class="border border-gray-300 py-2 px-4">Khách hàng</th>
                        <th class="border border-gray-300 py-2 px-4">Tổng tiền</th>
                        <th class="border border-gray-300 py-2 px-4">Địa chỉ nhận hàng</th>
                        <th class="border border-gray-300 py-2 px-4">Trạng thái hiện tại</th>
                        <th class="border border-gray-300 py-2 px-4">Sản phẩm</th>
                        <th class="border border-gray-300 py-2 px-4">Ngày đặt</th>
                        <th class="border border-gray-300 py-2 px-4">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        @php
                            $statusClasses = [
                                'pending' => 'bg-yellow-200 text-yellow-800',
                                'confirmed' => 'bg-blue-200 text-blue-800',
                                'ready_to_pick' => 'bg-purple-200 text-purple-800',
                                'picked' => 'bg-green-200 text-green-800',
                                'shipping' => 'bg-green-200 text-green-800',
                                'delivered' => 'bg-red-200 text-red-800',
                                'cancelled' => 'bg-red-200 text-red-800',
                                'shipping_failed' => 'bg-red-200 text-red-800',
                                'returned' => 'bg-red-200 text-red-800',
                                'completed' => 'bg-green-200 text-green-800',
                            ];
                            $currentStatusClass = $statusClasses[$order->status] ?? 'bg-gray-200 text-gray-800';
                        @endphp
                        <tr class="border border-gray-300">
                            <td class="py-2 px-4">{{ $order->code }}</td>
                            <td class="py-2 px-4">{{ $order->order->address->receiver_name ?? 'Khách vãng lai' }}</td>
                            <td class="py-2 px-4">{{ number_format($order->items->sum('unit_price'), 0, ',', '.') }}đ</td>
                            <td class="py-2 px-4">
                                @if ($order->order->address)
                                    {{ $order->order->address->receiver_name }}<br>
                                    {{ $order->order->address->address }}, {{ $order->order->address->ward }},
                                    {{ $order->order->address->district }}, {{ $order->order->address->province }}<br>
                                    Điện thoại: {{ $order->order->address->receiver_phone }}
                                @else
                                    Chưa có địa chỉ
                                @endif
                            </td>
                            <td class="py-2 px-4">
                                <span class="inline-block px-2 py-1 rounded {{ $currentStatusClass }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="py-2 px-4">
                                <ul>
                                    @foreach ($order->items as $item)
                                        <li>
                                            <div class="flex items-center space-x-2">
                                                <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}"
                                                    class="w-10 h-10 object-cover rounded">
                                                <div>
                                                    <p class="font-semibold">{{ $item->product_name }}</p>
                                                    <p>Số lượng: {{ $item->quantity }} x
                                                        {{ number_format($item->unit_price, 0, ',', '.') }}đ</p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="py-2 px-4">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-2 px-4">
                                <a href="{{ route('seller.order.show', $order->order->order_code) }}"
                                    class="text-blue-600 hover:underline">Xem chi tiết</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
