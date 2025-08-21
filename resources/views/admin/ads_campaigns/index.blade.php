@extends('layouts.admin')

@section('title', 'Chiến dịch quảng cáo')

@section('content')
    <div class="admin-page-header mb-5">
        <h1 class="admin-page-title">Chiến dịch quảng cáo</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / Duyệt quảng cáo</div>
    </div>

    <section class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4 h-[72px]">
            <form class="w-full md:w-[223px] relative" onsubmit="return false;">
                <input name="search" id="searchInput"
                    class="w-full h-[42px] border border-[#F2F2F6] rounded-md py-2 pl-10 pr-4 text-xs placeholder:text-gray-400 focus:outline-none"
                    placeholder="Tìm kiếm theo tên chiến dịch" type="text" value="{{ $search ?? '' }}" />
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">
                    <i class="fas fa-search text-[#55585b]"></i>
                </span>
            </form>

            <div class="flex gap-4 items-center h-full">
                <div class="flex gap-4">
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Trạng thái:</span>
                        <select id="statusFilter"
                            class="dropdown border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="">Tất cả</option>
                            <option value="active" {{ ($status ?? '') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="pending" {{ ($status ?? '') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                            <option value="ended" {{ ($status ?? '') == 'ended' ? 'selected' : '' }}>Đã kết thúc</option>
                            <option value="cancelled" {{ ($status ?? '') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Cửa hàng:</span>
                        <select id="shopFilter"
                            class="dropdown border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="">Tất cả cửa hàng</option>
                            @foreach ($shops as $shop)
                                <option value="{{ $shop->id }}" {{ ($shopId ?? '') == $shop->id ? 'selected' : '' }}>
                                    {{ $shop->shop_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Ngày:</span>
                        <input type="date" id="filterDate" value="{{ $filterDate ?? '' }}"
                            class="border rounded px-3 py-2 text-xs">
                    </div>
                </div>
                <button id="resetFilterBtn" type="button"
                    class="border border-gray-300 text-xs text-white bg-red-500 px-3 py-2 rounded-md hover:bg-red-600 hover:text-white transition-colors"
                    style="display:none;">
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
                    <th class="w-6 py-3 pr-6">
                        <input id="select-all" class="w-[18px] h-[18px]" type="checkbox" />
                    </th>
                    <th class="py-3">ID</th>
                    <th class="py-3">Tên chiến dịch</th>
                    <th class="py-3">Cửa hàng</th>
                    <th class="py-3">Thời gian</th>
                    <th class="py-3">Giá thầu</th>
                    <th class="py-3">Trạng thái</th>
                    <th class="py-3 pr-6 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody id="campaignTbody" class="divide-y divide-gray-100 text-gray-900 font-normal">
                @include('admin.ads_campaigns._table_body', ['campaigns' => $campaigns])
            </tbody>
        </table>
        <div class="mt-6 flex items-center justify-between text-[11px] text-gray-500 select-none">
            <div>
                Hiển thị {{ $campaigns->count() }} chiến dịch trên {{ $campaigns->total() }} chiến dịch
            </div>
            {{ $campaigns->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusFilter = document.getElementById('statusFilter');
            const shopFilter = document.getElementById('shopFilter');
            const searchInput = document.getElementById('searchInput');
            const filterDate = document.getElementById('filterDate');
            const resetFilterBtn = document.getElementById('resetFilterBtn');
            const tbody = document.getElementById('campaignTbody');

            function checkShowResetBtn() {
                const hasFilter =
                    (searchInput && searchInput.value) ||
                    (statusFilter && statusFilter.value) ||
                    (shopFilter && shopFilter.value) ||
                    (filterDate && filterDate.value);
                resetFilterBtn.style.display = hasFilter ? 'inline-flex' : 'none';
            }

            function submitFilters() {
                const params = new URLSearchParams();
                if (searchInput && searchInput.value) params.append('search', searchInput.value);
                if (statusFilter && statusFilter.value) params.append('status', statusFilter.value);
                if (shopFilter && shopFilter.value) params.append('shop_id', shopFilter.value);
                if (filterDate && filterDate.value) params.append('filter_date', filterDate.value);

                fetch("{{ route('admin.ads_campaigns.ajax') }}?" + params.toString(), {
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

            if (statusFilter) statusFilter.addEventListener('change', () => {
                submitFilters();
                checkShowResetBtn();
            });
            if (shopFilter) shopFilter.addEventListener('change', () => {
                submitFilters();
                checkShowResetBtn();
            });
            if (filterDate) filterDate.addEventListener('change', () => {
                submitFilters();
                checkShowResetBtn();
            });
            if (searchInput) searchInput.addEventListener('input', function() {
                clearTimeout(this._timer);
                this._timer = setTimeout(() => {
                    submitFilters();
                    checkShowResetBtn();
                }, 400);
            });
            if (resetFilterBtn) resetFilterBtn.addEventListener('click', function() {
                if (searchInput) searchInput.value = '';
                if (statusFilter) statusFilter.value = '';
                if (shopFilter) shopFilter.value = '';
                if (filterDate) filterDate.value = '';
                submitFilters();
                checkShowResetBtn();
            });

            checkShowResetBtn();
        });
    </script>
@endsection
