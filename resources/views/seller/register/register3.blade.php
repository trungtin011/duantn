@extends('layouts.seller')

@section('content')
<!-- Add CSRF token meta tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">

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
                        <form action="{{ route('seller.register.step4.post') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
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
                                            Vui lòng cung cấp ảnh mặt trước và mặt sau CCCD/CMND. Hệ thống sẽ tự động quét và điền thông tin vào form.
                                        </p>
                                    </div>
                                </div>
                        </div>

                        <!-- Định danh Chủ Shop đã điền -->
                        @if(auth()->check() && auth()->user()->fullname)
                        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-user-check text-green-500 mt-1"></i>
                                <div>
                                    <h3 class="font-semibold text-green-900 mb-2">Định danh Chủ Shop</h3>
                                    <div class="text-sm text-green-800 space-y-1">
                                        <p><strong>Họ và tên:</strong> {{ auth()->user()->fullname }}</p>
                                        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                                        @if(auth()->user()->phone)
                                            <p><strong>Số điện thoại:</strong> {{ auth()->user()->phone }}</p>
                                        @endif
                                        @if(auth()->user()->birthday)
                                            <p><strong>Ngày sinh:</strong> {{ auth()->user()->birthday->format('d/m/Y') }}</p>
                                        @endif
                                        @if(auth()->user()->gender)
                                            <p><strong>Giới tính:</strong> {{ auth()->user()->gender == 'male' ? 'Nam' : (auth()->user()->gender == 'female' ? 'Nữ' : 'Khác') }}</p>
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
                                        <input type="radio" name="id_type" value="cccd" class="sr-only peer" {{ old('id_type', 'cccd') == 'cccd' ? 'checked' : '' }}>
                                        <div class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all duration-200">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-purple-500 peer-checked:bg-purple-500 flex items-center justify-center">
                                                    <div class="w-2 h-2 bg-white rounded-full hidden peer-checked:block"></div>
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-gray-900">CCCD</div>
                                                    <div class="text-sm text-gray-500">Căn cước công dân</div>
                                                </div>
                                            </div>
                                        </div>
                                </label>
                                    
                                    <label class="relative">
                                        <input type="radio" name="id_type" value="cmnd" class="sr-only peer" {{ old('id_type') == 'cmnd' ? 'checked' : '' }}>
                                        <div class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all duration-200">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-purple-500 peer-checked:bg-purple-500 flex items-center justify-center">
                                                    <div class="w-2 h-2 bg-white rounded-full hidden peer-checked:block"></div>
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
                                            <input type="file" name="file" accept="image/*" class="hidden" id="filechoose" 
                                                   {{ !old('cccd_image') ? 'required' : '' }}>
                                            <!-- Hidden input to store uploaded image path -->
                                            <input type="hidden" name="cccd_image" id="cccd_image" value="{{ old('cccd_image') }}">
                                            <label for="filechoose" 
                                                   class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-purple-500 transition-colors duration-200 bg-gray-50">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <i class="fas fa-camera text-3xl text-gray-400 mb-2"></i>
                                                    <p class="mb-2 text-sm text-gray-500">
                                                        <span class="font-semibold">Click để tải lên</span> hoặc kéo thả
                                                    </p>
                                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG (Tối đa 5MB)</p>
                                                </div>
                                            </label>
                                        </div>
                                        <!-- Preview image: ưu tiên old('cccd_image') nếu có -->
                                        @if(old('cccd_image'))
                                            <img id="filepreview" src="{{ asset(old('cccd_image')) }}" alt="Preview" class="w-full h-32 object-cover rounded-lg shadow-md mt-2" />
                                        @else
                                            <img id="filepreview" src="#" alt="Preview" class="hidden w-full h-32 object-cover rounded-lg shadow-md mt-2" />
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
                                            <input type="file" name="backfile" accept="image/*" class="hidden" id="backfilechoose" 
                                                   {{ !old('back_cccd_image') ? 'required' : '' }}>
                                            <!-- Hidden input to store uploaded back image path -->
                                            <input type="hidden" name="back_cccd_image" id="back_cccd_image" value="{{ old('back_cccd_image') }}">
                                            <label for="backfilechoose" 
                                                   class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-purple-500 transition-colors duration-200 bg-gray-50">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <i class="fas fa-camera text-3xl text-gray-400 mb-2"></i>
                                                    <p class="mb-2 text-sm text-gray-500">
                                                        <span class="font-semibold">Click để tải lên</span> hoặc kéo thả
                                                    </p>
                                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG (Tối đa 5MB)</p>
                                                </div>
                                            </label>
                                        </div>
                                        <!-- Preview image: ưu tiên old('back_cccd_image') nếu có -->
                                        @if(old('back_cccd_image'))
                                            <img id="backfilepreview" src="{{ asset(old('back_cccd_image')) }}" alt="Preview" class="w-full h-32 object-cover rounded-lg shadow-md mt-2" />
                                        @else
                                            <img id="backfilepreview" src="#" alt="Preview" class="hidden w-full h-32 object-cover rounded-lg shadow-md mt-2" />
                                        @endif
                                        @error('backfile')
                                            <p class="text-red-500 text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Scan and Manual Input Buttons -->
                                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                                    <button type="button" id="scan-cccd-btn" 
                                            class="inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                                        <i class="fas fa-search mr-3"></i>
                                        Quét và tự động điền thông tin
                                    </button>
                                    <button type="button" id="manual-input-btn" 
                                            class="inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-400 to-purple-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                                        <i class="fas fa-edit mr-3"></i>
                                        Nhập thủ công
                                    </button>
                                </div>
                                
                                <div id="scan-cccd-loading" class="hidden text-center">
                                    <div class="inline-flex items-center space-x-2">
                                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-purple-500"></div>
                                        <span class="text-gray-600">Đang quét và xử lý thông tin...</span>
                                    </div>
                                </div>
                                <div id="scan-cccd-error" class="text-red-500 text-sm text-center"></div>
                                <div id="scan-cccd-success" class="text-green-600 text-sm text-center hidden"></div>
                            </div>

                            <!-- Hidden fields to preserve scanned data -->
                            <input type="hidden" name="scanned_full_name" id="scanned_full_name" value="{{ old('scanned_full_name') }}">
                            <input type="hidden" name="scanned_id_number" id="scanned_id_number" value="{{ old('scanned_id_number') }}">
                            <input type="hidden" name="scanned_birthday" id="scanned_birthday" value="{{ old('scanned_birthday') }}">
                            <input type="hidden" name="scanned_nationality" id="scanned_nationality" value="{{ old('scanned_nationality') }}">
                            <input type="hidden" name="scanned_residence" id="scanned_residence" value="{{ old('scanned_residence') }}">
                            <input type="hidden" name="scanned_hometown" id="scanned_hometown" value="{{ old('scanned_hometown') }}">
                            <input type="hidden" name="scanned_gender" id="scanned_gender" value="{{ old('scanned_gender') }}">
                            <input type="hidden" name="scanned_identity_card_date" id="scanned_identity_card_date" value="{{ old('scanned_identity_card_date') }}">
                            <input type="hidden" name="scanned_identity_card_place" id="scanned_identity_card_place" value="{{ old('scanned_identity_card_place') }}">
                            <input type="hidden" name="scanned_dac_diem_nhan_dang" id="scanned_dac_diem_nhan_dang" value="{{ old('scanned_dac_diem_nhan_dang') }}">

                            <!-- Personal Information (Hidden initially, shown after scan) -->
                            <div id="personal-info-section" class="hidden space-y-6">
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
                                                placeholder="Nhập họ và tên theo CCCD/CMND" value="{{ old('full_name') }}">
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
                                                    <input type="radio" name="gender" value="male" {{ old('gender') == 'male' ? 'checked' : '' }} class="text-purple-500">
                                                    <span>Nam</span>
                                                </label>
                                                <label class="flex items-center space-x-2">
                                                    <input type="radio" name="gender" value="female" {{ old('gender') == 'female' ? 'checked' : '' }} class="text-purple-500">
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
                                                placeholder="Nhập quê quán theo CCCD/CMND" value="{{ old('hometown') }}">
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
                                                placeholder="Nhập nơi thường trú theo CCCD/CMND" value="{{ old('residence') }}">
                                            @error('residence')
                                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                    </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Identity Card Information (Hidden initially, shown after scan) -->
                            <div id="identity-info-section" class="hidden space-y-6">
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
                                                placeholder="Nhập nơi cấp CCCD/CMND" value="{{ old('identity_card_place') }}">
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
                                                placeholder="Nhập đặc điểm nhận dạng (nếu có)" value="{{ old('dac_diem_nhan_dang') }}">
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
                                        <a href="#" class="text-purple-600 hover:underline font-semibold">Chính Sách Bảo Mật</a>.
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
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
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
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
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
            const preview = document.getElementById('filepreview');
            const hiddenInput = document.getElementById('cccd_image');
            
            if (file) {
                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    alert('Kích thước tệp vượt quá 5MB. Vui lòng chọn tệp nhỏ hơn.');
                    e.target.value = '';
                    preview.classList.add('hidden');
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
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Lưu đường dẫn ảnh vào input hidden
                        hiddenInput.value = data.path;
                        // Hiển thị preview từ server
                        preview.src = data.url;
                        preview.classList.remove('hidden');
                        // Bỏ required ở input file vì đã có ảnh
                        e.target.removeAttribute('required');
                    } else {
                        alert('Lỗi upload ảnh: ' + data.message);
                        e.target.value = '';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Lỗi upload ảnh. Vui lòng thử lại.');
                    e.target.value = '';
                });
            } else {
                preview.classList.add('hidden');
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
            const preview = document.getElementById('backfilepreview');
            const hiddenInput = document.getElementById('back_cccd_image');
            
            if (file) {
                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    alert('Kích thước tệp vượt quá 5MB. Vui lòng chọn tệp nhỏ hơn.');
                    e.target.value = '';
                    preview.classList.add('hidden');
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
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Lưu đường dẫn ảnh vào input hidden
                        hiddenInput.value = data.path;
                        // Hiển thị preview từ server
                        preview.src = data.url;
                        preview.classList.remove('hidden');
                        // Bỏ required ở input file vì đã có ảnh
                        e.target.removeAttribute('required');
                    } else {
                        alert('Lỗi upload ảnh: ' + data.message);
                        e.target.value = '';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Lỗi upload ảnh. Vui lòng thử lại.');
                    e.target.value = '';
                });
            } else {
                preview.classList.add('hidden');
                hiddenInput.value = '';
                // Thêm lại required nếu không có ảnh
                if (!hiddenInput.value) {
                    e.target.setAttribute('required', '');
                }
            }
        });

// CCCD scan integration
        const scanBtn = document.getElementById('scan-cccd-btn');
        const manualBtn = document.getElementById('manual-input-btn');
        const fileInput = document.getElementById('filechoose');
        const loadingDiv = document.getElementById('scan-cccd-loading');
        const errorDiv = document.getElementById('scan-cccd-error');
        const successDiv = document.getElementById('scan-cccd-success');
        const personalInfoSection = document.getElementById('personal-info-section');
        const identityInfoSection = document.getElementById('identity-info-section');

        scanBtn.addEventListener('click', function() {
            errorDiv.textContent = '';
    successDiv.classList.add('hidden');
    successDiv.textContent = '';
    
            if (!fileInput.files[0] || !document.getElementById('backfilechoose').files[0]) {
        errorDiv.textContent = 'Vui lòng chọn đủ ảnh mặt trước và mặt sau CCCD/CMND trước khi quét.';
                return;
            }
    
            const formData = new FormData();
            formData.append('front_image', fileInput.files[0]);
            formData.append('back_image', document.getElementById('backfilechoose').files[0]);
    
    loadingDiv.classList.remove('hidden');
            scanBtn.disabled = true;
    
            fetch('http://127.0.0.1:5000/process_cccd', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
        loadingDiv.classList.add('hidden');
                scanBtn.disabled = false;
        
                if (data.error) {
                    errorDiv.textContent = data.error;
                    return;
                }
        
        // Fill form data
        fillFormData(data);
        
        // Show the sections
        personalInfoSection.classList.remove('hidden');
        identityInfoSection.classList.remove('hidden');
        
        successDiv.textContent = 'Quét thành công! Thông tin đã được điền tự động. Bạn có thể chỉnh sửa nếu cần thiết.';
        successDiv.classList.remove('hidden');
        
        // Scroll to the filled sections
        personalInfoSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    })
    .catch(err => {
        loadingDiv.classList.add('hidden');
        scanBtn.disabled = false;
        errorDiv.textContent = 'Không thể quét CCCD/CMND. Vui lòng thử lại.';
    });
});

// Manual input button handler
manualBtn.addEventListener('click', function() {
    errorDiv.textContent = '';
    successDiv.classList.add('hidden');
    successDiv.textContent = '';
    
    // Show the sections for manual input
    personalInfoSection.classList.remove('hidden');
    identityInfoSection.classList.remove('hidden');
    
    // Clear any existing scanned data
    const scannedFields = [
        'scanned_full_name', 'scanned_id_number', 'scanned_birthday', 
        'scanned_nationality', 'scanned_residence', 'scanned_hometown',
        'scanned_identity_card_date', 'scanned_identity_card_place', 
        'scanned_dac_diem_nhan_dang', 'scanned_gender'
    ];
    
    scannedFields.forEach(field => {
        const hiddenInput = document.querySelector(`input[name="${field}"]`);
        if (hiddenInput) {
            hiddenInput.value = '';
        }
    });
    
    // Clear form fields to allow manual input
    const formFields = [
        'full_name', 'id_number', 'birthday', 'nationality', 'residence', 
        'hometown', 'identity_card_date', 'identity_card_place', 'dac_diem_nhan_dang'
    ];
    
    const placeholders = {
        'full_name': 'Nhập họ và tên theo CCCD/CMND',
        'id_number': 'Nhập số CCCD/CMND',
        'birthday': '',
        'nationality': 'Ví dụ: Việt Nam',
        'residence': 'Nhập nơi thường trú theo CCCD/CMND',
        'hometown': 'Nhập quê quán theo CCCD/CMND',
        'identity_card_date': '',
        'identity_card_place': 'Nhập nơi cấp CCCD/CMND',
        'dac_diem_nhan_dang': 'Nhập đặc điểm nhận dạng (nếu có)'
    };
    
    formFields.forEach(field => {
        const input = document.querySelector(`input[name="${field}"]`);
        if (input) {
            input.value = '';
            if (placeholders[field]) {
                input.placeholder = placeholders[field];
            }
        }
    });
    
    // Clear gender selection
    const genderRadios = document.querySelectorAll('input[name="gender"]');
    genderRadios.forEach(radio => {
        radio.checked = false;
    });
    
    successDiv.textContent = 'Chế độ nhập thủ công đã được kích hoạt. Vui lòng điền thông tin vào các trường bên dưới.';
    successDiv.classList.remove('hidden');
    
    // Scroll to the sections
    personalInfoSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
});

// Fill form data from CCCD scan
function fillFormData(data) {
    const fieldMappings = {
        'full_name': 'full_name',
        'identity_number': 'id_number',
        'birth_date': 'birthday',
        'nationality': 'nationality',
        'residence': 'residence',
        'hometown': 'hometown',
        'identity_card_date': 'identity_card_date',
        'identity_card_place': 'identity_card_place',
        'dac_diem_nhan_dang': 'dac_diem_nhan_dang'
    };

    Object.entries(fieldMappings).forEach(([apiField, formField]) => {
        if (data[apiField]) {
            const input = document.querySelector(`input[name="${formField}"]`);
            const hiddenInput = document.querySelector(`input[name="scanned_${formField}"]`);
            if (input) {
                if (apiField === 'birth_date' || apiField === 'identity_card_date') {
                    // Handle date formatting
                    const date = new Date(data[apiField]);
                    if (!isNaN(date.getTime())) {
                        const formattedDate = date.toISOString().split('T')[0];
                        input.value = formattedDate;
                        if (hiddenInput) hiddenInput.value = formattedDate;
                    }
                } else {
                    input.value = data[apiField];
                    if (hiddenInput) hiddenInput.value = data[apiField];
                }
            }
        }
    });

    // Handle gender
                if (data.gender) {
                    const radios = document.querySelectorAll('input[name="gender"]');
        const hiddenGender = document.querySelector('input[name="scanned_gender"]');
                    radios.forEach(radio => {
            if (radio.value === data.gender.toLowerCase()) {
                radio.checked = true;
                if (hiddenGender) hiddenGender.value = data.gender.toLowerCase();
            }
        });
    }
}

// Check if there's existing scanned data on page load
document.addEventListener('DOMContentLoaded', function() {
    const hasScannedData = document.querySelector('input[name="scanned_full_name"]').value ||
                          document.querySelector('input[name="scanned_id_number"]').value;
    
    if (hasScannedData) {
        // Restore scanned data to visible fields
        const scannedFields = [
            'scanned_full_name', 'scanned_id_number', 'scanned_birthday', 
            'scanned_nationality', 'scanned_residence', 'scanned_hometown',
            'scanned_identity_card_date', 'scanned_identity_card_place', 
            'scanned_dac_diem_nhan_dang'
        ];
        
        scannedFields.forEach(field => {
            const hiddenInput = document.querySelector(`input[name="${field}"]`);
            const visibleInput = document.querySelector(`input[name="${field.replace('scanned_', '')}"]`);
            if (hiddenInput && hiddenInput.value && visibleInput) {
                visibleInput.value = hiddenInput.value;
            }
        });
        
        // Restore gender
        const scannedGender = document.querySelector('input[name="scanned_gender"]').value;
        if (scannedGender) {
            const radios = document.querySelectorAll('input[name="gender"]');
            radios.forEach(radio => {
                if (radio.value === scannedGender) {
                    radio.checked = true;
                }
            });
        }
        
        // Show the sections
        personalInfoSection.classList.remove('hidden');
        identityInfoSection.classList.remove('hidden');
        
        successDiv.textContent = 'Thông tin đã quét trước đó được khôi phục.';
        successDiv.classList.remove('hidden');
    }
});

// Preview ảnh mặt trước, ưu tiên old('cccd_image')
document.addEventListener('DOMContentLoaded', function() {
    var oldCccdImage = document.getElementById('cccd_image').value;
    var preview = document.getElementById('filepreview');
    if (oldCccdImage) {
        preview.src = '/' + oldCccdImage.replace(/^\/+/, '');
        preview.classList.remove('hidden');
    }
    
    // Preview ảnh mặt sau, ưu tiên old('back_cccd_image')
    var oldBackCccdImage = document.getElementById('back_cccd_image').value;
    var backPreview = document.getElementById('backfilepreview');
    if (oldBackCccdImage) {
        backPreview.src = '/' + oldBackCccdImage.replace(/^\/+/, '');
        backPreview.classList.remove('hidden');
    }
});
</script>
@endsection