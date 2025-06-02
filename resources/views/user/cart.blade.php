@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
                        <!-- Sản phẩm 1: LCD Monitor -->
                        <tr class="border-b border-gray-200">
                            <td class="py-4 px-4">
                                <div class="flex items-center">
                                    <img src="https://cdn.kkfashion.vn/26926-large_default/ao-thun-nu-mau-den-in-hinh-buom-asm16-35.jpg"
                                        alt="LCD Monitor" class="w-[150px] h-[150px] object-contain mr-4">
                                    <span class="text-gray-700">LCD Monitor</span>
                                </div>
                            </td>
                            <td class="py-4 text-center text-gray-700">$650</td>
                            <td class="py-4 text-center">
                                <div class="flex items-center justify-center">
                                    <button class="px-2 py-1 border border-gray-300 hover:bg-gray-100">-</button>
                                    <input type="text" value="1"
                                        class="w-12 text-center border-t border-b border-gray-300 focus:outline-none">
                                    <button class="px-2 py-1 border border-gray-300 hover:bg-gray-100">+</button>
                                </div>
                            </td>
                            <td class="py-4 text-center text-gray-700">$650</td>
                        </tr>
                        <!-- Sản phẩm 2: H1 Gamepad -->
                        <tr class="border-b border-gray-200">
                            <td class="py-4 px-4">
                                <div class="flex items-center">
                                    <img src="https://cdn.kkfashion.vn/26926-large_default/ao-thun-nu-mau-den-in-hinh-buom-asm16-35.jpg"
                                        alt="H1 Gamepad" class="w-[150px] h-[150px] object-contain mr-4">
                                    <span class="text-gray-700">H1 Gamepad</span>
                                </div>
                            </td>
                            <td class="py-4 text-center text-gray-700">$550</td>
                            <td class="py-4 text-center">
                                <div class="flex items-center justify-center">
                                    <button class="px-2 py-1 border border-gray-300 hover:bg-gray-100">-</button>
                                    <input type="text" value="2"
                                        class="w-12 text-center border-t border-b border-gray-300 focus:outline-none">
                                    <button class="px-2 py-1 border border-gray-300 hover:bg-gray-100">+</button>
                                </div>
                            </td>
                            <td class="py-4 text-center text-gray-700">$1100</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Nút hành động -->
            <div class="flex justify-between mt-6">
                <a href="#" class="border border-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-100">Quay lại
                    cửa hàng</a>
                <button class="border border-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-100">Cập nhật giỏ
                    hàng</button>
            </div>

            <!-- Mã giảm giá và Tổng số giỏ hàng -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Mã giảm giá -->
                <div class="shadow-sm rounded-lg py-4">
                    <h6 class="text-gray-700 mb-3">Mã giảm giá</h6>
                    <div class="flex">
                        <input type="text"
                            class="border border-gray-300 rounded-l-md p-2 flex-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Nhập mã giảm giá">
                        <button class="bg-gray-800 text-white px-4 py-2 rounded-r-md hover:bg-gray-900">Áp dụng mã</button>
                    </div>
                </div>
                <!-- Tổng số giỏ hàng -->
                <div class="shadow-sm rounded-lg py-4">
                    <h5 class="font-bold text-gray-800 mb-3">Tổng số giỏ hàng</h5>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tổng phụ:</span>
                            <span class="text-gray-700">1750.000đ</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Vận chuyển:</span>
                            <span class="text-gray-700">Free</span>
                        </div>
                        <hr class="border-gray-200">
                        <div class="flex justify-between font-bold">
                            <span class="text-gray-800">Tổng tiền:</span>
                            <span class="text-red-800">1750.000đ</span>
                        </div>
                    </div>
                    <div class="text-right mt-4">
                        <a href="#" class="bg-gray-800 text-white px-6 py-2 rounded hover:bg-gray-900">Tiến hành thanh
                            toán</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
