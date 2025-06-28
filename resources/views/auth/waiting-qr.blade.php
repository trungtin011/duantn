@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-6 rounded shadow text-center">
        <h2 class="text-lg font-bold mb-4">Quét mã QR để xác nhận đăng nhập</h2>
        <div>{!! $qr_svg !!}</div>
        <p class="mt-4 text-gray-600">Dùng điện thoại đăng nhập để quét mã QR này.</p>
    </div>
</div>
@endsection
