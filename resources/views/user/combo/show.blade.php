<<<<<<< HEAD
=======

>>>>>>> Huy
@extends('layouts.app')

@section('title', '{{ $combo->combo_name }}')

@section('meta-description', '{{ Str::limit(strip_tags($combo->combo_description ?? ""), 160) }}')

@section('meta-keywords', 'combo, {{ $combo->combo_name }}, khuyến mãi, deal, bundle, offer, discount')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user/style-prefix.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/style-home.css') }}">
@endpush

@section('content')
    <div class="container mx-auto px-4 py-8">
<<<<<<< HEAD
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 mb-6 text-sm font-medium">
            <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">Trang chủ</a>
            <span class="text-gray-400">/</span>
            <a href="#" class="text-blue-600 hover:text-blue-800">Chi tiết sản phẩm</a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-800 font-semibold">{{ \Illuminate\Support\Str::limit($combo->combo_name, 30, '...') }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Cột chính (Hình ảnh + Thông tin sản phẩm) -->
            <div class="lg:col-span-3">
                <!-- Hình ảnh và thông tin sản phẩm -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white rounded-lg p-6 shadow">
                    <!-- Hình ảnh sản phẩm -->
                    <div class="relative">
                        @if ($combo->image)
                            <img src="{{ asset('storage/' . $combo->image) }}" alt="{{ $combo->combo_name }}"
                                class="w-full h-80 object-cover rounded-lg transform transition-transform duration-300 hover:scale-105"
                                loading="lazy">
                        @else
                            <img src="{{ asset('images/no-image.png') }}" alt="{{ $combo->combo_name }}"
                                class="w-full h-80 object-cover rounded-lg transform transition-transform duration-300 hover:scale-105"
                                loading="lazy">
                        @endif
                        
                        <!-- Badge thông báo nếu combo không có sản phẩm -->
                        @if ($combo->products->isEmpty())
                            <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                Tạm hết hàng
                            </div>
                        @endif
                    </div>

                    <!-- Thông tin sản phẩm -->
                    <div class="flex flex-col gap-6">
                        <h2 class="text-3xl font-bold text-gray-900" title="{{ $combo->combo_name }}">
                            {{ \Illuminate\Support\Str::limit($combo->combo_name, 50, '...') }}
                        </h2>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-gray-600">
                                    (Chưa có đánh giá) | Đã bán: 0
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4" id="price-display">
                            <span class="text-red-600 text-3xl font-bold">
                                {{ number_format($combo->total_price, 0, ',', '.') }} ₫
                            </span>
                            @if ($combo->discount_value && $combo->discount_type)
                                <span class="text-gray-500 line-through text-lg">
                                    {{ number_format($combo->total_price + ($combo->discount_type == 'percentage' ? $combo->total_price * $combo->discount_value / 100 : $combo->discount_value), 0, ',', '.') }} ₫
                                </span>
                                <span class="bg-red-100 text-red-600 px-3 py-1 rounded text-sm">
                                    -{{ $combo->discount_type == 'percentage' ? $combo->discount_value : round(($combo->discount_value / ($combo->total_price + $combo->discount_value)) * 100) }}%
                                </span>
                            @endif
                        </div>
                        <p class="text-gray-700 text-base leading-relaxed">{!! $combo->combo_description !!}</p>

                        <!-- Form thêm vào giỏ hàng -->
                        @if ($combo->products->isNotEmpty())
                            <form action="{{ route('cart.addCombo') }}" method="POST" class="mt-4">
                                @csrf
                                <input type="hidden" name="combo_id" value="{{ $combo->id }}">
                                <div class="flex items-center gap-4">
                                    <input type="number" name="quantity" value="1" min="1"
                                        class="w-20 border border-gray-300 rounded-md p-2 text-sm">
                                    <button type="submit"
                                        class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm transition-colors duration-200">
                                        Thêm vào giỏ hàng
                                    </button>
                                </div>
                            </form>
                        @else
                            <!-- Thông báo khi không có sản phẩm -->
                            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <p class="text-yellow-800 font-medium">Combo hiện tại tạm thời không khả dụng</p>
                                        <p class="text-yellow-700 text-sm mt-1">Vui lòng liên hệ cửa hàng để biết thêm thông tin</p>
                                    </div>
                                </div>
                                <button disabled
                                    class="mt-3 bg-gray-400 text-white px-6 py-2 rounded-md text-sm cursor-not-allowed">
                                    Không thể thêm vào giỏ hàng
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Danh sách sản phẩm trong combo -->
                <div class="bg-white rounded-lg p-6 mt-6 shadow">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Sản phẩm trong Combo</h3>
                    @if ($combo->products->isEmpty())
                        <!-- UI cải thiện cho trường hợp không có sản phẩm -->
                        <div class="text-center py-8">
                            <div class="mb-4">
                                <svg class="w-16 h-16 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Không có sản phẩm nào trong combo</h4>
                            <p class="text-gray-600 mb-4">Combo này hiện tại không có sản phẩm nào hoặc các sản phẩm đã bị xóa.</p>
                            <div class="flex justify-center gap-3">
                                <a href="{{ route('home') }}" 
                                   class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm transition-colors duration-200">
                                    Về trang chủ
                                </a>
                                @if(isset($combo->shop))
                                    <a href="{{ route('shop.profile', $combo->shop->id) }}" 
                                       class="border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-md text-sm transition-colors duration-200">
                                        Xem cửa hàng
                                    </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($combo->products as $comboProduct)
                                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-md hover:bg-gray-100 transition-colors duration-200">
                                    @if ($comboProduct->product && $comboProduct->product->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $comboProduct->product->images->first()->image_url) }}"
                                            alt="{{ $comboProduct->product->name }}"
                                            class="w-16 h-16 object-cover rounded-md" loading="lazy">
                                    @else
                                        <img src="{{ asset('images/no-image.png') }}"
                                            alt="{{ $comboProduct->product->name ?? 'Sản phẩm không xác định' }}"
                                            class="w-16 h-16 object-cover rounded-md" loading="lazy">
                                    @endif
                                    <div class="flex-1">
                                        @if ($comboProduct->product)
                                            <p class="text-gray-800 font-semibold">{{ $comboProduct->product->name }}</p>
                                            <p class="text-gray-500 text-sm">Số lượng: {{ $comboProduct->quantity }}</p>
                                            <p class="text-gray-500 text-sm">Giá: {{ number_format($comboProduct->product->price, 0, ',', '.') }} ₫</p>
                                        @else
                                            <p class="text-gray-500 font-semibold">Sản phẩm đã bị xóa</p>
                                            <p class="text-gray-400 text-sm">Số lượng: {{ $comboProduct->quantity }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Cột bên phải (Thông tin shop) -->
            <div class="lg:col-span-1">
                <div class="sticky top-5">
                    <div class="bg-white rounded-lg p-6 shadow">
                        <h2 class="text-xl font-semibold mb-4 border-b pb-2 text-gray-800">Cửa hàng</h2>
                        @if(isset($combo->shop))
                            <div class="flex items-center gap-4 mb-4">
                                @if($combo->shop->logo)
                                    <img src="{{ asset('storage/' . $combo->shop->logo) }}" alt="Logo Shop" class="w-16 h-16 rounded-full object-cover border" loading="lazy">
                                @else
                                    <img src="{{ asset('storage/shop_logos/default_shop_logo.png') }}" alt="Logo Shop" class="w-16 h-16 rounded-full object-cover border" loading="lazy">
                                @endif
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $combo->shop->name ?? 'Cửa hàng' }}</h3>
                                    <p class="text-sm text-gray-600">
                                        @if($combo->shop->created_at)
                                            Hoạt động từ: {{ $combo->shop->created_at->diffForHumans() }}
                                        @else
                                            Cửa hàng mới
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex justify-center gap-3">
                                <button
                                    class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 flex items-center gap-2 transition-colors duration-200">
                                    <i class="fa-solid fa-comment"></i> Nhắn tin
                                </button>
                                <a href="{{ route('shop.profile', $combo->shop->id) }}"
                                    class="border border-gray-300 px-5 py-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">Xem
                                    cửa hàng
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-gray-500 text-sm">Thông tin cửa hàng không khả dụng</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
=======
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Hình ảnh -->
                <div class="md:w-1/2">
                    @if ($combo->image)
                        <img src="{{ asset('storage/' . $combo->image) }}" alt="{{ $combo->combo_name }}"
                            class="w-full h-80 object-cover rounded-md" loading="lazy">
                    @else
                        <img src="{{ asset('images/no-image.png') }}" alt="{{ $combo->combo_name }}"
                            class="w-full h-80 object-cover rounded-md" loading="lazy">
                    @endif
                </div>

                <!-- Thông tin combo -->
                <div class="md:w-1/2">
                    <h1 class="text-2xl font-semibold text-gray-800 mb-4">{{ $combo->combo_name }}</h1>
                    <p class="text-red-500 font-semibold text-xl mb-4">{{ number_format($combo->total_price, 0, ',', '.') }} ₫</p>
                    @if ($combo->discount_value && $combo->discount_type)
                        <p class="text-gray-500 mb-4">
                            Giảm giá: {{ number_format($combo->discount_value) }} {{ $combo->discount_type == 'percentage' ? '%' : 'VNĐ' }}
                        </p>
                    @endif
                    <p class="text-gray-600 mb-4">{{ $combo->combo_description ?? 'Không có mô tả.' }}</p>
                    <p class="text-gray-500 mb-4">Cửa hàng: <a href="#" class="text-blue-500 hover:underline">{{ $combo->shop->shop_name ?? 'Không có thông tin' }}</a></p>

                    <!-- Form thêm vào giỏ hàng -->
                    <form action="{{ route('cart.addCombo') }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="combo_id" value="{{ $combo->id }}">
                        <div class="flex items-center gap-4">
                            <input type="number" name="quantity" value="1" min="1"
                                class="w-20 border border-gray-300 rounded-md p-2 text-sm">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm">
                                Thêm vào giỏ hàng
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danh sách sản phẩm trong combo -->
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Sản phẩm trong Combo</h2>
                @if ($combo->products->isEmpty())
                    <p class="text-gray-500">Không có sản phẩm nào trong combo này.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($combo->products as $comboProduct)
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-md">
                                @if ($comboProduct->product->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $comboProduct->product->images->first()->image_url) }}"
                                        alt="{{ $comboProduct->product->name }}"
                                        class="w-16 h-16 object-cover rounded-md" loading="lazy">
                                @else
                                    <img src="{{ asset('images/no-image.png') }}"
                                        alt="{{ $comboProduct->product->name }}"
                                        class="w-16 h-16 object-cover rounded-md" loading="lazy">
                                @endif
                                <div>
                                    <p class="text-gray-800 font-semibold">{{ $comboProduct->product->name }}</p>
                                    <p class="text-gray-500 text-sm">Số lượng: {{ $comboProduct->quantity }}</p>
                                    <p class="text-gray-500 text-sm">Giá: {{ number_format($comboProduct->product->price, 0, ',', '.') }} ₫</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
>>>>>>> Huy
