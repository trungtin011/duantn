<script>
    @php
        $success = session()->pull('success');
        $error = session()->pull('error');
    @endphp

    @if ($success)
        Swal.fire({
            position: 'top-end',
            toast: true,
            icon: 'success',
            title: 'Thành công!',
            text: @json($success),
            confirmButtonColor: '#0989ff',
            timer: 3000,
            showConfirmButton: false
        });
    @elseif ($error)
        Swal.fire({
            position: 'top-end',
            toast: true,
            icon: 'error',
            title: 'Lỗi!',
            text: @json($error),
            confirmButtonColor: '#d33',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
</script>
