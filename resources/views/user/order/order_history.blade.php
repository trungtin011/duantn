@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lấy tất cả các nút tab và nội dung tab
            const tabs = document.querySelectorAll('#orderStatusTabs button');
            const tabContents = document.querySelectorAll('.tab-content .tab-pane');

            // Xử lý sự kiện nhấp vào tab
            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Loại bỏ class active từ tất cả các tab
                    tabs.forEach(t => t.classList.remove('active', 'text-black', 'border-b-2',
                        'border-black'));
                    // Thêm class active cho tab được chọn
                    this.classList.add('active', 'text-black', 'border-b-2', 'border-black');

                    // Ẩn tất cả nội dung tab
                    tabContents.forEach(content => content.classList.add('hidden'));
                    // Hiển thị nội dung tab tương ứng
                    const target = document.querySelector(this.getAttribute('data-target'));
                    if (target) {
                        target.classList.remove('hidden');
                    }
                });
            });

            // Kích hoạt tab mặc định (Tất cả)
            const defaultTab = document.querySelector('#all-tab');
            if (defaultTab) {
                defaultTab.classList.add('active', 'text-black', 'border-b-2', 'border-black');
                const defaultContent = document.querySelector(defaultTab.getAttribute('data-target'));
                if (defaultContent) {
                    defaultContent.classList.remove('hidden');
                }
            }
        });
    </script>
@endpush

@section('content')
    <div class="container mx-auto py-5">
        <div class="w-full mx-auto px-4 sm:px-0">
            <!-- breadcrumb -->
            <div class="flex flex-wrap items-center gap-2 mb-10 px-[10px] sm:px-0 md:mb-20 text-sm md:text-base">
                <a href="{{ route('home') }}" class="text-gray-500 hover:underline">Trang chủ</a>
                <span>/</span>
                <span>Lịch sử đơn hàng</span>
            </div>

            {{-- Tabs for Order Status --}}
            <ul class="flex items-center justify-between border border-gray-200 px-4 py-4 mb-[35px] overflow-x-auto"
                id="orderStatusTabs" role="tablist">
                <li class="mr-4" role="presentation">
                    <button class="px-2 font-bold text-gray-500 hover:text-black focus:outline-none" id="all-tab"
                        data-target="#all" type="button" role="tab" aria-controls="all">
                        Tất cả
                    </button>
                </li>
                <li class="mr-4" role="presentation">
                    <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="processing-tab"
                        data-target="#processing" type="button" role="tab" aria-controls="processing">
                        Đang xử lý
                    </button>
                </li>
                <li class="mr-4" role="presentation">
                    <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="awaiting-pickup-tab"
                        data-target="#awaiting-pickup" type="button" role="tab" aria-controls="awaiting-pickup">
                        Chờ lấy hàng
                    </button>
                </li>
                <li class="mr-4" role="presentation">
                    <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="in-delivery-tab"
                        data-target="#in-delivery" type="button" role="tab" aria-controls="in-delivery">
                        Đang giao hàng
                    </button>
                </li>
                <li class="mr-4" role="presentation">
                    <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="completed-tab"
                        data-target="#completed" type="button" role="tab" aria-controls="completed">
                        Hoàn thành
                    </button>
                </li>
                <li class="mr-4" role="presentation">
                    <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="cancelled-tab"
                        data-target="#cancelled" type="button" role="tab" aria-controls="cancelled">
                        Đã hủy
                    </button>
                </li>
                <li class="mr-4" role="presentation">
                    <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="returns-tab"
                        data-target="#returns" type="button" role="tab" aria-controls="returns">
                        Trả hàng/Hoàn tiền
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="orderStatusTabsContent">
                {{-- Tab Pane: Tất cả (All) --}}
                <div class="tab-pane" id="all" role="tabpanel" aria-labelledby="all-tab">
                    <!-- Nội dung tab "Tất cả" đã có, giữ nguyên -->
                    <div class="bg-white shadow-sm rounded-lg mb-4">
                        <div class="flex items-center justify-between py-6 px-4 sm:px-6 border-b border-gray-200">
                            <div class="flex items-center">
                                <h6 class="font-bold text-base sm:text-lg mr-3 mb-0">Shop Quần Áo XYZ</h6>
                                <button
                                    class="bg-black border border-gray-500 text-white px-2 sm:px-3 py-2 rounded text-xs sm:text-sm flex items-center mr-2 hover:bg-white hover:text-black">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                    </svg>
                                    Chat
                                </button>
                                <button
                                    class="border border-gray-500 text-gray-700 px-2 sm:px-3 py-2 rounded text-xs sm:text-sm flex items-center hover:bg-[#DB4444] hover:text-white hover:border-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                                    </svg>
                                    Xem Shop
                                </button>
                            </div>
                        </div>
                        <div class="px-4 sm:px-6 py-2">
                            <!-- Nội dung sản phẩm giữ nguyên -->
                            <div class="flex justify-between py-3">
                                <div class="flex">
                                    <div
                                        class="w-[160px] h-[160px] bg-gray-100 rounded flex items-center justify-center mr-3 overflow-hidden">
                                        <img src="https://cdn.kkfashion.vn/26926-large_default/ao-thun-nu-mau-den-in-hinh-buom-asm16-35.jpg"
                                            alt="Quần jean slimfit" class="object-contain w-full h-full">
                                    </div>
                                    <div class="flex flex-col gap-[30px] h-full p-[10px]">
                                        <div class="">
                                            <h6 class="font-normal text-sm sm:text-base mb-1">Quần jean slimfit (size 30)
                                            </h6>
                                        </div>
                                        <div class="">
                                            <p class="text-gray-500 text-xs sm:text-sm mb-0">Số lượng: 1</p>
                                            <p class="text-gray-500 text-xs sm:text-sm mb-0">Màu sắc: Trắng</p>
                                            <p class="text-gray-500 text-xs sm:text-sm mb-0">Kích thước: M</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-end pr-4">
                                    <span class="font-bold text-sm sm:text-base text-black">Giá: <span
                                            class="text-red-500">250.000đ</span></span>
                                </div>
                            </div>
                            <div class="my-3 border-b-2 border-dashed"></div>
                            <div class="flex justify-between py-3">
                                <div class="flex">
                                    <div
                                        class="w-[160px] h-[160px] bg-gray-100 rounded flex items-center justify-center mr-3 overflow-hidden">
                                        <img src="https://cdn.kkfashion.vn/26926-large_default/ao-thun-nu-mau-den-in-hinh-buom-asm16-35.jpg"
                                            alt="Quần jean slimfit" class="object-contain w-full h-full">
                                    </div>
                                    <div class="flex flex-col gap-[30px] h-full p-[10px]">
                                        <div class="">
                                            <h6 class="font-normal text-sm sm:text-base mb-1">Quần jean slimfit (size 30)
                                            </h6>
                                        </div>
                                        <div class="">
                                            <p class="text-gray-500 text-xs sm:text-sm mb-0">Số lượng: 1</p>
                                            <p class="text-gray-500 text-xs sm:text-sm mb-0">Màu sắc: Trắng</p>
                                            <p class="text-gray-500 text-xs sm:text-sm mb-0">Kích thước: M</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-end pr-4">
                                    <span class="font-bold text-sm sm:text-base text-black">Giá: <span
                                            class="text-red-500">250.000đ</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 py-3 px-4 sm:px-6 flex justify-end items-center">
                            <span class="font-bold text-sm sm:text-base mr-4">Thành tiền: <span
                                    class="text-red-500">448.000đ</span></span>
                            <button
                                class="bg-[#DB4444] text-white px-3 sm:px-4 py-1 sm:py-2 text-xs sm:text-sm mr-2 hover:bg-[#CF4343]">Mua
                                Lại</button>
                            <button
                                class="border border-gray-500 text-gray-700 px-3 sm:px-4 py-1 sm:py-2 text-xs sm:text-sm mr-2 hover:bg-black hover:text-white">Liên
                                hệ người bán</button>
                            <button
                                class="border border-gray-500 text-gray-700 px-3 sm:px-4 py-1 sm:py-2 text-xs sm:text-sm hover:bg-black hover:text-white">Xem
                                chi tiết</button>
                        </div>
                    </div>
                    <!-- Các order block khác giữ nguyên -->
                    <div class="bg-white shadow-sm rounded-lg mb-4">
                        <div class="flex items-center justify-between py-6 px-4 sm:px-6 border-b border-gray-200">
                            <div class="flex items-center">
                                <h6 class="font-bold text-base sm:text-lg mr-3 mb-0">Cửa hàng Điện tử VNK</h6>
                                <button
                                    class="bg-black border border-gray-500 text-white px-2 sm:px-3 py-2 rounded text-xs sm:text-sm flex items-center mr-2 hover:bg-white hover:text-black">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                    </svg>
                                    Chat
                                </button>
                                <button
                                    class="border border-gray-500 text-gray-700 px-2 sm:px-3 py-2 rounded text-xs sm:text-sm flex items-center hover:bg-[#DB4444] hover:text-white hover:border-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                                    </svg>
                                    Xem Shop
                                </button>
                            </div>
                        </div>
                        <div class="px-4 sm:px-6 py-2">
                            <div class="flex justify-between py-3">
                                <div class="flex">
                                    <div
                                        class="w-[160px] h-[160px] bg-gray-100 rounded flex items-center justify-center mr-3 overflow-hidden">
                                        <img src="https://img.lazcdn.com/g/p/1b98b91e8a6c72a80536d69c5450d99c.jpg_720x720q80.jpg"
                                            alt="Tai nghe không dây" class="object-contain w-full h-full">
                                    </div>
                                    <div class="flex flex-col gap-[30px] h-full p-[10px]">
                                        <div>
                                            <h6 class="font-normal text-sm sm:text-base mb-1">Tai nghe Bluetooth X500</h6>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 text-xs sm:text-sm mb-0">Số lượng: 1</p>
                                            <p class="text-gray-500 text-xs sm:text-sm mb-0">Màu sắc: Đen</p>
                                            <p class="text-gray-500 text-xs sm:text-sm mb-0">Kích thước: Free Size</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-end pr-4">
                                    <span class="font-bold text-sm sm:text-base text-black">Giá: <span
                                            class="text-red-500">799.000đ</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 py-3 px-4 sm:px-6 flex justify-end items-center">
                            <span class="font-bold text-sm sm:text-base mr-4">Thành tiền: <span
                                    class="text-red-500">799.000đ</span></span>
                            <button
                                class="border border-gray-500 text-gray-700 px-3 sm:px-4 py-1 sm:py-2 text-xs sm:text-sm mr-2 hover:bg-black hover:text-white">Liên
                                hệ người bán</button>
                            <button
                                class="border border-gray-500 text-gray-700 px-3 sm:px-4 py-1 sm:py-2 text-xs sm:text-sm hover:bg-black hover:text-white">Xem
                                chi tiết</button>
                        </div>
                    </div>
                    <div class="bg-white shadow-sm rounded-lg mb-4">
                        <div class="flex items-center justify-between py-6 px-4 sm:px-6 border-b border-gray-200">
                            <div class="flex items-center">
                                <h6 class="font-bold text-base sm:text-lg mr-3 mb-0">Hiệu sách Trí Thức</h6>
                                <button
                                    class="bg-black border border-gray-500 text-white px-2 sm:px-3 py-2 rounded text-xs sm:text-sm flex items-center mr-2 hover:bg-white hover:text-black">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                    </svg>
                                    Chat
                                </button>
                                <button
                                    class="border border-gray-500 text-gray-700 px-2 sm:px-3 py-2 rounded text-xs sm:text-sm flex items-center hover:bg-[#DB4444] hover:text-white hover:border-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                                    </svg>
                                    Xem Shop
                                </button>
                            </div>
                        </div>
                        <div class="px-4 sm:px-6 py-2">
                            <div class="flex justify-between py-3">
                                <div class="flex">
                                    <div
                                        class="w-[160px] h-[160px] bg-gray-100 rounded flex items-center justify-center mr-3 overflow-hidden">
                                        <img src="https://static.ladipage.net/5b568b804dbc7c3412de46f4/bia-lap-trinh-20230228071751-ovq5s.png"
                                            alt="Sách Lập trình Laravel" class="object-contain w-full h-full">
                                    </div>
                                    <div class="flex flex-col gap-[30px] h-full p-[10px]">
                                        <div>
                                            <h6 class="font-normal text-sm sm:text-base mb-1">Sách: Lập trình từ
                                                A-Z</h6>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 text-xs sm:text-sm mb-0">Số lượng: 1</p>
                                            <p class="text-gray-500 text-xs sm:text-sm mb-0">Màu sắc: N/A</p>
                                            <p class="text-gray-500 text-xs sm:text-sm mb-0">Kích thước: N/A</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-end pr-4">
                                    <span class="font-bold text-sm sm:text-base text-black">Giá: <span
                                            class="text-red-500">320.000đ</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 py-3 px-4 sm:px-6 flex justify-end items-center">
                            <span class="font-bold text-sm sm:text-base mr-4">Thành tiền: <span
                                    class="text-red-500">320.000đ</span></span>
                            <button
                                class="border border-gray-500 text-gray-700 px-3 sm:px-4 py-1 sm:py-2 text-xs sm:text-sm hover:bg-black hover:text-white">Xem
                                chi tiết</button>
                        </div>
                    </div>
                </div>

                {{-- Other Tab Panes (empty for this sample) --}}
                <div class="tab-pane hidden" id="processing" role="tabpanel" aria-labelledby="processing-tab">
                    <div class="bg-white shadow-sm rounded-lg text-center py-5">
                        <div class="p-4">
                            <h5 class="text-gray-500 text-base sm:text-lg">Bạn không có đơn hàng nào đang xử lý.</h5>
                        </div>
                    </div>
                </div>
                <div class="tab-pane hidden" id="awaiting-pickup" role="tabpanel" aria-labelledby="awaiting-pickup-tab">
                    <div class="bg-white shadow-sm rounded-lg text-center py-5">
                        <div class="p-4">
                            <h5 class="text-gray-500 text-base sm:text-lg">Bạn không có đơn hàng nào chờ lấy hàng.</h5>
                        </div>
                    </div>
                </div>
                <div class="tab-pane hidden" id="in-delivery" role="tabpanel" aria-labelledby="in-delivery-tab">
                    <div class="bg-white shadow-sm rounded-lg text-center py-5">
                        <div class="p-4">
                            <h5 class="text-gray-500 text-base sm:text-lg">Bạn không có đơn hàng nào đang giao hàng.</h5>
                        </div>
                    </div>
                </div>
                <div class="tab-pane hidden" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                    <div class="bg-white shadow-sm rounded-lg text-center py-5">
                        <div class="p-4">
                            <h5 class="text-gray-500 text-base sm:text-lg">Bạn không có đơn hàng nào đã hoàn thành.</h5>
                        </div>
                    </div>
                </div>
                <div class="tab-pane hidden" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                    <div class="bg-white shadow-sm rounded-lg text-center py-5">
                        <div class="p-4">
                            <h5 class="text-gray-500 text-base sm:text-lg">Bạn không có đơn hàng nào đã hủy.</h5>
                        </div>
                    </div>
                </div>
                <div class="tab-pane hidden" id="returns" role="tabpanel" aria-labelledby="returns-tab">
                    <div class="bg-white shadow-sm rounded-lg text-center py-5">
                        <div class="p-4">
                            <h5 class="text-gray-500 text-base sm:text-lg">Bạn không có đơn hàng nào đang yêu cầu trả
                                hàng/hoàn tiền.</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
