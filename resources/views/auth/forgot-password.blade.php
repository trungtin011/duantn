@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-10 px-4">
    <div class="bg-white shadow-md rounded-lg w-full max-w-md p-6">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Quên mật khẩu</h2>

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
                <label for="email" class="block text-gray-600 mb-1">Địa chỉ Email</label>
                <input type="email" name="email" id="email"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Nhập email bạn đã đăng ký" required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-[#ef4444] hover:bg-[#dc2626] text-white py-2 rounded font-medium transition">
                Gửi mã xác nhận
            </button>
        </form>
    </div>
</div>
@endsection
