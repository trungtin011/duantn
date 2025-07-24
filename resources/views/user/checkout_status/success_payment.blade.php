@extends('layouts.app')

@section('title', 'Thanh toán thành công')
@section('content')
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Thanh toán thành công</h1>
            <p class="text-lg text-gray-600">Cảm ơn bạn đã mua hàng tại cửa hàng chúng tôi</p>
            <div class="mt-6">
                <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    Về trang chủ
                </a>
            </div>
        </div>

        <!-- Order Summary Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h2 class="text-xl font-semibold text-gray-800">Thông tin đơn hàng</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Mã đơn hàng</p>
                    <p class="font-medium">{{ $order->order_code }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Ngày đặt hàng</p>
                    <p class="font-medium">{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tổng tiền</p>
                    <p class="font-medium text-red-600">{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Phương thức thanh toán</p>
                    <p class="font-medium">{{ $order->payment_method }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Trạng thái thanh toán</p>
                    <p class="font-medium {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ $order->payment_status }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Trạng thái đơn hàng</p>
                    <p class="font-medium">{{ $order->order_status }}</p>
                </div>
            </div>
        </div>

        <!-- Customer Information Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h2 class="text-xl font-semibold text-gray-800">Thông tin người nhận</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Tên người nhận</p>
                    <p class="font-medium">{{ $order->address->receiver_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Số điện thoại</p>
                    <p class="font-medium">{{ $order->address->receiver_phone }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500">Địa chỉ</p>
                    <p class="font-medium">
                        {{ $order->address->address }}, {{ $order->address->ward }}, {{ $order->address->district }}, {{ $order->address->city }}
                    </p>
                </div>
                @if($order->address->note)
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500">Ghi chú</p>
                    <p class="font-medium">{{ $order->address->note }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Items Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h2 class="text-xl font-semibold text-gray-800">Chi tiết đơn hàng</h2>
            </div>
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
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-md overflow-hidden">
                                        <!-- Product image would go here -->
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->variant_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($item->unit_price, 0, ',', '.') }} VNĐ
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ number_format($item->total_price, 0, ',', '.') }} VNĐ
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Tổng cộng</td>
                            <td class="px-6 py-3 text-sm font-bold text-red-600">
                                {{ number_format($order->total_price, 0, ',', '.') }} VNĐ
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Order Actions -->
        <div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
            <a href="#" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                In hóa đơn
            </a>
        </div>
    </div>
@endsection