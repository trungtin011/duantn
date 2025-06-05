<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <!-- Quill CSS -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <!-- Quill JS -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <!-- Compressor.js -->
    <script src="https://unpkg.com/compressorjs@1.2.1/dist/compressor.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/slugify@1.6.5/slugify.js"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&amp;family=Montserrat:ital,wght@0,700;1,700&amp;display=swap"
        rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
    @yield('head')
</head>

<body>
    <div class="d-flex">
        <div class="sidebar p-4">
            <div class="logo mb-4"><i class="fa-solid fa-bag-shopping me-2"></i>eBazer</div>
            <a href="{{ route('admin.dashboard') }}"
                class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }} link_admin"><i
                    class="fa-solid fa-chart-line"></i> Dashboard
            </a>

            <div x-data="{ open: false }" class="">
                <!-- Nút dropdown -->
                <div @click="open = !open"
                    class="dropdown_admin px-[24px] py-[12px] flex items-center justify-between cursor-pointer {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                    <div class="flex items-center">
                        <i class="fa-solid fa-box me-2 text-[#64748b]"></i>
                        Products
                    </div>
                    {{-- Arrow right --}}
                    <i class="fa-solid fa-chevron-right text-[#64748b] transition-all duration-200" :class="open ? 'rotate-90' : ''"></i>
                </div>

                <!-- Nội dung dropdown -->
                <ul x-show="open" x-transition
                    class="dropdown_products_content flex flex-col gap-2 px-[44px] py-[12px]">
                    <li class="text-sm text-[#64748b] flex items-center gap-2">
                        <span class="text-sm text-[#64748b]">•</span>
                        <a href="{{ route('admin.products.index') }}">Products</a>
                    </li>
                    <li class="text-sm text-[#64748b] flex items-center gap-2">
                        <span class="text-sm text-[#64748b]">•</span>
                        <a href="{{ route('admin.products.attributes.index') }}">Attributes</a>
                    </li>
                </ul>
            </div>

            <a href="{{ route('admin.categories.index') }}"
                class="{{ request()->routeIs('admin.categories.index') ? 'active' : '' }} link_admin"><i
                    class="fa-solid fa-list"></i> Categories</a>
            <a href="{{ route('admin.orders.index') }}"
                class="{{ request()->routeIs('admin.orders.index') ? 'active' : '' }} link_admin"><i
                    class="fa-solid fa-cart-shopping"></i> Orders</a>
            <a href="{{ route('admin.reviews.index') }}"
                class="{{ request()->routeIs('admin.reviews.index') ? 'active' : '' }} link_admin"><i
                    class="fa-solid fa-star"></i> Reviews</a>
            <a href="#" class="link_admin"><i class="fa-solid fa-ticket"></i> Coupons</a>
            <a href="#" class="link_admin"><i class="fa-solid fa-user"></i> Profile</a>
            <a href="{{ route('admin.settings.index') }}"
                class="{{ request()->routeIs('admin.settings.index') ? 'active' : '' }} link_admin"><i
                    class="fa-solid fa-gear"></i> Shop Settings</a>
            <a href="#" class="link_admin"><i class="fa-solid fa-file"></i> Pages</a>
        </div>
        <div class="flex-grow-1">
            <div class="header">
                <form class="search-box flex items-center relative">
                    <input class="form-control w-full" placeholder="Search..." />
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 absolute right-3 top-1/2 -translate-y-1/2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>

                </form>
                <div class="right">
                    <div class="relative">
                        <!-- Nút thông báo -->
                        <div
                            class="border border-[#F2F2F6] rounded-[4px] w-[38px] h-[38px] flex items-center justify-center">
                            <button id="notification-btn" class="w-full h-full flex items-center justify-center">
                                <i class="fa-regular fa-bell fa-lg text-secondary"></i>
                            </button>
                            <span id="notification-count"
                                class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full text-xs w-4 h-4 flex items-center justify-center">
                                9+
                            </span>
                        </div>
                        <!-- Dropdown thông báo -->
                        <div id="notification-dropdown"
                            class="absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg hidden z-10">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-800">Notifications</h3>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                <div class="p-4 hover:bg-gray-100 cursor-pointer border-b border-gray-100">
                                    <p class="text-sm text-gray-700">New order received!</p>
                                    <p class="text-xs text-gray-500">5 minutes ago</p>
                                </div>
                                <div class="p-4 hover:bg-gray-100 cursor-pointer border-b border-gray-100">
                                    <p class="text-sm text-gray-700">Product stock updated.</p>
                                    <p class="text-xs text-gray-500">10 minutes ago</p>
                                </div>
                                <div class="p-4 hover:bg-gray-100 cursor-pointer">
                                    <p class="text-sm text-gray-700">User registered.</p>
                                    <p class="text-xs text-gray-500">1 hour ago</p>
                                </div>
                            </div>
                            <div class="p-4 border-t border-gray-200 text-center">
                                <a href="#" class="text-sm text-blue-600 hover:underline">View all
                                    notifications</a>
                            </div>
                        </div>
                    </div>
                    <img src="https://i.pravatar.cc/38" class="avatar" alt="avatar">
                </div>
            </div>
            <main>
                @yield('content')
            </main>
        </div>
    </div>

    @yield('scripts')
    <script src="{{ asset('js/admin/product.js') }}"></script>
    <script src="{{ asset('js/admin/variant.js') }}"></script>
    <script src="{{ asset('js/admin/admin.js') }}"></script>
</body>

</html>
