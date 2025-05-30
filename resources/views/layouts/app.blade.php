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

    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
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
    <header class="bg-white border-b" x-data="{ mobileMenuOpen: false }">
        <div class="container mx-auto px-0 py-3 flex justify-between items-center">
            <!-- Logo -->
            <a class="text-xl font-bold text-gray-900" href="/">Exclusive</a>

            <!-- Menu cho desktop -->
            <ul class="hidden md:flex gap-6 text-sm font-medium text-gray-700">
                <li><a href="/" class="hover:text-orange-500">Trang chủ</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-orange-500">Liên hệ</a></li>
                <li><a href="#" class="hover:text-orange-500">Về chúng tôi</a></li>
                <li><a href="{{ route('signup') }}" class="hover:text-orange-500">Đăng ký</a></li>
            </ul>

            <!-- Icon menu mobile -->
            <button class="md:hidden text-2xl text-gray-700" @click="mobileMenuOpen = !mobileMenuOpen">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Search & Icons -->
            <div class="hidden md:flex items-center gap-3">
                <input type="text" placeholder="Bạn muốn tìm kiếm gì ?"
                    class="px-4 py-1.5 text-sm rounded-full border border-gray-300 focus:outline-none focus:ring-1 focus:ring-orange-500" />
                <button><i class="fa fa-search text-gray-700 hover:text-orange-500"></i></button>
                <button><i class="fa fa-heart text-gray-700 hover:text-orange-500"></i></button>
                <button><i class="fa fa-shopping-cart text-gray-700 hover:text-orange-500"></i></button>
                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-700">
                    <i class="fa fa-user"></i>
                </div>
            </div>
        </div>

        <!-- Menu xổ xuống mobile -->
        <div x-show="mobileMenuOpen" class="md:hidden px-4 pb-4">
            <ul class="flex flex-col gap-3 text-sm font-medium text-gray-700 border-t pt-3">
                <li><a href="/" class="hover:text-orange-500">Trang chủ</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-orange-500">Liên hệ</a></li>
                <li><a href="#" class="hover:text-orange-500">Về chúng tôi</a></li>
                <li><a href="{{ route('signup') }}" class="hover:text-orange-500">Đăng ký</a></li>
            </ul>

            <div class="md:hidden flex items-center justify-center gap-3 mt-4">
                <input type="text" placeholder="Bạn muốn tìm kiếm gì ?"
                    class="px-4 py-1.5 text-sm rounded-full border border-gray-300 focus:outline-none focus:ring-1 focus:ring-orange-500" />
                <button><i class="fa fa-search text-gray-700 hover:text-orange-500"></i></button>
                <button><i class="fa fa-heart text-gray-700 hover:text-orange-500"></i></button>
                <button><i class="fa fa-shopping-cart text-gray-700 hover:text-orange-500"></i></button>
                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-700">
                    <i class="fa fa-user"></i>
                </div>
            </div>
        </div>
    </header>


    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#111] text-white mt-10 pt-10">
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

            <!-- Tài khoản -->
            <div>
                <h4 class="font-bold mb-2">Tài khoản</h4>
                <a href="#" class="text-sm text-gray-400 hover:text-orange-500 block">Tài khoản của tôi</a>
                <a href="#" class="text-sm text-gray-400 hover:text-orange-500 block">Đăng nhập/Đăng ký</a>
                <a href="#" class="text-sm text-gray-400 hover:text-orange-500 block">Giỏ hàng</a>
                <a href="#" class="text-sm text-gray-400 hover:text-orange-500 block">Danh sách ước</a>
                <a href="#" class="text-sm text-gray-400 hover:text-orange-500 block">Cửa hàng</a>
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
            &copy; Copyright Rimel 2022. All rights reserved.
        </div>
    </footer>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>

</html>
