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
                updateStepper(1);
            </script>
            <!-- Card Form -->
            <div class="bg-white rounded-2xl p-6">
                <form>
                    <!-- Hỏa Tốc -->
                    <div class="mb-10 flex flex-col gap-5">
                        <div class="flex justify-between">
                            <label class="text-[18px]">Hỏa Tốc</label>
                            <button type="button" onclick="toggleSection('express-section', this)"
                                class="ms-2 text-[14px] border rounded-[4px] px-[10px] py-[5px]">
                                Thu gọn <i class="fa-solid fa-chevron-up ms-2"></i>
                            </button>
                        </div>
                        <div id="express-section"
                            class="flex items-center border rounded-[4px] px-[16px] py-[20px] text-[16px]">
                            <span class="ms-2 pl-[10px]">Hỏa tốc</span>
                            <span class="text-[#DB4444] ms-2">[COD đã được kích hoạt]</span>
                            <label class="relative ms-auto inline-flex items-center cursor-pointer pr-[10px]">
                                <input type="checkbox" class="sr-only peer" checked />
                                <div
                                    class="w-11 h-6 bg-gray-300 peer-checked:bg-green-500 rounded-full peer transition-all duration-300">
                                </div>
                                <div
                                    class="absolute left-0.5 top-0.5 bg-white w-5 h-5 rounded-full transition-all duration-300 peer-checked:translate-x-full">
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Các phần khác -->
                    <!-- Nhanh -->
                    <div class="mb-10 flex flex-col gap-5">
                        <div class="flex justify-between">
                            <label class="text-[18px]">Nhanh</label>
                            <button type="button" onclick="toggleSection('fast-section', this)"
                                class="ms-2 text-[14px] border rounded-[4px] px-[10px] py-[5px]">
                                Thu gọn <i class="fa-solid fa-chevron-up ms-2"></i>
                            </button>
                        </div>
                        <div id="fast-section"
                            class="flex items-center border rounded-[4px] px-[16px] py-[20px] text-[16px]">
                            <span class="ms-2 pl-[10px]">Nhanh</span>
                            <span class="text-[#DB4444] ms-2">[COD đã được kích hoạt]</span>
                            <label class="relative ms-auto inline-flex items-center cursor-pointer pr-[10px]">
                                <input type="checkbox" class="sr-only peer" checked />
                                <div
                                    class="w-11 h-6 bg-gray-300 peer-checked:bg-green-500 rounded-full peer transition-all duration-300">
                                </div>
                                <div
                                    class="absolute left-0.5 top-0.5 bg-white w-5 h-5 rounded-full transition-all duration-300 peer-checked:translate-x-full">
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Tiết Kiệm -->
                    <div class="mb-10 flex flex-col gap-5">
                        <div class="flex justify-between">
                            <label class="text-[18px]">Tiết Kiệm</label>
                            <button type="button" onclick="toggleSection('economy-section', this)"
                                class="ms-2 text-[14px] border rounded-[4px] px-[10px] py-[5px]">
                                Thu gọn <i class="fa-solid fa-chevron-up ms-2"></i>
                            </button>
                        </div>
                        <div id="economy-section"
                            class="flex items-center border rounded-[4px] px-[16px] py-[20px] text-[16px]">
                            <span class="ms-2 pl-[10px]">Tiết Kiệm</span>
                            <span class="text-[#DB4444] ms-2">[COD đã được kích hoạt]</span>
                            <label class="relative ms-auto inline-flex items-center cursor-pointer pr-[10px]">
                                <input type="checkbox" class="sr-only peer" checked />
                                <div
                                    class="w-11 h-6 bg-gray-300 peer-checked:bg-green-500 rounded-full peer transition-all duration-300">
                                </div>
                                <div
                                    class="absolute left-0.5 top-0.5 bg-white w-5 h-5 rounded-full transition-all duration-300 peer-checked:translate-x-full">
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Tự Nhận Hàng -->
                    <div class="mb-10 flex flex-col gap-5">
                        <div class="flex justify-between">
                            <label class="text-[18px]">Tự Nhận Hàng</label>
                            <button type="button" onclick="toggleSection('pickup-section', this)"
                                class="ms-2 text-[14px] border rounded-[4px] px-[10px] py-[5px]">
                                Thu gọn <i class="fa-solid fa-chevron-up ms-2"></i>
                            </button>
                        </div>
                        <div id="pickup-section"
                            class="flex items-center border rounded-[4px] px-[16px] py-[20px] text-[16px]">
                            <span class="ms-2 pl-[10px]">Tự Nhận Hàng</span>
                            <label class="relative ms-auto inline-flex items-center cursor-pointer pr-[10px]">
                                <input type="checkbox" class="sr-only peer" checked />
                                <div
                                    class="w-11 h-6 bg-gray-300 peer-checked:bg-green-500 rounded-full peer transition-all duration-300">
                                </div>
                                <div
                                    class="absolute left-0.5 top-0.5 bg-white w-5 h-5 rounded-full transition-all duration-300 peer-checked:translate-x-full">
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Hàng công kềnh -->
                    <div class="mb-10 flex flex-col gap-5">
                        <div class="flex justify-between">
                            <label class="text-[18px]">Hàng công kềnh</label>
                            <button type="button" onclick="toggleSection('heavy-section', this)"
                                class="ms-2 text-[14px] border rounded-[4px] px-[10px] py-[5px]">
                                Thu gọn <i class="fa-solid fa-chevron-up ms-2"></i>
                            </button>
                        </div>
                        <div id="heavy-section"
                            class="flex items-center border rounded-[4px] px-[16px] py-[20px] text-[16px]">
                            <span class="ms-2 pl-[10px]">Hàng công kềnh</span>
                            <span class="text-[#DB4444] ms-2">[COD đã được kích hoạt]</span>
                            <label class="relative ms-auto inline-flex items-center cursor-pointer pr-[10px]">
                                <input type="checkbox" class="sr-only peer" checked />
                                <div
                                    class="w-11 h-6 bg-gray-300 peer-checked:bg-green-500 rounded-full peer transition-all duration-300">
                                </div>
                                <div
                                    class="absolute left-0.5 top-0.5 bg-white w-5 h-5 rounded-full transition-all duration-300 peer-checked:translate-x-full">
                                </div>
                            </label>
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
    </div>
@endsection
