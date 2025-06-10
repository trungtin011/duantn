@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 flex gap-8">
    <div class="w-1/4">
        <div class="bg-white shadow p-4 rounded">
            <h2 class="text-lg font-bold mb-4">Tài khoản</h2>
            <ul class="space-y-2">
                <li><a href="{{ route('account.dashboard') }}" class="hover:underline">Thông tin tài khoản</a></li>
                <li><a href="{{ route('account.profile') }}" class="hover:underline">Chỉnh sửa hồ sơ</a></li>
                <li><a href="{{ route('account.addresses') }}" class="hover:underline">Quản lý địa chỉ</a></li>
            </ul>
        </div>
    </div>

    <div class="w-3/4">
        @yield('account-content')
    </div>
</div>
@endsection
