@extends('account.layout')

@section('account-content')
<div class="bg-white p-6 rounded shadow max-w-2xl">
    <h2 class="text-2xl font-bold mb-4">Chỉnh sửa thông tin cá nhân</h2>

    @if(session('success'))
    <div class="text-green-600 mb-4">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('account.profile.update') }}" enctype="multipart/form-data">
        @csrf
        <!-- Username -->
        <div class="mb-4">
            <label>Tên đăng nhập:</label>
            <input type="text" name="username" value="{{ old('username', $user->username) }}"
                class="w-full border rounded px-3 py-2" required>
        </div>

        <!-- Họ tên -->
        <div class="mb-4">
            <label>Họ tên:</label>
            <input type="text" name="fullname" value="{{ old('fullname', $user->fullname) }}"
                class="w-full border rounded px-3 py-2" required>
        </div>

        <!-- Số điện thoại -->
        <div class="mb-4">
            <label>Số điện thoại:</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                class="w-full border rounded px-3 py-2">
        </div>

        <!-- Ngày sinh -->
        <div class="mb-4">
            <label>Ngày sinh:</label>
            <input type="date" name="birthday"
                value="{{ old('birthday', optional($user->birthday)->format('Y-m-d')) }}"
                class="w-full border rounded px-3 py-2">

        </div>

        <!-- Giới tính -->
        <div class="mb-4">
            <label>Giới tính:</label>
            <select name="gender" class="w-full border rounded px-3 py-2">
                <option value="">-- Chọn --</option>
                <option value="male" {{ old('gender', $user->gender->value ?? '') == 'male' ? 'selected' : '' }}>Nam</option>
                <option value="female" {{ old('gender', $user->gender->value ?? '') == 'female' ? 'selected' : '' }}>Nữ</option>
                <option value="other" {{ old('gender', $user->gender->value ?? '') == 'other' ? 'selected' : '' }}>Khác</option>
            </select>

        </div>

        <!-- Avatar -->
        <div class="mb-4">
            <label>Ảnh đại diện:</label>
            <input type="file" name="avatar" class="w-full border rounded px-3 py-2">
            @if($user->avatar)
            <img src="{{ asset('storage/' . $user->avatar) }}" class="mt-2 w-20 h-20 rounded-full object-cover">
            @endif
        </div>


        <hr class="my-6">

        <h3 class="text-xl font-semibold mb-3">Đổi mật khẩu</h3>

        <!-- Mật khẩu hiện tại -->
        <div class="mb-4">
            <label>Mật khẩu hiện tại:</label>
            <input type="password" name="current_password" class="w-full border rounded px-3 py-2">
            @error('current_password') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Mật khẩu mới -->
        <div class="mb-4">
            <label>Mật khẩu mới:</label>
            <input type="password" name="new_password" class="w-full border rounded px-3 py-2">
        </div>

        <!-- Xác nhận mật khẩu mới -->
        <div class="mb-4">
            <label>Xác nhận mật khẩu mới:</label>
            <input type="password" name="new_password_confirmation" class="w-full border rounded px-3 py-2">
        </div>

        <!-- Nút -->
        <div>
            <button type="submit" class="bg-black text-white px-4 py-2 rounded">Lưu thay đổi</button>
        </div>
    </form>
</div>
@endsection
