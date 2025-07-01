@extends('layouts.admin')

@section('content')
<h2>Thêm logo mới</h2>
<form action="{{ route('logo.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label>Tên logo</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Ảnh logo</label>
        <input type="file" name="image" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Trạng thái</label>
        <select name="status" class="form-control">
            <option value="active">Hiển thị</option>
            <option value="inactive" selected>Ẩn</option>
        </select>
    </div>
    <button class="btn btn-success">Thêm</button>
</form>
@endsection
