@extends('layouts.admin')

@section('content')
    <!-- Main content container -->
    <main class="pb-10 px-6 mx-auto">
        <h1 class="text-gray-700 text-lg font-normal mb-4">
            Thêm mới cài đặt
        </h1>
        <form action="{{ route('admin.settings.update') }}" method="post" class="space-y-6 max-w-3xl"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Tiêu đề trang -->
            <div class="flex items-center space-x-4">
                <label class="w-40 text-sm font-semibold text-gray-700" for="site-title">
                    Tiêu đề trang
                </label>
                <input
                    class="flex-grow border border-gray-300 rounded px-2 py-1 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-600"
                    id="site-title" type="text" name="site_title"
                    value="{{ old('site_title', $settings['site_title'] ?? '') }}" />
            </div>

            <!-- Dòng mô tả -->
            <div class="flex items-start space-x-4">
                <label class="w-40 text-sm font-semibold text-gray-700 pt-1" for="tagline">
                    Dòng mô tả
                </label>
                <div class="flex-grow">
                    <input
                        class="w-full border border-gray-300 rounded px-2 py-1 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-600"
                        id="tagline" type="text" name="tagline"
                        value="{{ old('tagline', $settings['tagline'] ?? '') }}" />
                    <p class="text-xs text-gray-500 mt-1 max-w-lg">
                        Trong vài từ, giải thích nội dung trang web này.
                    </p>
                </div>
            </div>

            <!-- Logo -->
            <div class="flex items-center space-x-4 space-y-6">
                <label class="w-40 text-sm font-semibold text-gray-700" for="logo">
                    Logo trang web
                </label>
                <div class="flex flex-col gap-4">
                    <div class="relative">
                        <img src="{{ $settings['logo'] ? asset('storage/' . $settings['logo']) : asset('images/upload.png') }}"
                            alt="logo" id="logo-preview" class="cursor-pointer w-32 h-auto"
                            onclick="document.getElementById('logo-input').click();">
                        <input class="hidden" id="logo-input" type="file" name="logo"
                            onchange="previewImage(event)" />
                    </div>
                    <p class="text-xs text-gray-500 mt-1 max-w-lg">
                        Tải lên logo trang web của bạn. Kích thước tối ưu là 200x200px.
                    </p>
                </div>
            </div>

            <!-- Banner Image -->
            <div class="flex items-center space-x-4 space-y-6">
                <label class="w-40 text-sm font-semibold text-gray-700" for="banner-image">
                    Hình ảnh banner
                </label>
                <div class="flex flex-col gap-4">
                    <div class="relative">
                        <img src="{{ $settings['banner_image'] ? asset('storage/' . $settings['banner_image']) : asset('images/upload.png') }}"
                            alt="banner" id="banner-preview" class="cursor-pointer w-32 h-auto"
                            onclick="document.getElementById('banner-input').click();">
                        <input class="hidden" id="banner-input" type="file" name="banner_image"
                            onchange="previewImage(event, 'banner-preview')" />
                    </div>
                    <p class="text-xs text-gray-500 mt-1 max-w-lg">
                        Tải lên hình ảnh banner. Kích thước tối ưu là 1200x400px.
                    </p>
                </div>
            </div>

            <!-- Favicon -->
            <div class="flex items-center space-x-4 space-y-6">
                <label class="w-40 text-sm font-semibold text-gray-700" for="favicon">
                    Favicon
                </label>
                <div class="flex flex-col gap-4">
                    <div class="relative">
                        <img src="{{ $settings['favicon'] ? asset('storage/' . $settings['favicon']) : asset('images/upload.png') }}"
                            alt="favicon" id="favicon-preview" class="cursor-pointer w-32 h-auto"
                            onclick="document.getElementById('favicon-input').click();">
                        <input class="hidden" id="favicon-input" type="file" name="favicon"
                            onchange="previewImage(event, 'favicon-preview')" />
                    </div>
                    <p class="text-xs text-gray-500 mt-1 max-w-lg">
                        Tải lên favicon. Kích thước tối ưu là 16x16px hoặc 32x32px.
                    </p>
                </div>
            </div>

            <!-- Nút Lưu thay đổi -->
            <div>
                <button
                    class="bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-600"
                    type="submit">
                    Lưu thay đổi
                </button>
            </div>
        </form>
    </main>
@endsection

@section('scripts')
    <script>
        // Hàm preview ảnh khi chọn file
        function previewImage(event, previewId = 'logo-preview') {
            const file = event.target.files[0];
            const preview = document.getElementById(previewId);
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
