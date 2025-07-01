@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">Danh sách danh mục</div>
        <div class="card-body">
            <a href="{{ route('help-category.create') }}" class="btn btn-success mb-3">+ Thêm danh mục</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>icon</th>
                        <th>Danh mục cha</th>
                        <th>Tiêu đề</th>
                        <th>Thứ tự</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                        <tr>
                            <td>
                                @if($cat->icon)
                                    <img src="{{ Storage::url($cat->icon) }}" alt="icon" style="width: 24px; height: 24px;">
                                @endif

                                {{ $cat->icon }}
                            </td>
                            <td>{{ $cat->parent->title ?? '---' }}</td>
                            <td>{{ $cat->title }}</td>
                            <td>{{ $cat->sort_order }}</td>
                            <td>{{ $cat->status }}</td>
                            <td>
                                <a href="{{ route('help-category.edit', $cat->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                <form action="{{ route('help-category.destroy', $cat->id) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Xóa danh mục này?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
