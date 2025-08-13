@extends('layouts.seller_home')
@section('title', 'Đặt lại mật khẩu mới')
@section('content')
    <div class="admin-page-header mb-5">
        <h1 class="admin-page-title text-2xl">Đặt lại mật khẩu mới</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Cập nhật thông tin cửa</a> / Đổi
            mật khẩu
        </div>
    </div>
    @include('seller.partials.account_submenu')
    <div class="bg-white p-6 rounded shadow max-w-md mx-auto mb-6">
        <h2 class="text-xl font-semibold mb-4">Đặt lại mật khẩu mới</h2>

        @if ($errors->any())
            <div class="text-red-600 mb-4">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="text-green-600 mb-4">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('seller.password.reset.confirm') }}" id="resetPasswordForm">
            @csrf

            <div class="mb-4">
                <label class="block mb-1 font-medium">Mật khẩu mới</label>
                <input type="password" name="password"
                    class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-black focus:border-black" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation"
                    class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-black focus:border-black" required>
            </div>

            <button type="submit" class="bg-black text-white px-4 py-2 rounded w-full hover:bg-gray-800 transition-colors">
                Đổi mật khẩu
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('resetPasswordForm');

            if (form) {
                form.addEventListener('submit', function(e) {
                    const password = form.querySelector('input[name="password"]').value;
                    const passwordConfirmation = form.querySelector('input[name="password_confirmation"]')
                        .value;

                    console.log('Form submitted:', {
                        password_length: password.length,
                        password_confirmation_length: passwordConfirmation.length,
                        passwords_match: password === passwordConfirmation
                    });
                });
            }
        });
    </script>
@endsection
