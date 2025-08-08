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

                    <!-- Danh mục và Thương hiệu trong cùng khu vực -->
                    <div class="mb-4">
                        <h3 class="font-semibold text-sm mb-2 text-gray-700">Danh mục</h3>
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

                        <h3 class="font-semibold text-sm mt-4 mb-2 text-gray-700">Thương hiệu</h3>
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
            
            <div class="w-full lg:w-4/5">
                @if ($advertisedProductsByShop->isNotEmpty())
                    @include('partials.advertised_products', [
                        'advertisedProductsByShop' => $advertisedProductsByShop,
                    ])
                @endif
                
                <!-- Kết quả tìm kiếm -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 bg-white p-3 rounded-lg shadow-lg">
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
                    @include('partials.product_list', [
                        'products' => $products,
                    ])
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('filter-form');

                // Gắn sự kiện change cho checkbox danh mục
                document.querySelectorAll('input[name="category[]"]').forEach(cb => {
                    cb.addEventListener('change', () => {
                        form.submit();
                    });
                });

                // Gắn sự kiện change cho checkbox thương hiệu
                document.querySelectorAll('input[name="brand[]"]').forEach(cb => {
                    cb.addEventListener('change', () => {
                        form.submit();
                    });
                });

                // Gợi ý khoảng giá
                document.querySelectorAll('.price-suggestion').forEach(button => {
                    button.addEventListener('click', () => {
                        document.getElementById('price_min').value = button.getAttribute('data-min') ||
                            '';
                        document.getElementById('price_max').value = button.getAttribute('data-max') ||
                            '';
                        form.submit();
                    });
                });

                // Nút reset
                document.getElementById('reset-filters')?.addEventListener('click', () => {
                    const form = document.getElementById('filter-form');
                    form.querySelectorAll('.filter-checkbox').forEach(cb => cb.checked = false);
                    document.getElementById('price_min').value = '';
                    document.getElementById('price_max').value = '';
                    form.submit();
                    document.getElementById('reset-filters').classList.add('hidden');
                });
            });
        </script>
    @endpush
@endsection
