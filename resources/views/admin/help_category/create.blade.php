@extends('layouts.admin')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Thêm danh mục trợ giúp</h1>
        <div class="admin-breadcrumb">
            <a href="{{ route('help-category.index') }}" class="admin-breadcrumb-link">Danh mục trợ giúp</a> / Thêm mới
        </div>
    </div>

    <section class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('help-category.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tiêu đề danh mục *</label>
                        <input type="text" name="title" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Nhập tiêu đề danh mục" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục cha</label>
                        <select name="parent_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Không có danh mục cha</option>
                            @foreach($parents as $p)
                                <option value="{{ $p->id }}">{{ $p->title }}</option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Thứ tự sắp xếp</label>
                        <input type="number" name="sort_order" value="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Nhập thứ tự sắp xếp">
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                        <select name="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="active">Hoạt động</option>
                            <option value="inactive">Không hoạt động</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Biểu tượng danh mục</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <div class="space-y-4">
                                <div id="iconPreviewBox" class="w-16 h-16 mx-auto bg-gradient-to-r from-purple-400 to-pink-500 rounded-lg flex items-center justify-center">
                                    <i id="iconDefault" class="fas fa-folder text-white text-xl"></i>
                                    <img id="iconPreview" src="" alt="preview" class="hidden w-full h-full object-cover rounded-lg" />
                                </div>
                                <div>
                                    <label for="icon_file" class="cursor-pointer">
                                        <span class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                                            Chọn file biểu tượng
                                        </span>
                                         <input type="file" id="icon_file" name="icon_file" class="hidden" accept="image/*">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, SVG tối đa 2MB</p>
                            </div>
                        </div>
                        @error('icon_file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('help-category.index') }}" 
                   class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                    Hủy
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Tạo danh mục
                </button>
            </div>
        </form>
    </section>
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('icon_file');
        const iconDefault = document.getElementById('iconDefault');
        const iconPreview = document.getElementById('iconPreview');

        if (!input) return;
        input.addEventListener('change', function () {
            const file = input.files && input.files[0];
            if (!file) {
                if (iconPreview) {
                    iconPreview.src = '';
                    iconPreview.classList.add('hidden');
                }
                if (iconDefault) iconDefault.classList.remove('hidden');
                return;
            }

            const objectUrl = URL.createObjectURL(file);
            if (iconPreview) {
                iconPreview.src = objectUrl;
                iconPreview.onload = function () { URL.revokeObjectURL(objectUrl); };
                iconPreview.classList.remove('hidden');
            }
            if (iconDefault) iconDefault.classList.add('hidden');
        });
    });
    </script>
    @endpush
@endsection
