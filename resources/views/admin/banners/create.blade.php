@extends('layouts.admin')

@section('title', 'Thêm Banner')

@section('content')

<style>
/* Modal styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    border-radius: 8px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    max-width: 90vw;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.modal-body {
    padding: 1.5rem;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #6b7280;
    transition: color 0.2s;
}

.modal-close:hover {
    color: #374151;
}

/* Banner preview styles */
.banner-preview {
    position: relative;
    width: 100%;
    height: 16rem;
    background-color: #f3f4f6;
    border-radius: 8px;
    overflow: hidden;
}

.banner-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.banner-content {
    position: absolute;
    inset: 0;
    padding: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.banner-text {
    text-align: center;
    color: white;
}

.banner-subtitle {
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
    opacity: 0.9;
}

.banner-title {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 1rem;
}

.banner-btn {
    display: inline-block;
    padding: 0.5rem 1.5rem;
    background-color: white;
    color: #111827;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    transition: background-color 0.2s;
}

.banner-btn:hover {
    background-color: #f3f4f6;
}
</style>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Thêm Banner</h1>
            <a href="{{ route('admin.banners.index') }}" 
               class="text-gray-600 hover:text-gray-900 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Quay lại
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" id="banner-form">
                @csrf
                
                <div class="space-y-6">
                    <!-- Tiêu đề -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Tiêu đề <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" 
                               value="{{ old('title') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                               placeholder="Nhập tiêu đề banner">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mô tả -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Mô tả
                        </label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                  placeholder="Nhập mô tả banner">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hình ảnh -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            Hình ảnh <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <div id="image-preview" class="hidden mb-4">
                                    <img id="preview-img" src="" alt="Preview" class="mx-auto h-32 w-auto rounded-lg">
                                    <div id="image-info" class="mt-2 text-sm text-gray-500 hidden">
                                        <p>Kích thước: <span id="image-dimensions"></span></p>
                                        <p>Dung lượng: <span id="image-file-size"></span></p>
                                    </div>
                                </div>
                                <div id="upload-icon" class="flex flex-col items-center">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Tải lên file</span>
                                            <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">hoặc kéo thả</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG, WEBP tối đa 2MB</p>
                                </div>
                            </div>
                        </div>
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Thông tin kích thước hình ảnh -->
                    <div id="image-size-section" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Thông tin kích thước hình ảnh
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Chiều rộng (px)</label>
                                <input type="number" id="image-width" name="image_width" readonly
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Chiều cao (px)</label>
                                <input type="number" id="image-height" name="image_height" readonly
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Tỷ lệ khung hình</label>
                                <input type="text" id="aspect-ratio" readonly
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700">
                            </div>
                        </div>
                        
                        <!-- Thông tin bổ sung -->
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-blue-900 mb-2">Thông tin file</h4>
                                <div class="text-xs text-blue-700 space-y-1">
                                    <p><strong>Dung lượng:</strong> <span id="file-size-display">-</span></p>
                                    <p><strong>Định dạng:</strong> <span id="file-format-display">-</span></p>
                                </div>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-green-900 mb-2">Khuyến nghị</h4>
                                <div class="text-xs text-green-700 space-y-1">
                                    <p>• Kích thước tối ưu: 1920x1080px</p>
                                    <p>• Dung lượng tối đa: 2MB</p>
                                    <p>• Định dạng: JPG, PNG, WEBP</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Link URL -->
                    <div>
                        <label for="link_url" class="block text-sm font-medium text-gray-700 mb-2">
                            Link URL
                        </label>
                        <input type="url" name="link_url" id="link_url" 
                               value="{{ old('link_url') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('link_url') border-red-500 @enderror"
                               placeholder="https://example.com">
                        @error('link_url')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hidden form inputs for customization (will be synchronized with modal inputs) -->
                    <input type="hidden" name="content_position" id="content_position" value="center">
                    <input type="hidden" name="text_align" id="text_align" value="center">
                    <input type="hidden" name="title_color" id="title_color" value="#ffffff">
                    <input type="hidden" name="subtitle_color" id="subtitle_color" value="#f3f4f6">
                    <input type="hidden" name="title_font_size" id="title_font_size" value="{{ old('title_font_size', '2rem') }}">
                    <input type="hidden" name="subtitle_font_size" id="subtitle_font_size" value="{{ old('subtitle_font_size', '1rem') }}">
                    
                    <!-- Hidden image position settings -->
                    <input type="hidden" name="image_position" id="image_position" value="center">
                    <input type="hidden" name="image_object_fit" id="image_object_fit" value="cover">
                    <input type="hidden" name="image_object_position" id="image_object_position" value="center">
                    <input type="hidden" name="image_parallax" id="image_parallax" value="0">
                    <input type="hidden" name="image_scale" id="image_scale" value="1.00">
                    
                    <!-- Hidden responsive settings -->
                    <input type="hidden" name="responsive_settings[desktop][title_font_size]" id="responsive_desktop_title_font_size" value="{{ old('responsive_settings.desktop.title_font_size', '2rem') }}">
                    <input type="hidden" name="responsive_settings[desktop][subtitle_font_size]" id="responsive_desktop_subtitle_font_size" value="{{ old('responsive_settings.desktop.subtitle_font_size', '1rem') }}">
                    <input type="hidden" name="responsive_settings[desktop][content_position]" id="responsive_desktop_content_position" value="{{ old('responsive_settings.desktop.content_position', 'center') }}">
                    <input type="hidden" name="responsive_settings[desktop][text_align]" id="responsive_desktop_text_align" value="{{ old('responsive_settings.desktop.text_align', 'center') }}">
                    <input type="hidden" name="responsive_settings[desktop][title_color]" id="responsive_desktop_title_color" value="{{ old('responsive_settings.desktop.title_color', '#ffffff') }}">
                    <input type="hidden" name="responsive_settings[desktop][subtitle_color]" id="responsive_desktop_subtitle_color" value="{{ old('responsive_settings.desktop.subtitle_color', '#f3f4f6') }}">
                    <input type="hidden" name="responsive_settings[desktop][image_position]" id="responsive_desktop_image_position" value="{{ old('responsive_settings.desktop.image_position', 'center') }}">
                    <input type="hidden" name="responsive_settings[desktop][image_object_fit]" id="responsive_desktop_image_object_fit" value="{{ old('responsive_settings.desktop.image_object_fit', 'cover') }}">
                    
                    <input type="hidden" name="responsive_settings[tablet][title_font_size]" id="responsive_tablet_title_font_size" value="{{ old('responsive_settings.tablet.title_font_size', '1.5rem') }}">
                    <input type="hidden" name="responsive_settings[tablet][subtitle_font_size]" id="responsive_tablet_subtitle_font_size" value="{{ old('responsive_settings.tablet.subtitle_font_size', '0.875rem') }}">
                    <input type="hidden" name="responsive_settings[tablet][content_position]" id="responsive_tablet_content_position" value="{{ old('responsive_settings.tablet.content_position', 'center') }}">
                    <input type="hidden" name="responsive_settings[tablet][text_align]" id="responsive_tablet_text_align" value="{{ old('responsive_settings.tablet.text_align', 'center') }}">
                    <input type="hidden" name="responsive_settings[tablet][title_color]" id="responsive_tablet_title_color" value="{{ old('responsive_settings.tablet.title_color', '#ffffff') }}">
                    <input type="hidden" name="responsive_settings[tablet][subtitle_color]" id="responsive_tablet_subtitle_color" value="{{ old('responsive_settings.tablet.subtitle_color', '#f3f4f6') }}">
                    <input type="hidden" name="responsive_settings[tablet][image_position]" id="responsive_tablet_image_position" value="{{ old('responsive_settings.tablet.image_position', 'center') }}">
                    <input type="hidden" name="responsive_settings[tablet][image_object_fit]" id="responsive_tablet_image_object_fit" value="{{ old('responsive_settings.tablet.image_object_fit', 'cover') }}">
                    
                    <input type="hidden" name="responsive_settings[mobile][title_font_size]" id="responsive_mobile_title_font_size" value="{{ old('responsive_settings.mobile.title_font_size', '1.25rem') }}">
                    <input type="hidden" name="responsive_settings[mobile][subtitle_font_size]" id="responsive_mobile_subtitle_font_size" value="{{ old('responsive_settings.mobile.subtitle_font_size', '0.75rem') }}">
                    <input type="hidden" name="responsive_settings[mobile][content_position]" id="responsive_mobile_content_position" value="{{ old('responsive_settings.mobile.content_position', 'center') }}">
                    <input type="hidden" name="responsive_settings[mobile][text_align]" id="responsive_mobile_text_align" value="{{ old('responsive_settings.mobile.text_align', 'center') }}">
                    <input type="hidden" name="responsive_settings[mobile][title_color]" id="responsive_mobile_title_color" value="{{ old('responsive_settings.mobile.title_color', '#ffffff') }}">
                    <input type="hidden" name="responsive_settings[mobile][subtitle_color]" id="responsive_mobile_subtitle_color" value="{{ old('responsive_settings.mobile.subtitle_color', '#f3f4f6') }}">
                    <input type="hidden" name="responsive_settings[mobile][image_position]" id="responsive_mobile_image_position" value="{{ old('responsive_settings.mobile.image_position', 'center') }}">
                    <input type="hidden" name="responsive_settings[mobile][image_object_fit]" id="responsive_mobile_image_object_fit" value="{{ old('responsive_settings.mobile.image_object_fit', 'cover') }}">



                    <!-- Trạng thái -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Trạng thái <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Thứ tự -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            Thứ tự
                        </label>
                        <input type="number" name="sort_order" id="sort_order" 
                               value="{{ old('sort_order', 0) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sort_order') border-red-500 @enderror"
                               placeholder="0">
                        @error('sort_order')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ngày bắt đầu -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Ngày bắt đầu
                        </label>
                        <input type="datetime-local" name="start_date" id="start_date" 
                               value="{{ old('start_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('start_date') border-red-500 @enderror">
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ngày kết thúc -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Ngày kết thúc
                        </label>
                        <input type="datetime-local" name="end_date" id="end_date" 
                               value="{{ old('end_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('end_date') border-red-500 @enderror">
                        @error('end_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Preview Button -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <button type="button" id="preview-btn"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 flex items-center gap-2">
                        <i class="fas fa-eye"></i>
                        Xem trước Banner
                    </button>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('admin.banners.index') }}" 
                       class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                        Hủy
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Lưu Banner
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div id="preview-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" style="display: none;">
    <div class="flex items-center justify-center min-h-screen p-2">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-7xl h-full max-h-[95vh] flex flex-col">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 flex-shrink-0">
                <h3 class="text-lg font-semibold text-gray-900">Xem trước Banner</h3>
                <button type="button" id="close-preview" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-auto flex-1">
                <!-- Vị trí nội dung -->
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Vị trí nội dung</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="preview-position" class="block text-sm text-gray-600 mb-1">Vị trí chung</label>
                            <select id="preview-position" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="center">Giữa</option>
                                <option value="left">Trái</option>
                                <option value="right">Phải</option>
                                <option value="top-left">Góc trên trái</option>
                                <option value="top-right">Góc trên phải</option>
                                <option value="bottom-left">Góc dưới trái</option>
                                <option value="bottom-right">Góc dưới phải</option>
                            </select>
                        </div>
                        <div>
                            <label for="preview-align" class="block text-sm text-gray-600 mb-1">Căn chỉnh text</label>
                            <select id="preview-align" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="center">Giữa</option>
                                <option value="left">Trái</option>
                                <option value="right">Phải</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tùy chỉnh màu sắc -->
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Tùy chỉnh màu sắc</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="preview-title-color" class="block text-sm text-gray-600 mb-1">Màu tiêu đề</label>
                            <input type="color" id="preview-title-color" value="#ffffff" class="w-full h-10 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label for="preview-subtitle-color" class="block text-sm text-gray-600 mb-1">Màu phụ đề</label>
                            <input type="color" id="preview-subtitle-color" value="#f3f4f6" class="w-full h-10 border border-gray-300 rounded-md">
                        </div>
                    </div>
                </div>

                <!-- Kích thước chữ -->
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Kích thước chữ</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="preview-title-font-size" class="block text-sm text-gray-600 mb-1">Kích thước tiêu đề</label>
                            <input type="text" id="preview-title-font-size" value="2rem" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="2rem">
                        </div>
                        <div>
                            <label for="preview-subtitle-font-size" class="block text-sm text-gray-600 mb-1">Kích thước phụ đề</label>
                            <input type="text" id="preview-subtitle-font-size" value="1rem" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="1rem">
                        </div>
                    </div>
                </div>

                <!-- Image Position Settings -->
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Vị trí Hình ảnh</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label for="preview-image-position" class="block text-sm text-gray-600 mb-1">Vị trí hình ảnh</label>
                            <select id="preview-image-position" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="center">Giữa</option>
                                <option value="left">Trái</option>
                                <option value="right">Phải</option>
                                <option value="top-left">Góc trên trái</option>
                                <option value="top-right">Góc trên phải</option>
                                <option value="bottom-left">Góc dưới trái</option>
                                <option value="bottom-right">Góc dưới phải</option>
                            </select>
                        </div>
                        <div>
                            <label for="preview-image-object-fit" class="block text-sm text-gray-600 mb-1">Cách hiển thị</label>
                            <select id="preview-image-object-fit" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="cover">Cover (Che phủ)</option>
                                <option value="contain">Contain (Chứa đủ)</option>
                                <option value="fill">Fill (Lấp đầy)</option>
                                <option value="none">None (Không thay đổi)</option>
                                <option value="scale-down">Scale Down (Thu nhỏ)</option>
                            </select>
                        </div>
                        <div>
                            <label for="preview-image-object-position" class="block text-sm text-gray-600 mb-1">Điểm neo</label>
                            <select id="preview-image-object-position" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="center">Giữa</option>
                                <option value="left">Trái</option>
                                <option value="right">Phải</option>
                                <option value="top">Trên</option>
                                <option value="bottom">Dưới</option>
                                <option value="top-left">Trên trái</option>
                                <option value="top-right">Trên phải</option>
                                <option value="bottom-left">Dưới trái</option>
                                <option value="bottom-right">Dưới phải</option>
                            </select>
                        </div>
                        <div>
                            <label for="preview-image-scale" class="block text-sm text-gray-600 mb-1">Tỷ lệ thu phóng</label>
                            <input type="range" id="preview-image-scale" min="0.1" max="3.0" step="0.1" value="1.0" 
                                   class="w-full">
                            <div class="text-xs text-gray-500 mt-1">
                                <span id="scale-value">1.0x</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="flex items-center">
                            <input type="checkbox" id="preview-image-parallax" class="mr-2">
                            <span class="text-sm text-gray-600">Hiệu ứng Parallax</span>
                        </label>
                    </div>
                </div>

                <!-- Responsive Settings -->
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Cài đặt Responsive</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Desktop -->
                        <div class="bg-blue-50 p-3 rounded-lg">
                            <h5 class="text-xs font-medium text-blue-900 mb-2">Desktop</h5>
                            <div class="space-y-2">
                                <div>
                                    <label class="block text-xs text-blue-700 mb-1">Font size tiêu đề</label>
                                    <input type="text" id="responsive-desktop-title-size" value="2rem" 
                                           class="w-full px-2 py-1 border border-blue-200 rounded text-xs">
                                </div>
                                <div>
                                    <label class="block text-xs text-blue-700 mb-1">Font size phụ đề</label>
                                    <input type="text" id="responsive-desktop-subtitle-size" value="1rem" 
                                           class="w-full px-2 py-1 border border-blue-200 rounded text-xs">
                                </div>
                                <div>
                                    <label class="block text-xs text-blue-700 mb-1">Vị trí nội dung</label>
                                    <select id="responsive-desktop-position" class="w-full px-2 py-1 border border-blue-200 rounded text-xs">
                                        <option value="center">Giữa</option>
                                        <option value="left">Trái</option>
                                        <option value="right">Phải</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs text-blue-700 mb-1">Căn chỉnh text</label>
                                    <select id="responsive-desktop-align" class="w-full px-2 py-1 border border-blue-200 rounded text-xs">
                                        <option value="center">Giữa</option>
                                        <option value="left">Trái</option>
                                        <option value="right">Phải</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs text-blue-700 mb-1">Màu tiêu đề</label>
                                    <input type="color" id="responsive-desktop-title-color" value="#ffffff" 
                                           class="w-full h-8 border border-blue-200 rounded">
                                </div>
                                <div>
                                    <label class="block text-xs text-blue-700 mb-1">Màu phụ đề</label>
                                    <input type="color" id="responsive-desktop-subtitle-color" value="#f3f4f6" 
                                           class="w-full h-8 border border-blue-200 rounded">
                                </div>
                                <div>
                                    <label class="block text-xs text-blue-700 mb-1">Vị trí hình ảnh</label>
                                    <select id="responsive-desktop-image-position" class="w-full px-2 py-1 border border-blue-200 rounded text-xs">
                                        <option value="center">Giữa</option>
                                        <option value="left">Trái</option>
                                        <option value="right">Phải</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs text-blue-700 mb-1">Cách hiển thị</label>
                                    <select id="responsive-desktop-image-object-fit" class="w-full px-2 py-1 border border-blue-200 rounded text-xs">
                                        <option value="cover">Cover</option>
                                        <option value="contain">Contain</option>
                                        <option value="fill">Fill</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Tablet -->
                        <div class="bg-green-50 p-3 rounded-lg">
                            <h5 class="text-xs font-medium text-green-900 mb-2">Tablet</h5>
                            <div class="space-y-2">
                                <div>
                                    <label class="block text-xs text-green-700 mb-1">Font size tiêu đề</label>
                                    <input type="text" id="responsive-tablet-title-size" value="1.5rem" 
                                           class="w-full px-2 py-1 border border-green-200 rounded text-xs">
                                </div>
                                <div>
                                    <label class="block text-xs text-green-700 mb-1">Font size phụ đề</label>
                                    <input type="text" id="responsive-tablet-subtitle-size" value="0.875rem" 
                                           class="w-full px-2 py-1 border border-green-200 rounded text-xs">
                                </div>
                                <div>
                                    <label class="block text-xs text-green-700 mb-1">Vị trí nội dung</label>
                                    <select id="responsive-tablet-position" class="w-full px-2 py-1 border border-green-200 rounded text-xs">
                                        <option value="center">Giữa</option>
                                        <option value="left">Trái</option>
                                        <option value="right">Phải</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs text-green-700 mb-1">Căn chỉnh text</label>
                                    <select id="responsive-tablet-align" class="w-full px-2 py-1 border border-green-200 rounded text-xs">
                                        <option value="center">Giữa</option>
                                        <option value="left">Trái</option>
                                        <option value="right">Phải</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs text-green-700 mb-1">Màu tiêu đề</label>
                                    <input type="color" id="responsive-tablet-title-color" value="#ffffff" 
                                           class="w-full h-8 border border-green-200 rounded">
                                </div>
                                <div>
                                    <label class="block text-xs text-green-700 mb-1">Màu phụ đề</label>
                                    <input type="color" id="responsive-tablet-subtitle-color" value="#f3f4f6" 
                                           class="w-full h-8 border border-green-200 rounded">
                                </div>
                                <div>
                                    <label class="block text-xs text-green-700 mb-1">Vị trí hình ảnh</label>
                                    <select id="responsive-tablet-image-position" class="w-full px-2 py-1 border border-green-200 rounded text-xs">
                                        <option value="center">Giữa</option>
                                        <option value="left">Trái</option>
                                        <option value="right">Phải</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs text-green-700 mb-1">Cách hiển thị</label>
                                    <select id="responsive-tablet-image-object-fit" class="w-full px-2 py-1 border border-green-200 rounded text-xs">
                                        <option value="cover">Cover</option>
                                        <option value="contain">Contain</option>
                                        <option value="fill">Fill</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile -->
                        <div class="bg-purple-50 p-3 rounded-lg">
                            <h5 class="text-xs font-medium text-purple-900 mb-2">Mobile</h5>
                            <div class="space-y-2">
                                <div>
                                    <label class="block text-xs text-purple-700 mb-1">Font size tiêu đề</label>
                                    <input type="text" id="responsive-mobile-title-size" value="1.25rem" 
                                           class="w-full px-2 py-1 border border-purple-200 rounded text-xs">
                                </div>
                                <div>
                                    <label class="block text-xs text-purple-700 mb-1">Font size phụ đề</label>
                                    <input type="text" id="responsive-mobile-subtitle-size" value="0.75rem" 
                                           class="w-full px-2 py-1 border border-purple-200 rounded text-xs">
                                </div>
                                <div>
                                    <label class="block text-xs text-purple-700 mb-1">Vị trí nội dung</label>
                                    <select id="responsive-mobile-position" class="w-full px-2 py-1 border border-purple-200 rounded text-xs">
                                        <option value="center">Giữa</option>
                                        <option value="left">Trái</option>
                                        <option value="right">Phải</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs text-purple-700 mb-1">Căn chỉnh text</label>
                                    <select id="responsive-mobile-align" class="w-full px-2 py-1 border border-purple-200 rounded text-xs">
                                        <option value="center">Giữa</option>
                                        <option value="left">Trái</option>
                                        <option value="right">Phải</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs text-purple-700 mb-1">Màu tiêu đề</label>
                                    <input type="color" id="responsive-mobile-title-color" value="#ffffff" 
                                           class="w-full h-8 border border-purple-200 rounded">
                                </div>
                                <div>
                                    <label class="block text-xs text-purple-700 mb-1">Màu phụ đề</label>
                                    <input type="color" id="responsive-mobile-subtitle-color" value="#f3f4f6" 
                                           class="w-full h-8 border border-purple-200 rounded">
                                </div>
                                <div>
                                    <label class="block text-xs text-purple-700 mb-1">Vị trí hình ảnh</label>
                                    <select id="responsive-mobile-image-position" class="w-full px-2 py-1 border border-purple-200 rounded text-xs">
                                        <option value="center">Giữa</option>
                                        <option value="left">Trái</option>
                                        <option value="right">Phải</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs text-purple-700 mb-1">Cách hiển thị</label>
                                    <select id="responsive-mobile-image-object-fit" class="w-full px-2 py-1 border border-purple-200 rounded text-xs">
                                        <option value="cover">Cover</option>
                                        <option value="contain">Contain</option>
                                        <option value="fill">Fill</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="banner-preview" class="relative w-full h-96 bg-gray-100 rounded-lg overflow-hidden">
                    <img id="preview-banner-img" src="" alt="Banner Preview" class="w-full h-full object-cover">
                    <div id="preview-content" class="absolute inset-0 flex items-center justify-center p-8">
                        <div class="text-center">
                            <p id="preview-subtitle" class="text-sm mb-2" style="color: #f3f4f6;">Mô tả banner</p>
                            <h2 id="preview-title" class="text-2xl font-bold mb-4" style="color: #ffffff;">Tiêu đề banner</h2>
                            <a href="#" id="preview-btn-link" class="inline-block px-6 py-2 bg-white text-gray-900 rounded-md hover:bg-gray-100">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Banner preview script loaded');
    
    // Preview hình ảnh
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    const uploadIcon = document.getElementById('upload-icon');
    const imageInfo = document.getElementById('image-info');
    const imageWidthInput = document.getElementById('image-width');
    const imageHeightInput = document.getElementById('image-height');
    const aspectRatioInput = document.getElementById('aspect-ratio');

    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    uploadIcon.classList.add('hidden');
                    updateImageInfo(file);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Drag and drop
    const dropZone = document.querySelector('.border-dashed');
    if (dropZone) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                imageInput.files = files;
                imageInput.dispatchEvent(new Event('change'));
            }
        }
    }

    // Preview Modal
    const previewBtn = document.getElementById('preview-btn');
    const previewModal = document.getElementById('preview-modal');
    const closePreview = document.getElementById('close-preview');
    const previewBannerImg = document.getElementById('preview-banner-img');
    const previewTitle = document.getElementById('preview-title');
    const previewSubtitle = document.getElementById('preview-subtitle');
    const previewBtnLink = document.getElementById('preview-btn-link');
    const previewContent = document.getElementById('preview-content');
    const previewPosition = document.getElementById('preview-position');
    const previewAlign = document.getElementById('preview-align');

    console.log('Preview elements:', {
        previewBtn: !!previewBtn,
        previewModal: !!previewModal,
        closePreview: !!closePreview
    });

    // Mở modal preview
    if (previewBtn) {
        previewBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Preview button clicked');
            
            const title = document.getElementById('title')?.value || 'Tiêu đề banner';
            const description = document.getElementById('description')?.value || 'Mô tả banner';
            const linkUrl = document.getElementById('link_url')?.value || '#';
            const imageFile = imageInput?.files[0];

            console.log('Form data:', { title, description, linkUrl, hasImage: !!imageFile });

            if (imageFile) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewBannerImg.src = e.target.result;
                };
                reader.readAsDataURL(imageFile);
            } else {
                previewBannerImg.src = '{{ asset("assets/images/banner-1.jpg") }}'; // Fallback image
            }

            previewTitle.textContent = title;
            previewSubtitle.textContent = description;
            previewBtnLink.href = linkUrl;
            previewBtnLink.textContent = linkUrl === '#' ? 'Xem chi tiết' : 'Xem chi tiết';

            // Apply initial colors and font sizes
            const titleColor = document.getElementById('title_color')?.value || '#ffffff';
            const subtitleColor = document.getElementById('subtitle_color')?.value || '#f3f4f6';
            const titleFontSize = document.getElementById('title_font_size')?.value || '2rem';
            const subtitleFontSize = document.getElementById('subtitle_font_size')?.value || '1rem';

            if (previewTitle) {
                previewTitle.style.color = titleColor;
                previewTitle.style.fontSize = titleFontSize;
            }
            if (previewSubtitle) {
                previewSubtitle.style.color = subtitleColor;
                previewSubtitle.style.fontSize = subtitleFontSize;
            }

            previewModal.style.display = 'block';
            updatePreviewPosition();
        });
    }

    // Đóng modal preview
    if (closePreview) {
        closePreview.addEventListener('click', function() {
            previewModal.style.display = 'none';
        });
    }

    // Đóng modal khi click bên ngoài
    if (previewModal) {
        previewModal.addEventListener('click', function(e) {
            if (e.target === previewModal) {
                previewModal.style.display = 'none';
            }
        });
    }

    // Cập nhật vị trí preview
    function updatePreviewPosition() {
        if (!previewPosition || !previewAlign || !previewContent) return;
        
        const position = previewPosition.value;
        const align = previewAlign.value;

        console.log('Updating position:', position, align);

        // Reset classes
        previewContent.className = 'absolute inset-0 p-8';
        
        // Thêm classes cho vị trí
        switch(position) {
            case 'center':
                previewContent.classList.add('flex', 'items-center', 'justify-center');
                break;
            case 'left':
                previewContent.classList.add('flex', 'items-center', 'justify-start');
                break;
            case 'right':
                previewContent.classList.add('flex', 'items-center', 'justify-end');
                break;
            case 'top-left':
                previewContent.classList.add('flex', 'items-start', 'justify-start');
                break;
            case 'top-right':
                previewContent.classList.add('flex', 'items-start', 'justify-end');
                break;
            case 'bottom-left':
                previewContent.classList.add('flex', 'items-end', 'justify-start');
                break;
            case 'bottom-right':
                previewContent.classList.add('flex', 'items-end', 'justify-end');
                break;
        }

        // Cập nhật căn chỉnh text
        const contentDiv = previewContent.querySelector('div');
        if (contentDiv) {
            contentDiv.className = 'text-' + align;
        }
    }

    // Event listeners cho việc thay đổi vị trí
    if (previewPosition) {
        previewPosition.addEventListener('change', updatePreviewPosition);
    }
    if (previewAlign) {
        previewAlign.addEventListener('change', updatePreviewPosition);
    }

    // Real-time preview khi thay đổi form
    const formInputs = ['title', 'description', 'link_url'];
    formInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', function() {
                if (previewModal && previewModal.style.display !== 'none') {
                    const title = document.getElementById('title')?.value || 'Tiêu đề banner';
                    const description = document.getElementById('description')?.value || 'Mô tả banner';
                    const linkUrl = document.getElementById('link_url')?.value || '#';

                    previewTitle.textContent = title;
                    previewSubtitle.textContent = description;
                    previewBtnLink.href = linkUrl;
                    previewBtnLink.textContent = linkUrl === '#' ? 'Xem chi tiết' : 'Xem chi tiết';
                }
            });
        }
    });

    // Synchronize modal inputs with hidden form inputs
    function syncModalToForm() {
        const modalInputs = {
            'preview-position': 'content_position',
            'preview-align': 'text_align',
            'preview-title-color': 'title_color',
            'preview-subtitle-color': 'subtitle_color',
            'preview-title-font-size': 'title_font_size',
            'preview-subtitle-font-size': 'subtitle_font_size',
            'preview-image-position': 'image_position',
            'preview-image-object-fit': 'image_object_fit',
            'preview-image-object-position': 'image_object_position',
            'preview-image-parallax': 'image_parallax',
            'preview-image-scale': 'image_scale'
        };

        Object.keys(modalInputs).forEach(modalId => {
            const modalInput = document.getElementById(modalId);
            const formInput = document.getElementById(modalInputs[modalId]);
            
            if (modalInput && formInput) {
                if (modalInput.type === 'checkbox') {
                    modalInput.addEventListener('change', function() {
                        formInput.value = this.checked ? '1' : '0';
                        updatePreviewStyles();
                    });
                } else {
                    modalInput.addEventListener('input', function() {
                        formInput.value = this.value;
                        updatePreviewStyles();
                    });
                }
            }
        });

        // Handle scale range input
        const scaleInput = document.getElementById('preview-image-scale');
        const scaleValue = document.getElementById('scale-value');
        if (scaleInput && scaleValue) {
            scaleInput.addEventListener('input', function() {
                scaleValue.textContent = this.value + 'x';
            });
        }
    }

    // Update preview styles based on current values
    function updatePreviewStyles() {
        if (previewModal && previewModal.style.display !== 'none') {
            const titleColor = document.getElementById('title_color')?.value || '#ffffff';
            const subtitleColor = document.getElementById('subtitle_color')?.value || '#f3f4f6';
            const titleFontSize = document.getElementById('title_font_size')?.value || '2rem';
            const subtitleFontSize = document.getElementById('subtitle_font_size')?.value || '1rem';

            if (previewTitle) {
                previewTitle.style.color = titleColor;
                previewTitle.style.fontSize = titleFontSize;
            }
            if (previewSubtitle) {
                previewSubtitle.style.color = subtitleColor;
                previewSubtitle.style.fontSize = subtitleFontSize;
            }
        }
    }

    // Initialize modal input synchronization
    syncModalToForm();

    // Initialize modal inputs with form values when modal opens
    if (previewBtn) {
        previewBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Preview button clicked');
            
            // Sync form values to modal inputs
            const formInputs = {
                'content_position': 'preview-position',
                'text_align': 'preview-align',
                'title_color': 'preview-title-color',
                'subtitle_color': 'preview-subtitle-color',
                'title_font_size': 'preview-title-font-size',
                'subtitle_font_size': 'preview-subtitle-font-size'
            };

            Object.keys(formInputs).forEach(formId => {
                const formInput = document.getElementById(formId);
                const modalInput = document.getElementById(formInputs[formId]);
                
                if (formInput && modalInput) {
                    modalInput.value = formInput.value;
                }
            });
            
            const title = document.getElementById('title')?.value || 'Tiêu đề banner';
            const description = document.getElementById('description')?.value || 'Mô tả banner';
            const linkUrl = document.getElementById('link_url')?.value || '#';
            const imageFile = imageInput?.files[0];

            console.log('Form data:', { title, description, linkUrl, hasImage: !!imageFile });

            if (imageFile) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewBannerImg.src = e.target.result;
                };
                reader.readAsDataURL(imageFile);
            } else {
                previewBannerImg.src = '{{ asset("assets/images/banner-1.jpg") }}'; // Fallback image
            }

            previewTitle.textContent = title;
            previewSubtitle.textContent = description;
            previewBtnLink.href = linkUrl;
            previewBtnLink.textContent = linkUrl === '#' ? 'Xem chi tiết' : 'Xem chi tiết';

            // Apply initial colors and font sizes
            updatePreviewStyles();

            previewModal.style.display = 'block';
            updatePreviewPosition();
        });
    }

    // Function to update image info
    function updateImageInfo(file) {
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    imageWidthInput.value = img.width;
                    imageHeightInput.value = img.height;
                    aspectRatioInput.value = `${img.width / img.height}`;
                    imageInfo.classList.remove('hidden');
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            imageInfo.classList.add('hidden');
        }
    }

    // Show/hide image size section
    const imageSizeSection = document.getElementById('image-size-section');
    if (imageSizeSection) {
        imageSizeSection.classList.toggle('hidden', !imageInput || !imageInput.files.length);
    }

    // Event listener for image input change to toggle visibility
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            if (imageSizeSection) {
                imageSizeSection.classList.toggle('hidden', !this.files.length);
            }
        });
    }

    // Responsive Settings Synchronization
    function syncResponsiveSettings() {
        const responsiveInputs = {
            // Desktop
            'responsive-desktop-title-size': 'responsive_desktop_title_font_size',
            'responsive-desktop-subtitle-size': 'responsive_desktop_subtitle_font_size',
            'responsive-desktop-position': 'responsive_desktop_content_position',
            'responsive-desktop-align': 'responsive_desktop_text_align',
            'responsive-desktop-title-color': 'responsive_desktop_title_color',
            'responsive-desktop-subtitle-color': 'responsive_desktop_subtitle_color',
            'responsive-desktop-image-position': 'responsive_desktop_image_position',
            'responsive-desktop-image-object-fit': 'responsive_desktop_image_object_fit',
            
            // Tablet
            'responsive-tablet-title-size': 'responsive_tablet_title_font_size',
            'responsive-tablet-subtitle-size': 'responsive_tablet_subtitle_font_size',
            'responsive-tablet-position': 'responsive_tablet_content_position',
            'responsive-tablet-align': 'responsive_tablet_text_align',
            'responsive-tablet-title-color': 'responsive_tablet_title_color',
            'responsive-tablet-subtitle-color': 'responsive_tablet_subtitle_color',
            'responsive-tablet-image-position': 'responsive_tablet_image_position',
            'responsive-tablet-image-object-fit': 'responsive_tablet_image_object_fit',
            
            // Mobile
            'responsive-mobile-title-size': 'responsive_mobile_title_font_size',
            'responsive-mobile-subtitle-size': 'responsive_mobile_subtitle_font_size',
            'responsive-mobile-position': 'responsive_mobile_content_position',
            'responsive-mobile-align': 'responsive_mobile_text_align',
            'responsive-mobile-title-color': 'responsive_mobile_title_color',
            'responsive-mobile-subtitle-color': 'responsive_mobile_subtitle_color',
            'responsive-mobile-image-position': 'responsive_mobile_image_position',
            'responsive-mobile-image-object-fit': 'responsive_mobile_image_object_fit'
        };

        Object.keys(responsiveInputs).forEach(modalId => {
            const modalInput = document.getElementById(modalId);
            const formInput = document.getElementById(responsiveInputs[modalId]);
            
            if (modalInput && formInput) {
                modalInput.addEventListener('input', function() {
                    formInput.value = this.value;
                });
            }
        });
    }

    // Initialize responsive settings synchronization
    syncResponsiveSettings();
});
</script>
@endpush
