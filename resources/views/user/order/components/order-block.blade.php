<!-- Hiển thị đơn hàng (thêm nút hủy) -->
<div class="order-block bg-white shadow-sm rounded-lg mb-4">
    <div class="flex items-center justify-between py-4 px-4 sm:px-6 border-b border-gray-200">
        <h6 class="text-sm text-gray-500">Ngày đặt: {{ $order->created_at->format('d/m/Y') }}</h6>

        <span
            class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $order->status_classes }}">
            {{ $statuses[$order->status] ?? $order->status  }}
        </span>
    </div>

    <div
        class="order-body px-4 sm:px-6 py-4 {{ $order->status === 'cancelled' ? 'filter grayscale opacity-75' : '' }}">
        @forelse ($order->items as $item)
            <div
                class="product-row flex justify-between items-center py-3 @if(!$loop->last) border-b border-dashed @endif"
                <div class="flex items-center">
                    <div
                        class="w-[120px] h-[120px] sm:w-[160px] sm:h-[160px] bg-gray-100 rounded flex items-center justify-center mr-3 overflow-hidden {{ $order->order_status === 'cancelled' ? 'opacity-50' : '' }}">
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
                            class="object-contain w-full h-full">

                    </div>
                    <div class="flex flex-col gap-2">
                        <h6
                            class="font-normal text-sm sm:text-base mb-0 {{ $order->status === 'cancelled' ? 'text-gray-400' : '' }}">
                            {{ $item->product_name ?? $item->variant->variant_name }}
                        </h6>
                        <div
                            class="text-xs sm:text-sm {{ $order->status === 'cancelled' ? 'text-gray-400' : 'text-gray-500' }}">
                            <p>Số lượng: {{ $item->quantity }}</p>
                            <p>{{ $item->variant->variant_name ?? $item->product_name }}</p>
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
                            class="font-thin {{ $order->status === 'cancelled' ? 'text-gray-400' : 'text-red-500' }}">
                            {{ number_format($item->unit_price, 0, ',', '.') }}đ
                        </span>
                    </span>
                </div>
            </div>

            @if ($order->status === 'completed' && !in_array($item->productID, $reviewedProductIds))
                <div class="text-right mt-2">
                    <button
                        class="open-review-modal bg-[#DB4444] text-white px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm hover:bg-[#CF4343]"
                        data-product-id="{{ $item->productID }}" data-product-name="{{ $item->product_name }}"
                        data-order-id="{{ $order->id }}" data-shop-id="{{ $order->shopID }}">
                        Đánh giá sản phẩm
                    </button>
                </div>
            @endif
        @empty
            <p class="text-gray-500 text-center">Không có sản phẩm trong đơn hàng.</p>
        @endforelse
    </div>

    <div class="order-footer bg-gray-50 py-3 px-4 sm:px-6 flex justify-end items-center">
        <span class="font-bold text-sm sm:text-base mr-4">Thành tiền:
            <span class="text-red-500">{{ number_format($order->order->total_price ?? $order->total_price , 0, ',', '.') }}đ</span>
        </span>

        @if (in_array($order->status, ['pending', 'processing']))
            <button
                class="open-cancel-modal bg-red-500 text-white px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm hover:bg-red-600 mr-4"
                data-order-id="{{ $order->id }}">
                Hủy đơn hàng
            </button>
        @endif

        @if ($order->status === 'delivered')
            <button
                class="open-refund-modal bg-orange-500 text-white px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm hover:bg-orange-600 mr-4"
                data-order-id="{{ $order->id }}">
                Yêu cầu trả hàng
            </button>
            <button
                class="confirm-received bg-green-500 text-white px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm hover:bg-green-600 mr-4"
                data-order-id="{{ $order->id }}">
                Xác nhận đã nhận
            </button>
        @endif

        @if ($order->status === 'completed')
            <a href="{{ route('user.order.reorder', $order->id) }}"
                class="bg-blue-500 text-white px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm hover:bg-blue-600 mr-4">
                Đặt lại
            </a>
        @endif

        <a href="{{ route('user.order.show', $order->id) }}"
            class="border border-gray-500 text-gray-700 px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm hover:bg-black hover:text-white">
            Xem chi tiết
        </a>
    </div>
</div>


