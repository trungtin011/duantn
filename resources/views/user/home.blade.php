@extends('layouts.app')

@section('title', 'Trang chủ')
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
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-[21px] h-[21px] ml-1 arrow-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>

                        </button>
                        <div id="dropdownMenu"
                            class="dropdown-content text-[18px] shadow-lg font-semibold absolute top-[32px] left-[384px] z-10 w-[1152px] h-[400px] bg-white">
                            <div class="flex h-[100%]">
                                <div class="flex flex-col gap-[25px] p-4">
                                    <div class="w-1/2 flex gap-[40px]">
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
                                    <div class="w-1/2 flex gap-[40px]">
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
                                <div class="flex justify-center items-center">
                                    <img src="{{ asset('images/thoitrangnu.jpg') }}" alt="banner"
                                        class="object-cover w-[100%] h-[400px] object-cover">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button id="dropdownToggleSecond"
                        class="text-gray-700 hover:text-black flex items-center justify-between w-full mb-2 text-[18px] font-semibold">
                        Thời trang nam
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-[21px] h-[21px] ml-1 arrow-icon-second">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div id="dropdownMenuSecond"
                        class="dropdown-content text-[18px] shadow-lg font-semibold absolute top-[32px] left-[384px] z-10 w-[1152px] h-[400px] bg-white">
                        <div class="flex h-[100%]">
                            <div class="flex flex-col gap-[25px] p-4">
                                <div class="w-1/2 flex gap-[40px]">
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
                                <div class="w-1/2 flex gap-[40px]">
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
                            <div class="flex justify-center items-center">
                                <img src="{{ asset('images/thoitrangnam.jpg') }}" alt="banner"
                                    class="object-cover w-[100%] h-[400px] object-cover">
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <a href="" class="text-gray-700 hover:text-black text-[18px] font-semibold">Đồ điện tử</a>
                </li>
                <li>
                    <a href="" class="text-gray-700 hover:text-black text-[18px] font-semibold">phong cách sống</a>
                </li>
                <li>
                    <a href="" class="text-gray-700 hover:text-black text-[18px] font-semibold">Thuốc</a>
                </li>
                <li>
                    <a href="" class="text-gray-700 hover:text-black text-[18px] font-semibold">Thể thao</a>
                </li>
                <li>
                    <a href="" class="text-gray-700 hover:text-black text-[18px] font-semibold">Đồ chơi trẻ em</a>
                </li>
                <li>
                    <a href="" class="text-gray-700 hover:text-black text-[18px] font-semibold">Thực phẩm thú
                        cưng
                    </a>
                </li>
                <li>
                    <a href="" class="text-gray-700 hover:text-black text-[18px] font-semibold">Sức khỏe và sắc
                        đẹp
                    </a>
                </li>
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

    <!-- Khuyến mãi with Countdown Timer -->
    <section class="container mx-auto px-4 py-8">
        <div class="flex gap-3 items-center mb-3">
            <div style="background-color: #BDBDBD;" class="rounded h-[45px] w-[20px]"></div>
            <span style="color: #BDBDBD;" class="font-bold">Hôm nay</span>
        </div>
        <div class="flex justify-between items-center mb-[60px]">
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
        <div class="flex justify-between items-center mb-[60px]">
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
        <div class="bg-black text-white min-h-[600px] flex flex-col md:flex-row">
            <!-- Bên trái: Text + Nút + Đồng hồ -->
            <div class="w-full md:w-5/12 flex flex-col justify-center p-8 md:p-[55px] gap-10">
                <div class="flex flex-col gap-8">
                    <span class="text-[20px] text-[#00FF66] font-semibold">Danh mục</span>
                    <h2 class="text-[40px] md:text-[63px] font-bold leading-tight">Nâng cao trải nghiệm âm nhạc</h2>
                    <div class="flex items-center gap-6 flex-wrap">
                        <div
                            class="w-[62px] h-[62px] rounded-full bg-white text-black flex flex-col items-center justify-center">
                            <span class="text-[18px] font-bold">23</span>
                            <span class="text-[13px]">Giờ</span>
                        </div>
                        <div
                            class="w-[62px] h-[62px] rounded-full bg-white text-black flex flex-col items-center justify-center">
                            <span class="text-[18px] font-bold">05</span>
                            <span class="text-[13px]">Ngày</span>
                        </div>
                        <div
                            class="w-[62px] h-[62px] rounded-full bg-white text-black flex flex-col items-center justify-center">
                            <span class="text-[18px] font-bold">05</span>
                            <span class="text-[13px]">Phút</span>
                        </div>
                        <div
                            class="w-[62px] h-[62px] rounded-full bg-white text-black flex flex-col items-center justify-center">
                            <span class="text-[18px] font-bold">05</span>
                            <span class="text-[13px]">Giây</span>
                        </div>
                    </div>
                </div>
                <button class="bg-[#00FF66] text-white text-[20px] font-semibold px-6 py-3 w-[179px] h-[58px] rounded-md">
                    Mua ngay
                </button>
            </div>

            <!-- Bên phải: Ảnh sản phẩm -->
            <div class="w-full md:w-7/12 relative flex justify-center items-center p-8 md:p-[55px]">
                <!-- Vòng sáng -->
                <div class="w-[310px] h-[300px] rounded-full absolute z-0"
                    style="background: radial-gradient(circle, #D9D9D9 100%); filter: blur(150px);">
                </div>
                <!-- Ảnh loa -->
                <div class="relative z-10">
                    <img src="{{ asset('images/jbl_boombox.png') }}" alt="Loa JBL"
                        class="object-cover max-w-full h-auto">
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
        <div class="flex justify-between items-center mb-[60px]">
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
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor"
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
            <button class="px-4 py-2 rounded-[4px] w-[264px] h-[56px] text-[#000] hover:bg-[#000] hover:text-[#FFF]"
                style="border: 1px solid #000;">
                Xem tất cả sản phẩm
            </button>
        </div>
    </section>

    <!-- Hàng mới về -->
    <section class="container mx-auto px-4 py-8">
        <div class="flex gap-3 items-center mb-3">
            <div style="background-color: #BDBDBD;" class="rounded h-[45px] w-[20px]"></div>
            <span style="color: #BDBDBD;" class="font-bold">Nổi bật</span>
        </div>
        <div class="flex justify-between items-center mb-[60px]">
            <div class="flex items-center justify-center">
                <h2 class="text-[36px] font-bold mr-[87px]">Hàng mới về</h2>
            </div>
        </div>
        <div class="flex gap-[30px]">
            <div class="relative w-[744px] h-[600px] bg-[#0D0D0D] flex items-end justify-center rounded-[4px]">
                <img src="{{ asset('images/ps5_slim.png') }}" alt="banner"
                    class="object-cover w-[511px] h-[511px]">
                <div class="absolute bottom-[30px] left-[35px] text-white flex flex-col gap-[16px] w-[400px]">
                    <h2 class="text-[28px] font-bold">PlayStation 5 Slim</h2>
                    <p class="text-[18px]">PlayStation 5 Slim là bản nâng cấp của PlayStation 5, với thiết kế nhỏ gọn và
                        hiệu suất cao hơn.</p>
                    <button class="text-white buy-now-btn flex items-center">
                        <span style="border-bottom: 1px solid #858585; padding-bottom: 5px; font-size: 18px;">Mua
                            ngay</span>
                    </button>
                </div>
            </div>
            <div class="w-[744px] flex flex-col justify-center items-center gap-[30px]">
                <div class="relative flex justify-end items-center w-[100%] h-[284px] bg-[#0D0D0D] rounded-[4px]">
                    <img src="{{ asset('images/attractive_woman1.png') }}" alt="banner"
                        class="object-cover w-[432px] h-[284px]">
                    <div class="absolute bottom-[30px] left-[35px] text-white flex flex-col gap-[16px] w-[400px]">
                        <h2 class="text-[28px] font-bold">Bộ sưu tập của phụ nữ</h2>
                        <p class="text-[18px]">Bộ sưu tập phụ nữ nổi bật mang đến cho bạn cảm giác khác biệt.</p>
                        <button class="text-white buy-now-btn flex items-center">
                            <span style="border-bottom: 1px solid #858585; padding-bottom: 5px; font-size: 18px;">Mua
                                ngay</span>
                        </button>
                    </div>
                </div>
                <div class="w-[100%] flex gap-[30px]">
                    <div class="relative flex justify-center items-center w-[364px] h-[284px] bg-[#0D0D0D] rounded-[4px]">
                        <img src="{{ asset('images/speaker.png') }}" alt="banner"
                            class="object-cover w-[190px] h-[221px]">
                        <div class="absolute bottom-[30px] left-[35px] text-white flex flex-col gap-[10px] w-[400px]">
                            <h2 class="text-[28px] font-bold">Loa</h2>
                            <p class="text-[18px]">Loa không dây Amazon</p>
                            <button class="text-white buy-now-btn flex items-center">
                                <span style="border-bottom: 1px solid #858585; padding-bottom: 5px; font-size: 18px;">Mua
                                    ngay</span>
                            </button>
                        </div>
                    </div>
                    <div class="relative flex justify-center items-center w-[364px] h-[284px] bg-[#0D0D0D] rounded-[4px]">
                        <img src="{{ asset('images/perfume.png') }}" alt="banner"
                            class="object-cover w-[201px] h-[203px]">
                        <div class="absolute bottom-[30px] left-[35px] text-white flex flex-col gap-[10px] w-[400px]">
                            <h2 class="text-[28px] font-bold">Nước hoa</h2>
                            <p class="text-[18px]">NƯỚC HOA GUCCI INTENSE OUD</p>
                            <button class="text-white buy-now-btn flex items-center">
                                <span style="border-bottom: 1px solid #858585; padding-bottom: 5px; font-size: 18px;">Mua
                                    ngay</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dịch vụ -->
    <section class="container mx-auto px-4 py-8">
        <div class="flex gap-3 items-center gap-[88px]">
            <div class="flex flex-col items-center justify-center gap-[30px] w-1/3">
                <div class="bg-[#C1C1C1] rounded-full flex items-center justify-center w-[80px] h-[80px]">
                    <div class="bg-[#000] rounded-full flex items-center justify-center w-[58px] h-[58px]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            class="bi bi-truck text-center text-[30px] text-[#fff] w-[30px]" viewBox="0 0 16 16">
                            <path
                                d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5zm1.294 7.456A2 2 0 0 1 4.732 11h5.536a2 2 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456M12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
                        </svg>
                    </div>
                </div>
                <div class="flex flex-col gap-[10px] text-center">
                    <h2 class="text-[24px] font-bold">Giao hàng miễn phí và nhanh chóng</h2>
                    <p class="text-[14px]">Miễn phí vận chuyển cho đơn hàng trên 1 trăm nghìn đồng</p>
                </div>
            </div>
            <div class="flex flex-col items-center justify-center gap-[30px] w-1/3">
                <div class="bg-[#C1C1C1] rounded-full flex items-center justify-center w-[80px] h-[80px]">
                    <div class="bg-[#000] rounded-full flex items-center justify-center w-[58px] h-[58px]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            class="bi bi-headset text-center text-[30px] text-[#fff] w-[30px]" viewBox="0 0 16 16">
                            <path
                                d="M8 1a5 5 0 0 0-5 5v1h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a6 6 0 1 1 12 0v6a2.5 2.5 0 0 1-2.5 2.5H9.366a1 1 0 0 1-.866.5h-1a1 1 0 1 1 0-2h1a1 1 0 0 1 .866.5H11.5A1.5 1.5 0 0 0 13 12h-1a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h1V6a5 5 0 0 0-5-5" />
                        </svg>
                    </div>
                </div>
                <div class="flex flex-col gap-[10px] text-center">
                    <h2 class="text-[24px] font-bold">Chăm sóc khách hàng 24/7</h2>
                    <p class="text-[14px]">Hỗ trợ khách hàng thân thiện 24/7</p>
                </div>
            </div>
            <div class="flex flex-col items-center justify-center gap-[30px] w-1/3">
                <div class="bg-[#C1C1C1] rounded-full flex items-center justify-center w-[80px] h-[80px]">
                    <div class="bg-[#000] rounded-full flex items-center justify-center w-[58px] h-[58px]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            class="bi bi-shield-check text-center text-[30px] text-[#fff] w-[30px]" viewBox="0 0 16 16">
                            <path
                                d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56" />
                            <path
                                d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0" />
                        </svg>
                    </div>
                </div>
                <div class="flex flex-col gap-[10px] text-center">
                    <h2 class="text-[24px] font-bold">Đảm bảo hoàn tiền</h2>
                    <p class="text-[14px]">Đảm bảo hoàn tiền trong 30 ngày</p>
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript -->
    <script src="{{ asset('js/home.js') }}"></script>
@endsection
