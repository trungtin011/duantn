@extends('layouts.admin')

@section('title', 'Quản lý đánh giá')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/reviews.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Quản lý đánh giá</h1>
        <div class="admin-breadcrumb"><a href="{{ route('admin.dashboard') }}" class="admin-breadcrumb-link">Trang chủ</a> / Danh sách đánh giá
        </div>
    </div>

    <section class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4 h-[72px]">
            <form class="w-full md:w-[223px] relative" method="GET" action="{{ route('admin.reviews.index') }}">
                <input name="search"
                    class="w-full h-[42px] border border-[#F2F2F6] rounded-md py-2 pl-10 pr-4 text-xs placeholder:text-gray-400 focus:outline-none"
                    placeholder="Tìm kiếm đánh giá" type="text" value="{{ request('search') }}" />
                <input type="hidden" name="rating" value="{{ request('rating') }}">
                <input type="hidden" name="shop_id" value="{{ request('shop_id') }}">
                <input type="hidden" name="filter_date" value="{{ request('filter_date') }}">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">
                    <i class="fas fa-search text-[#55585b]"></i>
                </span>
            </form>

            <div class="flex gap-4 items-center h-full">
                <div class="flex gap-4">
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Đánh giá:</span>
                        <select name="rating" id="ratingFilter"
                            class="dropdown border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="">Tất cả sao</option>
                            @for ($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                            @endfor
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
            </div>
        </div>

        <table class="w-full text-xs text-left text-gray-400 border-t border-gray-100">
            <thead class="text-gray-300 font-semibold border-b border-gray-100">
                <tr>
                    <th class="py-3">Sản phẩm</th>
                    <th class="py-3">Khách hàng</th>
                    <th class="py-3">Đánh giá</th>
                    <th class="py-3">Cửa hàng</th>
                    <th class="py-3">Ngày</th>
                    <th class="py-3">Phản hồi seller</th>
                    <th class="py-3 pr-6 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-900 font-normal" id="reviews-table-body">
                @include('admin.reviews._table_body', ['reviews' => $reviews])
            </tbody>
        </table>
        <div class="mt-6 flex items-center justify-between text-[11px] text-gray-500 select-none">
            <div>
                Hiển thị {{ $reviews->count() }} đánh giá trên {{ $reviews->total() }} đánh giá
            </div>
            {{ $reviews->links('pagination::bootstrap-5') }}
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ratingFilter = document.getElementById('ratingFilter');
            const shopFilter = document.getElementById('shopFilter');
            const searchInput = document.querySelector('input[name="search"]');
            const filterDate = document.getElementById('filterDate');
            const resetFilterBtn = document.getElementById('resetFilterBtn');
            const tbody = document.getElementById('reviews-table-body');

            function checkShowResetBtn() {
                const hasFilter =
                    (searchInput && searchInput.value) ||
                    (ratingFilter && ratingFilter.value) ||
                    (shopFilter && shopFilter.value) ||
                    (filterDate && filterDate.value);

                if (resetFilterBtn) {
                    resetFilterBtn.style.display = hasFilter ? 'inline-flex' : 'none';
                }
            }

            function submitFilters() {
                const params = new URLSearchParams();
                if (searchInput && searchInput.value) params.append('search', searchInput.value);
                if (ratingFilter && ratingFilter.value) params.append('rating', ratingFilter.value);
                if (shopFilter && shopFilter.value) params.append('shop_id', shopFilter.value);
                if (filterDate && filterDate.value) params.append('filter_date', filterDate.value);

                fetch("{{ route('admin.reviews.ajax') }}?" + params.toString(), {
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

            if (ratingFilter) ratingFilter.addEventListener('change', function() {
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
                    if (ratingFilter) ratingFilter.value = '';
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
