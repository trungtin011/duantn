@extends('layouts.admin')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Thêm bài viết mới</h1>
        <div class="admin-breadcrumb">
            <a href="#" class="admin-breadcrumb-link">Home</a> / 
            <a href="{{ route('post.index') }}" class="admin-breadcrumb-link">Bài viết</a> / 
            Thêm mới
        </div>
    </div>

    <section class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Thông tin cơ bản -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label for="inputTitle" class="block text-sm font-medium text-gray-700 mb-2">
                        Tiêu đề <span class="text-red-500">*</span>
                    </label>
                    <input id="inputTitle" type="text" name="title" placeholder="Nhập tiêu đề bài viết" 
                           value="{{ old('title') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="post_cat_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Danh mục <span class="text-red-500">*</span>
                    </label>
                    <select name="post_cat_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('post_cat_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('post_cat_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Trích dẫn -->
            <div>
                <label for="quote" class="block text-sm font-medium text-gray-700 mb-2">Trích dẫn</label>
                <textarea class="form-control summernote-short w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                          id="quote" name="quote" placeholder="Nhập trích dẫn...">{{ old('quote') }}</textarea>
                @error('quote')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tóm tắt -->
            <div>
                <label for="summary" class="block text-sm font-medium text-gray-700 mb-2">
                    Bản tóm tắt <span class="text-red-500">*</span>
                </label>
                <textarea class="form-control summernote-short w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                          id="summary" name="summary" placeholder="Nhập tóm tắt bài viết...">{{ old('summary') }}</textarea>
                @error('summary')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nội dung chi tiết -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Nội dung chi tiết</label>
                <textarea class="form-control summernote w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                          id="description" name="description" placeholder="Nhập nội dung bài viết...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tags và Tác giả -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                    <select name="tags[]" multiple data-live-search="true" 
                            class="form-control selectpicker w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @foreach($tags as $tag)
                            <option value="{{ $tag->title }}" {{ in_array($tag->title, old('tags', [])) ? 'selected' : '' }}>
                                {{ $tag->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('tags')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="added_by" class="block text-sm font-medium text-gray-700 mb-2">Tác giả</label>
                    <select name="added_by" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Chọn tác giả --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('added_by', $users->first()->id) == $user->id ? 'selected' : '' }}>
                                {{ $user->username }}
                            </option>
                        @endforeach
                    </select>
                    @error('added_by')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Upload ảnh -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ảnh đại diện</h3>
                <div class="text-center">
                    <div class="mb-4">
                        <img id="uploadIcon1" class="w-24 h-auto mx-auto mb-2" 
                             src="https://html.hixstudio.net/ebazer/assets/img/icons/upload.png" alt="Upload Icon">
                        <p class="text-sm text-gray-500">Kích thước ảnh phải nhỏ hơn 5MB</p>
                    </div>
                    
                    <div class="flex items-center justify-center">
                        <label for="mainImage" 
                               class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 cursor-pointer transition-colors">
                            <i class="fas fa-upload mr-2"></i>
                            Chọn ảnh
                        </label>
                        <input type="file" id="mainImage" name="photo" class="hidden" accept="image/*">
                    </div>
                    
                    @error('photo')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Trạng thái -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Trạng thái <span class="text-red-500">*</span>
                </label>
                <select name="status" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <button type="reset" 
                        class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                    <i class="fas fa-undo mr-2"></i>Làm mới
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Lưu bài viết
                </button>
            </div>
        </form>
    </section>

    <!-- jQuery (cần cho Summernote) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Summernote JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.summernote-short').summernote({
                height: 150,
                minHeight: 100,
                maxHeight: 100,
                placeholder: 'Nhập nội dung ngắn...',
                toolbar: [
                    ['font', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol']],
                    ['view', ['codeview']]
                ]
            });
            $('.summernote').summernote({
                height: 250,
                placeholder: 'Nhập nội dung bài viết...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
@endsection
