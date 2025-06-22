@extends('layouts.admin') {{-- Hoặc layout bạn đang dùng --}}

@section('content')
<div class="container">
    <h2>Chỉnh sửa danh mục bài viết</h2>

    {{-- Hiển thị thông báo --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('post-categories.update', $postCategory->id) }}">
        @csrf
        @method('PUT')

        {{-- Tiêu đề --}}
        <div class="form-group mb-3">
            <label for="title">Tên danh mục</label>
            <input type="text" name="title" value="{{ old('title', $postCategory->title) }}" class="form-control" required>
            @error('title')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Trạng thái --}}
        <div class="form-group mb-3">
            <label for="status">Trạng thái</label>
            <select name="status" class="form-control" required>
                <option value="active" {{ $postCategory->status == 'active' ? 'selected' : '' }}>Kích hoạt</option>
                <option value="inactive" {{ $postCategory->status == 'inactive' ? 'selected' : '' }}>Ẩn</option>
            </select>
            @error('status')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Nút cập nhật --}}
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('post-categories.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
