@extends('layouts.app')

@section('title', 'Thanh toán thành công')
@section('content')
    <div class="container mx-auto px-[20px] md:px-0 md:py-8 md:mb-[200px]">
        <div class="flex flex-col items-center">
            <h1 class="text-2xl font-bold">Thanh toán thành công</h1>
            <p>Cảm ơn bạn đã mua hàng tại chúng tôi</p>
        </div>
        <div class="flex flex-col items-center">
            <h1 class="text-2xl font-bold">Thông tin đơn hàng</h1>
            <p>Mã đơn hàng: {{ $order->order_code }}</p>
            <p>Ngày đặt hàng: {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
            <p>Tổng tiền: {{ number_format($order->total_price, 0, ',', '.') }} VNĐ</p>
            <p>Phương thức thanh toán: {{ $order->payment_method }}</p>
            <p>Trạng thái thanh toán: {{ $order->payment_status }}</p>
            <p>Trạng thái đơn hàng: {{ $order->order_status }}</p>
        </div>
        <div class="flex flex-col items-center">
            <h1 class="text-2xl font-bold">Thông tin người nhận</h1>
            <p>Tên: {{ $order->address->receiver_name }}</p>
            <p>Số điện thoại: {{ $order->address->receiver_phone }}</p>
            <p>Địa chỉ: {{ $order->address->address }}, {{ $order->address->city }}, {{ $order->address->district }}, {{ $order->address->ward }}, {{ $order->address->postcode }}</p>
            <p>Ghi chú: {{ $order->address->note }}</p>
        </div>
    </div>
    <div class="flex flex-col items-center">
        <h1 class="text-2xl font-bold">Danh sách sản phẩm</h1>
        <table class="table-auto">
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->unit_price, 0, ',', '.') }} VNĐ</td>
                        <td>{{ number_format($item->total_price, 0, ',', '.') }} VNĐ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection