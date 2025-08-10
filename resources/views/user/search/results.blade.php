@extends('layouts.app')
@push('styles')
    <style>
        .rotate-180 {
            transform: rotate(180deg);
        }

        .filter-link.active {
            color: #ef444488 !important;
        }

        /* Loading indicator */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
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

                        <!-- Danh mục và Thương hiệu trong cùng khu vực -->
                        <div class="mb-4">
                            <h3 class="font-semibold text-sm mb-2 text-gray-700">Danh mục</h3>
                            <div id="category-filters-container">
                                @foreach ($categories as $cat)
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
                                        @if ($cat->subCategories->isNotEmpty())
                                            <div id="dropdown-{{ $cat->id }}" class="ml-4 mt-1 space-y-1">
                                                @foreach ($cat->subCategories as $sub)
                                                    <label class="flex items-center space-x-2 cursor-pointer">
                                                        <input type="checkbox" class="filter-checkbox" name="category[]"
                                                            value="{{ $sub->id }}"
                                                            {{ in_array($sub->id, request('category', [])) ? 'checked' : '' }}>
                                                        <span class="text-sm text-gray-700">-- {{ $sub->name }} <span
                                                                class="text-gray-500">({{ $sub->product_count ?? 0 }})</span></span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <h3 class="font-semibold text-sm mt-4 mb-2 text-gray-700">Thương hiệu</h3>
                            <div id="brand-filters-container">
                                @foreach ($brands as $brand)
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
                                        @if ($brand->subBrands->isNotEmpty())
                                            <div id="brand-dropdown-{{ $brand->id }}" class="ml-4 mt-1 space-y-1">
                                                @foreach ($brand->subBrands as $sub)
                                                    <label class="flex items-center space-x-2 cursor-pointer">
                                                        <input type="checkbox" class="filter-checkbox" name="brand[]"
                                                            value="{{ $sub->id }}"
                                                            {{ in_array($sub->id, request('brand', [])) ? 'checked' : '' }}>
                                                        <span class="text-sm text-gray-700">-- {{ $sub->name }} <span
                                                                class="text-gray-500">({{ $sub->product_count ?? 0 }})</span></span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
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

                <!-- Loading indicator -->
                <div id="loading-indicator" class="hidden text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-red-500"
                        aria-label="Đang tải"></div>
                    <p class="mt-2 text-gray-600">Đang tải kết quả...</p>
                </div>

                <div id="product-results" class="fade-in">
                    @include('partials.product_list', ['products' => $products])
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('filter-form');
                const productResults = document.getElementById('product-results');
                const loadingIndicator = document.getElementById('loading-indicator');
                let currentRequest = null; // Để cancel request cũ

                // Hàm AJAX chung để cập nhật kết quả
                function updateResults(params = {}) {
                    // Cancel request cũ nếu có
                    if (currentRequest) {
                        currentRequest.abort();
                    }

                    // Hiển thị loading
                    loadingIndicator.classList.remove('hidden');
                    productResults.classList.add('loading');

                    // Thêm aria-live để screen reader biết có thay đổi
                    productResults.setAttribute('aria-live', 'polite');
                    productResults.setAttribute('aria-busy', 'true');

                    // Lấy form data
                    const formData = new FormData(form);

                    // Thêm params mới
                    Object.keys(params).forEach(key => {
                        if (params[key] !== null && params[key] !== undefined) {
                            formData.set(key, params[key]);
                        }
                    });

                    // Tạo URL params
                    const urlParams = new URLSearchParams(formData);

                    // Gọi AJAX
                    currentRequest = fetch(`{{ route('search') }}?${urlParams.toString()}`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Cập nhật URL mà không reload trang
                            const newUrl = `{{ route('search') }}?${urlParams.toString()}`;
                            window.history.pushState({}, '', newUrl);

                            // Cập nhật danh sách sản phẩm
                            productResults.innerHTML = data.productList;
                            productResults.classList.remove('loading');
                            productResults.classList.add('fade-in');

                            // Cập nhật bộ lọc danh mục
                            const categoryFiltersContainer = document.getElementById('category-filters-container');
                            if (categoryFiltersContainer && data.categoryFilters) {
                                categoryFiltersContainer.innerHTML = data.categoryFilters;
                            }

                            // Cập nhật bộ lọc thương hiệu
                            const brandFiltersContainer = document.getElementById('brand-filters-container');
                            if (brandFiltersContainer && data.brandFilters) {
                                brandFiltersContainer.innerHTML = data.brandFilters;
                            }

                            // Cập nhật aria attributes
                            productResults.setAttribute('aria-busy', 'false');

                            // Cập nhật trạng thái nút reset
                            updateResetButtonVisibility();

                            // Cập nhật active state cho sort buttons
                            updateSortButtonsActiveState(params.sort || formData.get('sort'));

                            // Scroll to top of results
                            productResults.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });

                            // Thông báo cho screen reader
                            const resultCount = productResults.querySelectorAll('.product-card').length;
                            if (resultCount > 0) {
                                productResults.setAttribute('aria-label', `Hiển thị ${resultCount} sản phẩm`);
                            }

                            // Cập nhật số lượng sản phẩm hiển thị
                            updateProductCount(data.totalProducts);

                            // Re-attach event listeners cho các checkbox mới
                            reattachFilterEventListeners();
                        })
                        .catch(error => {
                            if (error.name !== 'AbortError') {
                                console.error('Error:', error);

                                // Hiển thị thông báo lỗi
                                productResults.innerHTML = `
                                <div class="text-center py-8">
                                    <div class="text-red-500 mb-4">
                                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Có lỗi xảy ra</h3>
                                    <p class="text-gray-600 mb-4">Không thể tải kết quả tìm kiếm. Vui lòng thử lại.</p>
                                    <button onclick="window.location.reload()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                                        Tải lại trang
                                    </button>
                                </div>
                            `;

                                // Cập nhật aria attributes
                                productResults.setAttribute('aria-busy', 'false');
                                productResults.setAttribute('aria-label', 'Có lỗi xảy ra khi tải kết quả');
                            }
                        })
                        .finally(() => {
                            loadingIndicator.classList.add('hidden');
                            productResults.classList.remove('loading');
                            currentRequest = null;
                        });
                }

                // Gắn sự kiện change cho checkbox danh mục
                document.querySelectorAll('input[name="category[]"]').forEach(cb => {
                    cb.addEventListener('change', () => {
                        updateResults();
                    });
                });

                // Gắn sự kiện change cho checkbox thương hiệu
                document.querySelectorAll('input[name="brand[]"]').forEach(cb => {
                    cb.addEventListener('change', () => {
                        updateResults();
                    });
                });

                // Gợi ý khoảng giá
                document.querySelectorAll('.price-suggestion').forEach(button => {
                    button.addEventListener('click', () => {
                        document.getElementById('price_min').value = button.getAttribute('data-min') ||
                            '';
                        document.getElementById('price_max').value = button.getAttribute('data-max') ||
                            '';
                        updateResults();
                    });
                });

                // Nút áp dụng bộ lọc
                document.getElementById('apply-filters').addEventListener('click', () => {
                    updateResults();
                });

                // Nút reset - chỉ xóa các bộ lọc, giữ từ khóa tìm kiếm và sắp xếp
                document.getElementById('reset-filters')?.addEventListener('click', () => {
                    // Xóa tất cả checkbox
                    form.querySelectorAll('.filter-checkbox').forEach(cb => cb.checked = false);

                    // Xóa giá min/max
                    document.getElementById('price_min').value = '';
                    document.getElementById('price_max').value = '';

                    // Cập nhật kết quả
                    updateResults();
                });

                // Sort buttons
                document.querySelectorAll('.sort-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const sort = btn.getAttribute('data-sort');
                        updateResults({
                            sort: sort
                        });
                    });
                });

                // Price sort select
                document.getElementById('price-sort-select').addEventListener('change', function() {
                    updateResults({
                        sort: this.value
                    });
                });

                // Hàm cập nhật trạng thái active cho sort buttons
                function updateSortButtonsActiveState(sort) {
                    // Reset tất cả sort buttons
                    document.querySelectorAll('.sort-btn').forEach(btn => {
                        btn.classList.remove('bg-red-500', 'text-white');
                        btn.classList.add('hover:bg-gray-100');
                    });

                    // Set active cho button được chọn
                    const activeBtn = document.querySelector(`[data-sort="${sort}"]`);
                    if (activeBtn) {
                        activeBtn.classList.remove('hover:bg-gray-100');
                        activeBtn.classList.add('bg-red-500', 'text-white');
                    }

                    // Cập nhật price sort select
                    const priceSelect = document.getElementById('price-sort-select');
                    if (priceSelect && (sort === 'price_asc' || sort === 'price_desc')) {
                        priceSelect.value = sort;
                    }
                }

                // Cập nhật trạng thái hiển thị nút reset
                function updateResetButtonVisibility() {
                    const resetBtn = document.getElementById('reset-filters');
                    const hasActiveFilters =
                        document.querySelectorAll('input[name="category[]"]:checked').length > 0 ||
                        document.querySelectorAll('input[name="brand[]"]:checked').length > 0 ||
                        document.getElementById('price_min').value ||
                        document.getElementById('price_max').value;

                    if (hasActiveFilters) {
                        resetBtn.classList.remove('hidden');
                    } else {
                        resetBtn.classList.add('hidden');
                    }
                }

                // Debounce function cho input giá
                let priceTimeout;

                function debouncePriceUpdate(func, wait) {
                    clearTimeout(priceTimeout);
                    priceTimeout = setTimeout(func, wait);
                }

                // Gắn sự kiện cho input giá với debounce
                ['#price_min', '#price_max'].forEach(selector => {
                    const element = document.querySelector(selector);
                    if (element) {
                        element.addEventListener('input', () => {
                            updateResetButtonVisibility();
                            // Debounce update results để tránh gọi quá nhiều
                            debouncePriceUpdate(() => {
                                if (element.value) {
                                    updateResults();
                                }
                            }, 500);
                        });
                    }
                });

                // Gắn sự kiện để cập nhật trạng thái nút reset
                document.querySelectorAll('.filter-checkbox').forEach(element => {
                    element.addEventListener('change', updateResetButtonVisibility);
                });

                // Khởi tạo trạng thái nút reset
                updateResetButtonVisibility();

                // Mobile filter toggle
                const mobileFilterToggle = document.getElementById('mobile-filter-toggle');
                const filterContent = document.getElementById('filter-content');

                if (mobileFilterToggle && filterContent) {
                    mobileFilterToggle.addEventListener('click', () => {
                        const isHidden = filterContent.classList.contains('hidden');

                        if (isHidden) {
                            filterContent.classList.remove('hidden');
                            mobileFilterToggle.querySelector('svg').classList.add('rotate-180');
                        } else {
                            filterContent.classList.add('hidden');
                            mobileFilterToggle.querySelector('svg').classList.remove('rotate-180');
                        }
                    });

                    // Ẩn filter content trên mobile mặc định
                    filterContent.classList.add('hidden');
                }

                // Xử lý browser back/forward
                window.addEventListener('popstate', function() {
                    // Reload trang khi user sử dụng browser back/forward
                    window.location.reload();
                });

                // Hàm cập nhật số lượng sản phẩm hiển thị
                function updateProductCount(totalProducts) {
                    const countElement = document.getElementById('product-count');
                    if (countElement) {
                        countElement.textContent = totalProducts;
                    }
                }

                // Hàm re-attach event listeners cho các checkbox mới
                function reattachFilterEventListeners() {
                    // Re-attach category checkbox events
                    document.querySelectorAll('input[name="category[]"]').forEach(cb => {
                        cb.addEventListener('change', () => {
                            updateResults();
                        });
                    });

                    // Re-attach brand checkbox events
                    document.querySelectorAll('input[name="brand[]"]').forEach(cb => {
                        cb.addEventListener('change', () => {
                            updateResults();
                        });
                    });

                    // Re-attach filter checkbox events
                    document.querySelectorAll('.filter-checkbox').forEach(element => {
                        element.addEventListener('change', updateResetButtonVisibility);
                    });
                }
            });
        </script>
    @endpush
@endsection
