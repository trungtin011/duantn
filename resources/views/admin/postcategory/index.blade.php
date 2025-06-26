@extends('layouts.admin')

@section('title', 'Danh sách danh mục bài viết')

@section('content')
<div class="container">
    <h1>Danh mục bài viết</h1>

    <a href="{{ route('post-categories.create') }}" class="btn btn-primary mb-3">Thêm danh mục mới</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Tiêu đề</th>
                <th>Slug</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($postCategories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->title }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>{{ $category->status }}</td>
                    <td>
                        <a href="{{ route('post-categories.edit', $category->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('post-categories.destroy', $category->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Xác nhận xóa?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
