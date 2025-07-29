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
                <div class="bg-white rounded-lg p-6 mt-6 shadow">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Sản phẩm trong Combo</h3>
                    @if ($combo->products->isEmpty())
                        <p class="text-gray-600">Không có sản phẩm nào trong combo này.</p>
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

            <!-- Cột bên phải (Thông tin shop) -->
            <div class="lg:col-span-1">
                <div class="sticky top-5">
                    <div class="bg-white rounded-lg p-6 shadow">
                        <h2 class="text-xl font-semibold mb-4 border-b pb-2 text-gray-800">Cửa hàng</h2>
                        <div class="flex items-center gap-4 mb-4">
                            <img src="{{ asset('storage/shop_logos/default_shop_logo.png') }}" alt="Logo Shop" class="w-16 h-16 rounded-full object-cover border" loading="lazy">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Seller One Shop</h3>
                                <p class="text-sm text-gray-600">Hoạt động từ: 2 tuần trước</p>
                            </div>
                        </div>
                        <div class="flex justify-center gap-3">
                            <button
                                class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 flex items-center gap-2">
                                <i class="fa-solid fa-comment"></i> Nhắn tin
                            </button>
                            <a href="{{ route('shop.profile', $combo->shop->id) }}"
                                class="border border-gray-300 px-5 py-2 rounded-lg hover:bg-gray-100">Xem
                                cửa hàng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection