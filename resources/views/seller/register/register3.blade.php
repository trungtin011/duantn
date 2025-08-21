@extends('layouts.seller')

@section('content')
    <!-- Add CSRF token meta tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-8">
        <div class="">
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
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-purple-400 to-pink-500 px-8 py-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                    <i class="fas fa-id-card text-white text-xl"></i>
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold text-white">Thông tin Định danh</h1>
                                    <p class="text-white/80">Bước 3/4 - Xác thực danh tính</p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Content -->
                        <div class="p-8">
                            <form action="{{ route('seller.register.step4.post') }}" method="POST"
                                enctype="multipart/form-data" class="space-y-8">
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

                                <!-- Info Alert -->
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                                    <div class="flex items-start space-x-3">
                                        <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                                        <div>
                                            <h3 class="font-semibold text-blue-900 mb-2">Thông tin quan trọng</h3>
                                            <p class="text-sm text-blue-800">
                                                Vui lòng cung cấp ảnh mặt trước và mặt sau CCCD/CMND. Sau đó nhấn nút "Nhập thủ công" để điền thông tin vào form.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Định danh Chủ Shop đã điền -->
                                @if (auth()->check() && auth()->user()->fullname)
                                    <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                                        <div class="flex items-start space-x-3">
                                            <i class="fas fa-user-check text-green-500 mt-1"></i>
                                            <div>
                                                <h3 class="font-semibold text-green-900 mb-2">Định danh Chủ Shop</h3>
                                                <div class="text-sm text-green-800 space-y-1">
                                                    <p><strong>Họ và tên:</strong> {{ auth()->user()->fullname }}</p>
                                                    <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                                                    @if (auth()->user()->phone)
                                                        <p><strong>Số điện thoại:</strong> {{ auth()->user()->phone }}</p>
                                                    @endif
                                                    @if (auth()->user()->birthday)
                                                        <p><strong>Ngày sinh:</strong>
                                                            {{ auth()->user()->birthday->format('d/m/Y') }}</p>
                                                    @endif
                                                    @if (auth()->user()->gender)
                                                        <p><strong>Giới tính:</strong>
                                                            {{ auth()->user()->gender == 'male' ? 'Nam' : (auth()->user()->gender == 'female' ? 'Nữ' : 'Khác') }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Identity Type -->
                                <div class="space-y-4">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        <i class="fas fa-id-card mr-2 text-purple-500"></i>
                                        Hình thức định danh <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid md:grid-cols-3 gap-4">
                                        <label class="relative">
                                            <input type="radio" name="id_type" value="cccd" class="sr-only peer"
                                                {{ old('id_type', 'cccd') == 'cccd' ? 'checked' : '' }}>
                                            <div
                                                class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all duration-200">
                                                <div class="flex items-center space-x-3">
                                                    <div
                                                        class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-purple-500 peer-checked:bg-purple-500 flex items-center justify-center">
                                                        <div
                                                            class="w-2 h-2 bg-white rounded-full hidden peer-checked:block">
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="font-semibold text-gray-900">CCCD</div>
                                                        <div class="text-sm text-gray-500">Căn cước công dân</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>

                                        <label class="relative">
                                            <input type="radio" name="id_type" value="cmnd" class="sr-only peer"
                                                {{ old('id_type') == 'cmnd' ? 'checked' : '' }}>
                                            <div
                                                class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all duration-200">
                                                <div class="flex items-center space-x-3">
                                                    <div
                                                        class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-purple-500 peer-checked:bg-purple-500 flex items-center justify-center">
                                                        <div
                                                            class="w-2 h-2 bg-white rounded-full hidden peer-checked:block">
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="font-semibold text-gray-900">CMND</div>
                                                        <div class="text-sm text-gray-500">Chứng minh nhân dân</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>

                                    </div>
                                    @error('id_type')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Document Upload -->
                                <div class="space-y-6">
                                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <i class="fas fa-upload mr-2 text-purple-500"></i>
                                        Tải lên giấy tờ định danh
                                    </h3>

                                    <div class="grid md:grid-cols-2 gap-6">
                                        <!-- Front Side -->
                                        <div class="space-y-2">
                                            <label class="block text-sm font-semibold text-gray-700">
                                                Ảnh mặt trước CCCD/CMND <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <input type="file" name="file" accept="image/*" class="hidden"
                                                    id="filechoose" {{ !old('cccd_image') ? 'required' : '' }}>
                                                <!-- Hidden input to store uploaded image path -->
                                                <input type="hidden" name="cccd_image" id="cccd_image"
                                                    value="{{ old('cccd_image') }}">
                                                <label for="filechoose"
                                                    class="upload-area flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-purple-500 transition-colors duration-200 bg-gray-50">
                                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 {{ old('cccd_image') ? 'hidden' : '' }}" id="cccd_front_upload_content">
                                                        <i class="fas fa-camera text-3xl text-gray-400 mb-2"></i>
                                                        <p class="mb-2 text-sm text-gray-500">
                                                            <span class="font-semibold">Click để tải lên</span> hoặc kéo thả
                                                        </p>
                                                        <p class="text-xs text-gray-500">PNG, JPG, JPEG (Tối đa 5MB)</p>
                                                    </div>
                                                    <div id="cccd_front_preview" class="{{ old('cccd_image') ? 'w-full h-full flex items-center justify-center' : 'hidden w-full h-full flex items-center justify-center' }}">
                                                        <img src="{{ old('cccd_image') ? asset(old('cccd_image')) : '' }}" alt="Preview" class="max-w-full max-h-full object-contain rounded-lg">
                                                    </div>
                                                </label>
                                            </div>
                                            <!-- Preview image: ưu tiên old('cccd_image') nếu có -->
                                            @if (false)
                                                <img id="filepreview" src="#" alt="Preview" class="hidden" />
                                            @endif
                                            @error('file')
                                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Back Side -->
                                        <div class="space-y-2">
                                            <label class="block text-sm font-semibold text-gray-700">
                                                Ảnh mặt sau CCCD/CMND <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <input type="file" name="backfile" accept="image/*" class="hidden"
                                                    id="backfilechoose" {{ !old('back_cccd_image') ? 'required' : '' }}>
                                                <!-- Hidden input to store uploaded back image path -->
                                                <input type="hidden" name="back_cccd_image" id="back_cccd_image"
                                                    value="{{ old('back_cccd_image') }}">
                                                <label for="backfilechoose"
                                                    class="upload-area flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-purple-500 transition-colors duration-200 bg-gray-50">
                                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 {{ old('back_cccd_image') ? 'hidden' : '' }}" id="cccd_back_upload_content">
                                                        <i class="fas fa-camera text-3xl text-gray-400 mb-2"></i>
                                                        <p class="mb-2 text-sm text-gray-500">
                                                            <span class="font-semibold">Click để tải lên</span> hoặc kéo
                                                            thả
                                                        </p>
                                                        <p class="text-xs text-gray-500">PNG, JPG, JPEG (Tối đa 5MB)</p>
                                                    </div>
                                                    <div id="cccd_back_preview" class="{{ old('back_cccd_image') ? 'w-full h-full flex items-center justify-center' : 'hidden w-full h-full flex items-center justify-center' }}">
                                                        <img src="{{ old('back_cccd_image') ? asset(old('back_cccd_image')) : '' }}" alt="Preview" class="max-w-full max-h-full object-contain rounded-lg">
                                                    </div>
                                                </label>
                                            </div>
                                            <!-- Preview image: ưu tiên old('back_cccd_image') nếu có -->
                                            @if (false)
                                                <img id="backfilepreview" src="#" alt="Preview" class="hidden" />
                                            @endif
                                            @error('backfile')
                                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Personal Information (Always visible) -->
                                <div id="personal-info-section" class="space-y-6">
                                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <i class="fas fa-user mr-2 text-purple-500"></i>
                                        Thông tin cá nhân
                                    </h3>

                                    <div class="bg-gray-50 rounded-xl p-6 space-y-6">
                                        <div class="grid md:grid-cols-2 gap-6">
                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">
                                                    Họ và tên <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="full_name" maxlength="100" required
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                                    placeholder="Nhập họ và tên theo CCCD/CMND"
                                                    value="{{ old('full_name') }}">
                                                <p class="text-xs text-gray-500">Theo CMND/CCCD/Hộ Chiếu</p>
                                                @error('full_name')
                                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">
                                                    Giới tính <span class="text-red-500">*</span>
                                                </label>
                                                <div class="flex space-x-4">
                                                    <label class="flex items-center space-x-2">
                                                        <input type="radio" name="gender" value="male"
                                                            {{ old('gender') == 'male' ? 'checked' : '' }}
                                                            class="text-purple-500">
                                                        <span>Nam</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2">
                                                        <input type="radio" name="gender" value="female"
                                                            {{ old('gender') == 'female' ? 'checked' : '' }}
                                                            class="text-purple-500">
                                                        <span>Nữ</span>
                                                    </label>
                                                </div>
                                                @error('gender')
                                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">
                                                    Ngày sinh <span class="text-red-500">*</span>
                                                </label>
                                                <input type="date" name="birthday" required
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                                    value="{{ old('birthday') }}">
                                                @error('birthday')
                                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">
                                                    Quốc tịch <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="nationality" maxlength="50" required
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                                    placeholder="Ví dụ: Việt Nam" value="{{ old('nationality') }}">
                                                @error('nationality')
                                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">
                                                    Quê quán <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="hometown" maxlength="100" required
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                                    placeholder="Nhập quê quán theo CCCD/CMND"
                                                    value="{{ old('hometown') }}">
                                                @error('hometown')
                                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">
                                                    Nơi thường trú <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="residence" maxlength="100" required
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                                    placeholder="Nhập nơi thường trú theo CCCD/CMND"
                                                    value="{{ old('residence') }}">
                                                @error('residence')
                                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Identity Card Information (Always visible) -->
                                <div id="identity-info-section" class="space-y-6">
                                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <i class="fas fa-id-card mr-2 text-purple-500"></i>
                                        Thông tin CCCD/CMND
                                    </h3>

                                    <div class="bg-gray-50 rounded-xl p-6 space-y-6">
                                        <div class="grid md:grid-cols-2 gap-6">
                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">
                                                    Số CCCD/CMND <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="id_number" maxlength="20" required
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                                    placeholder="Nhập số CCCD/CMND" value="{{ old('id_number') }}">
                                                @error('id_number')
                                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">
                                                    Ngày cấp <span class="text-red-500">*</span>
                                                </label>
                                                <input type="date" name="identity_card_date" required
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                                    value="{{ old('identity_card_date') }}">
                                                @error('identity_card_date')
                                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">
                                                    Nơi cấp <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="identity_card_place" maxlength="255" required
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                                    placeholder="Nhập nơi cấp CCCD/CMND"
                                                    value="{{ old('identity_card_place') }}">
                                                @error('identity_card_place')
                                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">
                                                    Đặc điểm nhận dạng
                                                </label>
                                                <input type="text" name="dac_diem_nhan_dang" maxlength="255"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                                    placeholder="Nhập đặc điểm nhận dạng (nếu có)"
                                                    value="{{ old('dac_diem_nhan_dang') }}">
                                                @error('dac_diem_nhan_dang')
                                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Confirmation -->
                                <div class="bg-gray-50 rounded-xl p-6">
                                    <div class="flex items-start space-x-3">
                                        <input type="checkbox" id="confirm" name="confirm" required
                                            class="mt-1 w-4 h-4 text-purple-500 border-gray-300 rounded focus:ring-purple-500">
                                        <label for="confirm" class="text-sm text-gray-700">
                                            Tôi xác nhận tất cả dữ liệu đã cung cấp là chính xác và trung thực.
                                            Tôi đã đọc và đồng ý với
                                            <a href="#" class="text-purple-600 hover:underline font-semibold">Chính
                                                Sách Bảo Mật</a>.
                                        </label>
                                    </div>
                                    @error('confirm')
                                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex justify-between items-center pt-8 border-t border-gray-200">
                                    <button type="button" onclick="window.history.back()"
                                        class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition-all duration-200">
                                        <i class="fas fa-arrow-left mr-2"></i>
                                        Quay lại
                                    </button>
                                    <button type="submit"
                                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-400 to-pink-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
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
                        <!-- Info Card -->
                        <div class="bg-purple-50 border border-purple-200 rounded-2xl p-6">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-shield-alt text-purple-600"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-purple-900">Bảo mật thông tin</h3>
                            </div>
                            <p class="text-sm text-purple-800 leading-relaxed">
                                Thông tin định danh của bạn sẽ được mã hóa và bảo vệ theo tiêu chuẩn bảo mật cao nhất.
                                Chỉ được sử dụng cho mục đích xác thực.
                            </p>
                        </div>

                        <!-- Tips Card -->
                        <div class="bg-white rounded-2xl border border-gray-100 p-6">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-lightbulb text-yellow-600"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Lưu ý quan trọng</h3>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                    <p class="text-sm text-gray-600">Ảnh CCCD/CMND phải rõ ràng, không bị mờ</p>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                    <p class="text-sm text-gray-600">Hệ thống sẽ tự động quét và điền thông tin</p>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                    <p class="text-sm text-gray-600">Thông tin đã quét sẽ được giữ lại khi có lỗi</p>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Card -->
                        <div class="bg-white rounded-2xl border border-gray-100 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Tiến độ đăng ký</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Thông tin Shop</span>
                                    <span class="text-sm font-semibold text-green-500">Hoàn thành</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Thông tin Thuế</span>
                                    <span class="text-sm font-semibold text-green-500">Hoàn thành</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Định danh</span>
                                    <span class="text-sm font-semibold text-purple-500">Đang thực hiện</span>
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

    <script>
        // Upload ảnh mặt trước bằng AJAX
        document.getElementById('filechoose').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewDiv = document.getElementById('cccd_front_preview');
            const previewImg = previewDiv.querySelector('img');
            const uploadContent = document.getElementById('cccd_front_upload_content');
            const hiddenInput = document.getElementById('cccd_image');

            if (file) {
                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    alert('Kích thước tệp vượt quá 5MB. Vui lòng chọn tệp nhỏ hơn.');
                    e.target.value = '';
                    previewDiv.classList.add('hidden');
                    uploadContent.classList.remove('hidden');
                    return;
                }

                // Upload bằng AJAX
                const formData = new FormData();
                formData.append('file', file);
                formData.append('type', 'cccd_front');

                fetch('/api/upload-cccd-temp', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Lưu đường dẫn ảnh vào input hidden
                            hiddenInput.value = data.path;
                            // Hiển thị preview từ server ngay trong vùng upload
                            previewImg.src = data.url;
                            previewDiv.classList.remove('hidden');
                            uploadContent.classList.add('hidden');
                            // Bỏ required ở input file vì đã có ảnh
                            e.target.removeAttribute('required');
                        } else {
                            alert('Lỗi upload ảnh: ' + data.message);
                            e.target.value = '';
                            previewDiv.classList.add('hidden');
                            uploadContent.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Lỗi upload ảnh. Vui lòng thử lại.');
                        e.target.value = '';
                        previewDiv.classList.add('hidden');
                        uploadContent.classList.remove('hidden');
                    });
            } else {
                previewDiv.classList.add('hidden');
                uploadContent.classList.remove('hidden');
                hiddenInput.value = '';
                // Thêm lại required nếu không có ảnh
                if (!hiddenInput.value) {
                    e.target.setAttribute('required', '');
                }
            }
        });

        // Upload ảnh mặt sau bằng AJAX
        document.getElementById('backfilechoose').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewDiv = document.getElementById('cccd_back_preview');
            const previewImg = previewDiv.querySelector('img');
            const uploadContent = document.getElementById('cccd_back_upload_content');
            const hiddenInput = document.getElementById('back_cccd_image');

            if (file) {
                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    alert('Kích thước tệp vượt quá 5MB. Vui lòng chọn tệp nhỏ hơn.');
                    e.target.value = '';
                    previewDiv.classList.add('hidden');
                    uploadContent.classList.remove('hidden');
                    return;
                }

                // Upload bằng AJAX
                const formData = new FormData();
                formData.append('file', file);
                formData.append('type', 'cccd_back');

                fetch('/api/upload-cccd-temp', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Lưu đường dẫn ảnh vào input hidden
                            hiddenInput.value = data.path;
                            // Hiển thị preview từ server ngay trong vùng upload
                            previewImg.src = data.url;
                            previewDiv.classList.remove('hidden');
                            uploadContent.classList.add('hidden');
                            // Bỏ required ở input file vì đã có ảnh
                            e.target.removeAttribute('required');
                        } else {
                            alert('Lỗi upload ảnh: ' + data.message);
                            e.target.value = '';
                            previewDiv.classList.add('hidden');
                            uploadContent.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Lỗi upload ảnh. Vui lòng thử lại.');
                        e.target.value = '';
                        previewDiv.classList.add('hidden');
                        uploadContent.classList.remove('hidden');
                    });
            } else {
                previewDiv.classList.add('hidden');
                uploadContent.classList.remove('hidden');
                hiddenInput.value = '';
                // Thêm lại required nếu không có ảnh
                if (!hiddenInput.value) {
                    e.target.setAttribute('required', '');
                }
            }
        });

        // Form sections are now always visible
        const personalInfoSection = document.getElementById('personal-info-section');
        const identityInfoSection = document.getElementById('identity-info-section');

        // Function to restore form data from old values
        function restoreFormData() {
            // Restore form fields from old values
            const formFields = [
                'full_name', 'id_number', 'birthday', 'nationality', 'residence', 
                'hometown', 'identity_card_date', 'identity_card_place', 'dac_diem_nhan_dang'
            ];
            
            formFields.forEach(field => {
                const input = document.querySelector(`input[name="${field}"]`);
                if (input && input.value) {
                    // Field already has old value, keep it
                    console.log(`Field ${field} restored from old value:`, input.value);
                }
            });
            
            // Restore gender selection
            const genderRadios = document.querySelectorAll('input[name="gender"]');
            genderRadios.forEach(radio => {
                if (radio.checked) {
                    console.log(`Gender restored: ${radio.value}`);
                }
            });
            
            // Restore ID type selection
            const idTypeRadios = document.querySelectorAll('input[name="id_type"]');
            idTypeRadios.forEach(radio => {
                if (radio.checked) {
                    console.log(`ID type restored: ${radio.value}`);
                }
            });
            
            // Form sections are always visible now
            console.log('Personal info sections are always visible');
        }

        // Function to save form data to localStorage
        function saveFormDataToLocalStorage() {
            const formData = {};
            
            // Save text inputs
            const textInputs = document.querySelectorAll('input[type="text"], input[type="date"]');
            textInputs.forEach(input => {
                if (input.name && input.value) {
                    formData[input.name] = input.value;
                }
            });
            
            // Save radio button selections
            const radioGroups = ['gender', 'id_type'];
            radioGroups.forEach(groupName => {
                const selectedRadio = document.querySelector(`input[name="${groupName}"]:checked`);
                if (selectedRadio) {
                    formData[groupName] = selectedRadio.value;
                }
            });
            
            // Save file inputs (paths)
            const fileInputs = ['cccd_image', 'back_cccd_image'];
            fileInputs.forEach(inputName => {
                const input = document.querySelector(`input[name="${inputName}"]`);
                if (input && input.value) {
                    formData[inputName] = input.value;
                }
            });
            
            try {
                localStorage.setItem('register3_form_data', JSON.stringify(formData));
                console.log('Dữ liệu biểu mẫu được lưu vào localStorage:', formData);
            } catch (error) {
                console.error('Lỗi khi lưu vào localStorage:', error);
            }
        }

        // Function to restore form data from localStorage
        function restoreFormDataFromLocalStorage() {
            try {
                const savedData = localStorage.getItem('register3_form_data');
                if (savedData) {
                    const formData = JSON.parse(savedData);
                    console.log('Restoring form data from localStorage:', formData);
                    
                    // Restore text inputs
                    Object.keys(formData).forEach(fieldName => {
                        if (fieldName === 'gender') {
                            // Handle gender
                            const radios = document.querySelectorAll('input[name="gender"]');
                            radios.forEach(radio => {
                                if (radio.value === formData[fieldName]) {
                                    radio.checked = true;
                                }
                            });
                        } else if (fieldName === 'id_type') {
                            // Handle ID type
                            const radios = document.querySelectorAll('input[name="id_type"]');
                            radios.forEach(radio => {
                                if (radio.value === formData[fieldName]) {
                                    radio.checked = true;
                                }
                            });
                        } else if (fieldName === 'cccd_image' || fieldName === 'back_cccd_image') {
                            // Handle image paths
                            const input = document.querySelector(`input[name="${fieldName}"]`);
                            if (input) {
                                input.value = formData[fieldName];
                            }
                        } else {
                            // Handle regular text/date inputs
                            const input = document.querySelector(`input[name="${fieldName}"]`);
                            if (input) {
                                input.value = formData[fieldName];
                            }
                        }
                    });
                    
                    // Form sections are always visible now
                    console.log('Form sections are always visible');
                    
                    // Restore image previews
                    restoreImagePreviews();
                    
                    return true;
                }
            } catch (error) {
                console.error('Error restoring from localStorage:', error);
                localStorage.removeItem('register3_form_data');
            }
            return false;
        }

        // Function to restore image previews
        function restoreImagePreviews() {
            // Restore front image preview
            const frontImagePath = document.getElementById('cccd_image').value;
            const frontPreviewDiv = document.getElementById('cccd_front_preview');
            const frontPreviewImg = frontPreviewDiv ? frontPreviewDiv.querySelector('img') : null;
            const frontUploadContent = document.getElementById('cccd_front_upload_content');
            if (frontImagePath && frontPreviewDiv && frontPreviewImg) {
                frontPreviewImg.src = '/' + frontImagePath.replace(/^\/+/, '');
                frontPreviewDiv.classList.remove('hidden');
                if (frontUploadContent) frontUploadContent.classList.add('hidden');
            }
            
            // Restore back image preview
            const backImagePath = document.getElementById('back_cccd_image').value;
            const backPreviewDiv = document.getElementById('cccd_back_preview');
            const backPreviewImg = backPreviewDiv ? backPreviewDiv.querySelector('img') : null;
            const backUploadContent = document.getElementById('cccd_back_upload_content');
            if (backImagePath && backPreviewDiv && backPreviewImg) {
                backPreviewImg.src = '/' + backImagePath.replace(/^\/+/, '');
                backPreviewDiv.classList.remove('hidden');
                if (backUploadContent) backUploadContent.classList.add('hidden');
            }
        }

        // Save form data on input changes
        document.addEventListener('input', function(e) {
            if (e.target.name && (e.target.type === 'text' || e.target.type === 'date')) {
                saveFormDataToLocalStorage();
            }
        });

        // Save form data on radio button changes
        document.addEventListener('change', function(e) {
            if (e.target.name && (e.target.type === 'radio' || e.target.type === 'file')) {
                saveFormDataToLocalStorage();
            }
        });

        // Clear localStorage when form is submitted successfully
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function() {
                    // Clear localStorage when form is submitted
                    try {
                        localStorage.removeItem('register3_form_data');
                        console.log('Form data cleared from localStorage on form submit');
                    } catch (error) {
                        console.error('Error clearing localStorage on form submit:', error);
                    }
                });
            }
        });

        // Check if there's existing scanned data on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Try to restore from localStorage first, then from old values
            const restoredFromLocalStorage = restoreFormDataFromLocalStorage();
            
            if (!restoredFromLocalStorage) {
                // Fallback to old values
                restoreFormData();
            }
            
            // Restore image previews
            restoreImagePreviews();
        });

        // Preview ảnh mặt trước, ưu tiên old('cccd_image')
        document.addEventListener('DOMContentLoaded', function() {
            var oldCccdImage = document.getElementById('cccd_image').value;
            var previewDiv = document.getElementById('cccd_front_preview');
            var previewImg = previewDiv ? previewDiv.querySelector('img') : null;
            var uploadContent = document.getElementById('cccd_front_upload_content');
            if (oldCccdImage && previewDiv && previewImg) {
                previewImg.src = '/' + oldCccdImage.replace(/^\/+/, '');
                previewDiv.classList.remove('hidden');
                if (uploadContent) uploadContent.classList.add('hidden');
            }

            // Preview ảnh mặt sau, ưu tiên old('back_cccd_image')
            var oldBackCccdImage = document.getElementById('back_cccd_image').value;
            var backPreviewDiv = document.getElementById('cccd_back_preview');
            var backPreviewImg = backPreviewDiv ? backPreviewDiv.querySelector('img') : null;
            var backUploadContent = document.getElementById('cccd_back_upload_content');
            if (oldBackCccdImage && backPreviewDiv && backPreviewImg) {
                backPreviewImg.src = '/' + oldBackCccdImage.replace(/^\/+/, '');
                backPreviewDiv.classList.remove('hidden');
                if (backUploadContent) backUploadContent.classList.add('hidden');
            }
        });
    </script>
@endsection
