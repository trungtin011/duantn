@extends('layouts.seller')

@section('content')
    <style>
        .stepper-step {
            position: relative;
        }

        .step-dot {
            width: 24px;
            height: 24px;
            background-color: #e5e7eb;
            border-radius: 50%;
            margin: 0 auto 8px;
            transition: background-color 0.3s;
        }

        .step-dot.active {
            background-color: #ef4444;
        }

        .step-label {
            font-size: 14px;
            color: #6b7280;
        }

        .stepper-line {
            flex: 1;
            height: 2px;
            background-color: #e5e7eb;
            margin: 0 10px;
        }
    </style>

    <div class="container bg-white mx-auto py-5 mt-5 flex flex-col items-center" style="min-height: 80vh;">
        <div class="w-full" style="max-width: 1000px;">
            <!-- Breadcrumb -->

            <!-- Stepper -->
            <div class="px-4 py-4">
                <div class="flex items-center justify-between relative mb-8">
                    <div class="stepper-step text-center flex-1">
                        <div class="step-dot active"></div>
                        <div class="step-label">Thông tin shop</div>
                    </div>
                    <div class="stepper-line"></div>
                    <div class="stepper-step text-center flex-1">
                        <div class="step-dot"></div>
                        <div class="step-label">Cài đặt vận chuyển</div>
                    </div>
                    <div class="stepper-line"></div>
                    <div class="stepper-step text-center flex-1">
                        <div class="step-dot"></div>
                        <div class="step-label">Thông tin thuế</div>
                    </div>
                    <div class="stepper-line"></div>
                    <div class="stepper-step text-center flex-1">
                        <div class="step-dot"></div>
                        <div class="step-label">Thực tính danh</div>
                    </div>
                    <div class="stepper-line"></div>
                    <div class="stepper-step text-center flex-1">
                        <div class="step-dot"></div>
                        <div class="step-label">Hoàn thành</div>
                    </div>
                </div>
            </div>

            <!-- hr -->
            <div class="w-full h-[1px] bg-gray-200"></div>
            <!-- Form -->
            <div class="bg-white shadow-sm rounded-2xl p-6">
                <form>
                    <div class="mb-4 flex items-center">
                        <label class="w-1/4 font-semibold text-gray-700">Tên người dùng:</label>
                        <div class="w-3/4">
                            <input type="text"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                name="username" placeholder="">
                        </div>
                    </div>
                    <div class="mb-4 flex items-center">
                        <label class="w-1/4 font-semibold text-gray-700">Tên Shop:</label>
                        <div class="w-3/4">
                            <input type="text"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                name="shop_name" placeholder="">
                        </div>
                    </div>
                    <div class="mb-4 flex items-start">
                        <label class="w-1/4 font-semibold text-gray-700">Địa chỉ lấy hàng:</label>
                        <div class="w-3/4">
                            <input type="text"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                name="address" placeholder="">
                            <button type="button"
                                class="mt-2 border border-gray-300 text-gray-700 px-4 py-1 rounded hover:bg-gray-100">+ Thêm
                                địa chỉ</button>
                        </div>
                    </div>
                    <div class="mb-4 flex items-center">
                        <label class="w-1/4 font-semibold text-gray-700">Email:</label>
                        <div class="w-3/4">
                            <input type="email"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                name="email" placeholder="">
                        </div>
                    </div>
                    <div class="mb-6 flex items-center">
                        <label class="w-1/4 font-semibold text-gray-700">Số điện thoại:</label>
                        <div class="w-3/4">
                            <input type="text"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                name="phone" placeholder="">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="submit"
                            class="border border-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-100">Lưu</button>
                        <button type="button" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700">Tiếp
                            theo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
