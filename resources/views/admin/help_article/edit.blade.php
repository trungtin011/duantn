@extends('layouts.admin')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Chỉnh sửa bài viết trợ giúp</h1>
        <div class="admin-breadcrumb">
            <a href="{{ route('help-article.index') }}" class="admin-breadcrumb-link">Bài viết trợ giúp</a> / Chỉnh sửa
        </div>
    </div>

    <section class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('help-article.update', $article->id) }}" method="POST">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tiêu đề bài viết *</label>
                        <input type="text" name="title" value="{{ $article->title }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Nhập tiêu đề bài viết" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nội dung bài viết *</label>
                        <textarea name="content" class="summernote w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                  placeholder="Nhập nội dung bài viết..." required>{!! $article->content !!}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column - Sidebar -->
                <div class="space-y-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-4">Cài đặt bài viết</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục *</label>
                                <select name="category_id" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required>
                                    <option value="">Chọn danh mục</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $article->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                                <select name="status" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="active" {{ $article->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="inactive" {{ $article->status == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                    <option value="draft" {{ $article->status == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-blue-700 mb-2">Gợi ý</h3>
                        <ul class="text-xs text-blue-600 space-y-1">
                            <li>• Sử dụng tiêu đề rõ ràng và mô tả</li>
                            <li>• Tổ chức nội dung với các tiêu đề</li>
                            <li>• Bao gồm hình ảnh liên quan nếu cần</li>
                            <li>• Giữ nội dung ngắn gọn và hữu ích</li>
                        </ul>
                    </div>

                    <div class="bg-green-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-green-700 mb-2">Thông tin bài viết</h3>
                        <div class="text-xs text-green-600 space-y-1">
                            <p><strong>Tạo lúc:</strong> {{ $article->created_at ? $article->created_at->format('d/m/Y') : 'Không có' }}</p>
                            <p><strong>Cập nhật:</strong> {{ $article->updated_at ? $article->updated_at->format('d/m/Y') : 'Không có' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('help-article.index') }}" 
                   class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                    Hủy
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Cập nhật bài viết
                </button>
            </div>
        </form>
    </section>

    <!-- jQuery (required for Summernote) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Summernote JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.summernote').summernote({
                height: 400,
                placeholder: 'Nhập nội dung bài viết...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onImageUpload: function(files) {
                        // Handle image upload if needed
                    }
                }
            });
        });
    </script>
@endsection
