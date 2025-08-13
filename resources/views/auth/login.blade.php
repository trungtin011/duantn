@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center px-4 pt-8">
        <div class="flex flex-col md:flex-row w-full max-w-6xl shadow-xl rounded-xl overflow-hidden">
            <!-- Image Section -->
            <div class="w-full md:w-1/2 bg-cover bg-center min-h-[200px] md:min-h-[500px]"
                style="background-image: url('https://e-commerce-website-muzaffar-ali.vercel.app/_next/image?url=%2Fimages%2Fsignup%2Fmobile.png&w=828&q=75');">
            </div>

            <!-- Form Section -->
            <div class="w-full md:w-1/2 bg-white p-4 md:p-12">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <h2 class="text-2xl md:text-3xl font-bold mb-3">Đăng nhập</h2>
                <p class="text-gray-600 mb-6 text-sm md:text-base">Nhập thông tin của bạn bên dưới</p>

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <!-- Login Field -->
                    <div class="mb-4">
                        <input type="text" name="login" placeholder="Email hoặc số điện thoại" value="{{ old('login') }}"
                            class="text-sm md:text-base w-full border rounded px-3 py-2 placeholder-gray-400 @error('login') border-red-500 @enderror"
                            required />
                        @error('login')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4">
                        <input type="password" name="password" placeholder="Mật khẩu"
                            class="text-sm md:text-base w-full border rounded px-3 py-2 placeholder-gray-400 @error('password') border-red-500 @enderror"
                            required />
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center mb-6">
                        <input type="checkbox" name="remember" id="remember" class="mr-2">
                        <label for="remember" class="text-sm text-gray-600">Nhớ mật khẩu</label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-black hover:bg-gray-800 text-white py-2 text-sm md:text-base rounded mb-4">
                        Đăng nhập
                    </button>

                    <!-- Forgot Password -->
                    <div class="text-center mb-4">
                        <a href="{{ route('password.email.form') }}" class="text-gray-600 hover:underline text-sm">
                            Quên mật khẩu?
                        </a>
                    </div>

                    <!-- Google Login -->
                    <div class="flex flex-col md:flex-row gap-2 mt-4">
                        <a href="{{ route('auth.google.login') }}"
                            class="w-full flex items-center justify-center border border-red-300 hover:bg-gray-100 py-2 text-sm md:text-base rounded">
                            <img src="https://www.svgrepo.com/show/355037/google.svg" class="h-4 w-4 mr-2"
                                alt="Google icon">
                            Google
                        </a>
                    </div>

                    <!-- Signup Link -->
                    <div class="flex mt-4 text-xs flex-wrap gap-2 justify-center">
                        <span class="text-gray-600">Bạn chưa có tài khoản?
                            <a href="{{ route('signup') }}" class="text-gray-600 hover:underline">Đăng ký</a>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
