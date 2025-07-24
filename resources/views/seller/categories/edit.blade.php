@extends('layouts.seller_home')

@section('content')
    <div class="mt-[32px] mb-[24px]">
        <h1 class="font-semibold text-[28px]">Chỉnh sửa danh mục</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / <a
                href="{{ route('admin.categories.index') }}" class="admin-breadcrumb-link">Danh mục</a> / Chỉnh sửa</div>
    </div>
    @include('layouts.notification')
    <div class="row g-3">
        {{-- Left Column: Add/Edit Category Form --}}
        <div class="col-md-4">
            <div class="p-[24px] bg-white rounded-[8px]">
                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <h5 class="mb-3 admin-card-title">Tải lên ảnh</h5>
                    <div class="flex flex-col justify-center items-center mb-3">
                        <div class="flex flex-col items-center justify-center text-muted">
                            <img id="imagePreview"
                                src="{{ $category->image_path ? asset('storage/' . $category->image_path) : asset('images/upload.png') }}"
                                alt="Image Placeholder" class="mb-2" style="width: 100px; height: 100px;">
                            <p class="mb-0 text-sm">Kích thước ảnh phải nhỏ hơn 5Mb</p>
                        </div>
                        <input type="file" id="uploadImage" name="image_path" class="hidden">
                        <label for="uploadImage"
                            class="border border-[#eff2f5] text-center w-full py-2 px-4 rounded text-[#55585B] text-[12px] mt-3">Tải
                            ảnh lên</label>
                        @error('image_path')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Tên danh mục</label>
                        <input type="text" class="form-control" id="categoryName" name="name"
                            value="{{ old('name', $category->name) }}">
                        @error('name')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="categorySlug" class="form-label">Slug</label>
                        <input type="text" class="form-control" id="categorySlug" name="slug"
                            value="{{ old('slug', $category->slug) }}">
                        @error('slug')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="parentCategory" class="form-label">Danh mục cha</label>
                        <select class="form-select form-select-admin" id="parentCategory" name="parent_id">
                            <option value="">Không có</option>
                            @foreach ($parentCategories as $parentCategory)
                                <option value="{{ $parentCategory->id }}"
                                    {{ old('parent_id', $category->parent_id) == $parentCategory->id ? 'selected' : '' }}>
                                    {{ $parentCategory->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="categoryDescription" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="categoryDescription" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="metaTitle" class="form-label">Meta Title</label>
                        <input type="text" class="form-control" id="metaTitle" name="meta_title"
                            value="{{ old('meta_title', $category->meta_title) }}">
                        @error('meta_title')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="metaDescription" class="form-label">Meta Description</label>
                        <textarea class="form-control" id="metaDescription" name="meta_description" rows="3">{{ old('meta_description', $category->meta_description) }}</textarea>
                        @error('meta_description')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="metaKeywords" class="form-label">Meta Keywords</label>
                        <input type="text" class="form-control" id="metaKeywords" name="meta_keywords"
                            value="{{ old('meta_keywords', $category->meta_keywords) }}">
                        @error('meta_keywords')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-admin-primary">Cập nhật danh mục</button>
                </form>
            </div>
        </div>

        {{-- Right Column: Danh sách danh mục con --}}
        <div class="col-md-8">
            <div class="p-[24px] bg-white rounded-[8px]">
                <h5 class="mb-3 admin-card-title">Danh mục con</h5>
                <div class="table-responsive admin-table-container">
                    <table class="w-full text-xs text-left text-gray-400 border-gray-100">
                        <thead class="text-gray-300 font-semibold border-b border-gray-100">
                            <tr>
                                <th class="py-3">Tên danh mục</th>
                                <th class="py-3 text-right">Slug</th>
                                <th class="py-3 text-right">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                            @foreach ($category->subCategories as $subCategory)
                                <tr>
                                    <td class="py-4">{{ $subCategory->name }}</td>
                                    <td class="py-4 text-right">{{ $subCategory->slug }}</td>
                                    <td class="py-4 text-right">
                                        <form action="{{ route('admin.categories.removeSubCategory', $subCategory->id) }}"
                                            method="POST" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-warning">
                                                <i class="fa-solid fa-link" title="Gỡ"></i>
                                            </button>
                                        </form>

                                        <a href="{{ route('admin.categories.edit', $subCategory->id) }}"
                                            class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"
                                                title="Sửa"></i></a>
                                        <form action="{{ route('admin.categories.destroy', $subCategory->id) }}"
                                            method="POST" style="display:inline-block"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i
                                                    class="fa-solid fa-trash" title="Xoá"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($category->subCategories->isEmpty())
                                <tr>
                                    <td colspan="3" class="text-center text-gray-400 py-4">Không có danh mục con</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
