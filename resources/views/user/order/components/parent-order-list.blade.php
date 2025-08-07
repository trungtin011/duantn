@forelse ($orders as $parentOrder)
    <!-- Parent Order Block -->
    <div class="order-block bg-white shadow-lg rounded-xl mb-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between py-5 px-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <div class="flex flex-col">
                    <h6 class="text-sm font-medium text-gray-600">Mã đơn hàng: {{ $parentOrder->order_code }}</h6>
                    <h6 class="text-sm font-medium text-gray-600">Ngày đặt: {{ $parentOrder->created_at->format('d/m/Y') }}</h6>
                </div>
            </div>

            <span class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-semibold ring-1 ring-inset 
                @if($parentOrder->order_status === 'pending') bg-yellow-100 text-yellow-800 ring-yellow-600/20
                @elseif($parentOrder->order_status === 'processing') bg-blue-100 text-blue-800 ring-blue-600/20
                @elseif($parentOrder->order_status === 'completed') bg-emerald-100 text-emerald-800 ring-emerald-600/20
                @elseif($parentOrder->order_status === 'cancelled') bg-red-100 text-red-800 ring-red-600/20
                @else bg-gray-100 text-gray-800 ring-gray-600/20 @endif">
                @if($parentOrder->order_status === 'pending')
                    @if($parentOrder->payment_status === 'pending')
                        Chưa thanh toán
                    @else
                        Chưa xử lý
                    @endif
                @else
                    {{ $statuses[$parentOrder->order_status] ?? $parentOrder->order_status }}
                @endif
            </span>
        </div>

        <div class="order-body px-6 py-5 {{ $parentOrder->order_status === 'cancelled' ? 'filter grayscale opacity-75' : '' }}">
            @php
                // Nhóm các sản phẩm theo shop
                $shopGroups = collect();
                foreach ($parentOrder->shopOrders as $shopOrder) {
                    $shopId = $shopOrder->shopID;
                    $shop = $shopOrder->shop;
                    
                    if (!$shopGroups->has($shopId)) {
                        $shopGroups->put($shopId, [
                            'shop' => $shop,
                            'shopOrder' => $shopOrder,
                            'items' => collect()
                        ]);
                    }
                    
                    foreach ($shopOrder->items as $item) {
                        $shopGroups->get($shopId)['items']->push($item);
                    }
                }
            @endphp

            @forelse ($shopGroups as $shopId => $shopGroup)
                @php
                    $shop = $shopGroup['shop'];
                    $shopOrder = $shopGroup['shopOrder'];
                    $items = $shopGroup['items'];
                @endphp
                
                <!-- Shop Group Section -->
                <div class="shop-group mb-6 last:mb-0">
                    <div class="flex items-center justify-between mb-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            @php
                                $shopLogo = $shop->shop_logo ?? null;
                                $shopBg = $shop->shop_banner ?? null;
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
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-700">
                                    {{ $shop->shop_name ?? 'Không xác định' }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $items->count() }} sản phẩm</span>
                            </div>
                        </div>
                        <span class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-medium ring-1 ring-inset 
                            @if($shopOrder->status === 'pending') bg-yellow-100 text-yellow-800 ring-yellow-600/20
                            @elseif($shopOrder->status === 'processing') bg-blue-100 text-blue-800 ring-blue-600/20
                            @elseif($shopOrder->status === 'shipped') bg-purple-100 text-purple-800 ring-purple-600/20
                            @elseif($shopOrder->status === 'delivered') bg-green-100 text-green-800 ring-green-600/20
                            @elseif($shopOrder->status === 'completed') bg-emerald-100 text-emerald-800 ring-emerald-600/20
                            @elseif($shopOrder->status === 'cancelled') bg-red-100 text-red-800 ring-red-600/20
                            @elseif($shopOrder->status === 'returned') bg-orange-100 text-orange-800 ring-orange-600/20
                            @else bg-gray-100 text-gray-800 ring-gray-600/20 @endif">
                            {{ $statuses[$shopOrder->status] ?? $shopOrder->status }}
                        </span>
                    </div>

                    <!-- Products in this shop -->
                    <div class="products-container space-y-3">
                        @foreach($items as $item)
                            <div class="product-row flex justify-between items-center py-3 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center flex-1">
                                    <div class="w-[80px] h-[80px] sm:w-[100px] sm:h-[100px] bg-gray-50 rounded-lg flex items-center justify-center mr-4 overflow-hidden border border-gray-200 {{ $parentOrder->order_status === 'cancelled' ? 'opacity-50' : '' }}">
                                        @php
                                            $variantId = $item->variantID ?? null;
                                            $productImages = $item->product->images ?? collect();
                                            $variantImage = $productImages->firstWhere('variantID', $variantId);
                                            $defaultProductImage = $productImages->firstWhere('is_default', 1) ?? $productImages->first();
                                            $imageToShow = $variantImage->image_path ?? ($defaultProductImage->image_path ?? 'https://via.placeholder.com/150');
                                        @endphp

                                        <img src="{{ asset('storage/' . $imageToShow) }}" alt="{{ $item->product_name }}"
                                            class="object-contain w-full h-full rounded-md">
                                    </div>
                                    <div class="flex flex-col gap-2 flex-1">
                                        <h6 class="font-semibold text-sm sm:text-base mb-0 text-gray-800 {{ $parentOrder->order_status === 'cancelled' ? 'text-gray-400' : '' }}">
                                            {{ $item->product_name }}
                                        </h6>
                                        <div class="text-xs sm:text-sm {{ $parentOrder->order_status === 'cancelled' ? 'text-gray-400' : 'text-gray-500' }}">
                                            <p class="flex items-center gap-2">
                                                <span class="font-medium">Số lượng:</span>
                                                <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full text-xs font-medium">{{ $item->quantity }}</span>
                                            </p>
                                            @if($item->variant && $item->variant->variant_name)
                                                <p class="text-gray-600">Phân loại: {{ $item->variant->variant_name }}</p>
                                            @elseif($item->combo && $item->combo->products)
                                                @php
                                                    $comboProduct = $item->combo->products->firstWhere('productID', $item->productID);
                                                @endphp
                                                @if($comboProduct && $comboProduct->variant && $comboProduct->variant->variant_name)
                                                    <p class="text-gray-600">Phân loại: {{ $comboProduct->variant->variant_name }}</p>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center pr-4">
                                    <span class="font-bold text-sm sm:text-base flex items-center gap-2 {{ $parentOrder->order_status === 'cancelled' ? 'text-gray-400' : 'text-black' }}">
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
                                            <span class="font-thin line-through {{ $parentOrder->order_status === 'cancelled' ? 'text-gray-300' : 'text-gray-400' }}">
                                                {{ number_format($originalPrice, 0, ',', '.') }}đ
                                            </span>
                                        @endif
                                        <span class="font-semibold {{ $parentOrder->order_status === 'cancelled' ? 'text-gray-400' : 'text-red-600' }}">
                                            {{ number_format($currentPrice, 0, ',', '.') }}đ
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Shop-specific actions -->
                    <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-100">
                        {{-- @if ($shopOrder->status === 'completed')
                            @foreach($items as $item)
                                @if (!in_array($item->productID, $reviewedProductIds))
                                    <button class="open-review-modal bg-gradient-to-r from-red-500 to-red-600 text-white px-3 py-1.5 text-xs sm:text-sm hover:from-red-600 hover:to-red-700 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg font-medium"
                                        data-product-id="{{ $item->productID }}"
                                        data-product-name="{{ $item->product_name }}"
                                        data-product-image="{{ asset('storage/' . $imageToShow) }}"
                                        data-product-variant-name="{{ $item->variant->variant_name ?? ($item->combo && $item->combo->products ? ($item->combo->products->firstWhere('productID', $item->productID)->variant->variant_name ?? '') : '') }}"
                                        data-order-id="{{ $parentOrder->id }}" 
                                        data-shop-id="{{ $shopOrder->shopID }}">
                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674c.1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                        Đánh giá sản phẩm
                                    </button>
                                @endif
                            @endforeach
                        @endif --}}

                        @if ($shopOrder->status === 'delivered')
                            <button class="open-refund-modal bg-gradient-to-r from-orange-500 to-orange-600 text-white px-3 py-1.5 text-xs sm:text-sm hover:from-orange-600 hover:to-orange-700 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg font-medium"
                                data-order-id="{{ $shopOrder->id }}">
                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                </svg>
                                Yêu cầu trả hàng
                            </button>
                            <button class="confirm-received bg-gradient-to-r from-green-500 to-green-600 text-white px-3 py-1.5 text-xs sm:text-sm hover:from-green-600 hover:to-green-700 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg font-medium"
                                data-order-id="{{ $shopOrder->id }}">
                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Xác nhận đã nhận
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500 text-sm">Không có sản phẩm nào trong đơn hàng này.</p>
                </div>
            @endforelse
        </div>

        <div class="order-footer bg-gradient-to-r from-gray-50 to-gray-100 py-4 px-6 flex flex-wrap justify-between items-center rounded-b-xl">
            <div class="flex items-center mb-3 sm:mb-0">
                <span class="font-bold text-sm sm:text-base text-gray-700">
                    Tổng tiền đơn hàng:
                    <span class="text-red-600 ml-1">{{ number_format($parentOrder->total_price, 0, ',', '.') }}đ</span>
                </span>
            </div>

            <div class="flex flex-wrap gap-2">
                @if (in_array($parentOrder->order_status, ['pending', 'processing']))
                    @if ($parentOrder->payment_status === 'pending')
                        <button onclick="showGlobalPopup('{{$parentOrder->order_code}}');" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 text-xs sm:text-sm hover:from-green-600 hover:to-green-700 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg font-medium"
                            data-order-id="{{ $parentOrder->id }}">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"></path>
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"></circle>
                            </svg>
                            Thanh Toán
                        </button>
                    @endif
                    <button class="open-cancel-modal bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 text-xs sm:text-sm hover:from-red-600 hover:to-red-700 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg font-medium"
                        data-order-id="{{ $parentOrder->id }}">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Hủy đơn hàng
                    </button>
                @elseif ($parentOrder->order_status === 'pending' && in_array($parentOrder->payment_status, ['paid', 'cod_paid']))
                    <button class="open-cancel-modal bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 text-xs sm:text-sm hover:from-red-600 hover:to-red-700 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg font-medium"
                        data-order-id="{{ $parentOrder->id }}">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Hủy đơn hàng
                    </button>
                @endif

                @if ($parentOrder->order_status === 'completed')
                    <a href="{{ route('user.order.reorder', $parentOrder->id) }}"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 text-xs sm:text-sm hover:from-blue-600 hover:to-blue-700 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Đặt lại
                    </a>
                @endif

                <a href="{{ route('user.order.parent-detail', $parentOrder->order_code) }}"
                    class="border-2 border-gray-300 text-gray-700 px-4 py-2 text-xs sm:text-sm hover:bg-gray-800 hover:text-white hover:border-gray-800 rounded-lg transition-all duration-200 font-medium">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Xem chi tiết
                </a>
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-12">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Không có đơn hàng nào</h3>
        <p class="text-gray-500">Bạn chưa có đơn hàng nào trong trạng thái này.</p>
    </div>
@endforelse 