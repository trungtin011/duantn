@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-5">
        <h1 class="text-2xl font-bold mb-6">Chi tiết đơn hàng #{{ $order->order_code }}</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg p-4 mb-6">
            <h2 class="text-lg font-semibold mb-2">Thông tin đơn hàng</h2>
            <p><strong>Trạng thái:</strong> <span class="{{ $order->order_status === 'delivered' ? 'text-green-600' : ($order->order_status === 'cancelled' || $order->order_status === 'refunded' ? 'text-red-600' : 'text-blue-600') }}">{{ __('order_status.' . $order->order_status) }}</span></p>
            <p><strong>Tổng tiền:</strong> {{ number_format($order->total_price, 0, ',', '.') }}đ</p>
            @if ($order->coupon)
                <p><strong>Mã giảm giá:</strong> {{ $order->coupon->code }} (Giảm {{ number_format($order->coupon_discount, 0, ',', '.') }}đ)</p>
            @endif
            <p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method }}</p>
            <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            @if ($order->cancel_reason)
                <p><strong>Lý do hủy:</strong> {{ e($order->cancel_reason) }}</p>
            @endif

            <div class="mt-4 flex space-x-4">
                @if (in_array($order->order_status, ['pending', 'processing']))
                    <button onclick="document.getElementById('cancel-form').classList.toggle('hidden')" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Hủy đơn hàng</button>
                @endif
                @if (in_array($order->order_status, ['cancelled', 'refunded']))
                    <form action="{{ route('user.orders.reorder', $order->id) }}" method="POST">
                        @csrf
                        @method('POST')
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Mua lại</button>
                    </form>
                @endif
            </div>

            @if (in_array($order->order_status, ['pending', 'processing']))
                <div id="cancel-form" class="hidden mt-4">
                    <form action="{{ route('user.orders.cancel', $order->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('POST')
                        <div>
                            <label for="cancel_reason" class="block text-sm font-medium text-gray-700">Lý do hủy:</label>
                            <textarea id="cancel_reason" name="cancel_reason" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required></textarea>
                            @error('cancel_reason')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Xác nhận hủy</button>
                    </form>
                </div>
            @endif
        </div>

        <div class="bg-white shadow-sm rounded-lg p-4 mb-6">
            <h2 class="text-lg font-semibold mb-2">Sản phẩm</h2>
            @foreach ($orderItems as $item)
                <div class="flex items-center mb-4 border-b pb-2">
                    <img src="{{ $item->product_image ?? ($item->product->images->first()->image_path ?? 'https://via.placeholder.com/100') }}"
                         alt="{{ e($item->product_name) }}" class="w-24 h-24 object-contain mr-4">
                    <div>
                        <p class="text-sm font-medium">{{ e($item->product_name) }}</p>
                        <p class="text-xs text-gray-500">Số lượng: {{ $item->quantity }}</p>
                        <p class="text-xs text-gray-500">Giá: {{ number_format($item->unit_price, 0, ',', '.') }}đ</p>
                        @if ($item->variant)
                            <p class="text-xs text-gray-500">Biến thể: {{ e($item->variant->variant_name) }}</p>
                        @endif
                        <p class="text-xs text-gray-500">Shop: {{ e($item->shopOrder->shop->shop_name ?? 'N/A') }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bg-white shadow-sm rounded-lg p-4">
            <h2 class="text-lg font-semibold mb-2">Địa chỉ giao hàng</h2>
            @if ($orderAddress)
                <p><strong>Tên người nhận:</strong> {{ e($orderAddress->receiver_name) }}</p>
                <p><strong>Số điện thoại:</strong> {{ $orderAddress->receiver_phone }}</p>
                <p><strong>Địa chỉ:</strong> {{ e($orderAddress->address) }}, {{ e($orderAddress->ward) }}, {{ e($orderAddress->district) }}, {{ e($orderAddress->province) }}</p>
                @if ($orderAddress->note)
                    <p><strong>Ghi chú:</strong> {{ e($orderAddress->note) }}</p>
                @endif
            @else
                <p class="text-gray-500">Không có thông tin địa chỉ.</p>
            @endif
        </div>
    </div>
@endsection