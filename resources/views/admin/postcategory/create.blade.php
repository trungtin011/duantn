@extends('layouts.admin')

@section('content')
<h1>Thêm danh mục bài viết</h1>

<form method="POST" action="{{ route('post-categories.store') }}">
    @csrf
    <label>Tiêu đề</label>
    <input type="text" name="title" value="{{ old('title') }}">

    <label>Trạng thái</label>
    <select name="status">
        <option value="active">Hoạt động</option>
        <option value="inactive">Không hoạt động</option>
    </select>

    <button type="submit">Lưu</button>
</form>
@endsection
