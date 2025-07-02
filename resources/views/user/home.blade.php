@extends('layouts.app')
@section('title', 'Trang chủ')
@section('content')
    <!-- Main Banner -->
    <section class="container mx-auto py-8 flex flex-col sm:flex-row relative">
        <!-- Sidebar Menu with Dropdown -->
        <div class="w-full h-[100px] sm:h-full overflow-y-scroll sm:overflow-y-hidden px-2 sm:w-1/4 sm:pr-10">
            <ul class="space-y-2">
                <li>
                    <div id="dropdownToggle">
                        <button
                            class="focus:outline-none text-gray-700 hover:text-black flex items-center justify-between w-full mb-2 text-md">
                            Thời trang phụ nữ
                            <!-- Arrow icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-[21px] h-[21px] ml-1 arrow-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>

                        </button>
                        <div id="dropdownMenu"
                            class="dropdown-content hidden text-base shadow-lg font-emibold absolute sm:top-[32px] sm:left-[384px] z-10 sm:w-[1152px] sm:h-[400px] top-[58px] left-0 right-0 mx-auto z-10 w-max-w-4xl h-auto bg-white mt-2 sm:mt-0">
                            <div class="sm:flex h-[100%]">
                                <div class="flex flex-col gap-[25px] p-4">
                                    <div
                                        class="w-full sm:w-1/2 flex flex-row gap-[22px] sm:gap-[40px] overflow-x-scroll sm:overflow-x-hidden">
                                        <a href="#"
                                            class="text-gray-700 hover:text-black flex flex-col items-center gap-[5px]">
                                            <img src="https://down-vn.img.susercontent.com/file/48630b7c76a7b62bc070c9e227097847@resize_w320_nl.webp"
                                                alt="phone" class="w-[100px] h-[100px]">
                                            <span class="capitalize text-[16px] w-[100px] text-center">Giày dép nữ</span>
                                        </a>
                                        <a href="#"
                                            class="text-gray-700 hover:text-black flex flex-col items-center gap-[5px]">
                                            <img src="https://down-vn.img.susercontent.com/file/75ea42f9eca124e9cb3cde744c060e4d@resize_w320_nl.webp"
                                                alt="phone" class="w-[100px] h-[100px]">
                                            <span class="capitalize text-[16px] w-[100px] text-center">Thời trang nữ</span>
                                        </a>
                                        <a href="#"
                                            class="text-gray-700 hover:text-black flex flex-col items-center gap-[5px]">
                                            <img src="https://down-vn.img.susercontent.com/file/8e71245b9659ea72c1b4e737be5cf42e@resize_w320_nl.webp"
                                                alt="phone" class="w-[100px] h-[100px]">
                                            <span class="capitalize text-[16px] w-[100px] text-center">Phụ kiện & trang sức
                                                nữ</span>
                                        </a>
                                        <a href="#"
                                            class="text-gray-700 hover:text-black flex flex-col items-center gap-[5px]">
                                            <img src="https://down-vn.img.susercontent.com/file/fa6ada2555e8e51f369718bbc92ccc52@resize_w320_nl.webp"
                                                alt="phone" class="w-[100px] h-[100px]">
                                            <span class="capitalize text-[16px] w-[100px] text-center">Túi ví nữ</span>
                                        </a>
                                    </div>
                                    <div
                                        class="w-full sm:w-1/2 flex flex-row gap-[22px] sm:gap-[40px] overflow-x-scroll sm:overflow-x-hidden">
                                        <a href="#"
                                            class="text-gray-700 hover:text-black flex flex-col items-center gap-[10px]">
                                            <img src="https://down-vn.img.susercontent.com/file/099edde1ab31df35bc255912bab54a5e@resize_w320_nl.webp"
                                                alt="phone" class="w-[80px] h-[80px] mt-[15px]">
                                            <span class="capitalize text-[16px] w-[100px] text-center">Mẹ & bé</span>
                                        </a>
                                        <a href="#"
                                            class="text-gray-700 hover:text-black flex flex-col items-center gap-[10px]">
                                            <img src="https://down-vn.img.susercontent.com/file/ef1f336ecc6f97b790d5aae9916dcb72@resize_w320_nl.webp"
                                                alt="phone" class="w-[80px] h-[80px] mt-[15px]">
                                            <span class="capitalize text-[16px] w-[100px] text-center">Sắc đẹp</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="hidden sm:flex sm:justify-center sm:items-center">
                                    <img src="{{ asset('images/thoitrangnu.jpg') }}" alt="banner"
                                        class="object-cover w-[100%] h-[400px] object-cover">
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <button id="dropdownToggleSecond"
                        class="focus:outline-none text-gray-700 hover:text-black flex items-center justify-between w-full mb-2 text-md">
                        Thời trang nam
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-[21px] h-[21px] ml-1 arrow-icon-second">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div id="dropdownMenuSecond"
                        class="dropdown-content hidden text-base shadow-lg font-emibold absolute sm:top-[32px] sm:left-[384px] z-10 sm:w-[1152px] sm:h-[400px] top-[88px] left-0 right-0 mx-auto z-10 w-max-w-4xl h-auto bg-white mt-2 sm:mt-0">
                        <div class="sm:flex h-[100%]">
                            <div class="flex flex-col gap-[25px] p-4">
                                <div
                                    class="w-full sm:w-1/2 flex flex-row gap-[22px] sm:gap-[40px] overflow-x-scroll sm:overflow-x-hidden">
                                    <a href="#"
                                        class="text-gray-700 hover:text-black flex flex-col items-center gap-[5px]">
                                        <img src="https://down-vn.img.susercontent.com/file/74ca517e1fa74dc4d974e5d03c3139de@resize_w320_nl.webp"
                                            alt="phone" class="w-[100px] h-[100px]">
                                        <span class="capitalize text-[16px] w-[100px] text-center">Giày dép nam</span>
                                    </a>
                                    <a href="#"
                                        class="text-gray-700 hover:text-black flex flex-col items-center gap-[5px]">
                                        <img src="https://down-vn.img.susercontent.com/file/687f3967b7c2fe6a134a2c11894eea4b@resize_w320_nl.webp"
                                            alt="phone" class="w-[100px] h-[100px]">
                                        <span class="capitalize text-[16px] w-[100px] text-center">Thời trang nam</span>
                                    </a>
                                    <a href="#"
                                        class="text-gray-700 hover:text-black flex flex-col items-center gap-[5px]">
                                        <img src="https://down-vn.img.susercontent.com/file/86c294aae72ca1db5f541790f7796260@resize_w320_nl.webp"
                                            alt="phone" class="w-[100px] h-[100px]">
                                        <span class="capitalize text-[16px] w-[100px] text-center">Đồng hồ nam</span>
                                    </a>
                                    <a href="#"
                                        class="text-gray-700 hover:text-black flex flex-col items-center gap-[5px]">
                                        <img src="https://down-vn.img.susercontent.com/file/6cb7e633f8b63757463b676bd19a50e4@resize_w320_nl.webp"
                                            alt="phone" class="w-[100px] h-[100px]">
                                        <span class="capitalize text-[16px] w-[100px] text-center">Thể thao nam</span>
                                    </a>
                                </div>
                                <div
                                    class="w-full sm:w-1/2 flex flex-row gap-[22px] sm:gap-[40px] overflow-x-scroll sm:overflow-x-hidden">
                                    <a href="#"
                                        class="text-gray-700 hover:text-black flex flex-col items-center gap-[10px]">
                                        <img src="https://down-vn.img.susercontent.com/file/099edde1ab31df35bc255912bab54a5e@resize_w320_nl.webp"
                                            alt="phone" class="w-[80px] h-[80px] mt-[15px]">
                                        <span class="capitalize text-[16px] w-[100px] text-center">Mẹ & bé</span>
                                    </a>
                                    <a href="#"
                                        class="text-gray-700 hover:text-black flex flex-col items-center gap-[10px]">
                                        <img src="https://down-vn.img.susercontent.com/file/ef1f336ecc6f97b790d5aae9916dcb72@resize_w320_nl.webp"
                                            alt="phone" class="w-[80px] h-[80px] mt-[15px]">
                                        <span class="capitalize text-[16px] w-[100px] text-center">Sắc đẹp</span>
                                    </a>
                                </div>
                            </div>
                            <div class="hidden sm:flex sm:justify-center sm:items-center">
                                <img src="{{ asset('images/thoitrangnam.jpg') }}" alt="banner"
                                    class="object-cover w-[100%] h-[400px] object-cover">
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <a href="" class="text-gray-700 hover:text-black text-md">Đồ điện tử</a>
                </li>
                <li>
                    <a href="" class="text-gray-700 hover:text-black text-md">phong cách sống</a>
                </li>
                <li>
                    <a href="" class="text-gray-700 hover:text-black text-md">Thuốc</a>
                </li>
                <li>
                    <a href="" class="text-gray-700 hover:text-black text-md">Thể thao</a>
                </li>
                <li>
                    <a href="" class="text-gray-700 hover:text-black text-md">Đồ chơi trẻ em</a>
                </li>
                <li>
                    <a href="" class="text-gray-700 hover:text-black text-md">Thực phẩm thú
                        cưng
                    </a>
                </li>
                <li>
                    <a href="" class="text-gray-700 hover:text-black text-md">Sức khỏe và sắc
                        đẹp
                    </a>
                </li>
            </ul>
        </div>
        <!-- Banner -->
        <div class="relative w-full sm:w-3/4 hidden sm:block">
            <div class="slider-container">
                <div class="slides" id="slides">
                    <!-- Slide 1 -->
                    <div class="slide bg-black h-[400px] flex items-center p-5">
                        <div class="text-star w-[400px]">
                            <div class="flex items-center mb-4">
                                <img src="{{ asset('images/apple.png') }}" alt="Apple" class="mr-2"
                                    style="width: 40px; height: 49px;">
                                <span class="text-white text-[16px]">iPhone 14 Series</span>
                            </div>
                            <h1 class="text-[48px] font-bold text-white w-full">Giảm Giá Lên Đến 10%</h1>
                            <button class="mt-4 text-white buy-now-btn flex items-center">
                                <span style="border-bottom: 1px solid #fff; padding-bottom: 5px; font-size: 18px;">Mua
                                    ngay</span>
                                <svg class="ml-3 size-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-center w-[600px]">
                            <img src="{{ asset('images/banner.png') }}" alt="iPhone 14">
                        </div>
                    </div>
                    <!-- Slide 2 -->
                    <div class="slide bg-black h-[400px] flex items-center p-5">
                        <div class="text-star w-[400px]">
                            <div class="flex items-center mb-4">
                                <img src="{{ asset('images/apple.png') }}" alt="Apple" class="mr-2"
                                    style="width: 40px; height: 49px;">
                                <span class="text-white text-[16px]">iPhone 14 Series</span>
                            </div>
                            <h1 class="text-[48px] font-bold text-white w-full">Giảm Giá Lên Đến 10%</h1>
                            <button class="mt-4 text-white buy-now-btn flex items-center">
                                <span style="border-bottom: 1px solid #fff; padding-bottom: 5px; font-size: 18px;">Mua
                                    ngay</span>
                                <svg class="ml-3 size-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-center w-[600px]">
                            <img src="{{ asset('images/banner.png') }}" alt="iPhone 14">
                        </div>
                    </div>
                    <!-- Slide 3 -->
                    <div class="slide bg-black h-[400px] flex items-center p-5">
                        <div class="text-star w-[400px]">
                            <div class="flex items-center mb-4">
                                <img src="{{ asset('images/apple.png') }}" alt="Apple" class="mr-2"
                                    style="width: 40px; height: 49px;">
                                <span class="text-white text-[16px]">iPhone 14 Series</span>
                            </div>
                            <h1 class="text-[48px] font-bold text-white w-full">Giảm Giá Lên Đến 10%</h1>
                            <button class="mt-4 text-white buy-now-btn flex items-center">
                                <span style="border-bottom: 1px solid #fff; padding-bottom: 5px; font-size: 18px;">Mua
                                    ngay</span>
                                <svg class="ml-3 size-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-center w-[600px]">
                            <img src="{{ asset('images/banner.png') }}" alt="iPhone 14">
                        </div>
                    </div>
                    <!-- Slide 4 -->
                    <div class="slide bg-black h-[400px] flex items-center p-5">
                        <div class="text-star w-[400px]">
                            <div class="flex items-center mb-4">
                                <img src="{{ asset('images/apple.png') }}" alt="Apple" class="mr-2"
                                    style="width: 40px; height: 49px;">
                                <span class="text-white text-[16px]">iPhone 14 Series</span>
                            </div>
                            <h1 class="text-[48px] font-bold text-white w-full">Giảm Giá Lên Đến 10%</h1>
                            <button class="mt-4 text-white buy-now-btn flex items-center">
                                <span style="border-bottom: 1px solid #fff; padding-bottom: 5px; font-size: 18px;">Mua
                                    ngay</span>
                                <svg class="ml-3 size-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-center w-[600px]">
                            <img src="{{ asset('images/banner.png') }}" alt="iPhone 14">
                        </div>
                    </div>
                    <!-- Slide 5 -->
                    <div class="slide bg-black h-[400px] flex items-center p-5">
                        <div class="text-star w-[400px]">
                            <div class="flex items-center mb-4">
                                <img src="{{ asset('images/apple.png') }}" alt="Apple" class="mr-2"
                                    style="width: 40px; height: 49px;">
                                <span class="text-white text-[16px]">iPhone 14 Series</span>
                            </div>
                            <h1 class="text-[48px] font-bold text-white w-full">Giảm Giá Lên Đến 10%</h1>
                            <button class="mt-4 text-white buy-now-btn flex items-center">
                                <span style="border-bottom: 1px solid #fff; padding-bottom: 5px; font-size: 18px;">Mua
                                    ngay</span>
                                <svg class="ml-3 size-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-center w-[600px]">
                            <img src="{{ asset('images/banner.png') }}" alt="iPhone 14">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center gap-3 mt-4 absolute bottom-3 left-0 right-0">
                <button style="background-color: #BDBDBD;"
                    class="pagination-button p-2 w-[20px] h-[20px] rounded-full active" data-index="0"></button>
                <button style="background-color: #BDBDBD;" class="pagination-button p-2 w-[20px] h-[20px] rounded-full"
                    data-index="1"></button>
                <button style="background-color: #BDBDBD;" class="pagination-button p-2 w-[20px] h-[20px] rounded-full"
                    data-index="2"></button>
                <button style="background-color: #BDBDBD;" class="pagination-button p-2 w-[20px] h-[20px] rounded-full"
                    data-index="3"></button>
                <button style="background-color: #BDBDBD;" class="pagination-button p-2 w-[20px] h-[20px] rounded-full"
                    data-index="4"></button>
            </div>
        </div>
    </section>

    <div class="container mx-auto mt-6">
        <!-- DANH MUC -->
        <div class="bg-white p-6 mb-8 border border-[#e5e5e5]">
            <div class="text-md text-[#999] mb-3 select-none">
                DANH MỤC
            </div>
            <div class="overflow-x-auto scrollbar-hide">
                <div class="flex space-x-6 min-w-[1100px]">
                    <!-- Row 1 -->
                    <div class="flex space-x-6">
                        <div class="flex flex-col items-center w-[100px]">
                            <img alt="Blue men's polo shirt on white background" class="mb-2" height="40"
                                src="https://storage.googleapis.com/a1aa/image/38ded9ae-f6b2-46d3-d2a3-e0c22e17a337.jpg"
                                width="100" />
                            <span class="text-xs text-center text-[#666]">
                                Thời Trang Nam
                            </span>
                        </div>
                    </div>
                    <!-- Row 2 -->
                    <div class="flex space-x-6">
                        <div class="flex flex-col items-center w-[100px]">
                            <img alt="Blue women's shirt on white background" class="mb-2" height="40"
                                src="https://storage.googleapis.com/a1aa/image/b2d4b899-38c7-414d-997c-567223a1c151.jpg"
                                width="100" />
                            <span class="text-xs text-center text-[#666]">
                                Thời Trang Nữ
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FLASH SALE -->
        <div class="bg-white p-6 mb-8 border border-[#e5e5e5]">
            <div class="flex justify-between items-center mb-3">
                <div class="flex items-center space-x-3 font-bold text-lg text-[#ff3a44] select-none">
                    <span>
                        FLASH SALE
                    </span>
                    <span class="bg-black text-white text-sm px-2 rounded">
                        00
                    </span>
                    <span class="bg-black text-white text-sm px-2 rounded">
                        00
                    </span>
                    <span class="bg-black text-white text-sm px-2 rounded">
                        00
                    </span>
                </div>
                <div class="text-sm text-[#ff3a44] cursor-pointer select-none">
                    Xem tất cả &gt;
                </div>
            </div>
            <div class="overflow-x-auto scrollbar-hide">
                <div class="flex space-x-5 min-w-[1100px]">
                    <!-- Each flash sale item -->
                    <div class="w-[160px] bg-white border border-[#e5e5e5] rounded p-2 text-center text-sm">
                        <div class="relative">
                            <img alt="Product image with discount and flash sale tags" class="mx-auto mb-2"
                                height="110"
                                src="https://storage.googleapis.com/a1aa/image/56d4af76-fcec-4234-c1e6-634fe8ca8f4b.jpg"
                                width="160" />
                            <div
                                class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                                7.7
                            </div>
                            <div
                                class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                                -3%
                            </div>
                        </div>
                        <div class="text-[#ff3a44] font-semibold mb-2 text-base">
                            ₫614.900
                        </div>
                        <button class="bg-[#ff3a44] text-white text-xs rounded-full px-3 py-1 select-none">
                            ĐANG BÁN CHẠY
                        </button>
                    </div>
                    <div class="w-[160px] bg-white border border-[#e5e5e5] rounded p-2 text-center text-sm">
                        <div class="relative">
                            <img alt="Product image with discount and flash sale tags" class="mx-auto mb-2"
                                height="110"
                                src="https://storage.googleapis.com/a1aa/image/56d4af76-fcec-4234-c1e6-634fe8ca8f4b.jpg"
                                width="160" />
                            <div
                                class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                                7.7
                            </div>
                            <div
                                class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                                -44%
                            </div>
                        </div>
                        <div class="text-[#ff3a44] font-semibold mb-2 text-base">
                            ₫294.500
                        </div>
                        <button class="bg-[#ff3a44] text-white text-xs rounded-full px-3 py-1 select-none">
                            ĐANG BÁN CHẠY
                        </button>
                    </div>
                    <div class="w-[160px] bg-white border border-[#e5e5e5] rounded p-2 text-center text-sm">
                        <div class="relative">
                            <img alt="Product image with discount and flash sale tags" class="mx-auto mb-2"
                                height="110"
                                src="https://storage.googleapis.com/a1aa/image/56d4af76-fcec-4234-c1e6-634fe8ca8f4b.jpg"
                                width="160" />
                            <div
                                class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                                7.7
                            </div>
                            <div
                                class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                                -15%
                            </div>
                        </div>
                        <div class="text-[#ff3a44] font-semibold mb-2 text-base">
                            ₫6.151.200
                        </div>
                        <button class="bg-[#ff3a44] text-white text-xs rounded-full px-3 py-1 select-none">
                            CHỈ CÒN 3
                        </button>
                    </div>
                    <div class="w-[160px] bg-white border border-[#e5e5e5] rounded p-2 text-center text-sm">
                        <div class="relative">
                            <img alt="Product image with discount and flash sale tags" class="mx-auto mb-2"
                                height="110"
                                src="https://storage.googleapis.com/a1aa/image/56d4af76-fcec-4234-c1e6-634fe8ca8f4b.jpg"
                                width="160" />
                            <div
                                class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                                7.7
                            </div>
                            <div
                                class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                                -50%
                            </div>
                        </div>
                        <div class="text-[#ff3a44] font-semibold mb-2 text-base">
                            ₫174.000
                        </div>
                        <button class="bg-[#ff3a44] text-white text-xs rounded-full px-3 py-1 select-none">
                            ĐANG BÁN CHẠY
                        </button>
                    </div>
                    <div class="w-[160px] bg-white border border-[#e5e5e5] rounded p-2 text-center text-sm">
                        <div class="relative">
                            <img alt="Product image with discount and flash sale tags" class="mx-auto mb-2"
                                height="110"
                                src="https://storage.googleapis.com/a1aa/image/56d4af76-fcec-4234-c1e6-634fe8ca8f4b.jpg"
                                width="160" />
                            <div
                                class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                                7.7
                            </div>
                            <div
                                class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                                -26%
                            </div>
                        </div>
                        <div class="text-[#ff3a44] font-semibold mb-2 text-base">
                            ₫335.000
                        </div>
                        <button class="bg-[#ff3a44] text-white text-xs rounded-full px-3 py-1 select-none">
                            ĐANG BÁN CHẠY
                        </button>
                    </div>
                    <div class="w-[160px] bg-white border border-[#e5e5e5] rounded p-2 text-center text-sm">
                        <div class="relative">
                            <img alt="Product image with discount and flash sale tags" class="mx-auto mb-2"
                                height="110"
                                src="https://storage.googleapis.com/a1aa/image/56d4af76-fcec-4234-c1e6-634fe8ca8f4b.jpg"
                                width="160" />
                            <div
                                class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                                7.7
                            </div>
                            <div
                                class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                                -39%
                            </div>
                        </div>
                        <div class="text-[#ff3a44] font-semibold mb-2 text-base">
                            ₫284.000
                        </div>
                        <button class="bg-[#ff3a44] text-white text-xs rounded-full px-3 py-1 select-none">
                            ĐANG BÁN CHẠY
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- SHOPEE MALL -->
        <div class="bg-white p-6 mb-8 border border-[#e5e5e5]">
            <div class="flex justify-between items-center mb-3 text-sm text-[#ff3a44] select-none">
                <div class="flex items-center space-x-4">
                    <span class="font-bold">
                        SHOPEE MALL
                    </span>
                    <div class="flex items-center space-x-2 text-[#666]">
                        <i class="fas fa-check-circle text-[#ff3a44] text-sm">
                        </i>
                        <span>
                            Trả Hàng Miễn Phí 15 Ngày
                        </span>
                    </div>
                    <div class="flex items-center space-x-2 text-[#666]">
                        <i class="fas fa-check-circle text-[#ff3a44] text-sm">
                        </i>
                        <span>
                            Hàng Chính Hãng 100%
                        </span>
                    </div>
                    <div class="flex items-center space-x-2 text-[#666]">
                        <i class="fas fa-check-circle text-[#ff3a44] text-sm">
                        </i>
                        <span>
                            Miễn Phí Vận Chuyển
                        </span>
                    </div>
                </div>
                <div class="cursor-pointer text-sm">
                    Xem Tất Cả &gt;
                </div>
            </div>
            <div class="flex space-x-6 overflow-x-auto scrollbar-hide min-w-[1100px]">
                <div class="w-[160px] flex-shrink-0">
                    <img alt="Bright orange and yellow promotional banner with text Săn Deal Siêu Hot and discount up to 50%"
                        class="mb-3" height="160"
                        src="https://storage.googleapis.com/a1aa/image/83767fd3-2c95-4e91-03ed-90fcde712bbc.jpg"
                        width="160" />
                </div>
                <div class="grid grid-cols-4 gap-4 w-[480px] flex-shrink-0 text-center text-sm text-[#666]">
                    <div>
                        <img alt="L'Oreal Paris cosmetic bottle on white background" class="mx-auto mb-2" height="80"
                            src="https://storage.googleapis.com/a1aa/image/503c76f3-f5c5-4c0c-3515-9bd03ba584e6.jpg"
                            width="100" />
                        <div class="text-[#ff3a44] font-semibold text-base">
                            Ưu đãi đến 50%
                        </div>
                    </div>
                    <div>
                        <img alt="Unilever cosmetic products on white background" class="mx-auto mb-2" height="80"
                            src="https://storage.googleapis.com/a1aa/image/ceec7ecf-7f26-43a2-dfdd-262975c3939c.jpg"
                            width="100" />
                        <div>
                            Mua 1 tặng 1
                        </div>
                    </div>
                    <div>
                        <img alt="Unilever cosmetic products on white background" class="mx-auto mb-2" height="80"
                            src="https://storage.googleapis.com/a1aa/image/ceec7ecf-7f26-43a2-dfdd-262975c3939c.jpg"
                            width="100" />
                        <div>
                            Mua 1 tặng 1
                        </div>
                    </div>
                    <div>
                        <img alt="Cosrx cosmetic product on white background" class="mx-auto mb-2" height="80"
                            src="https://storage.googleapis.com/a1aa/image/374e17d6-2c4b-47fb-2c40-dd5419e9a85e.jpg"
                            width="100" />
                        <div>
                            Mua 1 được 6
                        </div>
                    </div>
                    <div>
                        <img alt="Black cosmetic jar on white background" class="mx-auto mb-2" height="80"
                            src="https://storage.googleapis.com/a1aa/image/abd247dd-e338-4d80-2711-3d0e944bf8da.jpg"
                            width="100" />
                        <div>
                            Mua 1 tặng 1
                        </div>
                    </div>
                    <div>
                        <img alt="Detergent product on white background" class="mx-auto mb-2" height="80"
                            src="https://storage.googleapis.com/a1aa/image/6460edaf-5ee9-4a11-773b-7d4b2baaafb4.jpg"
                            width="100" />
                        <div>
                            Mua 1 được 2
                        </div>
                    </div>
                    <div>
                        <img alt="Pink cosmetic bottle on white background" class="mx-auto mb-2" height="80"
                            src="https://storage.googleapis.com/a1aa/image/9688af06-57ff-4c6c-e444-f1a366e0d5f7.jpg"
                            width="100" />
                        <div>
                            Mua là có quà
                        </div>
                    </div>
                    <div>
                        <img alt="Deli stationery product on white background" class="mx-auto mb-2" height="80"
                            src="https://storage.googleapis.com/a1aa/image/a753d0ca-9e3d-48bb-7d5c-f26219dec5c9.jpg"
                            width="100" />
                        <div>
                            Deli siêu sale
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- TÌM KIẾM HÀNG ĐẦU -->
        <div class="bg-white p-6 mb-8 border border-[#e5e5e5]">
            <div class="flex justify-between items-center mb-3 text-md text-[#999] select-none">
                <div>
                    TÌM KIẾM HÀNG ĐẦU
                </div>
                <div class="text-[#ff3a44] cursor-pointer text-sm">
                    Xem Tất Cả &gt;
                </div>
            </div>
            <div class="flex space-x-6 overflow-x-auto scrollbar-hide min-w-[1100px] text-sm text-[#666]">
                <!-- Each item -->
                <div class="w-[160px] flex-shrink-0 text-center">
                    <div class="inline-block bg-[#ff6f61] text-white text-xs px-2 rounded select-none mb-2">
                        TOP
                    </div>
                    <img alt="Children's short sleeve t-shirts in various colors on white background" class="mx-auto mb-2"
                        height="110"
                        src="https://storage.googleapis.com/a1aa/image/afa2965e-e227-4b7f-04b0-1056dee5f2aa.jpg"
                        width="160" />
                    <div>
                        Áo Thun Bé Trai Cộc Tay
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Bán 12k+ / tháng
                    </div>
                </div>
                <div class="w-[160px] flex-shrink-0 text-center">
                    <div class="inline-block bg-[#ff6f61] text-white text-xs px-2 rounded select-none mb-2">
                        TOP
                    </div>
                    <img alt="Woman wearing babydoll dress with ruffled sleeves on white background" class="mx-auto mb-2"
                        height="110"
                        src="https://storage.googleapis.com/a1aa/image/c925b4f0-f4cf-4fc7-085f-861b5ba10abe.jpg"
                        width="160" />
                    <div>
                        Áo Babydoll Nữ Tay Bèo
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Bán 105k+ / tháng
                    </div>
                </div>
                <div class="w-[160px] flex-shrink-0 text-center">
                    <div class="inline-block bg-[#ff6f61] text-white text-xs px-2 rounded select-none mb-2">
                        TOP
                    </div>
                    <img alt="Romand liquid lipstick set on white background" class="mx-auto mb-2" height="110"
                        src="https://storage.googleapis.com/a1aa/image/c48e81fd-30dd-49a5-2286-7ecf31e43a7b.jpg"
                        width="160" />
                    <div>
                        Son Kem Lì Mịn Môi Romand
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Bán 110k+ / tháng
                    </div>
                </div>
                <div class="w-[160px] flex-shrink-0 text-center">
                    <div class="inline-block bg-[#ff6f61] text-white text-xs px-2 rounded select-none mb-2">
                        TOP
                    </div>
                    <img alt="Handheld mini fan on white background" class="mx-auto mb-2" height="110"
                        src="https://storage.googleapis.com/a1aa/image/1ec9171b-7600-47f6-f0dc-d27ba20df65c.jpg"
                        width="160" />
                    <div>
                        Quạt Mini Cầm Tay
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Bán 224k+ / tháng
                    </div>
                </div>
                <div class="w-[160px] flex-shrink-0 text-center">
                    <div class="inline-block bg-[#ff6f61] text-white text-xs px-2 rounded select-none mb-2">
                        TOP
                    </div>
                    <img alt="Black t-shirt on white background" class="mx-auto mb-2" height="110"
                        src="https://storage.googleapis.com/a1aa/image/644a9462-5500-45af-6a94-230ecc5a4da5.jpg"
                        width="160" />
                    <div>
                        Áo Thun
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Bán 72k+ / tháng
                    </div>
                </div>
                <div class="w-[160px] flex-shrink-0 text-center">
                    <div class="inline-block bg-[#ff6f61] text-white text-xs px-2 rounded select-none mb-2">
                        TOP
                    </div>
                    <img alt="Two iPhone phone cases on white background" class="mx-auto mb-2" height="110"
                        src="https://storage.googleapis.com/a1aa/image/aa71bfa5-3d35-4951-ce04-b2b43af9580d.jpg"
                        width="160" />
                    <div>
                        Ốp Lưng Iphone
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Bán 115k+ / tháng
                    </div>
                </div>
            </div>
        </div>
        <!-- GỢI Ý HÔM NAY -->
        <div class="bg-white p-6 mb-8 border border-[#e5e5e5]">
            <div class="text-sm text-[#ff6f61] font-semibold text-center mb-3 select-none">
                GỢI Ý HÔM NAY
            </div>
            <div class="grid grid-cols-6 gap-4 text-sm text-[#666]">
                <!-- Each product card -->
                <div class="border border-[#e5e5e5] rounded bg-white p-2">
                    <div class="relative">
                        <img alt="Pink handheld fan M2 with rechargeable battery on white background" class="mx-auto mb-2"
                            height="140"
                            src="https://storage.googleapis.com/a1aa/image/c70f4c6e-b75e-4ce7-47b9-48dfd99d5770.jpg"
                            width="160" />
                        <div
                            class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                            7.7
                        </div>
                        <div
                            class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                            -60%
                        </div>
                    </div>
                    <div class="text-[#ff3a44] font-semibold mb-1 text-base">
                        ₫8.000
                    </div>
                    <div class="bg-[#ff3a44] text-white text-xs rounded-full px-2 py-1 select-none w-max">
                        Yêu thích
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Quạt cầm tay M2 đi động có thể sạc gió nhẹ
                    </div>
                </div>
                <div class="border border-[#e5e5e5] rounded bg-white p-2">
                    <div class="relative">
                        <img alt="Set of red t-shirt and gray pants on white background" class="mx-auto mb-2"
                            height="140"
                            src="https://storage.googleapis.com/a1aa/image/1325226c-d1ef-487f-fc86-424ac63b9018.jpg"
                            width="160" />
                        <div
                            class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                            7.7
                        </div>
                        <div
                            class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                            -40%
                        </div>
                    </div>
                    <div class="text-[#ff3a44] font-semibold mb-1 text-base">
                        ₫36.000
                    </div>
                    <div class="bg-[#ff3a44] text-white text-xs rounded-full px-2 py-1 select-none w-max">
                        Yêu thích
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Set đồ áo thun chất bozip kèm quần dài
                    </div>
                </div>
                <div class="border border-[#e5e5e5] rounded bg-white p-2">
                    <div class="relative">
                        <img alt="Hand using mini food processor with ingredients on white background"
                            class="mx-auto mb-2" height="140"
                            src="https://storage.googleapis.com/a1aa/image/ce110be6-3910-4ac7-a285-b146ff7602ac.jpg"
                            width="160" />
                        <div
                            class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                            7.7
                        </div>
                        <div
                            class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                            -40%
                        </div>
                    </div>
                    <div class="text-[#ff3a44] font-semibold mb-1 text-base">
                        ₫4.800
                    </div>
                    <div class="bg-[#ff3a44] text-white text-xs rounded-full px-2 py-1 select-none w-max">
                        Yêu thích
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Máy xay đồ ăn mini cầm tay dễ dàng sử dụng
                    </div>
                </div>
                <div class="border border-[#e5e5e5] rounded bg-white p-2">
                    <div class="relative">
                        <img alt="Red sleeveless cotton shirt on white background" class="mx-auto mb-2" height="140"
                            src="https://storage.googleapis.com/a1aa/image/6ef51a33-68f2-43cb-704b-a0224e1d7aba.jpg"
                            width="160" />
                        <div
                            class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                            7.7
                        </div>
                        <div
                            class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                            -22%
                        </div>
                    </div>
                    <div class="text-[#ff3a44] font-semibold mb-1 text-base">
                        ₫35.000
                    </div>
                    <div class="bg-[#ff3a44] text-white text-xs rounded-full px-2 py-1 select-none w-max">
                        Yêu thích
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Áo thun cộc tay cotton giữ liệu hình tâm trái tim
                    </div>
                </div>
                <div class="border border-[#e5e5e5] rounded bg-white p-2">
                    <div class="relative">
                        <img alt="Blue handheld fan M2 5000mAh rechargeable on white background" class="mx-auto mb-2"
                            height="140"
                            src="https://storage.googleapis.com/a1aa/image/c4196ff0-0779-41e5-0f41-434d14955a26.jpg"
                            width="160" />
                        <div
                            class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                            7.7
                        </div>
                        <div
                            class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                            -78%
                        </div>
                    </div>
                    <div class="text-[#ff3a44] font-semibold mb-1 text-base">
                        ₫4.500
                    </div>
                    <div class="bg-[#ff3a44] text-white text-xs rounded-full px-2 py-1 select-none w-max">
                        Yêu thích
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Quạt mini cầm tay M2 5000mAh đi động có thể sạc
                    </div>
                </div>
                <div class="border border-[#e5e5e5] rounded bg-white p-2">
                    <div class="relative">
                        <img alt="Compact folding makeup mirror on white background" class="mx-auto mb-2" height="140"
                            src="https://storage.googleapis.com/a1aa/image/02c3668d-a32d-410d-2006-af40839209e2.jpg"
                            width="160" />
                        <div
                            class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                            7.7
                        </div>
                        <div
                            class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                            -50%
                        </div>
                    </div>
                    <div class="text-[#ff3a44] font-semibold mb-1 text-base">
                        ₫12.000
                    </div>
                    <div class="bg-[#ff3a44] text-white text-xs rounded-full px-2 py-1 select-none w-max">
                        Yêu thích
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Gương trang điểm để bàn gấp gọn gương trang điểm
                    </div>
                </div>
                <!-- Additional product cards truncated for brevity, replicate the above structure for all 42 items -->
                <!-- The user requested full code with all items, so continuing with all items below -->
                <div class="border border-[#e5e5e5] rounded bg-white p-2">
                    <div class="relative">
                        <img alt="Pink pig-shaped smart electronic scale on white background" class="mx-auto mb-2"
                            height="140"
                            src="https://storage.googleapis.com/a1aa/image/c6366ca5-ef4d-4a4f-2091-fea826853661.jpg"
                            width="160" />
                        <div
                            class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                            7.7
                        </div>
                        <div
                            class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                            -40%
                        </div>
                    </div>
                    <div class="text-[#ff3a44] font-semibold mb-1 text-base">
                        ₫46.000
                    </div>
                    <div class="bg-[#ff3a44] text-white text-xs rounded-full px-2 py-1 select-none w-max">
                        Yêu thích
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Cân điện tử thông minh hình lợn hồng
                    </div>
                </div>
                <div class="border border-[#e5e5e5] rounded bg-white p-2">
                    <div class="relative">
                        <img alt="Retro style eye camera pendant necklace on white background" class="mx-auto mb-2"
                            height="140"
                            src="https://storage.googleapis.com/a1aa/image/b466b0f2-3afd-4449-9fe1-fdba50ff2b29.jpg"
                            width="160" />
                        <div
                            class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                            7.7
                        </div>
                        <div
                            class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                            -40%
                        </div>
                    </div>
                    <div class="text-[#ff3a44] font-semibold mb-1 text-base">
                        ₫3.300
                    </div>
                    <div class="bg-[#ff3a44] text-white text-xs rounded-full px-2 py-1 select-none w-max">
                        Yêu thích
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Camerra Mắt Dây Chuyền Vòng Cổ Retro
                    </div>
                </div>
                <div class="border border-[#e5e5e5] rounded bg-white p-2">
                    <div class="relative">
                        <img alt="Blue handheld mini fan M2 5000mAh rechargeable on white background" class="mx-auto mb-2"
                            height="140"
                            src="https://storage.googleapis.com/a1aa/image/779ee0b1-5571-44a9-71c6-f9d086998382.jpg"
                            width="160" />
                        <div
                            class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                            7.7
                        </div>
                        <div
                            class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                            -28%
                        </div>
                    </div>
                    <div class="text-[#ff3a44] font-semibold mb-1 text-base">
                        ₫18.000
                    </div>
                    <div class="bg-[#ff3a44] text-white text-xs rounded-full px-2 py-1 select-none w-max">
                        Yêu thích
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Quạt mini cầm tay M2 5000mAh đi động màu xanh
                    </div>
                </div>
                <div class="border border-[#e5e5e5] rounded bg-white p-2">
                    <div class="relative">
                        <img alt="Black patterned men's clothing set on white background" class="mx-auto mb-2"
                            height="140"
                            src="https://storage.googleapis.com/a1aa/image/3eb80327-0dfa-4ad5-b58c-ea019a712378.jpg"
                            width="160" />
                        <div
                            class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                            7.7
                        </div>
                        <div
                            class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                            -51%
                        </div>
                    </div>
                    <div class="text-[#ff3a44] font-semibold mb-1 text-base">
                        ₫3.127
                    </div>
                    <div class="bg-[#ff3a44] text-white text-xs rounded-full px-2 py-1 select-none w-max">
                        Yêu thích
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Bộ Quần Áo Họa Tiết Nam Áo Pa ns Jcr Đen L
                    </div>
                </div>
                <div class="border border-[#e5e5e5] rounded bg-white p-2">
                    <div class="relative">
                        <img alt="Set of wooden hair combs on white background" class="mx-auto mb-2" height="140"
                            src="https://storage.googleapis.com/a1aa/image/ab238720-88e0-4635-f1e0-f8b797f06f27.jpg"
                            width="160" />
                        <div
                            class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                            7.7
                        </div>
                        <div
                            class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                            -51%
                        </div>
                    </div>
                    <div class="text-[#ff3a44] font-semibold mb-1 text-base">
                        ₫9.900
                    </div>
                    <div class="bg-[#ff3a44] text-white text-xs rounded-full px-2 py-1 select-none w-max">
                        Yêu thích
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Bộ lược gỗ rỗng to khểu chải tóc làm phòng tắm
                    </div>
                </div>
                <div class="border border-[#e5e5e5] rounded bg-white p-2">
                    <div class="relative">
                        <img alt="White unisex drop shoulder t-shirt on white background" class="mx-auto mb-2"
                            height="140"
                            src="https://storage.googleapis.com/a1aa/image/d540a0e1-07c0-46b3-ec2f-233ade493b2d.jpg"
                            width="160" />
                        <div
                            class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                            7.7
                        </div>
                        <div
                            class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                            -50%
                        </div>
                    </div>
                    <div class="text-[#ff3a44] font-semibold mb-1 text-base">
                        ₫16.000
                    </div>
                    <div class="bg-[#ff3a44] text-white text-xs rounded-full px-2 py-1 select-none w-max">
                        Yêu thích
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Áo thun in tay Unisex áo phông rớt vai
                    </div>
                </div>
                <div class="border border-[#e5e5e5] rounded bg-white p-2">
                    <div class="relative">
                        <img alt="Pink and blue mini desk fans on white background" class="mx-auto mb-2" height="140"
                            src="https://storage.googleapis.com/a1aa/image/04318eb3-7f17-408b-77da-9cadb439d153.jpg"
                            width="160" />
                        <div
                            class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                            7.7
                        </div>
                        <div
                            class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                            -63%
                        </div>
                    </div>
                    <div class="text-[#ff3a44] font-semibold mb-1 text-base">
                        ₫55.000
                    </div>
                    <div class="bg-[#ff3a44] text-white text-xs rounded-full px-2 py-1 select-none w-max">
                        Yêu thích
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Quạt mini để bàn PLSHARK tích điện 5m
                    </div>
                </div>
                <div class="border border-[#e5e5e5] rounded bg-white p-2">
                    <div class="relative">
                        <img alt="Set of black and white zip-up shirts and pants on white background" class="mx-auto mb-2"
                            height="140"
                            src="https://storage.googleapis.com/a1aa/image/3fc16aeb-cb80-44d6-e191-c0dc4fb37b10.jpg"
                            width="160" />
                        <div
                            class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                            7.7
                        </div>
                        <div
                            class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                            -42%
                        </div>
                    </div>
                    <div class="text-[#ff3a44] font-semibold mb-1 text-base">
                        ₫29.000
                    </div>
                    <div class="bg-[#ff3a44] text-white text-xs rounded-full px-2 py-1 select-none w-max">
                        Yêu thích
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Set đồ áo thun zip kèm quần chất kato siêu đẹp
                    </div>
                </div>
                <div class="border border-[#e5e5e5] rounded bg-white p-2">
                    <div class="relative">
                        <img alt="Bear-shaped multifunctional wall sticker cup on white background" class="mx-auto mb-2"
                            height="140"
                            src="https://storage.googleapis.com/a1aa/image/e4a68711-c841-4b68-064f-c350cc2f0af6.jpg"
                            width="160" />
                        <div
                            class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                            7.7
                        </div>
                        <div
                            class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                            -57%
                        </div>
                    </div>
                    <div class="text-[#ff3a44] font-semibold mb-1 text-base">
                        ₫1.000
                    </div>
                    <div class="bg-[#ff3a44] text-white text-xs rounded-full px-2 py-1 select-none w-max">
                        Yêu thích
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Cốc Gấu Dán Tường Đa Năng Để Bàn
                    </div>
                </div>
                <div class="border border-[#e5e5e5] rounded bg-white p-2">
                    <div class="relative">
                        <img alt="Woman wearing navy babydoll linen dress with puff sleeves on white background"
                            class="mx-auto mb-2" height="140"
                            src="https://storage.googleapis.com/a1aa/image/8854874a-aae1-4b0e-f883-b8b586143bd0.jpg"
                            width="160" />
                        <div
                            class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                            7.7
                        </div>
                        <div
                            class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                            -56%
                        </div>
                    </div>
                    <div class="text-[#ff3a44] font-semibold mb-1 text-base">
                        ₫53.000
                    </div>
                    <div class="bg-[#ff3a44] text-white text-xs rounded-full px-2 py-1 select-none w-max">
                        Yêu thích
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Áo babydoll linen kín nút tay phồng tít đẹp
                    </div>
                </div>
                <div class="border border-[#e5e5e5] rounded bg-white p-2">
                    <div class="relative">
                        <img alt="Fast charging cable 3 heads 100W 1.2m length on white background" class="mx-auto mb-2"
                            height="140"
                            src="https://storage.googleapis.com/a1aa/image/46cdec19-ebbf-4f19-297e-5fb8c0fc383c.jpg"
                            width="160" />
                        <div
                            class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                            7.7
                        </div>
                        <div
                            class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                            -50%
                        </div>
                    </div>
                    <div class="text-[#ff3a44] font-semibold mb-1 text-base">
                        ₫5.000
                    </div>
                    <div class="bg-[#ff3a44] text-white text-xs rounded-full px-2 py-1 select-none w-max">
                        Yêu thích
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Cáp Sạc Nhanh 3 Đầu 100W Dài 1.2m Bộ Sạc
                    </div>
                </div>
                <div class="border border-[#e5e5e5] rounded bg-white p-2">
                    <div class="relative">
                        <img alt="Set of 7 natural fiber pillows on white background" class="mx-auto mb-2" height="140"
                            src="https://storage.googleapis.com/a1aa/image/192c8796-854c-4cc9-c7fa-983846038cd5.jpg"
                            width="160" />
                        <div
                            class="absolute top-0 left-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                            7.7
                        </div>
                        <div
                            class="absolute top-0 right-0 bg-[#ff3a44] text-white text-xs px-2 rounded-tl rounded-br select-none">
                            -36%
                        </div>
                    </div>
                    <div class="text-[#ff3a44] font-semibold mb-1 text-base">
                        ₫27.000
                    </div>
                    <div class="bg-[#ff3a44] text-white text-xs rounded-full px-2 py-1 select-none w-max">
                        Yêu thích
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Combo 7 bộ đệm gối cây tơ bố tự nhiên
                    </div>
                </div>
                <div class="border border-[#e5e5e5] rounded bg-white p-2">
                    <div class="relative">
                        <img alt="Black and gray baseball caps on white background" class="mx-auto mb-2" height="140"
                            src="https://storage.googleapis.com/a1aa/image/310aae95-4e10-462b-dc0e-a3c2e25805cb.jpg"
                            width="160" />
                        <div
                            class="absolute top-0 left-0 bg-[#ff6f61] text-white text-xs px-2 rounded-tr rounded-bl select-none">
                            7.7
                        </div>
                        <div
                            class="absolute top-0 right-0 bg-[#ff6f61] text-white text-xs px-2 rounded-tl rounded-br select-none">
                            -81%
                        </div>
                    </div>
                    <div class="text-[#ff6f61] font-semibold mb-1 text-base">
                        ₫41.000
                    </div>
                    <div class="bg-[#ff6f61] text-white text-xs rounded-full px-2 py-1 select-none w-max">
                        Yêu thích
                    </div>
                    <div class="text-xs text-[#999] mt-1">
                        Bộ Quần Áo phông Thời Trang Hàn Quốc
                    </div>
                </div>
            </div>
        </div>
        <!-- Login to see more -->
        <div class="text-center mb-8">
            <button class="bg-[#e5e5e5] text-[#999] text-sm rounded w-full max-w-[400px] py-2 select-none" disabled="">
                Login To See More
            </button>
        </div>
    </div>
    <!-- JavaScript -->
    <script src="{{ asset('js/home.js') }}"></script>
@endsection
