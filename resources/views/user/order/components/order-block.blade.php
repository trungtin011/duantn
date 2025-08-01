<!-- Hiển thị đơn hàng (thêm nút hủy) -->
<div
    class="order-block bg-white shadow-lg rounded-xl mb-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
    <div
        class="flex items-center justify-between py-5 px-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
        <div class="flex items-center space-x-3">
            @php
                $shopLogo = $order->shop->shop_logo ?? null;
                $shopBg = $order->shop->shop_banner ?? null;
            @endphp
            <div class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden border border-gray-200"
                @if ($shopBg) style="background: url('{{ asset('storage/' . $shopBg) }}') center center/cover no-repeat;" @endif>
                @if ($shopLogo)
                    <img src="{{ asset('storage/' . $shopLogo) }}" alt="Logo shop" class="object-contain w-full h-full">
                @else
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                @endif
            </div>
            <span class="text-sm font-medium text-gray-700">
                Tên shop: {{ $order->shop->shop_name ?? 'Không xác định' }}
            </span>
        </div>

        <span
            class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-semibold ring-1 ring-inset {{ $order->status_classes }}">
            {{ $statuses[$order->status] ?? $order->status }}
        </span>
    </div>

    <div class="order-body px-6 py-5 {{ $order->status === 'cancelled' ? 'filter grayscale opacity-75' : '' }}">
        @forelse ($order->items as $item)
            <div
                class="product-row flex justify-between items-center py-4 @if (!$loop->last) border-b border-gray-100 @endif">
                <div class="flex items-center flex-1">
                    <div
                        class="w-[100px] h-[100px] sm:w-[120px] sm:h-[120px] bg-gray-50 rounded-lg flex items-center justify-center mr-4 overflow-hidden border border-gray-200 {{ $order->order_status === 'cancelled' ? 'opacity-50' : '' }}">
                        @php
                            $variantId = $item->variantID ?? null;

                            // Tập ảnh thuộc sản phẩm
                            $productImages = $item->product->images ?? collect();

                            // Ưu tiên ảnh của biến thể (theo variantID trong bảng product_images)
                            $variantImage = $productImages->firstWhere('variantID', $variantId);

                            // Nếu không có thì lấy ảnh mặc định của sản phẩm
                            $defaultProductImage =
                                $productImages->firstWhere('is_default', 1) ?? $productImages->first();

                            // Quyết định ảnh sẽ hiển thị
                            $imageToShow =
                                $variantImage->image_path ??
                                ($defaultProductImage->image_path ?? 'https://via.placeholder.com/150');
                        @endphp

                        <img src="{{ asset('storage/' . $imageToShow) }}" alt="{{ $item->product_name }}"
                            class="object-contain w-full h-full rounded-md">

                    </div>
                    <div class="flex flex-col gap-2 flex-1">
                        <h6
                            class="font-semibold text-sm sm:text-base mb-0 text-gray-800 {{ $order->status === 'cancelled' ? 'text-gray-400' : '' }}">
                            {{ $item->product_name ?? $item->variant->variant_name }}
                        </h6>
                        <div
                            class="text-xs sm:text-sm {{ $order->status === 'cancelled' ? 'text-gray-400' : 'text-gray-500' }}">
                            <p class="flex items-center gap-2">
                                <span class="font-medium">Số lượng:</span>
                                <span
                                    class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full text-xs font-medium">{{ $item->quantity }}</span>
                            </p>
                            <p class="text-gray-600">{{ $item->variant->variant_name ?? $item->product_name }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center pr-4">
                    <span
                        class="font-bold text-sm sm:text-base flex items-center gap-2 {{ $order->status === 'cancelled' ? 'text-gray-400' : 'text-black' }}">
                        <span
                            class="font-thin line-through {{ $order->status === 'cancelled' ? 'text-gray-300' : 'text-gray-400' }}">
                            {{ number_format($item->product->price ?? 0, 0, ',', '.') }}đ
                        </span>
                        <span
                            class="font-semibold {{ $order->status === 'cancelled' ? 'text-gray-400' : 'text-red-600' }}">
                            {{ number_format($item->unit_price, 0, ',', '.') }}đ
                        </span>
                    </span>
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                    </path>
                </svg>
                <p class="text-gray-500 text-sm">Không có sản phẩm trong đơn hàng.</p>
            </div>
        @endforelse
    </div>

    <div
        class="order-footer bg-gradient-to-r from-gray-50 to-gray-100 py-4 px-6 flex flex-wrap justify-between items-center rounded-b-xl">
        <div class="flex items-center mb-3 sm:mb-0">
            <span class="font-bold text-sm sm:text-base text-gray-700">
                Thành tiền:
                <span
                    class="text-red-600 ml-1">{{ number_format($order->order->total_price ?? $order->total_price, 0, ',', '.') }}đ</span>
            </span>
        </div>

        <div class="flex flex-wrap gap-2">
            @if (in_array($order->status, ['pending', 'processing']))
                <button
                    class="open-cancel-modal bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 text-xs sm:text-sm hover:from-red-600 hover:to-red-700 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg font-medium"
                    data-order-id="{{ $order->id }}">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                    Hủy đơn hàng
                </button>
            @endif

            @if ($order->status === 'delivered')
                <button
                    class="open-refund-modal bg-gradient-to-r from-orange-500 to-orange-600 text-white px-4 py-2 text-xs sm:text-sm hover:from-orange-600 hover:to-orange-700 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg font-medium"
                    data-order-id="{{ $order->id }}">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                    </svg>
                    Yêu cầu trả hàng
                </button>
                <button
                    class="confirm-received bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 text-xs sm:text-sm hover:from-green-600 hover:to-green-700 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg font-medium"
                    data-order-id="{{ $order->id }}">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Xác nhận đã nhận
                </button>
            @endif

            @if ($order->status === 'completed')
                <a href="{{ route('user.order.reorder', $order->id) }}"
                    class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 text-xs sm:text-sm hover:from-blue-600 hover:to-blue-700 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Đặt lại
                </a>
            @endif

            <a href="{{ route('user.order.show', $order->id) }}"
                class="border-2 border-gray-300 text-gray-700 px-4 py-2 text-xs sm:text-sm hover:bg-gray-800 hover:text-white hover:border-gray-800 rounded-lg transition-all duration-200 font-medium">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                    </path>
                </svg>
                Xem chi tiết
            </a>
        </div>
    </div>
</div>