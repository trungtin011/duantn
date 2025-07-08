@extends('user.account.layout')

@section('account-content')
    <div class="container mx-auto py-5">
        @include('layouts.notification')
        <!-- Thông báo thành công/lỗi -->
        {{-- @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
                <button onclick="this.parentElement.style.display='none'" class="float-right text-green-700">×</button>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
                <button onclick="this.parentElement.style.display='none'" class="float-right text-green-700">×</button>
            </div>
        @endif --}}

        <!-- Breadcrumb -->
        <div class="flex flex-wrap items-center gap-2 mb-10 px-[10px] sm:px-0 md:mb-10 text-sm md:text-base">
            <a href="{{ route('order_history') }}" class="text-gray-500 hover:underline">Đơn hàng</a>
            <span>/</span>
            <span>Chi tiết đơn hàng</span>
        </div>

        <!-- Nút quan lại -->
        <div class="mb-4 bg-gray-200 shadow-sm rounded-lg p-4">
            <a href="{{ route('order_history') }}" class="text-sm hover:text-red-500">
                <i class="fa fa-arrow-left"></i> Quan lý đơn hàng
            </a>
        </div>

        <!-- Header: Chi tiết đơn hàng -->
        <div class="mb-4">
            <div class="bg-white shadow-sm rounded-lg p-4 h-full flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-1">Chi tiết đơn hàng</h2>
                    <p class="text-gray-600 text-sm">Đơn hàng {{ $order->order_code ?? 'N/A' }} | Order Created:
                        {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p class="text-gray-600 text-sm">
                        <strong>Trạng thái:</strong>
                        <span
                            class="{{ $order->order_status === 'delivered' ? 'text-green-600' : ($order->order_status === 'cancelled' || $order->order_status === 'refunded' ? 'text-red-600' : 'text-blue-600') }}">
                            {{ __('order_status.' . $order->order_status) }}
                        </span>
                    </p>
                    @if ($order->cancel_reason)
                        <p class="text-gray-600 text-sm"><strong>Lý do hủy:</strong> {{ e($order->cancel_reason) }}</p>
                    @endif
                </div>
                <!-- Nút hành động: Hủy đơn hàng hoặc Mua lại -->
                <div class="flex space-x-2">
                    @if (in_array($order->order_status, ['pending', 'processing']))
                        <button class="open-cancel-modal bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 text-sm"
                            data-order-id="{{ $order->id }}">
                            Hủy đơn hàng
                        </button>
                    @endif
                    @if (in_array($order->order_status, ['cancelled', 'refunded']))
                        <form action="{{ route('user.order.reorder', $order->id) }}" method="GET">
                            @csrf
                            <button type="submit"
                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">
                                Mua lại
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal hủy đơn hàng (gộp từ order-block.blade.php) -->
        @if (in_array($order->order_status, ['pending', 'processing']))
            <div id="cancelModal-{{ $order->id }}"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 w-full max-w-md">
                    <h3 class="text-lg font-semibold mb-4">Hủy đơn hàng</h3>
                    <form action="{{ route('user.order.cancel', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label for="cancel_reason" class="block text-sm font-medium text-gray-700">Lý do hủy</label>
                            <textarea name="cancel_reason" id="cancel_reason" rows="4"
                                class="mt-1 p-2 block w-full form-control border-gray-300 border rounded-lg text-sm focus:outline-none" required></textarea>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button"
                                class="close-modal px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Hủy</button>
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Xác
                                nhận</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Thông tin khách hàng, thanh toán, giao hàng -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
            <!-- Khách hàng -->
            <div class="bg-white shadow-sm rounded-lg p-4 h-full">
                <h5 class="font-semibold text-gray-800 mb-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 mr-2 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    Khách hàng
                </h5>
                <p class="text-gray-600 text-sm">
                    <strong>Tên:</strong> {{ $order->user->fullname ?? 'Không có thông tin' }}<br>
                    <strong>Email:</strong> {{ $order->user->email ?? 'Không có thông tin' }}<br>
                    <strong>Số điện thoại:</strong> {{ $order->user->phone ?? 'Không có thông tin' }}
                </p>
            </div>
            <!-- Thông tin thanh toán -->
            <div class="bg-white shadow-sm rounded-lg p-4 h-full">
                <h5 class="font-semibold text-gray-800 mb-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 mr-2 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                    </svg>
                    Thông tin thanh toán
                </h5>
                <p class="text-gray-600 text-sm">
                    <strong>Phương thức thanh toán:</strong> {{ $order->payment_method ?? 'VNPay' }}<br>
                    <strong>Tên chủ tài khoản:</strong> {{ $order->user->fullname ?? 'Không có thông tin' }}<br>
                    <strong>Số tài khoản:</strong> Không có thông tin
                </p>
            </div>
            <!-- Thông tin giao hàng -->
            <div class="bg-white shadow-sm rounded-lg p-4 h-full">
                <h5 class="font-semibold text-gray-800 mb-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 mr-2 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677h.75m0-11.177v-.548c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v.548m12 0h.75" />
                    </svg>
                    Thông tin giao hàng
                </h5>
                <p class="text-gray-600 text-sm">
                    <strong>Phương thức vận chuyển:</strong>
                    {{ isset($order->shopOrder) && $order->shopOrder->first() ? $order->shopOrder->first()->shipping_provider : 'Chờ xác nhận' }}<br>
                    <strong>Địa chỉ:</strong> {{ $orderAddress->address ?? 'Không có thông tin' }},
                    {{ $orderAddress->ward ?? '' }}, {{ $orderAddress->district ?? '' }},
                    {{ $orderAddress->province ?? '' }}<br>
                    <strong>Tên người nhận:</strong> {{ $orderAddress->receiver_name ?? 'Không có thông tin' }}<br>
                    <strong>Số điện thoại:</strong> {{ $orderAddress->receiver_phone ?? 'Không có thông tin' }}
                    @if ($orderAddress->note)
                        <br><strong>Ghi chú:</strong> {{ e($orderAddress->note) }}
                    @endif
                </p>
            </div>
        </div>

        <!-- Danh sách sản phẩm và Tổng quan đơn hàng -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Danh sách sản phẩm -->
            <div class="md:col-span-2">
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <h5 class="font-semibold text-gray-800 mb-4">Danh sách sản phẩm</h5>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <tbody>
                                @if (isset($orderItems) && $orderItems->isNotEmpty())
                                    @php
                                        $itemsGroupedByShop = $orderItems->groupBy('shop_orderID');
                                    @endphp

                                    @foreach ($itemsGroupedByShop as $shopOrderID => $items)
                                        @php
                                            $shop = $items->first()->shopOrder->shop;
                                            $shopName = $shop->shop_name ?? 'Không có tên shop';
                                        @endphp

                                        <h4 class="text-base font-semibold my-3">
                                            <img src="{{ asset('storage/' . $shop->shop_logo) }}"
                                                alt="Logo {{ $shop->shop_name }}"
                                                class="w-8 h-8 object-cover rounded-full inline-block mr-2">
                                            {{ $shop->shop_name }}
                                        </h4>

                                        <table class="w-full text-left mb-6 border border-gray-200 rounded">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="p-3 text-sm font-semibold text-gray-700">Sản phẩm</th>
                                                    <th class="p-3 text-sm font-semibold text-gray-700">Đơn giá</th>
                                                    <th class="p-3 text-sm font-semibold text-gray-700">Số lượng</th>
                                                    <th class="p-3 text-sm font-semibold text-gray-700">Thành tiền</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($items as $item)
                                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                                        <td class="p-3">
                                                            <div class="flex items-center">
                                                                @php
                                                                    $variantId = $item->variantID ?? null;
                                                                    $productImages =
                                                                        $item->product->images ?? collect();

                                                                    $variantImage = $productImages->firstWhere(
                                                                        'variantID',
                                                                        $variantId,
                                                                    );
                                                                    $defaultImage =
                                                                        $productImages->firstWhere('is_default', 1) ??
                                                                        $productImages->first();

                                                                    $imagePath =
                                                                        $variantImage->image_path ??
                                                                        ($defaultImage->image_path ??
                                                                            'https://via.placeholder.com/40');

                                                                    $finalImage = Str::startsWith($imagePath, [
                                                                        'http',
                                                                        '//',
                                                                    ])
                                                                        ? $imagePath
                                                                        : asset('storage/' . $imagePath);
                                                                @endphp

                                                                <img src="{{ $finalImage }}"
                                                                    alt="{{ e($item->product_name) }}"
                                                                    class="w-10 h-10 rounded mr-3 object-cover">

                                                                <div>
                                                                    <span
                                                                        class="font-medium">{{ e($item->product_name) }}</span>
                                                                    @if ($item->variant)
                                                                        <p class="text-xs text-gray-500">Biến thể:
                                                                            {{ e($item->variant->variant_name) }}</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="p-3 text-gray-600">
                                                            {{ number_format($item->unit_price, 0, ',', '.') }} VND</td>
                                                        <td class="p-3 text-gray-600">{{ $item->quantity }}</td>
                                                        <td class="p-3 text-gray-600">
                                                            {{ number_format($item->total_price, 0, ',', '.') }} VND</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="p-3 text-gray-600 text-center">Không có sản phẩm nào
                                            trong
                                            đơn hàng này.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tổng quan đơn hàng -->
            <div class="md:col-span-1">
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <h5 class="font-semibold text-gray-800 mb-4">Tổng quan đơn hàng</h5>
                    <table class="w-full text-sm">
                        <tbody>
                            <tr>
                                <td class="py-2 text-gray-600">Tổng tiền hàng</td>
                                <td class="py-2 text-right font-medium text-gray-800">
                                    {{ number_format($order->total_price - ($order->coupon_discount ?? 0), 0, ',', '.') }}
                                    VND
                                </td>
                            </tr>
                            <!-- Hiển thị phí vận chuyển theo từng shop (nếu có nhiều shop) -->
                            @foreach ($order->shopOrders as $shopOrder)
                                <tr>
                                    <td class="py-2 text-gray-600">Phí ship (Shop
                                        {{ $shopOrder->shop->shop_name ?? 'N/A' }})</td>
                                    <td class="py-2 text-right font-medium text-gray-800">
                                        {{ number_format($shopOrder->shipping_fee ?? 0, 0, ',', '.') }} VND
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="py-2 text-gray-600">Tổng phí ship</td>
                                <td class="py-2 text-right font-medium text-gray-800">
                                    {{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }} VND
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2 text-gray-600">Giảm giá</td>
                                <td class="py-2 text-right font-medium text-green-600">
                                    {{ number_format($order->coupon_discount ?? 0, 0, ',', '.') }} VND
                                </td>
                            </tr>
                            <tr class="border-t border-gray-200 pt-2">
                                <td class="py-2 font-bold text-gray-800">Tổng cộng</td>
                                <td class="py-2 text-right font-bold text-blue-600">
                                    {{ number_format($order->total_price, 0, ',', '.') }} VND
                                </td>
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
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.open-cancel-modal').forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-order-id');
                    const modal = document.getElementById(`cancelModal-${orderId}`);
                    if (modal) {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    }
                });
            });

            document.querySelectorAll('[id^="cancelModal-"]').forEach(modal => {
                const closeButtons = modal.querySelectorAll('button[type="button"]');
                closeButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    });
                });
            });
        });
    </script>
@endpush
