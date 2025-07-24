<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shopee Kênh Người Bán')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" />
    
    <script src="https://cdn.tiny.cloud/1/qcg1t5tgcxrd5t849fhl74dsm4w81nsyuhwtao66g7e1aw31/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    @vite('resources/css/seller/seller-products.css')
    @vite('resources/js/echo.js')
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

<body class="bg-[#f5f5f7] text-[#222222] text-sm leading-relaxed font-[Inter]" x-data="{ notificationDropdownOpen: false }">
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
            <button aria-label="Notification icon" class="p-1 hover:text-gray-900 transition-colors relative"
                @click="notificationDropdownOpen = !notificationDropdownOpen"
                @click.away="notificationDropdownOpen = false">
                <i class="fas fa-bell text-lg"></i>
            </button>

            <!-- Notification Dropdown -->
            <div x-show="notificationDropdownOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute top-7 right-0 mt-2 w-80 bg-white rounded-md shadow-lg overflow-hidden z-50"
                @click.away="notificationDropdownOpen = false">
                <div class="py-2">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-900">Thông báo</h3>
                    </div>

                    <div class="max-h-96 overflow-y-auto" id="notification-list">
                        @forelse($groupedNotifications as $type => $notifications)
                            <!-- Notification Group -->
                            <div class="notification-type" data-type="{{ $type }}">
                                <div class="px-4 py-2 bg-gray-50">
                                    <h4 class="text-xs font-medium text-gray-500 uppercase">
                                        @switch($type)
                                            @case('order')
                                                Đơn hàng
                                            @break

                                            @case('promotion')
                                                Khuyến mãi
                                            @break

                                            @case('system')
                                                Hệ thống
                                            @break

                                            @default
                                                {{ $type }}
                                        @endswitch
                                    </h4>
                                </div>
                                <div class="notification-items">
                                    @foreach ($notifications as $notification)
                                        <a href="{{ $notification->link ?? '#' }}"
                                            class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100"
                                            data-notification-id="{{ $notification->id }}"
                                            data-notification-title="{{ $notification->title }}"
                                            data-notification-type="{{ $notification->type }}"
                                            data-notification-receiver-type="{{ $notification->receiver_type }}">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <span
                                                        class="inline-block h-2 w-2 rounded-full {{ $notification->read_at ? 'bg-gray-300' : 'bg-red-500' }}"></span>
                                                </div>
                                                <div class="ml-3 w-0 flex-1">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $notification->title }}</p>
                                                    <p class="text-sm text-gray-500">{{ $notification->content }}</p>
                                                    <p class="text-xs text-gray-400 mt-1">
                                                        {{ $notification->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            @empty
                                <div class="px-4 py-3 text-center text-gray-500">
                                    <p>Không có thông báo mới</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <script>
                        window.userId = "{{ session('user_id') }}";
                    </script>
                </div>

                <div class="flex items-center space-x-2 cursor-pointer relative group" id="user-menu-trigger">
                    <img alt="User profile picture" class="w-8 h-8 rounded-full" height="32"
                        src="https://storage.googleapis.com/a1aa/image/0e0e42f3-afbf-4f26-d5b2-574d3f5640d2.jpg"
                        width="32" />
                    <span class="text-xs text-gray-700 select-none">{{ Auth::user()->username ?? 'Tài khoản' }}</span>
                    <i class="fas fa-chevron-down text-xs text-gray-700"></i>
                    <!-- Dropdown menu -->
                    <div class="absolute right-0 top-10 z-50 min-w-[200px] bg-white border border-gray-200 rounded shadow-lg hidden group-hover:block group-focus-within:block"
                        id="user-menu-dropdown">
                        <a href="{{ route('seller.settings') }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100 border-b border-gray-200"><i
                                class="fas fa-cog mr-2"></i>Cài đặt cửa hàng
                        </a>
                        <a href="{{ route('seller.profile') }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100 border-b border-gray-200"><i
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
            <nav class="w-[299px] bg-white border-r border-gray-200 px-4 py-4 text-xs text-gray-500 select-none overflow-y-auto"
                style="max-height: calc(100vh - 40px)">

                <div class="sidebar px-[14px] py-[17px]">
                    <div x-data="{ open: false }" class="">
                        <div @click="open = !open"
                            class="dropdown_admin px-[24px] py-[12px] flex items-center justify-between cursor-pointer {{ request()->routeIs('seller.order.index') ? 'active' : '' }}">
                            <div class="flex items-center gap-2">
                                <svg class="me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16"
                                    height="16">
                                    <path fill="currentColor"
                                        d="m11.349,24H0V3C0,1.346,1.346,0,3,0h12c1.654,0,3,1.346,3,3v5.059c-.329-.036-.662-.059-1-.059s-.671.022-1,.059V3c0-.552-.448-1-1-1H3c-.552,0-1,.448-1,1v19h7.518c.506.756,1.125,1.429,1.831,2Zm0-14h-7.349v2h5.518c.506-.756,1.125-1.429,1.831-2Zm-7.349,7h4c0-.688.084-1.356.231-2h-4.231v2Zm20,0c0,3.859-3.141,7-7,7s-7-3.141-7-7,3.141-7,7-7,7,3.141,7,7Zm-2,0c0-2.757-2.243-5-5-5s-5,2.243-5,5,2.243,5,5,5,5-2.243,5-5ZM14,5H4v2h10v-2Zm5.589,9.692l-3.228,3.175-1.63-1.58-1.393,1.436,1.845,1.788c.314.315.733.489,1.179.489s.865-.174,1.173-.482l3.456-3.399-1.402-1.426Z" />
                                </svg>
                                Quản Lý Đơn
                            </div>
                            <i class="fa-solid fa-chevron-right text-[#64748b] transition-all duration-200"
                                :class="open ? 'rotate-90' : ''"></i>
                        </div>
                        <ul x-show="open" x-transition
                            class="dropdown_products_content flex flex-col gap-2 px-[44px] py-[12px]">
                            <li class="text-sm text-[#64748b]">
                                <a href="{{ route('seller.order.index') }}" class="link_admin">Tất cả</a>
                            </li>
                            <li class="text-sm text-[#64748b]">
                                <a href="#" class="link_admin">Giao Hàng Loạt</a>
                            </li>
                            <li class="text-sm text-[#64748b]">
                                <a href="#" class="link_admin">Bàn Giao Đơn Hàng</a>
                            </li>
                            <li class="text-sm text-[#64748b]">
                                <a href="#" class="link_admin">Đơn Trả hàng/Hoàn tiền hoặc Đơn hủy</a>
                            </li>
                            <li class="text-sm text-[#64748b]">
                                <a href="#" class="link_admin">Cài Đặt Vận Chuyển</a>
                            </li>
                        </ul>
                    </div>

                    <div x-data="{ open: false }" class="">
                        <div @click="open = !open"
                            class="dropdown_admin px-[24px] py-[12px] flex items-center justify-between cursor-pointer {{ request()->routeIs(['seller.products.index', 'seller.products.create']) ? 'active' : '' }}">
                            <div class="flex items-center gap-2">
                                <svg class="me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    width="16" height="16">
                                    <path fill="currentColor"
                                        d="M23.621,6.836l-1.352-2.826c-.349-.73-.99-1.296-1.758-1.552L14.214,.359c-1.428-.476-3-.476-4.428,0L3.49,2.458c-.769,.256-1.41,.823-1.759,1.554L.445,6.719c-.477,.792-.567,1.742-.247,2.609,.309,.84,.964,1.49,1.802,1.796l-.005,6.314c-.002,2.158,1.372,4.066,3.418,4.748l4.365,1.455c.714,.238,1.464,.357,2.214,.357s1.5-.119,2.214-.357l4.369-1.457c2.043-.681,3.417-2.585,3.419-4.739l.005-6.32c.846-.297,1.508-.946,1.819-1.79,.317-.858,.228-1.799-.198-2.499ZM10.419,2.257c1.02-.34,2.143-.34,3.162,0l4.248,1.416-5.822,1.95-5.834-1.95,4.246-1.415ZM2.204,7.666l1.327-2.782c.048,.025,7.057,2.373,7.057,2.373l-1.621,3.258c-.239,.398-.735,.582-1.173,.434l-5.081-1.693c-.297-.099-.53-.325-.639-.619-.109-.294-.078-.616,.129-.97Zm3.841,12.623c-1.228-.409-2.052-1.554-2.051-2.848l.005-5.648,3.162,1.054c1.344,.448,2.792-.087,3.559-1.371l.278-.557-.005,10.981c-.197-.04-.391-.091-.581-.155l-4.366-1.455Zm11.897-.001l-4.37,1.457c-.19,.063-.384,.115-.581,.155l.005-10.995,.319,.64c.556,.928,1.532,1.459,2.561,1.459,.319,0,.643-.051,.96-.157l3.161-1.053-.005,5.651c0,1.292-.826,2.435-2.052,2.844Zm4-11.644c-.105,.285-.331,.504-.619,.6l-5.118,1.706c-.438,.147-.934-.035-1.136-.365l-1.655-3.323s7.006-2.351,7.054-2.377l1.393,2.901c.157,.261,.186,.574,.081,.859Z" />
                                </svg>
                                Quản Lý Sản Phẩm
                            </div>
                            <i class="fa-solid fa-chevron-right text-[#64748b] transition-all duration-200"
                                :class="open ? 'rotate-90' : ''"></i>
                        </div>
                        <ul x-show="open" x-transition
                            class="dropdown_products_content flex flex-col gap-2 px-[44px] py-[12px]">
                            <li class="text-sm text-[#64748b]">
                                <a href="{{ route('seller.products.index') }}" class="link_admin">Tất Cả Sản Phẩm</a>
                            </li>
                            <li class="text-sm text-[#64748b]">
                                <a href="{{ route('seller.products.create') }}" class="link_admin">Thêm Sản Phẩm</a>
                            </li>
                        </ul>
                    </div>

                    <div x-data="{ open: false }" class="">
                        <div @click="open = !open"
                            class="dropdown_admin px-[24px] py-[12px] flex items-center justify-between cursor-pointer {{ request()->routeIs('seller.combo.index') ? 'active' : '' }}">
                            <div class="flex items-center gap-2">
                                <svg class="me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    width="16" height="16">
                                    <path fill="currentColor"
                                        d="M16,0h-.13a2.02,2.02,0,0,0-1.941,1.532,2,2,0,0,1-3.858,0A2.02,2.02,0,0,0,8.13,0H8A5.006,5.006,0,0,0,3,5V21a3,3,0,0,0,3,3H8.13a2.02,2.02,0,0,0,1.941-1.532,2,2,0,0,1,3.858,0A2.02,2.02,0,0,0,15.87,24H18a3,3,0,0,0,3-3V5A5.006,5.006,0,0,0,16,0Zm2,22-2.143-.063A4,4,0,0,0,8.13,22H6a1,1,0,0,1-1-1V17H7a1,1,0,0,0,0-2H5V5A3,3,0,0,1,8,2l.143.063A4.01,4.01,0,0,0,12,5a4.071,4.071,0,0,0,3.893-3H16a3,3,0,0,1,3,3V15H17a1,1,0,0,0,0,2h2v4A1,1,0,0,1,18,22Z" />
                                    <path fill="currentColor" d="M13,15H11a1,1,0,0,0,0,2h2a1,1,0,0,0,0-2Z" />
                                </svg>
                                Kênh Marketing
                            </div>
                            <i class="fa-solid fa-chevron-right text-[#64748b] transition-all duration-200"
                                :class="open ? 'rotate-90' : ''"></i>
                        </div>
                        <ul x-show="open" x-transition
                            class="dropdown_products_content flex flex-col gap-2 px-[44px] py-[12px]">
                            <li class="text-sm text-[#64748b]">
                                <a href="{{ route('seller.combo.index') }}" class="link_admin">Tạo combo</a>
                            </li>
                            <li class="text-sm text-[#64748b]">
                                <a href="#" class="link_admin">Kênh Marketing</a>
                            </li>
                            <li class="text-sm text-[#64748b]">
                                <a href="#" class="link_admin">Đấu Giá Rẻ Vô Địch</a>
                            </li>
                            <li class="text-sm text-[#64748b]">
                                <a href="#" class="link_admin">Quảng Cáo Shopee</a>
                            </li>
                            <li class="text-sm text-[#64748b]">
                                <a href="#" class="link_admin">Tăng Đơn Cùng KOL</a>
                            </li>
                            <li class="text-sm text-[#64748b]">
                                <a href="#" class="link_admin">Live & Video</a>
                            </li>
                            <li class="text-sm text-[#64748b]">
                                <a href="#" class="link_admin">Khuyến Mãi của Shop</a>
                            </li>
                            <li class="text-sm text-[#64748b]">
                                <a href="#" class="link_admin">Flash Sale Của Shop</a>
                            </li>
                            <li class="text-sm text-[#64748b]">
                                <a href="#" class="link_admin">Mã Giảm Giá Của Shop</a>
                            </li>
                            <li class="text-sm text-[#64748b]">
                                <a href="#" class="link_admin">Chương Trình Shopee</a>
                            </li>
                        </ul>
                    </div>

                    <div x-data="{ open: false }" class="">
                        <div @click="open = !open"
                            class="dropdown_admin px-[24px] py-[12px] flex items-center justify-between cursor-pointer">
                            <div class="flex items-center gap-2">
                                <svg class="me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    width="16" height="16">
                                    <path fill="currentColor"
                                        d="M12.01,23.67c-.48,0-.96-.17-1.34-.51l-3.75-3.16h-2.92c-2.21,0-4-1.79-4-4V4C0,1.79,1.79,0,4,0H20c2.21,0,4,1.79,4,4v12c0,2.21-1.79,4-4,4h-2.85l-3.85,3.18c-.36,.32-.83,.49-1.29,.49ZM4,2c-1.1,0-2,.9-2,2v12c0,1.1,.9,2,2,2h3.29c.24,0,.46,.08,.64,.24l4.05,3.41,4.17-3.42c.18-.15,.4-.23,.64-.23h3.21c1.1,0,2-.9,2-2V4c0-1.1-.9-2-2-2H4Zm5.01,13.35c-.19,0-.39-.06-.55-.18-.31-.23-.44-.64-.32-1.01l.86-2.76-2.18-1.77c-.29-.25-.4-.65-.27-1.01,.13-.36,.48-.6,.86-.6h2.75l.97-2.61c.13-.36,.48-.6,.86-.6s.73,.24,.86,.6l.97,2.61h2.75c.38,0,.73,.24,.86,.6s.02,.77-.27,1.02l-2.17,1.77,.9,2.73c.12,.37,0,.78-.31,1.01-.31,.24-.73,.25-1.06,.04l-2.52-1.64-2.48,1.66c-.15,.1-.33,.15-.51,.15Z" />
                                </svg>
                                Chăm sóc khách hàng
                            </div>
                            <i class="fa-solid fa-chevron-right text-[#64748b] transition-all duration-200"
                                :class="open ? 'rotate-90' : ''"></i>
                        </div>
                        <ul x-show="open" x-transition
                            class="dropdown_products_content flex flex-col gap-2 px-[44px] py-[12px]">
                            <li class="text-sm text-[#64748b]">
                                <a href="#" class="link_admin">Quản lý Chat</a>
                            </li>
                            <li class="text-sm text-[#64748b]">
                                <a href="#" class="link_admin">Quản lý Đánh Giá</a>
                            </li>
                        </ul>
                    </div>

                    <div x-data="{ open: false }" class="">
                        <div @click="open = !open"
                            class="dropdown_admin px-[24px] py-[12px] flex items-center justify-between cursor-pointer">
                            <div class="flex items-center gap-2">
                                <svg class="me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    width="16" height="16">
                                    <path fill="currentColor"
                                        d="M22 6H2V2h20v4zM22 8H2v4h20V8zM22 14H2v4h20v-4zM22 20H2v2h20v-2z" />
                                </svg>
                                Tài Chính
                            </div>
                            <i class="fa-solid fa-chevron-right text-[#64748b] transition-all duration-200"
                                :class="open ? 'rotate-90' : ''"></i>
                        </div>
                        <ul x-show="open" x-transition
                            class="dropdown_products_content flex flex-col gap-2 px-[44px] py-[12px]">
                            <li class="text-sm text-[#64748b]">
                                <a href="#" class="link_admin">Doanh Thu</a>
                            </li>
                        </ul>
                        <ul x-show="open" x-transition
                            class="dropdown_products_content flex flex-col gap-2 px-[44px] py-[12px]">
                            <li class="text-sm text-[#64748b]">
                                <a href="{{ route('wallet.index') }}" class="link_admin">Ví Tài khoản Shop</a>
                            </li>
                        </ul>
                    </div>
                </div>
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
        <script>
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
        <script>
            window.Laravel = {
                user: @json(Auth::user()),
                shop: @json(Auth::user()->shop->id),
            };
        </script>
        @vite('resources/js/echo.js')
    </body>

    </html>
