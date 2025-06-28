@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">Danh sách bài viết</div>
        <div class="card-body">
            <a href="{{ route('help-article.create') }}" class="btn btn-success mb-3">+ Thêm bài viết</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Danh mục</th>
                        <th>Tiêu đề</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($articles as $item)
                        <tr>
                            <td>{{ $item->category->title ?? '---' }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->status }}</td>
                            <td>
                                <a href="{{ route('help-article.edit', $item->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                <form action="{{ route('help-article.destroy', $item->id) }}" method="POST"
                                    style="display:inline-block">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Xóa bài viết này?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
