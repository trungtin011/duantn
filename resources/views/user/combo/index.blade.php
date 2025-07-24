
@extends('layouts.app')

@section('title', 'Danh sách Combo')

@section('meta-description', 'Khám phá các combo khuyến mãi hấp dẫn với nhiều sản phẩm chất lượng từ các cửa hàng uy tín.')

@section('meta-keywords', 'combo, khuyến mãi, mua sắm trực tuyến, sản phẩm combo, deal, bundle, offer, discount')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user/style-prefix.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/style-home.css') }}">
    <style>
        .layout-container {
            display: grid;
            grid-template-columns: 1fr; /* Default to full width on small screens */
            gap: 2rem;
        }
        @media (min-width: 768px) {
            .layout-container {
                grid-template-columns: 3fr 9fr; /* 3:9 ratio on medium and larger screens */
            }
        }
        .filter-section {
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .products-section {
            min-width: 0; /* Prevent overflow issues */
        }
        .combo-card {
            position: relative;
        }
        .discount-tooltip {
            visibility: hidden;
            opacity: 0;
            position: absolute;
            top: -2rem;
            left: 50%;
            transform: translateX(-50%);
            background-color: #1f2937; /* Tailwind gray-800 */
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            white-space: nowrap;
            transition: opacity 0.2s ease-in-out;
            z-index: 10;
        }
        .combo-card:hover .discount-tooltip {
            visibility: visible;
            opacity: 1;
        }
        .original-price {
            text-decoration: line-through;
            color: #6b7280; /* Tailwind gray-500 */
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Danh sách Combo</h1>

        <div class="layout-container">
            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" action="{{ route('combo.index') }}" class="mb-0">
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Tìm kiếm</label>
                            <input type="text" name="search" id="search"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Tên combo" value="{{ request('search') }}">
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Giá (VNĐ)</label>
                            <div class="mt-1 flex gap-2">
                                <select name="min_price" id="min_price"
                                    class="w-1/2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="" {{ request('min_price') == '' ? 'selected' : '' }}>Chọn tối thiểu</option>
                                    <option value="100000" {{ request('min_price') == '100000' ? 'selected' : '' }}>100.000 ₫</option>
                                    <option value="200000" {{ request('min_price') == '200000' ? 'selected' : '' }}>200.000 ₫</option>
                                    <option value="300000" {{ request('min_price') == '300000' ? 'selected' : '' }}>300.000 ₫</option>
                                    <option value="500000" {{ request('min_price') == '500000' ? 'selected' : '' }}>500.000 ₫</option>
                                    <option value="700000" {{ request('min_price') == '700000' ? 'selected' : '' }}>700.000 ₫</option>
                                    <option value="1000000" {{ request('min_price') == '1000000' ? 'selected' : '' }}>1.000.000 ₫</option>
                                    <option value="2000000" {{ request('min_price') == '2000000' ? 'selected' : '' }}>2.000.000 ₫</option>
                                </select>
                                <select name="max_price" id="max_price"
                                    class="w-1/2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="" {{ request('max_price') == '' ? 'selected' : '' }}>Chọn tối đa</option>
                                    <option value="200000" {{ request('max_price') == '200000' ? 'selected' : '' }}>200.000 ₫</option>
                                    <option value="300000" {{ request('max_price') == '300000' ? 'selected' : '' }}>300.000 ₫</option>
                                    <option value="500000" {{ request('max_price') == '500000' ? 'selected' : '' }}>500.000 ₫</option>
                                    <option value="700000" {{ request('max_price') == '700000' ? 'selected' : '' }}>700.000 ₫</option>
                                    <option value="1000000" {{ request('max_price') == '1000000' ? 'selected' : '' }}>1.000.000 ₫</option>
                                    <option value="2000000" {{ request('max_price') == '2000000' ? 'selected' : '' }}>2.000.000 ₫</option>
                                    <option value="3000000" {{ request('max_price') == '3000000' ? 'selected' : '' }}>3.000.000 ₫</option>
                                    <option value="5000000" {{ request('max_price') == '5000000' ? 'selected' : '' }}>5.000.000 ₫</option>
                                </select>
                            </div>
                        </div>

                        <!-- Discount Percentage Range -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phần trăm giảm giá (%)</label>
                            <div class="mt-1 flex gap-2">
                                <select name="min_discount" id="min_discount"
                                    class="w-1/2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="" {{ request('min_discount') == '' ? 'selected' : '' }}>Chọn tối thiểu</option>
                                    <option value="10" {{ request('min_discount') == '10' ? 'selected' : '' }}>10%</option>
                                    <option value="20" {{ request('min_discount') == '20' ? 'selected' : '' }}>20%</option>
                                    <option value="30" {{ request('min_discount') == '30' ? 'selected' : '' }}>30%</option>
                                    <option value="40" {{ request('min_discount') == '40' ? 'selected' : '' }}>40%</option>
                                    <option value="50" {{ request('min_discount') == '50' ? 'selected' : '' }}>50%</option>
                                    <option value="60" {{ request('min_discount') == '60' ? 'selected' : '' }}>60%</option>
                                    <option value="70" {{ request('min_discount') == '70' ? 'selected' : '' }}>70%</option>
                                    <option value="80" {{ request('min_discount') == '80' ? 'selected' : '' }}>80%</option>
                                    <option value="90" {{ request('min_discount') == '90' ? 'selected' : '' }}>90%</option>
                                </select>
                                <select name="max_discount" id="max_discount"
                                    class="w-1/2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="" {{ request('max_discount') == '' ? 'selected' : '' }}>Chọn tối đa</option>
                                    <option value="20" {{ request('max_discount') == '20' ? 'selected' : '' }}>20%</option>
                                    <option value="30" {{ request('max_discount') == '30' ? 'selected' : '' }}>30%</option>
                                    <option value="40" {{ request('max_discount') == '40' ? 'selected' : '' }}>40%</option>
                                    <option value="50" {{ request('max_discount') == '50' ? 'selected' : '' }}>50%</option>
                                    <option value="60" {{ request('max_discount') == '60' ? 'selected' : '' }}>60%</option>
                                    <option value="70" {{ request('max_discount') == '70' ? 'selected' : '' }}>70%</option>
                                    <option value="80" {{ request('max_discount') == '80' ? 'selected' : '' }}>80%</option>
                                    <option value="90" {{ request('max_discount') == '90' ? 'selected' : '' }}>90%</option>
                                    <option value="100" {{ request('max_discount') == '100' ? 'selected' : '' }}>100%</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm">
                            Lọc
                        </button>
                    </div>
                </form>
            </div>

            <!-- Products Section -->
            <div class="products-section">
                @if ($combos->isEmpty())
                    <p class="text-gray-500 text-center">Chưa có combo nào khả dụng.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($combos as $combo)
                            <div class="combo-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <a href="{{ route('combo.show', $combo->id) }}">
                                    <div class="relative">
                                        @if ($combo->image)
                                            <img src="{{ asset('storage/' . $combo->image) }}" alt="{{ $combo->combo_name }}"
                                                class="w-full h-48 object-cover" loading="lazy">
                                        @else
                                            <img src="{{ asset('images/no-image.png') }}" alt="{{ $combo->combo_name }}"
                                                class="w-full h-48 object-cover" loading="lazy">
                                        @endif
                                        @if ($combo->discount_type === 'percentage' && $combo->discount_value > 0)
                                            <span class="discount-tooltip">Giảm {{ $combo->discount_value }}%</span>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $combo->combo_name }}</h3>
                                        <div class="flex items-center gap-2">
                                            <p class="text-red-500 font-semibold text-lg">{{ number_format($combo->total_price, 0, ',', '.') }} ₫</p>
                                            @if ($combo->discount_type === 'percentage' && $combo->discount_value > 0)
                                                @php
                                                    $originalPrice = $combo->original_price ?? ($combo->total_price / (1 - $combo->discount_value / 100));
                                                @endphp
                                                <p class="original-price text-sm">{{ number_format($originalPrice, 0, ',', '.') }} ₫</p>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $combos->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
