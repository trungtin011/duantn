@extends('layouts.admin')
@section('title', 'Đặt lại mật khẩu mới')
@section('content')
<div class="pb-10 mx-auto">
    <div class="admin-page-header">
        <h1 class="admin-page-title">Đặt lại mật khẩu mới</h1>
        <div class="admin-breadcrumb">
            <a href="#" class="admin-breadcrumb-link">Trang chủ</a> / 
            <a href="{{ route('admin.settings.index') }}" class="admin-breadcrumb-link">Cài đặt</a> / 
            <a href="{{ route('admin.password') }}" class="admin-breadcrumb-link">Mật khẩu</a> / 
            Đặt lại
        </div>
    </div>

    <!-- Menu -->
    <div class="mb-6">
        <ul class="flex flex-wrap gap-2 border-b border-gray-200">
            <li><a href="{{ route('admin.settings.index') }}" class="inline-block px-4 py-2 font-semibold text-gray-700 hover:text-blue-600">Tổng quan</a></li>
            <li><a href="{{ route('admin.settings.emails') }}" class="inline-block px-4 py-2 font-semibold text-gray-700 hover:text-blue-600">Emails</a></li>
            <li><a href="{{ route('admin.password') }}" class="inline-block px-4 py-2 font-semibold text-gray-700 hover:text-blue-600 border-b-2 border-blue-600">Mật khẩu</a></li>
        </ul>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800">Đặt lại mật khẩu mới</h2>
            <p class="text-sm text-gray-600">Nhập mật khẩu mới cho tài khoản của bạn.</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.password.reset.confirm') }}" id="resetPasswordForm" class="space-y-6">
            @csrf

            <div class="space-y-4">
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-semibold text-gray-700">Mật khẩu mới</label>
                    <input type="password" name="password" id="password" 
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" 
                           required>
                </div>

                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Xác nhận mật khẩu</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" 
                           required>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-[#f42f46] text-white text-sm font-semibold px-6 py-2 rounded hover:bg-[#d91f35] focus:outline-none focus:ring-2 focus:ring-[#f42f46] focus:ring-opacity-50">
                        <i class="fas fa-key mr-2"></i> Đổi mật khẩu
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('resetPasswordForm');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                const password = form.querySelector('input[name="password"]').value;
                const passwordConfirmation = form.querySelector('input[name="password_confirmation"]').value;
                
                if (password !== passwordConfirmation) {
                    e.preventDefault();
                    alert('Mật khẩu xác nhận không khớp!');
                    return false;
                }
                
                if (password.length < 8) {
                    e.preventDefault();
                    alert('Mật khẩu phải có ít nhất 8 ký tự!');
                    return false;
                }
            });
        }
    });
</script>
@endsection
