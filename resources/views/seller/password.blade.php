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

            <div class="bg-white p-6 rounded-lg shadow-md max-w-2xl mx-auto">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800">Đổi mật khẩu</h2>
                    <p class="text-sm text-gray-600">Thay đổi mật khẩu tài khoản seller của bạn.</p>
                </div>

                @if (session('password_success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('password_success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('seller.password.request.code') }}" class="space-y-6">
                    @csrf
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4">
                            <label class="w-40 text-sm font-semibold text-gray-700">Email:</label>
                            <div class="flex-grow">
                                <div class="border border-gray-300 rounded px-3 py-2 bg-gray-50 text-gray-700">
                                    <strong>{{ Auth::user()->email }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <p class="text-sm text-gray-600 mb-4">
                                Chúng tôi sẽ gửi mã xác nhận tới email của bạn để tiếp tục đổi mật khẩu.
                            </p>
                            <button type="submit" class="bg-[#f42f46] text-white text-sm font-semibold px-4 py-2 rounded hover:bg-[#d91f35] focus:outline-none focus:ring-2 focus:ring-[#f42f46] focus:ring-opacity-50">
                                <i class="fas fa-paper-plane mr-2"></i> Gửi mã xác nhận
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
