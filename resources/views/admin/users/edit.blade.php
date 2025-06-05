@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h1 class="h3">Sửa thông tin người dùng</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Danh sách người dùng</a></li>
                <li class="breadcrumb-item active" aria-current="page">Sửa thông tin</li>
            </ol>
        </nav>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Tên đăng nhập</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->username) }}" required>
                        <div class="invalid-feedback">Vui lòng nhập tên đăng nhập.</div>
                    </div>

                    <div class="col-md-6">
                        <label for="fullname" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" value="{{ old('fullname', $user->fullname) }}" required>
                        <div class="invalid-feedback">Vui lòng nhập họ và tên.</div>
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" pattern="^\+?\d{9,15}$" required>
                        <div class="invalid-feedback">Vui lòng nhập số điện thoại hợp lệ.</div>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        <div class="invalid-feedback">Vui lòng nhập email hợp lệ.</div>
                    </div>

                    <div class="col-md-6">
                        <select name="role" class="form-select">
                            @foreach(UserRole::cases() as $role)
                                <option value="{{ $role->value }}" {{ $selectedRole === $role->value ? 'selected' : '' }}>
                                    {{ $role->name === 'ADMIN' ? 'Quản trị viên' : ($role->name === 'CUSTOMER' ? 'Khách hàng' : $role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                            <option value="banned" {{ old('status', $user->status) == 'banned' ? 'selected' : '' }}>Bị khóa</option>
                        </select>
                        <div class="invalid-feedback">Vui lòng chọn trạng thái.</div>
                    </div>

                    <div class="col-md-6">
                        <label for="gender" class="form-label">Giới tính</label>
                        <select name="gender" id="gender" class="form-select" required>
                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                        </select>
                        <div class="invalid-feedback">Vui lòng chọn giới tính.</div>
                    </div>

                    <div class="col-md-6">
                        <label for="birthdate" class="form-label">Ngày sinh</label>
                        <input type="date" class="form-control" id="birthdate" name="birthdate" value="{{ old('birthdate', $user->birthdate) }}">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Bootstrap 5 validation
(() => {
  'use strict'
  const forms = document.querySelectorAll('.needs-validation')
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})()
</script>

@endsection
