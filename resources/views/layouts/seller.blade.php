<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Đăng ký trở thành Người bán - ZynoxMall</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/seller/seller-register.css') }}">

    <script src="{{ asset('js/seller/register.js') }}"></script>
    @vite('resources/js/echo.js')
    @stack('styles')
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="w-20">
                    <h4 class="font-bold mb-2">
                        <!-- Logo -->
                        @if (empty($settings->logo))
                            <a class="w-20 lg:w-[50%]" href="/">
                                <img src="{{ asset('images/logo.png') }}" alt="logo" class="w-full h-full">
                            </a>
                        @else
                            <a href="{{ route('home') }}" class="flex items-center">
                                <img src="{{ asset('storage/' . $settings->logo) }}" alt="logo" class="w-20">
                            </a>
                        @endif
                    </h4>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-orange-500 transition-colors">
                        Trang chủ
                    </a>
                    <a href="{{ route('user.tickets.index') }}"
                        class="text-gray-600 hover:text-orange-500 transition-colors">
                        Hỗ trợ
                    </a>
                    <a href="{{ route('contact') }}" class="text-gray-600 hover:text-orange-500 transition-colors">
                        Liên hệ
                    </a>
                </nav>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="flex items-center space-x-3">
                            @include('partials.user-avatar', ['size' => 'sm'])
                            @auth
                                <span
                                    class="hidden sm:block font-medium text-gray-700 hover:text-[#EF3248] cursor-pointer">{{ Auth::user()->fullname ?? Auth::user()->username }}</span>
                            @endauth
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-orange-500 transition-colors">
                            Đăng nhập
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-50 border-t border-gray-200 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-gray-600">
                <p>&copy; 2024 Unstoppable. Tất cả quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    @php
        if (!auth()->check()) {
            header('Location: ' . route('login'));
            exit();
        }
    @endphp
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>

</html>
