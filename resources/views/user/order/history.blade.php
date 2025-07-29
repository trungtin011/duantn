@extends('user.account.layout')

@section('account-content')
    @include('layouts.notification')
    <div class="container mx-auto bg-white">
        <!-- Main Navigation -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg px-6 py-4 mb-6 shadow-sm">
            <ul class="flex items-center space-x-6 overflow-x-auto">
                <li role="presentation">
                    <a href="{{ route('user.order.parent-order') }}" 
                       class="flex items-center px-4 py-2 text-blue-700 font-semibold hover:text-blue-900 hover:bg-blue-100 rounded-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Đơn hàng lớn
                    </a>
                </li>
            </ul>
        </div>

        <!-- Order Status Tabs -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8">
            <ul class="flex items-center justify-between px-6 py-4 overflow-x-auto space-x-1" 
                id="orderStatusTabs" role="tablist">
                <li role="presentation">
                    <button class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:bg-blue-50" 
                            id="pending-tab" data-target="#pending" type="button" role="tab" aria-controls="pending">
                        <span class="flex items-center">
                            <span class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></span>
                            Chờ xử lý
                        </span>
                    </button>
                </li>
                <li role="presentation">
                    <button class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:bg-blue-50" 
                            id="confirmed-tab" data-target="#confirmed" type="button" role="tab" aria-controls="confirmed">
                        <span class="flex items-center">
                            <span class="w-2 h-2 bg-blue-400 rounded-full mr-2"></span>
                            Đã nhận
                        </span>
                    </button>
                </li>
                <li role="presentation">
                    <button class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:bg-blue-50" 
                            id="shipped-tab" data-target="#shipped" type="button" role="tab" aria-controls="shipped">
                        <span class="flex items-center">
                            <span class="w-2 h-2 bg-purple-400 rounded-full mr-2"></span>
                            Đang giao đến
                        </span>
                    </button>
                </li>
                <li role="presentation">
                    <button class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:bg-blue-50" 
                            id="delivered-tab" data-target="#delivered" type="button" role="tab" aria-controls="delivered">
                        <span class="flex items-center">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                            Đã giao hàng
                        </span>
                    </button>
                </li>
                <li role="presentation">
                    <button class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:bg-blue-50" 
                            id="completed-tab" data-target="#completed" type="button" role="tab" aria-controls="completed">
                        <span class="flex items-center">
                            <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2"></span>
                            Hoàn thành
                        </span>
                    </button>
                </li>
                <li role="presentation">
                    <button class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:bg-blue-50" 
                            id="cancelled-tab" data-target="#cancelled" type="button" role="tab" aria-controls="cancelled">
                        <span class="flex items-center">
                            <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                            Đơn hủy
                        </span>
                    </button>
                </li>
                <li role="presentation">
                    <button class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:bg-blue-50" 
                            id="returned-tab" data-target="#returned" type="button" role="tab" aria-controls="returned">
                        <span class="flex items-center">
                            <span class="w-2 h-2 bg-orange-400 rounded-full mr-2"></span>
                            Trả hàng/Hoàn tiền
                        </span>
                    </button>
                </li>
            </ul>
        </div>

        <div class="tab-content h-full" id="orderStatusTabsContent">
            <!-- Tab Pane: Đang chờ xử lý -->
            <div class="tab-pane bg-white rounded-lg border border-gray-200 shadow-sm p-6" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                <div class="flex items-center mb-4">
                    <span class="w-3 h-3 bg-yellow-400 rounded-full mr-3"></span>
                    <h3 class="text-lg font-semibold text-gray-800">Đơn hàng chờ xử lý</h3>
                </div>
                <div id="pending-orders-container" class="space-y-4">
                    <!-- Nội dung sẽ được load bằng AJAX -->
                </div>
                <div id="pending-pagination" class="mt-6"></div>
            </div>

            <!-- Tab Pane: Đang xử lý -->
            <div class="tab-pane hidden bg-white rounded-lg border border-gray-200 shadow-sm p-6" id="confirmed" role="tabpanel" aria-labelledby="confirmed-tab">
                <div class="flex items-center mb-4">
                    <span class="w-3 h-3 bg-blue-400 rounded-full mr-3"></span>
                    <h3 class="text-lg font-semibold text-gray-800">Đơn hàng đã nhận</h3>
                </div>
                <div id="confirmed-orders-container" class="space-y-4">
                    <!-- Nội dung sẽ được load bằng AJAX -->
                </div>
                <div id="confirmed-pagination" class="mt-6"></div>
            </div>

            <!-- Tab Pane: Đang giao hàng -->
            <div class="tab-pane hidden bg-white rounded-lg border border-gray-200 shadow-sm p-6" id="shipped" role="tabpanel" aria-labelledby="shipped-tab">
                <div class="flex items-center mb-4">
                    <span class="w-3 h-3 bg-purple-400 rounded-full mr-3"></span>
                    <h3 class="text-lg font-semibold text-gray-800">Đơn hàng đang giao</h3>
                </div>
                <div id="shipped-orders-container" class="space-y-4">
                    <!-- Nội dung sẽ được load bằng AJAX -->
                </div>
                <div id="shipped-pagination" class="mt-6"></div>
            </div>

            <!-- Tab Pane: Hoàn thành -->
            <div class="tab-pane hidden bg-white rounded-lg border border-gray-200 shadow-sm p-6" id="delivered" role="tabpanel" aria-labelledby="delivered-tab">
                <div class="flex items-center mb-4">
                    <span class="w-3 h-3 bg-green-400 rounded-full mr-3"></span>
                    <h3 class="text-lg font-semibold text-gray-800">Đơn hàng đã giao</h3>
                </div>
                <div id="delivered-orders-container" class="space-y-4">
                    <!-- Nội dung sẽ được load bằng AJAX -->
                </div>
                <div id="delivered-pagination" class="mt-6"></div>
            </div>

            <!-- Tab Pane: Đã hoàn thành -->
            <div class="tab-pane hidden bg-white rounded-lg border border-gray-200 shadow-sm p-6" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                <div class="flex items-center mb-4">
                    <span class="w-3 h-3 bg-emerald-400 rounded-full mr-3"></span>
                    <h3 class="text-lg font-semibold text-gray-800">Đơn hàng hoàn thành</h3>
                </div>
                <div id="completed-orders-container" class="space-y-4">
                    <!-- Nội dung sẽ được load bằng AJAX -->
                </div>
                <div id="completed-pagination" class="mt-6"></div>
            </div>

            <!-- Tab Pane: Đã hủy -->
            <div class="tab-pane hidden bg-white rounded-lg border border-gray-200 shadow-sm p-6" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                <div class="flex items-center mb-4">
                    <span class="w-3 h-3 bg-red-400 rounded-full mr-3"></span>
                    <h3 class="text-lg font-semibold text-gray-800">Đơn hàng đã hủy</h3>
                </div>
                <div id="cancelled-orders-container" class="space-y-4">
                    <!-- Nội dung sẽ được load bằng AJAX -->
                </div>
                <div id="cancelled-pagination" class="mt-6"></div>
            </div>

            <!-- Tab Pane: Trả hàng/Hoàn tiền -->
            <div class="tab-pane hidden bg-white rounded-lg border border-gray-200 shadow-sm p-6" id="returned" role="tabpanel" aria-labelledby="returned-tab">
                <div class="flex items-center mb-4">
                    <span class="w-3 h-3 bg-orange-400 rounded-full mr-3"></span>
                    <h3 class="text-lg font-semibold text-gray-800">Đơn hàng trả/Hoàn tiền</h3>
                </div>
                <div id="returned-orders-container" class="space-y-4">
                    <!-- Nội dung sẽ được load bằng AJAX -->
                </div>
                <div id="returned-pagination" class="mt-6"></div>
            </div>
        </div>
    </div>

    <!-- Modal hủy đơn -->
    <div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-lg shadow-xl relative animate-fade-in-down">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Hủy đơn hàng</h2>
                <button id="closeCancelModalBtn" class="text-gray-400 hover:text-gray-600 text-2xl transition-colors duration-200">×</button>
            </div>

            <form id="cancelForm" method="POST" action="{{ route('user.order.cancel', ':id') }}" class="p-6">
                @csrf
                @method('PATCH')
                <input type="hidden" name="orderID" id="modalOrderIdCancel">

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Lý do hủy đơn:</label>
                    <textarea name="cancel_reason" 
                              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-300 focus:border-red-500 transition-all duration-200 resize-none" 
                              rows="4" 
                              placeholder="Nhập lý do hủy đơn..." 
                              required></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" id="closeCancelModalBtnSubmit" 
                            class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200 font-medium">
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

    <!-- Modal trả hàng -->
    <div id="refundModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-lg shadow-xl relative animate-fade-in-down">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Yêu cầu trả hàng</h2>
                <button id="closeRefundModalBtn" class="text-gray-400 hover:text-gray-600 text-2xl transition-colors duration-200">×</button>
            </div>

            <form id="refundForm" method="POST" action="{{ route('user.order.refund', ':id') }}" class="p-6">
                @csrf
                <input type="hidden" name="orderID" id="modalOrderIdRefund">

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Lý do trả hàng:</label>
                    <textarea name="refund_reason" 
                              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-300 focus:border-orange-500 transition-all duration-200 resize-none" 
                              rows="4" 
                              placeholder="Nhập lý do trả hàng..." 
                              required></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" id="closeRefundModalBtnSubmit" 
                            class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200 font-medium">
                        Hủy
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-200 font-medium">
                        Gửi yêu cầu
                    </button>
                </div>
            </form>
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
                    <img id="modalProductImage" src="" alt="Product Image" class="w-24 h-24 object-contain rounded-md mr-4 border p-1 bg-white">
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
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star-fill text-4xl text-gray-300 cursor-pointer mx-1 transition-colors duration-200 hover:text-yellow-400" data-value="{{ $i }}"></i>
                            @endfor
                            <span id="starRatingText" class="ml-3 text-lg font-semibold text-gray-600"></span>
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
                            <i class="bi bi-image mr-2"></i> Thêm Hình ảnh (<span id="imageCount">0</span>)
                        </label>
                        <input type="file" name="images[]" id="imagesUpload" accept="image/*" multiple class="hidden">

                        <label for="videoUpload" class="flex items-center px-4 py-3 border-2 border-red-500 text-red-500 rounded-lg cursor-pointer hover:bg-red-50 transition-colors duration-200 font-medium">
                            <i class="bi bi-camera-video mr-2"></i> Thêm Video (<span id="videoCount">0</span>)
                        </label>
                        <input type="file" name="video" id="videoUpload" accept="video/*" class="hidden">
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
                        console.error('Lỗi khi tải đơn hàng');
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

                // Logic cho trả hàng
                const refundModal = document.getElementById('refundModal');
                const refundForm = document.getElementById('refundForm');
                const orderIdInputRefund = document.getElementById('modalOrderIdRefund');

                document.getElementById('orderStatusTabsContent').addEventListener('click', function(e) {
                    if (e.target.classList.contains('open-refund-modal')) {
                        e.preventDefault();
                        orderIdInputRefund.value = e.target.dataset.orderId;
                        
                        // Cập nhật action URL cho form
                        // Kiểm tra URL đúng chưa
                        const actionUrl = "{{ route('user.order.refund', ':id') }}".replace(':id', e.target.dataset.orderId);
                        refundForm.setAttribute('action', actionUrl);
                        
                        refundForm.reset();
                        
                        refundModal.classList.remove('hidden');
                        refundModal.classList.add('flex');
                    }
                });

                // Xử lý submit form refund
                refundForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const orderId = orderIdInputRefund.value;
                    const refundReason = formData.get('refund_reason');
                    
                    // Validation
                    if (!refundReason || refundReason.trim().length < 10) {
                        showNotification('error', 'Vui lòng nhập lý do trả hàng (ít nhất 10 ký tự)');
                        return;
                    }
                    
                    // Hiển thị loading
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;
                    submitBtn.textContent = 'Đang xử lý...';
                    submitBtn.disabled = true;
                    
                    fetch(this.action, {
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
                            showNotification('success', 'Yêu cầu trả hàng đã được gửi thành công!');
                            refundModal.classList.add('hidden');
                            refundModal.classList.remove('flex');
                            
                            // Reload trang để cập nhật trạng thái
                            location.reload();
                        } else {
                            showNotification('error', 'Có lỗi xảy ra');
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi khi gửi yêu cầu trả hàng:', error);
                        showNotification('error', 'Có lỗi xảy ra khi gửi yêu cầu trả hàng');
                    })
                    .finally(() => {
                        // Khôi phục nút submit
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    });
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
                                    showNotification('success', 'Đã xác nhận nhận hàng thành công!');
                                    location.reload();
                                } else {
                                    showNotification('error', 'Có lỗi xảy ra: ');
                                }
                            })
                            .catch(error => {
                                showNotification('error', 'Có lỗi xảy ra khi xác nhận nhận hàng');
                            });
                        }
                    }
                });

                // Logic chọn sao đánh giá
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
