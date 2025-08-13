@extends('layouts.seller_home')

@section('title', 'Quản lý đơn hàng')

@section('content')
    <div class="admin-page-header mb-5">
        <h1 class="admin-page-title text-2xl">Đơn hàng</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / Danh sách đơn hàng</div>
    </div>

    @include('layouts.notification')

    <section class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4 h-[72px]">
            <form class="w-full md:w-[223px] relative" method="GET" action="{{ route('seller.order.index') }}">
                <input name="search"
                    class="w-full h-[42px] border border-[#F2F2F6] rounded-md py-2 pl-10 pr-4 text-xs placeholder:text-gray-400 focus:outline-none"
                    placeholder="Tìm kiếm đơn hàng" type="text" value="{{ request('search') }}" />
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">
                    <i class="fas fa-search text-[#55585b]"></i>
                </span>
            </form>

            <div class="flex gap-4 items-center h-full">
                <div class="flex gap-4">
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Trạng thái:</span>
                        <select name="status" id="statusFilter"
                            class="dropdown border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="">Tất cả</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="ready_to_pick" {{ request('status') == 'ready_to_pick' ? 'selected' : '' }}>Sẵn sàng lấy hàng</option>
                            <option value="picked" {{ request('status') == 'picked' ? 'selected' : '' }}>Đã lấy hàng</option>
                            <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            <option value="shipping_failed" {{ request('status') == 'shipping_failed' ? 'selected' : '' }}>Giao hàng thất bại</option>
                            <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Đã trả hàng</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="damage" {{ request('status') == 'damage' ? 'selected' : '' }}>Hư hỏng</option>
                            <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Thất lạc</option>
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

        @if ($orders->isEmpty())
            <div class="text-center text-gray-400 py-8">
                <i class="fas fa-box-open text-4xl mb-4"></i>
                <p>
                    @if (request('search') || request('status') || request('filter_date'))
                        Không tìm thấy đơn hàng nào phù hợp với bộ lọc hiện tại
                    @else
                        Không có đơn hàng nào.
                    @endif
                </p>
            </div>
        @else
            <table class="w-full text-xs text-left text-gray-400 border-t border-gray-100">
                <thead class="text-gray-300 font-semibold border-b border-gray-100">
                    <tr>
                        <th class="py-3">Mã đơn hàng</th>
                        <th class="py-3">Khách hàng</th>
                        <th class="py-3">Tổng tiền</th>
                        <th class="py-3">Sản phẩm</th>
                        <th class="py-3">Trạng thái</th>
                        <th class="py-3">Ngày đặt</th>
                        <th class="py-3 pr-6 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                    @include('seller.order._table_body', ['orders' => $orders])
                </tbody>
            </table>

            <div class="mt-6 flex items-center justify-between text-[11px] text-gray-500 select-none">
                <div>
                    Hiển thị {{ $orders->count() }} đơn hàng trên {{ $orders->total() }} đơn hàng
                </div>
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusFilter = document.getElementById('statusFilter');
            const searchInput = document.querySelector('input[name="search"]');
            const filterDate = document.getElementById('filterDate');
            const resetFilterBtn = document.getElementById('resetFilterBtn');
            const tbody = document.querySelector('table tbody');

            function checkShowResetBtn() {
                const hasFilter =
                    (searchInput && searchInput.value) ||
                    (statusFilter && statusFilter.value) ||
                    (filterDate && filterDate.value);

                if (resetFilterBtn) {
                    resetFilterBtn.style.display = hasFilter ? 'inline-flex' : 'none';
                }
            }

            function submitFilters() {
                const params = new URLSearchParams();
                if (searchInput && searchInput.value) params.append('search', searchInput.value);
                if (statusFilter && statusFilter.value) params.append('status', statusFilter.value);
                if (filterDate && filterDate.value) params.append('filter_date', filterDate.value);

                fetch("{{ route('seller.order.ajax') }}?" + params.toString(), {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(res => res.text())
                    .then(html => {
                        if (tbody) tbody.innerHTML = html;
                        // Update URL without full reload for UX
                        const currentUrl = new URL(window.location);
                        ['search','status','filter_date'].forEach(key => currentUrl.searchParams.delete(key));
                        params.forEach((value, key) => currentUrl.searchParams.set(key, value));
                        window.history.replaceState({}, '', currentUrl.toString());
                    });
            }

            if (statusFilter) statusFilter.addEventListener('change', function() {
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
                    if (filterDate) filterDate.value = '';
                    submitFilters();
                    checkShowResetBtn();
                });
                checkShowResetBtn();
            }
        });
    </script>
@endsection
