@extends('user.account.layout')

@section('account-content')
    <div class="bg-white p-6 rounded shadow max-w-2xl">
        <h2 class="text-2xl font-bold mb-4">Đổi mật khẩu</h2>

        @if (session('password_success'))
            <div class="text-green-600 mb-4">{{ session('password_success') }}</div>
        @endif

        <form method="POST" action="{{ route('account.password.request.confirm') }}">
            @csrf

            <!-- Mật khẩu hiện tại -->
            <div class="mb-4">
                <label class="block mb-1 font-medium">Mật khẩu hiện tại</label>
                <input type="password" name="current_password" class="w-full border rounded px-3 py-2">
                @error('current_password')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mật khẩu mới -->
            <div class="mb-4">
                <label class="block mb-1 font-medium">Mật khẩu mới</label>
                <input type="password" name="new_password" class="w-full border rounded px-3 py-2">
                @error('new_password')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Xác nhận mật khẩu mới -->
            <div class="mb-4">
                <label class="block mb-1 font-medium">Xác nhận mật khẩu mới</label>
                <input type="password" name="new_password_confirmation" class="w-full border rounded px-3 py-2">
                @error('new_password_confirmation')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">
                    Gửi mã xác nhận
                </button>
            </div>
        </form>
    </div>
@endsection
