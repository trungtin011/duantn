<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title')</title>

    <!-- Font + Tailwind + Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/user/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/client-wishlist.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/orderDetail.css') }}">
    @stack('styles')
</head>

<body class="font-[Inter]">
    <!-- Top Header -->
    <div class="bg-black text-white py-3">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4 px-4">
            <div class="flex flex-col md:flex-row items-center gap-2 text-center md:text-left">
                <span>Khuyến mãi mùa hè cho tất cả đồ bơi và giao hàng nhanh miễn phí - GIẢM 50%!</span>
                <button class="text-white font-bold border-b border-white hover:text-orange-500">Mua ngay</button>
            </div>
            <div class="flex items-center gap-2">
                <select class="bg-transparent border border-none text-white px-2 py-1 rounded text-sm">
                    <option class="text-black">Tiếng Việt</option>
                    <option class="text-black">English</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white border-b" x-data="{ mobileMenuOpen: false, userDropdownOpen: false }">
        <div class="container mx-auto px-[10px] sm:px-0 py-3 flex justify-between items-center">
            <!-- Logo -->
            <a class="text-xl font-bold text-gray-900" href="/">Exclusive</a>

            <!-- Menu cho desktop -->
            <ul class="hidden md:flex gap-6 text-sm font-medium text-gray-700">
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

                @auth
                    @if (optional(Auth::user()->role)->value == 'customer' || Auth::user()->role == 'customer')
                        <li>
                            <a href="/seller/index" class="text-orange-500 font-semibold hover:text-orange-600">
                                Bạn có muốn trở thành người bán?
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>


            <!-- Icon menu mobile -->
            <button class="md:hidden text-2xl text-gray-700" @click="mobileMenuOpen = !mobileMenuOpen">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Search & Icons -->
            <div class="hidden md:flex items-center gap-3">
                <input type="text" placeholder="Bạn muốn tìm kiếm gì ?"
                    class="px-4 py-1.5 text-sm rounded-full border border-gray-300 focus:outline-none focus:ring-1 focus:ring-orange-500" />
                <a><i class="fa fa-search text-gray-700 hover:text-orange-500"></i></a>
                <a href="{{ route('wishlist') }}">
                    <i class="fa fa-heart text-gray-700 hover:text-orange-500"></i>
                </a>
                <a href="{{ route('cart') }}">
                    <i class="fa fa-shopping-cart text-gray-700 hover:text-orange-500"></i>
                </a>

                <div class="relative" @click="userDropdownOpen = !userDropdownOpen"
                    @click.away="userDropdownOpen = false">
                    <div class="flex items-center gap-2 w-auto">
                        @auth
                            <span
                                class="text-sm font-semibold text-gray-700">{{ Auth::user()->fullname ?? Auth::user()->username }}</span>
                        @endauth
                        <div
                            class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-700 hover:bg-gray-200">
                            <i class="fa fa-user"></i>
                        </div>
                    </div>
                    <!-- Dropdown Menu -->
                    <div x-show="userDropdownOpen"
                        class="absolute right-[-1px] mt-2 p-3 w-[250px] bg-gradient-to-b from-gray-800 to-purple-900 bg-opacity-90 backdrop-blur-md rounded-md shadow-lg z-10">
                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-white hover:bg-purple-600">
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
                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-white hover:bg-purple-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Đơn hàng bị hủy
                        </a>
                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-white hover:bg-purple-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                            </svg>
                            Đánh giá của tôi
                        </a>
                        <a href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();"
                            class="flex items-center gap-2 px-4 py-2 text-white hover:bg-purple-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                            </svg>
                            Đăng xuất
                        </a>
                        <form id="logout-form-header" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
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
                        <!-- Dropdown Menu trên mobile -->
                        <div x-show="userDropdownOpen"
                            class="absolute right-[-1px] mt-2 p-3 w-[250px] bg-gradient-to-b from-gray-800 to-purple-900 bg-opacity-90 backdrop-blur-md rounded-md shadow-lg z-10">
                            <a href="#"
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
                            <a href="#"
                                class="flex items-center gap-2 px-4 py-2 text-white hover:bg-purple-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                Đơn hàng bị hủy
                            </a>
                            <a href="#"
                                class="flex items-center gap-2 px-4 py-2 text-white hover:bg-purple-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.563.563 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                </svg>
                                Đánh giá của tôi
                            </a>
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
    <main class="bg-white pb-10">
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
                <a href="#" class="text-sm text-gray-400 hover:text-orange-500 block">Chính sách bảo mật</a>
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

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>

</html>
