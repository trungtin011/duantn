@extends('layouts.app')
@section('title', 'Thanh toán')
@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fa;
        }
        
        .breadcrumb-item:not(:last-child)::after {
            content: '>';
            margin: 0 10px;
            color: #9CA3AF;
        }
        
        .payment-card {
            transition: all 0.3s ease;
            border: 2px solid #E5E7EB;
        }
        
        .payment-card:hover, .payment-card.selected {
            border-color: #4F46E5;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.1);
        }
        
        .product-card {
            transition: transform 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-3px);
        }
        
        .summary-card {
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        
        .discount-input:focus {
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }
        
        .expand-enter {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height 0.5s ease, opacity 0.5s ease;
        }
        
        .expand-enter-active {
            max-height: 1000px;
            opacity: 1;
        }
    </style>
@endpush
@section('content')
    <div class="container mx-auto px-4 py-8 max-w-12xl">
        <!-- Breadcrumb -->
        <div class="flex flex-wrap items-center gap-2 my-6 md:my-10 text-sm md:text-base text-gray-600">
            <a href="#" class="hover:text-primary transition-colors">Tài khoản</a>
            <span class="text-gray-300">/</span>
            <a href="#" class="hover:text-primary transition-colors">Tài khoản của tôi</a>
            <span class="text-gray-300">/</span>
            <a href="#" class="hover:text-primary transition-colors">Sản phẩm</a>
            <span class="text-gray-300">/</span>
            <a href="#" class="hover:text-primary transition-colors">Xem giỏ hàng</a>
            <span class="text-gray-300">/</span>
            <span class="text-primary font-medium">Thanh toán</span>
        </div>

        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg">
            <!-- Tiêu đề -->
            <div class="mb-8 text-center md:text-left">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Chi tiết hóa đơn</h1>
                <p class="text-gray-600">Kiểm tra thông tin đơn hàng và thanh toán</p>
            </div>
            
            <!-- Thông báo -->
            <div class="mb-8">
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4 hidden" id="error-notification">
                    <p>Mã giảm giá không hợp lệ. Vui lòng kiểm tra lại.</p>
                </div>
                
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4 hidden" id="success-notification">
                    <p>Áp dụng mã giảm giá thành công! Bạn được giảm 50,000 VND.</p>
                </div>
            </div>

            <!-- Main Container -->
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Thông tin người nhận -->
                <div class="w-full lg:w-1/2">
                    <div class="bg-gray-50 p-6 rounded-xl mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user-circle mr-2 text-primary"></i>
                            Thông tin người nhận
                        </h2>
                        
                        <!-- Chọn địa chỉ -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-3">Địa chỉ giao hàng</h3>
                            
                            <div class="space-y-3 mb-4">
                                @foreach ($user_addresses as $address)
                                <label class="address-card bg-white p-4 rounded-lg border border-gray-200 cursor-pointer hover:border-primary transition-colors flex items-start">
                                    <input type="radio" name="receiver_address_id" id="address2" class="mt-1 mr-3" value="{{ $address->id }}">
                                    <div>
                                        <p class="block font-medium">{{ $address->receiver_name }}</p>
                                        <p class="text-gray-600 text-sm">(+84) {{ $address->receiver_phone }}</p>
                                        <p class="text-gray-600">{{ $address->address }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            
                            <!-- Nút Thêm địa chỉ -->
                            <button id="showAddressForm" class="w-full text-center py-3 text-primary border-2 border-dashed border-primary rounded-lg hover:bg-primary hover:text-white transition-colors">
                                <i class="fas fa-plus mr-2"></i> Thêm địa chỉ mới
                            </button>
                        </div>
                        
                        <!-- Form thêm địa chỉ mới -->
                        <div id="createAddressForm" class="hidden bg-white p-5 rounded-xl border border-gray-200 mt-6 mb-8">
                            <h3 class="text-lg font-semibold mb-4">Thêm địa chỉ mới</h3>
                            <form class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Tên Người Nhận <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Số điện thoại <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Loại Địa chỉ <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <button type="button" class="address-type-btn py-2 px-3 bg-gray-100 rounded-lg text-sm border border-gray-300 hover:border-primary">Nhà</button>
                                        <button type="button" class="address-type-btn py-2 px-3 bg-gray-100 rounded-lg text-sm border border-gray-300 hover:border-primary">Văn phòng</button>
                                        <button type="button" class="address-type-btn py-2 px-3 bg-gray-100 rounded-lg text-sm border border-gray-300 hover:border-primary">Khác</button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Tỉnh/Thành phố <span class="text-red-500">*</span>
                                        </label>
                                        <select class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" id="city">
                                            <option value="">Chọn tỉnh/thành phố</option>
                                            <option>Hồ Chí Minh</option>
                                            <option>Hà Nội</option>
                                            <option>Đà Nẵng</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Quận/Huyện <span class="text-red-500">*</span>
                                        </label>
                                        <select class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" id="district">
                                            <option value="">Chọn quận/huyện</option>
                                            <option>Quận 1  </option>
                                            <option>Quận 3</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Phường/Xã <span class="text-red-500">*</span>
                                        </label>
                                        <select class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" id="ward">
                                            <option value="">Chọn phường/xã</option>
                                            <option>Phường Bến Nghé</option>
                                            <option>Phường Bến Thành</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Địa chỉ cụ thể <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="default-address" class="h-4 w-4 text-primary focus:ring-primary">
                                    <label for="default-address" class="ml-2 text-sm text-gray-700">Đặt làm địa chỉ mặc định</label>
                                </div>

                                <div class="flex gap-3">
                                    <button type="button" class="flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors" id="cancelAddressForm">
                                        Hủy bỏ
                                    </button>
                                    <button type="button" class="flex-1 bg-primary text-white py-2 px-4 rounded-lg hover:bg-primary-dark transition-colors">
                                        Thêm địa chỉ
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Phương thức thanh toán -->
                    <div class="bg-gray-50 p-6 rounded-xl">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-credit-card mr-2 text-secondary"></i>
                            Phương thức thanh toán
                        </h2>
                        
                        <div class="space-y-3">
                            <div class="payment-card bg-white p-4 rounded-lg cursor-pointer flex items-center selected">
                                <input type="radio" name="payment" value="MOMO" class="mr-3" >
                                <div class="flex-1">
                                    <div class="font-medium mb-1">Ví điện tử Momo</div>
                                    <p class="text-sm text-gray-600">Thanh toán nhanh qua ứng dụng Momo</p>
                                </div>
                                <img src="https://pay2s.vn/blog/wp-content/uploads/2024/11/momo_icon_circle_pinkbg_RGB-1024x1024.png" alt="Momo" class="w-10 h-10">
                            </div>
                            
                            <div class="payment-card bg-white p-4 rounded-lg cursor-pointer flex items-center">
                                <input type="radio" name="payment" value="VNPAY" class="mr-3">
                                <div class="flex-1">
                                    <div class="font-medium mb-1">Ví VNPay</div>
                                    <p class="text-sm text-gray-600">Thanh toán qua cổng VNPay</p>
                                </div>
                                <img src="https://vinadesign.vn/uploads/images/2023/05/vnpay-logo-vinadesign-25-12-57-55.jpg" alt="VNPay" class="w-10 h-10">
                            </div>
                            
                            <div class="payment-card bg-white p-4 rounded-lg cursor-pointer flex items-center">
                                <input type="radio" name="payment" value="COD" class="mr-3">
                                <div class="flex-1">
                                    <div class="font-medium mb-1">Thanh toán khi nhận hàng (COD)</div>
                                    <p class="text-sm text-gray-600">Thanh toán bằng tiền mặt khi nhận hàng</p>
                                </div>
                                <i class="fas fa-money-bill-wave text-2xl text-green-500"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danh sách sản phẩm và thanh toán -->
                <div class="w-full lg:w-1/2">
                    <!-- Danh sách sản phẩm -->
                    <div class="bg-gray-50 p-6 rounded-xl mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-shopping-cart mr-2 text-accent"></i>
                            Đơn hàng của bạn
                        </h2>
                        @php
                            // Nhóm sản phẩm theo shop
                            $groupedItems = [];
                            foreach ($items as $item) {
                                $shopId = $item['product']->shop->id;
                                if (!isset($groupedItems[$shopId])) {
                                    $groupedItems[$shopId] = [
                                        'shop' => $item['product']->shop,
                                        'items' => []
                                    ];
                                }
                                $groupedItems[$shopId]['items'][] = $item;
                            }
                        @endphp
                        
                        <div class="space-y-5">
                            @foreach ($groupedItems as $shopId => $shopData)
                            <!-- Shop {{ $shopData['shop']->name }} -->
                            <div class="bg-white rounded-xl overflow-hidden shadow-sm">
                                <div class="bg-gray-100 px-4 py-3 border-b">
                                    <h3 class="font-semibold text-gray-800  items-center">
                                        <i class="fas fa-store mr-2 text-gray-600"></i>
                                          {{$shopData['shop']->shop_name }} - Phí giao hàng : <span id="shipping-fee-shop-{{ $shopData['shop']->id }}">{{ number_format($shopData['shop']->shipping_fee, 0, ',', '.') }}₫</span>
                                    </h3>
                                </div>
                                
                                <div class="p-6 w-full">
                                    @foreach ($shopData['items'] as $index => $item)
                                    <!-- Sản phẩm {{ $index + 1 }} -->
                                    <div class="product-card  flex flex-row gap-2 items-center py-1 w-full {{ $index < count($shopData['items']) - 1 ? 'border-b border-gray-100' : '' }}" style="align-items: center;">
                                        <!-- Ảnh sản phẩm - chiếm 1/3 -->
                                        <div class="w-1/3 pr-4">
                                            <img
                                                src="{{ $item['product']->variants->first()->image ?? 'https://www.shutterstock.com/image-vector/no-photo-image-viewer-thumbnail-260nw-2495883211.jpg' }}"
                                                alt="{{ $item['product']->name }}"
                                                class="w-full object-contain rounded-lg"
                                            />                                       
                                        </div>
                                        
                                        <!-- Thông tin sản phẩm - chiếm 2/3 -->
                                        <div class="w-2/3">
                                            <div class="text-left">
                                                <div class="font-medium text-gray-800 mb-1">{{ $item['product']->name }}</div>
                                                <div class="text-sm text-gray-600 mb-1">{{ $item['product']->variants->first()->variant_name }}</div>
                                                <div class="text-sm text-gray-600">Số lượng: x{{ $item['quantity'] }}</div>
                                            </div>
                                            <div class="text-right flex flex-row gap-2 mt-5">
                                                <div class="font-medium text-gray-800">{{ number_format($item['product']->variants->first()->sale_price, 0, ',', '.') }}₫</div>
                                                <div class="text-sm text-gray-500 line-through">{{ number_format($item['product']->variants->first()->price, 0, ',', '.') }}₫</div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    
                                    <!-- Lời nhắn cho shop -->
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Lời nhắn cho Shop :  {{ $shopData['shop']->shop_name }}</label>
                                        <textarea 
                                            name="note_for_shop_{{ $shopData['shop']->id }}" 
                                            data-shop-id="{{ $shopData['shop']->id }}"
                                            class="w-full h-16 bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" 
                                            placeholder="Ví dụ: Gói hàng cẩn thận..."
                                        ></textarea>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Tóm tắt đơn hàng -->
                    <div class="summary-card p-6 mb-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-receipt mr-2 text-gray-600"></i>
                            Tóm tắt đơn hàng
                        </h2>
                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between text-gray-600">
                                <span>Tạm tính:</span>
                                <span id="subtotal">{{ number_format($subtotal, 0, ',', '.') }}₫</span>
                            </div>
                            
                            <div class="flex justify-between text-gray-600">
                                <span>Giảm giá:</span>
                                <span class="text-green-600" id="discount_amount">{{ number_format(0, 0, ',', '.') }}₫</span>
                            </div>
                            
                            <div class="flex justify-between text-gray-600">
                                <span>Phí vận chuyển:</span>
                                <span id="total_shipping_fee">
                                    {{ number_format(0, 0, ',', '.') }}₫
                                </span>
                                
                            </div>
                            
                            <div class="border-t border-gray-200 my-3 pt-3 flex justify-between text-lg font-bold">
                                <span>Tổng cộng:</span>
                                <span class="text-primary" id="total_amount">{{ number_format(0, 0, ',', '.') }}₫</span>
                            </div>
                            
                            <div class="flex justify-between text-sm text-gray-500 mt-2">
                                <span>Thời gian giao hàng dự kiến:</span>
                                <span class="font-medium">từ 2 đến 3 ngày</span>
                            </div>
                        </div>
                        
                        <!-- Mã giảm giá -->
                         
                        <form class="mb-6" id="discount-form">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mã giảm giá</label>
                            <div class="flex gap-2">
                                <input type="text" name="discount_code" placeholder="Nhập mã giảm giá" class="discount-input flex-1 bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900 transition-colors">Áp dụng</button>
                            </div>
                        </form>
                        
                        <!-- Form ẩn để lưu trữ dữ liệu -->
                        <form id="checkout-form" method="POST" action="{{ route('checkout.store') }}" style="display: none;">
                            @csrf
                            <input type="hidden" name="selected_address_id" id="address2">
                            <input type="hidden" name="payment_method" id="payment_method">
                            <input type="hidden" name="shop_notes" id="shop_notes">
                            <input type="hidden" name="shipping_fee" id="total_shipping_fee">
                            <input type="hidden" name="subtotal" id="subtotal">
                            <input type="hidden" name="discount_amount" id="discount_amount">
                            <input type="hidden" name="total_amount" id="total_amount">
                            <input type="hidden" name="discount_code" id="discount_code">
                        </form>
                        
                        <!-- Nút đặt hàng -->
                        <button type="button" id="place-order-btn" class="w-full bg-primary text-white py-3 px-6 rounded-lg text-lg font-semibold hover:bg-primary-dark transition-colors shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-transform">
                            <i class="fas fa-shopping-bag mr-2"></i> Đặt hàng ({{ number_format(0, 0, ',', '.') }}₫)
                        </button>
                        
                        <div class="text-center mt-4 text-sm text-gray-600">
                            <p>Bằng cách đặt hàng, bạn đồng ý với <a href="#" class="text-primary hover:underline">Điều khoản dịch vụ</a> của chúng tôi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Checkout script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let isSubmitting = false;
            
            document.getElementById('place-order-btn').addEventListener('click', function() {
                if (isSubmitting) return;
                
                if (validateForm()) {
                    collectAndSubmitData();
                }
            });

            function validateForm() {
                const selectedAddress = document.querySelector('input[name="receiver_address_id"]:checked');
                if (!selectedAddress) {
                    showError('Vui lòng chọn địa chỉ giao hàng');
                    return false;
                }

                const selectedPayment = document.querySelector('input[name="payment"]:checked');
                if (!selectedPayment) {
                    showError('Vui lòng chọn phương thức thanh toán');
                    return false;
                }
                return true;
            }

            function collectFormData() {
                const formData = {
                    // Địa chỉ giao hàng
                    selected_address_id: document.querySelector('input[name="receiver_address_id"]:checked')?.value,
                    
                    // Phương thức thanh toán
                    payment_method: document.querySelector('input[name="payment"]:checked')?.value,
                    
                    // Lời nhắn cho các shop
                    shop_notes: collectShopNotes(),
                    
                    // Thông tin đơn hàng
                    subtotal: document.getElementById('subtotal').textContent.replace(/[^\d]/g, '') || 0,
                    discount_amount: document.getElementById('discount_amount').textContent.replace(/[^\d]/g, '') || 0,
                    shipping_fee: document.getElementById('total_shipping_fee').textContent.replace(/[^\d]/g, '') || 0,
                    total_amount: document.getElementById('total_amount').textContent.replace(/[^\d]/g, '') || 0,
                    
                    discount_code: document.querySelector('input[name="discount_code"]')?.value || null,
                
                    _token: '{{ csrf_token() }}'
                };

                return formData;
            }

            // Thu thập lời nhắn cho các shop
            function collectShopNotes() {
                const notes = {};
                document.querySelectorAll('textarea[name^="note_for_shop"]').forEach(textarea => {
                    const shopId = textarea.getAttribute('data-shop-id');
                    const note = textarea.value.trim();
                    
                    if (note) {
                        notes[shopId] = note;
                    }
                });
                return notes;
            }

            // Lấy phí vận chuyển
            function getShippingFee() {
                const shippingFeeElement = document.getElementById('total_shipping_fee');
                if (shippingFeeElement) {
                    const feeText = shippingFeeElement.textContent;
                    const fee = parseInt(feeText.replace(/[^\d]/g, ''));
                    return isNaN(fee) ? 0 : fee;
                }
                return 0;
            }

            // Gửi dữ liệu đến backend
            function collectAndSubmitData() {
                isSubmitting = true;
                
                // Thay đổi trạng thái nút
                const submitBtn = document.getElementById('place-order-btn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';
                submitBtn.disabled = true;

                const formData = collectFormData();
                
                console.log('Dữ liệu gửi đi:', formData);

                // Gửi request đến backend
                fetch('{{ route("checkout.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Phản hồi từ server:', data);
                    if(data.success)
                    {
                        showSuccess('Đặt hàng thành công!');
                        window.location.href = data.redirectUrl;
                    }
                    else
                    {
                        showError('Có lỗi xảy ra khi đặt hàng');
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    showError('Có lỗi xảy ra khi kết nối đến server ' + error);
                })
                .catch(message => {
                    showError(message);
                })
                .finally(() => {
                    // Khôi phục trạng thái nút
                    isSubmitting = false;
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            }

            // Hiển thị thông báo lỗi
            function showError(message) {
                // Tạo toast notification
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                toast.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>${message}</span>
                    </div>
                `;
                document.body.appendChild(toast);
                
                // Tự động ẩn sau 5 giây
                setTimeout(() => {
                    toast.remove();
                }, 5000);
            }

            // Hiển thị thông báo thành công
            function showSuccess(message) {
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                toast.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>${message}</span>
                    </div>
                `;
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.remove();
                }, 5000);
            }

            const discountForm = document.getElementById('discount-form');
            if (discountForm) {
                discountForm.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    const codeInput = discountForm.querySelector('input[name="discount_code"]');
                    const discountCode = codeInput ? codeInput.value.trim() : '';
                    if (!discountCode) {
                        showError('Vui lòng nhập mã giảm giá');
                        return;
                    }
                    try {
                        const response = await fetch('{{ route("customer.apply-app-discount") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                discount_code: discountCode,
                                subtotal: @json($subtotal),
                            })
                        });
                        const data = await response.json();
                        console.log(data.discount_amount);
                        if(data.discount_amount)
                        {
                            document.getElementById('discount_amount').textContent = Number(data.discount_amount).toLocaleString('vi-VN');
                            showSuccess('Áp dụng mã giảm giá thành công!');
                            updateTotal();
                        }
                    } catch (error) {
                        console.error(error);
                        showError('Có lỗi xảy ra khi áp dụng mã giảm giá');
                    }
                });
            }
        });
    </script>
    <!-- Update total -->
    <script>
        function parseCurrency(str) {
            return parseFloat(str.replace(/[^\d]/g, '')) || 0;
        }

        function updateTotal($params) {
            const subtotal = parseCurrency(document.getElementById('subtotal')?.textContent ?? '0');
            const discount_amount = parseCurrency(document.getElementById('discount_amount')?.textContent ?? '0');
            const total_shipping_fee = parseCurrency(document.getElementById('total_shipping_fee')?.textContent ?? '0');

            const total = subtotal - discount_amount + total_shipping_fee;

            document.getElementById('total_amount').textContent = new Intl.NumberFormat('vi-VN').format(total) + '₫';
        }

        updateTotal();
    </script>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com">
    </script>
    <!-- Tailwind CSS config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#F1416C',
                        secondary: '#4F46E5',
                        accent: '#10B981',
                        light: '#F9FAFB',
                        dark: '#1F2937'
                    }
                }
            }
        }
    </script>
    <!-- Xử lý hiển thị form thêm địa chỉ -->
    <script>
        // Xử lý hiển thị form thêm địa chỉ
        document.getElementById('showAddressForm').addEventListener('click', function() {
            const form = document.getElementById('createAddressForm');
            form.classList.toggle('hidden');
            this.textContent = form.classList.contains('hidden') 
                ? ' Thêm địa chỉ mới' 
                : ' Hủy thêm địa chỉ';
        });

        // Xử lý nút hủy form địa chỉ
        document.getElementById('cancelAddressForm').addEventListener('click', function() {
            document.getElementById('createAddressForm').classList.add('hidden');
            document.getElementById('showAddressForm').textContent = ' Thêm địa chỉ mới';
        });

        // Xử lý chọn phương thức thanh toán
        const paymentCards = document.querySelectorAll('.payment-card');
        paymentCards.forEach(card => {
            card.addEventListener('click', function() {
                paymentCards.forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                const radio = this.querySelector('input[type="radio"]');
                if (radio) radio.checked = true;
            });
        });

        // Xử lý chọn loại địa chỉ
        const addressTypeBtns = document.querySelectorAll('.address-type-btn');
        addressTypeBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                addressTypeBtns.forEach(b => b.classList.remove('bg-primary', 'text-white'));
                this.classList.add('bg-primary', 'text-white');
            });
        });

        // Hiệu ứng cho product card
        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.05)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.boxShadow = 'none';
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addresses = @json($user_addresses);
            const shops = @json($shops);

            let isFetchingShippingFee = false;
            let currentRadio = null;
            let feeCache = {};

            // Hiện loading popup
            function showLoading(message = "Đang tính phí vận chuyển...") {
                const overlay = document.createElement('div');
                overlay.id = 'shipping-fee-overlay';
                overlay.style.position = 'fixed';
                overlay.style.top = '0';
                overlay.style.left = '0';
                overlay.style.width = '100vw';
                overlay.style.height = '100vh';
                overlay.style.backgroundColor = 'rgba(0,0,0,0.5)';
                overlay.style.display = 'flex';
                overlay.style.alignItems = 'center';
                overlay.style.justifyContent = 'center';
                overlay.style.zIndex = '9999';

                overlay.innerHTML = `
                    <div style="background: white; padding: 20px 30px; border-radius: 8px; font-size: 16px;">
                        ${message}
                    </div>
                `;
                document.body.appendChild(overlay);
            }

            // Ẩn loading popup
            function hideLoading() {
                const overlay = document.getElementById('shipping-fee-overlay');
                if (overlay) overlay.remove();
            }

            // Hiện confirm popup
            function showConfirmPopup(callback) {
                const overlay = document.createElement('div');
                overlay.id = 'confirm-popup-overlay';
                overlay.style.position = 'fixed';
                overlay.style.top = '0';
                overlay.style.left = '0';
                overlay.style.width = '100vw';
                overlay.style.height = '100vh';
                overlay.style.backgroundColor = 'rgba(0,0,0,0.5)';
                overlay.style.display = 'flex';
                overlay.style.alignItems = 'center';
                overlay.style.justifyContent = 'center';
                overlay.style.zIndex = '10000';

                overlay.innerHTML = `
                    <div style="background: white; padding: 25px; border-radius: 8px; width: 350px; max-width: 90%; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                        <h3 style="margin-top: 0; color: #333; font-size: 18px;">Xác nhận địa chỉ</h3>
                        <p style="margin-bottom: 20px; color: #666;">Phí vận chuyển sẽ được tính dựa trên địa chỉ nhận hàng</p>
                        <div style="display: flex; justify-content: flex-end; gap: 10px;">
                            <button id="confirm-cancel" style="padding: 8px 16px; background: #f0f0f0; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; color: #333;">Hủy</button>
                            <button id="confirm-ok" style="padding: 8px 16px; background: #4CAF50; border: none; border-radius: 4px; cursor: pointer; color: white;">Xác nhận</button>
                        </div>
                    </div>
                `;
                document.body.appendChild(overlay);

                document.getElementById('confirm-cancel').addEventListener('click', function () {
                    hideConfirmPopup();
                    callback(false);
                });

                document.getElementById('confirm-ok').addEventListener('click', function () {
                    hideConfirmPopup();
                    callback(true);
                });
            }

            function hideConfirmPopup() {
                const overlay = document.getElementById('confirm-popup-overlay');
                if (overlay) overlay.remove();
            }

            async function fetchShippingFee(addressId, shop) {
                try {
                    const response = await fetch('/calculate-shipping-fee', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            address_id: addressId,
                            shop_id: shop.id
                        })
                    });
                    const data = await response.json();
                    if (data && !data.error) {
                        return Number(data.shipping_fee) || 0;
                    } else {
                        console.error('Không thể tính phí vận chuyển cho shop', shop.id, data.error);
                        return 0;
                    }
                } catch (error) {
                    console.error('Lỗi:', error);
                    return 0;
                }
            }

            document.querySelectorAll('input[name="receiver_address_id"]').forEach(function (radio) {
                radio.addEventListener('change', async function () {
                    if (isFetchingShippingFee) return;

                    const selectedId = this.value;
                    currentRadio = this;
                    const address = addresses.find(addr => addr.id == selectedId);
                    if (!address) return;

                    isFetchingShippingFee = true;

                    // Tính phí trước
                    const feePromises = shops.map(shop => fetchShippingFee(address.id, shop));
                    const fees = await Promise.all(feePromises);

                    feeCache = {};
                    fees.forEach((fee, index) => {
                        feeCache[shops[index].id] = fee;
                    });

                    // Hiện popup xác nhận sau khi tính xong
                    showConfirmPopup(function (confirmed) {
                        if (!confirmed) {
                            currentRadio.checked = false;
                            isFetchingShippingFee = false;
                            return;
                        }

                        showLoading();

                        setTimeout(() => {
                            let total_shipping_fee = 0;
                            Object.entries(feeCache).forEach(([shopId, fee]) => {
                                total_shipping_fee += fee;
                                const el = document.getElementById('shipping-fee-shop-' + shopId);
                                if (el) el.textContent = fee.toLocaleString('vi-VN') + '₫';
                            });

                            document.getElementById('total_shipping_fee').textContent = total_shipping_fee.toLocaleString('vi-VN') + '₫';

                            if (typeof updateTotal === 'function') {
                                updateTotal();
                            }

                            hideLoading();
                            isFetchingShippingFee = false;
                        }, 300); // delay nhỏ cho mượt
                    });
                });
            });
        });
    </script>
    <!-- lấy danh sách Thành phố, Quận, Huyện từ GHN -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const citySelect = document.getElementById('city');
            const districtSelect = document.getElementById('district');
            const wardSelect = document.getElementById('ward');

            // Load danh sách tỉnh/thành phố khi trang load
            loadProvinces();

            // Xử lý khi chọn tỉnh/thành phố
            citySelect.addEventListener('change', function() {
                const provinceId = this.value;
                if (provinceId) {
                    loadDistricts(provinceId);
                    // Reset district và ward
                    districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                } else {
                    districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                }
            });

            // Xử lý khi chọn quận/huyện
            districtSelect.addEventListener('change', function() {
                const districtId = this.value;
                if (districtId) {
                    loadWards(districtId);
                    // Reset ward
                    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                } else {
                    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                }
            });

            // Load danh sách tỉnh/thành phố
            async function loadProvinces() {
                try {
                    citySelect.innerHTML = '<option value="">Đang tải...</option>';
                    
                    const response = await fetch('/api/address/provinces');
                    const result = await response.json();
                    
                    if (result.success && result.data) {
                        citySelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
                        result.data.forEach(province => {
                            const option = document.createElement('option');
                            option.value = province.ProvinceID;
                            option.textContent = province.ProvinceName;
                            citySelect.appendChild(option);
                        });
                    } else {
                        citySelect.innerHTML = '<option value="">Không thể tải danh sách tỉnh/thành phố</option>';
                    }
                } catch (error) {
                    console.error('Lỗi khi tải danh sách tỉnh/thành phố:', error);
                    citySelect.innerHTML = '<option value="">Lỗi khi tải dữ liệu</option>';
                }
            }

            // Load danh sách quận/huyện
            async function loadDistricts(provinceId) {
                try {
                    districtSelect.innerHTML = '<option value="">Đang tải...</option>';
                    
                    const response = await fetch(`/api/address/districts?province_id=${provinceId}`);
                    const result = await response.json();
                    
                    if (result.success && result.data) {
                        districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                        result.data.forEach(district => {
                            const option = document.createElement('option');
                            option.value = district.DistrictID;
                            option.textContent = district.DistrictName;
                            districtSelect.appendChild(option);
                        });
                    } else {
                        districtSelect.innerHTML = '<option value="">Không thể tải danh sách quận/huyện</option>';
                    }
                } catch (error) {
                    console.error('Lỗi khi tải danh sách quận/huyện:', error);
                    districtSelect.innerHTML = '<option value="">Lỗi khi tải dữ liệu</option>';
                }
            }

            // Load danh sách phường/xã
            async function loadWards(districtId) {
                try {
                    wardSelect.innerHTML = '<option value="">Đang tải...</option>';
                    
                    const response = await fetch(`/api/address/wards?district_id=${districtId}`);
                    const result = await response.json();
                    
                    if (result.success && result.data) {
                        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                        result.data.forEach(ward => {
                            const option = document.createElement('option');
                            option.value = ward.WardCode;
                            option.textContent = ward.WardName;
                            wardSelect.appendChild(option);
                        });
                    } else {
                        wardSelect.innerHTML = '<option value="">Không thể tải danh sách phường/xã</option>';
                    }
                } catch (error) {
                    console.error('Lỗi khi tải danh sách phường/xã:', error);
                    wardSelect.innerHTML = '<option value="">Lỗi khi tải dữ liệu</option>';
                }
            }
        });
    </script>
@endpush
