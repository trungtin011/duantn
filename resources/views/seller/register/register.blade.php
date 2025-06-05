@extends('layouts.seller')

@section('content')
    <div class="container mx-auto py-5 flex flex-col" style="min-height: 80vh;">
        <!-- Breadcrumb -->
        <div class="flex flex-wrap items-center gap-2 my-10 text-sm md:text-base">
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
                <form method="POST" action="{{ route('seller.register.step1') }}" enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                        <div class="mb-4 text-red-600">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="flex justify-center">
                        <div class="w-full max-w-3xl grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                            <!-- Labels Column -->
                            <div class="flex flex-col gap-8 text-right pt-1">
                                <label class="text-gray-700 flex items-center justify-end">
                                    <sup class="text-red-500 text-[12px]">*</sup>Tên Shop:
                                </label>
                                <label class="text-gray-700 flex items-center justify-end">
                                    <sup class="text-red-500 text-[12px]">*</sup>Địa chỉ lấy hàng:
                                </label>
                                <label class="text-gray-700 flex items-center justify-end">
                                    <sup class="text-red-500 text-[12px]">*</sup>Email:
                                </label>
                                <label class="text-gray-700 flex items-center justify-end">
                                    <sup class="text-red-500 text-[12px]">*</sup>Số điện thoại:
                                </label>
                                <label class="text-gray-700 flex items-center justify-end">Mô tả shop:</label>
                                <label class="text-gray-700 flex items-center justify-end">Logo shop:</label>
                                <label class="text-gray-700 flex items-center justify-end">Banner shop:</label>
                            </div>

                            <!-- Inputs Column -->
                            <div class="flex flex-col gap-8 col-span-2">
                                <input type="text" name="shop_name" value="{{ old('shop_name') }}"
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Nhập tên shop" maxlength="100" required>
                                <input type="text" name="address" value="{{ old('address') }}"
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Nhập địa chỉ lấy hàng" maxlength="255" required>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Nhập email shop" maxlength="100" required>
                                <input type="text" name="phone" value="{{ old('phone') }}"
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Nhập số điện thoại shop" maxlength="11" required>
                                <textarea name="shop_description" rows="3"
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Mô tả shop (bắt buộc)" required maxlength="65535">{{ old('shop_description') }}</textarea>
                                <div>
                                    <input type="file" name="shop_logo" accept="image/*"
                                        class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required>
                                    <span class="text-xs text-gray-500">Chọn ảnh logo shop (bắt buộc, jpg/png/jpeg, tối đa 2MB)</span>
                                </div>
                                <div>
                                    <input type="file" name="shop_banner" accept="image/*"
                                        class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required>
                                    <span class="text-xs text-gray-500">Chọn ảnh banner shop (bắt buộc, jpg/png/jpeg, tối đa 4MB)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hr -->
                    <hr class="my-10">

                    <!-- Buttons -->
                    <div class="flex justify-between">
                        <a href="{{ route('home') }}"
                            class="px-4 py-2 bg-white border rounded hover:bg-gray-100">Quay lại</a>
                        <button type="submit"
                            class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700">Tiếp theo</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal -->
        @include('seller.register.modal')
    </div>
@endsection