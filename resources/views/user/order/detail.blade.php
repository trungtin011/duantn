@extends('user.account.layout')

@section('account-content')
    <div class="container mx-auto py-5 px-3 sm:px-0">
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
                                                                class="w-12 h-12 rounded-lg object-cover mr-3 border border-gray-200 product-image"
                                                                data-product-id="{{ $item->productID }}">

                                                            <div>
                                                                <div class="font-medium text-gray-900">{{ e($item->product_name) }}</div>
                                                                @if ($item->variant)
                                                                    <div class="text-sm text-gray-500">Biến thể: {{ e($item->variant->variant_name) }}</div>
                                                                @elseif($item->combo && $item->combo->products)
                                                                    @php
                                                                        $comboProduct = $item->combo->products->firstWhere('productID', $item->productID);
                                                                    @endphp
                                                                    @if($comboProduct && $comboProduct->variant && $comboProduct->variant->variant_name)
                                                                        <div class="text-sm text-gray-500">Biến thể: {{ e($comboProduct->variant->variant_name) }}</div>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 text-sm text-gray-900">
                                                        @php
                                                            $originalPrice = 0;
                                                            $currentPrice = $item->unit_price;
                                                            
                                                            if ($item->variant && $item->variant->price) {
                                                                $originalPrice = $item->variant->price;
                                                            } elseif ($item->combo && $item->combo->products) {
                                                                $comboProduct = $item->combo->products->firstWhere('productID', $item->productID);
                                                                if ($comboProduct && $comboProduct->variant && $comboProduct->variant->price) {
                                                                    $originalPrice = $comboProduct->variant->price;
                                                                } else {
                                                                    $originalPrice = $item->product->price ?? 0;
                                                                }
                                                            } else {
                                                                $originalPrice = $item->product->price ?? 0;
                                                            }
                                                        @endphp
                                                        @if($originalPrice > $currentPrice)
                                                            <div class="text-gray-400 line-through text-xs">
                                                                {{ number_format($originalPrice, 0, ',', '.') }} VND
                                                            </div>
                                                        @endif
                                                        <div class="font-medium text-red-600">
                                                            {{ number_format($currentPrice, 0, ',', '.') }} VND
                                                        </div>
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
                                                
                                                {{-- Phần đánh giá sản phẩm --}}
                                                @if ($order->status === 'completed' && !in_array($item->productID, $reviewedProductIds ?? []))
                                                    <tr>
                                                        <td colspan="4" class="px-4 py-3">
                                                            <div class="text-right">
                                                                <button
                                                                    class="open-review-modal bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 text-xs sm:text-sm hover:from-red-600 hover:to-red-700 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg font-medium"
                                                                    data-product-id="{{ $item->productID }}" 
                                                                    data-product-name="{{ $item->product_name }}"
                                                                    data-order-id="{{ $order->id }}" 
                                                                    data-shop-id="{{ $item->shopOrder->shopID ?? $shop->id }}"
                                                                    data-product-variant-name="{{ $item->variant->variant_name ?? ($item->combo && $item->combo->products ? ($item->combo->products->firstWhere('productID', $item->productID)->variant->variant_name ?? '') : '') }}">
                                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                                                        </path>
                                                                    </svg>
                                                                    Đánh giá sản phẩm
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
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

    <!-- Modal đánh giá -->
    <div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-2xl rounded-lg shadow-xl relative animate-fade-in-down max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 id="modalProductName" class="text-xl font-bold text-gray-800">Đánh giá sản phẩm</h2>
                <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600 text-2xl transition-colors duration-200">×</button>
            </div>

            <div class="p-6">
                <!-- Instruction Section -->
                <div class="relative border border-red-300 bg-red-50 rounded-lg overflow-visible mb-6">
                    <div class="flex items-center justify-between p-4 cursor-pointer" id="instructionToggle">
                        <div class="flex items-center">
                            <i class="bi bi-coin text-red-500 text-xl mr-2"></i>
                            <span class="flex items-center gap-1">Xem Hướng dẫn đánh giá chuẩn để nhận đến <span class="font-semibold text-red-700">200 xu!</span></span>
                        </div>
                        <i class="bi bi-chevron-down text-red-700 transition-transform duration-300" id="instructionArrow"></i>
                    </div>
                    <div class="absolute w-full mt-2 left-0 z-10 px-4 pb-4 hidden bg-white border border-red-300 rounded-lg shadow-lg" id="instructionContent">
                        <h4 class="font-bold text-gray-800 mb-2 mt-2">Điều kiện nhận Xu</h4>
                        <p class="text-sm text-gray-700 mb-4">Viết nhận xét với ít nhất 50 ký tự, kèm hình ảnh và/hoặc video để nhận Xu bạn nhé!</p>

                        <h4 class="font-bold text-gray-800 mb-2">Xu thưởng cho đánh giá hợp lệ</h4>
                        <ul class="list-disc list-inside text-sm text-gray-700 mb-4">
                            <li>Xu thưởng từ ZynoxMall: nhập ít nhất 50 ký tự kèm 1 hình ảnh hoặc 1 video. <span class="font-bold text-red-500">100 xu</span></li>
                            <li>Xu thưởng từ ZynoxMall: nhập ít nhất 50 ký tự kèm 1 hình ảnh và 1 video. <span class="font-bold text-red-500">200 xu</span></li>
                        </ul>

                        <ul class="text-xs text-gray-600 space-y-1">
                            <li>* Trong 1 đơn hàng có nhiều hơn 1 sản phẩm, Bạn sẽ nhận được Xu trên từng sản phẩm nếu đánh giá thỏa điều kiện</li>
                            <li>* Sản phẩm đánh giá có nội dung không liên quan hoặc không phù hợp, ZynoxMall sẽ thu hồi Xu</li>
                            <li>* Bạn sẽ không nhận được Xu nếu chỉnh sửa nội dung đánh giá</li>
                            <li>* Bạn sẽ nhận được Xu sau khi đánh giá được gửi thành công</li>
                            <li>* Điều kiện để nhận Xu khi số tiền được thanh toán của mặt hàng cao hơn ₫2.000 VNĐ.</li>
                        </ul>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="flex items-center mb-6 border border-gray-200 p-4 rounded-lg bg-gray-50">
                    <div class="w-24 h-24 mr-4 border rounded-md p-1 bg-white flex items-center justify-center">
                        <img id="modalProductImage" src="https://via.placeholder.com/96x96?text=Loading..." alt="Product Image" class="w-full h-full object-contain rounded">
                    </div>
                    <div>
                        <h3 class="text-md font-semibold text-gray-800" id="modalProductDisplayName"></h3>
                        <p class="text-sm text-gray-600" id="modalProductVariantNameLabel"></p>
                        <p class="text-sm text-gray-600" id="modalProductVariantName"></p>
                    </div>
                </div>

                <form id="reviewForm" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="orderID" id="modalOrderId">
                    <input type="hidden" name="shopID" id="modalShopId">
                    <input type="hidden" name="productID" id="modalProductId">

                    <!-- Product Quality Rating -->
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-3">Chất lượng sản phẩm:</label>
                        <div class="flex justify-start items-center mb-4" id="modalStarRating">
                            <div class="flex items-center star-container">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star-fill text-xl text-gray-300 cursor-pointer mx-1 star-item" data-value="{{ $i }}"></i>
                                @endfor
                            </div>
                            <span id="starRatingText" class="ml-3 text-lg font-semibold text-gray-600 transition-all duration-300"></span>
                            <input type="hidden" name="rating" id="modalRating" value="0">
                        </div>
                    </div>

                    <!-- Comment Section -->
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-3">Bình luận:</label>
                        <textarea name="comment" id="reviewComment" 
                                  class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all duration-200 resize-none" 
                                  rows="5" 
                                  placeholder="Hãy chia sẻ những điều bạn thích về sản phẩm này với những người mua khác nhé."></textarea>
                        <p class="text-xs text-gray-500 mt-2"><span id="commentCharCount">0</span>/50 ký tự tối thiểu</p>
                    </div>

                    <!-- File Upload Buttons -->
                    <div class="flex space-x-4 mb-6">
                        <label for="imagesUpload" class="flex items-center px-4 py-3 border-2 border-red-500 text-red-500 rounded-lg cursor-pointer hover:bg-red-50 transition-colors duration-200 font-medium">
                            <div id="imageIcon" class="mr-2">
                                <i class="bi bi-image"></i>
                            </div>
                            <span>Thêm Hình ảnh (<span id="imageCount">0</span>)</span>
                        </label>
                        <input type="file" name="images[]" id="imagesUpload" accept="image/*" multiple class="hidden">

                        <label for="videoUpload" class="flex items-center px-4 py-3 border-2 border-red-500 text-red-500 rounded-lg cursor-pointer hover:bg-red-50 transition-colors duration-200 font-medium">
                            <div id="videoIcon" class="mr-2">
                                <i class="bi bi-camera-video"></i>
                            </div>
                            <span>Thêm Video (<span id="videoCount">0</span>)</span>
                        </label>
                        <input type="file" name="video" id="videoUpload" accept="video/*,.webm" class="hidden">
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" id="cancelReviewBtn" 
                                class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200 font-semibold">
                            TRỞ LẠI
                        </button>
                        <button type="submit" 
                                class="px-6 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors duration-200 font-semibold">
                            HOÀN THÀNH
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <!-- Loading Spinner CSS -->
    <style>
        .spinner-border {
            display: inline-block;
            width: 2rem;
            height: 2rem;
            vertical-align: text-bottom;
            border: 0.25em solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spinner-border .75s linear infinite;
        }
        
        @keyframes spinner-border {
            to { transform: rotate(360deg); }
        }
        
        .visually-hidden {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            padding: 0 !important;
            margin: -1px !important;
            overflow: hidden !important;
            clip: rect(0, 0, 0, 0) !important;
            white-space: nowrap !important;
            border: 0 !important;
        }
        
        /* Star rating hover effects */
        .star-container {
            position: relative;
        }
        
        .star-item {
            position: relative;
            z-index: 1;
            transition: all 0.2s ease-in-out;
        }
        
        .star-item:hover {
            z-index: 2;
        }
        
        /* Màu sắc sao rating */
        .text-orange-400 {
            color: #fb923c !important;
        }
        
        .text-orange-500 {
            color: #f97316 !important;
        }
        
        .text-orange-600 {
            color: #ea580c !important;
        }
        

    </style>
@endsection


@push('scripts')
    <script>
        // Function hiển thị thông báo
        function showNotification(type, message) {
            // Tạo element thông báo
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
            
            if (type === 'success') {
                notification.classList.add('bg-green-500', 'text-white');
            } else if (type === 'error') {
                notification.classList.add('bg-red-500', 'text-white');
            } else {
                notification.classList.add('bg-blue-500', 'text-white');
            }
            
            notification.innerHTML = `
                <div class="flex items-center">
                    <span class="mr-2">${type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ'}</span>
                    <span>${message}</span>
                    <button class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">×</button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Hiển thị thông báo
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Tự động ẩn sau 5 giây
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        }

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
                const closeButtons = modal.querySelectorAll('.close-modal');
                closeButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    });
                });
            });

            // Logic cho đánh giá (sử dụng event delegation)
            const reviewModal = document.getElementById('reviewModal');
            const reviewForm = document.getElementById('reviewForm');
            const orderIdInput = document.getElementById('modalOrderId');
            const shopIdInput = document.getElementById('modalShopId');
            const productIdInput = document.getElementById('modalProductId');
            const productNameEl = document.getElementById('modalProductName');
            const ratingInput = document.getElementById('modalRating');
            const stars = document.querySelectorAll('#modalStarRating i');
            const starRatingText = document.getElementById('starRatingText');
            const imagesUploadInput = document.getElementById('imagesUpload');
            const videoUploadInput = document.getElementById('videoUpload');
            const cancelReviewBtn = document.getElementById('cancelReviewBtn');
            const reviewComment = document.getElementById('reviewComment');
            const commentCharCount = document.getElementById('commentCharCount');
            const imageCountSpan = document.getElementById('imageCount');
            const videoCountSpan = document.getElementById('videoCount');

            // Instruction toggle
            const instructionToggle = document.getElementById('instructionToggle');
            const instructionContent = document.getElementById('instructionContent');
            const instructionArrow = document.getElementById('instructionArrow');

            if (instructionToggle) {
                instructionToggle.addEventListener('click', function() {
                    instructionContent.classList.toggle('hidden');
                    instructionArrow.classList.toggle('rotate-180');
                });
            }

            // Event listener cho nút đánh giá sản phẩm
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('open-review-modal')) {
                    e.preventDefault();
                    orderIdInput.value = e.target.dataset.orderId;
                    shopIdInput.value = e.target.dataset.shopId;
                    productIdInput.value = e.target.dataset.productId;
                    productNameEl.innerText = `Đánh Giá Sản Phẩm`;

                    // Set product info
                    const productId = e.target.dataset.productId;
                    console.log('Tìm hình ảnh cho sản phẩm ID:', productId);
                    
                    // Tìm hình ảnh sản phẩm
                    const productImage = document.querySelector(`img.product-image[data-product-id="${productId}"]`);
                    const modalProductImage = document.getElementById('modalProductImage');
                    
                    console.log('Tìm thấy hình ảnh:', productImage);
                    
                    if (productImage && productImage.src) {
                        modalProductImage.src = productImage.src;
                        console.log('Đã set hình ảnh sản phẩm:', productImage.src);
                        
                        // Xử lý lỗi tải hình ảnh
                        modalProductImage.onerror = function() {
                            console.log('Lỗi tải hình ảnh, sử dụng hình ảnh mặc định');
                            this.src = 'https://via.placeholder.com/96x96?text=No+Image';
                        };
                        
                        // Xử lý khi tải hình ảnh thành công
                        modalProductImage.onload = function() {
                            console.log('Tải hình ảnh thành công');
                        };
                    } else {
                        console.log('Không tìm thấy hình ảnh sản phẩm cho ID:', productId);
                        console.log('Tất cả hình ảnh sản phẩm:', document.querySelectorAll('img.product-image'));
                        modalProductImage.src = 'https://via.placeholder.com/96x96?text=No+Image';
                    }
                    document.getElementById('modalProductDisplayName').innerText = e.target.dataset.productName;
                    
                    const modalProductVariantName = e.target.dataset.productVariantName;
                    if (modalProductVariantName) {
                        document.getElementById('modalProductVariantNameLabel').innerText = 'Phân loại hàng:';
                        document.getElementById('modalProductVariantName').innerText = modalProductVariantName;
                    } else {
                        document.getElementById('modalProductVariantNameLabel').innerText = '';
                        document.getElementById('modalProductVariantName').innerText = '';
                    }

                    // Reset rating
                    currentRating = 0;
                    ratingInput.value = 0;
                    updateStars(0, false);
                    updateRatingText(0);

                    // Reset form fields
                    imagesUploadInput.value = '';
                    videoUploadInput.value = '';
                    reviewForm.reset();
                    commentCharCount.innerText = '0';
                    imageCountSpan.innerText = '0';
                    videoCountSpan.innerText = '0';
                    
                    // Reset icons
                    document.getElementById('imageIcon').innerHTML = '<i class="bi bi-image"></i>';
                    document.getElementById('videoIcon').innerHTML = '<i class="bi bi-camera-video"></i>';

                    reviewModal.classList.remove('hidden');
                    reviewModal.classList.add('flex');
                    console.log('Mở modal đánh giá cho đơn hàng ID:', e.target.dataset.orderId,
                        'Sản phẩm ID:', e.target.dataset.productId, 'Shop ID:', e.target.dataset.shopId);
                }
            });

            document.getElementById('closeModalBtn').addEventListener('click', function() {
                reviewModal.classList.add('hidden');
                reviewModal.classList.remove('flex');
                console.log('Đóng modal đánh giá.');
            });

            // Logic chọn sao đánh giá với hiệu ứng hover
            let currentRating = 0;
            let isHovering = false;
            
            // Hàm cập nhật hiển thị sao
            function updateStars(rating, isHover = false) {
                const colorClass = isHover ? 'text-orange-400' : 'text-orange-500';
                console.log('Cập nhật sao - Rating:', rating, 'isHover:', isHover, 'Color:', colorClass);
                
                stars.forEach(s => {
                    const sValue = parseInt(s.dataset.value);
                    if (sValue <= rating) {
                        s.classList.remove('text-gray-300', 'text-yellow-400', 'text-yellow-500', 'text-orange-400', 'text-orange-500');
                        s.classList.add(colorClass);
                        console.log('Sao', sValue, '->', colorClass);
                    } else {
                        s.classList.remove('text-yellow-400', 'text-yellow-500', 'text-orange-400', 'text-orange-500');
                        s.classList.add('text-gray-300');
                        console.log('Sao', sValue, '-> gray');
                    }
                });
            }
            
            // Hàm cập nhật text đánh giá
            function updateRatingText(rating) {
                const ratingTexts = {
                    0: 'Chọn sao',
                    1: 'Rất không hài lòng',
                    2: 'Không hài lòng', 
                    3: 'Bình thường',
                    4: 'Hài lòng',
                    5: 'Rất hài lòng'
                };
                
                starRatingText.innerText = ratingTexts[rating] || `${rating} sao`;
                
                // Thêm hiệu ứng màu sắc cho text
                if (rating > 0) {
                    starRatingText.classList.remove('text-gray-600');
                    starRatingText.classList.add('text-orange-600');
                } else {
                    starRatingText.classList.remove('text-orange-600');
                    starRatingText.classList.add('text-gray-600');
                }
            }
            
            stars.forEach(star => {
                const starValue = parseInt(star.dataset.value);
                
                // Hiệu ứng hover
                star.addEventListener('mouseenter', function(e) {
                    e.stopPropagation();
                    console.log('Hover vào sao:', starValue);
                    isHovering = true;
                    updateStars(starValue, true);
                    updateRatingText(starValue);
                });
                
                // Hiệu ứng khi rời chuột
                star.addEventListener('mouseleave', function(e) {
                    e.stopPropagation();
                    isHovering = false;
                    updateStars(currentRating, false);
                    updateRatingText(currentRating);
                });
                
                // Hiệu ứng click
                star.addEventListener('click', function(e) {
                    e.stopPropagation();
                    currentRating = starValue;
                    ratingInput.value = currentRating;
                    
                    updateStars(currentRating, false);
                    updateRatingText(currentRating);
                    
                    // Thêm hiệu ứng animation cho sao được click
                    this.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 200);
                });
            });

            // Logic đếm ký tự bình luận
            if (reviewComment) {
                reviewComment.addEventListener('input', function() {
                    const count = this.value.length;
                    commentCharCount.innerText = count;
                    if (count < 50) {
                        commentCharCount.classList.remove('text-green-600');
                        commentCharCount.classList.add('text-red-500');
                    } else {
                        commentCharCount.classList.remove('text-red-500');
                        commentCharCount.classList.add('text-green-600');
                    }
                });
            }

            // Logic cho file upload hình ảnh
            imagesUploadInput.addEventListener('change', function() {
                const files = this.files;
                imageCountSpan.innerText = files.length;
                
                const imageIcon = document.getElementById('imageIcon');
                
                if (files.length > 0) {
                    console.log('Đã chọn ' + files.length + ' file hình ảnh.', files);
                    
                    // Show first image as thumbnail
                    const firstFile = files[0];
                    if (firstFile.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imageIcon.innerHTML = `
                                <img src="${e.target.result}" alt="Preview" 
                                     class="w-6 h-6 object-cover rounded border border-gray-300">
                            `;
                        };
                        reader.readAsDataURL(firstFile);
                    }
                } else {
                    console.log('Không có file hình ảnh nào được chọn.');
                    // Reset to icon
                    imageIcon.innerHTML = '<i class="bi bi-image"></i>';
                }
            });

            // Logic cho file upload video
            videoUploadInput.addEventListener('change', function() {
                const file = this.files[0];
                videoCountSpan.innerText = file ? '1' : '0';
                
                const videoIcon = document.getElementById('videoIcon');
                
                if (file && file.type.startsWith('video/')) {
                    console.log('Đã chọn file video:', file);
                    
                    // Show video thumbnail
                    videoIcon.innerHTML = `
                        <div class="w-6 h-6 bg-gray-100 rounded border border-gray-300 flex items-center justify-center relative">
                            <i class="bi bi-play-circle-fill text-red-500 text-sm"></i>
                            <div class="absolute -bottom-1 -right-1 bg-red-500 text-white text-xs rounded-full w-3 h-3 flex items-center justify-center">
                                <i class="bi bi-camera-video text-xs"></i>
                            </div>
                        </div>
                    `;
                } else {
                    console.log('Không có file video nào được chọn.');
                    // Reset to icon
                    videoIcon.innerHTML = '<i class="bi bi-camera-video"></i>';
                }
            });

            // Logic cho nút TRỞ LẠI
            cancelReviewBtn.addEventListener('click', function() {
                reviewModal.classList.add('hidden');
                reviewModal.classList.remove('flex');
                console.log('Đóng modal đánh giá và trở lại.');
            });



            // Logic submit form đánh giá
            reviewForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const rating = formData.get('rating');
                const comment = formData.get('comment');
                
                // Validation
                if (!rating || rating == 0) {
                    showNotification('error', 'Vui lòng chọn số sao đánh giá');
                    return;
                }
                
                // Hiển thị loading
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Đang xử lý...';
                submitBtn.disabled = true;
                
                fetch('{{ route("reviews.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('success', 'Đánh giá đã được gửi thành công!');
                        reviewModal.classList.add('hidden');
                        reviewModal.classList.remove('flex');
                        
                        // Reload trang để cập nhật trạng thái
                        location.reload();
                    } else {
                        showNotification('error', data.message || 'Có lỗi xảy ra');
                    }
                })
                .catch(error => {
                    console.error('Lỗi khi gửi đánh giá:', error);
                    showNotification('error', 'Có lỗi xảy ra khi gửi đánh giá');
                })
                .finally(() => {
                    // Khôi phục nút submit
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                });
            });
        });
    </script>
@endpush
