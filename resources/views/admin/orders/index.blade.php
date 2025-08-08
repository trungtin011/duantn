@extends('layouts.admin')

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
                <form method="GET" action="{{ route('admin.orders.index') }}">
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Trạng thái:</span>
                        <select name="status" id="statusFilter"
                            class="dropdown px-3 py-2 text-gray-600 text-xs focus:outline-none w-[100px]">
                            <option value="">Tất cả</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận
                            </option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý
                            </option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đang giao hàng
                            </option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao hàng
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy
                            </option>
                            <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền
                            </option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <table class="w-full text-xs text-left text-gray-400 border-t border-gray-100">
            <thead class="text-gray-300 font-semibold border-b border-gray-100">
                <tr>
                    <th class="w-6 py-3 pr-6">
                        <input id="select-all" class="w-[18px] h-[18px]" aria-label="Chọn tất cả đơn hàng" type="checkbox" />
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
                    <tr>
                        <td class="py-4 pr-6">
                            <input class="select-item w-[18px] h-[18px]" aria-label="Chọn {{ $order->order_code }}"
                                type="checkbox" />
                        </td>
                        <td class="py-4 text-[13px]">{{ $order->order_code }}</td>
                        <td class="py-4 text-[13px]">{{ $order->user ? $order->user->fullname : 'Khách vãng lai' }}</td>
                        <td class="py-4 text-[13px]">{{ $order->shop ? $order->shop->shop_name : 'Không xác định' }}</td>
                        <td class="py-4 text-[13px]">{{ $order->items->sum('quantity') }}</td>
                        <td class="py-4 text-[13px]">{{ number_format($order->final_price, 2) }} VNĐ</td>
                        <td class="py-4">
                                                            <span
                                    class="inline-block {{ $order->order_status == 'pending' ? 'bg-yellow-100 text-yellow-600' : ($order->order_status == 'processing' ? 'bg-blue-100 text-blue-600' : ($order->order_status == 'shipped' ? 'bg-purple-100 text-purple-600' : ($order->order_status == 'delivered' ? 'bg-green-100 text-green-600' : ($order->order_status == 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600')))) }} text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                {{ $order->order_status == 'pending' ? 'Chờ xác nhận' : ($order->order_status == 'processing' ? 'Đang xử lý' : ($order->order_status == 'shipped' ? 'Đang giao hàng' : ($order->order_status == 'delivered' ? 'Đã giao hàng' : ($order->order_status == 'cancelled' ? 'Đã hủy' : 'Đã hoàn tiền')))) }}
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
                        <td colspan="9" class="text-center text-gray-400 py-4">Không tìm thấy đơn hàng nào</td>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
