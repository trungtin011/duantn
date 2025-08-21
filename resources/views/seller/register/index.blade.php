<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Đăng ký trở thành Người bán - ZynoxMall</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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
                {{-- <!-- Logo -->
                <a href="/" class="flex items-center space-x-2">
                    <div
                        class="w-10 h-10 bg-gradient-to-r from-red-400 to-red-500 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg">Z</span>
                    </div>
                    <div class="hidden sm:flex flex-col">
                        <span
                            class="text-lg font-bold bg-gradient-to-r from-red-400 to-red-500 bg-clip-text text-transparent">
                            ZynoxMall
                        </span>
                        <span class="text-xs">
                            zynoxmall.xyz
                        </span>
                    </div>
                </a> --}}

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
    <main class="flex-grow flex items-center justify-center px-4 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="space-y-8">
                    <!-- Welcome Section -->
                    <div class="space-y-4">
                        <div
                            class="inline-flex items-center px-4 py-2 bg-orange-100 text-orange-700 rounded-full text-sm font-medium">
                            <i class="fas fa-star mr-2"></i>
                            Chào mừng đến với ZynoxMall
                        </div>
                        <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 leading-tight">
                            Bắt đầu hành trình
                            <span class="bg-gradient-to-r from-orange-400 to-red-500 bg-clip-text text-transparent">
                                kinh doanh online
                            </span>
                        </h1>
                        <p class="text-xl text-gray-600 leading-relaxed">
                            Tham gia cộng đồng người bán hàng đầu Việt Nam.
                            Tạo shop của riêng bạn chỉ trong vài phút.
                        </p>
                    </div>

                    <!-- Features -->
                    <div class="grid sm:grid-cols-2 gap-6">
                        <div class="flex items-start space-x-3">
                            <div
                                class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-rocket text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Khởi tạo nhanh chóng</h3>
                                <p class="text-gray-600 text-sm">Đăng ký và thiết lập shop trong 5 phút</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div
                                class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-shield-alt text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Bảo mật tuyệt đối</h3>
                                <p class="text-gray-600 text-sm">Thông tin được mã hóa và bảo vệ</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div
                                class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-line text-purple-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Tăng trưởng bền vững</h3>
                                <p class="text-gray-600 text-sm">Công cụ marketing và phân tích chuyên nghiệp</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div
                                class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-headset text-yellow-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Hỗ trợ 24/7</h3>
                                <p class="text-gray-600 text-sm">Đội ngũ hỗ trợ chuyên nghiệp</p>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="pt-4">
                        <a href="{{ route('seller.register') }}"
                            class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-orange-400 to-red-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                            <i class="fas fa-store mr-3"></i>
                            Bắt đầu đăng ký ngay
                            <i class="fas fa-arrow-right ml-3"></i>
                        </a>
                    </div>
                </div>

                <!-- Right Content - Illustration -->
                <div class="relative hidden lg:block">
                    <div class="relative z-10">
                        <!-- Main Illustration -->
                        <div class="relative">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-orange-400/20 to-red-500/20 rounded-3xl blur-3xl">
                            </div>
                            <div
                                class="relative bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-2xl border border-white/20">
                                <div class="text-center space-y-6">
                                    <div
                                        class="w-24 h-24 mx-auto bg-gradient-to-br from-orange-400 to-red-500 rounded-full flex items-center justify-center floating-animation">
                                        <i class="fas fa-store text-white text-3xl"></i>
                                    </div>
                                    <div class="space-y-2">
                                        <h3 class="text-2xl font-bold text-gray-900">Tạo Shop Online</h3>
                                        <p class="text-gray-600">Thiết lập shop chuyên nghiệp với đầy đủ tính năng</p>
                                    </div>

                                    <!-- Progress Steps -->
                                    <div class="flex justify-center space-x-4">
                                        <div class="flex flex-col items-center space-y-2">
                                            <div
                                                class="w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-semibold">
                                                1
                                            </div>
                                            <span class="text-xs text-gray-600">Thông tin Shop</span>
                                        </div>
                                        <div class="flex flex-col items-center space-y-2">
                                            <div
                                                class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-semibold">
                                                2
                                            </div>
                                            <span class="text-xs text-gray-600">Thông tin Thuế</span>
                                        </div>
                                        <div class="flex flex-col items-center space-y-2">
                                            <div
                                                class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-semibold">
                                                3
                                            </div>
                                            <span class="text-xs text-gray-600">Định danh</span>
                                        </div>
                                        <div class="flex flex-col items-center space-y-2">
                                            <div
                                                class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-semibold">
                                                4
                                            </div>
                                            <span class="text-xs text-gray-600">Hoàn tất</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
</body>

</html>
