<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Chờ duyệt đăng ký shop - ZynoxMall</title>
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

        .pulse-animation {
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.8;
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
                    <a href="{{ route('user.tickets.index') }}" class="text-gray-600 hover:text-orange-500 transition-colors">
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
                            <span class="hidden sm:block font-medium text-gray-700 hover:text-[#EF3248] cursor-pointer">
                                {{ Auth::user()->fullname ?? Auth::user()->username }}
                            </span>
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
                    <!-- Status Badge -->
                    <div class="space-y-4">
                        <div class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-700 rounded-full text-sm font-medium">
                            <i class="fas fa-clock mr-2 pulse-animation"></i>
                            Đang chờ duyệt từ Admin
                        </div>
                        
                        <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 leading-tight">
                            Hồ sơ của bạn đã được
                            <span class="bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">
                                gửi thành công!
                            </span>
                        </h1>
                        
                        <p class="text-xl text-gray-600 leading-relaxed">
                            Chúng tôi đã nhận được hồ sơ đăng ký shop của bạn. 
                            Đội ngũ admin sẽ xem xét và phản hồi trong thời gian sớm nhất.
                        </p>
                    </div>

                    <!-- Status Info -->
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl p-6 border border-yellow-200">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-info-circle text-yellow-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Thông tin trạng thái</h3>
                                <p class="text-gray-600 text-sm">Cập nhật mới nhất</p>
                            </div>
                        </div>
                        
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Trạng thái</p>
                                    <p class="text-sm text-gray-600">Đang chờ xác thực</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clock text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Thời gian xử lý</p>
                                    <p class="text-sm text-gray-600">3-4 ngày làm việc</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Steps -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900">Quy trình xử lý</h3>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-semibold">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">Hồ sơ đã được gửi</p>
                                    <p class="text-sm text-gray-600">Thông tin shop và giấy tờ đã được tải lên</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="w-8 h-8 bg-yellow-500 text-white rounded-full flex items-center justify-center text-sm font-semibold pulse-animation">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">Đang xem xét</p>
                                    <p class="text-sm text-gray-600">Admin đang kiểm tra tính hợp lệ</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-semibold">
                                    3
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">Phê duyệt</p>
                                    <p class="text-sm text-gray-600">Kích hoạt shop và gửi thông báo</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 space-y-3">
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-orange-400 to-red-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                            <i class="fas fa-home mr-3"></i>
                            Về trang chủ
                            <i class="fas fa-arrow-right ml-3"></i>
                        </a>
                        
                        <div class="text-center">
                            <a href="{{ route('user.tickets.index') }}" class="text-orange-500 hover:text-orange-600 font-medium">
                                <i class="fas fa-headset mr-2"></i>
                                Cần hỗ trợ? Liên hệ ngay
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Illustration -->
                <div class="relative hidden lg:block">
                    <div class="relative z-10">
                        <!-- Main Illustration -->
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-yellow-400/20 to-orange-500/20 rounded-3xl blur-3xl"></div>
                            <div class="relative bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-2xl border border-white/20">
                                <div class="text-center space-y-6">
                                    <div class="w-24 h-24 mx-auto bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center floating-animation">
                                        <i class="fas fa-hourglass-half text-white text-3xl"></i>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <h3 class="text-2xl font-bold text-gray-900">Đang xử lý hồ sơ</h3>
                                        <p class="text-gray-600">Chúng tôi sẽ thông báo kết quả sớm nhất</p>
                                    </div>

                                    <!-- What's Next -->
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6">
                                        <h4 class="font-semibold text-gray-900 mb-4">
                                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                            Những gì sẽ xảy ra tiếp theo
                                        </h4>
                                        <div class="space-y-3 text-left">
                                            <div class="flex items-start space-x-3">
                                                <div class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                                <p class="text-sm text-gray-600">Admin xem xét thông tin shop</p>
                                            </div>
                                            <div class="flex items-start space-x-3">
                                                <div class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                                <p class="text-sm text-gray-600">Kiểm tra tính hợp lệ giấy tờ</p>
                                            </div>
                                            <div class="flex items-start space-x-3">
                                                <div class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                                <p class="text-sm text-gray-600">Xác thực thông tin kinh doanh</p>
                                            </div>
                                            <div class="flex items-start space-x-3">
                                                <div class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                                <p class="text-sm text-gray-600">Gửi thông báo kết quả qua email</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Important Notice -->
                                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl p-6 border border-yellow-200">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900 mb-2">Lưu ý quan trọng</h4>
                                                <p class="text-sm text-gray-600">
                                                    Bạn không thể đăng ký shop mới trong khi đang chờ duyệt. 
                                                    Vui lòng chờ kết quả xác thực từ admin.
                                                </p>
                                            </div>
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
</body>

</html>
