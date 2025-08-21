@extends('layouts.admin')

@section('title', 'Sửa thuộc tính')

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Sửa thuộc tính</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / <a
                href="{{ route('admin.attributes.index') }}" class="admin-breadcrumb-link">Thuộc tính</a> / Sửa</div>
    </div>
    @include('layouts.notification')
    <div class="row g-3">
        <div class="col-md-4">
            <div class="admin-card">
                <form action="{{ route('admin.attributes.update', $attribute->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <h5 class="mb-3 admin-card-title">Sửa thuộc tính</h5>
                    <div class="mb-3">
                        <label for="attributeName" class="form-label">Tên thuộc tính</label>
                        <input type="text" class="form-control" id="attributeName" name="name"
                            placeholder="Tên thuộc tính" value="{{ old('name', $attribute->name) }}">
                        @error('name')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="attributeValues" class="form-label">Giá trị (cách nhau bằng dấu phẩy, ví dụ: đỏ,
                            đen)</label>
                        <input type="text" class="form-control" id="attributeValues" name="values" placeholder="đỏ, đen"
                            value="{{ old('values', $attribute->attributeValues->pluck('value')->implode(', ')) }}">
                        @error('values')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit"
                        class="bg-[#28BCF9] hover:bg-[#3DA5F7] text-white w-full py-2 px-4 rounded-md flex items-center justify-center transition-all duration-300">Cập
                        nhật
                        thuộc tính</button>
                    <a href="{{ route('admin.attributes.index') }}"
                        class="mt-3 w-full py-2 px-4 rounded-md flex items-center justify-center text-[#55585B] border border-[#eff2f5] hover:bg-[#eff2f5] transition-all duration-300">Hủy</a>
                </form>
            </div>
        </div>
    </div>
@endsection
