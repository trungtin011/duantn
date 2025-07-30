@extends('user.account.layout')

@section('account-content')
    <div class="container mx-auto py-5">
        @include('layouts.notification')

        <!-- Breadcrumb -->
        <div class="flex flex-wrap items-center gap-2 mb-8 px-[10px] sm:px-0 text-sm md:text-base">
            <a href="{{ route('user.order.parent-order') }}" class="text-gray-500 hover:text-blue-600 transition-colors duration-200">Đơn hàng lớn</a>
            <span class="text-gray-400">/</span>
            <span class="text-blue-600 font-medium">{{ $parentOrder->order_code ?? 'N/A' }}</span>
        </div>

        <div class="mb-6">
            <a href="{{ route('user.order.parent-order') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>

        <div class="mb-6">
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="mb-4 md:mb-0">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Chi tiết đơn hàng lớn</h2>
                        <div class="space-y-1 text-sm text-gray-600">
                            <p>Mã đơn hàng: <span class="font-semibold text-blue-600">{{ $parentOrder->order_code ?? 'N/A' }}</span></p>
                            <p>Ngày đặt: <span class="font-medium">{{ $parentOrder->created_at->format('d/m/Y H:i') }}</span></p>
                            <p>
                                Trạng thái: 
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($parentOrder->order_status === 'delivered') bg-green-100 text-green-800
                                    @elseif($parentOrder->order_status === 'cancelled' || $parentOrder->order_status === 'refunded') bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ $statuses[$parentOrder->order_status] ?? $parentOrder->order_status }}
                                </span>
                            </p>
                            @if ($parentOrder->cancel_reason)
                                <p class="text-red-600"><strong>Lý do hủy:</strong> {{ e($parentOrder->cancel_reason) }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Nút hành động -->
                    <div class="flex flex-wrap gap-2">
                        @if($parentOrder->payment_status === 'pending')
                            <button onclick="showGlobalPopup('{{$parentOrder->order_code}}');" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 text-xs sm:text-sm hover:from-green-600 hover:to-green-700 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg font-medium"
                                data-order-id="{{ $parentOrder->id }}">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"></path>
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"></circle>
                                </svg>
                                Thanh Toán
                            </button>
                        @endif
                        @if (in_array($parentOrder->order_status, ['pending', 'processing']))
                            <button class="open-cancel-modal bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm"
                                data-order-id="{{ $parentOrder->id }}">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Hủy đơn hàng
                            </button>
                        @endif
                        @if (in_array($parentOrder->order_status, ['cancelled', 'refunded']))
                            <a href="{{ route('user.order.reorder', $parentOrder->id) }}"
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
        @if (in_array($parentOrder->order_status, ['pending', 'processing']))
            <div id="cancelModal-{{ $parentOrder->id }}"
                class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                    <div class="flex items-center justify-between p-6 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800">Hủy đơn hàng</h3>
                        <button class="close-modal text-gray-400 hover:text-gray-600 text-2xl transition-colors duration-200">×</button>
                    </div>
                    <form action="{{ route('user.order.cancel', $parentOrder->id) }}" method="POST" class="p-6">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Thông tin khách hàng
                </h5>
                <div class="space-y-2 text-sm text-gray-600">
                    <p><span class="font-medium">Tên:</span> {{ $parentOrder->user->fullname ?? 'Không có thông tin' }}</p>
                    <p><span class="font-medium">Email:</span> {{ $parentOrder->user->email ?? 'Không có thông tin' }}</p>
                    <p><span class="font-medium">Số điện thoại:</span> {{ $parentOrder->user->phone ?? 'Không có thông tin' }}</p>
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
                    <p><span class="font-medium">Phương thức:</span> {{ $parentOrder->payment_method ?? 'Chưa xác định' }}</p>
                    <p><span class="font-medium">Trạng thái:</span> 
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            @if($parentOrder->payment_status === 'paid') bg-green-100 text-green-800
                            @elseif($parentOrder->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $parentOrder->payment_status ?? 'Chưa xác định' }}
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

        <!-- Danh sách shop orders -->
        <div class="mb-8">
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                <h5 class="font-semibold text-gray-800 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Danh sách đơn hàng shop
                </h5>
                
                @if($parentOrder->shopOrders && $parentOrder->shopOrders->count() > 0)
                    <div class="space-y-6">
                        @foreach($parentOrder->shopOrders as $shopOrder)
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <!-- Shop Header -->
                                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            @php
                                                $shopLogo = $shopOrder->shop->shop_logo ?? null;
                                                $shopBg = $shopOrder->shop->shop_banner ?? null;
                                            @endphp
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden border border-gray-200"
                                                @if($shopBg) style="background: url('{{ asset('storage/' . $shopBg) }}') center center/cover no-repeat;" @endif>
                                                @if($shopLogo)
                                                    <img src="{{ asset('storage/' . $shopLogo) }}" alt="Logo shop" class="object-contain w-full h-full">
                                                @else
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="font-semibold text-gray-800">{{ $shopOrder->shop->shop_name ?? 'Không xác định' }}</h6>
                                                <p class="text-sm text-gray-500">Mã shop order: {{ $shopOrder->code ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium ring-1 ring-inset 
                                                @if($shopOrder->status === 'pending') bg-yellow-100 text-yellow-800 ring-yellow-600/20
                                                @elseif($shopOrder->status === 'confirmed') bg-blue-100 text-blue-800 ring-blue-600/20
                                                @elseif($shopOrder->status === 'shipped') bg-purple-100 text-purple-800 ring-purple-600/20
                                                @elseif($shopOrder->status === 'delivered') bg-green-100 text-green-800 ring-green-600/20
                                                @elseif($shopOrder->status === 'completed') bg-emerald-100 text-emerald-800 ring-emerald-600/20
                                                @elseif($shopOrder->status === 'cancelled') bg-red-100 text-red-800 ring-red-600/20
                                                @elseif($shopOrder->status === 'returned') bg-orange-100 text-orange-800 ring-orange-600/20
                                                @else bg-gray-100 text-gray-800 ring-gray-600/20 @endif">
                                                {{ $statuses[$shopOrder->status] ?? $shopOrder->status }}
                                            </span>
                                            <!-- Nút xem chi tiết order_shop -->
                                            <a href="{{ route('user.order.detail', ['orderID' => $shopOrder->id]) }}"
                                               class="ml-2 inline-flex items-center px-3 py-1 border border-indigo-500 text-indigo-600 text-xs font-medium rounded-full hover:bg-indigo-50 transition"
                                               title="Xem chi tiết đơn shop">
                                                Xem chi tiết
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Shop Order Details -->
                                <div class="p-6">
                                    <!-- Shipping Info -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                        <div class="text-sm">
                                            <span class="font-medium text-gray-700">Đơn vị vận chuyển:</span>
                                            <p class="text-gray-600">{{ $shopOrder->shipping_provider ?? 'Chờ xác nhận' }}</p>
                                        </div>
                                        <div class="text-sm">
                                            <span class="font-medium text-gray-700">Phí vận chuyển:</span>
                                            <p class="text-gray-600">{{ number_format($shopOrder->shipping_shop_fee ?? 0, 0, ',', '.') }} VND</p>
                                        </div>
                                        <div class="text-sm">
                                            <span class="font-medium text-gray-700">Mã vận đơn :</span>
                                            <p class="text-gray-600">{{ $shopOrder->tracking_code ?? 'Chưa có' }}</p>
                                        </div>
                                    </div>

                                    <!-- Products Table -->
                                    @if($shopOrder->items && $shopOrder->items->count() > 0)
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
                                                    @foreach($shopOrder->items as $item)
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

                                        <!-- Shop Order Summary -->
                                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm font-medium text-gray-700">Tổng tiền shop order:</span>
                                                <span class="text-lg font-bold text-blue-600">
                                                    {{ number_format($shopOrder->items->sum('total_price'), 0, ',', '.') }} VND
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center py-8">
                                            <p class="text-gray-500 text-sm">Không có sản phẩm nào trong shop order này.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-gray-500 text-sm">Không có shop orders nào trong đơn hàng này.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tổng quan đơn hàng -->
        <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
            <h5 class="font-semibold text-gray-800 mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Tổng quan đơn hàng lớn
            </h5>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Tổng tiền hàng</span>
                        <span class="font-medium text-gray-900">
                                {{ number_format($parentOrder->total_price - $parentOrder->shipping_fee, 0, ',', '.') }} VND
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Phí vận chuyển</span>
                        <span class="font-medium text-gray-900">
                            {{ number_format($parentOrder->shipping_fee, 0, ',', '.') }} VND
                        </span>
                    </div>
                    
                    @if (!empty($parentOrder->discount_amount) && $parentOrder->discount_amount > 0)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Giảm giá</span>
                        <span class="font-medium text-green-600">
                            -{{ number_format($parentOrder->discount_amount, 0, ',', '.') }} VND
                        </span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between items-center py-3 border-t-2 border-gray-200">
                        <span class="text-lg font-bold text-gray-900">Tổng cộng</span>
                        <span class="text-lg font-bold text-blue-600">
                            {{ number_format($parentOrder->total_price, 0, ',', '.') }} VND
                        </span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <h6 class="font-semibold text-yellow-800 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-1 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"></path>
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"></circle>
                            </svg>
                            Lịch sử trạng thái đơn hàng
                        </h6>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-2 py-1 text-left font-semibold">Thời gian</th>
                                        <th class="px-2 py-1 text-left font-semibold">Trạng thái</th>
                                        <th class="px-2 py-1 text-left font-semibold">Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($parentOrder->statusHistory as $history)
                                        <tr>
                                            <td class="px-2 py-1 whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($history->created_at)->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-2 py-1">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                    @if(in_array($history->order_status, ['completed', 'delivered'])) bg-green-100 text-green-800
                                                    @elseif(in_array($history->order_status, ['cancelled', 'refunded', 'damage', 'lost'])) bg-red-100 text-red-800
                                                    @elseif(in_array($history->order_status, ['pending', 'processing'])) bg-yellow-100 text-yellow-800
                                                    @else bg-blue-100 text-blue-800 @endif">
                                                    {{ $statuses[$history->order_status] ?? ucfirst(str_replace('_', ' ', $history->order_status)) }}
                                                </span>
                                            </td>
                                            <td class="px-2 py-1">
                                                {{ $history->note ?? '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-2 py-2 text-center text-gray-400">Chưa có lịch sử trạng thái.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
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