@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">Thêm danh mục</div>
        <div class="card-body">
            <form action="{{ route('help-category.store') }}" method="POST">
                @csrf
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
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Danh mục cha</label>
                    <select name="parent_id" class="form-control">
                        <option value="">--- Không có ---</option>
                        @foreach($parents as $p)
                            <option value="{{ $p->id }}">{{ $p->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Thứ tự</label>
                    <input type="number" name="sort_order" class="form-control" value="0">
                </div>
                <div class="form-group">
                    <label>Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="active">Hoạt động</option>
                        <option value="inactive">Ẩn</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Lưu</button>
            </form>
        </div>
    </div>

@endsection
