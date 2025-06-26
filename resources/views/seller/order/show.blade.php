@extends('layouts.seller_home')

@section('title', 'Chi tiết đơn hàng #{{ $order->order_code }}')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container mx-auto py-5">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Chi tiết đơn hàng #{{ $order->order_code }} - {{ $shop_order->status }}</h1>
            @if($shop_order->status === 'pending')
            <form action="{{ route('seller.order.update-status', ['id' => $shop_order->id, 'shop_id' => $shop->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="confirmed">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-md" type="submit">Nhận đơn</button>
            </form>
            @endif
            @if($shop_order->status === 'confirmed' || $shop_order->status === 'ready_to_pick' || $shop_order->status === 'picked')
            <form action="{{ route('seller.order.cancel') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="{{ $shop_order->status }}">
                <input type="hidden" name="id" value="{{ $shop_order->id }}">
                <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                <button class="bg-red-500 text-white px-4 py-2 rounded-md" type="submit">Huỷ đơn hàng</button>
            </form>
            @endif
            <form action="{{ route('seller.order.tracking') }}" method="POST">
                @csrf
                <input type="hidden" name="tracking_code" value="{{ $shop_order->code }}">
                <input type="hidden" name="method_request" value="status_update">
                <button class="bg-red-500 text-white px-4 py-2 rounded-md" type="submit">Cập nhật đơn hàng</button>
            </form>
        </div>

        <!-- Thông tin đơn hàng -->
        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-lg font-semibold mb-4">Thông tin đơn hàng</h2>
                    <p><strong>Mã đơn hàng:</strong> {{ $shop_order->code  ?? ''}}</p>
                    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y') ?? ''  }}</p>
                    <p><strong>Mã vận đơn:</strong> {{ $shop_order->tracking_code ?? '' }}</p>
                    <p><strong>Lời nhắn của khách hàng:</strong> {{ $order->shop_order->first()->note ?? '' }}</p>

                </div>
                <div>
                    <h2 class="text-lg font-semibold mb-4">Địa chỉ giao hàng</h2>
                    <p><strong>Tên người nhận:</strong> {{ $order->address->receiver_name }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $order->address->receiver_phone }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->address->address }}, {{ $order->address->ward }}, {{ $order->address->district }}, {{ $order->address->province }}</p>
                </div>

                <div>
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
                                        <p class="text-gray-700">{{ $item->product_name}}</p>
                                        <p class="text-sm text-gray-500">{{ $item->variant_name }}</p>
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
         @if($shop_order->status === 'confirmed')
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
                        @foreach ($shop_address as $address)
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
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-xl font-bold text-gray-800">Lịch sử trạng thái</h2>
    </div>
    
    @if ($status->isEmpty())
        <div class="p-6 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <p class="mt-3 text-gray-500">Chưa có lịch sử trạng thái</p>
        </div>
    @else
        @php
            $groupedStatus = collect($status)->groupBy(function($item) {
                return $item->created_at->format('d/m/Y');
            });

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
        
        <div class="divide-y divide-gray-100">
            @foreach ($groupedStatus as $date => $histories)
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 h-5 w-5 text-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="ml-2 text-lg font-semibold text-gray-700">Ngày {{ $date }}</h3>
                    </div>
                    
                    <div class="ml-7 pl-5 border-l-2 border-blue-100 space-y-4">
                        @foreach ($histories as $history)
                            <div class="relative pb-4">
                                <div class="absolute -left-5 top-3 h-3 w-3 rounded-full {{ $statusClasses[$history->status] ?? 'bg-gray-200 text-gray-800' }} border-2 border-white"></div>
                                <div class="bg-gray-50 rounded-lg p-4 shadow-sm">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$history->status] ?? 'bg-gray-200 text-gray-800' }}">
                                                {{ ucfirst($history->status) }}
                                            </span>
                                            <span class="ml-2 text-sm text-gray-500">
                                                {{ $history->created_at->format('H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-2 text-sm text-gray-700 space-y-1">
                                            <p><span class="font-medium">Mô tả: </span> {{ $history->description }}</p>
                                        @if ($history->note)
                                            <div class="mt-2 p-2 bg-yellow-50 border-l-4 border-yellow-400 rounded-r">
                                                <div class="flex">
                                                    <div class="flex-shrink-0">
                                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <div class="ml-3">
                                                        <p class="text-sm text-yellow-700">
                                                            <span class="font-medium">Ghi chú:</span> {{ $history->note }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
@endsection