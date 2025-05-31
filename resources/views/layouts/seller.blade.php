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
    <!-- Header -->
    <header class="bg-white border-b">
        <div class="sm:px-[16px] sm:px-0 py-3 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <a class="text-xl font-bold text-gray-900" href="/">Exclusive</a>
                <span>Đăng ký trở thành người bán</span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="">
        @yield('content')
    </main>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>

</html>
