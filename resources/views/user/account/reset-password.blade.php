@extends('user.account.layout')

@section('account-content')
    <div class="bg-white p-6 rounded shadow max-w-md mx-auto">
        <h2 class="text-xl font-semibold mb-4">Đặt lại mật khẩu mới</h2>

        @if ($errors->any())
            <div class="text-red-600 mb-4">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('account.password.request.confirm') }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-1 font-medium">Mật khẩu mới</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
            </div>

            <button type="submit" class="bg-black text-white px-4 py-2 rounded w-full">
                Đổi mật khẩu
            </button>
        </form>
    </div>
@endsection
