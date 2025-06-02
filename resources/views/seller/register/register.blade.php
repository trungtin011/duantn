@extends('layouts.seller')


@section('content')
    <div class="container mx-auto py-5 flex flex-col" style="min-height: 80vh;">
        <!-- Breadcrumb -->
        <div class="flex flex-wrap items-center gap-2 my-10 md:my-10 text-sm md:text-base">
            <a href="{{ route('home') }}" class="text-gray-500 hover:underline">Trang chủ</a>
            <span>/</span>
            <span>Đăng ký trở thành người bán</span>
        </div>
        <div class="p-6 w-full shadow-[0_0_10px_0_rgba(0,0,0,0.1)] rounded-[10px]">
            <!-- Stepper -->
            @include('seller.register.stepper')
            <script>
                updateStepper(0);
            </script>

            <!-- Form -->
            <div class="bg-white rounded-2xl p-6">
                <form>
                    <div class="flex justify-center gap-[42px]">
                        <div class="w-1/1 flex justify-center flex-col gap-[50px] text-right">
                            <label class="text-gray-700"><sup class="text-red-500 text-[12px]">*</sup>Tên Shop:</label>
                            <label class="text-gray-700"><sup class="text-red-500 text-[12px]">*</sup>Địa chỉ lấy
                                hàng:</label>
                            <label class="text-gray-700"><sup class="text-red-500 text-[12px]">*</sup>Email:</label>
                            <label class="text-gray-700"><sup class="text-red-500 text-[12px]">*</sup>Số điện
                                thoại:</label>
                        </div>
                        <div class="w-2/4 flex flex-col gap-8">
                            <div class="">
                                <input type="text"
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    name="shop_name" placeholder="">
                            </div>
                            <div class="">
                                <button type="button" onclick="openModal()"
                                    class="mt-2 border border-gray-300 text-gray-700 px-4 py-1 rounded hover:bg-gray-100">
                                    + Thêm địa chỉ
                                </button>
                            </div>
                            <div class="">
                                <input type="email"
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    name="email" placeholder="">
                            </div>
                            <div class="">
                                <input type="text"
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    name="phone" placeholder="">
                            </div>
                        </div>
                    </div>
                    <!-- Hr -->
                    <hr class="my-10">
                    <!-- Button -->
                    <div class="flex justify-between">
                        <div class="">
                            <button type="button" class="px-4 py-2 bg-white border rounded hover:bg-gray-100">Quay
                                lại</button>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="submit" class="px-4 py-2 bg-white border rounded hover:bg-gray-100">Lưu</button>
                            <button type="button" class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700">Tiếp
                                theo</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Modal -->
        @include('seller.register.modal')
    </div>
@endsection
