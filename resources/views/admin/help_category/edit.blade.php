@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">Sửa danh mục</div>
        <div class="card-body">
            <form action="{{ route('help-category.update', $category->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Ảnh icon</label>
                    <input type="file" name="icon_file" class="form-control" accept="image/*">

                    @if (!empty($category->icon))
                        <div class="mt-2">
                            <img src="{{ Storage::url($category->icon) }}" alt="icon" style="width: 50px;">
                        </div>
                    @endif
                </div>


                <div class="form-group">
                    <label>Tiêu đề</label>
                    <input type="text" name="title" class="form-control" value="{{ $category->title }}" required>
                </div>
                <div class="form-group">
                    <label>Danh mục cha</label>
                    <select name="parent_id" class="form-control">
                        <option value="">--- Không có ---</option>
                        @foreach($parents as $p)
                            <option value="{{ $p->id }}" {{ $category->parent_id == $p->id ? 'selected' : '' }}>{{ $p->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Thứ tự</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ $category->sort_order }}">
                </div>
                <div class="form-group">
                    <label>Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="active" {{ $category->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ $category->status == 'inactive' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
@endsection
