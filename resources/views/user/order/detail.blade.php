@extends('user.account.layout')

@section('account-content')
    <div class="container mx-auto py-5">
        @include('layouts.notification')

        <!-- Breadcrumb -->
        <div class="flex flex-wrap items-center gap-2 mb-8 px-[10px] sm:px-0 text-sm md:text-base">
            <a href="{{ route('user.order.parent-order') }}" class="text-gray-500 hover:text-blue-600 transition-colors duration-200">Đơn hàng lớn</a>
            <span class="text-gray-400">/</span>
            <a href="{{ route('user.order.parent-detail',['orderID' => $parentOrder->order_code]) }}" class="text-gray-700">Đơn hàng cha</a>
            <span class="text-gray-400">/</span>
            <span class="text-blue-600 font-medium">{{ $order->id ?? 'N/A' }}</span>
        </div>

        <!-- Nút quay lại -->
        <div class="mb-6">
            <a href="{{ route('user.order.parent-detail',['orderID' => $parentOrder->order_code]) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>

        <!-- Header: Chi tiết đơn hàng -->
        <div class="mb-6">
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="mb-4 md:mb-0">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Chi tiết đơn hàng</h2>
                        <div class="space-y-1 text-sm text-gray-600">
                            <p>Mã đơn hàng: <span class="font-semibold text-blue-600">{{ $order->code ?? 'N/A' }}</span></p>
                            <p>Ngày đặt: <span class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</span></p>
                            <p>
                                Trạng thái: 
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($order->status === 'delivered') bg-green-100 text-green-800
                                    @elseif($order->status === 'cancelled' || $order->status === 'refunded') bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ __('status.' . $order->status) }}
                                </span>
                            </p>
                            @if ($order->cancel_reason)
                                <p class="text-red-600"><strong>Lý do hủy:</strong> {{ e($order->cancel_reason) }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Nút hành động -->
                    <div class="flex flex-wrap gap-2">
                        @if (in_array($order->order_status, ['pending', 'processing']))
                            <button class="open-cancel-modal bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm"
                                data-order-id="{{ $order->id }}">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Hủy đơn hàng
                            </button>
                        @endif
                        @if (in_array($order->order_status, ['cancelled', 'refunded']))
                            <a href="{{ route('user.order.reorder', $order->id) }}"
                               class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Mua lại
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal hủy đơn hàng -->
        @if (in_array($order->order_status, ['pending', 'processing']))
            <div id="cancelModal-{{ $order->id }}"
                class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                    <div class="flex items-center justify-between p-6 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800">Hủy đơn hàng</h3>
                        <button class="close-modal text-gray-400 hover:text-gray-600 text-2xl transition-colors duration-200">×</button>
                    </div>
                    <form action="{{ route('user.order.cancel', $order->id) }}" method="POST" class="p-6">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label for="cancel_reason" class="block text-sm font-semibold text-gray-700 mb-2">Lý do hủy</label>
                            <textarea name="cancel_reason" id="cancel_reason" rows="4"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-300 focus:border-red-500 transition-all duration-200 resize-none" 
                                placeholder="Nhập lý do hủy đơn hàng..." required></textarea>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button"
                                class="close-modal px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200 font-medium">
                                Hủy
                            </button>
                            <button type="submit" 
                                class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 font-medium">
                                Xác nhận hủy
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Thông tin khách hàng, thanh toán, giao hàng -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Khách hàng -->
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                <h5 class="font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a4 4 0 004 4h10a4 4 0 004-4V7"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3H8a4 4 0 00-4 4v0a4 4 0 004 4h8a4 4 0 004-4v0a4 4 0 00-4-4z"></path>
                    </svg>
                    Thông tin shop
                </h5>
                <div class="space-y-2 text-sm text-gray-600">
                    <p><span class="font-medium">Tên shop:</span> 
                        {{ $order->shop->shop_name ?? 'Không có thông tin' }}
                                        </p>
                    <p><span class="font-medium">Email:</span> 
                        {{ $order->shop->shop_email ?? 'Không có thông tin' }}
                    </p>
                    <p><span class="font-medium">Số điện thoại:</span> 
                        {{ $order->shop->shop_phone ?? 'Không có thông tin' }}
                    </p>
                </div>
            </div>
            
            <!-- Thông tin thanh toán -->
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                <h5 class="font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Thông tin thanh toán
                </h5>
                <div class="space-y-2 text-sm text-gray-600">
                    <p><span class="font-medium">Phương thức:</span> {{ $order->order->payment_method ?? 'Chưa xác định' }}</p>
                    <p><span class="font-medium">Trạng thái:</span> 
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            @if($order->order->payment_status === 'paid') bg-green-100 text-green-800
                            @elseif($order->order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $order->order->payment_status ?? 'Chưa xác định' }}
                        </span>
                    </p>
                </div>
            </div>
            
            <!-- Thông tin giao hàng -->
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                <h5 class="font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Thông tin giao hàng
                </h5>
                <div class="space-y-2 text-sm text-gray-600">
                    <p><span class="font-medium">Đơn vị vận chuyển:</span> 
                        {{ isset($order->shopOrder) && $order->shopOrder->first() ? $order->shopOrder->first()->shipping_provider : 'Chờ xác nhận' }}
                    </p>
                    <p><span class="font-medium">Người nhận:</span> {{ $orderAddress->receiver_name ?? 'Không có thông tin' }}</p>
                    <p><span class="font-medium">Số điện thoại:</span> {{ $orderAddress->receiver_phone ?? 'Không có thông tin' }}</p>
                    <p><span class="font-medium">Địa chỉ:</span> {{ $orderAddress->address ?? 'Không có thông tin' }}, 
                        {{ $orderAddress->ward ?? '' }}, {{ $orderAddress->district ?? '' }}, {{ $orderAddress->province ?? '' }}
                    </p>
                    @if ($orderAddress->note ?? false)
                        <p><span class="font-medium">Ghi chú:</span> {{ e($orderAddress->note) }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Danh sách sản phẩm và Tổng quan đơn hàng -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Danh sách sản phẩm -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                    <h5 class="font-semibold text-gray-800 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Danh sách sản phẩm
                    </h5>
                    
                    @if (isset($orderItems) && $orderItems->isNotEmpty())
                        @php
                            $itemsGroupedByShop = $orderItems->groupBy('shop_orderID');
                        @endphp

                        @foreach ($itemsGroupedByShop as $shopOrderID => $items)
                            @php
                                $shop = $items->first()->shopOrder->shop;
                                $shopName = $shop->shop_name ?? 'Không có tên shop';
                            @endphp

                            <div class="mb-6 last:mb-0">
                                <div class="flex items-center mb-4 p-3 bg-gray-50 rounded-lg">
                                    @if($shop->shop_logo)
                                        <img src="{{ asset('storage/' . $shop->shop_logo) }}"
                                            alt="Logo {{ $shop->shop_name }}"
                                            class="w-8 h-8 object-cover rounded-full mr-3">
                                    @else
                                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <span class="font-semibold text-gray-800">{{ $shop->shop_name }}</span>
                                </div>

                                <div class="overflow-hidden rounded-lg border border-gray-200">
                                    <table class="w-full">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Sản phẩm</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Đơn giá</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Số lượng</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Thành tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($items as $item)
                                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                    <td class="px-4 py-4">
                                                        <div class="flex items-center">
                                                            @php
                                                                $variantId = $item->variantID ?? null;
                                                                $productImages = $item->product->images ?? collect();
                                                                $variantImage = $productImages->firstWhere('variantID', $variantId);
                                                                $defaultImage = $productImages->firstWhere('is_default', 1) ?? $productImages->first();
                                                                $imagePath = $variantImage->image_path ?? ($defaultImage->image_path ?? 'https://via.placeholder.com/40');
                                                                $finalImage = Str::startsWith($imagePath, ['http', '//']) ? $imagePath : asset('storage/' . $imagePath);
                                                            @endphp

                                                            <img src="{{ $finalImage }}"
                                                                alt="{{ e($item->product_name) }}"
                                                                class="w-12 h-12 rounded-lg object-cover mr-3 border border-gray-200">

                                                            <div>
                                                                <div class="font-medium text-gray-900">{{ e($item->product_name) }}</div>
                                                                @if ($item->variant)
                                                                    <div class="text-sm text-gray-500">Biến thể: {{ e($item->variant->variant_name) }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 text-sm text-gray-900">
                                                        {{ number_format($item->unit_price, 0, ',', '.') }} VND
                                                    </td>
                                                    <td class="px-4 py-4 text-sm text-gray-900">
                                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                                            {{ $item->quantity }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-4 text-sm font-semibold text-gray-900">
                                                        {{ number_format($item->total_price, 0, ',', '.') }} VND
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-gray-500 text-sm">Không có sản phẩm nào trong đơn hàng này.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tổng quan đơn hàng -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                    <h5 class="font-semibold text-gray-800 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Tổng quan đơn hàng
                    </h5>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">Tổng tiền hàng</span>
                            <span class="font-medium text-gray-900">
                                {{ number_format($order->items->sum('total_price'), 0, ',', '.') }} VND
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">Phí vận chuyển</span>
                            <span class="font-medium text-gray-900">
                                {{ number_format($order->shipping_shop_fee ?? 0, 0, ',', '.') }} VND
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">Giảm giá</span>
                            <span class="font-medium text-green-600">
                                -{{ number_format($order->discount_shop_amount ?? 0, 0, ',', '.') }} VND
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center py-3 border-t-2 border-gray-200">
                            <span class="text-lg font-bold text-gray-900">Tổng cộng</span>
                            <span class="text-lg font-bold text-blue-600">
                                {{ number_format(($order->items->sum('total_price') + ($order->shipping_shop_fee ?? 0)) - ($order->discount_shop_amount ?? 0), 0, ',', '.') }} VND
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Section -->
        <div class="mt-8">
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                <h5 class="font-semibold text-gray-800 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Lịch sử đơn hàng
                </h5>
                
                <div class="relative">
                    <!-- Timeline line -->
                    <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                    
                    <div class="space-y-6">
                        <!-- Order Created -->
                        <div class="relative flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h6 class="text-sm font-semibold text-gray-900">Đơn hàng được tạo</h6>
                                    <span class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">Đơn hàng #{{ $order->code ?? 'N/A' }} đã được tạo thành công</p>
                            </div>
                        </div>

                        <!-- Payment Status -->
                        @if($order->order->payment_status)
                        <div class="relative flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 
                                @if($order->order->payment_status === 'paid') bg-green-100
                                @elseif($order->order->payment_status === 'pending') bg-yellow-100
                                @else bg-gray-100 @endif rounded-full flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 
                                    @if($order->order->payment_status === 'paid') text-green-600
                                    @elseif($order->order->payment_status === 'pending') text-yellow-600
                                    @else text-gray-600 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h6 class="text-sm font-semibold text-gray-900">Thanh toán</h6>
                                    @if(isset($order->order->paid_at))
                                    <span class="text-xs text-gray-500">{{ $order->order->paid_at->format('d/m/Y H:i') }}</span>
                                    @else
                                    <span class="text-xs text-gray-500">Chưa thanh toán</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 mt-1">
                                    Trạng thái thanh toán: 
                                    <span class="font-medium 
                                        @if($order->order->payment_status === 'paid') text-green-600
                                        @elseif($order->order->payment_status === 'pending') text-yellow-600
                                        @else text-gray-600 @endif">
                                        {{ $order->order->payment_status === 'paid' ? 'Đã thanh toán' : ($order->order->payment_status === 'pending' ? 'Chờ thanh toán' : 'Chưa xác định') }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        @endif

                        <!-- Order Status Changes -->
                        @php
                        $statusHistory = [
                            'pending' => [
                                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', // đồng hồ
                                'color' => 'yellow',
                                'text' => 'Chờ xác nhận'
                            ],
                            'confirmed' => [
                                'icon' => 'M5 13l4 4L19 7', // check
                                'color' => 'orange',
                                'text' => 'Chờ xác nhận'
                            ],
                            'ready_to_pick' => [
                                'icon' => 'M3 3h2l.4 2M7 13h10l1.5 6H6.5L7 13zm1.5-8h7l1 4H7.5l1-4z', // xe đẩy hàng
                                'color' => 'green',
                                'text' => 'Chờ lấy hàng'
                            ],
                            'picked' => [
                                'icon' => 'M3 4.5A2.5 2.5 0 015.5 2h13A2.5 2.5 0 0121 4.5v11a2.5 2.5 0 01-2.5 2.5h-13A2.5 2.5 0 013 15.5v-11zM5 6h14M5 10h14', // hộp hàng
                                'color' => 'teal',
                                'text' => 'Đơn vị vận chuyển đã lấy hàng'
                            ],
                            'shipping' => [
                                'icon' => 'M3 9l1.5 6h13l1.5-6H3zm16 6a2 2 0 100 4 2 2 0 000-4zm-12 0a2 2 0 100 4 2 2 0 000-4z', // xe tải (delivery truck)
                                'color' => 'purple',
                                'text' => 'Đang giao hàng'
                            ],
                            'delivered' => [
                                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', // success check
                                'color' => 'lime',
                                'text' => 'Đã giao hàng'
                            ],
                            'cancelled' => [
                                'icon' => 'M6 18L18 6M6 6l12 12', // dấu X
                                'color' => 'red',
                                'text' => 'Đã hủy'
                            ],
                            'completed' => [
                                'icon' => 'M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2zm2 7l3 3 6-6', // check trong khung
                                'color' => 'blue',
                                'text' => 'Đã hoàn thành'
                            ],
                            'refunded' => [
                                'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', // tờ hóa đơn
                                'color' => 'pink',
                                'text' => 'Đã hoàn tiền'
                            ]
                        ];

                        @endphp

                        @foreach ($order->history as $history)
                        <div class="relative flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 
                                @if(isset($statusHistory[$history->status])) bg-{{ $statusHistory[$history->status]['color'] }}-100 @else bg-gray-100 @endif rounded-full flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 
                                    @if(isset($statusHistory[$history->status])) text-{{ $statusHistory[$history->status]['color'] }}-600 @else text-gray-600 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusHistory[$history->status]['icon'] ?? 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' }}"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h6 class="text-sm font-semibold text-gray-900">Trạng thái đơn hàng</h6>
                                    <span class="text-xs text-gray-500">{{ $history->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $statusHistory[$history->status]['text']  }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                        <!-- Shipping Information -->

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal cancel functionality
            document.querySelectorAll('.open-cancel-modal').forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-order-id');
                    const modal = document.getElementById(`cancelModal-${orderId}`);
                    if (modal) {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    }
                });
            });

            document.querySelectorAll('[id^="cancelModal-"]').forEach(modal => {
                const closeButtons = modal.querySelectorAll('button[type="button"]');
                closeButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    });
                });
            });
        });
    </script>
@endpush
