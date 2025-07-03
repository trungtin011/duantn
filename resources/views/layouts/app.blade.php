<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png" />
    <title>@yield('title')</title>

    <!-- Font + Tailwind + Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/user/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/client-wishlist.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/orderDetail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/post.css') }}">
    @stack('styles')
    @vite('resources/js/echo.js')
</head>

@auth
    <script>
        window.addEventListener('beforeunload', function() {
            navigator.sendBeacon('/update-session', JSON.stringify({
                user_id: {{ auth()->id() }}
            }));
        });
    </script>
@endauth

<body class="font-[Inter]">
    <!-- Top Header -->
    <div class="bg-black text-white py-3">
        <div class="container mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
            @auth
                @if (optional(Auth::user()->role)->value == 'customer' || Auth::user()->role == 'customer')
                    <div>
                        <a href="{{ route('seller.index') }}" class="text-[#EF3248] capitalize hover:text-orange-600">
                            Kênh người bán
                        </a>
                    </div>
                @endif
            @endauth
            <div class="flex flex-col md:flex-row items-center gap-2 text-center md:text-left">
                <span>Khuyến mãi mùa hè cho tất cả đồ bơi và giao hàng nhanh miễn phí - GIẢM 50%!</span>
                <button class="text-white font-bold border-b border-white hover:text-orange-500">Mua ngay</button>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative dropdown-notification cursor-pointer">
                    <div class="flex items-center gap-1 hover:text-[#EF3248] cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                        <span class="capitalize text-sm">Thông báo</span>
                    </div>
                    <div class="absolute dropdown-notification-content z-10 right-0 bg-white w-[300px] p-3 shadow">
                        <!-- Thêm phần nhô lên -->
                        <div class="absolute top-[-15px] right-10 transform w-5 h-5 bg-white clip-triangle">
                        </div>
                        <div class="">
                            <span class="text-sm text-gray-500">Thông báo tin nhắn mới</span>
                        </div>
                        <div class="border-t border-gray-200 my-2"></div>
                        <div class="flex items-center gap-1 text-black">
                            <img src="https://down-vn.img.susercontent.com/file/6cb7e633f8b63757463b676bd19a50e4@resize_w320_nl.webp"
                                alt="phone" class="w-[50px] h-[50px] rounded-[5px]">
                            <div class="flex flex-col gap-1 overflow-hidden">
                                <h6 class="uppercase text-sm w-full truncate">
                                    LIVESTREAMING: Giảm giá 50% cho tất cả đồ bơi và giao hàng nhanh miễn phí!
                                </h6>
                                <span class="text-xs text-gray-500">1 phút trước</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <select class="bg-transparent border border-none text-white px-2 py-1 rounded text-sm">
                        <option class="text-black">Tiếng Việt</option>
                        <option class="text-black">English</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <div class="relative dropdown-parent">
                        @guest
                            <div class="flex items-center gap-2 text-sm">
                                <a href="{{ route('signup') }}" class="hover:text-[#EF3248]">
                                    Đăng ký
                                </a>
                                |
                                <a href="{{ route('login') }}" class="hover:text-[#EF3248]">
                                    Đăng nhập
                                </a>
                            </div>
                        @endguest
                        @auth
                            <div class="flex items-center gap-1 w-auto">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center">
                                    <!-- Avatar placeholder -->
                                    <img src="https://down-vn.img.susercontent.com/file/6cb7e633f8b63757463b676bd19a50e4@resize_w320_nl.webp"
                                        alt="avatar" class="w-full h-full rounded-full">
                                </div>
                                @auth
                                    <span
                                        class="text-sm font-semibold text-white hover:text-[#EF3248] cursor-pointer">{{ Auth::user()->fullname ?? Auth::user()->username }}</span>
                                @endauth
                            </div>
                        @endauth
                        <!-- Dropdown Menu (Desktop) -->
                        <div class="absolute dropdown-content w-[250px] shadow bg-white">
                            @auth
                                <div class="absolute top-[-15px] right-10 transform w-5 h-5 bg-white clip-triangle-second">
                                </div>
                            @endauth
                            @auth
                                @if (Auth::user()->role === \App\Enums\UserRole::ADMIN)
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="flex items-center gap-2 px-6 py-3 text-black hover:bg-gray-100 text-sm hover:text-[#EF3248]">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            class="size-6">
                                            <path stroke="#FFFFFF" stroke-width="2"
                                                d="M4 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5ZM14 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1V5ZM4 16a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3ZM14 13a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1v-6Z" />
                                        </svg>
                                        Quản trị viên
                                    </a>
                                    <a href="{{ route('admin.products.index') }}"
                                        class="flex items-center gap-2 px-6 py-3 text-black hover:bg-gray-100 text-sm hover:text-[#EF3248]">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                        </svg>
                                        Quản lý sản phẩm
                                    </a>
                                @elseif (Auth::user()->role === \App\Enums\UserRole::SELLER)
                                    <a href="{{ route('seller.dashboard') }}"
                                        class="flex items-center gap-2 px-6 py-3 text-black hover:bg-gray-100 text-sm hover:text-[#EF3248]">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3.75 6A2.25 2.25 0 016 3.75h12A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6z" />
                                        </svg>
                                        Bảng điều khiển Seller
                                    </a>
                                    <a href="{{ route('seller.products.index') }}"
                                        class="flex items-center gap-2 px-6 py-3 text-black hover:bg-gray-100 text-sm hover:text-[#EF3248]">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                        </svg>
                                        Sản phẩm của tôi
                                    </a>
                                    <a href="{{ route('seller.order.index') }}"
                                        class="flex items-center gap-2 px-6 py-3 text-black hover:bg-gray-100 text-sm hover:text-[#EF3248]">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                        </svg>
                                        Đơn hàng
                                    </a>
                                @elseif (Auth::user()->role === \App\Enums\UserRole::CUSTOMER)
                                    <a href="{{ route('account.profile') }}"
                                        class="flex items-center gap-2 px-6 py-3 text-black hover:bg-gray-100 text-sm hover:text-[#EF3248]">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                        </svg>
                                        Quản lý tài khoản
                                    </a>
                                    <a href="{{ route('order_history') }}"
                                        class="flex items-center gap-2 px-6 py-3 text-black hover:bg-gray-100 text-sm hover:text-[#EF3248]">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                        </svg>
                                        Đơn hàng của tôi
                                    </a>
                                    <a href="{{ route('wishlist') }}"
                                        class="flex items-center gap-2 px-6 py-3 text-black hover:bg-gray-100 text-sm hover:text-[#EF3248]">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                        </svg>
                                        Yêu thích
                                    </a>
                                    {{-- <a href="{{ route('seller.register') }}"
                                        class="flex items-center gap-2 px-6 py-3 text-black hover:bg-gray-100 text-sm hover:text-[#EF3248]">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                                        </svg>
                                        Trở thành người bán
                                    </a> --}}
                                @endif
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();"
                                    class="flex items-center gap-2 px-6 py-3 text-black hover:bg-gray-100 text-sm hover:text-[#EF3248]">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                                    </svg>
                                    Đăng xuất
                                </a>
                                <form id="logout-form-header" action="{{ route('logout') }}" method="POST"
                                    class="hidden">
                                    @csrf
                                </form>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white border-b" x-data="{ mobileMenuOpen: false, userDropdownOpen: false, notificationDropdownOpen: false }">
        <div class="container mx-auto px-[10px] sm:px-0 py-3 flex justify-between items-center">
            <!-- Logo -->
            @if (empty($settings->logo))
                <a class="w-full lg:w-[14%] flex items-center justify-center gap-2 py-2" href="/">
                    <div class="bg-black flex items-center gap-2 py-1 px-2 rounded lg:w-[175px]">
                        <img src="{{ asset('images/logo.svg') }}" alt="logo" class="w-[30%] h-[30%]">
                        <div class="text-white grid">
                            <h5 class="m-0 text-xl">ZynoxMall</h5>
                            <span class="text-xs text-right">zynoxmall.xyz</span>
                        </div>
                    </div>
                </a>
            @else
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="logo" class="w-20">
                </a>
            @endif
            <!-- Menu cho desktop -->
            {{-- <ul class="hidden md:flex gap-6 text-sm font-medium text-gray-700">
                <li><a href="{{ route('home') }}" class="hover:text-orange-500">Trang chủ</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-orange-500">Liên hệ</a></li>
                <li><a href="{{ route('about') }}" class="hover:text-orange-500">Về chúng tôi</a></li>
                @auth
                    @if (Auth::user()->role === 'customer')
                        <li>
                            <a href="{{ route('seller.register') }}"
                                class="text-orange-500 font-semibold hover:text-orange-600">
                                {{ __('messages.become_seller') }}
                            </a>
                        </li>
                    @endif
                @endauth
            </ul> --}}


            <!-- Icon menu mobile -->
            <button class="md:hidden text-2xl text-gray-700" @click="mobileMenuOpen = !mobileMenuOpen">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Search & Icons -->
            <div class="hidden md:flex items-center gap-10 w-5/6">
                <form action="{{ route('search') }}" method="GET"
                    class="rounded-full border border-gray-300 px-4 py-2 w-full flex items-center justify-between">
                    <input type="text" name="query" placeholder="Bạn muốn tìm kiếm gì ?"
                        class="text-sm focus:outline-none w-full" value="{{ request('query') }}" />
                    <button type="submit">
                        <i class="fa fa-search text-gray-700 hover:text-[#EF3248]"></i>
                    </button>
                </form>
                <div class="relative">
                    <div
                        class="absolute top-0 left-4 bg-red-500 rounded-full w-4 h-4 flex items-center justify-center z-10">
                        <span class="text-center text-xs text-white">3</span>
                    </div>
                    <a href="{{ route('cart') }}">
                        <i class="fa fa-shopping-cart text-gray-700 hover:text-red-500 text-2xl"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Menu xổ xuống mobile -->
        <div x-show="mobileMenuOpen" class="md:hidden px-4 pb-4">
            <ul class="flex flex-col gap-3 text-sm font-medium text-gray-700 border-t pt-3">
                <li><a href="{{ route('home') }}" class="hover:text-orange-500">Trang chủ</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-orange-500">Liên hệ</a></li>
                <li><a href="{{ route('about') }}" class="hover:text-orange-500">Về chúng tôi</a></li>
                @guest
                    <li><a href="{{ route('signup') }}" class="hover:text-orange-500">Đăng ký</a></li>
                @endguest
                @auth
                    @if (Auth::user()->role === 'customer')
                        <li>
                            <a href="{{ route('seller.register') }}"
                                class="text-orange-500 font-semibold hover:text-orange-600">
                                {{ __('messages.become_seller') }}
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>

            <div class="md:hidden flex items-center justify-center gap-3 mt-4">
                <input type="text" placeholder="Bạn muốn tìm kiếm gì ?"
                    class="px-4 py-1.5 text-sm rounded-full border border-gray-300 focus:outline-none focus:ring-1 focus:ring-orange-500" />
                <a><i class="fa fa-search text-gray-700 hover:text-orange-500"></i></a>
                <a href="{{ route('wishlist') }}"><i class="fa fa-heart text-gray-700 hover:text-orange-500"></i></a>
                <a href="{{ route('cart') }}"><i
                        class="fa fa-shopping-cart text-gray-700 hover:text-orange-500"></i></a>
                @if (Auth::check())
                    <div class="relative" @click="userDropdownOpen = !userDropdownOpen"
                        @click.away="userDropdownOpen = false">
                        <div
                            class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-700 hover:bg-gray-200">
                            <i class="fa fa-user"></i>
                        </div>
                        <!-- Dropdown Menu (Mobile) -->
                        <div x-show="userDropdownOpen"
                            class="absolute right-[-1px] mt-2 p-3 w-[250px] bg-gradient-to-b from-gray-800 to-purple-900 bg-opacity-90 backdrop-blur-md rounded-md shadow-lg z-10">
                            @auth
                                <!-- Admin Links -->
                                @if (Auth::user()->role === \App\Enums\UserRole::ADMIN)
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="flex items-center gap-2 px-4 py-2 text-white hover:bg-purple-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            class="size-6">
                                            <path stroke="#FFFFFF" stroke-width="2"
                                                d="M4 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5ZM14 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1V5ZM4 16a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3ZM14 13a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1v-6Z" />
                                        </svg>
                                        Quản trị viên
                                    </a>
                                    <a href="{{ route('admin.products.index') }}"
                                        class="flex items-center gap-2 px-4 py-2 text-white hover:bg-purple-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                        </svg>
                                        Quản lý sản phẩm
                                    </a>
                                    <!-- Seller Links -->
                                @elseif (Auth::user()->role === \App\Enums\UserRole::SELLER)
                                    <a href="{{ route('seller.dashboard') }}"
                                        class="flex items-center gap-2 px-4 py-2 text-white hover:bg-purple-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3.75 6A2.25 2.25 0 016 3.75h12A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6z" />
                                        </svg>
                                        Bảng điều khiển Seller
                                    </a>
                                    <a href="{{ route('seller.products.index') }}"
                                        class="flex items-center gap-2 px-4 py-2 text-white hover:bg-purple-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                        </svg>
                                        Sản phẩm của tôi
                                    </a>
                                    <a href="{{ route('seller.order.index') }}"
                                        class="flex items-center gap-2 px-4 py-2 text-white hover:bg-purple-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                        </svg>
                                        Đơn hàng
                                    </a>
                                    <!-- Customer Links -->
                                @elseif (Auth::user()->role === \App\Enums\UserRole::CUSTOMER)
                                    <a href="{{ route('account.profile') }}"
                                        class="flex items-center gap-2 px-4 py-2 text-white hover:bg-purple-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                        </svg>
                                        Quản lý tài khoản
                                    </a>
                                    <a href="{{ route('order_history') }}"
                                        class="flex items-center gap-2 px-4 py-2 text-white hover:bg-purple-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                        </svg>
                                        Đơn hàng của tôi
                                    </a>
                                    <a href="{{ route('wishlist') }}"
                                        class="flex items-center gap-2 px-4 py-2 text-white hover:bg-purple-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                        </svg>
                                        Danh sách ước
                                    </a>
                                    <a href="{{ route('seller.register') }}"
                                        class="flex items-center gap-2 px-4 py-2 text-white hover:bg-purple-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                                        </svg>
                                        Trở thành người bán
                                    </a>
                                @endif
                                <!-- Common Logout Link -->
                                <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST"
                                    class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center gap-2 px-4 py-2 text-white hover:bg-purple-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                                        </svg>
                                        Đăng xuất
                                    </button>
                                </form>
                            @endauth
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-700 hover:bg-gray-200">
                        <i class="fa fa-user"></i>
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="bg-white pb-10 min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#111] text-white pt-10">
        <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
            <!-- Exclusive -->
            <div>
                <h4 class="font-bold mb-2">Exclusive</h4>
                <p class="text-sm text-gray-400 mb-2">Đăng ký</p>
                <p class="text-sm text-gray-400 mb-2">Giảm giá 10% cho đơn hàng đầu tiên</p>
                <div class="flex">
                    <input type="email" placeholder="Nhập email của bạn"
                        class="w-full px-2 py-1 text-sm text-black rounded" />
                    <button class="ml-2 bg-white text-black px-2 py-1 rounded"><i
                            class="fa fa-arrow-right"></i></button>
                </div>
            </div>

            <!-- Hỗ trợ -->
            <div>
                <h4 class="font-bold mb-2">Hỗ trợ</h4>
                <p class="text-sm text-gray-400">403 Quang Trung, Buôn Ma Thuột, Đaklak</p>
                <p class="text-sm text-gray-400">exclusive@gmail.com</p>
                <p class="text-sm text-gray-400">0915571415</p>
            </div>

            <div>
                <h4 class="font-bold mb-2">Tài khoản</h4>
                @auth

                    <a href="#" class="text-sm text-gray-400 hover:text-orange-500 block">Tài khoản của tôi</a>
                    <a href="{{ route('order_history') }}" class="text-sm text-gray-400 hover:text-orange-500 block">Đơn
                        hàng</a>
                    <a href="{{ route('wishlist') }}" class="text-sm text-gray-400 hover:text-orange-500 block">Danh sách
                        ước</a>
                    <a href="{{ route('cart') }}" class="text-sm text-gray-400 hover:text-orange-500 block">Giỏ hàng</a>

                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form-footer').submit();"
                        class="text-sm text-gray-400 hover:text-orange-500 block">Đăng xuất</a>
                    <form id="logout-form-footer" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-orange-500 block">Đăng
                        nhập</a>
                    <a href="{{ route('register') }}" class="text-sm text-gray-400 hover:text-orange-500 block">Đăng
                        ký</a>
                    <a href="{{ route('cart') }}" class="text-sm text-gray-400 hover:text-orange-500 block">Giỏ hàng</a>
                    <a href="{{ route('wishlist') }}" class="text-sm text-gray-400 hover:text-orange-500 block">Danh sách
                        ước</a>
                    <a href="#" class="text-sm text-gray-400 hover:text-orange-500 block">Cửa hàng</a>
                @endauth
            </div>

            <!-- Liên kết nhanh -->
            <div>
                <h4 class="font-bold mb-2">Liên kết nhanh</h4>
                <a href="{{ route('help.center') }}" class="text-sm text-gray-400 hover:text-orange-500 block">Chính
                    sách bảo mật</a>
                <a href="#" class="text-sm text-gray-400 hover:text-orange-500 block">Điều khoản sử dụng</a>
                <a href="#" class="text-sm text-gray-400 hover:text-orange-500 block">Câu hỏi thường gặp</a>
                <a href="#" class="text-sm text-gray-400 hover:text-orange-500 block">Liên hệ</a>
            </div>

            <!-- Tải App -->
            <div>
                <h4 class="font-bold mb-2">Tải App</h4>
                <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                    class="w-28 mb-2" />
                <img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg"
                    class="w-28" />
                <p class="text-xs text-gray-400 mt-2">Tiết kiệm 5.3 ứng dụng dành cho người dùng mới</p>
            </div>

            <!-- Kết nối -->
            <div>
                <h4 class="font-bold mb-2">Kết nối</h4>
                <div class="flex gap-3 text-lg">
                    <a href="#" class="text-white hover:text-orange-500"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white hover:text-orange-500"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white hover:text-orange-500"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white hover:text-orange-500"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>

        <div class="border-t border-[#222] mt-10 py-4 text-center text-sm text-gray-400">
            © Copyright Rimel 2022. All rights reserved.
        </div>
    </footer>

    @stack('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        window.Laravel = {
            user: @json(Auth::user())
        };
    </script>
</body>

</html>
