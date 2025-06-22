@extends('layouts.admin')

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Chi tiết đơn hàng</h1>
        <div class="admin-breadcrumb">
            <a href="{{ route('admin.orders.index') }}" class="admin-breadcrumb-link">Đơn hàng</a> / Chi tiết đơn hàng
            #{{ $order->order_code }}
        </div>
    </div>

    <!-- Thông báo -->
    @include('layouts.notification')

    <section class="bg-white rounded-lg shadow-sm py-3 px-4 mb-[21px] flex justify-between items-center">
        <div class="flex flex-col">
            <h2 class="text-md mb-1">Mã đơn hàng: #{{ $order->order_code }}</h2>
            <div class="flex items-center gap-2 text-gray-700">
                <span class="text-xs">
                    Đơn hàng được tạo: {{ $order->created_at->format('d/m/Y H:i') }}
                </span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <form class="flex items-center gap-2" id="update-status-form"
                action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mr-3">
                    <span class="text-xs">Trạng thái đơn hàng:</span>
                    <select name="status" id="status" class="text-xs font-semibold focus:outline-none">
                        <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>
                            Chờ xử lý
                        </option>
                        <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>
                            Đang xử lý
                        </option>
                        <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>
                            Đang giao hàng
                        </option>
                        <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>
                            Đã giao hàng
                        </option>
                        <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>
                            Hủy đơn hàng
                        </option>
                        <option value="refunded" {{ $order->order_status == 'refunded' ? 'selected' : '' }}>
                            Hoàn tiền
                        </option>
                    </select>
                </div>
                <input type="hidden" name="description" id="description">
                <input type="hidden" name="shipping_provider" id="shipping_provider">
                <input type="hidden" name="cancel_reason" id="cancel_reason">
                <input type="hidden" name="note" id="status-note">
                <button type="submit"
                    class="text-md bg-[#0989ff] text-white px-4 py-2 rounded-md hover:bg-[#056ECE]">Lưu</button>
            </form>
        </div>
    </section>

    <div class="mb-[21px] grid grid-cols-1 md:grid-cols-3 gap-6">
        <section class="bg-white rounded-lg shadow-sm py-4 px-4">
            <div>
                <h3 class="text-md font-semibold text-gray-800 mb-3">Chi tiết khách hàng</h3>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center justify-between gap-2 border-b border-gray-100 pb-3">
                        <p class="text-sm text-gray-500">Họ tên</p>
                        <p class="text-sm text-gray-900 flex items-center gap-2">
                            <img src="{{ $order->user && $order->user->avatar ? (filter_var($order->user->avatar, FILTER_VALIDATE_URL) ? $order->user->avatar : asset($order->user->avatar)) : asset('images/icon-7797704_640.png') }}"
                                alt="{{ $order->user ? $order->user->fullname : 'Khách vãng lai' }}"
                                class="w-6 h-6 rounded-full object-cover"
                                onerror="this.src='{{ asset('images/icon-7797704_640.png') }}'">
                            {{ $order->user ? $order->user->fullname : 'Khách vãng lai' }}
                        </p>
                    </div>
                    <div class="flex items-center justify-between gap-2 border-b border-gray-100 pb-3">
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="text-sm text-gray-900">{{ $order->user ? $order->user->email : 'N/A' }}</p>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm text-gray-500">Số điện thoại</p>
                        <p class="text-sm text-gray-900">{{ $order->user ? $order->user->phone : 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="bg-white rounded-lg shadow-sm py-4 px-4">
            <div>
                <h3 class="text-md font-semibold text-gray-800 mb-3">Tóm tắt đơn hàng</h3>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center justify-between gap-2 border-b border-gray-100 pb-3">
                        <p class="text-sm text-gray-500">Ngày đặt hàng </p>
                        <p class="text-sm text-gray-900">{{ $order->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div class="flex items-center justify-between gap-2 border-b border-gray-100 pb-3">
                        <p class="text-sm text-gray-500">Phương thức thanh toán</p>
                        <p class="text-sm text-gray-900">{{ $order->payment_method }}</p>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm text-gray-500">Phương thức vận chuyển</p>
                        <p class="text-sm text-gray-900">
                            @if ($order->payment_method == 'cod')
                                Thanh toán khi nhận hàng
                            @else
                                {{ $order->payment_method }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <section class="bg-white rounded-lg shadow-sm py-4 px-4">
            <div>
                <h3 class="text-md font-semibold text-gray-800 mb-3">Giao hàng đến</h3>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center justify-between gap-2 border-b border-gray-100 pb-3">
                        <p class="text-sm text-gray-500">Căn nhà</p>
                        <p class="text-sm text-gray-900">{{ $order->address->address }}, {{ $order->address->ward }},
                            {{ $order->address->district }}, {{ $order->address->province }}</p>
                    </div>
                    <div class="flex items-center justify-between gap-2 border-b border-gray-100 pb-3">
                        <p class="text-sm text-gray-500">Đường phố</p>
                        <p class="text-sm text-gray-900">{{ $order->address->address }}</p>
                    </div>
                    <div class="flex items-center justify-between gap-2 border-b border-gray-100 pb-3">
                        <p class="text-sm text-gray-500">Tình trạng</p>
                        <p class="text-sm text-gray-900">{{ $order->address->address_type }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="mb-[21px] grid grid-cols-1 md:grid-cols-3 gap-6">
        <section class="col-span-2 bg-white rounded-lg shadow-sm py-3 px-4 mb-[21px]">
            <div class="mb-6">
                <h3 class="text-md font-semibold text-gray-800 mb-3">Danh sách sản phẩm ({{ $order->items->count() }} sản
                    phẩm)</h3>
                @if ($order->items->isNotEmpty())
                    <table class="w-full text-xs text-left text-gray-400">
                        <thead class="text-[#C5C8D4] uppercase font-semibold">
                            <tr>
                                <th class="py-3 text-left">Sản phẩm</th>
                                <th class="py-3 text-right">Đơn giá</th>
                                <th class="py-3 text-right">Số lượng</th>
                                <th class="py-3 text-right">Tổng cộng</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-900 font-normal border-t border-[#eff2f5]">
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="py-4 flex items-center gap-4">
                                        <img alt="{{ $item->product_name }} image"
                                            class="w-10 h-10 rounded-md object-cover" height="40"
                                            src="{{ $item->product_image }}" width="40" />
                                        <span class="font-semibold text-[13px]">{{ $item->product_name }}</span>
                                    </td>
                                    <td class="py-4 text-[13px] text-right">{{ number_format($item->unit_price) }} VNĐ</td>
                                    <td class="py-4 text-[13px] text-right">{{ $item->quantity }}</td>
                                    <td class="py-4 text-[13px] text-right">{{ number_format($item->total_price) }} VNĐ
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-sm text-gray-500">Không có sản phẩm nào trong đơn hàng này.</p>
                @endif
            </div>
        </section>
        <section class="col-span-1 bg-white rounded-lg shadow-sm py-4 px-4 mb-[21px]">
            <div>
                <h3 class="text-md font-semibold text-gray-800 mb-3">Giá đơn hàng</h3>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center justify-between gap-2 border-b border-gray-100 pb-3">
                        <p class="text-sm text-gray-500">Tổng cộng</p>
                        <p class="text-sm text-gray-900">{{ number_format($order->total_price) }} VNĐ</p>
                    </div>
                    <div class="flex items-center justify-between gap-2 border-b border-gray-100 pb-3">
                        <p class="text-sm text-gray-500">Phí vận chuyển</p>
                        <p class="text-sm text-gray-900">{{ number_format($order->shipping_fee) }} VNĐ</p>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm text-gray-500">Tổng cộng</p>
                        <p class="text-[16px] text-gray-900 font-semibold">
                            {{ number_format($order->total_price + $order->shipping_fee) }} VNĐ
                        </p>
                    </div>
                </div>
            </div>

            <!-- Thêm phần Hoàn tiền đơn hàng -->
            @if (in_array($order->order_status, ['delivered', 'cancelled']))
                <div class="mt-6">
                    <h3 class="text-md font-semibold text-gray-800 mb-3">Hoàn tiền đơn hàng</h3>
                    <form action="{{ route('admin.orders.refund', $order->id) }}" method="POST">
                        @csrf
                        <p class="text-xs text-gray-500 mb-3">Hoàn tiền đơn hàng nếu cần thiết.</p>
                        <button type="submit"
                            class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none">
                            Hoàn tiền đơn hàng
                        </button>
                    </form>
                </div>
            @endif
        </section>
    </div>
@endsection
