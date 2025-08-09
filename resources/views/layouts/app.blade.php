<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png" />
    <title>@yield('title', 'Default Title')</title>

    @php
        $laravelUserData = null;
        if (Auth::check()) {
            $laravelUserData = [
                'id' => Auth::user()->id,
                'name' => Auth::user()->fullname ?? Auth::user()->username,
            ];
        }
    @endphp

    <script>
        window.Laravel = {
            csrfToken: "{{ csrf_token() }}",
            user: @json($laravelUserData)
        };
    </script>

    <!-- Font + Tailwind + Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- Fonts & Styles -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    @stack('styles')
    @vite('resources/css/user/home.css')
    @vite('resources/css/user/orderDetail.css')
    @vite('resources/css/user/notifications.css')
    @vite('resources/css/user/cart-sidebar.css')
    <link rel="stylesheet" href="{{ asset('css/snow-animation.css') }}">

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/snow-animation.js') }}"></script>
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
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.13);
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

        .support-icon.zalo {
            border-color: #008fe5;
        }

        .support-icon.messenger {
            border-color: #0084ff;
        }

        .support-icon.phone {
            border-color: #43a047;
            color: #43a047;
        }

        .support-icon.email {
            border-color: #fbc02d;
            color: #fbc02d;
        }

        .support-icon.chat {
            border-color: #ef3248;
            color: #ef3248;
        }

        .support-icon:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.13);
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
            100% {
                transform: rotate(1turn)
            }
        }
    </style>

    @auth
        <script>
            window.addEventListener('beforeunload', function() {
                navigator.sendBeacon('/update-session', JSON.stringify({
                    user_id: {{ Auth::id() }}
                }));
            });
        </script>
    @endauth
    @include('partials.repay_popup')
</head>

<body class="font-[Inter]">
    <!-- Ad Click Notifications -->
    @include('partials.ad_click_notifications')
    
    <!-- Loader Fullscreen -->
    <div id="global-loader"
        style="position:fixed;z-index:99999;inset:0;display:flex;align-items:center;justify-content:center;background:#fff;">
        <div class="loader"></div>
    </div>
    <!-- Top Header -->
    <div class="bg-black text-white py-3">
        <div class="container mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
            @auth
                @if (optional(Auth::user()->role)->value == 'customer' || Auth::user()->role == 'customer')
                    <div>
                        <a href="{{ route('seller.index') }}"
                            class="text-[#EF3248] text-sm capitalize hover:text-orange-600">
                            Kênh người bán
                        </a>
                    </div>
                @endif
            @endauth
            <div class="flex flex-col md:flex-row items-center gap-2 text-center md:text-left text-sm">
                <span>Khuyến mãi mùa hè cho tất cả đồ bơi và giao hàng nhanh miễn phí - GIẢM 50%!</span>
                <button class="text-white font-bold border-b border-white hover:text-orange-500">Mua ngay</button>
            </div>
            <div class="flex items-center gap-3">
                <!-- Notification Dropdown -->
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
                                    $processedOrderCodes = collect();
                                    foreach ($groupedNotifications as $type => $notifications) {
                                        foreach ($notifications as $notification) {
                                            $isRead = false;
                                            if ($notification->type === 'order' && $notification->order_code) {
                                                if (!$processedOrderCodes->contains($notification->order_code)) {
                                                    $processedOrderCodes->push($notification->order_code);
                                                    if (
                                                        $notification->receiver &&
                                                        $notification->receiver->count() > 0
                                                    ) {
                                                        foreach ($notification->receiver as $receiver) {
                                                            if ($receiver->receiver_id == auth()->id()) {
                                                                $isRead = $receiver->is_read;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    if (!$isRead) {
                                                        $unreadCount++;
                                                    }
                                                }
                                            } else {
                                                if ($notification->receiver && $notification->receiver->count() > 0) {
                                                    foreach ($notification->receiver as $receiver) {
                                                        if ($receiver->receiver_id == auth()->id()) {
                                                            $isRead = $receiver->is_read;
                                                            break;
                                                        }
                                                    }
                                                }
                                                if (!$isRead) {
                                                    $unreadCount++;
                                                }
                                            }
                                        }
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
                        <div class="absolute top-[-15px] right-10 transform w-5 h-5 bg-white clip-triangle"></div>
                        @auth
                            @if (isset($groupedNotifications) && $groupedNotifications->count() > 0)
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="text-sm font-semibold text-gray-700">Thông báo mới</span>
                                        <a href="{{ route('notifications.index') }}"
                                            class="text-xs text-blue-600 hover:text-blue-800">Xem tất cả</a>
                                    </div>
                                    @foreach ($groupedNotifications as $type => $notifications)
                                        <div class="mb-3">
                                            <div class="flex items-center gap-2 mb-2">
                                                <div
                                                    class="w-2 h-2 rounded-full
                                                    @switch($type)
                                                        @case('order') bg-blue-500 @break
                                                        @case('promotion') bg-green-500 @break
                                                        @case('system') bg-purple-500 @break
                                                        @case('security') bg-red-500 @break
                                                        @default bg-gray-500
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
                                            @foreach ($notifications->sortByDesc('created_at')->take(3) as $notification)
                                                @php
                                                    $isRead = false;
                                                    if (
                                                        $notification->receiver &&
                                                        $notification->receiver->count() > 0
                                                    ) {
                                                        foreach ($notification->receiver as $receiver) {
                                                            if ($receiver->receiver_id == auth()->id()) {
                                                                $isRead = $receiver->is_read;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                <div class="flex items-start gap-3 p-3 hover:bg-gray-50 rounded-lg cursor-pointer mb-2 {{ !$isRead ? 'bg-blue-50 border-l-4 border-blue-500' : 'border-l-4 border-transparent' }}"
                                                    data-notification-id="{{ $notification->id }}"
                                                    data-notification-type="{{ $notification->type }}">
                                                    <div class="flex-shrink-0">
                                                        @if ($notification->image_path)
                                                            <div
                                                                class="w-10 h-10 rounded-full flex items-center justify-center">
                                                                <img src="{{ asset('images/notifications/' . $notification->image_path) }}"
                                                                    alt="Notification Image"
                                                                    class="w-full h-full rounded-full">
                                                            </div>
                                                        @else
                                                            @php
                                                                $defaultImage = match ($notification->type) {
                                                                    'order' => 'default-order.png',
                                                                    'promotion' => 'default-promotion.png',
                                                                    'system' => 'default-system.png',
                                                                    'security' => 'default-security.png',
                                                                    default => 'default.png',
                                                                };
                                                            @endphp
                                                            <div
                                                                class="w-10 h-10 rounded-full flex items-center justify-center">
                                                                <img src="{{ asset('images/notifications/' . $defaultImage) }}"
                                                                    alt="Notification Default Image"
                                                                    class="w-full h-full rounded-full">
                                                            </div>
                                                        @endif
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
                                                            @php
                                                                $isRead = false;
                                                                if (
                                                                    $notification->receiver &&
                                                                    $notification->receiver->count() > 0
                                                                ) {
                                                                    foreach ($notification->receiver as $receiver) {
                                                                        if ($receiver->receiver_id == auth()->id()) {
                                                                            $isRead = $receiver->is_read;
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                            @endphp
                                                            @if ($isRead === false)
                                                                <div class="w-2 h-2 bg-blue-500 rounded-full unread-dot"
                                                                    data-notification-id="{{ $notification->id }}"></div>
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
                                <a href="{{ route('login') }}"
                                    class="text-blue-600 hover:text-blue-800 text-sm mt-2 inline-block">Đăng nhập</a>
                            </div>
                        @endguest
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="relative dropdown-parent">
                        @guest
                            <div class="flex items-center gap-2 text-sm">
                                <a href="{{ route('signup') }}" class="hover:text-[#EF3248]">Đăng ký</a>
                                |
                                <a href="{{ route('login') }}" class="hover:text-[#EF3248]">Đăng nhập</a>
                            </div>
                        @endguest
                        @auth
                            <div class="flex items-center gap-1 w-auto">
                                @include('partials.user-avatar', ['size' => 'sm'])
                                @auth
                                    <span
                                        class="text-sm font-semibold text-white hover:text-[#EF3248] cursor-pointer">{{ Auth::user()->fullname ?? Auth::user()->username }}</span>
                                @endauth
                            </div>
                        @endauth
                        <div class="absolute dropdown-content w-[250px] shadow bg-white">
                            @auth
                                <div class="absolute top-[-15px] right-10 transform w-5 h-5 bg-white clip-triangle-second">
                                </div>
                                @if (Auth::user()->role === \App\Enums\UserRole::ADMIN)
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="flex items-center gap-2 px-6 py-3 text-black hover:bg-gray-100 text-sm hover:text-[#EF3248]">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
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
                                    <a href="{{ route('user.order.parent-order') }}"
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
        <div class="container-header mx-auto sm:px-0 py-[10px] px-[10px] flex justify-between items-center">
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

            <!-- Mobile Menu Button -->
            <button class="md:hidden text-2xl text-gray-700" @click="mobileMenuOpen = !mobileMenuOpen">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Search & Cart (Desktop) -->
            <div class="hidden md:flex items-center gap-10 w-5/6">
                <form action="{{ route('search') }}" method="GET" id="searchForm"
                    class="rounded-full border border-gray-300 px-4 py-2 w-full flex items-center justify-between relative">

                    <input type="text" name="query" id="searchInput" placeholder="Bạn muốn tìm kiếm gì ?"
                        class="text-sm focus:outline-none w-full" value="{{ request('query') }}"
                        autocomplete="off" />
                    <button type="submit">
                        <i class="fa fa-search text-gray-700 hover:text-[#EF3248]"></i>
                    </button>

                    <!-- Gợi ý tìm kiếm -->
                    <div id="searchSuggestions"
                        class="absolute top-full left-0 bg-white border w-full mt-1 shadow-lg rounded-md hidden z-50">
                    </div>
                </form>

                <div class="relative">
                    <div
                        class="absolute top-0 left-4 bg-red-500 rounded-full w-4 h-4 flex items-center justify-center z-10">
                        <span id="cart-count" class="text-center text-xs text-white">0</span>
                    </div>
                    <button id="desktop-cart-trigger"
                        class="cart-icon-trigger text-gray-700 hover:text-red-500 text-2xl p-0 border-none bg-transparent cursor-pointer">
                        <i class="fa fa-shopping-cart"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
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
                        <div x-show="userDropdownOpen"
                            class="absolute right-[-1px] mt-2 p-3 w-[250px] bg-gradient-to-b from-gray-800 to-purple-900 bg-opacity-90 backdrop-blur-md rounded-md shadow-lg z-10">
                            @auth
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
                                        class="flex items-center gap-2 px-6 py-3 text-white hover:bg-purple-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                        </svg>
                                        Đơn hàng
                                    </a>
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
    <main class="bg-white min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#111] text-white pt-10">
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
                    @if (Auth::user()->role === \App\Enums\UserRole::CUSTOMER)
                        <a href="{{ route('account.profile') }}"
                            class="text-sm text-gray-400 hover:text-orange-500 block">Tài khoản của tôi</a>
                        <a href="{{ route('order_history') }}"
                            class="text-sm text-gray-400 hover:text-orange-500 block">Đơn hàng</a>
                        <a href="{{ route('wishlist') }}" class="text-sm text-gray-400 hover:text-orange-500 block">Danh
                            sách ước</a>
                        <a href="{{ route('cart') }}" class="text-sm text-gray-400 hover:text-orange-500 block">Giỏ
                            hàng</a>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form-footer').submit();"
                            class="text-sm text-gray-400 hover:text-orange-500 block">Đăng xuất</a>
                        <form id="logout-form-footer" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf</form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-orange-500 block">Đăng
                            nhập</a>
                        <a href="{{ route('register') }}" class="text-sm text-gray-400 hover:text-orange-500 block">Đăng
                            ký</a>
                        <a href="{{ route('cart') }}" class="text-sm text-gray-400 hover:text-orange-500 block">Giỏ
                            hàng</a>
                        <a href="{{ route('wishlist') }}" class="text-sm text-gray-400 hover:text-orange-500 block">Danh
                            sách ước</a>
                        <a href="#" class="text-sm text-gray-400 hover:text-orange-500 block">Cửa hàng</a>
                    @endif
                @endauth
            </div>
            <div>
                <h4 class="font-bold mb-2">Liên kết nhanh</h4>
                <a href="{{ route('help.center') }}" class="text-sm text-gray-400 hover:text-orange-500 block">Chính
                    sách bảo mật</a>
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

    <!-- Popup -->
    <div class="group fixed bottom-20 right-0 sm:bottom-0 sm:right-0 p-2 flex items-end justify-end w-24 h-24">
        <!-- main -->
        <div
            class="text-white shadow-xl flex items-center justify-center p-3 rounded-full bg-gradient-to-r from-cyan-500 to-blue-500 z-50 absolute  ">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="w-6 h-6 group-hover:rotate-90 transition  transition-all duration-[0.6s]">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
        <!-- Chat với Shop -->
        <a href="{{ route('chat.index') }}"
            class="absolute rounded-full transition-all duration-[0.2s] ease-out scale-y-0 group-hover:scale-y-100 group-hover:-translate-x-16 flex p-2 hover:p-3 bg-green-300 scale-100 hover:bg-green-400 text-white cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 1.12-.224 2.19-.623 3.176l-1.291 3.961A2.25 2.25 0 0116.315 21c-1.12-.224-2.19-.623-3.176-.623H9.684c-1.12.224-2.19.623-3.176.623a2.25 2.25 0 01-2.771-2.771L3.623 15.176A10.427 10.427 0 013 12c0-1.12.224-2.19.623-3.176l1.291-3.961A2.25 2.25 0 017.685 3c1.12.224 2.19.623 3.176.623H14.316c1.12-.224 2.19-.623 3.176-.623a2.25 2.25 0 012.771 2.771l-1.291 3.961A10.427 10.427 0 0121 12z" />
            </svg>
        </a>
        <!-- Hồ sơ cá nhân -->
        <a href="{{ route('account.profile') }}"
            class="absolute rounded-full transition-all duration-[0.2s] ease-out scale-x-0 group-hover:scale-x-100 group-hover:-translate-y-16 flex p-2 hover:p-3 bg-blue-300 hover:bg-blue-400 text-white cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
        </a>
        <!-- Chat Bot AI -->
        <a href="{{ route('seller.chat.QA') }}"
            class="absolute rounded-full transition-all duration-[0.2s] ease-out scale-x-0 group-hover:scale-x-100 group-hover:-translate-y-14 group-hover:-translate-x-14 flex p-2 hover:p-3 bg-purple-300 hover:bg-purple-400 text-white cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423L16.5 15.75l.394 1.183a2.25 2.25 0 001.423 1.423L19.5 18.75l-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
            </svg>
        </a>
    </div>
    @stack('scripts')
    <script>
        window.Laravel = {
            user: @json(Auth::user())
        };

        document.addEventListener('DOMContentLoaded', function() {
            // Notification Dropdown
            const notificationBtn = document.querySelector('.dropdown-notification');
            const dropdownContent = document.querySelector('.dropdown-notification-content');
            if (notificationBtn && dropdownContent) {
                notificationBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownContent.classList.toggle('hidden');
                });
                document.addEventListener('click', function(e) {
                    if (!notificationBtn.contains(e.target)) dropdownContent.classList.add('hidden');
                });
                dropdownContent.querySelectorAll('[data-notification-id]').forEach(item => {
                    item.addEventListener('click', function() {
                        const notificationId = this.getAttribute('data-notification-id');
                        markNotificationAsRead(notificationId);
                    });
                });
            }

            function markNotificationAsRead(notificationId) {
                fetch(`/notifications/${notificationId}/mark-read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const notificationItem = document.querySelector(
                                `[data-notification-id="${notificationId}"]`);
                            if (notificationItem) {
                                notificationItem.classList.remove('bg-blue-50', 'border-blue-500');
                                notificationItem.classList.add('border-transparent');
                                const unreadDot = notificationItem.querySelector('.unread-dot');
                                if (unreadDot) unreadDot.remove();
                                const timeContainer = notificationItem.querySelector(
                                    '.flex.items-center.justify-between.mt-2');
                                if (timeContainer) {
                                    const lastChild = timeContainer.lastElementChild;
                                    if (lastChild && lastChild.classList.contains('w-2') && lastChild.classList
                                        .contains('h-2') && lastChild.classList.contains('bg-blue-500')) {
                                        lastChild.remove();
                                    }
                                }
                            }
                            updateUnreadCount();
                        }
                    })
                    .catch(error => console.error('Error marking notification as read:', error));
            }

            function updateUnreadCount() {
                const unreadCountElement = document.querySelector('.bg-red-500.text-white.text-xs');
                if (unreadCountElement) {
                    const currentCount = parseInt(unreadCountElement.textContent);
                    if (currentCount > 1) {
                        unreadCountElement.textContent = currentCount - 1;
                    } else {
                        const badgeContainer = unreadCountElement.closest(
                            '.bg-red-500.text-white.text-xs.rounded-full');
                        if (badgeContainer) badgeContainer.remove();
                        else unreadCountElement.parentElement.remove();
                    }
                }
                const totalUnreadCount = document.querySelectorAll('[data-notification-id]').length;
                if (totalUnreadCount === 0) {
                    const notificationBadge = document.querySelector('.bg-red-500.text-white.text-xs');
                    if (notificationBadge) notificationBadge.style.display = 'none';
                }
            }

            // Cart Sidebar
            const cartIconTrigger = document.getElementById('desktop-cart-trigger');
            const cartSidebar = document.getElementById('cart-sidebar');
            const closeCartSidebarBtn = document.getElementById('close-cart-sidebar');
            const cartSidebarOverlay = document.getElementById('cart-sidebar-overlay');
            const cartItemsContainer = document.getElementById('cart-items-container');
            const loadingCartItems = document.getElementById('loading-cart-items');
            const emptyCartMessage = document.getElementById('empty-cart-message');
            const cartTotalPriceElement = document.getElementById('cart-total-price');
            let cartItemsCache = [];

            function formatCurrency(amount) {
                return new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(amount);
            }

            function updateCartCount() {
                fetch('{{ route('cart.quantity') }}')
                    .then(response => response.json())
                    .then(data => {
                        const cartCountElement = document.getElementById('cart-count');
                        if (cartCountElement) {
                            cartCountElement.textContent = data.quantity;
                            cartCountElement.classList.toggle('hidden', data.quantity <= 0);
                        }
                    })
                    .catch(error => console.error('Error fetching cart quantity:', error));

            }

            function fetchCartItems() {
                if (cartItemsCache.length > 0) {
                    displayCartItems(cartItemsCache);
                    return;
                }
                if (loadingCartItems) loadingCartItems.classList.remove('hidden');
                if (emptyCartMessage) emptyCartMessage.classList.add('hidden');
                cartItemsContainer.innerHTML = '';
                fetch('{{ route('cart.items') }}')
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        cartItemsCache = data.cartItems;
                        displayCartItems(data.cartItems);
                    })
                    .catch(error => {
                        if (emptyCartMessage) {
                            emptyCartMessage.textContent = 'Không thể tải giỏ hàng.';
                            emptyCartMessage.classList.remove('hidden');
                        }
                    })
                    .finally(() => {
                        if (loadingCartItems) loadingCartItems.classList.add('hidden');
                    });
            }

            function displayCartItems(items) {
                if (!cartItemsContainer) return;
                cartItemsContainer.innerHTML = '';
                let totalPrice = 0;
                if (items && items.length > 0) {
                    emptyCartMessage.classList.add('hidden');
                    items.forEach(item => {
                        const itemPrice = item.variant ? (item.variant.sale_price ?? item.variant.price) : (
                            item.product.sale_price ?? item.product.price);
                        const itemTotal = itemPrice * item.quantity;
                        totalPrice += itemTotal;

                        const imageUrl = item.product.images && item.product.images.length > 0 ?
                            '/storage/' + item.product.images[0].image_path :
                            '/images/default_product.png'; // Default image if no images

                        // const itemName = item.variant ? item.variant.variant_name : item.product.name; // Removed this line

                        const cartItemHtml = `
                            <div class="flex items-center gap-3 p-2 cursor-pointer mb-2">
                                <div class="flex-shrink-0 w-16 h-16 rounded-md overflow-hidden">
                                    <img src="${imageUrl}" alt="${item.product.name}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">${item.product.name}</p>
                                    ${item.variant ? `<p class="text-xs text-gray-600 mt-0.5">${item.variant.variant_name}</p>` : ''}
                                    <p class="text-xs text-gray-500 mt-1">${formatCurrency(itemPrice)} x ${item.quantity}</p>
                                    <p class="text-sm font-semibold text-[#EF3248]">${formatCurrency(itemTotal)}</p>
                                </div>
                            </div>
                            <div class="border-b border-gray-200 my-2"></div>
                        `;
                        cartItemsContainer.insertAdjacentHTML('beforeend', cartItemHtml);
                    });
                } else {
                    emptyCartMessage.classList.remove('hidden');
                }
                cartTotalPriceElement.textContent = formatCurrency(totalPrice);
            }
            if (cartIconTrigger && cartSidebar && closeCartSidebarBtn && cartSidebarOverlay) {
                cartIconTrigger.addEventListener('click', (e) => {
                    e.preventDefault(); // Prevent default link behavior
                    fetchCartItems(); // Fetch items when sidebar is opened
                    cartSidebar.classList.remove('hidden'); // Ensure it's visible before transforming
                    // Remove 'open' first to reset transition, then add it immediately
                    cartSidebar.classList.remove('open');
                    void cartSidebar.offsetWidth; // Trigger reflow to apply 'open' after removal
                    cartSidebar.classList.add('open');
                    cartSidebarOverlay.classList.remove('hidden');
                });
                closeCartSidebarBtn.addEventListener('click', () => {
                    cartSidebar.classList.remove('open');
                    cartSidebarOverlay.classList.add('hidden');
                    setTimeout(() => {
                        cartSidebar.classList.add('hidden');
                    }, 700);
                });
                cartSidebarOverlay.addEventListener('click', () => {
                    cartSidebar.classList.remove('open');
                    cartSidebarOverlay.classList.add('hidden');
                    setTimeout(() => {
                        cartSidebar.classList.add('hidden');
                    }, 700);
                });
            }
            updateCartCount();
            document.addEventListener('cartUpdated', () => {
                updateCartCount();
                cartItemsCache = [];
                if (cartSidebar.classList.contains('open')) fetchCartItems();
            });
        });
    </script>

    <!-- Cart Sidebar -->
    <div id="cart-sidebar"
        class="cart-sidebar fixed top-0 right-0 w-[500px] h-full bg-white shadow-lg transform translate-x-full transition-transform duration-700 ease-in-out z-[1000]">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold">Giỏ hàng của bạn</h3>
            <button id="close-cart-sidebar" class="text-gray-500 hover:text-gray-700">
                <i class="fa fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-4 flex-grow overflow-y-auto"> {{-- This div will now handle scrolling --}}
            <div id="cart-items-container">
                <!-- Cart items will be loaded here by JavaScript -->
                <div class="text-center text-gray-500 py-4" id="loading-cart-items">Đang tải sản phẩm...</div>
                <div class="text-center text-gray-500 py-4 hidden" id="empty-cart-message">Giỏ hàng trống.</div>
            </div>
        </div>
        <div class="p-4 border-t"> {{-- This div will be the fixed footer --}}
            <div class="flex justify-between items-center mt-4 pt-3">
                <span class="text-md font-semibold">Tổng phụ:</span>
                <span class="text-md font-bold" id="cart-total-price">0 ₫</span>
            </div>
            <a href="{{ route('cart') }}"
                class="block w-full text-center border border-transparent bg-transparent text-black py-2 mt-4 hover:border-[#EF3248] hover:text-[#EF3248] transition duration-300">
                Xem giỏ hàng
            </a>
            <a href="{{ route('checkout') }}"
                class="block w-full text-center bg-black text-white py-2 mt-2 hover:border-[#EF3248] hover:bg-[#EF3248] transition duration-300">
                Thanh toán ngay
            </a>
        </div>
    </div>

    <!-- Overlay for sidebar -->
    <div id="cart-sidebar-overlay" class="fixed inset-0 bg-black opacity-50 hidden z-[999]"></div>
    <script>
        function showGlobalPopup($order_code) {
            window.repay_order_code = $order_code;
            document.getElementById('global-popup-overlay').style.display = 'block';
        }

        function closeGlobalPopup() {
            document.getElementById('global-popup-overlay').style.display = 'none';
        }
    </script>
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



    <script>
        // Ẩn loader khi toàn bộ trang đã load (bao gồm ảnh, css, js...) với hiệu ứng mờ dần
        window.addEventListener('load', function() {
            var loader = document.getElementById('global-loader');
            if (loader) {
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