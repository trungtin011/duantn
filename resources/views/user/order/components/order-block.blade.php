<!-- Hiển thị đơn hàng (thêm nút hủy) -->
<div class="order-block bg-white shadow-sm rounded-lg mb-4">
    <div class="flex items-center justify-between py-4 px-4 sm:px-6 border-b border-gray-200">
        <h6 class="text-sm text-gray-500">Ngày đặt: {{ $order->created_at->format('d/m/Y') }}</h6>

        @php
            // Map trạng thái sang tiếng Việt
            $statusMap = [
                'pending'    => 'Chờ xác nhận',
                'processing' => 'Đang xử lý',
                'shipped'    => 'Đang giao hàng',
                'delivered'  => 'Đã hoàn thành',
                'cancelled'  => 'Đã hủy',
                'refunded'   => 'Trả hàng/Hoàn tiền',
            ];
            $orderStatus = $order->order_status ?? '';
            $statusClass = $order->status_classes ?? 'bg-gray-100 text-gray-500';
            $statusLabel = $statusMap[$orderStatus] ?? ucfirst($orderStatus);
        @endphp
        <span
            class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $statusClass }}">
            {{ $statusLabel }}
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
                            class="font-normal text-sm sm:text-base mb-0 {{ $order->order_status === 'cancelled' ? 'text-gray-400' : '' }}">
                            {{ $item->product_name }}
                        </h6>
                        <div
                            class="text-xs sm:text-sm {{ $order->order_status === 'cancelled' ? 'text-gray-400' : 'text-gray-500' }}">
                            <p>Số lượng: {{ $item->quantity }}</p>
                            <p>{{ $item->variant->variant_name ?? '' }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center pr-4">
                    <span
                        class="font-bold text-sm sm:text-base flex items-center gap-2 {{ $order->order_status === 'cancelled' ? 'text-gray-400' : 'text-black' }}">
                        <span
                            class="font-thin line-through {{ $order->order_status === 'cancelled' ? 'text-gray-300' : 'text-gray-400' }}">
                            @if(isset($item->combo_id) && $item->combo_id)
                                @php
                                    // Lấy thông tin sản phẩm từ combo_products
                                    $comboProduct = $item->combo->products->where('productID', $item->productID)->first();
                                    if ($comboProduct && $comboProduct->variantID) {
                                        // Nếu có variantID, lấy giá từ variant
                                        $variant = \App\Models\ProductVariant::find($comboProduct->variantID);
                                        $originalPrice = $variant ? $variant->price : 0;
                                    } else {
                                        // Nếu không có variantID, lấy giá từ product
                                        $product = \App\Models\Product::find($item->productID);
                                        $originalPrice = $product ? $product->price : 0;
                                    }
                                @endphp
                                {{ number_format($originalPrice, 0, ',', '.') }}đ
                            @else
                                @if($item->variantID)
                                    {{ number_format($item->variant->price ?? 0, 0, ',', '.') }}đ
                                @else
                                    {{ number_format($item->product->price ?? 0, 0, ',', '.') }}đ
                                @endif
                            @endif
                        </span>
                        <span
                            class="font-thin {{ $order->order_status === 'cancelled' ? 'text-gray-400' : 'text-red-500' }}">
                            {{ number_format($item->unit_price, 0, ',', '.') }}đ
                        </span>
                    </span>
                </div>
            </div>

            @if ($order->order_status === 'delivered' && !in_array($item->productID, $reviewedProductIds))
                <div class="text-right mt-2">
                    <button
                        class="open-review-modal bg-[#DB4444] text-white px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm hover:bg-[#CF4343]"
                        data-product-id="{{ $item->productID }}" data-product-name="{{ $item->product_name }}"
                        data-order-id="{{ $order->id }}" data-shop-id="{{ $item->shopOrder->shopID }}">
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

        @if (in_array($order->order_status, ['pending', 'processing']))
            <button
                class="open-cancel-modal bg-red-500 text-white px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm hover:bg-red-600 mr-4"
                data-order-id="{{ $order->id }}">
                Hủy đơn hàng
            </button>
        @endif

        <a href="{{ route('user.order.show', $order->id) }}"
            class="border border-gray-500 text-gray-700 px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm hover:bg-black hover:text-white">
            Xem chi tiết
        </a>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logic cho các tab trạng thái đơn hàng
            const tabButtons = document.querySelectorAll('#orderStatusTabs button');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Xóa trạng thái active khỏi tất cả các nút
                    tabButtons.forEach(btn => {
                        btn.classList.remove('text-black');
                        btn.classList.add('text-gray-500');
                    });

                    // Thêm trạng thái active cho nút được nhấp
                    this.classList.remove('text-gray-500');
                    this.classList.add('text-black');

                    // Ẩn tất cả các tab pane
                    tabPanes.forEach(pane => {
                        pane.classList.add('hidden');
                    });

                    // Hiển thị tab pane tương ứng
                    const targetPaneId = this.getAttribute('data-target');
                    const targetPane = document.querySelector(targetPaneId);
                    if (targetPane) {
                        targetPane.classList.remove('hidden');
                    }
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

            // Xử lý phân trang AJAX
            document.querySelectorAll('.pagination a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.href;
                    const targetPane = this.closest('.tab-pane');

                    fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'text/html',
                            }
                        })
                        .then(response => response.text())
                        .then(html => {
                            targetPane.innerHTML = html;
                        })
                        .catch(error => {
                            console.error('Lỗi khi tải phân trang:', error);
                        });
                });
            });

            // Gắn sự kiện thông qua phần tử cha
            document.getElementById('orderStatusTabsContent').addEventListener('click', function(e) {
                if (e.target.classList.contains('open-review-modal')) {
                    e.preventDefault();
                    orderIdInput.value = e.target.dataset.orderId;
                    shopIdInput.value = e.target.dataset.shopId;
                    productIdInput.value = e.target.dataset.productId;
                    productNameEl.innerText = `Đánh giá: ${e.target.dataset.productName}`;

                    ratingInput.value = 0;
                    stars.forEach(s => {
                        s.classList.remove('text-yellow-500');
                        s.classList.add('text-gray-400');
                    });

                    const actionUrl = "{{ route('reviews.store', ':id') }}".replace(':id', e.target.dataset
                        .orderId);
                    reviewForm.setAttribute('action', actionUrl);

                    reviewModal.classList.remove('hidden');
                    reviewModal.classList.add('flex');
                }
            });

            document.getElementById('closeModalBtn').addEventListener('click', function() {
                reviewModal.classList.add('hidden');
                reviewModal.classList.remove('flex');
            });

            // Logic cho hủy đơn
            const cancelModal = document.getElementById('cancelModal');
            const cancelForm = document.getElementById('cancelForm');
            const orderIdInputCancel = document.getElementById('modalOrderIdCancel');

            document.getElementById('orderStatusTabsContent').addEventListener('click', function(e) {
                if (e.target.classList.contains('open-cancel-modal')) {
                    e.preventDefault();
                    orderIdInputCancel.value = e.target.dataset.orderId;
                    const actionUrl = "{{ route('user.order.cancel', ':id') }}".replace(':id', e.target
                        .dataset.orderId);
                    cancelForm.setAttribute('action', actionUrl);
                    cancelModal.classList.remove('hidden');
                    cancelModal.classList.add('flex');
                }
            });

            document.querySelectorAll('#closeCancelModalBtn, #closeCancelModalBtnSubmit').forEach(button => {
                button.addEventListener('click', function() {
                    cancelModal.classList.add('hidden');
                    cancelModal.classList.remove('flex');
                });
            });

            // Logic chọn sao đánh giá
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = this.dataset.value;
                    ratingInput.value = value;
                    stars.forEach(s => {
                        s.classList.toggle('text-yellow-500', s.dataset.value <= value);
                        s.classList.toggle('text-gray-400', s.dataset.value > value);
                    });
                });
            });
        });
    </script>
@endpush
