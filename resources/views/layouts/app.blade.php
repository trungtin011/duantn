<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png" />
    <title>@yield('title', 'Default Title')</title>
    
    <!-- Font + Tailwind + Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    @vite('resources/css/user/home.css')
    @vite('resources/css/user/orderDetail.css')
    @vite('resources/css/user/notifications.css')
    @stack('styles')
    @vite('resources/js/echo.js')
    <style>
    .floating-support-icons {
        position: fixed;
        top: 50%;
        right: 36px;
        transform: translateY(-50%);
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 20px;
        transition: opacity 0.2s, transform 0.2s;
    }
    .floating-support-icons.collapsed {
        opacity: 0;
        pointer-events: none;
        transform: translateY(-50%) scale(0.7);
    }
    .support-icon {
        width: 56px;
        height: 56px;
        background: #fff;
        border-radius: 50%;
        box-shadow: 0 4px 16px rgba(0,0,0,0.13);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: box-shadow 0.22s, transform 0.18s, background 0.18s;
        font-size: 30px;
        color: #1976D2;
        border: 2.5px solid #f3f3f3;
        cursor: pointer;
    }
    .support-icon img {
        width: 32px;
        height: 32px;
        object-fit: contain;
    }
    .support-icon.zalo { border-color: #008fe5; }
    .support-icon.messenger { border-color: #0084ff; }
    .support-icon.phone { border-color: #43a047; color: #43a047; }
    .support-icon.email { border-color: #fbc02d; color: #fbc02d; }
    .support-icon.chat { border-color: #ef3248; color: #ef3248; }
    .support-icon:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.18);
        transform: scale(1.12) translateY(-2px);
        background: #f0f4ff;
        border-width: 3px;
    }
    .floating-support-toggle {
        position: fixed;
        top: 50%;
        right: 36px;
        transform: translateY(-50%);
        z-index: 10000;
        width: 44px;
        height: 44px;
        background: #fff;
        border-radius: 50%;
        box-shadow: 0 2px 8px rgba(0,0,0,0.13);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2px solid #ef3248;
        color: #ef3248;
        transition: background 0.2s, color 0.2s;
    }
    .floating-support-toggle:hover {
        background: #ef3248;
        color: #fff;
    }
    .floating-support-toggle ion-icon {
        font-size: 28px;
        transition: transform 0.2s;
    }
    .floating-support-icons.collapsed {
        opacity: 0;
        pointer-events: none;
        transform: translateY(-50%) scale(0.7);
        transition: opacity 0.2s, transform 0.2s;
    }
    .floating-support-icons {
        transition: opacity 0.2s, transform 0.2s;
    }
    /* Loader Spinner */
    .loader {
      width: 50px;
      aspect-ratio: 1;
      border-radius: 50%;
      border: 8px solid #0000;
      border-right-color: #ffa50097;
      position: relative;
      animation: l24 1s infinite linear;
    }
    .loader:before,
    .loader:after {
      content: "";
      position: absolute;
      inset: -8px;
      border-radius: 50%;
      border: inherit;
      animation: inherit;
      animation-duration: 2s;
    }
    .loader:after {
      animation-duration: 2s;
    }
    #global-loader {
      transition: opacity 0.5s ease;
      opacity: 1;
    }
    @keyframes l24 {
      100% {transform: rotate(1turn)}
    }
    </style>
</head>

@auth
    <script>
        window.addEventListener('beforeunload', function() {
            navigator.sendBeacon('/update-session', JSON.stringify({
                user_id: "{{ Auth::id() }}"
            }));
        });
    </script>
@endauth

<body class="font-[Inter]">
    <!-- Loader Fullscreen -->
    <div id="global-loader" style="position:fixed;z-index:99999;inset:0;display:flex;align-items:center;justify-content:center;background:#fff;">
        <div class="loader"></div>
    </div>
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
                        @auth
                            @php
                                $unreadCount = 0;
                                if (isset($groupedNotifications)) {
                                    foreach ($groupedNotifications as $type => $notifications) {
                                        $unreadCount += $notifications->where('status', 'unread')->count();
                                    }
                                }
                            @endphp
                            @if ($unreadCount > 0)
                                <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 min-w-[20px] text-center">
                                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                </span>
                            @endif
                        @endauth
                    </div>
                    <div
                        class="absolute dropdown-notification-content z-10 right-0 bg-white w-[400px] max-h-[500px] overflow-y-auto shadow-lg rounded-lg border hidden">
                        <!-- Thêm phần nhô lên -->
                        <div class="absolute top-[-15px] right-10 transform w-5 h-5 bg-white clip-triangle">
                        </div>

                        @auth
                            @if (isset($groupedNotifications) && $groupedNotifications->count() > 0)
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="text-sm font-semibold text-gray-700">Thông báo mới</span>
                                        <a href="#" class="text-xs text-blue-600 hover:text-blue-800">Xem tất cả</a>
                                    </div>

                                    @foreach ($groupedNotifications as $type => $notifications)
                                        <!-- Notification Type Header -->
                                        <div class="mb-3">
                                            <div class="flex items-center gap-2 mb-2">
                                                <div
                                                    class="w-2 h-2 rounded-full 
                                                    @switch($type)
                                                        @case('order')
                                                            bg-blue-500
                                                            @break
                                                        @case('promotion')
                                                            bg-green-500
                                                            @break
                                                        @case('system')
                                                            bg-purple-500
                                                            @break
                                                        @case('security')
                                                            bg-red-500
                                                            @break
                                                        @default
                                                            bg-gray-500
                                                    @endswitch">
                                                </div>
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

                                                        @case('security')
                                                            Bảo mật
                                                        @break

                                                        @default
                                                            {{ ucfirst($type) }}
                                                    @endswitch
                                                </h4>
                                                <span class="text-xs text-gray-400">({{ $notifications->count() }})</span>
                                            </div>
                                            
                                            @foreach($notifications->sortByDesc('created_at')->take(3) as $notification)
                                                <div class="flex items-start gap-3 p-3 hover:bg-gray-50 rounded-lg cursor-pointer mb-2 {{ $notification->status === 'unread' ? 'bg-blue-50 border-l-4 border-blue-500' : 'border-l-4 border-transparent' }}"
                                                    data-notification-id="{{ $notification->id }}"
                                                    data-notification-type="{{ $notification->type }}">
                                                    <div class="flex-shrink-0">
                                                        <div
                                                            class="w-10 h-10 rounded-full flex items-center justify-center
                                                            @switch($notification->type)
                                                                @case('order')
                                                                    bg-blue-100
                                                                    @break
                                                                @case('promotion')
                                                                    bg-green-100
                                                                    @break
                                                                @case('system')
                                                                    bg-purple-100
                                                                    @break
                                                                @case('security')
                                                                    bg-red-100
                                                                    @break
                                                                @default
                                                                    bg-gray-100
                                                            @endswitch">
                                                            @switch($notification->type)
                                                                @case('order')
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                                        class="w-5 h-5 text-blue-600">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                                                    </svg>
                                                                @break

                                                                @case('promotion')
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                                        class="w-5 h-5 text-green-600">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.732.699 2.431 0l4.318-4.318c.699-.699.699-1.732 0-2.431L9.568 3z" />
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            d="M6 6h.008v.008H6V6z" />
                                                                    </svg>
                                                                @break

                                                                @case('system')
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                                        class="w-5 h-5 text-purple-600">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                                                                    </svg>
                                                                @break

                                                                @case('security')
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                                        class="w-5 h-5 text-red-600">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                                                    </svg>
                                                                @break

                                                                @default
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                                        class="w-5 h-5 text-gray-600">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                                                                    </svg>
                                                            @endswitch
                                                        </div>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center justify-between">
                                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                                {{ $notification->title }}
                                                            </p>
                                                            @if ($notification->priority === 'high')
                                                                <span
                                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                    Quan trọng
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                                            {{ $notification->content }}
                                                        </p>
                                                        <div class="flex items-center justify-between mt-2">
                                                            <p class="text-xs text-gray-400">
                                                                {{ $notification->created_at->diffForHumans() }}
                                                            </p>
                                                            @if ($notification->status === 'unread')
                                                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                            @if ($notifications->count() > 3)
                                                <div class="text-center py-2">
                                                    <a href="{{ route('notifications.index', ['type' => $type]) }}"
                                                        class="text-xs text-blue-600 hover:text-blue-800">
                                                        Xem thêm {{ $notifications->count() - 3 }} thông báo
                                                        {{ strtolower($type) }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-8 text-center">
                                    <div
                                        class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-500">Không có thông báo mới</p>
                                </div>
                            @endif
                        @endauth
                        @guest
                            <div class="p-6 text-center">
                                <p class="text-sm text-gray-500">Vui lòng đăng nhập để xem thông báo</p>
                                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 text-sm mt-2 inline-block">Đăng nhập</a>
                            </div>
                        @endguest
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
                    @if (Auth::user()->role === \App\Enums\UserRole::CUSTOMER)
                        <a href="{{ route('account.profile') }}"
                            class="text-sm text-gray-400 hover:text-orange-500 block">Tài khoản của tôi</a>
                        <a href="{{ route('order_history') }}"
                            class="text-sm text-gray-400 hover:text-orange-500 block">Đơn
                            hàng</a>
                        <a href="{{ route('wishlist') }}" class="text-sm text-gray-400 hover:text-orange-500 block">Danh
                            sách
                            ước</a>
                        <a href="{{ route('cart') }}" class="text-sm text-gray-400 hover:text-orange-500 block">Giỏ
                            hàng</a>

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
                        <a href="{{ route('cart') }}" class="text-sm text-gray-400 hover:text-orange-500 block">Giỏ
                            hàng</a>
                        <a href="{{ route('wishlist') }}" class="text-sm text-gray-400 hover:text-orange-500 block">Danh
                            sách
                            ước</a>
                        <a href="#" class="text-sm text-gray-400 hover:text-orange-500 block">Cửa hàng</a>
                    @endif
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

    <!-- Floating Support Toggle Button -->
    <div id="floating-support-toggle" class="floating-support-toggle" onclick="toggleSupportIcons()">
        <ion-icon id="toggle-icon" name="chevron-back-outline"></ion-icon>
    </div>
    <!-- Floating Support Icons -->
    <div id="floating-support-icons" class="floating-support-icons">
        <a href="https://zalo.me/0915571415" target="_blank" class="support-icon zalo" title="Chat Zalo">
            <img src="https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg" alt="Zalo" />
        </a>
        <a href="https://m.me/yourfacebook" target="_blank" class="support-icon messenger" title="Messenger">
            <img src="https://upload.wikimedia.org/wikipedia/commons/8/83/Facebook_Messenger_4_Logo.svg" alt="Messenger" />
        </a>
        <a href="tel:0915571415" class="support-icon phone" title="Gọi điện">
            <ion-icon name="call"></ion-icon>
        </a>
        <a href="mailto:exclusive@gmail.com" class="support-icon email" title="Gửi email">
            <ion-icon name="mail"></ion-icon>
        </a>
        <a href="/chat" class="support-icon chat" title="Chat trực tiếp">
            <ion-icon name="chatbubbles"></ion-icon>
        </a>
    </div>
    <script>
        let supportIconsOpen = true;
        function toggleSupportIcons() {
            const icons = document.getElementById('floating-support-icons');
            const toggle = document.getElementById('floating-support-toggle');
            const icon = document.getElementById('toggle-icon');
            supportIconsOpen = !supportIconsOpen;
            if (supportIconsOpen) {
                icons.classList.remove('collapsed');
                icon.setAttribute('name', 'chevron-back-outline');
                toggle.style.right = '36px';
            } else {
                icons.classList.add('collapsed');
                icon.setAttribute('name', 'chevron-forward-outline');
                toggle.style.right = '36px';
            }
        }
    </script>

    @stack('scripts')
    <script>
        // Ẩn loader khi toàn bộ trang đã load (bao gồm ảnh, css, js...) với hiệu ứng mờ dần
        window.addEventListener('load', function() {
            var loader = document.getElementById('global-loader');
            if(loader) {
                loader.style.opacity = '0';
                setTimeout(function() {
                    loader.style.display = 'none';
                }, 500); // Thời gian khớp với transition
            }
        });
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>

</html>
