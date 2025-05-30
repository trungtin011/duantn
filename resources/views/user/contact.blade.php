@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-0">
        <!-- breadcrumb -->
        <div class="flex flex-wrap items-center gap-2 my-10 md:my-20 text-sm md:text-base">
            <a href="{{ route('home') }}" class="text-gray-500 hover:underline">Trang chủ</a>
            <span>/</span>
            <span>Liên hệ</span>
        </div>

        <!-- contact section -->
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Contact Info -->
            <div class="w-full lg:w-1/3 bg-white shadow-md rounded-lg p-5 space-y-8">
                <!-- Call -->
                <div class="space-y-2">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gray-400 text-white rounded-full flex items-center justify-center">
                            <i class="fas fa-phone text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold">Gọi cho chúng tôi</h3>
                    </div>
                    <p>Chúng tôi phục vụ 24/7, 7 ngày một tuần.</p>
                    <p>Số điện thoại: <strong>+84 8919576</strong></p>
                </div>

                <div class="border-t border-gray-300"></div>

                <!-- Email -->
                <div class="space-y-2">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gray-400 text-white rounded-full flex items-center justify-center">
                            <i class="fas fa-envelope text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold">Viết thư cho chúng tôi</h3>
                    </div>
                    <p>Điền vào biểu mẫu và chúng tôi sẽ liên hệ với bạn trong vòng 24 giờ.</p>
                    <p>Email: <br><strong>customer@exclusive.com</strong><br><strong>support@exclusive.com</strong></p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="w-full lg:w-2/3 bg-white shadow-md rounded-lg p-5">
                <form action="#" method="POST" class="space-y-6">
                    <div class="flex flex-col md:flex-row gap-4">
                        <input type="text" placeholder="Tên của bạn"
                            class="w-full p-3 rounded-md bg-gray-100 focus:outline-none">
                        <input type="email" placeholder="Email của bạn"
                            class="w-full p-3 rounded-md bg-gray-100 focus:outline-none">
                        <input type="text" placeholder="Số điện thoại"
                            class="w-full p-3 rounded-md bg-gray-100 focus:outline-none">
                    </div>
                    <textarea placeholder="Nội dung" rows="6"
                        class="w-full p-3 rounded-md bg-gray-100 resize-none focus:outline-none"></textarea>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-black text-white px-6 py-3 rounded-md hover:bg-gray-800 transition font-semibold">Gửi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
