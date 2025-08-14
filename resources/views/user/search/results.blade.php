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

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
@endpush
@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto py-6 px-4">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Bộ Lọc -->
                <aside class="w-full lg:w-1/4">
                    <!-- Mobile filter toggle -->
                    <div class="lg:hidden mb-4">
                        <button type="button" id="mobile-filter-toggle"
                            class="w-full bg-white hover:bg-gray-50 text-gray-800 py-3 px-4 rounded-lg shadow-sm flex items-center justify-between transition-colors duration-200 border border-gray-200">
                            <span class="font-medium text-gray-700">Bộ Lọc Sản Phẩm</span>
                            <svg class="w-5 h-5 transform transition-transform text-gray-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>
                    </div>

                    <div id="filter-content" class="hidden lg:block">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <!-- Filter Header -->
                            <div class="bg-gray-800 px-4 py-3 rounded-t-lg">
                                <div class="flex justify-between items-center">
                                    <h2 class="text-lg font-semibold text-white">Bộ Lọc</h2>
                                    <button type="button" id="reset-filters"
                                        class="text-gray-300 hover:text-white transition-colors duration-200 text-sm underline hidden">
                                        Xóa lọc
                                    </button>
                                </div>
                            </div>

                            <!-- Filter Content -->
                            <div class="p-4">
                                <form method="GET" action="{{ route('search') }}" id="filter-form">
                                    <input type="hidden" name="query" value="{{ request('query') }}">
                                    <input type="hidden" name="sort" value="{{ request('sort', 'relevance') }}">

                                    <!-- Danh mục và Thương hiệu -->
                                    <div class="space-y-4">
                                        <!-- Danh mục -->
                                        @if ($categories && $categories->isNotEmpty())
                                            <div id="category-filters-container">
                                                @include('partials.category_filters', [
                                                    'categories' => $categories,
                                                ])
                                            </div>
                                        @endif

                                        <!-- Thương hiệu -->
                                        @if ($brands && $brands->isNotEmpty())
                                            <div id="brand-filters-container">
                                                @include('partials.brand_filters', ['brands' => $brands])
                                            </div>
                                        @endif

                                        <!-- Cửa hàng -->
                                        @if ($shops && $shops->isNotEmpty())
                                            <div id="shop-filters-container">
                                                @include('partials.shop_filters', ['shops' => $shops])
                                            </div>
                                        @endif

                                        <!-- Rating Filter -->
                                        <div id="rating-filters-container">
                                            @include('partials.rating_filters')
                                        </div>
                                    </div>

                                    <!-- Khoảng giá -->
                                    <div class="mb-4">
                                        <h3 class="font-semibold text-sm mb-3 text-gray-700">Khoảng Giá</h3>
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="text-xs text-gray-600 mb-1 block">Giá tối thiểu</label>
                                                    <input type="range" id="price-range-min" min="0"
                                                        max="100000000" step="1000"
                                                        value="{{ request('price_min') ? (int) request('price_min') : 0 }}"
                                                        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                                </div>
                                                <div>
                                                    <label class="text-xs text-gray-600 mb-1 block">Giá tối đa</label>
                                                    <input type="range" id="price-range-max" min="0"
                                                        max="100000000" step="1000"
                                                        value="{{ request('price_max') ? (int) request('price_max') : 100000000 }}"
                                                        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                                </div>
                                            </div>

                                            <div class="flex items-center justify-between mt-3 text-sm">
                                                <span id="price-min-display" class="text-gray-700"></span>
                                                <span id="price-max-display" class="text-gray-700"></span>
                                            </div>

                                            <input type="hidden" name="price_min" id="price_min"
                                                value="{{ request('price_min') }}">
                                            <input type="hidden" name="price_max" id="price_max"
                                                value="{{ request('price_max') }}">

                                            <div class="grid grid-cols-2 gap-2 mt-3">
                                                <button type="button"
                                                    class="price-suggestion bg-white border border-gray-200 rounded px-2 py-1 text-xs hover:bg-gray-50 transition-colors duration-200"
                                                    data-min="0" data-max="500000">Dưới 500K</button>
                                                <button type="button"
                                                    class="price-suggestion bg-white border border-gray-200 rounded px-2 py-1 text-xs hover:bg-gray-50 transition-colors duration-200"
                                                    data-min="500000" data-max="1000000">500K – 1 triệu</button>
                                                <button type="button"
                                                    class="price-suggestion bg-white border border-gray-200 rounded px-2 py-1 text-xs hover:bg-gray-50 transition-colors duration-200"
                                                    data-min="1000000" data-max="3000000">1 – 3 triệu</button>
                                                <button type="button"
                                                    class="price-suggestion bg-white border border-gray-200 rounded px-2 py-1 text-xs hover:bg-gray-50 transition-colors duration-200"
                                                    data-min="3000000" data-max="">Trên 3 triệu</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tùy chọn tự động lướt xuống -->
                                    <div class="mb-4">
                                        <label
                                            class="flex items-center space-x-2 cursor-pointer p-2 rounded hover:bg-gray-50 transition-colors duration-200">
                                            <input type="checkbox" id="auto-scroll-toggle" class="rounded border-gray-300">
                                            <span class="text-sm text-gray-600">Tự động lướt xuống kết quả</span>
                                        </label>
                                    </div>

                                    <button type="button" id="apply-filters"
                                        class="w-full bg-gray-800 hover:bg-gray-700 text-white py-3 rounded-lg transition-colors duration-200 font-medium">
                                        Áp dụng bộ lọc
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Kết quả tìm kiếm -->
                <div class="w-full lg:w-3/4">
                    @if ($advertisedProductsByShop->isNotEmpty())
                        @include('partials.advertised_products', [
                            'advertisedProductsByShop' => $advertisedProductsByShop,
                        ])
                    @endif
                    <!-- Hiển thị từ khóa tìm kiếm nếu có -->
                    @if (request('query'))
                        <div class="mb-4 bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-search text-gray-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-medium text-gray-800">Kết quả tìm kiếm</h3>
                                        <p class="text-gray-600">Từ khóa: <strong>"{{ request('query') }}"</strong></p>
                                    </div>
                                </div>
                                <a href="{{ route('search') }}"
                                    class="text-gray-500 hover:text-gray-700 text-sm underline transition-colors duration-200">
                                    Xóa từ khóa
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Toolbar -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-4 h-4 text-gray-600">
                                        <path
                                            d="M18.75 12.75h1.5a.75.75 0 0 0 0-1.5h-1.5a.75.75 0 0 0 0 1.5ZM12 6a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 12 6ZM12 18a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 12 18ZM3.75 6.75h1.5a.75.75 0 1 0 0-1.5h-1.5a.75.75 0 0 0 0 1.5ZM5.25 18.75h-1.5a.75.75 0 0 1 0-1.5h1.5a.75.75 0 0 1 0 1.5ZM3 12a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 3 12ZM9 3.75a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5ZM12.75 12a2.25 2.25 0 1 1 4.5 0 2.25 2.25 0 0 1-4.5 0ZM9 15.75a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">
                                        Hiển thị <span id="product-count"
                                            class="font-semibold text-lg text-gray-800">{{ $products->total() }}</span> sản
                                        phẩm
                                    </span>
                                </div>
                            </div>

                            <div class="flex gap-2 flex-wrap">
                                <button type="button" data-sort="relevance"
                                    class="sort-btn px-3 py-2 text-sm border border-gray-300 rounded font-medium transition-colors duration-200 {{ request('sort') == 'relevance' || !request('sort') ? 'bg-gray-800 text-white border-transparent' : 'hover:bg-gray-50' }}">
                                    Liên Quan
                                </button>
                                <button type="button" data-sort="newest"
                                    class="sort-btn px-3 py-2 text-sm border border-gray-300 rounded font-medium transition-colors duration-200 {{ request('sort') == 'newest' ? 'bg-gray-800 text-white border-transparent' : 'hover:bg-gray-50' }}">
                                    Mới Nhất
                                </button>
                                <button type="button" data-sort="sold"
                                    class="sort-btn px-3 py-2 text-sm border border-gray-300 rounded font-medium transition-colors duration-200 {{ request('sort') == 'sold' ? 'bg-gray-800 text-white border-transparent' : 'hover:bg-gray-50' }}">
                                    Bán Chạy
                                </button>
                                <select id="price-sort-select"
                                    class="px-3 py-2 text-sm border border-gray-300 rounded bg-white focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all duration-200">
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá:
                                        Thấp đến Cao</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                        Giá: Cao đến Thấp</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Product Results -->
                    <div id="product-results" class="fade-in">
                        @include('partials.product_list', ['products' => $products])
                    </div>
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
                        const min = this.dataset.min === '' ? ABS_MIN : parseInt(this.dataset.min ||
                            ABS_MIN, 10);
                        const max = this.dataset.max === '' ? ABS_MAX : parseInt(this.dataset.max ||
                            ABS_MAX, 10);
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
        <script>
            // Khởi tạo trạng thái nút reset khi trang load
            document.addEventListener('DOMContentLoaded', function() {
                // Đợi SearchFilterManager khởi tạo xong
                const checkManager = () => {
                    const searchManager = window.searchFilterManager;
                    if (searchManager && typeof searchManager.updateResetButtonVisibility === 'function') {
                        searchManager.updateResetButtonVisibility();
                    } else {
                        // Thử lại sau 50ms nếu chưa sẵn sàng
                        setTimeout(checkManager, 50);
                    }
                };
                checkManager();

                // Fallback: nếu SearchFilterManager không hoạt động, sử dụng form submit thông thường
                setTimeout(() => {
                    if (!window.searchFilterManager) {
                        console.warn('SearchFilterManager not available, using fallback form submission');
                        const form = document.getElementById('filter-form');
                        if (form) {
                            // Add fallback event listeners
                            const sortButtons = document.querySelectorAll('.sort-btn');
                            sortButtons.forEach(btn => {
                                btn.addEventListener('click', (e) => {
                                    e.preventDefault();
                                    const sort = btn.getAttribute('data-sort');
                                    if (sort) {
                                        const sortInput = form.querySelector(
                                            'input[name="sort"]');
                                        if (sortInput) sortInput.value = sort;
                                        form.submit();
                                    }
                                });
                            });

                            const priceSelect = document.getElementById('price-sort-select');
                            if (priceSelect) {
                                priceSelect.addEventListener('change', (e) => {
                                    if (e.target.value) {
                                        const sortInput = form.querySelector('input[name="sort"]');
                                        if (sortInput) sortInput.value = e.target.value;
                                        form.submit();
                                    }
                                });
                            }
                        }
                    }
                }, 1000);
            });
        </script>
    @endpush
@endsection
