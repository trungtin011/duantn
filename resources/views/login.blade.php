@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-white">
        <div class="flex w-full max-w-7xl shadow-2xl rounded-2xl overflow-hidden">
            <!-- Bên trái: hình ảnh -->
            <div class="w-1/2 bg-cover bg-center min-h-[600px]"
                style="background-image: url('https://e-commerce-website-muzaffar-ali.vercel.app/_next/image?url=%2Fimages%2Fsignup%2Fmobile.png&w=828&q=75');">
            </div>

            <!-- Bên phải: form đăng nhập -->
            <div class="w-1/2 bg-white p-16">
                <h2 class="text-4xl font-bold mb-4">Đăng nhập</h2>
                <p class="text-gray-600 mb-8 text-lg">Nhập thông tin của bạn bên dưới</p>
                <form>
                    <div class="mb-6">
                        <input type="text" placeholder="Email hoặc số điện thoại"
                            class="text-lg w-full border-b border-gray-300 px-1 py-3 focus:outline-none focus:border-black placeholder-gray-400">
                    </div>
                    <div class="mb-3">
                        <input type="password" placeholder="Mật khẩu"
                            class="text-lg w-full border-b border-gray-300 px-1 py-3 focus:outline-none focus:border-black placeholder-gray-400">
                    </div>

                    <!-- nhớ mật khẩu -->
                    <div class="mb-8 flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" id="remember" class="mr-2">
                            <label for="remember" class="text-sm text-gray-600">Nhớ mật khẩu</label>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-black text-white py-3 px-8 rounded hover:bg-gray-800 text-lg">Đăng
                            nhập</button>
                        <a href="#" class="text-sm text-gray-400 hover:underline">Quên mật khẩu?</a>
                    </div>

                    <div class="flex justify-between mt-6 text-sm flex-wrap gap-2">
                        <span class="underline">Bạn chưa có tài khoản? <a href="{{ route('signup') }}" class="text-gray-600 hover:underline">Đăng ký</a></span>
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
