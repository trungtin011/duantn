@extends('layouts.seller_home')
@section('title', 'Thay đổi mật khẩu')
@section('content')
    <div class="admin-page-header mb-5">
        <h1 class="admin-page-title text-2xl">Thay đổi mật khẩu</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Cập nhật thông tin cửa</a> / Đổi mật khẩu
        </div>
    </div>
    @include('seller.partials.account_submenu')
    <div class="bg-white p-6 rounded shadow max-w-md mx-auto relative mb-6">
        <h2 class="text-xl font-semibold mb-4">Đổi mật khẩu</h2>

        @if (session('password_success'))
            <div class="text-green-600 mb-4">{{ session('password_success') }}</div>
        @endif

        @if ($errors->any())
            <div class="text-red-600 mb-4">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('seller.password.request.code') }}" id="sendCodeForm">
            @csrf
            <p class="mb-4">Chúng tôi sẽ gửi mã xác nhận tới email của bạn để tiếp tục đổi mật khẩu.</p>

            <div class="mb-4 p-3 bg-gray-50 rounded">
                <p class="text-sm text-gray-600">Email: <strong>{{ Auth::user()->email }}</strong></p>
            </div>

            <button type="submit" class="bg-black text-white px-4 py-2 rounded w-full mb-3">
                Gửi mã xác nhận
            </button>
        </form>
    </div>

    {{-- JS test email --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form handling code can be added here if needed
        });
    </script>
@endsection
