@extends('layouts.app')

@section('title', 'Quên mật khẩu')

@section('content')
    <div class="flex items-center justify-center px-4 pt-8">
        <div class="flex flex-col md:flex-row w-full max-w-6xl shadow-xl rounded-xl overflow-hidden">
            <div class="w-full md:w-1/2 bg-cover bg-center min-h-[200px] md:min-h-[500px]"
                style="background-image: url('https://e-commerce-website-muzaffar-ali.vercel.app/_next/image?url=%2Fimages%2Fsignup%2Fmobile.png&w=828&q=75');">
            </div>

            <div class="w-full md:w-1/2 bg-white p-4 md:p-12">
                <h2 class="text-2xl md:text-3xl font-bold mb-3">Quên mật khẩu</h2>
                <p class="text-gray-600 mb-6 text-sm md:text-base">Nhập email đã đăng ký để nhận mã xác nhận.</p>

                @if (session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.code.send') }}">
                    @csrf

                    <div class="mb-4">
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="text-sm md:text-base w-full border rounded px-3 py-2 placeholder-gray-400 @error('email') border-red-500 @enderror"
                            placeholder="Địa chỉ email" required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-black hover:bg-gray-800 text-white py-2 text-sm md:text-base rounded">
                        Gửi mã xác nhận
                    </button>

                    <div class="flex mt-4 text-xs flex-wrap gap-2">
                        <a href="{{ route('login') }}" class="text-gray-600 hover:underline">Quay lại đăng nhập</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
