@extends('layouts.app')
@push('styles')
    <style>
        .rotate-180 {
            transform: rotate(180deg);
        }

        .filter-link.active {
            color: #ef444488 !important;
        }



        /* Smooth transitions */
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Notification styles */
        .notification {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
            max-width: 300px;
        }

        .notification.show {
            transform: translateX(0);
        }

        /* Error state */
        .error-state {
            text-align: center;
            padding: 2rem;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 0.5rem;
            margin: 1rem 0;
        }

        /* Disabled state */
        .disabled {
            opacity: 0.5;
            pointer-events: none;
        }


    </style>
@endpush
@php
    $hasFilter =
        request()->has('category') ||
        request()->has('brand') ||
        request()->filled('price_min') ||
        request()->filled('price_max');
@endphp
@section('content')
    <div class="container mx-auto py-6">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Bộ Lọc -->
            <aside class="w-full lg:w-1/5 bg-white p-4 rounded-lg shadow">
                <!-- Mobile filter toggle -->
                <div class="lg:hidden mb-4">
                    <button type="button" id="mobile-filter-toggle"
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 px-4 rounded-lg flex items-center justify-between transition-colors">
                        <span>Bộ Lọc</span>
                        <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>

                <div id="filter-content" class="hidden lg:block">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Bộ Lọc</h2>
                        <button type="button" id="reset-filters"
                            class="text-sm text-black hover:text-red-600 transition-colors underline {{ $hasFilter ? '' : 'hidden' }}">
                            Xóa lọc
                        </button>
                    </div>

                    <form method="GET" action="{{ route('search') }}" id="filter-form">
                        <input type="hidden" name="query" value="{{ request('query') }}">
                        <input type="hidden" name="sort" value="{{ request('sort', 'relevance') }}">

                        <!-- Danh mục và Thương hiệu -->
                        <div class="mb-4">
                            <!-- Danh mục -->
                            @if($categories && $categories->isNotEmpty())
                                <div class="mb-4">
                                    <h3 class="font-semibold text-sm mb-2 text-gray-700">Danh mục</h3>
                                    <div id="category-filters-container">
                                        @foreach ($categories as $cat)
                                            @if($cat && $cat->product_count > 0)
                                                <div class="category-group mb-1">
                                                    <div class="flex items-center bg-white rounded-md px-2 py-1">
                                                        <label class="flex items-center space-x-2 w-full cursor-pointer">
                                                            <input type="checkbox" class="filter-checkbox" name="category[]"
                                                                value="{{ $cat->id }}"
                                                                {{ in_array($cat->id, request('category', [])) ? 'checked' : '' }}>
                                                            <span class="text-sm text-gray-800">{{ $cat->name }} <span
                                                                    class="text-gray-500">({{ $cat->product_count }})</span></span>
                                                        </label>
                                                    </div>
                                                    @if ($cat->subCategories && $cat->subCategories->isNotEmpty())
                                                        <div id="dropdown-{{ $cat->id }}" class="ml-4 mt-1 space-y-1">
                                                            @foreach ($cat->subCategories as $sub)
                                                                @if($sub && ($sub->product_count ?? 0) > 0)
                                                                    <label class="flex items-center space-x-2 cursor-pointer">
                                                                        <input type="checkbox" class="filter-checkbox" name="category[]"
                                                                            value="{{ $sub->id }}"
                                                                            {{ in_array($sub->id, request('category', [])) ? 'checked' : '' }}>
                                                                        <span class="text-sm text-gray-700">-- {{ $sub->name }} <span
                                                                                class="text-gray-500">({{ $sub->product_count ?? 0 }})</span></span>
                                                                    </label>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Thương hiệu -->
                            @if($brands && $brands->isNotEmpty())
                                <div class="mb-4">
                                    <h3 class="font-semibold text-sm mb-2 text-gray-700">Thương hiệu</h3>
                                    <div id="brand-filters-container">
                                        @foreach ($brands as $brand)
                                            @if($brand && $brand->product_count > 0)
                                                <div class="brand-group mb-1">
                                                    <div class="flex items-center bg-white rounded-md px-2 py-1">
                                                        <label class="flex items-center space-x-2 w-full cursor-pointer">
                                                            <input type="checkbox" class="filter-checkbox" name="brand[]"
                                                                value="{{ $brand->id }}"
                                                                {{ in_array($brand->id, request('brand', [])) ? 'checked' : '' }}>
                                                            <span class="text-sm text-gray-800">{{ $brand->name }} <span
                                                                    class="text-gray-500">({{ $brand->product_count }})</span></span>
                                                        </label>
                                                    </div>
                                                    @if ($brand->subBrands && $brand->subBrands->isNotEmpty())
                                                        <div id="brand-dropdown-{{ $brand->id }}" class="ml-4 mt-1 space-y-1">
                                                            @foreach ($brand->subBrands as $sub)
                                                                @if($sub && ($sub->product_count ?? 0) > 0)
                                                                    <label class="flex items-center space-x-2 cursor-pointer">
                                                                        <input type="checkbox" class="filter-checkbox" name="brand[]"
                                                                            value="{{ $sub->id }}"
                                                                            {{ in_array($sub->id, request('brand', [])) ? 'checked' : '' }}>
                                                                        <span class="text-sm text-gray-700">-- {{ $sub->name }} <span
                                                                                class="text-gray-500">({{ $sub->product_count ?? 0 }})</span></span>
                                                                    </label>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Khoảng giá -->
                        <div class="mb-4">
                            <h3 class="font-semibold text-sm mb-2 text-gray-700">Khoảng Giá</h3>
                            <div class="grid grid-cols-2 gap-2 mb-3">
                                <button type="button"
                                    class="price-suggestion border border-gray-300 rounded-lg px-3 py-1.5 text-sm hover:bg-gray-100"
                                    data-min="0" data-max="500000">Dưới 500K</button>
                                <button type="button"
                                    class="price-suggestion border border-gray-300 rounded-lg px-3 py-1.5 text-sm hover:bg-gray-100"
                                    data-min="500000" data-max="1000000">500K – 1 triệu</button>
                                <button type="button"
                                    class="price-suggestion border border-gray-300 rounded-lg px-3 py-1.5 text-sm hover:bg-gray-100"
                                    data-min="1000000" data-max="3000000">1 – 3 triệu</button>
                                <button type="button"
                                    class="price-suggestion border border-gray-300 rounded-lg px-3 py-1.5 text-sm hover:bg-gray-100"
                                    data-min="3000000" data-max="">Trên 3 triệu</button>
                            </div>
                            <div class="flex gap-2">
                                <input type="number" name="price_min" id="price_min" placeholder="Từ"
                                    value="{{ request('price_min') }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <input type="number" name="price_max" id="price_max" placeholder="Đến"
                                    value="{{ request('price_max') }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Tùy chọn tự động lướt xuống -->
                        <div class="mb-3">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" id="auto-scroll-toggle" class="rounded">
                                <span class="text-xs text-gray-600">Tự động lướt xuống kết quả</span>
                            </label>
                        </div>

                        <button type="button" id="apply-filters"
                            class="w-full mt-4 bg-[#f42f46] hover:bg-red-600 text-white py-2 rounded-lg shadow-md transition-colors duration-200">
                            Áp dụng
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Kết quả tìm kiếm -->
            <div class="w-full lg:w-4/5">
                <!-- Hiển thị từ khóa tìm kiếm nếu có -->
                @if (request('query'))
                    <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-search text-blue-600"></i>
                                <span class="text-blue-800 font-medium">Kết quả tìm kiếm cho:
                                    <strong>"{{ request('query') }}"</strong></span>
                            </div>
                            <a href="{{ route('search') }}" class="text-blue-600 hover:text-blue-800 text-sm underline">
                                Xóa từ khóa tìm kiếm
                            </a>
                        </div>
                    </div>
                @endif

                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 bg-white p-3 rounded-lg shadow">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path
                                d="M18.75 12.75h1.5a.75.75 0 0 0 0-1.5h-1.5a.75.75 0 0 0 0 1.5ZM12 6a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 12 6ZM12 18a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 12 18ZM3.75 6.75h1.5a.75.75 0 1 0 0-1.5h-1.5a.75.75 0 0 0 0 1.5ZM5.25 18.75h-1.5a.75.75 0 0 1 0-1.5h1.5a.75.75 0 0 1 0 1.5ZM3 12a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 3 12ZM9 3.75a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5ZM12.75 12a2.25 2.25 0 1 1 4.5 0 2.25 2.25 0 0 1-4.5 0ZM9 15.75a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z" />
                        </svg>
                        <span class="text-sm text-gray-600">
                            Hiển thị <span id="product-count" class="font-semibold">{{ $products->total() }}</span> sản
                            phẩm
                        </span>
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        <button type="button" data-sort="relevance"
                            class="sort-btn px-3 py-1 text-sm border border-gray-300 rounded {{ request('sort') == 'relevance' || !request('sort') ? 'bg-red-500 text-white' : 'hover:bg-gray-100' }} transition-colors duration-200">Liên
                            Quan</button>
                        <button type="button" data-sort="newest"
                            class="sort-btn px-3 py-1 text-sm border border-gray-300 rounded {{ request('sort') == 'newest' ? 'bg-red-500 text-white' : 'hover:bg-gray-100' }} transition-colors duration-200">Mới
                            Nhất</button>
                        <button type="button" data-sort="sold"
                            class="sort-btn px-3 py-1 text-sm border border-gray-300 rounded {{ request('sort') == 'sold' ? 'bg-red-500 text-white' : 'hover:bg-gray-100' }} transition-colors duration-200">Bán
                            Chạy</button>
                        <select id="price-sort-select"
                            class="px-3 py-1 text-sm border border-gray-300 rounded bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến
                                Cao</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao
                                đến Thấp</option>
                        </select>
                    </div>
                </div>



                <div id="product-results" class="fade-in">
                    @include('partials.product_list', ['products' => $products])
                </div>
            </div>
        </div>
    </div>

    <!-- Notification container -->
    <div id="notification-container"></div>



    @push('scripts')
        <script src="{{ asset('js/search-filter-manager.js') }}"></script>

    @endpush
@endsection