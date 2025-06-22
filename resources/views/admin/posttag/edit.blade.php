@extends('layouts.admin') {{-- Hoặc layout bạn đang dùng --}}

@section('content')
<div class="container">
    <h2>Chỉnh sửa thẻ bài viết</h2>

    <form action="{{ route('post-tags.update', $postTag->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Tên thẻ</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $postTag->title) }}" required>
        </div>

        <div class="form-group mt-3">
            <label for="status">Trạng thái</label>
            <select name="status" class="form-control" required>
                <option value="active" {{ $postTag->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                <option value="inactive" {{ $postTag->status == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Cập nhật</button>
        <a href="{{ route('post-tags.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
    </form>
</div>
@endsection
