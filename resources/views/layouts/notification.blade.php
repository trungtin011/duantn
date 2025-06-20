<!-- resources/views/layouts/notification.blade.php -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Kiểm tra và hiển thị thông báo từ session
    @if (session('success'))
        Swal.fire({
            position: 'top-end',
            toast: true,
            icon: 'success',
            title: 'Thành công!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#0989ff',
            timer: 3000,
            showConfirmButton: false
        });
    @elseif (session('error'))
        Swal.fire({
            position: 'top-end',
            toast: true,
            icon: 'error',
            title: 'Lỗi!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
</script>
