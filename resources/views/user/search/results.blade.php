@extends('layouts.app')
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
            <aside class="w-full lg:w-1/5 bg-white p-4 border rounded-lg shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Bộ Lọc</h2>
                    @if ($hasFilter)
                        <button type="button" id="reset-filters"
                            class="text-sm text-black hover:text-red-600 transition-colors underline">
                            Xóa lọc
                        </button>
                    @endif
                </div>

                <form method="GET" action="{{ route('search') }}" id="filter-form">
                    <input type="hidden" name="query" value="{{ request('query') }}">

                    <!-- Danh mục -->
                    <div class="mb-4">
                        <h3 class="font-semibold text-sm mb-2 text-gray-700">Danh mục</h3>
                        <div class="space-y-1">
                            @foreach ($categories as $cat)
                                <div class="category-group relative">
                                    <div
                                        class="flex w-full justify-between items-center bg-white rounded-md overflow-hidden">
                                        <!-- Bấm vào phần này sẽ lọc -->
                                        <button type="button"
                                            class="filter-link text-left text-sm text-black hover:text-red-500 px-3 py-2 w-full"
                                            data-value="{{ $cat->id }}" data-type="category">
                                            {{ $cat->name }}
                                            <span class="text-gray-500">({{ $cat->product_count }})</span>
                                        </button>

                                        @if ($cat->subCategories->isNotEmpty())
                                            <!-- Nút mũi tên để mở dropdown -->
                                            <button type="button" class="toggle-dropdown px-2"
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

                                    <!-- Subcategory dropdown -->
                                    @if ($cat->subCategories->isNotEmpty())
                                        <div id="dropdown-{{ $cat->id }}"
                                            class="dropdown hidden mt-1 ml-4 rounded-md bg-white p-2 space-y-1">
                                            @foreach ($cat->subCategories as $sub)
                                                <button
                                                    class="filter-link block text-sm text-black hover:text-red-500 px-2 py-1 rounded"
                                                    data-value="{{ $sub->id }}" data-type="category">
                                                    -- {{ $sub->name }} <span
                                                        class="text-gray-500">({{ $sub->product_count ?? 0 }})</span>
                                                </button>
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
                                <div class="brand-group relative">
                                    <div
                                        class="flex w-full justify-between items-center bg-white rounded-md overflow-hidden">
                                        <!-- Bấm để lọc thương hiệu cha -->
                                        <button type="button"
                                            class="filter-link text-left text-sm text-black hover:text-red-500 px-3 py-2 w-full"
                                            data-value="{{ $brand->id }}" data-type="brand">
                                            {{ $brand->name }}
                                            <span class="text-gray-500">({{ $brand->product_count }})</span>
                                        </button>

                                        @if ($brand->subBrands->isNotEmpty())
                                            <!-- Nút mũi tên để mở subBrand -->
                                            <button type="button" class="toggle-dropdown px-2"
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
                                            class="dropdown hidden mt-1 ml-4 rounded-md bg-white p-2 space-y-1">
                                            @foreach ($brand->subBrands as $sub)
                                                <button
                                                    class="filter-link block text-sm text-black hover:text-red-500 px-2 py-1 rounded"
                                                    data-value="{{ $sub->id }}" data-type="brand">
                                                    -- {{ $sub->name }}
                                                    <span class="text-gray-500">({{ $sub->product_count ?? 0 }})</span>
                                                </button>
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

                        <!-- Gợi ý mức giá -->
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

                        <!-- Input tự chọn -->
                        <div class="flex gap-2">
                            <input type="number" name="price_min" id="price_min" placeholder="Từ"
                                value="{{ request('price_min') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="number" name="price_max" id="price_max" placeholder="Đến"
                                value="{{ request('price_max') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Nút áp dụng -->
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
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 bg-white p-3 rounded-lg shadow-sm">
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

    <script>
        document.getElementById('filter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            applyFilterAJAX();
        });

        document.querySelectorAll('.filter-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();

                const value = link.getAttribute('data-value');
                const type = link.getAttribute('data-type');
                const form = document.getElementById('filter-form');

                // Reset query
                const queryInput = form.querySelector('input[name="query"]');
                if (queryInput) queryInput.value = '';

                form.querySelectorAll(`input[name="${type}[]"]`).forEach(i => i.remove());

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `${type}[]`;
                input.value = value;
                form.appendChild(input);

                applyFilterAJAX();
            });
        });

        document.querySelectorAll('.price-suggestion').forEach(button => {
            button.addEventListener('click', () => {
                document.getElementById('price_min').value = button.getAttribute('data-min') || '';
                document.getElementById('price_max').value = button.getAttribute('data-max') || '';
                const queryInput = document.querySelector('input[name="query"]');
                if (queryInput) queryInput.value = '';
                applyFilterAJAX();
            });
        });

        document.getElementById('reset-filters')?.addEventListener('click', () => {
            const form = document.getElementById('filter-form');
            const queryInput = form.querySelector('input[name="query"]');
            if (queryInput) queryInput.value = '';
            document.getElementById('price_min').value = '';
            document.getElementById('price_max').value = '';
            form.querySelectorAll('input[name="category[]"], input[name="brand[]"]').forEach(el => el.remove());
            applyFilterAJAX();
        });

        function applyFilterAJAX() {
            const form = document.getElementById('filter-form');
            const url = form.getAttribute('action');
            const formData = new FormData(form);

            fetch(url + '?' + new URLSearchParams(formData).toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('product-results').innerHTML = data.html;
                    window.history.replaceState({}, '', url + '?' + new URLSearchParams(formData).toString());
                });
        }
    </script>
@endsection
