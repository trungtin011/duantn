@extends('layouts.seller')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-8">
            <a href="{{ route('home') }}" class="hover:text-orange-500 transition-colors">
                <i class="fas fa-home mr-1"></i>
                Trang chủ
            </a>
            <i class="fas fa-chevron-right text-gray-400"></i>
            <span class="text-gray-900 font-medium">Đăng ký trở thành người bán</span>
        </nav>

        <!-- Main Content -->
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Left Column - Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-orange-400 to-red-500 px-8 py-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-store text-white text-xl"></i>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">Thông tin Shop</h1>
                                <p class="text-white/80">Bước 1/4 - Thiết lập thông tin cơ bản</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Content -->
                    <div class="p-8">
                        <form method="POST" action="{{ route('seller.register.step1') }}" enctype="multipart/form-data" class="space-y-8">
                            @csrf
                            
                            @if ($errors->any())
                                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-exclamation-circle text-red-500"></i>
                                        <h3 class="font-semibold text-red-800">Có lỗi xảy ra</h3>
                                    </div>
                                    <ul class="mt-2 text-sm text-red-700 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>• {{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Shop Name -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-store mr-2 text-orange-500"></i>
                                    Tên Shop <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="shop_name" value="{{ old('shop_name') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                    placeholder="Nhập tên shop của bạn" maxlength="100" required>
                                @error('shop_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-map-marker-alt mr-2 text-orange-500"></i>
                                    Địa chỉ lấy hàng <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="address" value="{{ old('address') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                    placeholder="Nhập địa chỉ lấy hàng" maxlength="255" required>
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Contact Information -->
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        <i class="fas fa-envelope mr-2 text-orange-500"></i>
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                        placeholder="shop@example.com" maxlength="100" required>
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        <i class="fas fa-phone mr-2 text-orange-500"></i>
                                        Số điện thoại <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="phone" value="{{ old('phone') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                        placeholder="0123456789" maxlength="11" required>
                                    @error('phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-align-left mr-2 text-orange-500"></i>
                                    Mô tả shop <span class="text-red-500">*</span>
                                </label>
                                <textarea value="{{ old('shop_description') }}" name="shop_description" rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 resize-none"
                                    placeholder="Mô tả về shop của bạn, sản phẩm chính, đặc điểm nổi bật..." required maxlength="65535">{{ old('shop_description') }}</textarea>
                                @error('shop_description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- File Uploads -->
                            <div class="grid md:grid-cols-2 gap-6">
                                <!-- Logo Upload -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        <i class="fas fa-image mr-2 text-orange-500"></i>
                                        Logo shop <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="file" name="shop_logo" accept="image/*"
                                            class="hidden" id="shop_logo_input" required>
                                        <label for="shop_logo_input" 
                                               class="upload-area flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-orange-500 transition-colors duration-200">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6 {{ session('register_shop.shop_logo') ? 'hidden' : '' }}" id="shop_logo_upload_content">
                                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                                <p class="mb-2 text-sm text-gray-500">
                                                    <span class="font-semibold">Click để tải lên</span> hoặc kéo thả
                                                </p>
                                                <p class="text-xs text-gray-500">PNG, JPG, JPEG (Tối đa 2MB)</p>
                                            </div>
                                            <div id="shop_logo_preview" class="{{ session('register_shop.shop_logo') ? 'w-full h-full flex items-center justify-center' : 'hidden w-full h-full flex items-center justify-center' }}">
                                                <img src="{{ session('register_shop.shop_logo') ? asset('storage/' . session('register_shop.shop_logo')) : '' }}" alt="Logo preview" class="max-w-full max-h-full object-contain rounded-lg">
                                            </div>
                                        </label>
                                    </div>
                                    @error('shop_logo')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Banner Upload -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        <i class="fas fa-image mr-2 text-orange-500"></i>
                                        Banner shop <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="file" name="shop_banner" accept="image/*"
                                            class="hidden" id="shop_banner_input" required>
                                        <label for="shop_banner_input" 
                                               class="upload-area flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-orange-500 transition-colors duration-200">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6 {{ session('register_shop.shop_banner') ? 'hidden' : '' }}" id="shop_banner_upload_content">
                                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                                <p class="mb-2 text-sm text-gray-500">
                                                    <span class="font-semibold">Click để tải lên</span> hoặc kéo thả
                                                </p>
                                                <p class="text-xs text-gray-500">PNG, JPG, JPEG (Tối đa 4MB)</p>
                                            </div>
                                            <div id="shop_banner_preview" class="{{ session('register_shop.shop_banner') ? 'w-full h-full flex items-center justify-center' : 'hidden w-full h-full flex items-center justify-center' }}">
                                                <img src="{{ session('register_shop.shop_banner') ? asset('storage/' . session('register_shop.shop_banner')) : '' }}" alt="Banner preview" class="max-w-full max-h-full object-contain rounded-lg">
                                            </div>
                                        </label>
                                    </div>
                                    @error('shop_banner')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-between items-center pt-8 border-t border-gray-200">
                                <a href="{{ route('home') }}"
                                    class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition-all duration-200">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Quay lại
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-orange-400 to-red-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                                    Tiếp theo
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column - Info & Tips -->
            <div class="lg:col-span-1">
                <div class="space-y-6">
                    <!-- Tips Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-lightbulb text-blue-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Lưu ý quan trọng</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                <p class="text-sm text-gray-600">Tên shop phải độc nhất và không trùng lặp</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                <p class="text-sm text-gray-600">Logo nên có kích thước 200x200px trở lên</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                <p class="text-sm text-gray-600">Banner nên có tỷ lệ 16:9 hoặc 3:1</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                <p class="text-sm text-gray-600">Thông tin sẽ được xác thực trong 3-4 ngày</p>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tiến độ đăng ký</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Thông tin Shop</span>
                                <span class="text-sm font-semibold text-orange-500">Đang thực hiện</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Thông tin Thuế</span>
                                <span class="text-sm font-semibold text-gray-400">Chưa thực hiện</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Định danh</span>
                                <span class="text-sm font-semibold text-gray-400">Chưa thực hiện</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Hoàn tất</span>
                                <span class="text-sm font-semibold text-gray-400">Chưa thực hiện</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
@include('seller.register.modal')
@endsection

@section('scripts')
<script>
function initInlineUploadPreview(inputId, uploadContentId, previewDivId, maxSizeMB) {
    const input = document.getElementById(inputId);
    const uploadContent = document.getElementById(uploadContentId);
    const previewDiv = document.getElementById(previewDivId);
    const previewImg = previewDiv ? previewDiv.querySelector('img') : null;
    if (!input || !uploadContent || !previewDiv || !previewImg) return;
    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const maxSize = maxSizeMB * 1024 * 1024;
        if (file.size > maxSize) {
            alert('Kích thước ảnh vượt quá ' + maxSizeMB + 'MB.');
            e.target.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(ev) {
            uploadContent.classList.add('hidden');
            previewImg.src = ev.target.result;
            previewDiv.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });
    previewDiv.addEventListener('click', function() { input.click(); });
    previewDiv.style.cursor = 'pointer';
}

// Initialize inline previews
initInlineUploadPreview('shop_logo_input', 'shop_logo_upload_content', 'shop_logo_preview', 2);
initInlineUploadPreview('shop_banner_input', 'shop_banner_upload_content', 'shop_banner_preview', 4);
</script>
@endsection