@extends('layouts.admin')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/brand.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Sửa thương hiệu</h1>
        <div class="admin-breadcrumb">
            <a href="{{ route('admin.brands.index') }}" class="admin-breadcrumb-link">Thương hiệu</a> / Sửa thương hiệu
        </div>
    </div>

    @include('layouts.notification')

    <div class="row g-3">
        <div class="col-md-12">
            <div class="admin-card">
                <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Tên thương hiệu</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name', $brand->name) }}" required>
                                @error('name')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea name="description" id="description" class="form-control" rows="5" required>{{ old('description', $brand->description) }}</textarea>
                                @error('description')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="image" class="form-label">Hình ảnh</label>
                                <input type="file" name="image" id="image" class="form-control-file">
                                @if ($brand->image_path)
                                    <img src="{{ asset('storage/' . $brand->image_path) }}" alt="{{ $brand->name }}"
                                        class="mt-2" width="100">
                                @endif
                                @error('image')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="meta_title" class="form-label">Tiêu đề Meta</label>
                                <input type="text" name="meta_title" id="meta_title" class="form-control"
                                    value="{{ old('meta_title', $brand->meta_title) }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="meta_description" class="form-label">Mô tả Meta</label>
                                <textarea name="meta_description" id="meta_description" class="form-control" rows="4">{{ old('meta_description', $brand->meta_description) }}</textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="meta_keywords" class="form-label">Từ khóa Meta</label>
                                <input type="text" name="meta_keywords" id="meta_keywords" class="form-control"
                                    value="{{ old('meta_keywords', $brand->meta_keywords) }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="status" class="form-label">Trạng thái</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="active" {{ old('status', $brand->status) == 'active' ? 'selected' : '' }}>
                                        Hoạt động</option>
                                    <option value="inactive" {{ old('status', $brand->status) == 'inactive' ? 'selected' : '' }}>
                                        Không hoạt động</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="bg-[#28BCF9] hover:bg-[#3DA5F7] text-white py-2 px-4 rounded-md transition-all duration-300">
                            Cập nhật
                        </button>
                        <a href="{{ route('admin.brands.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded-md ms-2 transition-all duration-300">
                            Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection