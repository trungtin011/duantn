@extends('layouts.app')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Tài khoản bị khóa',
                html: 'Tài khoản của bạn đã bị ban và không thể sử dụng hệ thống.<br>Vui lòng liên hệ quản trị viên để biết thêm chi tiết.',
                confirmButtonText: 'Đăng xuất',
                confirmButtonColor: '#e53935',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: true,
                showCancelButton: false,
                showCloseButton: false,
                focusConfirm: true,
                didOpen: () => {
                    // Disable tab navigation
                    document.querySelectorAll('body *:not(.swal2-container *)').forEach(el => {
                        el.setAttribute('tabindex', '-1');
                    });
                }
            }).then(function(result) {
                if (result.isConfirmed) {
                    // Tạo form ẩn để logout
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('logout') }}";
                    var csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = "{{ csrf_token() }}";
                    form.appendChild(csrf);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>
@endsection
