@extends('account.layout')

@section('account-content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Thông tin tài khoản</h2>
<div><strong>Tên tài khoản:</strong>{{ $user->username }}</div>
    <div><strong>Họ tên:</strong> {{ $user->fullname }}</div>
    <div><strong>Email:</strong> {{ $user->email }}</div>
    <div><strong>Số điện thoại:</strong> {{ $user->phone }}</div>
    <div><strong>Ngày sinh:</strong> {{ $user->birthday }}</div>
    <div><strong>Giới tính:</strong> {{ ucfirst($user->gender->value) }}</div>
    <div><strong>Trạng thái:</strong> {{ ucfirst($user->status->value) }}</div>
    @if($user->avatar)
        <div class="mt-3"><strong>Ảnh đại diện:</strong><br>
            <img src="{{ asset('storage/' . $user->avatar) }}" class="w-20 h-20 rounded-full object-cover mt-2">
        </div>
    @endif
</div>
@endsection
