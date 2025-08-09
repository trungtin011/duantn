@extends('user.account.layout')
@section('title', 'Mã giảm giá')
@section('account-content')
    <div class="bg-white rounded-lg shadow-sm border">
        <div
            class="px-4 sm:px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 justify-between">
            <div>
                <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Mã giảm giá đã lưu</h2>
                <p class="text-sm text-gray-600 mt-1">Quản lý và sử dụng các voucher đã lưu</p>
            </div>
            <form method="GET" class="flex items-center gap-2" id="coupon-filter-form">
                <select name="status" id="coupon-status-select" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="available" {{ $status === 'available' ? 'selected' : '' }}>Có thể dùng</option>
                    <option value="used" {{ $status === 'used' ? 'selected' : '' }}>Đã dùng</option>
                    <option value="expired" {{ $status === 'expired' ? 'selected' : '' }}>Hết hạn</option>
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Tất cả</option>
                </select>
            </form>
        </div>

        <div class="p-4 sm:p-6" id="coupon-list-wrapper">
            @if ($saved->isEmpty())
                <div class="text-center text-gray-500 py-10">
                    Chưa có mã giảm giá nào.
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($saved as $item)
                        @php($coupon = $item->coupon)
                        <div class="border rounded-lg p-4 flex flex-col gap-1 items-start">
                            <div class="flex items-center gap-2 w-full">
                                <div
                                    class="w-10 h-10 flex-shrink-0 rounded bg-gray-100 flex items-center justify-center overflow-hidden">
                                    @if ($coupon->image)
                                        <img src="{{ Storage::url($coupon->image) }}" alt="{{ $coupon->name }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xs text-gray-500">VOUCHER</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-semibold text-gray-900 truncate w-[100px]">
                                            {{ $coupon->name ?? $coupon->code }}</h3>
                                        @if ($item->status === 'used')
                                            <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">Đã
                                                dùng</span>
                                        @elseif($item->status === 'expired')
                                            <span class="text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-600">Hết
                                                hạn</span>
                                        @else
                                            <span
                                                class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-600 truncate w-[100px] hover:w-full">Có
                                                thể dùng</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        @if ($coupon->discount_type === 'percentage')
                                            Giảm {{ (float) $coupon->discount_value }}%
                                        @else
                                            Giảm {{ number_format((float) $coupon->discount_value, 0, ',', '.') }}đ
                                        @endif
                                        @if ($coupon->max_discount_amount)
                                            (tối đa {{ number_format((float) $coupon->max_discount_amount, 0, ',', '.') }}đ)
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1 w-full">
                                <p class="text-xs text-gray-500 mt-1">HSD: {{ optional($coupon->end_date)->format('d/m/Y') }}
                                </p>
                                @if ($coupon->min_order_amount)
                                    <p class="text-xs text-gray-500">ĐH tối thiểu:
                                        {{ number_format((float) $coupon->min_order_amount, 0, ',', '.') }}đ</p>
                                @endif
                                @if ($coupon->shop)
                                    <p class="text-xs text-gray-500">Cửa hàng: {{ $coupon->shop->shop_name }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $saved->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectEl = document.getElementById('coupon-status-select');
            const wrapper = document.getElementById('coupon-list-wrapper');
            const form = document.getElementById('coupon-filter-form');

            function fetchCoupons(url) {
                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        wrapper.innerHTML = data.html;
                        bindPagination();
                        window.history.replaceState({}, '', url);
                    })
                    .catch(() => {});
            }

            function buildUrl(page = null) {
                const base = form.getAttribute('action') || window.location.pathname;
                const url = new URL(base, window.location.origin);
                const params = new URLSearchParams(window.location.search);
                params.set('status', selectEl.value);
                if (page) params.set('page', page);
                url.search = params.toString();
                return url.pathname + (url.search ? url.search : '');
            }

            function bindPagination() {
                wrapper.querySelectorAll('nav[role="navigation"] a, .pagination a').forEach(a => {
                    a.addEventListener('click', function(e) {
                        e.preventDefault();
                        if (!this.href) return;
                        fetchCoupons(this.href);
                    });
                });
            }

            if (selectEl) {
                selectEl.addEventListener('change', function() {
                    fetchCoupons(buildUrl(1));
                });
            }

            bindPagination();
        });
    </script>
@endpush
