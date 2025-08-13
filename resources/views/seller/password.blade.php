@extends('layouts.seller_home')
@section('title', 'Đổi mật khẩu')
@section('content')
    <div class="flex-1 space-y-6 overflow-y-auto">
        <div class="w-full mx-0">
            <div class="admin-page-header mb-5">
                <h1 class="admin-page-title text-2xl">Đổi mật khẩu</h1>
                <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Cập nhật thông tin cửa</a> / Đổi
                    mật khẩu
                </div>
            </div>
            @include('seller.partials.account_submenu')

            <div class="bg-white p-6 rounded shadow max-w-md mx-auto mb-6">
                <h2 class="text-lg font-semibold mb-4">Đổi mật khẩu</h2>

                @if (session('password_success'))
                    <div class="text-green-600 mb-4">{{ session('password_success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="text-red-600 mb-4 text-sm">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('seller.password.request.code') }}" id="sendCodeForm">
                    @csrf
                    <p class="mb-4 text-sm">Chúng tôi sẽ gửi mã xác nhận tới email của bạn để tiếp tục đổi mật khẩu.</p>

                    <div class="mb-4 p-3 bg-gray-50 rounded">
                        <p class="text-sm text-gray-600">Email: <strong>{{ Auth::user()->email }}</strong></p>
                    </div>

                    <button type="submit" class="bg-black text-white px-4 py-2 rounded w-full mb-3 text-sm">
                        Gửi mã xác nhận
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
