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

        #discount-modal {
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .discount-card {
            border-left: 4px solid #4F46E5;
            transition: all 0.2s ease;
        }

        .discount-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .breadcrumb-item:not(:last-child)::after {
            content: '>';
            margin: 0 10px;
            color: #9CA3AF;
        }

        .place-order-btn {
            background-color: rgb(176, 9, 168);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .payment-card {
            transition: all 0.3s ease;
            border: 2px solid rgb(46, 114, 248);
        }

        .payment-card:hover,
        .payment-card.selected {
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
            background: rgb(240, 227, 240);
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
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Breadcrumb -->

        @if (session('error'))
            <div class="mb-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative animate__animated animate__shakeX"
                    role="alert">
                    <strong class="font-bold">Lỗi!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
        @endif
        <nav class="flex flex-wrap items-center gap-2 md:my-10 text-sm md:text-base text-gray-600" style="margin:10px">
            <a href="#" class="hover:text-primary transition-colors">Tài khoản</a>
            <span class="text-gray-300">/</span>
            <a href="#" class="hover:text-primary transition-colors">Tài khoản của tôi</a>
            <span class="text-gray-300">/</span>
            <a href="#" class="hover:text-primary transition-colors">Sản phẩm</a>
            <span class="text-gray-300">/</span>
            <a href="#" class="hover:text-primary transition-colors">Xem giỏ hàng</a>
            <span class="text-gray-300">/</span>
            <span class="text-primary font-medium">Thanh toán</span>
        </nav>

        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg">
            <!-- Header -->
            <header class="mb-8 text-center md:text-left">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Chi tiết hóa đơn</h1>
                <p class="text-gray-600">Kiểm tra thông tin đơn hàng và thanh toán</p>
            </header>

            <!-- Main Content -->
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Left Column: Receiver Info & Payment Methods -->
                <div class="w-full lg:w-1/2 space-y-8">
                    <!-- Receiver Information -->
                    <section class="bg-gray-50 p-6 rounded-xl">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user-circle mr-2 text-primary"></i>
                            Thông tin người nhận
                        </h2>

                        <!-- Address Selection -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-3">Địa chỉ giao hàng</h3>

                            <!-- Selected Address -->
                            <div id="selected-address-container" class="mb-4 hidden">
                                <div class="selected-address relative">
                                    <span class="address-badge hidden">Mặc định</span>
                                    <div id="selected-address-content"></div>
                                    <button class="change-address-btn" onclick="toggleAddressList()">
                                        <i class="fas fa-exchange-alt mr-1"></i> Thay đổi
                                    </button>
                                </div>
                            </div>

                            <!-- Address List -->
                            <div id="address-list" class="address-list">
                                <div class="space-y-3 mb-4">
                                    @foreach ($user_addresses as $address)
                                        <label
                                            class="address-card bg-white p-4 rounded-lg border border-gray-200 cursor-pointer hover:border-primary transition-colors flex items-start">
                                            <input type="radio" name="receiver_address_id" id="address{{ $address->id }}"
                                                class="mt-1 mr-3 address-radio" value="{{ $address->id }}"
                                                @if ($address->is_default) checked @endif
                                                data-address="{{ json_encode($address) }}">
                                            <div>
                                                @if ($address->is_default)
                                                    <span
                                                        class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mb-1">Mặc
                                                        định</span>
                                                @endif
                                                <p class="font-medium">{{ $address->receiver_name }}</p>
                                                <p class="text-gray-600 text-sm">(+84) {{ $address->receiver_phone }}</p>
                                                <p class="text-gray-600">{{ $address->address }}, {{ $address->ward }},
                                                    {{ $address->district }}, {{ $address->province }}</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Add New Address Button -->
                            <button id="showAddressForm"
                                class="w-full text-center py-3 text-primary border-2 border-dashed border-primary rounded-lg hover:bg-primary hover:text-white transition-colors">
                                <i class="fas fa-plus mr-2"></i> Thêm địa chỉ mới
                            </button>
                        </div>

                        <!-- New Address Form -->
                        <div id="createAddressForm" class="hidden bg-white p-5 rounded-xl border border-gray-200 mt-6">
                            <h3 class="text-lg font-semibold mb-4">Thêm địa chỉ mới</h3>
                            <form class="space-y-4" action="{{ route('account.addresses.store') }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Tên Người Nhận <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="receiver_name"
                                            class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Số điện thoại <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="receiver_phone"
                                            class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Loại Địa chỉ <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <button type="button" data-type="home"
                                            class="address-type-btn py-2 px-3 bg-gray-100 rounded-lg text-sm border border-gray-300 hover:border-primary">Nhà</button>
                                        <button type="button" data-type="office"
                                            class="address-type-btn py-2 px-3 bg-gray-100 rounded-lg text-sm border border-gray-300 hover:border-primary">Văn
                                            phòng</button>
                                        <button type="button" data-type="other"
                                            class="address-type-btn py-2 px-3 bg-gray-100 rounded-lg text-sm border border-gray-300 hover:border-primary">Khác</button>
                                    </div>
                                    <input type="hidden" name="address_type" id="address_type_input">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="ghn-address-container">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Tỉnh/Thành phố <span class="text-red-500">*</span>
                                        </label>
                                        <select name="province_id"
                                            class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary"
                                            id="city">
                                            <option value="">Chọn tỉnh/thành phố</option>
                                        </select>
                                        <input type="hidden" name="province" id="province_name">
                                        <div class="ghn-error-message hidden" id="city-error"></div>
                                    </div>
                                    <div class="ghn-address-container">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Quận/Huyện <span class="text-red-500">*</span>
                                        </label>
                                        <select name="district_id"
                                            class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary"
                                            id="district" disabled>
                                            <option value="">Chọn quận/huyện</option>
                                        </select>
                                        <input type="hidden" name="district" id="district_name">
                                        <div class="ghn-error-message hidden" id="district-error"></div>
                                    </div>
                                    <div class="ghn-address-container">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Phường/Xã <span class="text-red-500">*</span>
                                        </label>
                                        <select name="ward_id"
                                            class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary"
                                            id="ward" disabled>
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
                                    <input type="text" name="address"
                                        class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" name="is_default" id="default-address"
                                        class="h-4 w-4 text-primary focus:ring-primary">
                                    <label for="default-address" class="ml-2 text-sm text-gray-700">Đặt làm địa chỉ mặc
                                        định</label>
                                </div>

                                <div class="flex gap-3">
                                    <button type="button"
                                        class="flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors"
                                        id="cancelAddressForm">Hủy bỏ</button>
                                    <button type="submit"
                                        class="flex-1 bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-primary-dark transition-colors">Thêm
                                        địa chỉ</button>
                                </div>
                            </form>
                        </div>
                    </section>

                    <!-- Payment Methods -->
                    <section class="bg-gray-50 p-6 rounded-xl">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-credit-card mr-2 text-secondary"></i>
                            Phương thức thanh toán
                        </h2>

                        <div class="space-y-3">
                            <label class="payment-card bg-white p-4 rounded-lg cursor-pointer flex items-center selected">
                                <input type="radio" name="payment" value="MOMO" class="mr-3" checked>
                                <div class="flex-1">
                                    <div class="font-medium mb-1">Ví điện tử Momo</div>
                                    <p class="text-sm text-gray-600">Thanh toán nhanh qua ứng dụng Momo</p>
                                </div>
                                <img src="https://pay2s.vn/blog/wp-content/uploads/2024/11/momo_icon_circle_pinkbg_RGB-1024x1024.png"
                                    alt="Momo" class="w-10 h-10">
                            </label>

                            <label class="payment-card bg-white p-4 rounded-lg cursor-pointer flex items-center">
                                <input type="radio" name="payment" value="VNPAY" class="mr-3">
                                <div class="flex-1">
                                    <div class="font-medium mb-1">Ví VNPay</div>
                                    <p class="text-sm text-gray-600">Thanh toán qua cổng VNPay</p>
                                </div>
                                <img src="https://vinadesign.vn/uploads/images/2023/05/vnpay-logo-vinadesign-25-12-57-55.jpg"
                                    alt="VNPay" class="w-10 h-10">
                            </label>

                            <label class="payment-card bg-white p-4 rounded-lg cursor-pointer flex items-center">
                                <input type="radio" name="payment" value="COD" class="mr-3">
                                <div class="flex-1">
                                    <div class="font-medium mb-1">Thanh toán khi nhận hàng (COD)</div>
                                    <p class="text-sm text-gray-600">Thanh toán bằng tiền mặt khi nhận hàng</p>
                                </div>
                                <i class="fas fa-money-bill-wave text-2xl text-green-500"></i>
                            </label>
                        </div>
                    </section>
                </div>

                <!-- Right Column: Order Details & Summary -->
                <div class="w-full lg:w-1/2 space-y-8">
                    <!-- Order Items -->
                    <section class="bg-gray-50 p-6 rounded-xl">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-shopping-cart mr-2 text-accent"></i>
                            Đơn hàng của bạn
                        </h2>

                        @php
                            $groupedItems = [];
                            foreach ($items as $item) {
                                $shopId = $item['product']->shop->id;
                                if (!isset($groupedItems[$shopId])) {
                                    $groupedItems[$shopId] = [
                                        'shop' => $item['product']->shop,
                                        'items' => [],
                                    ];
                                }
                                $groupedItems[$shopId]['items'][] = $item;
                            }
                        @endphp

                        <div class="space-y-5">
                            @foreach ($groupedItems as $shopId => $shopData)
                                <div class="bg-white rounded-xl overflow-hidden shadow-sm">
                                    <header class="bg-gray-300 px-4 py-3 border-b">
                                        <h3 class="font-semibold text-gray-800 flex items-center">
                                            <i class="fas fa-store mr-2 text-gray-600"></i>
                                            {{ $shopData['shop']->shop_name }}
                                        </h3>
                                    </header>

                                    <div class="w-full flex flex-col px-4 py-2 bg-gray-100 border-t">
                                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                                            <div class="space-y-1 mb-3 md:mb-0">
                                                <div class="flex items-center">
                                                    <span class="text-gray-600 text-sm mr-2">Tổng giá sản phẩm:</span>
                                                    <span class="font-semibold text-gray-800 text-base"
                                                        id="total-product-price-shop-{{ $shopData['shop']->id }}">
                                                        {{ number_format(
                                                            collect($shopData['items'])->sum(function ($item) {
                                                                if (isset($item['is_combo']) && $item['is_combo'] && isset($item['combo_info'])) {
                                                                    return $item['combo_info']['price_in_combo'] * $item['quantity'];
                                                                }
                                                                if (!$item['product']->is_variant) {
                                                                    $price = $item['product']->sale_price ?? $item['product']->price;
                                                                    return $price * $item['quantity'];
                                                                }
                                                                if (isset($item['variant']) && $item['variant']) {
                                                                    $price = $item['variant']->sale_price ?? $item['variant']->price;
                                                                    return $price * $item['quantity'];
                                                                }
                                                                return 0;
                                                            }),
                                                            0,
                                                            ',',
                                                            '.',
                                                        ) }}₫
                                                    </span>
                                                </div>
                                                <div class="flex items-center">
                                                    <span class="text-gray-600 text-sm mr-2">Phí giao hàng:</span>
                                                    <span class="font-semibold text-gray-800 text-base"
                                                        id="shipping-fee-shop-{{ $shopData['shop']->id }}">
                                                        {{ number_format($shopData['shop']->shipping_fee, 0, ',', '.') }}₫
                                                    </span>
                                                </div>
                                                <div class="flex items-center hidden"
                                                    id="discount-context-shop-{{ $shopData['shop']->id }}">
                                                    <span class="text-gray-600 text-sm mr-2">Giảm giá:</span>
                                                    <span class="font-semibold text-green-500 text-base"
                                                        id="discount-amount-shop-{{ $shopData['shop']->id }}"></span>
                                                </div>
                                            </div>
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-px h-10 bg-black rounded"></div>
                                            </div>
                                            <button type="button"
                                                class="bg-gray-400 text-white px-4 py-1 rounded-md hover:bg-accent-dark transition whitespace-nowrap hidden"
                                                id="cancel-coupon-btn-shop-{{ $shopData['shop']->id }}"
                                                data-shop-id="{{ $shopData['shop']->id }}">
                                                Hủy mã
                                            </button>
                                            <button type="button"
                                                class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center space-x-2"
                                                onclick="showDiscountsModal({{ $shopData['shop']->id }})">
                                                <i class="fas fa-tag text-sm"></i>
                                                <span class="font-medium">Mã giảm giá</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="p-6 w-full">
                                        @foreach ($shopData['items'] as $index => $item)
                                            @php
                                                $imagePath =
                                                    optional($item['product']->images->first())->image_path ??
                                                    'product_images/default.png';
                                            @endphp
                                            <div
                                                class="product-card flex p-5 flex-row gap-2 items-center py-1 {{ $index < count($shopData['items']) - 1 ? 'border-b border-gray-200' : '' }}">
                                                <div class="aspect-square w-24 h-24 pr-2 flex-shrink-0">
                                                    <img src="{{ asset('storage/' . $imagePath) }}"
                                                        alt="{{ $item['product']->name }}"
                                                        class="w-full h-full object-contain rounded-lg"
                                                        style="aspect-ratio: 1 / 1;">
                                                </div>
                                                <div class="w-2/3">
                                                    <div class="text-left">
                                                        <div class="font-medium text-gray-800 mb-1">
                                                            {{ $item['product']->name }}</div>
                                                        @if (
                                                            (isset($item['is_combo']) && $item['is_combo'] && isset($item['variant']) && $item['variant']) ||
                                                                ($item['product']->variants && $item['product']->variants->count() > 0))
                                                            <div class="text-sm text-gray-600 mb-1">
                                                                @if (isset($item['is_combo']) && $item['is_combo'] && isset($item['variant']))
                                                                    {{ $item['variant']->variant_name ?? '' }}
                                                                @elseif ($item['product']->variants && $item['product']->variants->count() > 0)
                                                                    {{ $item['product']->variants->first()->variant_name ?? '' }}
                                                                @endif
                                                            </div>
                                                        @endif
                                                        <div class="text-sm text-gray-600">Số lượng:
                                                            x{{ $item['quantity'] }}</div>
                                                    </div>
                                                    @if (isset($item['is_combo']) && $item['is_combo'])
                                                        <div
                                                            class="text-xs text-primary bg-primary-soft px-2 py-1 rounded-md inline-block mb-1">
                                                            (Thuộc Combo:
                                                            <strong>{{ $item['combo_info']['combo_name'] }}</strong>)
                                                        </div>
                                                    @endif
                                                    @php
                                                        if (
                                                            isset($item['is_combo']) &&
                                                            $item['is_combo'] &&
                                                            isset($item['combo_info'])
                                                        ) {
                                                            $currentPrice = $item['combo_info']['price_in_combo'];
                                                            $originalPrice = $item['combo_info']['original_price'];
                                                        } elseif (
                                                            $item['product']->variants &&
                                                            $item['product']->variants->count() > 0
                                                        ) {
                                                            $currentPrice = $item['product']->variants->first()
                                                                ->sale_price;
                                                            $originalPrice = $item['product']->variants->first()->price;
                                                        } else {
                                                            $currentPrice =
                                                                $item['product']->sale_price ?? $item['product']->price;
                                                            $originalPrice = $item['product']->price;
                                                        }
                                                        $totalPrice = $currentPrice * $item['quantity'];
                                                    @endphp
                                                    <div class="flex flex-row justify-between items-end mt-5 gap-2">
                                                        <div class="flex flex-col items-start gap-1 min-w-[90px]">
                                                            <div class="font-medium text-gray-800">
                                                                {{ number_format($currentPrice, 0, ',', '.') }}₫</div>
                                                            @if ($currentPrice < $originalPrice)
                                                                <div class="text-sm text-gray-500 line-through">
                                                                    {{ number_format($originalPrice, 0, ',', '.') }}₫</div>
                                                            @endif
                                                        </div>
                                                        <div class="text-xs text-green-600 text-right min-w-[110px] mr-10">
                                                            Tổng:
                                                            <strong>{{ number_format($totalPrice, 0, ',', '.') }}₫</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        <!-- Note for Shop -->
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Lời nhắn cho Shop:
                                                {{ $shopData['shop']->shop_name }}</label>
                                            <textarea name="note_for_shop_{{ $shopData['shop']->id }}" data-shop-id="{{ $shopData['shop']->id }}"
                                                class="w-full h-16 bg-gray-50 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary"
                                                placeholder="Ví dụ: Gói hàng cẩn thận..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Shop Discounts Modal -->
                                <div id="discounts-modal-{{ $shopData['shop']->id }}"
                                    class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                                    <div class="relative top-10 mx-auto p-0 border-0 w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 shadow-2xl rounded-2xl bg-white overflow-hidden">
                                        <!-- Header -->
                                        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-store text-2xl"></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="text-2xl font-bold">Mã giảm giá của Shop</h3>
                                                        <p class="text-white text-opacity-90">{{ $shopData['shop']->shop_name }}</p>
                                                    </div>
                                                </div>
                                                <button onclick="closeDiscountsModal({{ $shopData['shop']->id }})"
                                                    class="w-10 h-10 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center transition-all duration-200">
                                                    <i class="fas fa-times text-xl"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <div class="p-6">
                                            @if ($shop_coupons[$shopData['shop']->id] && $shop_coupons[$shopData['shop']->id]->count() > 0)
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto">
                                                    @foreach ($shop_coupons[$shopData['shop']->id] as $coupon)
                                                        <div class="coupon-card bg-white border-2 border-dashed border-gray-200 rounded-xl p-4 hover:border-green-500 hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
                                                            <!-- Coupon Type Badge -->
                                                            <div class="absolute top-2 right-2">
                                                                @if($coupon->type_coupon == 'shipping')
                                                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                                        <i class="fas fa-truck mr-1"></i>Vận chuyển
                                                                    </span>
                                                                @elseif($coupon->type_coupon == 'order')
                                                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                                        <i class="fas fa-shopping-cart mr-1"></i>Đơn hàng
                                                                    </span>
                                                                @elseif($coupon->type_coupon == 'first_order')
                                                                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                                        <i class="fas fa-star mr-1"></i>Đơn đầu
                                                                    </span>
                                                                @else
                                                                    <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                                        <i class="fas fa-tag mr-1"></i>Khác
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            <!-- Coupon Code -->
                                                            <div class="text-center mb-4">
                                                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-3 mb-3">
                                                                    <h4 class="font-mono font-bold text-xl text-gray-800 tracking-wider">{{ $coupon->code }}</h4>
                                                                </div>
                                                            </div>

                                                            <!-- Coupon Details -->
                                                            <div class="space-y-2 mb-4">
                                                                <p class="text-gray-700 text-sm leading-relaxed">{{ $coupon->description }}</p>
                                                                
                                                                <!-- Discount Amount -->
                                                                <div class="flex items-center justify-center space-x-2">
                                                                    <span class="text-gray-500 text-sm">Giảm:</span>
                                                                    @if ($coupon->discount_type == 'percentage')
                                                                        <span class="text-2xl font-bold text-green-600">{{ $coupon->discount_value }}%</span>
                                                                    @else
                                                                        <span class="text-2xl font-bold text-green-600">{{ number_format($coupon->discount_value, 0, ',', '.') }}₫</span>
                                                                    @endif
                                                                </div>

                                                                <!-- Expiry Date -->
                                                                <div class="flex items-center justify-center space-x-2 text-xs text-gray-500">
                                                                    <i class="fas fa-clock"></i>
                                                                    <span>HSD: {{ $coupon->end_date->format('d/m/Y') }}</span>
                                                                </div>
                                                            </div>

                                                            <!-- Use Button -->
                                                            <button type="button"
                                                                class="use-shop-coupon-btn w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-green-600 hover:to-green-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl"
                                                                data-shop-id="{{ $shopData['shop']->id }}"
                                                                data-coupon-code="{{ $coupon->code }}">
                                                                <i class="fas fa-check mr-2"></i>Sử dụng ngay
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-center py-12">
                                                    <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-store text-4xl text-gray-400"></i>
                                                    </div>
                                                    <h4 class="text-xl font-semibold text-gray-600 mb-2">Không có mã giảm giá</h4>
                                                    <p class="text-gray-500">Shop này hiện không có mã giảm giá nào khả dụng.</p>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Footer -->
                                        <div class="bg-gray-50 px-6 py-4 border-t">
                                            <div class="flex justify-between items-center">
                                                <div class="text-sm text-gray-600">
                                                    <i class="fas fa-info-circle mr-2"></i>
                                                    Mã giảm giá sẽ được áp dụng tự động khi bạn nhấn "Sử dụng ngay"
                                                </div>
                                                <div class="flex space-x-3">
                                                    <button onclick="closeDiscountsModal({{ $shopData['shop']->id }})"
                                                        class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200">
                                                        Đóng
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                    <!-- Points -->
                    <section class="bg-gray-50 p-6 rounded-xl">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-star mr-2 text-gray-600"></i>
                            Đổi điểm
                            <span class="ml-10 flex items-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="toggle-points-btn" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-primary peer-checked:bg-primary transition-colors duration-300">
                                    </div>
                                    <div
                                        class="absolute left-0.5 top-0.5 w-5 h-5 bg-white border border-gray-300 rounded-full transition-transform duration-300 peer-checked:translate-x-5">
                                    </div>
                                </label>
                            </span>
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between text-gray-600">
                                <span>Điểm tích lũy:</span>
                                <span id="user_points">{{ number_format($user_points, 0, ',', '.') }} điểm</span>
                            </div>
                            <div class="flex items-center gap-2 mt-2 hidden" id="used-points-input-group">
                                <label for="used_points" class="text-gray-700 text-sm">Nhập số điểm muốn sử dụng:</label>
                                <input type="number" id="used_points" name="used_points" min="0"
                                    max="{{ $user_points }}" step="100" value="0"
                                    class="w-24 bg-gray-50 border border-gray-300 rounded-lg py-1 px-2 focus:outline-none focus:ring-2 focus:ring-primary text-right">
                                <span class="text-gray-500 text-sm">điểm</span>
                            </div>
                        </div>
                    </section>
                    <!-- Order Summary -->
                    <section class="summary-card p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-receipt mr-2 text-gray-600"></i>
                            Tóm tắt đơn hàng
                        </h2>

                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between text-gray-600">
                                <span>Tạm tính:</span>
                                <span id="subtotal">{{ number_format(0, 0, ',', '.') }}₫</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Phí vận chuyển:</span>
                                <span id="total_shipping_fee">{{ number_format(0, 0, ',', '.') }}₫</span>
                            </div>
                            <div class="flex justify-between text-gray-600 discount-row-platform" style="display:none">
                                <span>Giảm giá từ sàn:</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-green-600"
                                        id="discount_amount">{{ number_format(0, 0, ',', '.') }}₫</span>
                                    <button type="button" id="cancel-platform-coupon-btn" 
                                        class="hidden text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 transition-colors"
                                        onclick="removePlatformCoupon()">
                                        Hủy
                                    </button>
                                </div>
                            </div>
                            <div class="flex justify-between text-gray-600 discount-row-points">
                                <span>Đổi điểm:</span>
                                <span class="text-green-600"
                                    id="points_amount">{{ number_format(0, 0, ',', '.') }}₫</span>
                            </div>
                            <div class="flex justify-between text-gray-600 discount-row-shop" style="display:none">
                                <span>Giảm giá từ Shop:</span>
                                <span class="text-green-600"
                                    id="discount_shop_fee">{{ number_format(0, 0, ',', '.') }}₫</span>
                            </div>
                            <div class="flex justify-between text-gray-600 discount-row-shipping" style="display:none">
                                <span>Giảm giá vận chuyển:</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-green-600"
                                        id="discount_shipping_fee">{{ number_format(0, 0, ',', '.') }}₫</span>
                                    <button type="button" id="cancel-shipping-coupon-btn" 
                                        class="hidden text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 transition-colors"
                                        onclick="removeShippingCoupon()">
                                        Hủy
                                    </button>
                                </div>
                            </div>
                            <div class="border-t border-gray-200 my-3 pt-3 flex justify-between text-lg font-bold">
                                <span>Tổng cộng:</span>
                                <span class="text-primary" id="total_amount">{{ number_format(0, 0, ',', '.') }}₫</span>
                            </div>

                        </div>

                        <!-- Discount Code -->
                        <div class="mb-6 flex justify-end">
                            <button type="button"
                                class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center space-x-2"
                                id="show-platform-discounts-btn">
                                <i class="fas fa-gift text-lg"></i>
                                <span class="font-semibold">Mã giảm giá</span>
                            </button>
                        </div>

                        <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>

                        <!-- Hidden Checkout Form -->
                        <form id="checkout-form" class="hidden">
                            @csrf
                            <input type="hidden" name="selected_address_id" id="address2">
                            <input type="hidden" name="payment_method" id="payment_method">
                            <input type="hidden" name="shop_notes" id="shop_notes">
                            <input type="hidden" name="shipping_fee" id="total_shipping_fee">
                            <input type="hidden" name="subtotal" id="subtotal">
                            <input type="hidden" name="discount_amount" id="discount_amount">
                            <input type="hidden" name="total_amount" id="total_amount">
                            <input type="hidden" name="user_points" id="user_points">
                            <input type="hidden" name="recaptcha_token" id="recaptcha_token">
                            @foreach ($groupedItems as $shopId => $shopData)
                                <input type="hidden" name="shop_discount_code[{{ $shopId }}]"
                                    id="hidden_shop_discount_code_{{ $shopId }}">
                            @endforeach
                        </form>

                        <!-- Place Order Button -->
                        <button type="button" id="place-order-btn"
                            class="w-full bg-primary text-dark py-3 px-6 rounded-lg text-lg font-semibold hover:bg-primary-dark transition-colors shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-shopping-bag mr-2"></i> Đặt hàng ({{ number_format(0, 0, ',', '.') }}₫)
                        </button>

                        <div class="text-center mt-4 text-sm text-gray-600">
                            <p>Bằng cách đặt hàng, bạn đồng ý với <a href="#"
                                    class="text-primary hover:underline">Điều khoản dịch vụ</a> của chúng tôi</p>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <div id="platform-discount-modal"
            class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-10 mx-auto p-0 border-0 w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 shadow-2xl rounded-2xl bg-white overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                <i class="fas fa-gift text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold">Mã giảm giá của sàn</h3>
                                <p class="text-white text-opacity-90">Chọn mã giảm giá phù hợp với đơn hàng của bạn</p>
                            </div>
                        </div>
                        <button id="close-platform-discount-modal"
                            class="w-10 h-10 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center transition-all duration-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    @if ($public_coupons)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto">
                            @foreach ($public_coupons as $item)
                                <div class="coupon-card bg-white border-2 border-dashed border-gray-200 rounded-xl p-4 hover:border-blue-500 hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
                                    <!-- Coupon Type Badge -->
                                    <div class="absolute top-2 right-2">
                                        @if($item->type_coupon == 'shipping')
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                <i class="fas fa-truck mr-1"></i>Vận chuyển
                                            </span>
                                        @elseif($item->type_coupon == 'order')
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                <i class="fas fa-shopping-cart mr-1"></i>Đơn hàng
                                            </span>
                                        @elseif($item->type_coupon == 'first_order')
                                            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                <i class="fas fa-star mr-1"></i>Đơn đầu
                                            </span>
                                        @else
                                            <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                <i class="fas fa-tag mr-1"></i>Khác
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Coupon Code -->
                                    <div class="text-center mb-4">
                                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-3 mb-3">
                                            <h4 class="font-mono font-bold text-xl text-gray-800 tracking-wider">{{ $item->code }}</h4>
                                        </div>
                                    </div>

                                    <!-- Coupon Details -->
                                    <div class="space-y-2 mb-4">
                                        <p class="text-gray-700 text-sm leading-relaxed">{{ $item->description }}</p>
                                        
                                        <!-- Discount Amount -->
                                        <div class="flex items-center justify-center space-x-2">
                                            <span class="text-gray-500 text-sm">Giảm:</span>
                                            @if ($item->discount_type == 'percentage')
                                                <span class="text-2xl font-bold text-primary">{{ $item->discount_value }}%</span>
                                            @else
                                                <span class="text-2xl font-bold text-primary">{{ number_format($item->discount_value, 0, ',', '.') }}₫</span>
                                            @endif
                                        </div>

                                        <!-- Expiry Date -->
                                        <div class="flex items-center justify-center space-x-2 text-xs text-gray-500">
                                            <i class="fas fa-clock"></i>
                                            <span>HSD: {{ $item->end_date->format('d/m/Y') }}</span>
                                        </div>
                                    </div>

                                    <!-- Use Button -->
                                    <button type="button"
                                        class="use-platform-coupon-btn w-full bg-gradient-to-r from-primary to-primary-dark text-white py-3 px-4 rounded-lg font-semibold hover:from-primary-dark hover:to-primary transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl"
                                        data-code="{{ $item->code }}"
                                        data-discount-type="{{ $item->discount_type }}"
                                        data-discount-value="{{ $item->discount_value }}"
                                        data-coupon-type="{{ $item->type_coupon }}">
                                        <i class="fas fa-check mr-2"></i>Sử dụng ngay
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-tag text-4xl text-gray-400"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-600 mb-2">Không có mã giảm giá</h4>
                            <p class="text-gray-500">Hiện tại không có mã giảm giá nào khả dụng.</p>
                        </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-2"></i>
                            Mã giảm giá sẽ được áp dụng tự động khi bạn nhấn "Sử dụng ngay"
                        </div>
                        <div class="flex space-x-3">
                            <button id="close-platform-discount-modal"
                                class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200">
                                Đóng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .coupon-card {
        position: relative;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 2px dashed #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .coupon-card:hover {
        border-color: #3b82f6;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .coupon-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #1d4ed8);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .coupon-card:hover::before {
        opacity: 1;
    }
    
    /* Platform coupon button styles */
    .coupon-card .use-platform-coupon-btn {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        transition: all 0.3s ease;
    }
    
    .coupon-card .use-platform-coupon-btn:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
    }

    /* Shop coupon button styles */
    .coupon-card .use-shop-coupon-btn {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        transition: all 0.3s ease;
    }
    
    .coupon-card .use-shop-coupon-btn:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    }

    /* Hover border colors */
    .coupon-card:hover.border-blue-500 {
        border-color: #3b82f6;
    }
    
    .coupon-card:hover.border-green-500 {
        border-color: #10b981;
    }
    
    /* Custom scrollbar for modals */
    .max-h-96::-webkit-scrollbar {
        width: 6px;
    }
    
    .max-h-96::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }
    
    .max-h-96::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    
    .max-h-96::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Animation for modals */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    #platform-discount-modal .bg-white,
    [id^="discounts-modal-"] .bg-white {
        animation: slideIn 0.3s ease-out;
    }
    
    @media (max-width: 768px) {
        .grid-cols-1.md\:grid-cols-2.lg\:grid-cols-3 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }
    }
    
    @media (min-width: 768px) and (max-width: 1024px) {
        .grid-cols-1.md\:grid-cols-2.lg\:grid-cols-3 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>
@endpush

@push('scripts')
    <script>
        window.checkoutData = {
            addresses: @json($user_addresses),
            shops: @json($shops),
            user_points: @json($user_points),
            subtotal: @json($subtotal),
            csrfToken: '{{ csrf_token() }}',
            applyDiscountUrl: '{{ route('customer.apply-app-discount') }}',
            checkoutStoreUrl: '{{ route('checkout.store') }}'
        };
    </script>
    @vite(['resources/css/ghn-address.css', 'resources/js/checkout/ghn-address.js', 'resources/js/checkout/index.js'])
    <script>
        localStorage.removeItem('coupons_code');
        localStorage.removeItem('platform_coupons_code');

        document.addEventListener('DOMContentLoaded', () => {
            const platformModal = document.getElementById('platform-discount-modal');
            const showPlatformBtn = document.getElementById('show-platform-discounts-btn');
            const closePlatformBtn = document.getElementById('close-platform-discount-modal');

            showPlatformBtn?.addEventListener('click', () => platformModal.classList.remove('hidden'));
            closePlatformBtn?.addEventListener('click', () => platformModal.classList.add('hidden'));

            window.addEventListener('click', (e) => {
                if (e.target === platformModal) platformModal.classList.add('hidden');
            });

            document.querySelectorAll('.use-platform-coupon-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const code = btn.getAttribute('data-code');
                    const discountType = btn.getAttribute('data-discount-type');
                    const discountValue = btn.getAttribute('data-discount-value');
                    const couponType = btn.getAttribute('data-coupon-type');
                    
                    platformModal.classList.add('hidden');
                    
                    applyPlatformDiscount(code, discountType, discountValue, couponType);
                });
            });
        });

        function showDiscountsModal(shopId) {
            const modal = document.getElementById(`discounts-modal-${shopId}`);
            if (modal) {
                modal.classList.remove('hidden');
                document.querySelectorAll(`.use-shop-coupon-btn[data-shop-id="${shopId}"]`).forEach(btn => {
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                });
            }
        }

        function closeDiscountsModal(shopId) {
            const modal = document.getElementById(`discounts-modal-${shopId}`);
            if (modal) modal.classList.add('hidden');
        }

        function getCouponCodes() {
            return JSON.parse(localStorage.getItem('coupons_code') || '[]');
        }

        function storeCouponCode(shopId, couponData) {
            let codes = getCouponCodes();
            codes.push({
                shopId: shopId,
                code: couponData.code,
                discount_value: couponData.discount_value,
                coupon_type: couponData.coupon_type,
            });
            localStorage.setItem('coupons_code', JSON.stringify(codes));
            console.log(localStorage.getItem('coupons_code'));
        }

        function removeCouponForShop(shopId) {
            let codes = JSON.parse(localStorage.getItem('coupons_code') || '[]');
            codes = codes.filter(item => item.shopId != shopId);
            localStorage.setItem('coupons_code', JSON.stringify(codes));
        }

        function calculateDiscountTotal() {
            let codes = getCouponCodes();
            console.log(codes);
            let discountTotal = 0;
            codes.forEach(code => {
                if (code.discount_value) {
                    discountTotal += parseInt(code.discount_value);
                }
            });

            return discountTotal;
        }

        function applyPlatformDiscount(code, discountType, discountValue, couponType) {
            const subtotal = parseInt(document.getElementById('subtotal').innerText.replace(/[^\d]/g, ''));
            const totalShippingFee = parseInt(document.getElementById('total_shipping_fee').innerText.replace(/[^\d]/g, ''));
            
            let discountAmount = 0;
            let discountDescription = '';

            let platformCoupons = [];
            try {
                platformCoupons = JSON.parse(localStorage.getItem('platform_coupons_code')) || [];
            } catch (e) {
                platformCoupons = [];
            }

            // Chỉ cho phép 1 mã mỗi loại
                // Loại bỏ mã cũ cùng loại trước khi thêm mới
            platformCoupons = platformCoupons.filter(item => item.coupon_type !== couponType);

            // Xử lý theo loại mã giảm giá
            if (couponType === 'shipping') {
                // Mã giảm giá vận chuyển
                if (discountType === 'percentage') {
                    discountAmount = Math.floor(totalShippingFee * parseInt(discountValue) / 100);
                } else {
                    discountAmount = Math.min(parseInt(discountValue), totalShippingFee);
                }
                discountDescription = 'Giảm phí vận chuyển';
                
                document.querySelector('.discount-row-shipping').style.display = 'flex';
                document.getElementById('discount_shipping_fee').innerText = discountAmount.toLocaleString('vi-VN') + '₫';
                
            } else if (couponType === 'order') {
                if (discountType === 'percentage') {
                    discountAmount = Math.floor(subtotal * parseInt(discountValue) / 100);
                } else {
                    discountAmount = Math.min(parseInt(discountValue), subtotal);
                }
                discountDescription = 'Giảm giá đơn hàng';
                
                document.querySelector('.discount-row-platform').style.display = 'flex';
                document.getElementById('discount_amount').innerText = discountAmount.toLocaleString('vi-VN') + '₫';
                
            } else {
                if (discountType === 'percentage') {
                    discountAmount = Math.floor(subtotal * parseInt(discountValue) / 100);
                } else {
                    discountAmount = Math.min(parseInt(discountValue), subtotal);
                }
                discountDescription = 'Giảm giá đơn hàng';
                
                document.querySelector('.discount-row-platform').style.display = 'flex';
                document.getElementById('discount_amount').innerText = discountAmount.toLocaleString('vi-VN') + '₫';
            }

            platformCoupons.push({
                code: code,
                discount_value: discountAmount,
                coupon_type: couponType,
            });
            localStorage.setItem('platform_coupons_code', JSON.stringify(platformCoupons));

            if (couponType === 'shipping') {
                const cancelBtn = document.getElementById('cancel-shipping-coupon-btn');
                if (cancelBtn) {
                    cancelBtn.classList.remove('hidden');
                }
            } else {
                const cancelBtn = document.getElementById('cancel-platform-coupon-btn');
                if (cancelBtn) {
                    cancelBtn.classList.remove('hidden');
                }
            }

            updateTotal();
            showSuccess('Áp dụng mã giảm giá của sàn thành công!');
        }

        function storePlatformCouponCode(code, couponData) {
            let codes = Array.isArray(JSON.parse(localStorage.getItem('platform_coupons_code'))) ? JSON.parse(localStorage.getItem('platform_coupons_code')) : [];
            codes = codes.filter(item => item.code !== code);
            codes.push({
                code: code,
                discount_value: couponData.discount_value,
                coupon_type: couponData.coupon_type,
            });
            localStorage.setItem('platform_coupons_code', JSON.stringify(codes));
            console.log(localStorage.getItem('platform_coupons_code'));
        }

        function removePlatformCoupon() {
            let codes = getCouponCodes();
            codes = codes.filter(item => !item.isPlatform || item.couponData?.coupon_type !== 'order');
            localStorage.setItem('coupons_code', JSON.stringify(codes));
            
            // Ẩn dòng giảm giá đơn hàng
            document.querySelector('.discount-row-platform').style.display = 'none';
            document.getElementById('discount_amount').innerText = '0₫';
            
            // Ẩn nút hủy
            const cancelBtn = document.getElementById('cancel-platform-coupon-btn');
            if (cancelBtn) {
                cancelBtn.classList.add('hidden');
            }
            
            updateTotal();
        }

        function removeShippingCoupon() {
            let codes = getCouponCodes();
            codes = codes.filter(item => !item.isPlatform || item.couponData?.coupon_type !== 'shipping');
            localStorage.setItem('coupons_code', JSON.stringify(codes));
            
            // Ẩn dòng giảm giá vận chuyển
            document.querySelector('.discount-row-shipping').style.display = 'none';
            document.getElementById('discount_shipping_fee').innerText = '0₫';
            
            // Ẩn nút hủy
            const cancelBtn = document.getElementById('cancel-shipping-coupon-btn');
            if (cancelBtn) {
                cancelBtn.classList.add('hidden');
            }
            
            updateTotal();
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.use-shop-coupon-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const shopId = btn.getAttribute('data-shop-id');
                    const code = btn.getAttribute('data-coupon-code');
                    const discountAmountInput = document.getElementById(
                        `discount-amount-shop-${shopId}`);
                    const discountContextInput = document.getElementById(
                        `discount-context-shop-${shopId}`);
                    const totalAmountElem = document.getElementById(
                        `total-product-price-shop-${shopId}`);

                    if (!totalAmountElem.dataset.originalPrice) {
                        totalAmountElem.dataset.originalPrice = totalAmountElem.innerText.replace(
                            /[^\d]/g, '');
                    }
                    const totalAmountShop = parseInt(totalAmountElem.dataset.originalPrice);
                    closeDiscountsModal(shopId);

                    fetch('{{ route('customer.apply-shop-discount') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                shop_id: shopId,
                                coupon_code: code,
                                total_amount: totalAmountShop,
                                shipping_fee: parseInt(document.getElementById(`shipping-fee-shop-${shopId}`).innerText.replace(/[^\d]/g, '')) || 0,
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                storeCouponCode(shopId, data.used_coupon_data);
                                if (data.used_coupon_data.type_coupon == 'shipping') {
                                    let discountAmount = document.getElementById(
                                        'discount-amount-shop-' + shopId);
                                    discountAmount.innerText = data.used_coupon_data
                                        .discount_value.toLocaleString('vi-VN') + '₫';
                                    discountAmount.parentElement.classList.remove('hidden');

                                    document.querySelector('.discount-row-shop').style.display =
                                        'flex';
                                    document.getElementById('discount_shop_fee').innerText =
                                        calculateDiscountTotal().toLocaleString('vi-VN') + '₫';

                                    let cancelBtn = document.getElementById(
                                        `cancel-coupon-btn-shop-${shopId}`);
                                    cancelBtn.classList.remove('hidden');
                                    updateTotal();
                                } else {
                                    let discountAmount = document.getElementById(
                                        'discount-amount-shop-' + shopId);
                                    discountAmount.innerText = data.used_coupon_data
                                        .discount_value.toLocaleString('vi-VN') + '₫';
                                    discountAmount.parentElement.classList.remove('hidden');

                                    document.querySelector('.discount-row-shop').style.display =
                                        'flex';
                                    document.getElementById('discount_shop_fee').innerText =
                                        calculateDiscountTotal().toLocaleString('vi-VN') + '₫';

                                    let cancelBtn = document.getElementById(
                                        `cancel-coupon-btn-shop-${shopId}`);
                                    cancelBtn.classList.remove('hidden');
                                    updateTotal();
                                }
                                showSuccess('Áp dụng mã giảm thành công!');
                            } else {
                                showError(data.error);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showError('Có lỗi xảy ra khi áp dụng mã giảm');
                        });
                });
            });

            window.addEventListener('click', (e) => {
                document.querySelectorAll('[id^="discounts-modal-"]').forEach(modal => {
                    if (e.target === modal) {
                        const shopId = modal.id.split('-').pop();
                        closeDiscountsModal(shopId);
                    }
                });
            });

            window.addEventListener('DOMContentLoaded', (e) => {
                document.querySelectorAll('[id^="cancel-coupon-btn-shop-"]').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const shopId = btn.getAttribute('data-shop-id');
                        removeCouponForShop(shopId);
                        document.getElementById('cancel-coupon-btn-shop-' + shopId)
                            .classList.add('hidden');
                        const discountAmount = document.getElementById(
                            'discount-amount-shop-' + shopId);
                        if (discountAmount) {
                            discountAmount.innerText = '0';
                            discountAmount.parentElement.classList.add('hidden');
                        }
                        document.getElementById('discount_shop_fee').innerText =
                            calculateDiscountTotal().toLocaleString('vi-VN') + '₫';
                        if (calculateDiscountTotal() == 0) {
                            document.querySelector('.discount-row-shop').style.display =
                                'none';
                        }
                        updateTotal();
                        console.log(getCouponCodes());
                    });
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const togglePointsBtn = document.getElementById('toggle-points-btn');
            const usedPointsInputGroup = document.getElementById('used-points-input-group');
            togglePointsBtn.addEventListener('change', () => {
                usedPointsInputGroup.classList.toggle('hidden');
            });
        });
    </script>
@endpush
