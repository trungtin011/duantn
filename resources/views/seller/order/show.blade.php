@extends('layouts.seller_home')

@section('title', 'Chi tiết đơn hàng')

@section('content')
    <!-- Styles are inherited from seller layout; keep page lightweight and consistent -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container mx-auto py-8 px-4">
        @if (session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                <strong class="font-semibold">Thành công</strong>
                <span class="ml-1">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                <strong class="font-semibold">Lỗi</strong>
                <span class="ml-1">{{ session('error') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                <strong class="font-semibold">Lỗi</strong>
                <ul class="list-disc pl-5 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Header Section -->
        @php
            $statusLabelsVi = [
                'pending' => 'Chờ xác nhận',
                'confirmed' => 'Đã xác nhận',
                'ready_to_pick' => 'Sẵn sàng lấy hàng',
                'picked' => 'Đã lấy hàng',
                'shipping' => 'Đang giao hàng',
                'delivered' => 'Đã giao hàng',
                'cancelled' => 'Đã hủy',
                'shipping_failed' => 'Giao hàng thất bại',
                'returned' => 'Đã trả hàng',
                'completed' => 'Hoàn thành',
            ];
        @endphp
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-xl md:text-2xl font-semibold text-gray-900">Đơn hàng #{{ $order->order_code ?? 'N/A' }}</h1>
                <div class="flex items-center mt-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium
                        @if ($shop_order->status === 'completed') bg-green-100 text-green-700
                        @elseif($shop_order->status === 'cancelled') bg-red-100 text-red-700
                        @else bg-blue-100 text-blue-700 @endif">
                        {{ $statusLabelsVi[$shop_order->status] ?? ($shop_order->status ? ucfirst(str_replace('_',' ', $shop_order->status)) : 'Không rõ') }}
                    </span>
                    <span class="ml-3 text-gray-500 text-sm">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2">
                @if ($shop_order->status === 'pending')
                    <form action="{{ route('seller.order.update-status', ['id' => $shop_order->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="confirmed">
                        <button class="btn btn-primary" type="submit">Xác nhận đơn</button>
                    </form>
                @endif

                @if (in_array($shop_order->status, ['confirmed', 'ready_to_pick']))
                    <form action="{{ route('seller.order.cancel') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $shop_order->id }}">
                        <button class="btn btn-danger" type="submit">Hủy đơn</button>
                    </form>
                @endif

                @if (in_array($shop_order->status, ['shipping', 'ready_to_pick', 'picked']))
                    <form action="{{ route('seller.order.tracking') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tracking_code" value="{{ $shop_order->code }}">
                        <input type="hidden" name="method_request" value="status_update">
                        <button class="btn btn-secondary" type="submit">Cập nhật</button>
                    </form>

                    <form action="{{ route('seller.order.refund') }}" method="POST">
                        @csrf
                        <input type="hidden" name="code" value="{{ $shop_order->tracking_code }}">
                        <button class="btn btn-warning" type="submit">Trả hàng</button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Order Information Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Order Details Card -->
            <div class="card">
                <div class="card-header">Thông tin đơn hàng</div>
                <div class="card-body">
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
            <div class="card">
                <div class="card-header">Địa chỉ giao hàng</div>
                <div class="card-body">
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
        <div class="card mb-8">
            <div class="card-header">Sản phẩm đã đặt</div>
            <div class="card-body p-0 overflow-x-auto">
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
                                        @php
                                            $pid = $item->productID ?? $item->product_id ?? null;
                                            $vid = $item->variantID ?? $item->variant_id ?? null;
                                            $img = null;
                                            if ($vid) {
                                                $img = \App\Models\ProductImage::where('variantID', $vid)->value('image_path');
                                            }
                                            if (!$img && $pid) {
                                                $img = \App\Models\ProductImage::where('productID', $pid)->where('is_default', true)->value('image_path')
                                                    ?? \App\Models\ProductImage::where('productID', $pid)->orderBy('display_order')->value('image_path');
                                            }
                                        @endphp
                                        <div class="flex-shrink-0 h-16 w-16 bg-gray-100 rounded-md overflow-hidden">
                                            <img class="h-full w-full object-cover"
                                                src="{{ $img ? asset('storage/' . $img) : asset('images/avatar.png') }}"
                                                alt="{{ $item->product_name }}"
                                                onerror="this.src='{{ asset('images/avatar.png') }}'">
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
                            <td></td>
                            <td></td>
                            <td class="px-6 py-3 text-right text-sm font-medium text-gray-500">Phí vận
                                chuyển:
                                <span class="px-0 py-3 text-sm font-bold text-red-600 text-center">
                                    {{ number_format($shop_order->shipping_shop_fee, 0, ',', '.') }}đ
                                </span>
                            </td>
                            <td class="px-6 py-3 text-right text-sm font-medium text-gray-500">Tổng cộng:
                                <span class="px-0 py-3 text-sm font-bold text-red-600 text-center">
                                    {{ number_format($items->sum('total_price'), 0, ',', '.') }}đ
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Shipping Form -->
        @if ($shop_order->status === 'confirmed')
            <div class="card mb-8">
                <div class="card-header">Gửi đơn hàng vận chuyển</div>
                <div class="card-body">
                    <form action="{{ route('seller.order.shipping', $order->id) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="shipping_provider" class="block text-xs font-medium text-gray-700 mb-1">Đơn vị vận chuyển</label>
                                <select name="shipping_provider" id="shipping_provider" class="form-select w-full h-10">
                                    <option value="GHN">Giao Hàng Nhanh (GHN)</option>
                                </select>
                            </div>
                            <div>
                                <label for="required_note" class="block text-xs font-medium text-gray-700 mb-1">Hình thức kiểm hàng</label>
                                <select name="required_note" id="required_note" class="form-select w-full h-10">
                                    <option value="CHOTHUHANG">Cho xem hàng thử</option>
                                    <option value="CHOXEMHANGKHONGTHU">Cho xem hàng không thử</option>
                                    <option value="KHONGCHOXEMHANG">Không cho xem hàng</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="shop_address" class="block text-xs font-medium text-gray-700 mb-1">Địa chỉ shop</label>
                            <p class="text-[11px] text-red-500 mb-2">Trường hợp thay đổi địa chỉ thì shop sẽ chịu chi phí phát sinh</p>
                            <select name="shop_address" id="shop_address" class="form-select w-full h-10">
                                @foreach ($shop_address as $address)
                                    <option value="{{ $address->id }}">{{ $address->shop_address }}, {{ $address->shop_ward }}, {{ $address->shop_district }}, {{ $address->shop_province }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="note" class="block text-xs font-medium text-gray-700 mb-1">Ghi chú cho đơn vị vận chuyển</label>
                            <textarea name="note" id="note" rows="3" class="form-textarea border w-full"></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-primary">Xác nhận gửi hàng</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Order Status Timeline -->
        <div class="card">
            <div class="card-header">Lịch sử trạng thái</div>
            @if ($status->isEmpty())
                <div class="card-body text-center">
                    <div class="text-gray-400">
                        <i class="fas fa-clipboard-list text-3xl"></i>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-700">Chưa có lịch sử trạng thái</h3>
                    <p class="text-xs text-gray-500">Sẽ hiển thị tại đây khi có cập nhật trạng thái đơn hàng</p>
                </div>
            @else
                @php
                    $groupedStatus = collect($status)->groupBy(fn($item) => $item->created_at->format('d/m/Y'));
                    $statusIcons = [
                        'pending' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                        'confirmed' => 'M5 13l4 4L19 7',
                        'ready_to_pick' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                        'picked' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2',
                        'shipping' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2',
                        'delivered' => 'M5 13l4 4L19 7',
                        'cancelled' => 'M6 18L18 6M6 6l12 12',
                        'shipping_failed' => 'M12 9v2m0 4h.01m-6.938 4h13.856',
                        'returned' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9',
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
                <div class="card-body">
                    <div class="space-y-6">
                        @foreach ($groupedStatus as $date => $histories)
                            <div>
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="h-6 w-6 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14"/></svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-gray-800">Ngày {{ $date }}</h3>
                                </div>
                                <ul class="relative ml-3 border-l border-gray-200 pl-5 space-y-4">
                                    @foreach ($histories as $history)
                                        <li>
                                            <div class="absolute -left-2.5 mt-1 h-5 w-5 rounded-full {{ $statusColors[$history->status] ?? 'bg-gray-100 text-gray-800' }} flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusIcons[$history->status] ?? 'M12 8v4l3 3' }}"/></svg>
                                            </div>
                                            <div class="flex items-start justify-between">
                                                <div>
                                                    @php
                                                        $label = $statusLabelsVi[$history->status] ?? ucfirst(str_replace('_',' ', $history->status));
                                                    @endphp
                                                    <p class="text-sm text-gray-700">
                                                        {{ $label }}
                                                        <span class="ml-2 text-xs text-gray-500">{{ $history->created_at->format('H:i') }}</span>
                                                    </p>
                                                    @if ($history->description)
                                                        <p class="text-xs text-gray-500 mt-1">{{ $history->description }}</p>
                                                    @endif
                                                    @if ($history->note)
                                                        <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded">
                                                            <p class="text-xs text-yellow-700"><span class="font-medium">Ghi chú:</span> {{ $history->note }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
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
