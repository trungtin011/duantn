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
            <aside class="w-full lg:w-1/4 bg-white p-4 rounded-lg shadow">
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
                                    <div id="category-filters-container">
                                        @include('partials.category_filters', ['categories' => $categories])
                                    </div>
                                </div>
                            @endif

                            <!-- Thương hiệu -->
                            @if($brands && $brands->isNotEmpty())
                                <div class="mb-4">
                                    <div id="brand-filters-container">
                                        @include('partials.brand_filters', ['brands' => $brands])
                                    </div>
                                </div>
                            @endif

                            <!-- Cửa hàng -->
                            @if($shops && $shops->isNotEmpty())
                                <div class="mb-4">
                                    <div id="shop-filters-container">
                                        @include('partials.shop_filters', ['shops' => $shops])
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Rating Filter -->
                            <div class="mb-4">
                                <div id="rating-filters-container">
                                    @include('partials.rating_filters')
                                </div>
                            </div>
                        </div>

                        <!-- Khoảng giá -->
                        <div class="mb-4">
                            <h3 class="font-semibold text-sm mb-2 text-gray-700">Khoảng Giá</h3>
                            <div class="mt-2 flex flex-col gap-2">
                                <input type="range" id="price-range-min" min="0" max="100000000" step="1000" value="{{ request('price_min') ? (int) request('price_min') : 0 }}">
                                <input type="range" id="price-range-max" min="0" max="100000000" step="1000" value="{{ request('price_max') ? (int) request('price_max') : 100000000 }}">
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span id="price-min-display" class="text-sm text-gray-700"></span>
                                <span id="price-max-display" class="text-sm text-gray-700"></span>
                            </div>
                            <input type="hidden" name="price_min" id="price_min" value="{{ request('price_min') }}">
                            <input type="hidden" name="price_max" id="price_max" value="{{ request('price_max') }}">
                            <div class="grid grid-cols-2 gap-2 mt-3">
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
        <script>
            (function() {
                const currencyFormat = (value) => {
                    if (!value && value !== 0) return '';
                    return new Intl.NumberFormat('vi-VN').format(Math.round(value)) + 'đ';
                };

                const hiddenMin = document.getElementById('price_min');
                const hiddenMax = document.getElementById('price_max');
                const displayMin = document.getElementById('price-min-display');
                const displayMax = document.getElementById('price-max-display');

                const rangeMin = document.getElementById('price-range-min');
                const rangeMax = document.getElementById('price-range-max');

                const ABS_MIN = parseInt(rangeMin.min, 10) || 0;
                const ABS_MAX = parseInt(rangeMax.max, 10) || 100000000;

                const syncDisplays = () => {
                    const minV = parseInt(rangeMin.value, 10);
                    const maxV = parseInt(rangeMax.value, 10);
                    displayMin.textContent = 'Từ: ' + currencyFormat(minV);
                    displayMax.textContent = 'Đến: ' + (maxV >= ABS_MAX ? 'Không giới hạn' : currencyFormat(maxV));
                };

                const normalizeRanges = () => {
                    if (parseInt(rangeMin.value, 10) > parseInt(rangeMax.value, 10)) {
                        rangeMin.value = rangeMax.value;
                    }
                };

                // Initialize from hidden inputs if present
                if (hiddenMin.value) rangeMin.value = hiddenMin.value;
                if (hiddenMax.value) rangeMax.value = hiddenMax.value;
                normalizeRanges();
                syncDisplays();

                rangeMin.addEventListener('input', () => {
                    normalizeRanges();
                    hiddenMin.value = parseInt(rangeMin.value, 10) === ABS_MIN ? '' : rangeMin.value;
                    syncDisplays();
                });
                rangeMax.addEventListener('input', () => {
                    normalizeRanges();
                    hiddenMax.value = parseInt(rangeMax.value, 10) >= ABS_MAX ? '' : rangeMax.value;
                    syncDisplays();
                });

                // Price suggestion buttons bind to ranges
                document.querySelectorAll('.price-suggestion').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const min = this.dataset.min === '' ? ABS_MIN : parseInt(this.dataset.min || ABS_MIN, 10);
                        const max = this.dataset.max === '' ? ABS_MAX : parseInt(this.dataset.max || ABS_MAX, 10);
                        rangeMin.value = min;
                        rangeMax.value = max;
                        hiddenMin.value = min === ABS_MIN ? '' : min;
                        hiddenMax.value = max >= ABS_MAX ? '' : max;
                        syncDisplays();
                    });
                });
            })();
        </script>
        <script src="{{ asset('js/search-filter-manager.js') }}"></script>
    @endpush
@endsection