@extends('layouts.admin')

@section('content')
<div class="admin-page-header mb-4">
    <h1 class="admin-page-title">Users</h1>
    <div class="admin-breadcrumb">
        <a href="#" class="admin-breadcrumb-link">Home</a> / User List
    </div>
</div>

{{-- Thông báo khi thành công --}}
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table table-dark table-bordered">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Tên đăng nhập</th>
                <th scope="col">Họ và tên</th>
                <th scope="col">Số điện thoại</th>
                <th scope="col">Email</th>
                <th scope="col">Trạng thái</th>
                <th scope="col">Giới tính</th>
                <th scope="col">Ngày sinh</th>
                <th scope="col">Quyền</th>
                <th scope="col">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <th scope="row">{{ $user->id }}</th>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->fullname }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->status }}</td>
                    <td>{{ $user->gender }}</td>
                    <td>{{ $user->birthday }}</td>
                    <td>{{ $user->role }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>

                        {{-- Nút xóa --}}
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
