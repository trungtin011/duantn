@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Thêm thẻ bài viết mới</h2>

    <form action="{{ route('post-tags.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="title">Tên thẻ</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
        </div>

        <div class="form-group mt-3">
            <label for="status">Trạng thái</label>
            <select name="status" class="form-control" required>
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success mt-3">Thêm</button>
        <a href="{{ route('post-tags.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
    </form>
</div>
@endsection
