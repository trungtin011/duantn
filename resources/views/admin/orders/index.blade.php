@extends('layouts.admin')

@section('title', 'Đơn hàng')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/order.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Đơn hàng</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / Danh sách đơn hàng</div>
    </div>

    <section class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4 h-[72px]">
            <form class="w-full md:w-[223px] relative" method="GET" action="{{ route('admin.orders.index') }}">
                <input name="search"
                    class="w-full h-[42px] border border-[#F2F2F6] rounded-md py-2 pl-10 pr-4 text-xs placeholder:text-gray-400 focus:outline-none"
                    placeholder="Tìm kiếm theo mã đơn hàng hoặc khách hàng" type="text"
                    value="{{ request('search') }}" />
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
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận
                            </option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận
                            </option>
                            <option value="ready_to_pick" {{ request('status') == 'ready_to_pick' ? 'selected' : '' }}>Sẵn
                                sàng lấy hàng
                            </option>
                            <option value="picked" {{ request('status') == 'picked' ? 'selected' : '' }}>Đã lấy hàng
                            </option>
                            <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang giao hàng
                            </option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao hàng
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy
                            </option>
                            <option value="shipping_failed" {{ request('status') == 'shipping_failed' ? 'selected' : '' }}>
                                Giao hàng thất bại
                            </option>
                            <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Đã trả hàng
                            </option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành
                            </option>
                            <option value="damage" {{ request('status') == 'damage' ? 'selected' : '' }}>Hư hỏng
                            </option>
                            <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Thất lạc
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
            </div>
        </div>

        <table class="w-full text-xs text-left text-gray-400 border-t border-gray-100">
            <thead class="text-gray-300 font-semibold border-b border-gray-100">
                <tr>
                    <th class="w-6 py-3 pr-6">
                        <input id="select-all" class="w-[18px] h-[18px]" aria-label="Select all orders" type="checkbox" />
                    </th>
                    <th class="py-3">Mã đơn hàng</th>
                    <th class="py-3">Khách hàng</th>
                    <th class="py-3">Cửa hàng</th>
                    <th class="py-3">Số lượng</th>
                    <th class="py-3">Tổng giá</th>
                    <th class="py-3">Trạng thái</th>
                    <th class="py-3">Ngày đặt hàng</th>
                    <th class="py-3 pr-6 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                @foreach ($orders as $order)
                    <tr data-order-id="{{ $order->id }}">
                        <td class="py-4 pr-6">
                            <input class="select-item w-[18px] h-[18px]" aria-label="Chọn {{ $order->order_code }}"
                                type="checkbox" />
                        </td>
                        <td class="py-4 text-[13px]">{{ $order->order_code }}</td>
                        <td class="py-4 text-[13px]">{{ $order->user ? $order->user->fullname : 'Khách vãng lai' }}</td>
                        <td class="py-4 text-[13px]">
                            {{ optional($order->shopOrders->first())->shop ? $order->shopOrders->first()->shop->shop_name : 'Không xác định' }}
                        </td>
                        <td class="py-4 text-[13px]">{{ $order->items->sum('quantity') }}</td>
                        <td class="py-4 text-[13px]">{{ number_format($order->final_price, 2) }} VNĐ</td>
                        <td class="py-4">
                            <span
                                class="inline-block {{ $order->order_status == 'pending' ? 'bg-yellow-100 text-yellow-600' : ($order->order_status == 'processing' ? 'bg-blue-100 text-blue-600' : ($order->order_status == 'shipped' ? 'bg-purple-100 text-purple-600' : ($order->order_status == 'delivered' ? 'bg-green-100 text-green-600' : ($order->order_status == 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600')))) }} text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </td>
                        <td class="py-4 text-[13px]">{{ $order->created_at->format('d/m/Y') }}</td>
                        <td class="py-4 pr-6 flex items-center justify-end">
                            <div
                                class="bg-[#f2f2f6] hover:bg-[#0B8AFF] hover:text-white w-[37px] h-[35px] rounded-md flex items-center justify-center">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="transition-all duration-300">
                                    <i class="fas fa-eye" title="Xem chi tiết"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                @if ($orders->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center text-gray-400 py-4">No orders found</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="mt-6 flex items-center justify-between text-[11px] text-gray-500 select-none">
            <div>
                Hiển thị {{ $orders->count() }} đơn hàng trên {{ $orders->total() }} đơn hàng
            </div>
            {{ $orders->links('pagination::bootstrap-5') }}
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

                fetch("{{ route('admin.orders.ajax') }}?" + params.toString(), { // đổi route cho orders
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

<?php
Route::get('admin/orders/ajax', [AdminOrderController::class, 'ajaxList'])->name('admin.orders.ajax');
Route::get('admin/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
?>
