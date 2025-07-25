@extends('user.account.layout')

@section('account-content')
    @include('layouts.notification')
    <div class="container mx-auto bg-white">
        <!-- Tabs for Order Status -->
        <ul class="flex items-center justify-between border border-gray-200 px-4 py-4 mb-8 overflow-x-auto"
            id="orderStatusTabs" role="tablist">
            <li class="mr-4" role="presentation">
                <button class="px-2 font-bold text-black focus:outline-none" id="all-tab" data-target="#all" type="button"
                    role="tab" aria-controls="all">Tất cả</button>
            </li>
            <li class="mr-4" role="presentation">
                <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="pending-tab"
                    data-target="#pending" type="button" role="tab" aria-controls="pending">Đang chờ xử lý</button>
            </li>
            <li class="mr-4" role="presentation">
                <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="processing-tab"
                    data-target="#processing" type="button" role="tab" aria-controls="processing">Đang xử lý</button>
            </li>
            <li class="mr-4" role="presentation">
                <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="shipped-tab"
                    data-target="#shipped" type="button" role="tab" aria-controls="shipped">Đang giao hàng</button>
            </li>
            <li class="mr-4" role="presentation">
                <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="delivered-tab"
                    data-target="#delivered" type="button" role="tab" aria-controls="delivered">Hoàn thành</button>
            </li>
            <li class="mr-4" role="presentation">
                <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="cancelled-tab"
                    data-target="#cancelled" type="button" role="tab" aria-controls="cancelled">Đã hủy</button>
            </li>
            <li class="mr-4" role="presentation">
                <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="refunded-tab"
                    data-target="#refunded" type="button" role="tab" aria-controls="refunded">Trả hàng/Hoàn
                    tiền</button>
            </li>
        </ul>

        <div class="tab-content h-full" id="orderStatusTabsContent">
            <!-- Tab Pane: Tất cả -->
            <div class="tab-pane" id="all" role="tabpanel" aria-labelledby="all-tab">
                <div class="text-sm text-red-500 text-right mr-4 font-bold mb-2">Tổng đơn hàng: {{ $allOrders->total() }}
                </div>

                @forelse ($allOrders as $order)
                    @include('user.order.components.order-block', [
                        'order' => $order,
                        'reviewedProductIds' => $reviewedProductIds,
                    ])
                @empty
                    <div class="bg-white shadow-sm rounded-lg text-center py-6">
                        <div class="p-4 flex flex-col gap-2 items-center">
                            <h5 class="text-gray-500 text-base sm:text-lg">Bạn chưa có đơn hàng nào.</h5>
                            <a href="{{ route('home') }}" class="btn btn-dark">Quay lại mua sắm</a>
                        </div>
                    </div>
                @endforelse

                {{ $allOrders->links() }}
            </div>

            <!-- Tab Pane: Đang chờ xử lý -->
            <div class="tab-pane hidden" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                @forelse ($pendingOrders as $order)
                    @include('user.order.components.order-block', [
                        'order' => $order,
                        'reviewedProductIds' => $reviewedProductIds,
                    ])
                @empty
                    <div class="bg-white shadow-sm rounded-lg text-center py-6">
                        <div class="p-4">
                            <h5 class="text-gray-500 text-base sm:text-lg">Bạn không có đơn hàng nào đang chờ xử lý.</h5>
                        </div>
                    </div>
                @endforelse
                {{ $pendingOrders->links() }}
            </div>

            <!-- Tab Pane: Đang xử lý -->
            <div class="tab-pane hidden" id="processing" role="tabpanel" aria-labelledby="processing-tab">
                @forelse ($processingOrders as $order)
                    @include('user.order.components.order-block', [
                        'order' => $order,
                        'reviewedProductIds' => $reviewedProductIds,
                    ])
                @empty
                    <div class="bg-white shadow-sm rounded-lg text-center py-6">
                        <div class="p-4">
                            <h5 class="text-gray-500 text-base sm:text-lg">Bạn không có đơn hàng nào đang xử lý.</h5>
                        </div>
                    </div>
                @endforelse
                {{ $processingOrders->links() }}
            </div>

            <!-- Tab Pane: Đang giao hàng -->
            <div class="tab-pane hidden" id="shipped" role="tabpanel" aria-labelledby="shipped-tab">
                @forelse ($shippedOrders as $order)
                    @include('user.order.components.order-block', [
                        'order' => $order,
                        'reviewedProductIds' => $reviewedProductIds,
                    ])
                @empty
                    <div class="bg-white shadow-sm rounded-lg text-center py-6">
                        <div class="p-4">
                            <h5 class="text-gray-500 text-base sm:text-lg">Bạn không có đơn hàng nào đang giao hàng.</h5>
                        </div>
                    </div>
                @endforelse
                {{ $shippedOrders->links() }}
            </div>

            <!-- Tab Pane: Hoàn thành -->
            <div class="tab-pane hidden" id="delivered" role="tabpanel" aria-labelledby="delivered-tab">
                @forelse ($deliveredOrders as $order)
                    @include('user.order.components.order-block', [
                        'order' => $order,
                        'reviewedProductIds' => $reviewedProductIds,
                    ])
                @empty
                    <div class="bg-white shadow-sm rounded-lg text-center py-6">
                        <div class="p-4">
                            <h5 class="text-gray-500 text-base sm:text-lg">Bạn không có đơn hàng nào đã hoàn thành.</h5>
                        </div>
                    </div>
                @endforelse
                {{ $deliveredOrders->links() }}
            </div>

            <!-- Tab Pane: Đã hủy -->
            <div class="tab-pane hidden" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                @forelse ($cancelledOrders as $order)
                    @include('user.order.components.order-block', [
                        'order' => $order,
                        'reviewedProductIds' => $reviewedProductIds,
                    ])
                @empty
                    <div class="bg-white shadow-sm rounded-lg text-center py-6">
                        <div class="p-4">
                            <h5 class="text-gray-500 text-base sm:text-lg">Bạn không có đơn hàng nào đã hủy.</h5>
                        </div>
                    </div>
                @endforelse
                {{ $cancelledOrders->links() }}
            </div>

            <!-- Tab Pane: Trả hàng/Hoàn tiền -->
            <div class="tab-pane hidden" id="refunded" role="tabpanel" aria-labelledby="refunded-tab">
                @forelse ($refundedOrders as $order)
                    @include('user.order.components.order-block', [
                        'order' => $order,
                        'reviewedProductIds' => $reviewedProductIds,
                    ])
                @empty
                    <div class="bg-white shadow-sm rounded-lg text-center py-6">
                        <div class="p-4">
                            <h5 class="text-gray-500 text-base sm:text-lg">Bạn không có đơn hàng nào đang yêu cầu trả
                                hàng/hoàn tiền.</h5>
                        </div>
                    </div>
                @endforelse
                {{ $refundedOrders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <!-- Modal hủy đơn -->
    <div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white w-full max-w-lg p-6 rounded shadow relative">
            <button id="closeCancelModalBtn"
                class="absolute top-2 right-2 text-gray-600 hover:text-black text-xl">×</button>
            <h2 class="text-lg font-bold mb-4">Hủy đơn hàng</h2>

            <form id="cancelForm" method="POST" action="{{ route('user.order.cancel', ':id') }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="orderID" id="modalOrderIdCancel">

                <label class="block mb-2 font-semibold">Lý do hủy đơn:</label>
                <textarea name="cancel_reason" class="w-full border p-2 mb-4" rows="3" placeholder="Nhập lý do hủy đơn..."
                    required></textarea>

                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Xác nhận
                    hủy</button>
                <button type="button" id="closeCancelModalBtnSubmit"
                    class="bg-gray-300 text-black px-4 py-2 rounded ml-2 hover:bg-gray-400">Hủy</button>
            </form>
        </div>
    </div>

    <!-- Modal đánh giá -->
    <div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white w-full max-w-2xl p-8 rounded-lg shadow-xl relative animate-fade-in-down">
            <button id="closeModalBtn"
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl transition-colors duration-200">×</button>
            <h2 id="modalProductName" class="text-xl text-gray-800 mb-6">Đánh giá sản phẩm</h2>

            <!-- Instruction Section -->
            <div class="relative border border-red-300 bg-red-50 rounded-lg overflow-visible">
                <!-- Removed mb-6, added relative, overflow-visible -->
                <div class="flex items-center justify-between p-4 cursor-pointer" id="instructionToggle">
                    <div class="flex items-center">
                        <i class="bi bi-coin text-red-500 text-xl mr-2"></i>
                        <span class="flex items-center gap-1">Xem Hướng dẫn đánh giá chuẩn để nhận đến <span
                                class="font-semibold text-red-700">200 xu!</span></span>
                    </div>
                    <i class="bi bi-chevron-down text-red-700 transition-transform duration-300"
                        id="instructionArrow"></i>
                </div>
                <div class="absolute w-full mt-2 left-0 z-10 px-4 pb-4 hidden bg-white border border-red-300 rounded-lg shadow-lg"
                    id="instructionContent"> <!-- Added absolute, w-full, left-0, z-10, bg-white, border-t-0, shadow-lg -->
                    <h4 class="font-bold text-gray-800 mb-2 mt-2">Điều kiện nhận Xu</h4>
                    <p class="text-sm text-gray-700 mb-4">Viết nhận xét với ít nhất 50 ký tự, kèm hình ảnh và/hoặc video để
                        nhận Xu bạn nhé!</p>

                    <h4 class="font-bold text-gray-800 mb-2">Xu thưởng cho đánh giá hợp lệ</h4>
                    <ul class="list-disc list-inside text-sm text-gray-700 mb-4">
                        <li>Xu thưởng từ ZynoxMall: nhập ít nhất 50 ký tự kèm 1 hình ảnh hoặc 1 video. <span
                                class="font-bold text-red-500">100 xu</span></li>
                        <li>Xu thưởng từ ZynoxMall: nhập ít nhất 50 ký tự kèm 1 hình ảnh và 1 video. <span
                                class="font-bold text-red-500">200 xu</span></li>
                    </ul>

                    <ul class="text-xs text-gray-600 space-y-1">
                        <li>* Trong 1 đơn hàng có nhiều hơn 1 sản phẩm, Bạn sẽ nhận được Xu trên từng sản phẩm nếu đánh giá
                            thỏa điều kiện</li>
                        <li>* Sản phẩm đánh giá có nội dung không liên quan hoặc không phù hợp, ZynoxMall sẽ thu hồi Xu</li>
                        <li>* Bạn sẽ không nhận được Xu nếu chỉnh sửa nội dung đánh giá</li>
                        <li>* Bạn sẽ nhận được Xu sau khi đánh giá được gửi thành công</li>
                        <li>* Điều kiện để nhận Xu khi số tiền được thanh toán của mặt hàng cao hơn ₫2.000 VNĐ.</li>
                    </ul>
                </div>
            </div>

            <!-- Product Info -->
            <div class="flex items-center mt-3 mb-6 border p-4 rounded-lg bg-gray-50">
                <img id="modalProductImage" src="" alt="Product Image"
                    class="w-24 h-24 object-contain rounded-md mr-4 border p-1">
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
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Chất lượng sản phẩm:</label>
                    <div class="flex justify-start items-center mb-4" id="modalStarRating">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star-fill text-4xl text-gray-300 cursor-pointer mx-1 transition-colors duration-200 hover:text-yellow-400"
                                data-value="{{ $i }}"></i>
                        @endfor
                        <span id="starRatingText" class="ml-2 text-lg font-semibold text-gray-600"></span>
                        <input type="hidden" name="rating" id="modalRating" value="0">
                    </div>
                </div>

                <!-- Comment Section -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Bình luận:</label>
                    <textarea name="comment" id="reviewComment"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-y"
                        rows="5" placeholder="Hãy chia sẻ những điều bạn thích về sản phẩm này với những người mua khác nhé."></textarea>
                    <p class="text-xs text-gray-500 mt-1"><span id="commentCharCount">0</span>/50 ký tự tối thiểu</p>
                </div>

                <!-- File Upload Buttons -->
                <div class="flex space-x-4 mb-4">
                    <label for="imagesUpload"
                        class="flex items-center px-4 py-2 border border-red-500 text-red-500 rounded-md cursor-pointer hover:bg-red-50 transition-colors duration-200">
                        <i class="bi bi-image mr-2"></i> Thêm Hình ảnh (<span id="imageCount">0</span>)
                    </label>
                    <input type="file" name="images[]" id="imagesUpload" accept="image/*" multiple class="hidden">

                    <label for="videoUpload"
                        class="flex items-center px-4 py-2 border border-red-500 text-red-500 rounded-md cursor-pointer hover:bg-red-50 transition-colors duration-200">
                        <i class="bi bi-camera-video mr-2"></i> Thêm Video (<span id="videoCount">0</span>)
                    </label>
                    <input type="file" name="video" id="videoUpload" accept="video/*" class="hidden">
                </div>


                <div class="flex justify-end space-x-3 mt-8">
                    <button type="button" id="cancelReviewBtn"
                        class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition-colors duration-200 font-semibold text-base">TRỞ
                        LẠI</button>
                    <button type="submit"
                        class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition-colors duration-200 font-semibold text-base">HOÀN
                        THÀNH</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

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

                document.getElementById('orderStatusTabsContent').addEventListener('click', function(e) {
                    if (e.target.classList.contains('open-review-modal')) {
                        e.preventDefault();
                        orderIdInput.value = e.target.dataset.orderId;
                        shopIdInput.value = e.target.dataset.shopId;
                        productIdInput.value = e.target.dataset.productId;
                        productNameEl.innerText = `Đánh Giá Sản Phẩm`;

                        // Set product info
                        document.getElementById('modalProductImage').src = e.target.dataset.productImage;
                        document.getElementById('modalProductDisplayName').innerText = e.target.dataset
                            .productName;
                        const modalProductVariantName = e.target.dataset.productVariantName;
                        if (modalProductVariantName) {
                            document.getElementById('modalProductVariantNameLabel').innerText =
                                'Phân loại hàng:';
                            document.getElementById('modalProductVariantName').innerText =
                                modalProductVariantName;
                        } else {
                            document.getElementById('modalProductVariantNameLabel').innerText = '';
                            document.getElementById('modalProductVariantName').innerText = '';
                        }

                        ratingInput.value = 0;
                        stars.forEach(s => {
                            s.classList.remove('text-yellow-500');
                            s.classList.add('text-gray-300'); // Use text-gray-300 for unselected stars
                        });
                        starRatingText.innerText = 'Chọn sao';

                        // Reset form fields
                        imagesUploadInput.value = '';
                        videoUploadInput.value = '';
                        reviewForm.reset();
                        commentCharCount.innerText = '0'; // Reset character count
                        imageCountSpan.innerText = '0'; // Reset image count
                        videoCountSpan.innerText = '0'; // Reset video count

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

                // Logic chọn sao đánh giá sản phẩm
                stars.forEach(star => {
                    star.addEventListener('click', function() {
                        const value = parseInt(this.dataset.value); // Chuyển đổi sang số nguyên
                        ratingInput.value = value;
                        stars.forEach(s => {
                            if (parseInt(s.dataset.value) <= value) {
                                s.classList.remove('text-gray-300');
                                s.classList.add('text-yellow-500');
                            } else {
                                s.classList.remove('text-yellow-500');
                                s.classList.add('text-gray-300');
                            }
                        });
                        starRatingText.innerText = `${value} sao`;
                        console.log('Người dùng đã chọn ' + value + ' sao cho chất lượng sản phẩm.');
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
                    imageCountSpan.innerText = this.files.length;
                    if (this.files.length > 0) {
                        console.log('Đã chọn ' + this.files.length + ' file hình ảnh.', this.files);
                    } else {
                        console.log('Không có file hình ảnh nào được chọn.');
                    }
                });

                // Logic cho file upload video
                videoUploadInput.addEventListener('change', function() {
                    videoCountSpan.innerText = this.files.length > 0 ? '1' : '0';
                    if (this.files.length > 0) {
                        console.log('Đã chọn file video:', this.files[0]);
                    } else {
                        console.log('Không có file video nào được chọn.');
                    }
                });

                // Logic cho nút TRỞ LẠI
                cancelReviewBtn.addEventListener('click', function() {
                    reviewModal.classList.add('hidden');
                    reviewModal.classList.remove('flex');
                    console.log('Đóng modal đánh giá và trở lại.');
                });
            });
        </script>
    @endpush
@endsection
