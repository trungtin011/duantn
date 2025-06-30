<!-- Hiển thị đơn hàng -->
<div class="order-block bg-white shadow-sm rounded-lg mb-4">
    <div class="flex items-center justify-between py-4 px-4 sm:px-6 border-b border-gray-200">
        <div class="flex items-center">
            <h6 class="font-bold text-base sm:text-lg mr-3 mb-0">
                {{ $order->shop ? $order->shop->shop_name : 'Unknown Shop' }}
            </h6>
        </div>
        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $order->status_classes }}">
            {{ $order->status_label }}
        </span>
    </div>

    <div class="order-body px-4 sm:px-6 py-4 {{ $order->order_status === 'cancelled' ? 'filter grayscale opacity-75' : '' }}">
        @forelse ($order->items as $item)
            <div class="product-row flex justify-between items-center py-3 {{ !$loop->last ? 'border-b border-dashed' : '' }}">
                <div class="flex items-center">
                    <div class="w-[120px] h-[120px] sm:w-[160px] sm:h-[160px] bg-gray-100 rounded flex items-center justify-center mr-3 overflow-hidden {{ $order->order_status === 'cancelled' ? 'opacity-50' : '' }}">
                        <img src="{{ $item->product_image ?? 'https://via.placeholder.com/150' }}" alt="{{ $item->product_name }}" class="object-contain w-full h-full">
                    </div>
                    <div class="flex flex-col gap-2">
                        <h6 class="font-normal text-sm sm:text-base mb-0 {{ $order->order_status === 'cancelled' ? 'text-gray-400' : '' }}">
                            {{ $item->product_name }}
                        </h6>
                        <div class="text-xs sm:text-sm {{ $order->order_status === 'cancelled' ? 'text-gray-400' : 'text-gray-500' }}">
                            <p>Số lượng: {{ $item->quantity }}</p>
                            <p>{{ $item->variant->variant_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center pr-4">
                    <span class="font-bold text-sm sm:text-base flex items-center gap-2 {{ $order->order_status === 'cancelled' ? 'text-gray-400' : 'text-black' }}">
                        <span class="font-thin line-through {{ $order->order_status === 'cancelled' ? 'text-gray-300' : 'text-gray-400' }}">
                            {{ number_format($item->product->price ?? 0, 0, ',', '.') }}đ
                        </span>
                        <span class="font-thin {{ $order->order_status === 'cancelled' ? 'text-gray-400' : 'text-red-500' }}">
                            {{ number_format($item->unit_price, 0, ',', '.') }}đ
                        </span>
                    </span>
                </div>
            </div>

           @if ($order->order_status === 'delivered' && !in_array($item->productID, $reviewedProductIds))
    <div class="text-right mt-2">
        <button
            class="open-review-modal bg-[#DB4444] text-white px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm hover:bg-[#CF4343]"
            data-order-id="{{ $order->id }}"
            data-shop-id="{{ $order->shop->id }}"
            data-product-id="{{ $item->productID }}"
            data-product-name="{{ $item->product_name }}">
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
            <span class="text-red-500">{{ number_format($order->total_price, 0, ',', '.') }}đ</span>
        </span>
        <a href="{{ route('user.orders.show', $order->id) }}"
            class="border border-gray-500 text-gray-700 px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm hover:bg-black hover:text-white">
            Xem chi tiết
        </a>
    </div>
</div>

<!-- Modal đánh giá -->
<div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white w-full max-w-lg p-6 rounded shadow relative">
        <button id="closeModalBtn" class="absolute top-2 right-2 text-gray-600 hover:text-black text-xl">&times;</button>
        <h2 id="modalProductName" class="text-lg font-bold mb-4">Đánh giá sản phẩm</h2>

        <form id="reviewForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="orderID" id="modalOrderId">
            <input type="hidden" name="shopID" id="modalShopId">
            <input type="hidden" name="productID" id="modalProductId">

            <label class="block mb-2 font-semibold">Đánh giá sao:</label>
            <div class="flex mb-4" id="modalStarRating">
                @for ($i = 1; $i <= 5; $i++)
                <i class="bi bi-star-fill text-3xl text-gray-400 cursor-pointer mx-1 transition-colors duration-150"
                    data-value="{{ $i }}"></i>
                @endfor
                <input type="hidden" name="rating" id="modalRating" value="0">
            </div>

            <label class="block mb-2 font-semibold">Bình luận:</label>
            <textarea name="comment" class="w-full border p-2 mb-4" rows="4"></textarea>

            <label class="block mb-2 font-semibold">Hình ảnh:</label>
            <input type="file" name="images[]" accept="image/*" multiple class="mb-4">

            <label class="block mb-2 font-semibold">Video:</label>
            <input type="file" name="video" accept="video/*" class="mb-4">

            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Gửi đánh giá</button>
        </form>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('#modalStarRating i');
    const ratingInput = document.getElementById('modalRating');
    const reviewModal = document.getElementById('reviewModal');
    const reviewForm = document.getElementById('reviewForm');

    const orderIdInput = document.getElementById('modalOrderId');
    const shopIdInput = document.getElementById('modalShopId');
    const productIdInput = document.getElementById('modalProductId');
    const productNameEl = document.getElementById('modalProductName');

    document.querySelectorAll('.open-review-modal').forEach(button => {
        button.addEventListener('click', function () {
            orderIdInput.value = this.dataset.orderId;
            shopIdInput.value = this.dataset.shopId;
            productIdInput.value = this.dataset.productId;
            productNameEl.innerText = `Đánh giá: ${this.dataset.productName}`;

            ratingInput.value = 0;
            stars.forEach(s => {
                s.classList.remove('text-yellow-500');
                s.classList.add('text-gray-400');
            });

            const actionUrl = "{{ route('reviews.store', ':id') }}".replace(':id', this.dataset.orderId);
            reviewForm.setAttribute('action', actionUrl);

            reviewModal.classList.remove('hidden');
            reviewModal.classList.add('flex');
        });
    });

    document.getElementById('closeModalBtn').addEventListener('click', function () {
        reviewModal.classList.add('hidden');
        reviewModal.classList.remove('flex');
    });

    stars.forEach((star, index) => {
        star.addEventListener('mouseover', () => {
            stars.forEach((s, i) => {
                s.classList.toggle('text-yellow-500', i <= index);
                s.classList.toggle('text-gray-400', i > index);
            });
        });

        star.addEventListener('mouseout', () => {
            const selected = parseInt(ratingInput.value);
            stars.forEach((s, i) => {
                s.classList.toggle('text-yellow-500', i < selected);
                s.classList.toggle('text-gray-400', i >= selected);
            });
        });

        star.addEventListener('click', () => {
            ratingInput.value = star.dataset.value;
        });
    });
});
</script>
@endpush
<!-- End of order-block -->