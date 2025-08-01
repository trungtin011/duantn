@extends('layouts.app')

@section('title', 'Thanh toán thất bại')
@push('styles')
    @vite('resources/css/user/failed-payment.css')
@endpush
@section('content')
    <div class="container mx-auto px-4 py-8 max-w-6xl failed-payment-container">
        <!-- Header Section -->
        <div class="text-center mb-12 failed-payment-header">
            <div class="failed-payment-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <h1 class="failed-payment-title">Thanh toán thất bại</h1>
            <p class="failed-payment-subtitle">Rất tiếc, quá trình thanh toán của bạn không thành công</p>
            <p class="failed-payment-description">Vui lòng kiểm tra lại thông tin thanh toán và thử lại</p>
            <div class="failed-payment-actions">
                <button type="button" class="failed-payment-button primary" onclick="showGlobalPopup('{{$order->order_code}}')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                    </svg>
                    Thử lại thanh toán
                </button>
                <a href="{{ route('home') }}" class="failed-payment-button secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    Về trang chủ
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Liên hệ hỗ trợ
                </a>
            </div>
        </div>

        @if(isset($order))
        <!-- Order Summary Card -->
        <div class="order-card">
            <div class="order-card-header">
                <h2 class="order-card-title">Thông tin đơn hàng</h2>
            </div>
            <div class="order-card-content">
                <div class="order-info-grid">
                    <div class="order-info-item">
                        <p class="order-info-label">Mã đơn hàng</p>
                        <p class="order-info-value">{{ $order->order_code }}</p>
                    </div>
                    <div class="order-info-item">
                        <p class="order-info-label">Ngày đặt hàng</p>
                        <p class="order-info-value">{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div class="order-info-item">
                        <p class="order-info-label">Tổng tiền</p>
                        <p class="order-info-value">{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</p>
                    </div>
                    <div class="order-info-item">
                        <p class="order-info-label">Phương thức thanh toán</p>
                        <p class="order-info-value">{{ $order->payment_method }}</p>
                    </div>
                    <div class="order-info-item">
                        <p class="order-info-label">Trạng thái thanh toán</p>
                        <p class="order-info-value failed">Thất bại</p>
                    </div>
                    <div class="order-info-item">
                        <p class="order-info-label">Trạng thái đơn hàng</p>
                        <p class="order-info-value pending">Chờ thanh toán</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Information Card -->
        <div class="order-card">
            <div class="order-card-header">
                <h2 class="order-card-title">Thông tin người nhận</h2>
            </div>
            <div class="order-card-content">
                <div class="order-info-grid">
                    <div class="order-info-item">
                        <p class="order-info-label">Tên người nhận</p>
                        <p class="order-info-value">{{ $order->address->receiver_name }}</p>
                    </div>
                    <div class="order-info-item">
                        <p class="order-info-label">Số điện thoại</p>
                        <p class="order-info-value">{{ $order->address->receiver_phone }}</p>
                    </div>
                    <div class="order-info-item" style="grid-column: span 2;">
                        <p class="order-info-label">Địa chỉ</p>
                        <p class="order-info-value">
                            {{ $order->address->address }}, {{ $order->address->ward }}, {{ $order->address->district }}, {{ $order->address->city }}
                        </p>
                    </div>
                    @if($order->address->note)
                    <div class="order-info-item" style="grid-column: span 2;">
                        <p class="order-info-label">Ghi chú</p>
                        <p class="order-info-value">{{ $order->address->note }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Items Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h2 class="text-xl font-semibold text-gray-800">Chi tiết đơn hàng</h2>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Biến thể</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn giá</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thành tiền</th>
                            </tr>
                        </thead>
                        @php
                            // Gom item theo shop_order_id
                            $shopOrders = [];
                            foreach ($order->items as $item) {
                                $shopId = $item->shop_order_id;
                                if (!isset($shopOrders[$shopId])) {
                                    $shopOrders[$shopId] = [
                                        'shop_name' => $item->shop_order->shop_name,
                                        'shipping_fee' => $item->shop_order->shipping_shop_fee,
                                        'discount' => $item->shop_order->discount_shop_amount,
                                        'items' => [],
                                    ];
                                }
                                $shopOrders[$shopId]['items'][] = $item;
                            }
                            $orderTotal = 0;
                        @endphp
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($shopOrders as $shop)
                                <tr>
                                    <td colspan="5" class="bg-blue-50 px-6 py-3 font-semibold text-blue-700 text-base border-t border-b border-blue-100">
                                        <i class="fa fa-store mr-2"></i> {{ $shop['shop_name'] }}
                                    </td>
                                </tr>
                                @php
                                    $shopSubtotal = 0;
                                @endphp
                                @foreach($shop['items'] as $item)
                                    @php
                                        $itemTotal = $item->unit_price * $item->quantity;
                                        $shopSubtotal += $itemTotal;
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-12 w-12">
                                                    <img class="h-12 w-12 rounded-lg object-cover" src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" alt="{{ $item->product->name }}">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($item->variant)
                                                {{ $item->variant->name }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($item->unit_price, 0, ',', '.') }} VNĐ
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($itemTotal, 0, ',', '.') }} VNĐ
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5" class="bg-gray-50 px-6 py-3 text-sm text-gray-700">
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                            <div>
                                                <span class="font-medium">Tạm tính của shop:</span>
                                                <span class="font-semibold text-gray-900">{{ number_format($shopSubtotal, 0, ',', '.') }} VNĐ</span>
                                            </div>
                                            <div>
                                                <span class="font-medium">Phí vận chuyển:</span>
                                                <span class="text-gray-900">{{ number_format($shop['shipping_fee'], 0, ',', '.') }} VNĐ</span>
                                            </div>
                                            <div>
                                                <span class="font-medium">Giảm giá shop:</span>
                                                <span class="text-red-600">-{{ number_format($shop['discount'], 0, ',', '.') }} VNĐ</span>
                                            </div>
                                            <div>
                                                @php
                                                    $shopTotal = $shopSubtotal + $shop['shipping_fee'] - $shop['discount'];
                                                    $orderTotal += $shopTotal;
                                                @endphp
                                                <span class="font-semibold text-blue-700">Tổng shop:</span>
                                                <span class="font-bold text-blue-700">{{ number_format($shopTotal, 0, ',', '.') }} VNĐ</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="5" class="bg-yellow-50 px-6 py-4 text-right font-bold text-lg text-yellow-700 border-t-2 border-yellow-200">
                                    Tổng đơn hàng: {{ number_format($orderTotal, 0, ',', '.') }} VNĐ
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Help Section -->
        <div class="bg-blue-50 rounded-xl p-6 mb-8">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-blue-800 mb-2">Cần hỗ trợ?</h3>
                    <div class="text-sm text-blue-700">
                        <p class="mb-2">Nếu bạn gặp vấn đề với thanh toán, vui lòng:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Kiểm tra lại thông tin thẻ thanh toán</li>
                            <li>Đảm bảo tài khoản có đủ số dư</li>
                            <li>Thử lại với phương thức thanh toán khác</li>
                            <li>Liên hệ hỗ trợ khách hàng nếu cần thiết</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 