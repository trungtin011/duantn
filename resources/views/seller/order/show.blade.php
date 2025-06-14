@extends('layouts.seller_home')

@section('title', 'Chi tiết đơn hàng #{{ $order->order_code }}')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container mx-auto py-5">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Chi tiết đơn hàng #{{ $order->order_code }}</h1>
            @if($order->order_status == 'pending')
            <form action="{{ route('seller.order.update-status', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="processing">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-md" type="submit">Nhận đơn</button>
            </form>
            @endif
        </div>
        <!-- Thông tin đơn hàng -->
        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-lg font-semibold mb-4">Thông tin khách hàng</h2>
                    <p><strong>Tên:</strong> {{ $order->user->name ?? 'Khách vãng lai' }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                </div>
                <div>
                    <h2 class="text-lg font-semibold mb-4">Địa chỉ giao hàng</h2>
                    <p><strong>Tên người nhận:</strong> {{ $order->address->receiver_name }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $order->address->receiver_phone }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->address->address }}, {{ $order->address->ward }}, {{ $order->address->district }}, {{ $order->address->province }}</p>
                </div>
                <div>
                    <p><strong>Lời nhắn của khách hàng:</strong> {{ $order->shop_order->first()->note ?? 'Không có lời nhắn' }}</p>
                </div>
            </div>
        </div>

        <!-- Sản phẩm trong đơn hàng -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Sản phẩm</th>
                        <th class="py-3 text-sm font-semibold text-gray-700">Số lượng</th>
                        <th class="py-3 text-sm font-semibold text-gray-700">Giá</th>
                        <th class="py-3 text-sm font-semibold text-gray-700">Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr class="border-b border-gray-200">
                            <td class="py-4 px-4">
                                <div class="flex items-center">
                                    <img src="{{ $item->product->image_url ?? 'https://via.placeholder.com/150' }}"
                                         alt="{{ $item->product->title }}"
                                         class="w-[100px] h-[100px] object-contain mr-4">
                                    <div>
                                        <p class="text-gray-700">{{ $item->product->title }}</p>
                                        @if ($item->variant)
                                            <p class="text-sm text-gray-500">{{ $item->variant->variant_name }} ({{ $item->variant->color ?? '' }} {{ $item->variant->size ? '/' . $item->variant->size : '' }})</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 text-center">{{ $item->quantity }}</td>
                            <td class="py-4 text-center">{{ number_format($item->unit_price, 0, ',', '.') }}đ</td>
                            <td class="py-4 text-center">{{ number_format($item->total_price, 0, ',', '.') }}đ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Cập nhật trạng thái -->
         @if($order->order_status == 'processing')
        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Gửi đơn hàng vận chuyển</h2>
            <form id="update-status-form" action="{{ route('seller.order.shipping', $order->id) }}" method="POST">
                @csrf

                <div class="mb-4 flex items-center gap-4">
                    <div class="flex-1">
                        <label for="shipping_provider" class="block text-sm font-medium text-gray-700">Đơn vị vận chuyển</label>
                        <select name="shipping_provider" id="shipping_provider" class="mt-1 block w-full h-10 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="GHN">Giao Hàng Nhanh (GHN)</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="required_note" class="block text-sm font-medium text-gray-700">Hình thức kiểm hàng</label>
                        <select name="required_note" id="required_note" class="mt-1 block w-full h-10 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="CHOTHUHANG">Cho xem hàng thử</option>
                        <option value="CHOXEMHANGKHONGTHU">Cho xem hàng không thử</option>
                            <option value="KHONGCHOXEMHANG">Không cho xem hàng</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="payment_type" class="block text-sm font-medium text-gray-700">Chịu phí vận chuyển</label>
                        <select name="payment_type" id="payment_type" class="mt-1 block w-full h-10 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="2">Khách hàng</option>
                        <option value="1">Shop</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="shop_address" class="block text-sm font-medium text-gray-700">Địa chỉ shop</label>
                    <select name="shop_address" id="shop_address" class="mt-1 block w-full h-10 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @foreach ($shop->addresses as $address)
                            <option value="{{ $address->id }}">{{ $address->shop_address }}, {{ $address->shop_ward }}, {{ $address->shop_district }}, {{ $address->shop_province }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="note" class="block text-sm font-medium text-gray-700">Ghi chú</label>
                    <textarea name="note" id="note" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="4"></textarea>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Gửi đơn</button>
            </form>
        </div>
        @endif

        <!-- Lịch sử trạng thái -->
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Lịch sử trạng thái</h2>
            @if ($order->statusHistory->isEmpty())
                <p class="text-gray-600">Chưa có lịch sử trạng thái.</p>
            @else
                <ul class="space-y-4">
                    @foreach ($order->statusHistory as $history)
                        <li class="border-b border-gray-200 pb-4">
                            <p><strong>Trạng thái:</strong> {{ ucfirst($history->status) }}</p>
                            <p><strong>Ngày:</strong> {{ $history->created_at->format('d/m/Y H:i') }}</p>
                            @if ($history->description)
                                <p><strong>Mô tả:</strong> {{ $history->description }}</p>
                            @endif
                            @if ($history->shipping_provider)
                                <p><strong>Đơn vị vận chuyển:</strong> {{ $history->shipping_provider }}</p>
                            @endif
                            @if ($history->note)
                                <p><strong>Ghi chú:</strong> {{ $history->note }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
@endsection