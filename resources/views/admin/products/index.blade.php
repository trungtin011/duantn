@extends('layouts.admin')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Sản phẩm</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / Danh sách sản phẩm
        </div>
    </div>

    <section class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4 h-[72px]">
            <form class="w-full md:w-[223px] relative" method="GET" action="{{ route('admin.products.index') }}">
                <input name="search"
                    class="w-full h-[42px] border border-[#F2F2F6] rounded-md py-2 pl-10 pr-4 text-xs placeholder:text-gray-400 focus:outline-none"
                    placeholder="Tìm kiếm theo tên sản phẩm" type="text" value="{{ request('search') }}" />
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="shop_id" value="{{ request('shop_id') }}">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">
                    <i class="fas fa-search text-[#55585b]"></i>
                </span>
            </form>

            <div class="flex gap-4 items-center h-full">
                <div class="flex gap-4">
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Trạng thái:</span>
                        <span>Trạng thái:</span>
                        <select name="status" id="statusFilter"
                            class="dropdown border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="">Tất cả</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động
                            </option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt
                            </option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động
                            </option>
                            <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Số lượng thấp
                            </option>
                            <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Hết
                                hàng</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Lên lịch
                            </option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Cửa hàng:</span>
                        <select name="shop_id" id="shopFilter"
                            class="dropdown border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="">Tất cả cửa hàng</option>
                            @foreach ($shops as $shop)
                                <option value="{{ $shop->id }}"
                                    {{ request('shop_id') == $shop->id ? 'selected' : '' }}>
                                    {{ $shop->shop_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Ngày:</span>
                        <input type="date" name="filter_date" id="filterDate" value="{{ request('filter_date') }}"
                            class="border rounded px-3 py-2 text-xs">
                    </div>
                </div>
                <button id="resetFilterBtn" type="button"
                    class="border border-gray-300 text-xs text-white bg-red-500 px-3 py-2 rounded-md hover:bg-red-600 hover:text-white transition-colors"
                    style="display: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                </button>
                <button id="approve-selected"
                    class="hidden bg-green-500 text-white px-4 py-2 rounded-md text-sm hover:bg-green-600">
                    Duyệt đã chọn
                </button>
                <a href="{{ route('admin.products.select-shop') }}"
                    class="h-[44px] text-[15px] bg-blue-600 text-white px-4 py-2 flex items-center justify-center rounded-md hover:bg-blue-700 focus:outline-none">
                    Thêm sản phẩm
                    Thêm sản phẩm
                </a>
            </div>
        </div>

        <table class="w-full text-xs text-left text-gray-400 border-t border-gray-100">
            <thead class="text-gray-300 font-semibold border-b border-gray-100">
                <tr>
                    <th class="w-6 py-3 pr-6">
                        <input id="select-all" class="w-[18px] h-[18px]" aria-label="Chọn tất cả sản phẩm"
                            type="checkbox" />
                    </th>
                    <th class="py-3">Sản phẩm</th>
                    <th class="py-3">Cửa hàng</th>
                    <th class="py-3">Mã sản phẩm</th>
                    <th class="py-3">Số lượng</th>
                    <th class="py-3">Giá</th>
                    <th class="py-3">Trạng thái</th>
                    <th class="py-3 pr-6 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                @foreach ($products as $product)
                    <tr data-product-id="{{ $product->id }}">
                        <td class="py-4 pr-6">
                            <input class="select-item w-[18px] h-[18px]" aria-label="Chọn {{ $product->name }}"
                            <input class="select-item w-[18px] h-[18px]" aria-label="Chọn {{ $product->name }}"
                                type="checkbox" />
                        </td>
                        <td class="py-4 flex items-center gap-4">
                            <img alt="{{ $product->name }} product image" class="w-10 h-10 rounded-md object-cover"
                                height="40" src="{{ $product->image_url }}" width="40" />
                            <span class="font-semibold text-[13px]">
                                {{ $product->name }}
                            </span>
                        </td>
                        <td class="py-4 text-[13px]">
                            {{ $product->shop->shop_name ?? 'N/A' }}
                        </td>
                        <td class="py-4 text-[13px]">{{ $product->sku }}</td>
                        <td class="py-4 text-[13px]">
                            @if ($product->is_variant)
                                {{-- Nếu là sản phẩm biến thể, hiển thị tổng số lượng các biến thể --}}
                                @php
                                    $variantStock = $product->variants->sum('stock');
                                @endphp
                                {{ $variantStock }}
                                @if ($variantStock <= 5 && $variantStock > 0)
                                    <span
                                        class="inline-block bg-orange-100 text-orange-600 text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                        Số lượng thấp
                                    </span>
                                @elseif ($variantStock == 0)
                                    <span
                                        class="inline-block bg-red-100 text-red-600 text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                        Hết hàng
                                    </span>
                                @endif
                            @else
                                {{-- Nếu là sản phẩm đơn, hiển thị số lượng sản phẩm đơn --}}
                                {{ $product->stock_total }}
                                @if ($product->stock_total <= 5 && $product->stock_total > 0)
                                    <span
                                        class="inline-block bg-orange-100 text-orange-600 text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                        Số lượng thấp
                                    </span>
                                @elseif ($product->stock_total == 0)
                                    <span
                                        class="inline-block bg-red-100 text-red-600 text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                        Hết hàng
                                    </span>
                                @endif
                            @endif
                        </td>
                        <td class="py-4 text-[13px]">
                            @if ($product->is_variant)
                                {{-- Nếu là sản phẩm biến thể, hiển thị giá thấp nhất --}}
                                @php
                                    $minPrice = $product->variants->min('sale_price');
                                    $maxPrice = $product->variants->max('sale_price');
                                @endphp
                                @if ($minPrice == $maxPrice)
                                    {{ number_format($minPrice) }} VNĐ
                                @else
                                    Từ {{ number_format($minPrice) }} VNĐ đến {{ number_format($maxPrice) }} VNĐ
                                @endif
                            @else
                                {{ number_format($product->sale_price) }} VNĐ
                            @endif
                        </td>
                        <td class="py-4">
                            <span
                                class="inline-block 
                                {{ $product->status == 'active'
                                    ? 'bg-green-100 text-green-600'
                                    : ($product->status == 'pending'
                                        ? 'bg-yellow-100 text-yellow-600'
                                        : ($product->status == 'inactive'
                                            ? 'bg-red-100 text-red-600'
                                            : ($product->status == 'scheduled'
                                                ? 'bg-blue-100 text-blue-600'
                                                : 'bg-gray-100 text-gray-600'))) }} 
                                text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                {{-- Dịch trạng thái sang tiếng Việt --}}
                                @switch($product->status)
                                    @case('pending')
                                        Chờ duyệt
                                    @break

                                    @case('active')
                                        Hoạt động
                                    @break

                                    @case('inactive')
                                        Không hoạt động
                                    @break

                                    @case('scheduled')
                                        Lên lịch
                                    @break

                                    @case('low_stock')
                                        Số lượng thấp
                                    @break

                                    @case('out_of_stock')
                                        Hết hàng
                                    @break

                                    @case('draft')
                                        Bản nháp
                                    @break

                                    @default
                                        {{ ucfirst($product->status) }}
                                @endswitch
                            </span>
                        </td>
                        <td class="py-4 pr-6 text-right flex items-center gap-2 justify-end">
                            @if ($product->status == 'pending')
                                <form action="{{ route('admin.products.approve', $product->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    <button type="submit" aria-label="Duyệt {{ $product->name }}"
                                        class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-md focus:outline-none">
                                        <i class="fas fa-check text-xs"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.products.reject', $product->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    <button type="submit" aria-label="Từ chối {{ $product->name }}"
                                        class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md focus:outline-none">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-md focus:outline-none">
                                <i class="fas fa-pencil-alt text-xs"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="Xóa {{ $product->name }}"
                                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md focus:outline-none">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if ($products->isEmpty())
                    <tr>
                        <td colspan="8" class="text-center text-gray-400 py-4">
                            @if (request('search') || request('status') || request('shop_id'))
                                Không tìm thấy sản phẩm nào phù hợp với bộ lọc hiện tại
                            @else
                                Không tìm thấy sản phẩm nào
                            @endif
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="mt-6 flex items-center justify-between text-[11px] text-gray-500 select-none">
            <div>
                Hiển thị {{ $products->count() }} sản phẩm trên {{ $products->total() }} sản phẩm
            </div>
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusFilter = document.getElementById('statusFilter');
            const shopFilter = document.getElementById('shopFilter');
            const searchInput = document.querySelector('input[name="search"]');
            const filterDate = document.getElementById('filterDate');
            const resetFilterBtn = document.getElementById('resetFilterBtn');
            const tbody = document.querySelector('table tbody');

            function checkShowResetBtn() {
                const hasFilter =
                    (searchInput && searchInput.value) ||
                    (statusFilter && statusFilter.value) ||
                    (shopFilter && shopFilter.value) ||
                    (filterDate && filterDate.value);

                if (resetFilterBtn) {
                    resetFilterBtn.style.display = hasFilter ? 'inline-flex' : 'none';
                }
            }

            function submitFilters() {
                const params = new URLSearchParams();
                if (searchInput && searchInput.value) params.append('search', searchInput.value);
                if (statusFilter && statusFilter.value) params.append('status', statusFilter.value);
                if (shopFilter && shopFilter.value) params.append('shop_id', shopFilter.value);
                if (filterDate && filterDate.value) params.append('filter_date', filterDate.value);

                fetch("{{ route('admin.products.ajax') }}?" + params.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.text())
                    .then(html => {
                        tbody.innerHTML = html;
                        checkShowResetBtn();
                    });
            }

            if (statusFilter) statusFilter.addEventListener('change', function() {
                submitFilters();
                checkShowResetBtn();
            });
            if (shopFilter) shopFilter.addEventListener('change', function() {
                submitFilters();
                checkShowResetBtn();
            });
            if (filterDate) filterDate.addEventListener('change', function() {
                submitFilters();
                checkShowResetBtn();
            });
            if (searchInput) searchInput.addEventListener('input', function() {
                clearTimeout(this._timer);
                this._timer = setTimeout(function() {
                    submitFilters();
                    checkShowResetBtn();
                }, 400);
            });
            if (resetFilterBtn) {
                resetFilterBtn.addEventListener('click', function() {
                    if (searchInput) searchInput.value = '';
                    if (statusFilter) statusFilter.value = '';
                    if (shopFilter) shopFilter.value = '';
                    if (filterDate) filterDate.value = '';
                    submitFilters();
                    checkShowResetBtn();
                });
                checkShowResetBtn();
            }
        });
    </script>
@endsection
