@extends('layouts.app')
@push('styles')
    <style>
        .rotate-180 {
            transform: rotate(180deg);
        }

        .filter-link.active {
            color: #ef444488 !important;
        }
    </style>
@endpush
@php
    $hasFilter =
        request()->has('category') ||
        request()->has('brand') ||
        request()->filled('price_min') ||
        request()->filled('price_max') ||
        request()->filled('query');
@endphp
@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Bộ Lọc -->
            <aside class="w-full lg:w-1/5 bg-white p-4 rounded-lg shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Bộ Lọc</h2>
                    <button type="button" id="reset-filters"
                        class="text-sm text-black hover:text-red-600 transition-colors underline {{ $hasFilter ? '' : 'hidden' }}">
                        Xóa lọc
                    </button>
                </div>

                <form method="GET" action="{{ route('search') }}" id="filter-form">
                    <input type="hidden" name="query" value="{{ request('query') }}">

                    <!-- Danh mục -->
                    <div class="mb-4">
                        <h3 class="font-semibold text-sm mb-2 text-gray-700">Danh mục</h3>
                        <div class="space-y-1">
                            @foreach ($categories as $cat)
                                <div class="category-group">
                                    <div class="flex justify-between items-center bg-white rounded-md px-2 py-1">
                                        <label class="flex items-center space-x-2 w-full cursor-pointer">
                                            <input type="checkbox" class="filter-checkbox" name="category[]"
                                                value="{{ $cat->id }}"
                                                {{ in_array($cat->id, request('category', [])) ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-800">{{ $cat->name }} <span
                                                    class="text-gray-500">({{ $cat->product_count }})</span></span>
                                        </label>
                                        @if ($cat->subCategories->isNotEmpty())
                                            <button type="button" class="toggle-dropdown px-1"
                                                data-toggle="dropdown-{{ $cat->id }}">
                                                <svg class="w-4 h-4 text-gray-400 transition-transform"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>

                                    @if ($cat->subCategories->isNotEmpty())
                                        <div id="dropdown-{{ $cat->id }}" class="dropdown hidden ml-4 mt-1 space-y-1">
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
                    </div>

                    <!-- Thương hiệu -->
                    <div class="mb-4">
                        <h3 class="font-semibold text-sm mb-2 text-gray-700">Thương Hiệu</h3>
                        <div class="space-y-1">
                            @foreach ($brands as $brand)
                                <div class="brand-group">
                                    <div class="flex justify-between items-center bg-white rounded-md px-2 py-1">
                                        <label class="flex items-center space-x-2 w-full cursor-pointer">
                                            <input type="checkbox" class="filter-checkbox" name="brand[]"
                                                value="{{ $brand->id }}"
                                                {{ in_array($brand->id, request('brand', [])) ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-800">{{ $brand->name }} <span
                                                    class="text-gray-500">({{ $brand->product_count }})</span></span>
                                        </label>
                                        @if ($brand->subBrands->isNotEmpty())
                                            <button type="button" class="toggle-dropdown px-1"
                                                data-toggle="brand-dropdown-{{ $brand->id }}">
                                                <svg class="w-4 h-4 text-gray-400 transition-transform"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>

                                    @if ($brand->subBrands->isNotEmpty())
                                        <div id="brand-dropdown-{{ $brand->id }}"
                                            class="dropdown hidden ml-4 mt-1 space-y-1">
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

                    <button type="submit"
                        class="w-full mt-4 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg shadow-md transition-colors duration-200">
                        Áp dụng
                    </button>
                </form>
            </aside>

            <!-- Kết quả tìm kiếm -->
            <div class="w-full lg:w-4/5">
                <!-- Thanh sắp xếp -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 bg-white p-3 rounded-lg shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 ml-2">
                        <path
                            d="M18.75 12.75h1.5a.75.75 0 0 0 0-1.5h-1.5a.75.75 0 0 0 0 1.5ZM12 6a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 12 6ZM12 18a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 12 18ZM3.75 6.75h1.5a.75.75 0 1 0 0-1.5h-1.5a.75.75 0 0 0 0 1.5ZM5.25 18.75h-1.5a.75.75 0 0 1 0-1.5h1.5a.75.75 0 0 1 0 1.5ZM3 12a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 3 12ZM9 3.75a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5ZM12.75 12a2.25 2.25 0 1 1 4.5 0 2.25 2.25 0 0 1-4.5 0ZM9 15.75a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z" />
                    </svg>
                    <div class="flex gap-2 flex-wrap">
                        <a href="{{ route('search', array_merge(request()->query(), ['sort' => 'relevance'])) }}"
                            class="px-3 py-1 text-sm border border-gray-300 rounded {{ request('sort') == 'relevance' || !request('sort') ? 'bg-red-500 text-white' : 'hover:bg-gray-100' }} transition-colors duration-200">Liên
                            Quan</a>
                        <a href="{{ route('search', array_merge(request()->query(), ['sort' => 'newest'])) }}"
                            class="px-3 py-1 text-sm border border-gray-300 rounded {{ request('sort') == 'newest' ? 'bg-red-500 text-white' : 'hover:bg-gray-100' }} transition-colors duration-200">Mới
                            Nhất</a>
                        <a href="{{ route('search', array_merge(request()->query(), ['sort' => 'sold'])) }}"
                            class="px-3 py-1 text-sm border border-gray-300 rounded {{ request('sort') == 'sold' ? 'bg-red-500 text-white' : 'hover:bg-gray-100' }} transition-colors duration-200">Bán
                            Chạy</a>
                        <select onchange="window.location.href=this.value"
                            class="px-3 py-1 text-sm border border-gray-300 rounded bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option
                                value="{{ route('search', array_merge(request()->query(), ['sort' => 'price_asc'])) }}"
                                {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                            <option
                                value="{{ route('search', array_merge(request()->query(), ['sort' => 'price_desc'])) }}"
                                {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                        </select>
                    </div>
                </div>
                <div id="product-results">
                    @include('partials.product_list', ['products' => $products])
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            // Hàm debounce để trì hoãn gửi yêu cầu AJAX
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Lấy giá trị query từ URL nếu không tìm thấy trong DOM
            function getQueryParam(name) {
                const params = new URLSearchParams(window.location.search);
                return params.getAll(name).length > 0 ? params.getAll(name) : [];
            }

            // Hàm gửi yêu cầu AJAX
            function applyFilterAJAX() {
                const form = document.getElementById('filter-form');
                const url = form.getAttribute('action');
                const loading = document.getElementById('loading');
                const resultContainer = document.getElementById('product-results');

                // Hiển thị loading và làm mờ kết quả
                if (loading) loading.classList.remove('hidden');
                if (resultContainer) resultContainer.classList.add('opacity-50');

                // Đồng bộ từ khóa tìm kiếm
                const globalQuery = document.querySelector('form[action="{{ route('search') }}"] input[name="query"]');
                const keyword = globalQuery?.value || getQueryParam('query')[0] || '';
                let filterQuery = form.querySelector('input[name="query"]');
                if (!filterQuery) {
                    filterQuery = document.createElement('input');
                    filterQuery.type = 'hidden';
                    filterQuery.name = 'query';
                    form.appendChild(filterQuery);
                }
                filterQuery.value = keyword;

                // Gửi yêu cầu AJAX
                const formData = new FormData(form);
                fetch(url + '?' + new URLSearchParams(formData).toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Yêu cầu không thành công');
                        return res.json();
                    })
                    .then(data => {
                        // Cập nhật kết quả
                        if (resultContainer) {
                            resultContainer.innerHTML = data.html;
                            resultContainer.classList.remove('opacity-50');
                        }

                        // Đồng bộ trạng thái checkbox và hiển thị dropdown
                        if (data.filters) {
                            document.querySelectorAll('input[name="category[]"]').forEach(cb => {
                                cb.checked = data.filters.category.includes(parseInt(cb.value));
                            });
                            document.querySelectorAll('input[name="brand[]"]').forEach(cb => {
                                cb.checked = data.filters.brand.includes(parseInt(cb.value));
                            });

                            // Ẩn tất cả dropdown và reset biểu tượng mũi tên
                            document.querySelectorAll('.dropdown').forEach(dropdown => {
                                dropdown.classList.add('hidden');
                            });
                            document.querySelectorAll('.toggle-dropdown svg').forEach(icon => {
                                icon.classList.remove('rotate-180');
                            });

                            // Hiển thị dropdown của danh mục/thương hiệu được chọn
                            if (data.filters.category.length > 0) {
                                data.filters.category.forEach(catId => {
                                    const dropdown = document.getElementById(`dropdown-${catId}`);
                                    if (dropdown) dropdown.classList.remove('hidden');
                                    const icon = document.querySelector(`[data-toggle="dropdown-${catId}"] svg`);
                                    if (icon) icon.classList.add('rotate-180');
                                });
                            }
                            if (data.filters.brand.length > 0) {
                                data.filters.brand.forEach(brandId => {
                                    const dropdown = document.getElementById(`brand-dropdown-${brandId}`);
                                    if (dropdown) dropdown.classList.remove('hidden');
                                    const icon = document.querySelector(
                                        `[data-toggle="brand-dropdown-${brandId}"] svg`);
                                    if (icon) icon.classList.add('rotate-180');
                                });
                            }
                        } else {
                            // Nếu không có bộ lọc, hiển thị tất cả dropdown
                            document.querySelectorAll('.dropdown').forEach(dropdown => {
                                dropdown.classList.remove('hidden');
                            });
                            document.querySelectorAll('.toggle-dropdown svg').forEach(icon => {
                                icon.classList.remove('rotate-180');
                            });
                        }

                        // Hiển thị/ẩn nút reset
                        const hasFilter = form.querySelector('input[name="category[]"]:checked') ||
                            form.querySelector('input[name="brand[]"]:checked') ||
                            document.getElementById('price_min').value ||
                            document.getElementById('price_max').value;
                        const resetBtn = document.getElementById('reset-filters');
                        if (resetBtn) resetBtn.classList.toggle('hidden', !hasFilter);

                        if (loading) loading.classList.add('hidden');
                    })
                    .catch(err => {
                        console.error('Lỗi khi gọi AJAX:', err);
                        alert('Đã có lỗi xảy ra, vui lòng thử lại.');
                        if (loading) loading.classList.add('hidden');
                        if (resultContainer) resultContainer.classList.remove('opacity-50');
                    });
            }

            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('filter-form');
                const debouncedApplyFilter = debounce(applyFilterAJAX, 300); // Trì hoãn 300ms

                // Khởi tạo trạng thái ban đầu dựa trên URL
                const initialCategories = getQueryParam('category[]').map(id => parseInt(id));
                const initialBrands = getQueryParam('brand[]').map(id => parseInt(id));

                if (initialCategories.length > 0) {
                    document.querySelectorAll('input[name="category[]"]').forEach(cb => {
                        cb.checked = initialCategories.includes(parseInt(cb.value));
                    });
                    document.querySelectorAll('.dropdown[id^="dropdown-"]').forEach(dropdown => {
                        dropdown.classList.add('hidden');
                    });
                    initialCategories.forEach(catId => {
                        const dropdown = document.getElementById(`dropdown-${catId}`);
                        if (dropdown) dropdown.classList.remove('hidden');
                        const icon = document.querySelector(`[data-toggle="dropdown-${catId}"] svg`);
                        if (icon) icon.classList.add('rotate-180');
                    });
                }

                if (initialBrands.length > 0) {
                    document.querySelectorAll('input[name="brand[]"]').forEach(cb => {
                        cb.checked = initialBrands.includes(parseInt(cb.value));
                    });
                    document.querySelectorAll('.dropdown[id^="brand-dropdown-"]').forEach(dropdown => {
                        dropdown.classList.add('hidden');
                    });
                    initialBrands.forEach(brandId => {
                        const dropdown = document.getElementById(`brand-dropdown-${brandId}`);
                        if (dropdown) dropdown.classList.remove('hidden');
                        const icon = document.querySelector(`[data-toggle="brand-dropdown-${brandId}"] svg`);
                        if (icon) icon.classList.add('rotate-180');
                    });
                }

                // Gắn sự kiện change cho checkbox danh mục
                document.querySelectorAll('input[name="category[]"]').forEach(cb => {
                    cb.addEventListener('change', () => {
                        if (cb.checked) {
                            // Bỏ chọn tất cả checkbox danh mục khác
                            document.querySelectorAll('input[name="category[]"]').forEach(otherCb => {
                                if (otherCb !== cb && !isChildCategory(cb.value, otherCb
                                    .value)) {
                                    otherCb.checked = false;
                                }
                            });
                            // Ẩn tất cả dropdown danh mục
                            document.querySelectorAll('.dropdown[id^="dropdown-"]').forEach(
                            dropdown => {
                                dropdown.classList.add('hidden');
                            });
                            document.querySelectorAll('.toggle-dropdown[data-toggle^="dropdown-"] svg')
                                .forEach(icon => {
                                    icon.classList.remove('rotate-180');
                                });
                            // Hiển thị dropdown của danh mục được chọn
                            const dropdown = document.getElementById(`dropdown-${cb.value}`);
                            if (dropdown) dropdown.classList.remove('hidden');
                            const icon = document.querySelector(
                                `[data-toggle="dropdown-${cb.value}"] svg`);
                            if (icon) icon.classList.add('rotate-180');
                        }
                        debouncedApplyFilter();
                    });
                });

                // Gắn sự kiện change cho checkbox thương hiệu
                document.querySelectorAll('input[name="brand[]"]').forEach(cb => {
                    cb.addEventListener('change', () => {
                        if (cb.checked) {
                            // Bỏ chọn tất cả checkbox thương hiệu khác
                            document.querySelectorAll('input[name="brand[]"]').forEach(otherCb => {
                                if (otherCb !== cb && !isChildBrand(cb.value, otherCb.value)) {
                                    otherCb.checked = false;
                                }
                            });
                            // Ẩn tất cả dropdown thương hiệu
                            document.querySelectorAll('.dropdown[id^="brand-dropdown-"]').forEach(
                                dropdown => {
                                    dropdown.classList.add('hidden');
                                });
                            document.querySelectorAll(
                                '.toggle-dropdown[data-toggle^="brand-dropdown-"] svg').forEach(
                                icon => {
                                    icon.classList.remove('rotate-180');
                                });
                            // Hiển thị dropdown của thương hiệu được chọn
                            const dropdown = document.getElementById(`brand-dropdown-${cb.value}`);
                            if (dropdown) dropdown.classList.remove('hidden');
                            const icon = document.querySelector(
                                `[data-toggle="brand-dropdown-${cb.value}"] svg`);
                            if (icon) icon.classList.add('rotate-180');
                        }
                        debouncedApplyFilter();
                    });
                });

                // Hàm kiểm tra xem một danh mục có phải là con/cháu của danh mục khác không
                function isChildCategory(parentId, childId) {
                    const dropdown = document.getElementById(`dropdown-${parentId}`);
                    if (!dropdown) return false;
                    return dropdown.querySelector(`input[value="${childId}"]`) !== null;
                }

                // Hàm kiểm tra xem một thương hiệu có phải là con của thương hiệu khác không
                function isChildBrand(parentId, childId) {
                    const dropdown = document.getElementById(`brand-dropdown-${parentId}`);
                    if (!dropdown) return false;
                    return dropdown.querySelector(`input[value="${childId}"]`) !== null;
                }

                // Gợi ý khoảng giá
                document.querySelectorAll('.price-suggestion').forEach(button => {
                    button.addEventListener('click', () => {
                        document.getElementById('price_min').value = button.getAttribute('data-min') ||
                            '';
                        document.getElementById('price_max').value = button.getAttribute('data-max') ||
                            '';
                        debouncedApplyFilter();
                    });
                });

                // Nút reset
                document.getElementById('reset-filters')?.addEventListener('click', () => {
                    // Xóa trạng thái checkbox
                    form.querySelectorAll('.filter-checkbox').forEach(cb => cb.checked = false);
                    // Xóa giá trị input khoảng giá
                    document.getElementById('price_min').value = '';
                    document.getElementById('price_max').value = '';
                    // Mở lại tất cả các dropdown và reset biểu tượng mũi tên
                    document.querySelectorAll('.dropdown').forEach(dropdown => {
                        dropdown.classList.remove('hidden');
                    });
                    document.querySelectorAll('.toggle-dropdown svg').forEach(icon => {
                        icon.classList.remove('rotate-180');
                    });
                    // Gửi yêu cầu AJAX
                    debouncedApplyFilter();
                    // Ẩn nút reset
                    document.getElementById('reset-filters').classList.add('hidden');
                });

                // Toggle dropdown (accordion danh mục/brand)
                document.querySelectorAll('.toggle-dropdown').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const dropdownId = this.getAttribute('data-toggle');
                        const dropdown = document.getElementById(dropdownId);
                        const icon = this.querySelector('svg');
                        if (dropdown) {
                            dropdown.classList.toggle('hidden');
                            if (icon) icon.classList.toggle('rotate-180');
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
