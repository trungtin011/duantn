@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-10 px-4">
    <div class="bg-white shadow-md rounded-lg w-full max-w-md p-6">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Đặt lại mật khẩu</h2>

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.reset') }}">
            @csrf

            <div class="mb-4">
                <label for="password" class="block text-gray-600 mb-1">Mật khẩu mới</label>
                <input type="password" name="password" id="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Nhập mật khẩu mới">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-600 mb-1">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Xác nhận lại mật khẩu">
            </div>

            <button type="submit"
                class="w-full bg-[#ef4444] hover:bg-[#dc2626] text-white py-2 rounded font-medium transition">
                Đặt lại mật khẩu
            </button>
        </form>
    </div>
</div>
@endsection
