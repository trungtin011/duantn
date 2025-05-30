@extends('layouts.app')

@section('content')
    <!-- Main đăng ký -->
    <div class="flex items-center justify-center min-h-screen bg-gray-100 px-4">
        <div class="flex flex-col md:flex-row w-full max-w-7xl shadow-2xl rounded-2xl overflow-hidden">
            <!-- Hình ảnh bên trái -->
            <div class="w-full md:w-1/2 bg-cover bg-center min-h-[300px] md:min-h-[600px]"
                style="background-image: url('https://e-commerce-website-muzaffar-ali.vercel.app/_next/image?url=%2Fimages%2Fsignup%2Fmobile.png&w=828&q=75');">
            </div>

            <!-- Form bên phải -->
            <div class="w-full md:w-1/2 bg-white p-6 md:p-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Tạo một tài khoản</h2>
                <p class="text-gray-600 mb-8 text-base md:text-lg">Nhập thông tin của bạn bên dưới</p>
                <form>
                    <div class="mb-6">
                        <input type="text" placeholder="Tên"
                            class="text-base md:text-lg w-full border rounded px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400" />
                    </div>
                    <div class="mb-6">
                        <input type="email" placeholder="Email hoặc số điện thoại"
                            class="text-base md:text-lg w-full border rounded px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400" />
                    </div>
                    <div class="mb-8">
                        <input type="password" placeholder="Mật khẩu"
                            class="text-base md:text-lg w-full border rounded px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400" />
                    </div>

                    <!-- Nút -->
                    <button type="submit"
                        class="w-full bg-black hover:bg-gray-800 text-white py-3 text-base md:text-lg rounded">Tạo tài
                        khoản</button>
                    <button type="button"
                        class="w-full flex items-center justify-center border border-gray-300 bg-white hover:bg-gray-100 text-black py-3 text-base md:text-lg rounded mt-4">
                        <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google icon" class="h-5 w-5 mr-2">
                        Đăng ký với Google
                    </button>

                    <div class="flex mt-6 text-sm flex-wrap gap-2">
                        <span>Bạn đã có tài khoản? <a href="{{ route('login') }}" class="text-gray-600 hover:underline">Đăng nhập</a></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <style>
        .bg-image {
            background-image: url('https://images.unsplash.com/photo-1591337676887-a217a3fca0ed?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
        }
    </style>
@endsection
