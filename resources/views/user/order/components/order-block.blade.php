<div class="order-block bg-white shadow-sm rounded-lg mb-4">
    <div class="flex items-center justify-between py-4 px-4 sm:px-6 border-b border-gray-200">
        <div class="flex items-center">
            <h6 class="font-bold text-base sm:text-lg mr-3 mb-0">
                {{ $order->shop ? $order->shop->shop_name : 'Unknown Shop' }}
            </h6>
            <button
                class="bg-black border border-gray-500 text-white px-2 sm:px-3 py-1 sm:py-2 rounded text-xs sm:text-sm flex items-center mr-2 hover:bg-white hover:text-black">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                </svg>
                Chat
            </button>
            <button
                class="border border-gray-500 text-gray-700 px-2 sm:px-3 py-1 sm:py-2 rounded text-xs sm:text-sm flex items-center hover:bg-[#DB4444] hover:text-white hover:border-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                </svg>
                Xem Shop
            </button>
        </div>
        <span
            class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $order->status_classes }}">
            {{ $order->status_label }}
        </span>
    </div>
    <div
        class="order-body px-4 sm:px-6 py-4 {{ $order->order_status === 'cancelled' ? 'filter grayscale opacity-75' : '' }}">
        @forelse ($order->items as $item)
            <div
                class="product-row flex justify-between items-center py-3 {{ !$loop->last ? 'border-b border-dashed' : '' }}">
                <div class="flex items-center">
                    <div
                        class="w-[120px] h-[120px] sm:w-[160px] sm:h-[160px] bg-gray-100 rounded flex items-center justify-center mr-3 overflow-hidden {{ $order->order_status === 'cancelled' ? 'opacity-50' : '' }}">
                        <img src="{{ $item->product_image ?? 'https://via.placeholder.com/150' }}"
                            alt="{{ $item->product_name }}" class="object-contain w-full h-full">
                    </div>
                    <div class="flex flex-col gap-2">
                        <h6
                            class="font-normal text-sm sm:text-base mb-0 {{ $order->order_status === 'cancelled' ? 'text-gray-400' : '' }}">
                            {{ $item->product_name }}
                        </h6>
                        <div
                            class="text-xs sm:text-sm {{ $order->order_status === 'cancelled' ? 'text-gray-400' : 'text-gray-500' }}">
                            <p>Số lượng: {{ $item->quantity }}</p>
                            <p>{{ $item->variant->variant_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center pr-4">
                    <span
                        class="font-bold text-sm sm:text-base flex items-center gap-2 {{ $order->order_status === 'cancelled' ? 'text-gray-400' : 'text-black' }}">
                        <span
                            class="font-thin line-through {{ $order->order_status === 'cancelled' ? 'text-gray-300' : 'text-gray-400' }}">
                            {{ number_format($item->product->price, 0, ',', '.') }}đ
                        </span>
                        <span
                            class="font-thin {{ $order->order_status === 'cancelled' ? 'text-gray-400' : 'text-red-500' }}">
                            {{ number_format($item->unit_price, 0, ',', '.') }}đ
                        </span>
                    </span>
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-center">Không có sản phẩm trong đơn hàng.</p>
        @endforelse
    </div>
    <div class="order-footer bg-[#EF3248]/5 py-3 px-4 sm:px-6 flex flex-col items-end gap-4">
        <span class="font-bold text-sm sm:text-base font-thin mr-4">
            Thành tiền:
            <span class="text-red-500 text-2xl font-thin">{{ number_format($order->total_price, 0, ',', '.') }}đ</span>
        </span>
       <div class="order-footer bg-gray-50 py-3 px-4 sm:px-6 flex justify-end items-center">
    <span class="font-bold text-sm sm:text-base mr-4">Thành tiền:
        <span class="text-red-500">{{ number_format($order->total_price, 0, ',', '.') }}đ</span>
    </span>

    @if ($order->order_status === 'delivered')
        <button
            class="bg-[#DB4444] text-white px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm mr-2 hover:bg-[#CF4343]">
            Mua Lại
        </button>

        <a href="{{ route('reviews.create', ['order_id' => $order->id]) }}"
            class="border border-green-600 text-green-600 px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm mr-2 hover:bg-green-600 hover:text-white">
            Đánh giá sản phẩm
        </a>

        <button
            class="border border-gray-500 text-gray-700 px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm mr-2 hover:bg-black hover:text-white">
            Liên hệ người bán
        </button>
    @elseif ($order->order_status === 'cancelled' || $order->order_status === 'refunded')
        <!-- Không hiển thị gì thêm -->
    @else
        <button
            class="border border-gray-500 text-gray-700 px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm mr-2 hover:bg-black hover:text-white">
            Liên hệ người bán
        </button>
    @endif

    <a href="{{ route('user.orders.show', $order->id) }}"
        class="border border-gray-500 text-gray-700 px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm hover:bg-black hover:text-white">
        Xem chi tiết
    </a>
</div>

    </div>
</div>
<br>
