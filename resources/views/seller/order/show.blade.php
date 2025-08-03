@extends('layouts.seller_home')

@section('title', 'Chi tiết đơn hàng')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container mx-auto py-8 px-4">
        @if (session('success'))
            <div class="mb-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative animate__animated animate__fadeInDown"
                    role="alert">
                    <strong class="font-bold">Thành công!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative animate__animated animate__shakeX"
                    role="alert">
                    <strong class="font-bold">Lỗi!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative animate__animated animate__shakeX"
                    role="alert">
                    <strong class="font-bold">Lỗi!</strong>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 mr-3" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Đơn hàng #{{ $order->order_code ?? 'N/A' }}
                </h1>
                <div class="flex items-center mt-2">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    @if ($shop_order->status === 'completed') bg-green-100 text-green-800
                    @elseif($shop_order->status === 'cancelled') bg-red-100 text-red-800
                    @else bg-blue-100 text-blue-800 @endif animate-pulse">
                        {{ ucfirst(str_replace('_', ' ', $shop_order->status ?? 'N/A')) }}
                    </span>
                    <span class="ml-3 text-gray-500 text-sm">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                @if ($shop_order->status === 'pending')
                    <form action="{{ route('seller.order.update-status', ['id' => $shop_order->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="confirmed">
                        <button
                            class="flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all transform hover:scale-105"
                            type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Xác nhận đơn
                        </button>
                    </form>
                @endif

                @if (in_array($shop_order->status, ['confirmed', 'ready_to_pick']))
                    <form action="{{ route('seller.order.cancel') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $shop_order->id }}">
                        <button
                            class="flex items-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-all transform hover:scale-105"
                            type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Hủy đơn
                        </button>
                    </form>
                @endif

                @if (in_array($shop_order->status, ['shipping', 'ready_to_pick', 'picked']))
                    <form action="{{ route('seller.order.tracking') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tracking_code" value="{{ $shop_order->code }}">
                        <input type="hidden" name="method_request" value="status_update">
                        <button
                            class="flex items-center bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-all transform hover:scale-105"
                            type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Cập nhật
                        </button>
                    </form>

                    <form action="{{ route('seller.order.refund') }}" method="POST">
                        @csrf
                        <input type="hidden" name="code" value="{{ $shop_order->tracking_code }}">
                        <button
                            class="flex items-center bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-all transform hover:scale-105"
                            type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Trả hàng
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Order Information Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Order Details Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all hover:shadow-lg">
                <div class="p-5 bg-gradient-to-r from-blue-500 to-blue-600">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Thông tin đơn hàng
                    </h2>
                </div>
                <div class="p-5">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Mã đơn hàng:</span>
                            <span class="font-medium">{{ $shop_order->code ?? '' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ngày đặt:</span>
                            <span class="font-medium">{{ $order->created_at->format('d/m/Y H:i') ?? '' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Mã vận đơn:</span>
                            <span class="font-medium">{{ $shop_order->tracking_code ?? 'Chưa có' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Lời nhắn:</span>
                            <span
                                class="font-medium text-right">{{ $order->shop_order->first()->note ?? 'Không có' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Address Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all hover:shadow-lg">
                <div class="p-5 bg-gradient-to-r from-green-500 to-green-600">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Địa chỉ giao hàng
                    </h2>
                </div>
                <div class="p-5">
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span class="text-gray-600 min-w-[120px]">Người nhận:</span>
                            <span class="font-medium">{{ $order->address->receiver_name }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-gray-600 min-w-[120px]">Điện thoại:</span>
                            <span class="font-medium">{{ $order->address->receiver_phone }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-gray-600 min-w-[120px]">Địa chỉ:</span>
                            <span class="font-medium">
                                {{ $order->address->address }}, {{ $order->address->ward }},
                                {{ $order->address->district }}, {{ $order->address->province }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8 transition-all hover:shadow-lg">
            <div class="p-5 bg-gradient-to-r from-purple-500 to-purple-600">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Sản phẩm đã đặt
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản
                                phẩm</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Đơn giá</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Số
                                lượng</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($items as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-16 w-16 bg-gray-100 rounded-md overflow-hidden">
                                            <img class="h-full w-full object-contain"
                                                src="{{ $item->product_image ?? 'https://via.placeholder.com/150' }}"
                                                alt="{{ $item->product_name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $item->variant_name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    {{ number_format($item->unit_price, 0, ',', '.') }}đ
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                    {{ number_format($item->total_price, 0, ',', '.') }}đ
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Phí vận
                                chuyển</td>
                            <td class="px-6 py-3 text-sm font-bold text-red-600 text-center">
                                {{ number_format($shop_order->shipping_shop_fee, 0, ',', '.') }}đ
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Tổng cộng
                            </td>
                            <td class="px-6 py-3 text-sm font-bold text-red-600 text-center">
                                {{ number_format($items->sum('total_price'), 0, ',', '.') }}đ
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Shipping Form -->
        @if ($shop_order->status === 'confirmed')
            <div
                class="bg-white rounded-xl shadow-md overflow-hidden mb-8 transition-all hover:shadow-lg animate__animated animate__fadeIn">
                <div class="p-5 bg-gradient-to-r from-orange-500 to-orange-600">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                        Gửi đơn hàng vận chuyển
                    </h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('seller.order.shipping', $order->id) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div>
                                <label for="shipping_provider" class="block text-sm font-medium text-gray-700 mb-1">Đơn vị
                                    vận chuyển</label>
                                <select name="shipping_provider" id="shipping_provider"
                                    class="mt-1 block w-full h-10 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="GHN">Giao Hàng Nhanh (GHN)</option>
                                </select>
                            </div>
                            <div>
                                <label for="required_note" class="block text-sm font-medium text-gray-700 mb-1">Hình thức
                                    kiểm hàng</label>
                                <select name="required_note" id="required_note"
                                    class="mt-1 block w-full h-10 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="CHOTHUHANG">Cho xem hàng thử</option>
                                    <option value="CHOXEMHANGKHONGTHU">Cho xem hàng không thử</option>
                                    <option value="KHONGCHOXEMHANG">Không cho xem hàng</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="shop_address" class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ
                                shop</label>
                            <div class="text-red-500 mt-3 mb-2">Trường hợp thay đổi địa chỉ thì shop sẽ chịu chi phí phát
                                sinh</div>
                            <select name="shop_address" id="shop_address"
                                class="mt-1 block w-full h-10 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @foreach ($shop_address as $address)
                                    <option value="{{ $address->id }}">{{ $address->shop_address }},
                                        {{ $address->shop_ward }}, {{ $address->shop_district }},
                                        {{ $address->shop_province }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6">
                            <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Ghi chú cho đơn vị
                                vận chuyển</label>
                            <textarea name="note" id="note" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Xác nhận gửi hàng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Order Status Timeline -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all hover:shadow-lg">
            <div class="p-5 bg-gradient-to-r from-indigo-500 to-indigo-600">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Lịch sử trạng thái
                </h2>
            </div>

            @if ($status->isEmpty())
                <div class="p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Chưa có lịch sử trạng thái</h3>
                    <p class="mt-1 text-gray-500">Sẽ hiển thị tại đây khi có cập nhật trạng thái đơn hàng</p>
                </div>
            @else
                @php
                    $groupedStatus = collect($status)->groupBy(function ($item) {
                        return $item->created_at->format('d/m/Y');
                    });

                    $statusIcons = [
                        'pending' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                        'confirmed' => 'M5 13l4 4L19 7',
                        'ready_to_pick' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                        'picked' =>
                            'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                        'shipping' =>
                            'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                        'delivered' => 'M5 13l4 4L19 7',
                        'cancelled' => 'M6 18L18 6M6 6l12 12',
                        'shipping_failed' =>
                            'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                        'returned' =>
                            'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                        'completed' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                    ];

                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'confirmed' => 'bg-blue-100 text-blue-800',
                        'ready_to_pick' => 'bg-purple-100 text-purple-800',
                        'picked' => 'bg-indigo-100 text-indigo-800',
                        'shipping' => 'bg-green-100 text-green-800',
                        'delivered' => 'bg-teal-100 text-teal-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                        'shipping_failed' => 'bg-red-100 text-red-800',
                        'returned' => 'bg-orange-100 text-orange-800',
                        'completed' => 'bg-green-100 text-green-800',
                    ];
                @endphp

                <div class="p-6">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach ($groupedStatus as $date => $histories)
                                <li class="mb-8">
                                    <div class="flex items-center mb-4">
                                        <span
                                            class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </span>
                                        <h3 class="ml-3 text-lg font-medium text-gray-900">Ngày {{ $date }}</h3>
                                    </div>

                                    <ul class="space-y-6">
                                        @foreach ($histories as $history)
                                            <li class="relative pl-10 pb-6 last:pb-0">
                                                <div class="absolute top-0 left-0 h-full w-0.5 bg-gray-200"
                                                    aria-hidden="true"></div>
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span
                                                            class="h-8 w-8 rounded-full {{ $statusColors[$history->status] ?? 'bg-gray-100 text-gray-800' }} flex items-center justify-center ring-8 ring-white">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="{{ $statusIcons[$history->status] ?? 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' }}" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm text-gray-500">
                                                                {{ ucfirst(str_replace('_', ' ', $history->status)) }}
                                                                <span
                                                                    class="font-medium text-gray-900 ml-1">{{ $history->created_at->format('H:i') }}</span>
                                                            </p>
                                                            @if ($history->description)
                                                                <p class="text-sm text-gray-500 mt-1">
                                                                    {{ $history->description }}</p>
                                                            @endif
                                                            @if ($history->note)
                                                                <div
                                                                    class="mt-2 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded-r">
                                                                    <div class="flex">
                                                                        <div class="flex-shrink-0">
                                                                            <svg class="h-5 w-5 text-yellow-400"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                viewBox="0 0 20 20" fill="currentColor">
                                                                                <path fill-rule="evenodd"
                                                                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                                                    clip-rule="evenodd" />
                                                                            </svg>
                                                                        </div>
                                                                        <div class="ml-3">
                                                                            <p class="text-sm text-yellow-700">
                                                                                <span class="font-medium">Ghi chú:</span>
                                                                                {{ $history->note }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Animation for status change
        $(document).ready(function() {
            $('button[type="submit"]').click(function() {
                $(this).addClass('animate__animated animate__pulse');
                setTimeout(() => {
                    $(this).removeClass('animate__animated animate__pulse');
                }, 1000);
            });
        });
    </script>
@endsection
