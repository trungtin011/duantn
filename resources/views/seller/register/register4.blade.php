@extends('layouts.seller')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-8">
        <div class="">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-8">
                <a href="{{ route('home') }}" class="hover:text-orange-500 transition-colors">
                    <i class="fas fa-home mr-1"></i>
                    Trang chủ
                </a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <span class="text-gray-900 font-medium">Đăng ký trở thành người bán</span>
            </nav>

            <!-- Success Content -->
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-400 to-emerald-500 px-8 py-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">Hoàn tất đăng ký</h1>
                            <p class="text-white/80">Bước 4/4 - Chúc mừng bạn đã hoàn thành!</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-8">
                    <div class="text-center space-y-8">
                        <!-- Success Icon -->
                        <div class="flex justify-center">
                            <div
                                class="w-24 h-24 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center shadow-2xl">
                                <i class="fas fa-check text-white text-4xl"></i>
                            </div>
                        </div>

                        <!-- Success Message -->
                        <div class="space-y-4">
                            <h2 class="text-3xl font-bold text-gray-900">Đăng ký thành công!</h2>
                            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                                Cảm ơn bạn đã đăng ký trở thành người bán trên ZynoxMall.
                                Quá trình xử lý sẽ hoàn tất trong vòng 3–4 ngày làm việc.
                            </p>
                        </div>

                        <!-- Process Timeline -->
                        <div class="bg-gray-50 rounded-2xl p-8 max-w-2xl mx-auto">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center justify-center">
                                <i class="fas fa-clock mr-2 text-green-500"></i>
                                Quy trình xác thực
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-center space-x-4">
                                    <div
                                        class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-semibold">
                                        1
                                    </div>
                                    <div class="flex-1 text-left">
                                        <div class="font-semibold text-gray-900">Gửi thông tin</div>
                                        <div class="text-sm text-gray-600">Thông tin của bạn đã được gửi đến bộ phận xác
                                            thực</div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div
                                        class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-semibold">
                                        2
                                    </div>
                                    <div class="flex-1 text-left">
                                        <div class="font-semibold text-gray-900">Xác thực thông tin</div>
                                        <div class="text-sm text-gray-600">Quá trình xác thực sẽ diễn ra trong vòng 3-4 ngày
                                            làm việc</div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div
                                        class="w-8 h-8 bg-yellow-500 text-white rounded-full flex items-center justify-center text-sm font-semibold">
                                        3
                                    </div>
                                    <div class="flex-1 text-left">
                                        <div class="font-semibold text-gray-900">Thông báo kết quả</div>
                                        <div class="text-sm text-gray-600">Bạn sẽ nhận được thông báo qua email khi xác thực
                                            hoàn tất</div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div
                                        class="w-8 h-8 bg-purple-500 text-white rounded-full flex items-center justify-center text-sm font-semibold">
                                        4
                                    </div>
                                    <div class="flex-1 text-left">
                                        <div class="font-semibold text-gray-900">Bắt đầu kinh doanh</div>
                                        <div class="text-sm text-gray-600">Sau khi được phê duyệt, bạn có thể bắt đầu đăng
                                            sản phẩm</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Important Notes -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 max-w-2xl mx-auto">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-exclamation-triangle text-yellow-600 mt-1"></i>
                                <div class="text-left">
                                    <h3 class="font-semibold text-yellow-900 mb-2">Lưu ý quan trọng</h3>
                                    <ul class="text-sm text-yellow-800 space-y-1">
                                        <li class="flex items-start space-x-2">
                                            <span class="text-yellow-600">•</span>
                                            <span>Vui lòng kiểm tra email thường xuyên để nhận thông báo về trạng thái xác
                                                thực</span>
                                        </li>
                                        <li class="flex items-start space-x-2">
                                            <span class="text-yellow-600">•</span>
                                            <span>Nếu có vấn đề gì, vui lòng liên hệ hỗ trợ qua email hoặc hotline</span>
                                        </li>
                                        <li class="flex items-start space-x-2">
                                            <span class="text-yellow-600">•</span>
                                            <span>Trong thời gian chờ xác thực, bạn có thể chuẩn bị thông tin sản
                                                phẩm</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 max-w-2xl mx-auto">
                            <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center justify-center">
                                <i class="fas fa-headset mr-2"></i>
                                Thông tin liên hệ hỗ trợ
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-envelope text-blue-600"></i>
                                    <span class="text-blue-800">support@zynoxmall.com</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-phone text-blue-600"></i>
                                    <span class="text-blue-800">1900-xxxx</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-clock text-blue-600"></i>
                                    <span class="text-blue-800">8:00 - 18:00 (Thứ 2 - Thứ 6)</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-globe text-blue-600"></i>
                                    <span class="text-blue-800">www.zynoxmall.com</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center pt-8">
                            <a href="{{ route('home') }}"
                                class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                                <i class="fas fa-home mr-2"></i>
                                Về trang chủ
                            </a>
                            {{-- <a href="{{ route('seller.dashboard') }}"
                                class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-orange-400 to-red-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                                <i class="fas fa-store mr-2"></i>
                                Vào trang người bán
                            </a> --}}
                        </div>

                        <!-- Additional Info -->
                        <div class="text-center text-xs text-gray-500 space-y-1 pt-8 border-t border-gray-200">
                            <p>Mã đăng ký: <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ time() }}</span>
                            </p>
                            <p>Thời gian đăng ký: {{ now()->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
