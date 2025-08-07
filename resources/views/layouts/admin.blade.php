<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <!-- Quill CSS -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <!-- Quill JS -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/qcg1t5tgcxrd5t849fhl74dsm4w81nsyuhwtao66g7e1aw31/tinymce/8/tinymce.min.js"
        referrerpolicy="origin" crossorigin="anonymous"></script>
    <!-- Compressor.js -->
    <script src="https://unpkg.com/compressorjs@1.2.1/dist/compressor.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/slugify@1.6.5/slugify.js"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <!-- Summernote CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&amp;family=Montserrat:ital,wght@0,700;1,700&amp;display=swap"
        rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/admin.css')
    @vite('resources/css/admin/product.css')
    @stack('styles')
    @vite('resources/css/admin.css')
    @vite('resources/css/admin/product.css')
    @stack('styles')
    @yield('head')
</head>

<body class="bg-gray-50">
    <div x-data="{ sidebarOpen: true, mobileSidebarOpen: false, mobileCollapsed: false }" class="flex bg-gray-50">
        <!-- Mobile sidebar overlay -->
        <div x-show="mobileSidebarOpen" x-transition:enter="transition-opacity duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
            @click="mobileSidebarOpen = false">
        </div>

        <!-- Sidebar -->
        <div x-show="sidebarOpen || mobileSidebarOpen" x-transition:enter="transition duration-300 ease-in-out"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition duration-300 ease-in-out" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-50 bg-white shadow-lg lg:static lg:translate-x-0 transition-all duration-300"
            :class="{
                'w-64': (sidebarOpen && !mobileCollapsed) || (!sidebarOpen && mobileSidebarOpen && !mobileCollapsed),
                'w-16': (!sidebarOpen && !mobileSidebarOpen) || (mobileCollapsed && mobileSidebarOpen),
                'lg:w-64': sidebarOpen && !mobileCollapsed,
                'lg:w-16': !sidebarOpen && !mobileCollapsed
            }"
            @click.away="mobileSidebarOpen = false">

            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shopping-bag text-white text-sm"></i>
                    </div>
                    <span x-show="(sidebarOpen && !mobileCollapsed) || (mobileSidebarOpen && !mobileCollapsed)"
                        x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="transition duration-200"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        class="text-lg font-bold text-gray-900">eBazer</span>
                </div>
                <div class="flex items-center space-x-2">
                    <!-- Mobile collapse button -->
                    <button x-show="mobileSidebarOpen" @click="mobileCollapsed = !mobileCollapsed"
                        class="lg:hidden text-gray-400 hover:text-gray-600 transition-colors duration-200 p-1 rounded-md hover:bg-gray-100">
                        <svg class="w-4 h-4 transition-transform duration-200"
                            :class="{ 'rotate-180': mobileCollapsed }" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>
                    <!-- Desktop toggle button -->
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="hidden lg:block text-gray-400 hover:text-gray-600 transition-colors duration-200 p-1 rounded-md hover:bg-gray-100">
                        <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': !sidebarOpen }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Sidebar Content -->
            <div class="flex-1 overflow-y-auto">
                <nav class="px-4 py-6 space-y-2">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-orange-50 text-orange-700 border-r-2 border-orange-500' : 'text-gray-700 hover:bg-gray-100' }}"
                        :title="mobileCollapsed ? 'Bảng điều khiển' : ''">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                        </svg>
                        <span x-show="(sidebarOpen && !mobileCollapsed) || (mobileSidebarOpen && !mobileCollapsed)"
                            x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100" x-transition:leave="transition duration-200"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Bảng điều
                            khiển</span>
                    </a>

                    <!-- Notifications -->
                    <a href="{{ route('admin.notifications.index') }}"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.notifications.index') ? 'bg-orange-50 text-orange-700 border-r-2 border-orange-500' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span x-show="(sidebarOpen && !mobileCollapsed) || (mobileSidebarOpen && !mobileCollapsed)"
                            x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100" x-transition:leave="transition duration-200"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Thông báo</span>
                    </a>

                    <!-- Products Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span
                                    x-show="(sidebarOpen && !mobileCollapsed) || (mobileSidebarOpen && !mobileCollapsed)"
                                    x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100" x-transition:leave="transition duration-200"
                                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Sản
                                    phẩm</span>
                            </div>
                            <svg x-show="(sidebarOpen && !mobileCollapsed) || (mobileSidebarOpen && !mobileCollapsed)"
                                x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100" x-transition:leave="transition duration-200"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition duration-200"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0" class="ml-8 mt-1 space-y-1">
                            <a href="{{ route('admin.products.index') }}"
                                class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-200">Danh
                                sách sản phẩm</a>
                            <a href="{{ route('admin.attributes.index') }}"
                                class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-200">Thuộc
                                tính sản phẩm</a>
                        </div>
                    </div>

                    <!-- Categories -->
                    <a href="{{ route('admin.categories.index') }}"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.categories.index') ? 'bg-orange-50 text-orange-700 border-r-2 border-orange-500' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <span x-show="(sidebarOpen && !mobileCollapsed) || (mobileSidebarOpen && !mobileCollapsed)"
                            x-transition>Danh mục</span>
                    </a>

                    <!-- Logo -->
                    <a href="{{ route('logo.index') }}"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('logo.index') ? 'bg-orange-50 text-orange-700 border-r-2 border-orange-500' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span x-show="(sidebarOpen && !mobileCollapsed) || (mobileSidebarOpen && !mobileCollapsed)"
                            x-transition>Logo</span>
                    </a>

                    <!-- Orders -->
                    <a href="{{ route('admin.orders.index') }}"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.orders.index') ? 'bg-orange-50 text-orange-700 border-r-2 border-orange-500' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span x-show="(sidebarOpen && !mobileCollapsed) || (mobileSidebarOpen && !mobileCollapsed)"
                            x-transition>Đơn hàng</span>
                    </a>

                    <!-- Reviews -->
                    <a href="{{ route('admin.reviews.index') }}"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.reviews.index') ? 'bg-orange-50 text-orange-700 border-r-2 border-orange-500' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                        </svg>
                        <span x-show="(sidebarOpen && !mobileCollapsed) || (mobileSidebarOpen && !mobileCollapsed)"
                            x-transition>Đánh giá</span>
                    </a>

                    <!-- Reports -->
                    <a href="{{ route('admin.reports.index') }}"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.reports.index') ? 'bg-orange-50 text-orange-700 border-r-2 border-orange-500' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        <span x-show="(sidebarOpen && !mobileCollapsed) || (mobileSidebarOpen && !mobileCollapsed)"
                            x-transition>Báo cáo</span>
                    </a>

                    <!-- Coupons -->
                    <a href="{{ route('admin.coupon.index') }}"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.coupon.index') ? 'bg-orange-50 text-orange-700 border-r-2 border-orange-500' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                            </path>
                        </svg>
                        <span x-show="(sidebarOpen && !mobileCollapsed) || (mobileSidebarOpen && !mobileCollapsed)"
                            x-transition>Mã giảm giá</span>
                    </a>

                    <!-- Users -->
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.users.index') ? 'bg-orange-50 text-orange-700 border-r-2 border-orange-500' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                        <span x-show="(sidebarOpen && !mobileCollapsed) || (mobileSidebarOpen && !mobileCollapsed)"
                            x-transition>Người dùng</span>
                    </a>

                    <!-- Shop Approval -->
                    <a href="{{ route('admin.shops.pending') }}"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.shops.pending') ? 'bg-orange-50 text-orange-700 border-r-2 border-orange-500' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        <span x-show="(sidebarOpen && !mobileCollapsed) || (mobileSidebarOpen && !mobileCollapsed)"
                            x-transition>Duyệt cửa hàng</span>
                    </a>

                    <!-- Profile -->
                    <a href="#"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 text-gray-700 hover:bg-gray-100">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span x-show="(sidebarOpen && !mobileCollapsed) || (mobileSidebarOpen && !mobileCollapsed)"
                            x-transition>Hồ sơ</span>
                    </a>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 my-4"></div>

                    <!-- Content Management -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                    </path>
                                </svg>
                                <span
                                    x-show="(sidebarOpen && !mobileCollapsed) || (mobileSidebarOpen && !mobileCollapsed)"
                                    x-transition>Quản lý nội dung</span>
                            </div>
                            <svg x-show="(sidebarOpen && !mobileCollapsed) || (mobileSidebarOpen && !mobileCollapsed)"
                                x-transition class="w-4 h-4 transition-transform duration-200"
                                :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95" class="ml-8 mt-1 space-y-1">
                            <a href="{{ route('post.index') }}"
                                class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-200">Bài
                                viết</a>
                            <a href="{{ route('post-categories.index') }}"
                                class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-200">Loại
                                bài viết</a>
                            <a href="{{ route('post-tags.index') }}"
                                class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-200">Thẻ
                                bài viết</a>
                            <a href="#"
                                class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-200">Bình
                                luận</a>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span
                                    x-show="(sidebarOpen && !mobileCollapsed) || (mobileSidebarOpen && !mobileCollapsed)"
                                    x-transition>Cài đặt</span>
                            </div>
                            <svg x-show="(sidebarOpen && !mobileCollapsed) || (mobileSidebarOpen && !mobileCollapsed)"
                                x-transition class="w-4 h-4 transition-transform duration-200"
                                :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95" class="ml-8 mt-1 space-y-1">
                            <a href="{{ route('admin.settings.index') }}"
                                class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-200">Cài
                                đặt cửa hàng</a>
                            <a href="{{ route('help-category.index') }}"
                                class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-200">Chính
                                sách</a>
                            <a href="{{ route('help-article.index') }}"
                                class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-200">Chính
                                sách bài viết</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <!-- Mobile menu button -->
                    <button @click="mobileSidebarOpen = true"
                        class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Desktop sidebar toggle -->
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="hidden lg:block p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition-colors duration-200">
                        <svg class="w-6 h-6 transition-transform duration-200" :class="{ 'rotate-180': !sidebarOpen }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Search -->
                    <div class="flex-1 max-w-lg mx-4">
                        <div class="relative">
                            <input type="text" placeholder="Tìm kiếm..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Right side -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button
                            class="relative p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-5 5v-5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- User menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                <img src="{{ asset('images/avatar.png') }}" alt="Avatar"
                                    class="w-8 h-8 rounded-full">
                                <span
                                    class="hidden md:block text-sm font-medium text-gray-700">{{ Auth::user()->fullname }}</span>
                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100" x-transition:leave="transition duration-200"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">Cài
                                    đặt</a>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">Đăng
                                    xuất</a>
                                <form id="logout-form-header" action="{{ route('logout') }}" method="POST"
                                    class="hidden">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content area -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    @yield('scripts')
    @stack('scripts')
    @vite('resources/js/admin/admin.js')
    @vite('resources/js/admin/order.js')
    @vite('resources/js/admin/category.js')
    @vite('resources/js/admin/create-product.js')
</body>

</html>
