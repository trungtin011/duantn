@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
    <div class="container mx-auto py-5" style="min-height: 80vh;">
        <div class="container mx-auto py-5">
            <!-- breadcrumb -->
            <div class="flex flex-wrap items-center gap-2 mb-10 px-[10px] sm:px-0 md:mb-20 text-sm md:text-base">
                <a href="{{ route('home') }}" class="text-gray-500 hover:underline">Trang chủ</a>
                <span>/</span>
                <span>Giỏ hàng</span>
            </div>

            <!-- Bảng sản phẩm -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Sản phẩm</th>
                            <th class="py-3 text-sm font-semibold text-gray-700">Giá</th>
                            <th class="py-3 text-sm font-semibold text-gray-700">Số lượng</th>
                            <th class="py-3 text-sm font-semibold text-gray-700">Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach ($cartItems as $item)
                            @php
                                $subtotal = $item->price * $item->quantity;
                                $total += $subtotal;
                            @endphp
                            <tr class="border-b border-gray-200">
                                <td class="py-4 px-4">
                                    <div class="flex items-center">
                                        <img src="{{ $item->product->image ?? 'https://via.placeholder.com/150' }}"
                                            alt="{{ $item->product->name }}" class="w-[100px] h-[100px] object-contain mr-4">
                                        <span class="text-gray-700">{{ $item->product->name }}</span>
                                    </div>
                                </td>
                                <td class="py-4 text-center text-gray-700">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                <td class="py-4 text-center">
                                    <div class="flex items-center justify-center">
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="quantity" value="{{ $item->quantity }}"
                                                class="w-16 text-center border border-gray-300" min="1">
                                            <button type="submit" class="ml-2 px-2 py-1 border border-gray-300 hover:bg-gray-100">Cập nhật</button>
                                        </form>
                                    </div>
                                </td>
                                <td class="py-4 text-center text-gray-700">{{ number_format($subtotal, 0, ',', '.') }}đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mã giảm giá và Tổng số giỏ hàng -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Mã giảm giá -->
                <div class="shadow-sm rounded-lg py-4 px-4">
                    <h6 class="text-gray-700 mb-3">Mã giảm giá</h6>
                    <form action="#" method="POST">
                        @csrf
                        <div class="flex">
                            <input type="text" name="coupon"
                                class="border border-gray-300 rounded-l-md p-2 flex-1 focus:outline-none"
                                placeholder="Nhập mã giảm giá">
                            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-r-md hover:bg-gray-900">Áp dụng mã</button>
                        </div>
                    </form>
                </div>

                <!-- Tổng tiền -->
                <div class="shadow-sm rounded-lg py-4 px-4">
                    <h5 class="font-bold text-gray-800 mb-3">Tổng số giỏ hàng</h5>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tổng phụ:</span>
                            <span class="text-gray-700">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Vận chuyển:</span>
                            <span class="text-gray-700">Miễn phí</span>
                        </div>
                        <hr class="border-gray-200">
                        <div class="flex justify-between font-bold">
                            <span class="text-gray-800">Tổng tiền:</span>
                            <span class="text-red-800">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                    <div class="text-right mt-4">
                        <a href="{{ route('checkout') }}" class="bg-gray-800 text-white px-6 py-2 rounded hover:bg-gray-900">Tiến hành thanh toán</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
