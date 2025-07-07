@extends('layouts.app')

@section('content')
<div class="text-center mt-10">
    <h2 class="text-2xl font-bold mb-4">Quét mã QR để đăng nhập</h2>

    {{-- Hiển thị mã QR SVG --}}
    <div id="qr-box" class="inline-block bg-white p-4 shadow rounded">
        {!! $qr_svg !!}
    </div>

    <p class="mt-4 text-gray-500">Mở app trên điện thoại và quét mã QR này để đăng nhập.</p>
</div>

<script>
    const token = '{{ $token }}';
    const checkLogin = () => {
        fetch(`/api/qr/poll?token=${token}`)
            .then(res => res.json())
            .then(data => {
                if (data.authenticated) {
                    window.location.href = '/';
                } else {
                    setTimeout(checkLogin, 2000);
                }
            });
    };
    checkLogin();
</script>
@endsection
