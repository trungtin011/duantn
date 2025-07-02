@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="bg-white rounded shadow-lg p-6 max-w-md w-full">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">
            Xác nhận đăng nhập
        </h2>

        <p class="text-gray-600 text-center mb-6">
            Quét mã QR trên máy tính, sau đó đăng nhập Google tại đây để xác nhận.
        </p>

        @if (Auth::check())
            {{-- Người dùng đã đăng nhập trên điện thoại --}}
            <form method="POST" action="{{ route('qr.confirm.submit') }}">
                @csrf
                <input type="hidden" name="token" value="{{ request('token') }}">
                <button type="submit"
                    class="w-full bg-[#22c55e] hover:bg-[#16a34a] text-white font-bold py-2 rounded">
                    ✅ Cho phép đăng nhập vào máy tính
                </button>
            </form>
        @else
            {{-- Người dùng chưa đăng nhập --}}
            <div class="text-center text-red-600 font-semibold mb-4">
                Bạn chưa đăng nhập!
            </div>
            <div class="text-center">
                <a href="{{ route('auth.google.login') }}"
                   class="inline-flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition">
                    <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-5 h-5 mr-2" alt="Google icon">
                    Đăng nhập với Google
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
