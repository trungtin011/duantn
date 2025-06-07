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

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="username" class="form-label">Tên đăng nhập</label>
                        <input type="text" id="username" class="form-control" value="{{ $user->username }}" disabled>
                        <input type="hidden" name="username" value="{{ $user->username }}">
                    </div>

                    <div class="col-md-6">
                        <label for="fullname" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control @error('fullname') is-invalid @enderror" id="fullname" name="fullname" value="{{ old('fullname', $user->fullname) }}" required>
                        @error('fullname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" pattern="^\+?\d{9,15}$" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="role" class="form-label">Quyền</label>
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                            @foreach ($roles as $value => $label)
                                <option value="{{ $value }}" {{ old('role', $user->role) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            @foreach ($statuses as $value => $label)
                                <option value="{{ $value }}" {{ old('status', $user->status) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="gender" class="form-label">Giới tính</label>
                        <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror" required>
                            @foreach ($genders as $value => $label)
                                <option value="{{ $value }}" {{ old('gender', $user->gender) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="birthday" class="form-label">Ngày sinh</label>
                        <input type="date" class="form-control @error('birthday') is-invalid @enderror" id="birthday" name="birthday" value="{{ old('birthday', $user->birthday) }}">
                        @error('birthday')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Lưu</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Bootstrap 5 validation
(() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>
@endsection