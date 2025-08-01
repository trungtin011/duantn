@extends('user.account.layout')

@section('account-content')
    <div class="container mx-auto py-5 px-3 sm:px-0">
        @include('layouts.notification')

        <!-- Breadcrumb -->
        <div class="flex flex-wrap items-center gap-2 mb-6 sm:mb-10 text-sm md:text-base">
            <a href="{{ route('order_history') }}" class="text-gray-500 hover:underline">Đơn hàng</a>
            <span>/</span>
            <span>Chi tiết đơn hàng</span>
        </div>

        <!-- Nút quay lại -->
        <div class="mb-4 bg-gray-100 shadow-sm rounded-lg p-3">
            <a href="{{ route('order_history') }}" class="text-sm text-blue-600 hover:text-red-500">
                <i class="fa fa-arrow-left mr-1"></i> Quản lý đơn hàng
            </a>
        </div>

        <!-- Thông tin đơn hàng -->
        <div class="mb-4">
            <div class="bg-white shadow-sm rounded-lg p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-1">Chi tiết đơn hàng</h2>
                    <p class="text-gray-600 text-sm">Mã: {{ $order->order_code ?? 'N/A' }} | Ngày tạo:
                        {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p class="text-sm text-gray-600">
                        <strong>Trạng thái:</strong>
                        <span class="{{ $order->order_status === 'delivered' ? 'text-green-600' : ($order->order_status === 'cancelled' || $order->order_status === 'refunded' ? 'text-red-600' : 'text-blue-600') }}">
                            {{ __('order_status.' . $order->order_status) }}
                        </span>
                    </p>
                    @if ($order->cancel_reason)
                        <p class="text-sm text-gray-600"><strong>Lý do hủy:</strong> {{ e($order->cancel_reason) }}</p>
                    @endif
                </div>
                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    @if (in_array($order->order_status, ['pending', 'processing']))
                        <button class="open-cancel-modal bg-red-500 text-white px-4 py-2 rounded text-sm w-full sm:w-auto">
                            Hủy đơn hàng
                        </button>
                    @endif
                    @if (in_array($order->order_status, ['cancelled', 'refunded']))
                        <form action="{{ route('user.order.reorder', $order->id) }}" method="GET" class="w-full sm:w-auto">
                            @csrf
                            <button type="submit"
                                class="bg-blue-500 text-white px-4 py-2 rounded text-sm w-full sm:w-auto">
                                Mua lại
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal hủy đơn hàng -->
        @if (in_array($order->order_status, ['pending', 'processing']))
            <div id="cancelModal-{{ $order->id }}"
                class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
                <div class="bg-white rounded-lg p-5 w-full max-w-md mx-3">
                    <h3 class="text-lg font-semibold mb-4">Hủy đơn hàng</h3>
                    <form action="{{ route('user.order.cancel', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label for="cancel_reason" class="block text-sm font-medium text-gray-700">Lý do hủy</label>
                            <textarea name="cancel_reason" id="cancel_reason" rows="4"
                                class="mt-1 p-2 w-full border border-gray-300 rounded-lg text-sm focus:outline-none" required></textarea>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" class="close-modal px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Hủy</button>
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Xác nhận</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Thông tin khách hàng, thanh toán, giao hàng -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-5">
            {{-- Khách hàng --}}
            <div class="bg-white shadow-sm rounded-lg p-4">
                <h5 class="font-semibold text-gray-800 mb-2">Khách hàng</h5>
                <p class="text-sm text-gray-600">
                    <strong>Tên:</strong> {{ $order->user->fullname ?? 'Không có thông tin' }}<br>
                    <strong>Email:</strong> {{ $order->user->email ?? 'Không có thông tin' }}<br>
                    <strong>SĐT:</strong> {{ $order->user->phone ?? 'Không có thông tin' }}
                </p>
            </div>

            {{-- Thanh toán --}}
            <div class="bg-white shadow-sm rounded-lg p-4">
                <h5 class="font-semibold text-gray-800 mb-2">Thông tin thanh toán</h5>
                <p class="text-sm text-gray-600">
                    <strong>Phương thức:</strong> {{ $order->payment_method ?? 'VNPay' }}<br>
                    <strong>Chủ TK:</strong> {{ $order->user->fullname ?? 'Không có' }}<br>
                    <strong>Số TK:</strong> Không có thông tin
                </p>
            </div>

            {{-- Giao hàng --}}
            <div class="bg-white shadow-sm rounded-lg p-4">
                <h5 class="font-semibold text-gray-800 mb-2">Thông tin giao hàng</h5>
                <p class="text-sm text-gray-600">
                    <strong>Vận chuyển:</strong>
                    {{ $order->shopOrder->first()->shipping_provider ?? 'Chờ xác nhận' }}<br>
                    <strong>Địa chỉ:</strong> {{ $orderAddress->address ?? '' }}, {{ $orderAddress->ward ?? '' }},
                    {{ $orderAddress->district ?? '' }}, {{ $orderAddress->province ?? '' }}<br>
                    <strong>Người nhận:</strong> {{ $orderAddress->receiver_name ?? 'Không có' }}<br>
                    <strong>SĐT:</strong> {{ $orderAddress->receiver_phone ?? 'Không có' }}
                    @if ($orderAddress->note)
                        <br><strong>Ghi chú:</strong> {{ e($orderAddress->note) }}
                    @endif
                </p>
            </div>
        </div>

        <!-- Danh sách sản phẩm & Tổng quan -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Sản phẩm --}}
            <div class="md:col-span-2">
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <h5 class="font-semibold text-gray-800 mb-4">Danh sách sản phẩm</h5>
                    <div class="overflow-x-auto">
                        @foreach ($orderItems->groupBy('shop_orderID') as $shopOrderID => $items)
                            @php
                                $shop = $items->first()->shopOrder->shop;
                            @endphp
                            <h4 class="text-sm font-semibold my-2 flex items-center">
                                <img src="{{ asset('storage/' . $shop->shop_logo) }}" alt="{{ $shop->shop_name }}"
                                    class="w-6 h-6 rounded-full mr-2 object-cover">
                                {{ $shop->shop_name ?? 'Shop' }}
                            </h4>
                            <table class="min-w-full text-sm text-left border rounded mb-4">
                                <thead class="bg-gray-100 text-gray-700">
                                    <tr>
                                        <th class="p-2">Sản phẩm</th>
                                        <th class="p-2">Đơn giá</th>
                                        <th class="p-2">SL</th>
                                        <th class="p-2">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr class="border-t hover:bg-gray-50">
                                            <td class="p-2 flex items-center gap-2">
                                                <img src="{{ asset('storage/' . ($item->product->images->first()->image_path ?? '')) }}"
                                                    class="w-10 h-10 object-cover rounded" alt="">
                                                <div>
                                                    <div>{{ $item->product_name }}</div>
                                                    @if ($item->variant)
                                                        <small class="text-gray-500">Biến thể: {{ $item->variant->variant_name }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="p-2">{{ number_format($item->unit_price, 0, ',', '.') }}₫</td>
                                            <td class="p-2">{{ $item->quantity }}</td>
                                            <td class="p-2">{{ number_format($item->total_price, 0, ',', '.') }}₫</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Tổng quan --}}
            <div class="md:col-span-1">
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <h5 class="font-semibold text-gray-800 mb-4">Tổng quan</h5>
                    <table class="w-full text-sm">
                        <tbody>
                            <tr>
                                <td class="py-2 text-gray-600">Tổng tiền hàng</td>
                                <td class="py-2 text-right">{{ number_format($order->total_price - ($order->coupon_discount ?? 0), 0, ',', '.') }}₫</td>
                            </tr>
                            @foreach ($order->shopOrders as $shopOrder)
                                <tr>
                                    <td class="py-2 text-gray-600">Phí ship ({{ $shopOrder->shop->shop_name }})</td>
                                    <td class="py-2 text-right">{{ number_format($shopOrder->shipping_fee ?? 0, 0, ',', '.') }}₫</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="py-2 text-gray-600">Tổng phí ship</td>
                                <td class="py-2 text-right">{{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }}₫</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-gray-600">Giảm giá</td>
                                <td class="py-2 text-right text-green-600">-{{ number_format($order->coupon_discount ?? 0, 0, ',', '.') }}₫</td>
                            </tr>
                            <tr class="border-t border-gray-200">
                                <td class="py-2 font-bold">Tổng cộng</td>
                                <td class="py-2 text-right font-bold text-blue-600">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.open-cancel-modal').forEach(button => {
            button.addEventListener('click', function () {
                const orderId = this.getAttribute('data-order-id');
                const modal = document.getElementById(`cancelModal-${orderId}`);
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
            });
        });

        document.querySelectorAll('[id^="cancelModal-"]').forEach(modal => {
            const closeButtons = modal.querySelectorAll('.close-modal');
            closeButtons.forEach(button => {
                button.addEventListener('click', function () {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });
            });
        });
    });
</script>
@endpush
