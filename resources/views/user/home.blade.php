@extends('layouts.app')

@section('content')
    <!-- Main Banner -->
    <section class="container mx-auto py-8 flex relative">
        <!-- Sidebar Menu with Dropdown -->
        <div class="w-1/4 pr-10">
            <ul class="space-y-2">
                <li>
                    <div id="dropdownToggle">
                        <button
                            class="text-gray-700 hover:text-black flex items-center justify-between w-full mb-2 text-[18px] font-semibold">
                            Thời trang phụ nữ
                            <!-- Arrow icon -->
                            <svg class="w-[21px] h-[21px] ml-1 arrow-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        <div id="dropdownMenu" class="dropdown-content text-[18px] border-1 border-gray-300 px-4 pl-2 font-semibold absolute top-[32px] left-[390px] z-10 w-[1134px] h-[400px] bg-white">
                            <div class="flex flex-col gap-[10px]">
                                <div class="w-1/2 flex gap-[50px]">
                                    <a href="#" class="text-gray-700 hover:text-black">Điện thoại - di động</a>
                                    <a href="#" class="text-gray-700 hover:text-black">Máy tính</a>
                                    <a href="#" class="text-gray-700 hover:text-black">Phụ kiện</a>
                                </div>      
                                <div class="w-1/2 flex gap-[50px]">
                                    <a href="#" class="text-gray-700 hover:text-black">Máy ảnh</a>
                                    <a href="#" class="text-gray-700 hover:text-black">Tổng hợp</a>
                                    <a href="#" class="text-gray-700 hover:text-black">Gaming</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button id="dropdownToggleSecond"
                        class="text-gray-700 hover:text-black flex items-center justify-between w-full mb-2 text-[18px] font-semibold">
                        Thời trang nam
                        <svg class="w-[21px] h-[21px] ml-1 arrow-icon-second" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <ul id="dropdownMenuSecond" class="dropdown-content pl-4 space-y-2 text-[18px] font-semibold">
                        <li><a href="#" class="text-gray-700 hover:text-black">Apple</a></li>
                        <li><a href="#" class="text-gray-700 hover:text-black">Samsung</a></li>
                        <li><a href="#" class="text-gray-700 hover:text-black">Sony</a></li>
                        <li><a href="#" class="text-gray-700 hover:text-black">LG</a></li>
                        <li><a href="#" class="text-gray-700 hover:text-black">Dell</a></li>
                        <li><a href="#" class="text-gray-700 hover:text-black">Asus</a></li>
                    </ul>
                </li>
                <li><a href="" class="text-gray-700 hover:text-black text-[18px] font-semibold">Đồ điện tử</a></li>
                <li><a href="" class="text-gray-700 hover:text-black text-[18px] font-semibold">phong cách sống</a></li>
                <li><a href="" class="text-gray-700 hover:text-black text-[18px] font-semibold">Thuốc</a></li>
                <li><a href="" class="text-gray-700 hover:text-black text-[18px] font-semibold">Thể thao</a></li>
                <li><a href="" class="text-gray-700 hover:text-black text-[18px] font-semibold">Đồ chơi trẻ em</a></li>
                <li><a href="" class="text-gray-700 hover:text-black text-[18px] font-semibold">Thực phẩm thú cưng</a></li>
                <li><a href="" class="text-gray-700 hover:text-black text-[18px] font-semibold">Sức khỏe và sắc đẹp</a></li>
            </ul>
        </div>
        <!-- Banner -->
        <div class="relative w-3/4">
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
                            <h1 class="text-[48px] font-bold text-white w-[295px]">Giảm Giá Lên Đến 10%</h1>
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
                            <h1 class="text-[48px] font-bold text-white w-[295px]">Giảm Giá Lên Đến 10%</h1>
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
                            <h1 class="text-[48px] font-bold text-white w-[295px]">Giảm Giá Lên Đến 10%</h1>
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
                            <h1 class="text-[48px] font-bold text-white w-[295px]">Giảm Giá Lên Đến 10%</h1>
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
                            <h1 class="text-[48px] font-bold text-white w-[295px]">Giảm Giá Lên Đến 10%</h1>
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

    <!-- Khuyến mãi with Countdown Timer -->
    <section class="container mx-auto px-4 py-8">
        <div class="flex gap-3 items-center mb-3">
            <div style="background-color: #BDBDBD;" class="rounded h-[45px] w-[20px]"></div>
            <span style="color: #BDBDBD;" class="font-bold">Hôm nay</span>
        </div>
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center justify-center">
                <h2 class="text-[36px] font-bold mr-[87px]">Khuyến mãi</h2>
                <div class="flex space-x-4 text-lg">
                    <div class="">
                        <p class="text-timer px-2 py-1">Ngày</p>
                        <span id="days" class="text-timer-number px-2 py-1 rounded">00</span>
                    </div>
                    <div class="">
                        <p class="text-timer px-2 py-1">Giờ</p>
                        <span id="hours" class="text-timer-number px-2 py-1 rounded">00</span>
                    </div>
                    <div class="">
                        <p class="text-timer px-2 py-1">Phút</p>
                        <span id="minutes" class="text-timer-number px-2 py-1 rounded">00</span>
                    </div>
                    <div class="">
                        <p class="text-timer px-2 py-1">Giây</p>
                        <span id="seconds" class="text-timer-number px-2 py-1 rounded">00</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button style="background-color: #F5F5F5;"
                    class="prev-slide p-2 w-[46px] h-[46px] rounded-full flex items-center justify-center"
                    data-index="1"><i class="fa-solid fa-arrow-left text-[20px]"></i></button>
                <button style="background-color: #F5F5F5;"
                    class="next-slide p-2 w-[46px] h-[46px] rounded-full flex items-center justify-center"
                    data-index="1"><i class="fa-solid fa-arrow-right text-[20px]"></i></button>
            </div>
        </div>

        <div class="container-fluid p-0 h-[400px] overflow-hidden">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <!-- Sản phẩm 1 -->
                    <div class="swiper-slide slide-promotion w-1/4">
                        <!-- Nội dung sản phẩm 1 -->
                        <div class="card_product flex flex-col gap-[8px] object-cover">
                            <div class="background-card bg-[#F5F5F5] h-[210px] relative">
                                <div class="absolute top-0 right-0 w-full">
                                    <div class="flex justify-between p-3">
                                        <div
                                            class="bg-[#BDBDBD] rounded-[5px] w-[55px] h-[26px] flex items-center justify-center">
                                            <span class="text-white px-2 py-1">-40%</span>
                                        </div>
                                        <div class="flex flex-col items-center gap-[8px]">
                                            <button
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                                </svg>
                                            </button>
                                            <a href="#"
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-center items-center h-full">
                                    <img src="{{ asset('images/gamepad.png') }}" class="w-[172px] h-[152px] object-cover"
                                        alt="HAVIT HV-G92 Gamepad">
                                </div>
                                <div
                                    class="card-button-container absolute bottom-0 flex justify-center items-center w-full h-[41px] bg-[#000]">
                                    <button class="text-white">Thêm vào giỏ hàng</button>
                                </div>
                            </div>
                            <h3 class="text-[20px] mt-2">HAVIT HV-G92 Gamepad</h3>
                            <div class="flex items-center gap-[12px]">
                                <p class="text-[#7F7F7F]">$60</p>
                                <p class="text-[#BDBDBD] line-through">$100</p>
                            </div>
                            <div class="flex text-[#FF9F1C] h-[20px] gap-[8px]">
                                <div>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="text-[#7F7F7F] font-bold">(86)</div>
                            </div>
                        </div>
                    </div>
                    <!-- Sản phẩm 1 -->
                    <div class="swiper-slide slide-promotion w-1/4">
                        <!-- Nội dung sản phẩm 1 -->
                        <div class="card_product flex flex-col gap-[8px] object-cover">
                            <div class="background-card bg-[#F5F5F5] h-[210px] relative">
                                <div class="absolute top-0 right-0 w-full">
                                    <div class="flex justify-between p-3">
                                        <div
                                            class="bg-[#BDBDBD] rounded-[5px] w-[55px] h-[26px] flex items-center justify-center">
                                            <span class="text-white px-2 py-1">-40%</span>
                                        </div>
                                        <div class="flex flex-col items-center gap-[8px]">
                                            <button
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                                </svg>
                                            </button>
                                            <a href="#"
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-center items-center h-full">
                                    <img src="{{ asset('images/gamepad.png') }}" class="w-[172px] h-[152px] object-cover"
                                        alt="HAVIT HV-G92 Gamepad">
                                </div>
                                <div
                                    class="card-button-container absolute bottom-0 flex justify-center items-center w-full h-[41px] bg-[#000]">
                                    <button class="text-white">Thêm vào giỏ hàng</button>
                                </div>
                            </div>
                            <h3 class="text-[20px] mt-2">HAVIT HV-G92 Gamepad</h3>
                            <div class="flex items-center gap-[12px]">
                                <p class="text-[#7F7F7F]">$60</p>
                                <p class="text-[#BDBDBD] line-through">$100</p>
                            </div>
                            <div class="flex text-[#FF9F1C] h-[20px] gap-[8px]">
                                <div>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="text-[#7F7F7F] font-bold">(86)</div>
                            </div>
                        </div>
                    </div>
                    <!-- Sản phẩm 1 -->
                    <div class="swiper-slide slide-promotion w-1/4">
                        <!-- Nội dung sản phẩm 1 -->
                        <div class="card_product flex flex-col gap-[8px] object-cover">
                            <div class="background-card bg-[#F5F5F5] h-[210px] relative">
                                <div class="absolute top-0 right-0 w-full">
                                    <div class="flex justify-between p-3">
                                        <div
                                            class="bg-[#BDBDBD] rounded-[5px] w-[55px] h-[26px] flex items-center justify-center">
                                            <span class="text-white px-2 py-1">-40%</span>
                                        </div>
                                        <div class="flex flex-col items-center gap-[8px]">
                                            <button
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                                </svg>
                                            </button>
                                            <a href="#"
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-center items-center h-full">
                                    <img src="{{ asset('images/gamepad.png') }}" class="w-[172px] h-[152px] object-cover"
                                        alt="HAVIT HV-G92 Gamepad">
                                </div>
                                <div
                                    class="card-button-container absolute bottom-0 flex justify-center items-center w-full h-[41px] bg-[#000]">
                                    <button class="text-white">Thêm vào giỏ hàng</button>
                                </div>
                            </div>
                            <h3 class="text-[20px] mt-2">HAVIT HV-G92 Gamepad</h3>
                            <div class="flex items-center gap-[12px]">
                                <p class="text-[#7F7F7F]">$60</p>
                                <p class="text-[#BDBDBD] line-through">$100</p>
                            </div>
                            <div class="flex text-[#FF9F1C] h-[20px] gap-[8px]">
                                <div>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="text-[#7F7F7F] font-bold">(86)</div>
                            </div>
                        </div>
                    </div>
                    <!-- Sản phẩm 1 -->
                    <div class="swiper-slide slide-promotion w-1/4">
                        <!-- Nội dung sản phẩm 1 -->
                        <div class="card_product flex flex-col gap-[8px] object-cover">
                            <div class="background-card bg-[#F5F5F5] h-[210px] relative">
                                <div class="absolute top-0 right-0 w-full">
                                    <div class="flex justify-between p-3">
                                        <div
                                            class="bg-[#BDBDBD] rounded-[5px] w-[55px] h-[26px] flex items-center justify-center">
                                            <span class="text-white px-2 py-1">-40%</span>
                                        </div>
                                        <div class="flex flex-col items-center gap-[8px]">
                                            <button
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                                </svg>
                                            </button>
                                            <a href="#"
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-center items-center h-full">
                                    <img src="{{ asset('images/gamepad.png') }}" class="w-[172px] h-[152px] object-cover"
                                        alt="HAVIT HV-G92 Gamepad">
                                </div>
                                <div
                                    class="card-button-container absolute bottom-0 flex justify-center items-center w-full h-[41px] bg-[#000]">
                                    <button class="text-white">Thêm vào giỏ hàng</button>
                                </div>
                            </div>
                            <h3 class="text-[20px] mt-2">HAVIT HV-G92 Gamepad</h3>
                            <div class="flex items-center gap-[12px]">
                                <p class="text-[#7F7F7F]">$60</p>
                                <p class="text-[#BDBDBD] line-through">$100</p>
                            </div>
                            <div class="flex text-[#FF9F1C] h-[20px] gap-[8px]">
                                <div>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="text-[#7F7F7F] font-bold">(86)</div>
                            </div>
                        </div>
                    </div>
                    <!-- Sản phẩm 1 -->
                    <div class="swiper-slide slide-promotion w-1/4">
                        <!-- Nội dung sản phẩm 1 -->
                        <div class="card_product flex flex-col gap-[8px] object-cover">
                            <div class="background-card bg-[#F5F5F5] h-[210px] relative">
                                <div class="absolute top-0 right-0 w-full">
                                    <div class="flex justify-between p-3">
                                        <div
                                            class="bg-[#BDBDBD] rounded-[5px] w-[55px] h-[26px] flex items-center justify-center">
                                            <span class="text-white px-2 py-1">-40%</span>
                                        </div>
                                        <div class="flex flex-col items-center gap-[8px]">
                                            <button
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                                </svg>
                                            </button>
                                            <a href="#"
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-center items-center h-full">
                                    <img src="{{ asset('images/gamepad.png') }}" class="w-[172px] h-[152px] object-cover"
                                        alt="HAVIT HV-G92 Gamepad">
                                </div>
                                <div
                                    class="card-button-container absolute bottom-0 flex justify-center items-center w-full h-[41px] bg-[#000]">
                                    <button class="text-white">Thêm vào giỏ hàng</button>
                                </div>
                            </div>
                            <h3 class="text-[20px] mt-2">HAVIT HV-G92 Gamepad</h3>
                            <div class="flex items-center gap-[12px]">
                                <p class="text-[#7F7F7F]">$60</p>
                                <p class="text-[#BDBDBD] line-through">$100</p>
                            </div>
                            <div class="flex text-[#FF9F1C] h-[20px] gap-[8px]">
                                <div>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="text-[#7F7F7F] font-bold">(86)</div>
                            </div>
                        </div>
                    </div>
                    <!-- Sản phẩm 1 -->
                    <div class="swiper-slide slide-promotion w-1/4">
                        <!-- Nội dung sản phẩm 1 -->
                        <div class="card_product flex flex-col gap-[8px] object-cover">
                            <div class="background-card bg-[#F5F5F5] h-[210px] relative">
                                <div class="absolute top-0 right-0 w-full">
                                    <div class="flex justify-between p-3">
                                        <div
                                            class="bg-[#BDBDBD] rounded-[5px] w-[55px] h-[26px] flex items-center justify-center">
                                            <span class="text-white px-2 py-1">-40%</span>
                                        </div>
                                        <div class="flex flex-col items-center gap-[8px]">
                                            <button
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                                </svg>
                                            </button>
                                            <a href="#"
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-center items-center h-full">
                                    <img src="{{ asset('images/gamepad.png') }}" class="w-[172px] h-[152px] object-cover"
                                        alt="HAVIT HV-G92 Gamepad">
                                </div>
                                <div
                                    class="card-button-container absolute bottom-0 flex justify-center items-center w-full h-[41px] bg-[#000]">
                                    <button class="text-white">Thêm vào giỏ hàng</button>
                                </div>
                            </div>
                            <h3 class="text-[20px] mt-2">HAVIT HV-G92 Gamepad</h3>
                            <div class="flex items-center gap-[12px]">
                                <p class="text-[#7F7F7F]">$60</p>
                                <p class="text-[#BDBDBD] line-through">$100</p>
                            </div>
                            <div class="flex text-[#FF9F1C] h-[20px] gap-[8px]">
                                <div>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="text-[#7F7F7F] font-bold">(86)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Danh mục sản phẩm -->
    <section class="container mx-auto px-4 py-8">
        <div class="flex gap-3 items-center mb-3">
            <div style="background-color: #BDBDBD;" class="rounded h-[45px] w-[20px]"></div>
            <span style="color: #BDBDBD;" class="font-bold">Danh mục</span>
        </div>
        <div class="flex flex-col gap-[60px]">
            <div class="flex justify-between items-center">
                <div class="flex items-center justify-center">
                    <h2 class="text-[36px] font-bold mr-[87px]">Danh mục sản phẩm</h2>
                </div>
                <div class="flex items-center gap-2">
                    <button id="prev-slide-category" style="background-color: #F5F5F5;"
                        class="prev-slide-category p-2 w-[46px] h-[46px] rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-arrow-left text-[20px]"></i>
                    </button>
                    <button id="next-slide-category" style="background-color: #F5F5F5;"
                        class="next-slide-category p-2 w-[46px] h-[46px] rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-arrow-right text-[20px]"></i>
                    </button>
                </div>
            </div>
            <div class="overflow-hidden">
                <div id="category-slider" class="flex flex-row flex-nowrap gap-[30px] transition-transform duration-500">
                    <!-- Danh mục 1: Phones -->
                    <div class="flex-shrink-0 w-[223px]">
                        <div
                            class="flex flex-col items-center border-2 border-[#CFCFCF] rounded-[4px] p-3 w-[223px] h-[145px]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-[56px] h-[56px]">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                            </svg>
                            <p class="text-[20px] mt-3">Phones</p>
                        </div>
                    </div>
                    <!-- Danh mục 2: Computers -->
                    <div class="flex-shrink-0 w-[223px]">
                        <div
                            class="flex flex-col items-center border-2 border-[#CFCFCF] rounded-[4px] p-3 w-[223px] h-[145px]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-[56px] h-[56px]">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                            </svg>
                            <p class="text-[20px] mt-3">Computers</p>
                        </div>
                    </div>
                    <!-- Danh mục 3: SmartWatch -->
                    <div class="flex-shrink-0 w-[223px]">
                        <div
                            class="flex flex-col items-center border-2 border-[#CFCFCF] rounded-[4px] p-3 w-[223px] h-[145px]">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                fill="#000000" height="800px" width="800px" version="1.1" id="Layer_1"
                                viewBox="0 0 512 512" xml:space="preserve" class="w-[56px] h-[56px]">
                                <g transform="translate(1 1)">
                                    <g>
                                        <g>
                                            <path
                                                d="M404.333,212.333h-12.8V129.56c0-24.747-20.48-45.227-45.227-45.227h-1.067l-5.76-10.24     c-5.973-9.387-8.533-20.48-8.533-30.72C330.947,18.627,308.76-1,281.453-1h-87.04c-27.307,0-49.493,19.627-49.493,44.373     c0,10.24-3.413,21.333-8.533,30.72l-5.76,10.24h-1.067c-24.747,0-45.227,20.48-45.227,45.227v250.88     c0,24.747,20.48,45.227,45.227,45.227h1.067l5.76,10.24c5.973,9.387,8.533,20.48,8.533,30.72     c0,24.747,22.187,44.373,49.493,44.373h87.04c27.307,0,49.493-19.627,50.347-44.373c0-10.24,3.413-21.333,8.533-30.72l5.76-10.24     h0.213c24.747,0,45.227-20.48,45.227-45.227v-82.773h12.8c11.947,0,21.333-9.387,21.333-21.333v-42.667     C425.667,221.72,416.28,212.333,404.333,212.333z M151.747,82.627c6.827-11.947,10.24-25.6,10.24-39.253     c0-15.36,14.507-27.307,32.427-27.307h87.04c17.92,0,32.427,11.947,32.427,27.307c0,13.653,3.413,27.307,10.24,39.253     l0.853,1.707H150.04L151.747,82.627z M323.267,427.373c-6.827,11.947-10.24,25.6-10.24,39.253     c0,15.36-14.507,27.307-32.427,27.307h-87.04c-17.92,0-32.427-11.947-32.427-27.307c0-13.653-3.413-27.307-10.24-39.253     l-0.853-1.707h174.08L323.267,427.373z M374.467,380.44c0,15.36-12.8,28.16-28.16,28.16H129.56c-15.36,0-28.16-12.8-28.16-28.16     V129.56c0-15.36,12.8-28.16,28.16-28.16h216.747c15.36,0,28.16,12.8,28.16,28.16v82.773v85.333V380.44z M408.6,276.333     c0,2.56-1.707,4.267-4.267,4.267h-12.8v-51.2h12.8c2.56,0,4.267,1.707,4.267,4.267V276.333z" />
                                            <path
                                                d="M348.013,313.88h-17.067c-3.413,0-5.973,2.56-7.68,5.12l-15.36,31.573L288.28,278.04     c-0.853-3.413-3.413-5.12-6.827-5.973c-3.413,0-6.827,1.707-8.533,4.267l-23.04,38.4H229.4c-2.56,0-5.12,0.853-6.827,3.413     l-23.893,35.84L178.2,278.04c-0.853-3.413-3.413-5.12-6.827-5.973c-3.413-0.853-6.827,0.853-8.533,3.413l-31.573,47.787h-12.8     c-5.12,0-8.533,3.413-8.533,8.533c0,5.12,3.413,8.533,8.533,8.533h17.067c2.56,0,5.12-1.707,6.827-3.413l23.893-35.84     l20.48,75.947c0.853,3.413,3.413,5.12,6.827,5.973c0.853,0,0.853,0,1.707,0c2.56,0,5.12-1.707,6.827-3.413l31.573-47.787H255     c2.56,0,5.973-1.707,5.973-5.12l15.36-25.6l20.48,74.24c0.853,3.413,4.267,5.973,7.68,5.973c3.413,0.853,6.827-0.853,8.533-4.267     l23.04-46.08h11.947c5.12,0,8.533-3.413,8.533-8.533S353.133,313.88,348.013,313.88z" />
                                            <path
                                                d="M178.2,152.6h119.467c4.267,0,8.533-3.413,8.533-8.533c0-5.12-3.413-8.533-8.533-8.533H178.2     c-5.12,0-8.533,3.413-8.533,8.533C169.667,149.187,173.08,152.6,178.2,152.6z" />
                                            <path
                                                d="M297.667,169.667h-25.6c-5.12,0-8.533,3.413-8.533,8.533s3.413,8.533,8.533,8.533h25.6c4.267,0,8.533-3.413,8.533-8.533     S302.787,169.667,297.667,169.667z" />
                                            <path
                                                d="M178.2,186.733h59.733c4.267,0,8.533-3.413,8.533-8.533s-3.413-8.533-8.533-8.533H178.2c-5.12,0-8.533,3.413-8.533,8.533     S173.08,186.733,178.2,186.733z" />
                                            <path
                                                d="M246.467,203.8c-5.12,0-8.533,3.413-8.533,8.533c0,5.12,3.413,8.533,8.533,8.533h51.2c4.267,0,8.533-3.413,8.533-8.533     c0-5.12-3.413-8.533-8.533-8.533H246.467z" />
                                            <path
                                                d="M178.2,220.867h34.133c4.267,0,8.533-3.413,8.533-8.533c0-5.12-3.413-8.533-8.533-8.533H178.2     c-5.12,0-8.533,3.413-8.533,8.533C169.667,217.453,173.08,220.867,178.2,220.867z" />
                                        </g>
                                    </g>
                                </g>
                            </svg>
                            <p class="text-[20px] mt-3">SmartWatch</p>
                        </div>
                    </div>
                    <!-- Danh mục 4: Camera -->
                    <div class="flex-shrink-0 w-[223px]">
                        <div
                            class="flex flex-col items-center border-2 border-[#CFCFCF] rounded-[4px] p-3 w-[223px] h-[145px]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-[56px] h-[56px]">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                            </svg>
                            <p class="text-[20px] mt-3">Camera</p>
                        </div>
                    </div>
                    <!-- Danh mục 5: HeadPhones -->
                    <div class="flex-shrink-0 w-[223px]">
                        <div
                            class="flex flex-col items-center border-2 border-[#CFCFCF] rounded-[4px] p-3 w-[223px] h-[145px]">
                            <svg viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier" class="w-[56px] h-[56px]">
                                    <path
                                        d="M21 18V12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12V18M6.75 21C6.05302 21 5.70453 21 5.41473 20.9424C4.22466 20.7056 3.29436 19.7753 3.05764 18.5853C3 18.2955 3 17.947 3 17.25V15.6C3 15.0399 3 14.7599 3.10899 14.546C3.20487 14.3578 3.35785 14.2049 3.54601 14.109C3.75992 14 4.03995 14 4.6 14H6.4C6.96005 14 7.24008 14 7.45399 14.109C7.64215 14.2049 7.79513 14.3578 7.89101 14.546C8 14.7599 8 15.0399 8 15.6V19.75C8 19.9823 8 20.0985 7.98079 20.1951C7.90188 20.5918 7.59178 20.9019 7.19509 20.9808C7.09849 21 6.98233 21 6.75 21ZM17.25 21C17.0177 21 16.9015 21 16.8049 20.9808C16.4082 20.9019 16.0981 20.5918 16.0192 20.1951C16 20.0985 16 19.9823 16 19.75V15.6C16 15.0399 16 14.7599 16.109 14.546C16.2049 14.3578 16.3578 14.2049 16.546 14.109C16.7599 14 17.0399 14 17.6 14H19.4C19.9601 14 20.2401 14 20.454 14.109C20.6422 14.2049 20.7951 14.3578 20.891 14.546C21 14.7599 21 15.0399 21 15.6V17.25C21 17.947 21 18.2955 20.9424 18.5853C20.7056 19.7753 19.7753 20.7056 18.5853 20.9424C18.2955 21 17.947 21 17.25 21Z"
                                        stroke="#000000" stroke-width="0.4800000000000001" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </g>
                            </svg>
                            <p class="text-[20px] mt-3">HeadPhones</p>
                        </div>
                    </div>
                    <!-- Danh mục 6: Gaming -->
                    <div class="flex-shrink-0 w-[223px]">
                        <div
                            class="flex flex-col items-center border-2 border-[#CFCFCF] rounded-[4px] p-3 w-[223px] h-[145px]">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier" class="w-[56px] h-[56px]">
                                    <path
                                        d="M6 12H10M8 10V14M16 13H16.01M18 11H18.01M5.2 18H18.8C19.9201 18 20.4802 18 20.908 17.782C21.2843 17.5903 21.5903 17.2843 21.782 16.908C22 16.4802 22 15.9201 22 14.8V9.2C22 8.0799 22 7.51984 21.782 7.09202C21.5903 6.71569 21.2843 6.40973 20.908 6.21799C20.4802 6 19.9201 6 18.8 6H5.2C4.0799 6 3.51984 6 3.09202 6.21799C2.71569 6.40973 2.40973 6.71569 2.21799 7.09202C2 7.51984 2 8.07989 2 9.2V14.8C2 15.9201 2 16.4802 2.21799 16.908C2.40973 17.2843 2.71569 17.5903 3.09202 17.782C3.51984 18 4.07989 18 5.2 18Z"
                                        stroke="#000000" stroke-width="0.9600000000000002" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </g>
                            </svg>
                            <p class="text-[20px] mt-3">Gaming</p>
                        </div>
                    </div>
                    <!-- Danh mục 7: Tablets -->
                    <div class="flex-shrink-0 w-[223px]">
                        <div
                            class="flex flex-col items-center border-2 border-[#CFCFCF] rounded-[4px] p-3 w-[223px] h-[145px]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-[56px] h-[56px]">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 18.75a6 6 0 0 0 0-12v12Zm10.5-6a9.75 9.75 0 0 1-9.75 9.75H11.25A9.75 9.75 0 0 1 1.5 12.75v-1.5A9.75 9.75 0 0 1 11.25 1.5h1.5A9.75 9.75 0 0 1 22.5 11.25v1.5Z" />
                            </svg>
                            <p class="text-[20px] mt-3">Tablets</p>
                        </div>
                    </div>
                    <!-- Danh mục 8: Laptops -->
                    <div class="flex-shrink-0 w-[223px]">
                        <div
                            class="flex flex-col items-center border-2 border-[#CFCFCF] rounded-[4px] p-3 w-[223px] h-[145px]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-[56px] h-[56px]">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                            </svg>
                            <p class="text-[20px] mt-3">Laptops</p>
                        </div>
                    </div>
                    <!-- Danh mục 9: Accessories -->
                    <div class="flex-shrink-0 w-[223px]">
                        <div
                            class="flex flex-col items-center border-2 border-[#CFCFCF] rounded-[4px] p-3 w-[223px] h-[145px]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-[56px] h-[56px]">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 0 0 2.25-2.25V6a2.25 2.25 0 0 0-2.25-2.25H6A2.25 2.25 0 0 0 3.75 6v2.25A2.25 2.25 0 0 0 6 10.5Zm9.75 0h2.25A2.25 2.25 0 0 0 20.25 8.25V6a2.25 2.25 0 0 0-2.25-2.25h-2.25A2.25 2.25 0 0 0 13.5 6v2.25a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <p class="text-[20px] mt-3">Accessories</p>
                        </div>
                    </div>
                    <!-- Danh mục 10: Wearables -->
                    <div class="flex-shrink-0 w-[223px]">
                        <div
                            class="flex flex-col items-center border-2 border-[#CFCFCF] rounded-[4px] p-3 w-[223px] h-[145px]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-[56px] h-[56px]">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8.25v7.5m3.75-3.75h-7.5M12 21a9 9 0 1 1 0-18 9 9 0 0 1 0 18Zm0-18v1.5m0 15V18m-1.5-15h1.5m-1.5 15h1.5" />
                            </svg>
                            <p class="text-[20px] mt-3">Wearables</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sản phẩm bán chạy -->
    <section class="container mx-auto px-4 py-8">
        <div class="flex gap-3 items-center mb-3">
            <div style="background-color: #BDBDBD;" class="rounded h-[45px] w-[20px]"></div>
            <span style="color: #BDBDBD;" class="font-bold">Tháng này</span>
        </div>
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center justify-center">
                <h2 class="text-[36px] font-bold mr-[87px]">Sản phẩm bán chạy</h2>
            </div>
            <div class="flex items-center gap-2">
                <button style="background-color: #F5F5F5;"
                    class="prev-slide-best-seller p-2 w-[46px] h-[46px] rounded-full flex items-center justify-center"
                    data-index="1"><i class="fa-solid fa-arrow-left text-[20px]"></i></button>
                <button style="background-color: #F5F5F5;"
                    class="next-slide-best-seller p-2 w-[46px] h-[46px] rounded-full flex items-center justify-center"
                    data-index="1"><i class="fa-solid fa-arrow-right text-[20px]"></i></button>
            </div>
        </div>
        <div class="container-fluid p-0 h-[400px] overflow-hidden">
            <div class="swiper-container-best-seller">
                <div class="swiper-wrapper">
                    <!-- Sản phẩm 1 -->
                    <div class="swiper-slide slide-best-seller w-1/4">
                        <!-- Nội dung sản phẩm 1 -->
                        <div class="card_product flex flex-col gap-[8px] object-cover">
                            <div class="background-card bg-[#F5F5F5] h-[210px] relative">
                                <div class="absolute top-0 right-0 w-full">
                                    <div class="flex justify-end p-3">
                                        <div class="flex flex-col items-center gap-[8px]">
                                            <button
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                                </svg>
                                            </button>
                                            <a href="#"
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-center items-center h-full">
                                    <img src="{{ asset('images/gamepad.png') }}" class="w-[172px] h-[152px] object-cover"
                                        alt="HAVIT HV-G92 Gamepad">
                                </div>
                                <div
                                    class="card-button-container absolute bottom-0 flex justify-center items-center w-full h-[41px] bg-[#000]">
                                    <button class="text-white">Thêm vào giỏ hàng</button>
                                </div>
                            </div>
                            <h3 class="text-[20px] mt-2">HAVIT HV-G92 Gamepad</h3>
                            <div class="flex items-center gap-[12px]">
                                <p class="text-[#7F7F7F]">$60</p>
                                <p class="text-[#BDBDBD] line-through">$100</p>
                            </div>
                            <div class="flex text-[#FF9F1C] h-[20px] gap-[8px]">
                                <div>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="text-[#7F7F7F] font-bold">(86)</div>
                            </div>
                        </div>
                    </div>
                    <!-- Sản phẩm 2 -->
                    <div class="swiper-slide slide-best-seller w-1/4">
                        <!-- Nội dung sản phẩm 1 -->
                        <div class="card_product flex flex-col gap-[8px] object-cover">
                            <div class="background-card bg-[#F5F5F5] h-[210px] relative">
                                <div class="absolute top-0 right-0 w-full">
                                    <div class="flex justify-end p-3">
                                        <div class="flex flex-col items-center gap-[8px]">
                                            <button
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                                </svg>
                                            </button>
                                            <a href="#"
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-center items-center h-full">
                                    <img src="{{ asset('images/gamepad.png') }}" class="w-[172px] h-[152px] object-cover"
                                        alt="HAVIT HV-G92 Gamepad">
                                </div>
                                <div
                                    class="card-button-container absolute bottom-0 flex justify-center items-center w-full h-[41px] bg-[#000]">
                                    <button class="text-white">Thêm vào giỏ hàng</button>
                                </div>
                            </div>
                            <h3 class="text-[20px] mt-2">HAVIT HV-G92 Gamepad</h3>
                            <div class="flex items-center gap-[12px]">
                                <p class="text-[#7F7F7F]">$60</p>
                                <p class="text-[#BDBDBD] line-through">$100</p>
                            </div>
                            <div class="flex text-[#FF9F1C] h-[20px] gap-[8px]">
                                <div>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="text-[#7F7F7F] font-bold">(86)</div>
                            </div>
                        </div>
                    </div>
                    <!-- Sản phẩm 3 -->
                    <div class="swiper-slide slide-best-seller w-1/4">
                        <!-- Nội dung sản phẩm 1 -->
                        <div class="card_product flex flex-col gap-[8px] object-cover">
                            <div class="background-card bg-[#F5F5F5] h-[210px] relative">
                                <div class="absolute top-0 right-0 w-full">
                                    <div class="flex justify-end p-3">
                                        <div class="flex flex-col items-center gap-[8px]">
                                            <button
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                                </svg>
                                            </button>
                                            <a href="#"
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-center items-center h-full">
                                    <img src="{{ asset('images/gamepad.png') }}" class="w-[172px] h-[152px] object-cover"
                                        alt="HAVIT HV-G92 Gamepad">
                                </div>
                                <div
                                    class="card-button-container absolute bottom-0 flex justify-center items-center w-full h-[41px] bg-[#000]">
                                    <button class="text-white">Thêm vào giỏ hàng</button>
                                </div>
                            </div>
                            <h3 class="text-[20px] mt-2">HAVIT HV-G92 Gamepad</h3>
                            <div class="flex items-center gap-[12px]">
                                <p class="text-[#7F7F7F]">$60</p>
                                <p class="text-[#BDBDBD] line-through">$100</p>
                            </div>
                            <div class="flex text-[#FF9F1C] h-[20px] gap-[8px]">
                                <div>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="text-[#7F7F7F] font-bold">(86)</div>
                            </div>
                        </div>
                    </div>
                    <!-- Sản phẩm 4 -->
                    <div class="swiper-slide slide-best-seller w-1/4">
                        <!-- Nội dung sản phẩm 1 -->
                        <div class="card_product flex flex-col gap-[8px] object-cover">
                            <div class="background-card bg-[#F5F5F5] h-[210px] relative">
                                <div class="absolute top-0 right-0 w-full">
                                    <div class="flex justify-end p-3">
                                        <div class="flex flex-col items-center gap-[8px]">
                                            <button
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                                </svg>
                                            </button>
                                            <a href="#"
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-center items-center h-full">
                                    <img src="{{ asset('images/gamepad.png') }}" class="w-[172px] h-[152px] object-cover"
                                        alt="HAVIT HV-G92 Gamepad">
                                </div>
                                <div
                                    class="card-button-container absolute bottom-0 flex justify-center items-center w-full h-[41px] bg-[#000]">
                                    <button class="text-white">Thêm vào giỏ hàng</button>
                                </div>
                            </div>
                            <h3 class="text-[20px] mt-2">HAVIT HV-G92 Gamepad</h3>
                            <div class="flex items-center gap-[12px]">
                                <p class="text-[#7F7F7F]">$60</p>
                                <p class="text-[#BDBDBD] line-through">$100</p>
                            </div>
                            <div class="flex text-[#FF9F1C] h-[20px] gap-[8px]">
                                <div>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="text-[#7F7F7F] font-bold">(86)</div>
                            </div>
                        </div>
                    </div>
                    <!-- Sản phẩm 5 -->
                    <div class="swiper-slide slide-best-seller w-1/4">
                        <!-- Nội dung sản phẩm 1 -->
                        <div class="card_product flex flex-col gap-[8px] object-cover">
                            <div class="background-card bg-[#F5F5F5] h-[210px] relative">
                                <div class="absolute top-0 right-0 w-full">
                                    <div class="flex justify-end p-3">
                                        <div class="flex flex-col items-center gap-[8px]">
                                            <button
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                                </svg>
                                            </button>
                                            <a href="#"
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-center items-center h-full">
                                    <img src="{{ asset('images/gamepad.png') }}" class="w-[172px] h-[152px] object-cover"
                                        alt="HAVIT HV-G92 Gamepad">
                                </div>
                                <div
                                    class="card-button-container absolute bottom-0 flex justify-center items-center w-full h-[41px] bg-[#000]">
                                    <button class="text-white">Thêm vào giỏ hàng</button>
                                </div>
                            </div>
                            <h3 class="text-[20px] mt-2">HAVIT HV-G92 Gamepad</h3>
                            <div class="flex items-center gap-[12px]">
                                <p class="text-[#7F7F7F]">$60</p>
                                <p class="text-[#BDBDBD] line-through">$100</p>
                            </div>
                            <div class="flex text-[#FF9F1C] h-[20px] gap-[8px]">
                                <div>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="text-[#7F7F7F] font-bold">(86)</div>
                            </div>
                        </div>
                    </div>
                    <!-- Sản phẩm 6 -->
                    <div class="swiper-slide slide-best-seller w-1/4">
                        <!-- Nội dung sản phẩm 1 -->
                        <div class="card_product flex flex-col gap-[8px] object-cover">
                            <div class="background-card bg-[#F5F5F5] h-[210px] relative">
                                <div class="absolute top-0 right-0 w-full">
                                    <div class="flex justify-end p-3">
                                        <div class="flex flex-col items-center gap-[8px]">
                                            <button
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                                </svg>
                                            </button>
                                            <a href="#"
                                                class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-center items-center h-full">
                                    <img src="{{ asset('images/gamepad.png') }}" class="w-[172px] h-[152px] object-cover"
                                        alt="HAVIT HV-G92 Gamepad">
                                </div>
                                <div
                                    class="card-button-container absolute bottom-0 flex justify-center items-center w-full h-[41px] bg-[#000]">
                                    <button class="text-white">Thêm vào giỏ hàng</button>
                                </div>
                            </div>
                            <h3 class="text-[20px] mt-2">HAVIT HV-G92 Gamepad</h3>
                            <div class="flex items-center gap-[12px]">
                                <p class="text-[#7F7F7F]">$60</p>
                                <p class="text-[#BDBDBD] line-through">$100</p>
                            </div>
                            <div class="flex text-[#FF9F1C] h-[20px] gap-[8px]">
                                <div>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="text-[#7F7F7F] font-bold">(86)</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Banner phụ -->
    <section class="container mx-auto px-4 py-8">
        <div class="bg-black text-white h-[600px]">
            <div class="row h-full">
                <div class="col-md-5">
                    <div class="flex flex-col justify-center h-full p-[55px] gap-[40px]">
                        <div class="flex flex-col gap-[32px]">
                            <span class="text-[20px] text-[#00FF66] font-semibold">Danh mục</span>
                            <h2 class="text-[#fff] text-[63px] font-bold">Nâng cao trải nghiệm âm nhạc</h2>
                            <div class="timer-container flex items-center gap-[24px]">
                                <div
                                    class="timer-item w-[62px] h-[62px] rounded-full bg-[#fff] text-[#000] flex flex-col items-center justify-center">
                                    <span class="timer-value text-[18px] font-bold">23</span>
                                    <span class="timer-label text-[13px]">Giờ</span>
                                </div>
                                <div
                                    class="timer-item w-[62px] h-[62px] rounded-full bg-[#fff] text-[#000] flex flex-col items-center justify-center">
                                    <span class="timer-value text-[18px] font-bold">05</span>
                                    <span class="timer-label text-[13px]">Ngày</span>
                                </div>
                                <div
                                    class="timer-item w-[62px] h-[62px] rounded-full bg-[#fff] text-[#000] flex flex-col items-center justify-center">
                                    <span class="timer-value text-[18px] font-bold">05</span>
                                    <span class="timer-label text-[13px]">Phút</span>
                                </div>
                                <div
                                    class="timer-item w-[62px] h-[62px] rounded-full bg-[#fff] text-[#000] flex flex-col items-center justify-center">
                                    <span class="timer-value text-[18px] font-bold">05</span>
                                    <span class="timer-label text-[13px]">Giây</span>
                                </div>
                            </div>
                        </div>
                        <button
                            class="bg-[#00FF66] text-[#fff] text-[20px] font-semibold px-[24px] py-[12px] w-[179px] h-[58px] rounded-[4px]">Mua
                            ngay</button>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="flex justify-center items-center h-full p-[55px]">
                        <div class="w-[310px] h-[300px] rounded-full absolute"
                            style="background: radial-gradient(circle, #D9D9D9 100%); filter: blur(150px);">
                        </div>
                        <div class="relative">
                            <img src="{{ asset('images/jbl_boombox.png') }}" class="object-cover" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Khám phá sản phẩm -->
    <section class="container mx-auto px-4 py-8">
        <div class="flex gap-3 items-center mb-3">
            <div style="background-color: #BDBDBD;" class="rounded h-[45px] w-[20px]"></div>
            <span style="color: #BDBDBD;" class="font-bold">Sản phẩm của chúng tôi</span>
        </div>
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center justify-center">
                <h2 class="text-[36px] font-bold mr-[87px]">Khám phá sản phẩm của chúng tôi</h2>
            </div>
            <div class="flex items-center gap-2">
                <button style="background-color: #F5F5F5;"
                    class="prev-slide-explore p-2 w-[46px] h-[46px] rounded-full flex items-center justify-center"
                    data-index="1"><i class="fa-solid fa-arrow-left text-[20px]"></i></button>
                <button style="background-color: #F5F5F5;"
                    class="next-slide-explore p-2 w-[46px] h-[46px] rounded-full flex items-center justify-center"
                    data-index="1"><i class="fa-solid fa-arrow-right text-[20px]"></i></button>
            </div>
        </div>
        <div class="container-fluid p-0 h-[700px] overflow-hidden">
            <div class="swiper-container-explore">
                <div class="swiper-wrapper flex gap-[40px]">
                    <?php
                    // Dữ liệu mẫu (có thể thay bằng kết quả từ cơ sở dữ liệu)
                    $products = [
                        ['id' => 1, 'name' => 'HAVIT HV-G92 Gamepad', 'price' => 60, 'old_price' => 100, 'image' => 'gamepad.png', 'rating' => 5, 'reviews' => 86],
                        ['id' => 2, 'name' => 'Canon EOS DSLR Camera', 'price' => 500, 'old_price' => 600, 'image' => 'camera.png', 'rating' => 4, 'reviews' => 120],
                        ['id' => 3, 'name' => 'ASUS FHD Gaming Laptop', 'price' => 700, 'old_price' => 800, 'image' => 'laptop.png', 'rating' => 5, 'reviews' => 95],
                        ['id' => 4, 'name' => 'Curelology Product Set', 'price' => 50, 'old_price' => 70, 'image' => 'skincare.png', 'rating' => 4, 'reviews' => 75],
                        ['id' => 5, 'name' => 'Kids Electric Car', 'price' => 200, 'old_price' => 250, 'image' => 'car.png', 'rating' => 4, 'reviews' => 60],
                        ['id' => 6, 'name' => 'Jr. Zoom Soccer Cleats', 'price' => 80, 'old_price' => 100, 'image' => 'cleats.png', 'rating' => 5, 'reviews' => 45],
                        ['id' => 7, 'name' => 'GPI Shooter USB Gamepad', 'price' => 40, 'old_price' => 60, 'image' => 'gamepad2.png', 'rating' => 4, 'reviews' => 30],
                        ['id' => 8, 'name' => 'Quilted Satin Jacket', 'price' => 120, 'old_price' => 150, 'image' => 'jacket.png', 'rating' => 5, 'reviews' => 90],
                        // Thêm các sản phẩm khác nếu cần
                    ];
                    
                    // Chia dữ liệu thành các hàng (mỗi hàng 4 sản phẩm)
                    $rows = array_chunk($products, 4);
                    $rows = array_merge($rows, $rows); // Tạo 4 slide
                    ?>
                    <?php foreach ($rows as $row): ?>
                    <div class="swiper-slide slide-explore">
                        <div class="flex gap-[5px]">
                            <?php foreach ($row as $product): ?>
                            <div class="w-1/4">
                                <div class="card_product_explore flex flex-col gap-[8px] object-cover w-[355px]">
                                    <div class="background-card bg-[#F5F5F5] h-[210px] relative">
                                        <div class="absolute top-0 right-0 w-full">
                                            <div class="flex justify-end p-3">
                                                <div class="flex flex-col items-center gap-[8px]">
                                                    <button
                                                        class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                                        </svg>
                                                    </button>
                                                    <a href="#"
                                                        class="bg-[#FFF] rounded-full p-[5px] w-[34px] h-[34px] flex items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor"
                                                            class="w-[22px] h-[20px] text-center items-center justify-center flex">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex justify-center items-center h-full">
                                            <img src="{{ asset('images/<?php echo $product['image']; ?>') }}"
                                                class="w-[172px] h-[152px] object-cover" alt="<?php echo $product['name']; ?>">
                                        </div>
                                        <div
                                            class="card-button-container absolute bottom-0 flex justify-center items-center w-full h-[41px] bg-[#000]">
                                            <button class="text-white">Thêm vào giỏ hàng</button>
                                        </div>
                                    </div>
                                    <h3 class="text-[20px] mt-2"><?php echo $product['name']; ?></h3>
                                    <div class="flex items-center gap-[12px]">
                                        <p class="text-[#7F7F7F]">$<?php echo $product['price']; ?></p>
                                        <p class="text-[#BDBDBD] line-through">$<?php echo $product['old_price']; ?></p>
                                    </div>
                                    <div class="flex text-[#FF9F1C] h-[20px] gap-[8px]">
                                        <div>
                                            <?php
                                            for ($i = 1; $i <= 5; $i++) {
                                                $starClass = $i <= $product['rating'] ? 'fa-solid' : 'fa-regular';
                                                echo "<i class='$starClass fa-star'></i>";
                                            }
                                            ?>
                                        </div>
                                        <div class="text-[#7F7F7F] font-bold">(<?php echo $product['reviews']; ?>)</div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="flex justify-center items-center mt-5">
            <button
                class="border-1 border-[#000] px-4 py-2 rounded-[4px] w-[264px] h-[56px] text-[#000] hover:bg-[#000] hover:text-[#FFF]">
                Xem tất cả sản phẩm
            </button>
        </div>
    </section>

    <!-- Hàng mới về -->
    <section class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-4">HÀNG MỚI VỀ</h2>
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-gray-200 h-64 flex items-center justify-center">
                <div class="text-center">
                    <h3 class="text-xl font-bold">PlayStation 5</h3>
                    <button class="mt-2 px-4 py-2 bg-green-500 text-white rounded buy-now-btn">MUA NGAY</button>
                </div>
            </div>
            <div class="bg-gray-200 h-64 flex items-center justify-center">
                <div class="text-center">
                    <h3 class="text-xl font-bold">Loa thông minh</h3>
                    <button class="mt-2 px-4 py-2 bg-green-500 text-white rounded buy-now-btn">MUA NGAY</button>
                </div>
            </div>
            <div class="bg-gray-200 h-64 flex items-center justify-center">
                <div class="text-center">
                    <h3 class="text-xl font-bold">Nước hoa Gucci</h3>
                    <button class="mt-2 px-4 py-2 bg-green-500 text-white rounded buy-now-btn">MUA NGAY</button>
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript -->
    <script src="{{ asset('js/home.js') }}"></script>
@endsection
