@extends('layouts.app')
@section('content')
<div class="text-center mt-10">
    <h2 class="text-xl font-bold mb-4">Đăng nhập bằng mã QR</h2>
    <p>Dùng ứng dụng của bạn để quét mã:</p>

    <div class="my-6">
        {!! QrCode::size(200)->generate($token) !!}
    </div>

    <div id="status" class="text-sm text-gray-600">Đang chờ quét...</div>
</div>

<script>
    const token = "{{ $token }}";

    setInterval(() => {
        fetch("{{ route('login.qr.check') }}", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ token })
        })
        .then(res => res.json())
        .then(data => {
            if (data.authenticated) {
                window.location.href = '/';
            }
        });
    }, 3000);
</script>
@endsection
