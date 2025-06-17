<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Shopee Kênh Người Bán')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" />
    <!-- Quill CSS -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <!-- Quill JS -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <link rel="stylesheet" href="{{ asset('css/seller/seller-home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/seller/seller-products.css') }}">
    @stack('styles')
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 3px;
        }
    </style>
</head>

<body class="bg-[#f5f5f7] text-[#222222] text-sm leading-relaxed font-[Inter]">
    <header class="flex items-center justify-between px-4 py-2 border-b border-gray-200 bg-white sticky top-0 z-30">
        <div class="flex items-center space-x-1">
            <img alt="Logo orange square with white S letter" class="w-6 h-6" height="24"
                src="https://storage.googleapis.com/a1aa/image/0e0e42f3-afbf-4f26-d5b2-574d3f5640d2.jpg"
                width="24" />
            <span class="text-orange-500 font-semibold text-base">...</span>
            <span class="font-semibold text-base">Kênh Người Bán</span>
        </div>
        <div class="flex items-center space-x-6 text-gray-600">
            <button aria-label="Grid menu" class="p-1 hover:text-gray-900 transition-colors">
                <i class="fas fa-th-large text-lg"></i>
            </button>
            <button aria-label="Book icon" class="p-1 hover:text-gray-900 transition-colors">
                <i class="fas fa-book-open text-lg"></i>
            </button>
            <div class="flex items-center space-x-2 cursor-pointer relative group" id="user-menu-trigger">
                <img alt="User profile picture" class="w-8 h-8 rounded-full" height="32"
                    src="https://storage.googleapis.com/a1aa/image/0e0e42f3-afbf-4f26-d5b2-574d3f5640d2.jpg"
                    width="32" />
                <span class="text-xs text-gray-700 select-none">{{ Auth::user()->username ?? 'Tài khoản' }}</span>
                <i class="fas fa-chevron-down text-xs text-gray-700"></i>
                <!-- Dropdown menu -->
                <div class="absolute right-0 top-10 z-50 min-w-[200px] bg-white border border-gray-200 rounded shadow-lg hidden group-hover:block group-focus-within:block"
                    id="user-menu-dropdown">
                    <a href="{{ route('seller.settings') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 border-b border-gray-200"><i
                            class="fas fa-cog mr-2"></i>Cài đặt cửa hàng
                    </a>
                    <a href="{{ route('seller.profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 border-b border-gray-200"><i
                            class="fas fa-user mr-2"></i>Thông tin cá nhân
                    </a>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100"><i
                            class="fas fa-sign-out-alt mr-2"></i>Đăng xuất
                    </a>
                    <form id="logout-form-header" action="{{ route('logout') }}" method="POST" class="hidden">@csrf
                    </form>
                </div>
            </div>
        </div>
    </header>
    <div class="flex">
        <nav class="w-56 bg-white border-r border-gray-200 px-4 py-4 text-xs text-gray-500 select-none overflow-y-auto"
            style="max-height: calc(100vh - 40px)">
            <ul class="space-y-4">
                <li>
                    <button aria-expanded="true" class="flex flex-col items-start w-full font-semibold text-gray-400"
                        type="button">
                        Quản Lý Đơn
                        <span class="text-gray-600 font-normal mt-0.5 ml-1">Hàng</span>
                        <i class="fas fa-chevron-down ml-auto mt-1 text-xs"></i>
                    </button>
                    <ul class="mt-2 space-y-1 pl-3 text-gray-700 font-normal">
                        <li><a class="block hover:text-orange-500" href="{{ route('seller.order.index') }}">Tất cả</a>
                        </li>
                        <li><a class="block hover:text-orange-500" href="#">Giao Hàng Loạt</a></li>
                        <li><a class="block hover:text-orange-500" href="#">Bàn Giao Đơn Hàng</a></li>
                        <li><a class="block hover:text-orange-500" href="#">Đơn Trả hàng/Hoàn tiền hoặc Đơn
                                hủy</a></li>
                        <li><a class="block hover:text-orange-500" href="#">Cài Đặt Vận Chuyển</a></li>
                    </ul>
                </li>
                <li>
                    <button aria-expanded="true" class="flex flex-col items-start w-full font-semibold text-gray-400"
                        type="button">
                        Quản Lý Sản
                        <span class="text-gray-600 font-normal mt-0.5 ml-1">Phẩm</span>
                        <i class="fas fa-chevron-down ml-auto mt-1 text-xs"></i>
                    </button>
                    <ul class="mt-2 space-y-1 pl-3 text-gray-700 font-normal">
                        <li><a class="block hover:text-orange-500" href="{{ route('seller.products.index') }}">Tất Cả
                                Sản Phẩm</a></li>
                        <li><a class="block hover:text-orange-500" href="{{ route('seller.products.create') }}">Thêm Sản
                                Phẩm</a></li>
                    </ul>
                </li>
                <li>
                    <button aria-expanded="true" class="flex flex-col items-start w-full font-semibold text-gray-400"
                        type="button">
                        Kênh Marketing
                        <i class="fas fa-chevron-down ml-auto mt-1 text-xs"></i>
                    </button>
                    <ul class="mt-2 space-y-1 pl-3 text-gray-700 font-normal">
                        <li><a class="block hover:text-orange-500" href="#">Kênh Marketing</a></li>
                        <li><a class="block hover:text-orange-500" href="#">Đấu Giá Rẻ Vô Địch</a></li>
                        <li><a class="block hover:text-orange-500" href="#">Quảng Cáo Shopee</a></li>
                        <li><a class="block hover:text-orange-500" href="#">Tăng Đơn Cùng KOL</a></li>
                        <li><a class="block hover:text-orange-500" href="#">Live & Video</a></li>
                        <li><a class="block hover:text-orange-500" href="#">Khuyến Mãi của Shop</a></li>
                        <li><a class="block hover:text-orange-500" href="#">Flash Sale Của Shop</a></li>
                        <li><a class="block hover:text-orange-500" href="#">Mã Giảm Giá Của Shop</a></li>
                        <li><a class="block hover:text-orange-500" href="#">Chương Trình Shopee</a></li>
                    </ul>
                </li>
                <li>
                    <button aria-expanded="true" class="flex flex-col items-start w-full font-semibold text-gray-400"
                        type="button">
                        Chăm sóc khách
                        <span class="text-gray-600 font-normal mt-0.5 ml-1">hàng</span>
                        <i class="fas fa-chevron-down ml-auto mt-1 text-xs"></i>
                    </button>
                    <ul class="mt-2 space-y-1 pl-3 text-gray-700 font-normal">
                        <li><a class="block hover:text-orange-500" href="#">Quản lý Chat</a></li>
                        <li><a class="block hover:text-orange-500" href="#">Quản lý Đánh Giá</a></li>
                    </ul>
                </li>
                <li>
                    <button aria-expanded="true" class="flex flex-col items-start w-full font-semibold text-gray-400"
                        type="button">
                        Tài Chính
                        <i class="fas fa-chevron-down ml-auto mt-1 text-xs"></i>
                    </button>
                    <ul class="mt-2 space-y-1 pl-3 text-gray-700 font-normal">
                        <li><a class="block hover:text-orange-500" href="#">Doanh Thu</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <main class="flex-1 min-h-screen px-4 py-4 mx-auto">
            @yield('content')
        </main>
    </div>
    <footer class="bg-[#111] text-white pt-10 mt-10">
        <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
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
                    <form id="logout-form-footer" action="{{ route('logout') }}" method="POST" class="hidden">@csrf
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
            <div>
                <h4 class="font-bold mb-2">Liên kết nhanh</h4>
                <a href="#" class="text-sm text-gray-400 hover:text-orange-500 block">Chính sách bảo mật</a>
                <a href="#" class="text-sm text-gray-400 hover:text-orange-500 block">Điều khoản sử dụng</a>
                <a href="#" class="text-sm text-gray-400 hover:text-orange-500 block">Câu hỏi thường gặp</a>
                <a href="#" class="text-sm text-gray-400 hover:text-orange-500 block">Liên hệ</a>
            </div>
            <div>
                <h4 class="font-bold mb-2">Tải App</h4>
                <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                    class="w-28 mb-2" />
                <img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg"
                    class="w-28" />
                <p class="text-xs text-gray-400 mt-2">Tiết kiệm 5.3 ứng dụng dành cho người dùng mới</p>
            </div>
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
    <script src="{{ asset('js/seller/product.js') }}"></script>
    <script>
        // Hiển thị dropdown khi hover hoặc focus vào avatar
        // Dropdown menu user: giữ menu khi hover hoặc rê chuột xuống menu, không bị mất khi di chuyển chuột
        document.addEventListener('DOMContentLoaded', function() {
            const trigger = document.getElementById('user-menu-trigger');
            const dropdown = document.getElementById('user-menu-dropdown');
            if (trigger && dropdown) {
                let inside = false;
                trigger.addEventListener('mouseenter', () => {
                    dropdown.style.display = 'block';
                });
                trigger.addEventListener('mouseleave', () => {
                    setTimeout(() => {
                        if (!inside) dropdown.style.display = 'none';
                    }, 100);
                });
                dropdown.addEventListener('mouseenter', () => {
                    inside = true;
                    dropdown.style.display = 'block';
                });
                dropdown.addEventListener('mouseleave', () => {
                    inside = false;
                    dropdown.style.display = 'none';
                });
            }
        });
    </script>
</body>

</html>
