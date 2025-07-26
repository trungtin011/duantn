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
        .place-order-btn {
            background-color:rgb(176, 9, 168);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .payment-card {
            transition: all 0.3s ease;
            border: 2px solidrgb(46, 114, 248);
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

        .selected-address {
            background:rgb(240, 227, 240);
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
            position: relative;
        }
        
        .selected-address::after {
            content: '';
            position: absolute;
            top: -6px;
            right: 20px;
            width: 12px;
            height: 12px;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            border-left: 1px solid #e5e7eb;
            transform: rotate(45deg);
        }
        
        .address-badge {
            position: absolute;
            top: -10px;
            right: 20px;
            background: #4f46e5;
            color: white;
            font-size: 12px;
            padding: 3px 8px;
            border-radius: 12px;
            z-index: 10;
        }
        
        .change-address-btn {
            position: absolute;
            bottom: 60px;
            right: 16px;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 4px 12px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .change-address-btn:hover {
            background: #e5e7eb;
        }
        
        .address-list {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease;
        }
        
        .address-list.expanded {
            max-height: 1000px;
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
                            
                            <!-- Hiển thị địa chỉ đã chọn -->
                            <div id="selected-address-container" class="mb-4 hidden">
                                <div class="selected-address relative">
                                    <span class="address-badge hidden">Mặc định</span>
                                    <div id="selected-address-content">
                                        <!-- Nội dung địa chỉ sẽ được điền bằng JS -->
                                    </div>
                                    <div class="change-address-btn" onclick="toggleAddressList()">
                                        <i class="fas fa-exchange-alt mr-1"></i> Thay đổi
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Danh sách địa chỉ -->
                            <div id="address-list" class="address-list">
                                <div class="space-y-3 mb-4">
                                    @foreach ($user_addresses as $address)
                                    <label class="address-card bg-white p-4 rounded-lg border border-gray-200 cursor-pointer hover:border-primary transition-colors flex items-start">
                                        <input type="radio" name="receiver_address_id" id="address{{ $address->id }}" class="mt-1 mr-3 address-radio" value="{{ $address->id }}" 
                                            @if($address->is_default) checked @endif
                                            data-address="{{ json_encode($address) }}">
                                        <div>
                                            @if($address->is_default)
                                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mb-1">Mặc định</span>
                                            @endif
                                            <p class="block font-medium">{{ $address->receiver_name }}</p>
                                            <p class="text-gray-600 text-sm">(+84) {{ $address->receiver_phone }}</p>
                                            <p class="text-gray-600">{{ $address->address }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}</p>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            <!-- Nút Thêm địa chỉ -->
                            <button id="showAddressForm" class="w-full text-center py-3 text-primary border-2 border-dashed border-primary rounded-lg hover:bg-primary hover:text-white transition-colors">
                                <i class="fas fa-plus mr-2"></i> Thêm địa chỉ mới
                            </button>
                        </div>
                        
                        <!-- Form thêm địa chỉ mới -->
                        <div id="createAddressForm" class="hidden bg-white p-5 rounded-xl border border-gray-200 mt-6 mb-8">
                            <h3 class="text-lg font-semibold mb-4">Thêm địa chỉ mới</h3>
                            <form class="space-y-4" action="{{ route('account.addresses.store') }}" method="post">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Tên Người Nhận <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"  name="receiver_name" class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Số điện thoại <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="receiver_phone" class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Loại Địa chỉ <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <button type="button" data-type="home" class="address-type-btn py-2 px-3 bg-gray-100 rounded-lg text-sm border border-gray-300 hover:border-primary">Nhà</button>
                                        <button type="button" data-type="office" class="address-type-btn py-2 px-3 bg-gray-100 rounded-lg text-sm border border-gray-300 hover:border-primary">Văn phòng</button>
                                        <button type="button" data-type="other" class="address-type-btn py-2 px-3 bg-gray-100 rounded-lg text-sm border border-gray-300 hover:border-primary">Khác</button>
                                    </div>
                                    <input type="hidden" name="address_type" id="address_type_input" value="">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="ghn-address-container">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Tỉnh/Thành phố <span class="text-red-500">*</span>
                                        </label>
                                        <select name="province_id" class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" id="city">
                                            <option value="">Chọn tỉnh/thành phố</option>
                                        </select>
                                        <input type="hidden" name="province" id="province_name">
                                        <div class="ghn-error-message hidden" id="city-error"></div>
                                    </div>
                                    <div class="ghn-address-container">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Quận/Huyện <span class="text-red-500">*</span>
                                        </label>
                                        <select name="district_id" class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" id="district" disabled>
                                            <option value="">Chọn quận/huyện</option>
                                        </select>
                                        <input type="hidden" name="district" id="district_name">
                                        <div class="ghn-error-message hidden" id="district-error"></div>
                                    </div>
                                    <div class="ghn-address-container">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Phường/Xã <span class="text-red-500">*</span>
                                        </label>
                                        <select name="ward_id" class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" id="ward" disabled>
                                            <option value="">Chọn phường/xã</option>
                                        </select>
                                        <input type="hidden" name="ward" id="ward_name">
                                        <div class="ghn-error-message hidden" id="ward-error"></div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Địa chỉ cụ thể <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="address" class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" name="is_default" id="default-address" class="h-4 w-4 text-primary focus:ring-primary">
                                    <label for="default-address" class="ml-2 text-sm text-gray-700">Đặt làm địa chỉ mặc định</label>
                                </div>

                                <div class="flex gap-3">
                                    <button type="button" class="flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors" id="cancelAddressForm">
                                        Hủy bỏ
                                    </button>
                                    <button type="submit" class="flex-1 bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-primary-dark transition-colors">
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
                                                <div class="text-sm text-gray-600 mb-1">
                                                    @if (isset($item['is_combo']) && $item['is_combo'] && isset($item['variant']))
                                                        {{ $item['variant']->variant_name ?? 'Không có biến thể' }}
                                                    @elseif ($item['product']->variants && $item['product']->variants->count() > 0)
                                                        {{ $item['product']->variants->first()->variant_name ?? 'Không có biến thể' }}
                                                    @else
                                                        Không có biến thể
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-600">Số lượng: x{{ $item['quantity'] }}</div>
                                            </div>
                                            @if (isset($item['is_combo']) && $item['is_combo'])
                                                <div class="text-xs text-primary bg-primary-soft px-2 py-1 rounded-md inline-block mb-1">
                                                    (Thuộc Combo: <strong>{{ $item['combo_info']['combo_name'] }}</strong>)
                                                </div>
                                            @endif
                                            <div class="text-right flex flex-row gap-2 mt-5">
                                                @if (isset($item['is_combo']) && $item['is_combo'] && isset($item['combo_info']))
                                                    @php
                                                        // Sử dụng giá đã được tính sẵn từ CheckoutController
                                                        $combo = $item['combo_info'];
                                                        $discountedPrice = $combo['price_in_combo'];
                                                        $originalPrice = $combo['original_price'];
                                                    @endphp
                                                    <div class="font-medium text-gray-800">{{ number_format($discountedPrice, 0, ',', '.') }}₫</div>
                                                    @if($discountedPrice < $originalPrice)
                                                        <div class="text-sm text-gray-500 line-through">{{ number_format($originalPrice, 0, ',', '.') }}₫</div>
                                                    @endif
                                                @else
                                                    @php
                                                        // Kiểm tra xem sản phẩm có variant hay không
                                                        if ($item['product']->variants && $item['product']->variants->count() > 0) {
                                                            $currentPrice = $item['product']->variants->first()->sale_price;
                                                            $originalPrice = $item['product']->variants->first()->price;
                                                        } else {
                                                            // Sản phẩm không có variant - sử dụng giá từ product
                                                            $currentPrice = $item['product']->sale_price ?? $item['product']->price;
                                                            $originalPrice = $item['product']->price;
                                                        }
                                                    @endphp
                                                    <div class="font-medium text-gray-800">{{ number_format($currentPrice, 0, ',', '.') }}₫</div>
                                                    @if($currentPrice < $originalPrice)
                                                        <div class="text-sm text-gray-500 line-through">{{ number_format($originalPrice, 0, ',', '.') }}₫</div>
                                                    @endif
                                                @endif
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
                    <div class="bg-gray-50 p-6 rounded-xl">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-star mr-2 text-yellow-500"></i>
                            Điểm tích luỹ của bạn
                            <span class="ml-10 flex items-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="toggle-points-btn" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-primary peer-checked:bg-primary transition-colors duration-300"></div>
                                    <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white border border-gray-300 rounded-full transition-transform duration-300 peer-checked:translate-x-5"></div>
                                </label>
                            </span>
                        </h2>  
                       
                        <div class="space-y-3">
                            <div class="flex justify-between text-gray-600">
                                <span>Điểm tích luỹ:</span>
                                <span id="user_points">{{ number_format($user_points, 0, ',', '.') }} điểm</span>
                            </div>
                            <div 
                                class="flex items-center gap-2 mt-2"
                                id="used-points-input-group"
                                style="display: none;"
                            >
                                <label for="used_points" class="text-gray-700 text-sm">Nhập số điểm muốn sử dụng:</label>
                                <input 
                                    type="number" 
                                    id="used_points" 
                                    name="used_points" 
                                    min="0" 
                                    max="{{ $user_points }}"
                                    step="1000"
                                    value="0"
                                    class="w-24 bg-gray-50 border border-gray-300 rounded-lg py-1 px-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right"
                                >
                                <span class="text-gray-500 text-sm">điểm</span>
                            </div>
                            <small class="text-dark-500">1 điểm = 1000đ</small>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const toggle = document.getElementById('toggle-points-btn');
                            const inputGroup = document.getElementById('used-points-input-group');
                            if (toggle && inputGroup) {
                                function updateInputVisibility() {
                                    inputGroup.style.display = toggle.checked ? 'flex' : 'none';
                                }
                                toggle.addEventListener('change', updateInputVisibility);
                                updateInputVisibility();
                            }
                        });
                    </script>
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
                                <span>Điểm tích luỹ:</span>
                                <span class="text-green-600" id="points_amount">{{ number_format(0, 0, ',', '.') }}₫</span>
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
                                <button type="submit" class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors">Áp dụng</button>
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
                            <input type="hidden" name="user_points" id="user_points">
                        </form>
                        
                        <!-- Nút đặt hàng -->
                        <button type="button" id="place-order-btn" class="w-full bg-black text-white py-3 px-6 rounded-lg text-lg font-semibold hover:bg-gray-800 transition-colors shadow-md hover:shadow-lg">
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
    <script>
        window.checkoutData = {
            addresses: @json($user_addresses),
            shops: @json($shops),
            subtotal: @json($subtotal),
            user_points: @json($user_points),
            csrfToken: '{{ csrf_token() }}',
            applyDiscountUrl: '{{ route("customer.apply-app-discount") }}',
            checkoutStoreUrl: '{{ route("checkout.store") }}'
        };
    </script>
    
    <!-- GHN Address Handler -->
    @vite(['resources/css/ghn-address.css', 'resources/js/checkout/ghn-address.js'])
    
    <!-- Main Checkout Script -->
    @vite(['resources/js/checkout/index.js'])
@endpush
