
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
