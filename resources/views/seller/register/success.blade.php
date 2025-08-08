@extends('layouts.seller')

@section('content')
<div class="container mx-auto py-5 flex flex-col" style="min-height: 80vh;">
    <!-- Breadcrumb -->
    <div class="flex flex-wrap items-center gap-2 my-10 md:my-10 text-sm md:text-base">
        <a href="{{ route('home') }}" class="text-gray-500 hover:underline">Trang chủ</a>
        <span>/</span>
        <span>Đăng ký trở thành người bán</span>
    </div>
    
    <div class="p-6 w-full shadow-md rounded-[10px]">
        <div class="bg-white rounded-2xl p-6">
            <div class="flex flex-col items-center justify-center text-center">
                <!-- Icon thành công -->
                <div class="mb-6">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                <!-- Tiêu đề -->
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Đăng ký thành công!</h2>
                
                <!-- Thông báo -->
                <div class="max-w-2xl mx-auto">
                    <p class="text-lg text-gray-600 mb-6">
                        Cảm ơn bạn đã đăng ký trở thành người bán trên nền tảng của chúng tôi!
                    </p>
                    
                    <!-- Thông tin quan trọng -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-blue-800 mb-3">📋 Quy trình xác thực</h3>
                        <div class="space-y-3 text-sm text-blue-700">
                            <div class="flex items-start">
                                <span class="bg-blue-200 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">1</span>
                                <span>Thông tin của bạn đã được gửi đến bộ phận xác thực</span>
                            </div>
                            <div class="flex items-start">
                                <span class="bg-blue-200 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">2</span>
                                <span>Quá trình xác thực sẽ diễn ra trong vòng <strong>3-4 ngày làm việc</strong></span>
                            </div>
                            <div class="flex items-start">
                                <span class="bg-blue-200 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">3</span>
                                <span>Bạn sẽ nhận được thông báo qua email khi xác thực hoàn tất</span>
                            </div>
                            <div class="flex items-start">
                                <span class="bg-blue-200 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">4</span>
                                <span>Sau khi được phê duyệt, bạn có thể bắt đầu đăng sản phẩm và kinh doanh</span>
                            </div>
                        </div>
                    </div>

                    <!-- Lưu ý -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-yellow-800 mb-3">⚠️ Lưu ý quan trọng</h3>
                        <ul class="text-sm text-yellow-700 space-y-2">
                            <li class="flex items-start">
                                <span class="text-yellow-600 mr-2">•</span>
                                <span>Vui lòng kiểm tra email thường xuyên để nhận thông báo về trạng thái xác thực</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-yellow-600 mr-2">•</span>
                                <span>Nếu có vấn đề gì, vui lòng liên hệ hỗ trợ qua email hoặc hotline</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-yellow-600 mr-2">•</span>
                                <span>Trong thời gian chờ xác thực, bạn có thể chuẩn bị thông tin sản phẩm</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Thông tin liên hệ -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">📞 Thông tin liên hệ hỗ trợ</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <span class="font-medium">Email hỗ trợ:</span>
                                <span>support@example.com</span>
                            </div>
                            <div>
                                <span class="font-medium">Hotline:</span>
                                <span>1900-xxxx</span>
                            </div>
                            <div>
                                <span class="font-medium">Thời gian làm việc:</span>
                                <span>8:00 - 18:00 (Thứ 2 - Thứ 6)</span>
                            </div>
                            <div>
                                <span class="font-medium">Website:</span>
                                <span>www.example.com</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nút điều hướng -->
                <div class="flex flex-col sm:flex-row gap-4 mt-8">
                    <a href="{{ route('home') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Về trang chủ
                    </a>
                    <a href="{{ route('seller.dashboard') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        Vào trang người bán
                    </a>
                </div>

                <!-- Thông tin bổ sung -->
                <div class="mt-8 text-xs text-gray-500">
                    <p>Mã đăng ký: <span class="font-mono">{{ time() }}</span></p>
                    <p>Thời gian đăng ký: {{ now()->format('d/m/Y H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 