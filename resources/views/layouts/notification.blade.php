<!-- resources/views/layouts/notification.blade.php -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Kiểm tra và hiển thị thông báo từ session
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#0989ff',
            timer: 3000, // Tự động đóng sau 3 giây
            showConfirmButton: false
        });
    @elseif (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
</script>
