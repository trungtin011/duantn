@extends('layouts.admin')

@section('title', 'Sửa thông tin người dùng')

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Sửa thông tin người dùng</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> /
            <a href="{{ route('admin.users.index') }}" class="admin-breadcrumb-link">Danh sách người dùng</a>/ Sửa thông tin
        </div>
    </div>
    <div class="container">
        @include('layouts.notification')

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="needs-validation">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <input type="text" id="username" class="form-control" value="{{ $user->username }}"
                                disabled>
                            <input type="hidden" name="username" value="{{ $user->username }}">
                        </div>

                        <div class="col-md-6">
                            <label for="fullname" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control @error('fullname') is-invalid @enderror"
                                id="fullname" name="fullname" value="{{ old('fullname', $user->fullname) }}" >
                            @error('fullname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                name="phone" value="{{ old('phone', $user->phone) }}" pattern="^\+?\d{9,15}$" >
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email', $user->email) }}" >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="role" class="form-label">Quyền</label>
                            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror"
                                @if($user->role->value == 'seller') disabled @endif>
                                <option value="">-- Chọn quyền --</option>
                                @foreach ($roles as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('role', $user->role->value) == $value ? 'selected' : '' }}>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($user->role->value == 'seller')
                                <div class="text-danger mt-1" style="font-size:13px;">
                                    Không thể thay đổi quyền của người bán tại đây.
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror"
                                >
                                @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('status', $user->status) == $value ? 'selected' : '' }}>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="gender" class="form-label">Giới tính</label>
                            <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror"
                                >
                                @foreach ($genders as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('gender', $user->gender) == $value ? 'selected' : '' }}>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="birthday" class="form-label">Ngày sinh</label>
                            <input type="date" class="form-control @error('birthday') is-invalid @enderror"
                                id="birthday" name="birthday" value="{{ old('birthday', $user->birthday) }}">
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
