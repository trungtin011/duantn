@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Danh sách thẻ bài viết</h2>
    <a href="{{ route('post-tags.create') }}" class="btn btn-primary mb-3">Thêm thẻ mới</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Tiêu đề</th>
                <th>Slug</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($postTags as $tag)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $tag->title }}</td>
                <td>{{ $tag->slug }}</td>
                <td>{{ $tag->status }}</td>
                <td>
                    <a href="{{ route('post-tags.edit', $tag->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="{{ route('post-tags.destroy', $tag->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa thẻ này?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $postTags->links() }}
</div>
@endsection
