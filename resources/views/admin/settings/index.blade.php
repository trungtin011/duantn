@extends('layouts.admin')

@section('title', 'Cài đặt')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Main content container -->
    <div class="pb-10 mx-auto">
        <div class="admin-page-header">
            <h1 class="admin-page-title">Cài đặt</h1>
            <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / Cài đặt chung
            </div>
        </div>
        <!-- Menu -->
        <div class="mb-6">
            <ul class="flex flex-wrap gap-2 border-b border-gray-200">
                <li><a href="#"
                        class="inline-block px-4 py-2 font-semibold text-gray-700 hover:text-blue-600 border-b-2 border-blue-600">Tổng
                        quan</a></li>
                <li><a href="{{ route('admin.settings.emails') }}"
                        class="inline-block px-4 py-2 font-semibold text-gray-700 hover:text-blue-600">Emails</a></li>
                <li><a href="{{ route('admin.password') }}"
                        class="inline-block px-4 py-2 font-semibold text-gray-700 hover:text-blue-600">Mật khẩu</a></li>
            </ul>
        </div>
        <div class="bg-white p-6 rounded-lg rounded-lg shadow-md max-w-2xl">
            <div class="mb-10">
                <h2 class="text-lg font-semibold text-gray-800">Cài đặt chung</h2>
                <p class="text-sm text-gray-600">Quản lý các cài đặt cơ bản cho trang web của bạn.</p>
            </div>
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
                    <label class="w-40 text-sm font-semibold text-gray-700" for="logo">Logo trang web</label>
                    <div class="flex flex-col gap-2">
                        <div class="relative flex flex-col">
                            <img src="{{ $settings['logo'] ? asset('storage/' . $settings['logo']) : asset('images/upload.png') }}"
                                alt="logo" id="logo-preview" class="cursor-pointer w-32 h-auto"
                                onclick="confirmUpload('logo-input', 'logo')">
                            <input class="hidden" id="logo-input" type="file" name="logo"
                                onchange="previewImage(event)" />
                            <div class="flex gap-2 mt-2">
                                @if ($settings['logo'])
                                    <button type="button" class="text-xs hover:underline h-[24px] text-blue-600"
                                        onclick="confirmUpload('logo-input', 'logo')">
                                        Sửa
                                    </button>
                                    <button type="button" class="text-xs hover:underline h-[24px] text-blue-600"
                                        onclick="confirmDelete('logo')">
                                        Xóa
                                    </button>
                                @else
                                    <button type="button"
                                        class="px-2 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700"
                                        onclick="confirmUpload('logo-input', 'logo')">
                                        Thêm
                                    </button>
                                @endif
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 max-w-lg">Tải lên logo trang web của bạn. Kích thước tối ưu là
                            200x200px.</p>
                    </div>
                </div>

                <!-- Banner Image -->
                <div class="flex items-center space-x-4 space-y-6">
                    <label class="w-40 text-sm font-semibold text-gray-700" for="banner-image">Hình ảnh banner</label>
                    <div class="flex flex-col gap-2">
                        <div class="relative flex flex-col">
                            <img src="{{ $settings['banner_image'] ? asset('storage/' . $settings['banner_image']) : asset('images/upload.png') }}"
                                alt="banner" id="banner-preview" class="cursor-pointer w-32 h-auto"
                                onclick="confirmUpload('banner-input', 'banner')">
                            <input class="hidden" id="banner-input" type="file" name="banner_image"
                                onchange="previewImage(event, 'banner-preview')" />
                            <div class="flex gap-2 mt-2">
                                @if ($settings['banner_image'])
                                    <button type="button" class="text-xs hover:underline h-[24px] text-blue-600"
                                        onclick="confirmUpload('banner-input', 'banner')">
                                        Sửa
                                    </button>
                                    <button type="button" class="text-xs hover:underline h-[24px] text-blue-600"
                                        onclick="confirmDelete('banner')">
                                        Xóa
                                    </button>
                                @else
                                    <button type="button"
                                        class="px-2 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700"
                                        onclick="confirmUpload('banner-input', 'banner')">
                                        Thêm
                                    </button>
                                @endif
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 max-w-lg">Tải lên hình ảnh banner. Kích thước tối ưu là
                            1200x400px.
                        </p>
                    </div>
                </div>

                <!-- Favicon -->
                <div class="flex items-center space-x-4 space-y-6">
                    <label class="w-40 text-sm font-semibold text-gray-700" for="favicon">Favicon</label>
                    <div class="flex flex-col gap-2">
                        <div class="relative flex flex-col">
                            <img src="{{ $settings['favicon'] ? asset('storage/' . $settings['favicon']) : asset('images/upload.png') }}"
                                alt="favicon" id="favicon-preview" class="cursor-pointer w-32 h-auto"
                                onclick="confirmUpload('favicon-input', 'favicon')">
                            <input class="hidden" id="favicon-input" type="file" name="favicon"
                                onchange="previewImage(event, 'favicon-preview')" />
                            <div class="flex items-center gap-2 mt-2">
                                @if ($settings['favicon'])
                                    <button type="button" class="text-xs hover:underline h-[24px] text-blue-600"
                                        onclick="confirmUpload('favicon-input', 'favicon')">
                                        Sửa
                                    </button>
                                    <button type="button" class="text-xs hover:underline h-[24px] text-blue-600"
                                        onclick="confirmDelete('favicon')">
                                        Xóa
                                    </button>
                                @else
                                    <button type="button"
                                        class="px-2 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700"
                                        onclick="confirmUpload('favicon-input', 'favicon')">
                                        Thêm
                                    </button>
                                @endif
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 max-w-lg">Tải lên favicon. Kích thước tối ưu là 16x16px hoặc
                            32x32px.</p>
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
        </div>
    </div>
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

        // SweetAlert xác nhận xóa
        function confirmDelete(type) {
            let text = '';
            let route = '';
            
            if (type === 'logo') {
                text = 'Bạn có chắc chắn muốn xóa logo này?';
                route = '{{ route("admin.settings.destroyLogo") }}';
            }
            if (type === 'banner') {
                text = 'Bạn có chắc chắn muốn xóa banner này?';
                route = '{{ route("admin.settings.destroyBanner") }}';
            }
            if (type === 'favicon') {
                text = 'Bạn có chắc chắn muốn xóa favicon này?';
                route = '{{ route("admin.settings.destroyFavicon") }}';
            }

            Swal.fire({
                title: 'Xác nhận xóa',
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tạo form ẩn để submit DELETE request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = route;
                    
                    // Thêm CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    // Thêm method DELETE
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    
                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // SweetAlert xác nhận khi nhấn Thêm/Sửa (nếu muốn)
        function confirmUpload(inputId, type) {
            let text = '';
            if (type === 'logo') text = 'Bạn muốn tải lên logo mới?';
            if (type === 'banner') text = 'Bạn muốn tải lên banner mới?';
            if (type === 'favicon') text = 'Bạn muốn tải lên favicon mới?';

            Swal.fire({
                title: 'Xác nhận tải lên',
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4CAF50',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Tải lên',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(inputId).click();
                }
            });
        }
    </script>
@endsection
