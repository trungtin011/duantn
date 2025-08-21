@extends('layouts.admin')

@section('title', 'Chỉnh sửa thương hiệu')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/categories.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Chỉnh sửa thương hiệu</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / <a href="{{ route('admin.brands.index') }}" class="admin-breadcrumb-link">Thương hiệu</a> / Chỉnh sửa</div>
    </div>
    @include('layouts.notification')
    <div class="row g-3">
        <div class="col-md-4">
            <div class="admin-card">
                <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <h5 class="mb-3 admin-card-title">Tải lên ảnh</h5>
                    <div class="flex flex-col justify-center items-center mb-3">
                        <div class="flex flex-col items-center justify-center text-muted">
                            <img id="imagePreview" src="{{ $brand->image_path ? asset('storage/' . $brand->image_path) : asset('images/upload.png') }}" alt="Image Placeholder" class="mb-2" style="width: 100px; height: 100px;">
                            <p class="mb-0 text-sm">Kích thước ảnh phải nhỏ hơn 5Mb</p>
                        </div>
                        <input type="file" id="uploadImage" name="image_path" class="hidden" accept="image/*">
                        <label for="uploadImage" class="border border-[#eff2f5] text-center w-full py-2 px-4 rounded text-[#55585B] text-[12px] mt-3">Tải ảnh lên</label>
                        @error('image_path')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="brandName" class="form-label">Tên thương hiệu</label>
                        <input type="text" class="form-control" id="brandName" name="name" value="{{ old('name', $brand->name) }}">
                        @error('name')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="brandSlug" class="form-label">Slug</label>
                        <input type="text" class="form-control" id="brandSlug" name="slug" value="{{ old('slug', $brand->slug) }}">
                        @error('slug')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="brandDescription" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="brandDescription" name="description" rows="3">{{ old('description', $brand->description) }}</textarea>
                        @error('description')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="metaTitle" class="form-label">Tiêu đề Meta</label>
                        <input type="text" class="form-control" id="metaTitle" name="meta_title" placeholder="Tiêu đề Meta" value="{{ old('meta_title', $brand->meta_title) }}">
                        @error('meta_title')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="metaDescription" class="form-label">Mô tả Meta</label>
                        <textarea class="form-control" id="metaDescription" name="meta_description" rows="3" placeholder="Mô tả Meta">{{ old('meta_description', $brand->meta_description) }}</textarea>
                        @error('meta_description')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="metaKeywords" class="form-label">Từ khóa Meta</label>
                        <input type="text" class="form-control" id="metaKeywords" name="meta_keywords" placeholder="Từ khóa Meta" value="{{ old('meta_keywords', $brand->meta_keywords) }}">
                        @error('meta_keywords')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select form-select-admin" id="status" name="status" required>
                            <option value="active" {{ old('status', $brand->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ old('status', $brand->status) == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                        @error('status')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-admin-primary w-full">Cập nhật thương hiệu</button>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            // Preview image upload
            document.getElementById('uploadImage').addEventListener('change', function (event) {
                const [file] = event.target.files;
                if (file) {
                    document.getElementById('imagePreview').src = URL.createObjectURL(file);
                }
            });
        </script>
    @endpush
@endsection