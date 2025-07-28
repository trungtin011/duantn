@extends('user.account.layout')

@section('account-content')
    <div class="container mx-auto bg-white">
        <!-- Tabs for Order Status -->
        <ul class="flex items-center justify-between border border-gray-200 px-4 py-4 mb-8 overflow-x-auto"
            id="orderStatusTabs" role="tablist">
            <li class="mr-4" role="presentation">
                <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="pending-tab"
                    data-target="#pending" type="button" role="tab" aria-controls="pending">Chờ xử lý</button>
            </li>
            <li class="mr-4" role="presentation">
                <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="confirmed-tab"
                    data-target="#confirmed" type="button" role="tab" aria-controls="confirmed">Đã nhận</button>
            </li>
            <li class="mr-4" role="presentation">
                <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="shipped-tab"
                    data-target="#shipped" type="button" role="tab" aria-controls="shipped">Đang giao đến</button>
            </li>
            <li class="mr-4" role="presentation">
                <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="delivered-tab"
                    data-target="#delivered" type="button" role="tab" aria-controls="delivered">Đã giao hàng</button>
            </li>
            <li class="mr-4" role="presentation">
                <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="completed-tab"
                    data-target="#completed" type="button" role="tab" aria-controls="completed"> Hoàn thành</button>
            </li>
            <li class="mr-4" role="presentation">
                <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="cancelled-tab"
                    data-target="#cancelled" type="button" role="tab" aria-controls="cancelled">Đơn hủy</button>
            </li>
            <li class="mr-4" role="presentation">
                <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="refunded-tab"
                    data-target="#refunded" type="button" role="tab" aria-controls="refunded">Trả hàng/Hoàn
                    tiền</button>
            </li>
        </ul>

        <div class="tab-content h-full" id="orderStatusTabsContent">
            <!-- Tab Pane: Đang chờ xử lý -->
            <div class="tab-pane" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                <div id="pending-orders-container">
                    <!-- Nội dung sẽ được load bằng AJAX -->
                </div>
                <div id="pending-pagination"></div>
            </div>

            <!-- Tab Pane: Đang xử lý -->
            <div class="tab-pane hidden" id="confirmed" role="tabpanel" aria-labelledby="confirmed-tab">
                <div id="confirmed-orders-container">
                    <!-- Nội dung sẽ được load bằng AJAX -->
                </div>
                <div id="confirmed-pagination"></div>
            </div>

            <!-- Tab Pane: Đang giao hàng -->
            <div class="tab-pane hidden" id="shipped" role="tabpanel" aria-labelledby="shipped-tab">
                <div id="shipped-orders-container">
                    <!-- Nội dung sẽ được load bằng AJAX -->
                </div>
                <div id="shipped-pagination"></div>
            </div>

            <!-- Tab Pane: Hoàn thành -->
            <div class="tab-pane hidden" id="delivered" role="tabpanel" aria-labelledby="delivered-tab">
                <div id="delivered-orders-container">
                    <!-- Nội dung sẽ được load bằng AJAX -->
                </div>
                <div id="delivered-pagination"></div>
            </div>

            <!-- Tab Pane: Đã hoàn thành -->
            <div class="tab-pane hidden" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                <div id="completed-orders-container">
                    <!-- Nội dung sẽ được load bằng AJAX -->
                </div>
                <div id="completed-pagination"></div>
            </div>

            <!-- Tab Pane: Đã hủy -->
            <div class="tab-pane hidden" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                <div id="cancelled-orders-container">
                    <!-- Nội dung sẽ được load bằng AJAX -->
                </div>
                <div id="cancelled-pagination"></div>
            </div>

            <!-- Tab Pane: Trả hàng/Hoàn tiền -->
            <div class="tab-pane hidden" id="refunded" role="tabpanel" aria-labelledby="refunded-tab">
                <div id="refunded-orders-container">
                    <!-- Nội dung sẽ được load bằng AJAX -->
                </div>
                <div id="refunded-pagination"></div>
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

    <!-- Modal trả hàng -->
    <div id="refundModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white w-full max-w-lg p-6 rounded shadow relative">
            <button id="closeRefundModalBtn"
                class="absolute top-2 right-2 text-gray-600 hover:text-black text-xl">×</button>
            <h2 class="text-lg font-bold mb-4">Yêu cầu trả hàng</h2>

            <form id="refundForm" method="POST" action="{{ route('user.order.refund', ':id') }}">
                @csrf
                <input type="hidden" name="orderID" id="modalOrderIdRefund">

                <label class="block mb-2 font-semibold">Lý do trả hàng:</label>
                <textarea name="refund_reason" class="w-full border p-2 mb-4" rows="3" placeholder="Nhập lý do trả hàng..."
                    required></textarea>

                <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700">Gửi yêu cầu</button>
                <button type="button" id="closeRefundModalBtnSubmit"
                    class="bg-gray-300 text-black px-4 py-2 rounded ml-2 hover:bg-gray-400">Hủy</button>
            </form>
        </div>
    </div>

    <!-- Modal đánh giá -->
    <div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white w-full max-w-lg p-6 rounded shadow relative">
            <button id="closeModalBtn" class="absolute top-2 right-2 text-gray-600 hover:text-black text-xl">×</button>
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

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Gửi đánh
                    giá</button>
            </form>
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
        
        .text-primary {
            color: #007bff !important;
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
    </style>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                loadOrders('pending');

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
                            
                            // Load dữ liệu cho tab được chọn
                            const status = targetPaneId.replace('#', '');
                            loadOrders(status);
                        }
                    });
                });

                // Function để load đơn hàng theo status
                function loadOrders(status, page = 1) {
                    const container = document.getElementById(status + '-orders-container');
                    const paginationContainer = document.getElementById(status + '-pagination');
                    const orderCountEl = document.getElementById('order-count');
                    
                    // Hiển thị loading
                    container.innerHTML = '<div class="text-center py-8"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
                    
                    const url = "{{ route('user.order.ajax', ':status') }}".replace(':status', status);
                    const fullUrl = page > 1 ? url + '?page=' + page : url;

                    fetch(fullUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        container.innerHTML = data.html;
                        paginationContainer.innerHTML = data.pagination;
                        
                        // Gắn lại event listeners cho pagination
                        attachPaginationListeners(status);
                    })
                    .catch(error => {
                        console.error('Lỗi khi tải đơn hàng:', error);
                        container.innerHTML = '<div class="text-center py-8 text-red-500">Có lỗi xảy ra khi tải dữ liệu</div>';
                    });
                }

                function attachPaginationListeners(status) {
                    const paginationContainer = document.getElementById(status + '-pagination');
                    const links = paginationContainer.querySelectorAll('a');
                    
                    links.forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            const url = new URL(this.href);
                            const page = url.searchParams.get('page') || 1;
                            loadOrders(status, page);
                        });
                    });
                }

                // Logic cho đánh giá (sử dụng event delegation)
                const reviewModal = document.getElementById('reviewModal');
                const reviewForm = document.getElementById('reviewForm');
                const orderIdInput = document.getElementById('modalOrderId');
                const shopIdInput = document.getElementById('modalShopId');
                const productIdInput = document.getElementById('modalProductId');
                const productNameEl = document.getElementById('modalProductName');
                const ratingInput = document.getElementById('modalRating');
                const stars = document.querySelectorAll('#modalStarRating i');

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

                // Logic cho trả hàng
                const refundModal = document.getElementById('refundModal');
                const refundForm = document.getElementById('refundForm');
                const orderIdInputRefund = document.getElementById('modalOrderIdRefund');

                document.getElementById('orderStatusTabsContent').addEventListener('click', function(e) {
                    if (e.target.classList.contains('open-refund-modal')) {
                        e.preventDefault();
                        orderIdInputRefund.value = e.target.dataset.orderId;
                        const actionUrl = "{{ route('user.order.refund', ':id') }}".replace(':id', e.target
                            .dataset.orderId);
                        refundForm.setAttribute('action', actionUrl);
                        refundModal.classList.remove('hidden');
                        refundModal.classList.add('flex');
                    }
                });

                document.querySelectorAll('#closeRefundModalBtn, #closeRefundModalBtnSubmit').forEach(button => {
                    button.addEventListener('click', function() {
                        refundModal.classList.add('hidden');
                        refundModal.classList.remove('flex');
                    });
                });

                // Logic cho xác nhận đã nhận
                document.getElementById('orderStatusTabsContent').addEventListener('click', function(e) {
                    if (e.target.classList.contains('confirm-received')) {
                        e.preventDefault();
                        if (confirm('Bạn có chắc chắn đã nhận được đơn hàng này?')) {
                            const orderId = e.target.dataset.orderId;
                            fetch(`/customer/user/order/${orderId}/confirm-received`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('Đã xác nhận nhận hàng thành công!');
                                    // Reload trang hoặc cập nhật UI
                                    location.reload();
                                } else {
                                    alert('Có lỗi xảy ra: ' + data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Lỗi:', error);
                                alert('Có lỗi xảy ra khi xác nhận nhận hàng');
                            });
                        }
                    }
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
@endsection
