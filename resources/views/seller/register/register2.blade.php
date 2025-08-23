@extends('layouts.seller')

@section('content')
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
                        <div class="bg-gradient-to-r from-blue-400 to-purple-500 px-8 py-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                    <i class="fas fa-file-invoice text-white text-xl"></i>
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold text-white">Thông tin Thuế</h1>
                                    <p class="text-white/80">Bước 2/4 - Thông tin kinh doanh và thuế</p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Content -->
                        <div class="p-8">
                            <form method="POST" action="{{ route('seller.register.step3') }}" class="space-y-8" enctype="multipart/form-data">
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

                                <!-- Business Type -->
                                <div class="space-y-4">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        <i class="fas fa-building mr-2 text-blue-500"></i>
                                        Loại hình kinh doanh <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid md:grid-cols-3 gap-4">
                                        <label class="relative">
                                            <input type="radio" name="business_type" value="individual" class="sr-only peer business-type-radio"
                                                {{ old('business_type', session('register.business_type')) == 'individual' ? 'checked' : '' }}>
                                            <div
                                                class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200">
                                                <div class="flex items-center space-x-3">
                                                    <div
                                                        class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center">
                                                        <div
                                                            class="w-2 h-2 bg-white rounded-full hidden peer-checked:block">
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="font-semibold text-gray-900">Cá nhân</div>
                                                        <div class="text-sm text-gray-500">Kinh doanh cá nhân</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>

                                        <label class="relative">
                                            <input type="radio" name="business_type" value="household"
                                                class="sr-only peer business-type-radio"
                                                {{ old('business_type', session('register.business_type')) == 'household' ? 'checked' : '' }}>
                                            <div
                                                class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200">
                                                <div class="flex items-center space-x-3">
                                                    <div
                                                        class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center">
                                                        <div
                                                            class="w-2 h-2 bg-white rounded-full hidden peer-checked:block">
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="font-semibold text-gray-900">Hộ kinh doanh</div>
                                                        <div class="text-sm text-gray-500">Hộ gia đình kinh doanh</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>

                                        <label class="relative">
                                            <input type="radio" name="business_type" value="company" class="sr-only peer business-type-radio"
                                                {{ old('business_type', session('register.business_type')) == 'company' ? 'checked' : '' }}>
                                            <div
                                                class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200">
                                                <div class="flex items-center space-x-3">
                                                    <div
                                                        class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center">
                                                        <div
                                                            class="w-2 h-2 bg-white rounded-full hidden peer-checked:block">
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="font-semibold text-gray-900">Công ty</div>
                                                        <div class="text-sm text-gray-500">Doanh nghiệp</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @error('business_type')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                    
                                    <!-- Dynamic Business Type Info -->
                                    <div id="business-type-info" class="hidden mt-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                        <div class="flex items-center space-x-2 mb-3">
                                            <i class="fas fa-info-circle text-blue-600"></i>
                                            <h4 class="font-semibold text-blue-900">Thông tin chi tiết</h4>
                                        </div>
                                        <div id="business-type-details" class="space-y-2 text-sm text-blue-800">
                                            <!-- Content will be populated by JavaScript -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Fields for Household and Company Business -->
                                <div id="additional-business-fields" class="hidden space-y-6">
                                    <div class="border-t border-gray-200 pt-6">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                            <i class="fas fa-file-alt mr-2 text-blue-500"></i>
                                            Thông tin giấy phép kinh doanh
                                        </h3>
                                        
                                        <div class="grid md:grid-cols-2 gap-6">
                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-600">
                                                    Số giấy phép kinh doanh <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="business_license_number"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                                    placeholder="GP123456789"
                                                    value="{{ old('business_license_number', session('register.business_license_number')) }}">
                                                @error('business_license_number')
                                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-600">
                                                    Ngày cấp giấy phép <span class="text-red-500">*</span>
                                                </label>
                                                <input type="date" name="business_license_date"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                                    value="{{ old('business_license_date', session('register.business_license_date')) }}">
                                                @error('business_license_date')
                                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- File Upload Section -->
                                        <div class="mt-6 space-y-4">
                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-600">
                                                    <i class="fas fa-file-image mr-2 text-blue-500"></i>
                                                    Ảnh giấy phép kinh doanh <span class="text-red-500">*</span>
                                                </label>
                                                <div class="space-y-3">
                                                    <!-- Front Side -->
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-600 mb-2">
                                                            Mặt trước giấy phép
                                                        </label>
                                                        <div class="flex items-center space-x-4">
                                                            <div class="flex-1">
                                                                <input type="file" name="business_license_front" 
                                                                    accept="image/*" 
                                                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                                                    onchange="previewImage(this, 'preview-front')">
                                                            </div>
                                                            <div id="preview-front" class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                                                                @if(session('register_shop.business_license_front'))
                                                                    <img src="{{ asset('storage/' . session('register_shop.business_license_front')) }}" class="w-full h-full object-cover rounded-lg" alt="Front Preview">
                                                                @else
                                                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                                                @endif
                                                            </div>
                                                            @if(session('register_shop.business_license_front'))
                                                                <button type="button" onclick="removeImage('business_license_front', 'preview-front')" 
                                                                    class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                        @error('business_license_front')
                                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Back Side -->
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-600 mb-2">
                                                            Mặt sau giấy phép
                                                        </label>
                                                        <div class="flex items-center space-x-4">
                                                            <div class="flex-1">
                                                                <input type="file" name="business_license_back" 
                                                                    accept="image/*" 
                                                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                                                    onchange="previewImage(this, 'preview-back')">
                                                            </div>
                                                            <div id="preview-back" class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                                                                @if(session('register_shop.business_license_back'))
                                                                    <img src="{{ asset('storage/' . session('register_shop.business_license_back')) }}" class="w-full h-full object-cover rounded-lg" alt="Back Preview">
                                                                @else
                                                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                                                @endif
                                                            </div>
                                                            @if(session('register_shop.business_license_back'))
                                                                <button type="button" onclick="removeImage('business_license_back', 'preview-back')" 
                                                                    class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                        @error('business_license_back')
                                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <p class="text-sm text-gray-500 mt-2">
                                                    <i class="fas fa-info-circle mr-1"></i>
                                                    Chỉ chấp nhận file ảnh (JPG, PNG, JPEG). Kích thước tối đa: 5MB mỗi file.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Business Address -->
                                <div class="space-y-4">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                        Địa chỉ đăng ký kinh doanh <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid md:grid-cols-3 gap-4">
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-600">Tỉnh / Thành phố</label>
                                            <select name="business_province" id="business_province"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                                <option value="" disabled
                                                    {{ !old('business_province', session('register.business_province')) ? 'selected' : '' }}>
                                                    Chọn tỉnh/thành
                                                </option>
                                            </select>
                                            <input type="hidden" name="business_province_name" id="business_province_name" value="{{ old('business_province_name', session('register.business_province_name')) }}">
                                            @error('business_province')
                                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-600">Quận / Huyện</label>
                                            <select name="business_district" id="business_district"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                                <option value="" disabled
                                                    {{ !old('business_district', session('register.business_district')) ? 'selected' : '' }}>
                                                    Chọn quận/huyện
                                                </option>
                                            </select>
                                            <input type="hidden" name="business_district_name" id="business_district_name" value="{{ old('business_district_name', session('register.business_district_name')) }}">
                                            @error('business_district')
                                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-600">Phường / Xã</label>
                                            <select name="business_ward" id="business_ward"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                                <option value="" disabled
                                                    {{ !old('business_ward', session('register.business_ward')) ? 'selected' : '' }}>
                                                    Chọn phường/xã
                                                </option>
                                            </select>
                                            <input type="hidden" name="business_ward_name" id="business_ward_name" value="{{ old('business_ward_name', session('register.business_ward_name')) }}">
                                            @error('business_ward')
                                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-600">Địa chỉ chi tiết</label>
                                        <input type="text" name="business_address_detail"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                            placeholder="Số nhà, tên đường..."
                                            value="{{ old('business_address_detail', session('register.business_address_detail')) }}">
                                        @error('business_address_detail')
                                            <p class="text-red-500 text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">
                                            <i class="fas fa-envelope mr-2 text-blue-500"></i>
                                            Email nhận hóa đơn điện tử <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" name="invoice_email"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#f42f46] focus:border-[#f42f46] transition-all duration-200"
                                            placeholder="invoice@example.com"
                                            value="{{ old('invoice_email', session('register.invoice_email')) }}">
                                        <p class="text-sm text-gray-500 mt-1">Hóa đơn điện tử sẽ được gửi đến email này</p>
                                        @error('invoice_email')
                                            <p class="text-red-500 text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">
                                            <i class="fas fa-id-card mr-2 text-blue-500"></i>
                                            Mã số thuế <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="tax_code"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#f42f46] focus:border-[#f42f46] transition-all duration-200"
                                            placeholder="0123456789"
                                            value="{{ old('tax_code', session('register.tax_code')) }}">
                                        <p class="text-sm text-gray-500 mt-1">
                                            Mã số thuế kinh doanh.
                                        </p>
                                        @error('tax_code')
                                            <p class="text-red-500 text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex justify-between items-center pt-8 border-t border-gray-200">
                                    <a href="{{ route('seller.register') }}"
                                        class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition-all duration-200">
                                        <i class="fas fa-arrow-left mr-2"></i>
                                        Quay lại
                                    </a>
                                    <button type="submit"
                                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-400 to-purple-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
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
                        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-info-circle text-blue-600"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-blue-900">Thông tin quan trọng</h3>
                            </div>
                            <p class="text-sm text-blue-800 leading-relaxed">
                                Việc thu thập thông tin thuế và định danh là bắt buộc theo quy định của Luật an ninh mạng,
                                Thương mại điện tử và Thuế của Việt Nam. Thông tin sẽ được bảo vệ theo chính sách bảo mật
                                của ZynoxMall.
                            </p>
                        </div>

                        <!-- Tips Card -->
                        <div class="bg-white rounded-2xl border border-gray-100 p-6">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-lightbulb text-green-600"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Lưu ý</h3>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                    <p class="text-sm text-gray-600">Mã số thuế phải chính xác và hợp lệ</p>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                    <p class="text-sm text-gray-600">Địa chỉ kinh doanh phải đầy đủ và chính xác</p>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                    <p class="text-sm text-gray-600">Email nhận hóa đơn phải hoạt động</p>
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
                                    <span class="text-sm font-semibold text-blue-500">Đang thực hiện</span>
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
@endsection

@push('scripts')
    <!-- Bỏ include dữ liệu tĩnh -->
    {{-- <script src="{{ asset('js/seller/address-data.js') }}"></script> --}}
    <script src="{{ asset('js/seller/register.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('business_province');
            const districtSelect = document.getElementById('business_district');
            const wardSelect = document.getElementById('business_ward');
            const provinceNameInput = document.getElementById('business_province_name');
            const districtNameInput = document.getElementById('business_district_name');
            const wardNameInput = document.getElementById('business_ward_name');

            function showLoading(select, text = 'Đang tải...') {
                select.innerHTML = `<option value="" disabled selected>${text}</option>`;
                select.disabled = true;
            }
            function doneLoading(select, placeholder) {
                select.disabled = false;
                if (placeholder) {
                    select.innerHTML = `<option value="" disabled selected>${placeholder}</option>`;
                }
            }
            function showError(message) {
                const div = document.createElement('div');
                div.className = 'mt-2 text-sm text-red-600';
                div.textContent = message;
                provinceSelect.parentNode.appendChild(div);
                setTimeout(() => div.remove(), 5000);
            }

            // Load Provinces (VNPost -> fallback)
            function loadProvinces() {
                showLoading(provinceSelect, 'Đang tải tỉnh/thành...');
                fetch('https://api.vnpost.vn/api/v1/province')
                    .then(r => r.json())
                    .then(data => {
                        if (data.success && data.data) {
                            doneLoading(provinceSelect, 'Chọn tỉnh/thành');
                            data.data.forEach(p => {
                                const opt = document.createElement('option');
                                opt.value = p.provinceCode;
                                opt.textContent = p.provinceName;
                                if (opt.value == "{{ old('business_province', session('register.business_province')) }}") opt.selected = true;
                                provinceSelect.appendChild(opt);
                            });
                        } else {
                            throw new Error('VNPost provinces failed');
                        }
                    })
                    .catch(() => {
                        // Fallback
                        fetch('https://provinces.open-api.vn/api/p/')
                            .then(r => r.json())
                            .then(list => {
                                doneLoading(provinceSelect, 'Chọn tỉnh/thành');
                                list.forEach(p => {
                                    const opt = document.createElement('option');
                                    opt.value = p.code;
                                    opt.textContent = p.name;
                                    if (opt.value == "{{ old('business_province', session('register.business_province')) }}") opt.selected = true;
                                    provinceSelect.appendChild(opt);
                                });
                            })
                            .catch(() => showError('Không thể tải danh sách tỉnh/thành'));
                    })
                    .finally(() => {
                        if (provinceSelect.value) provinceSelect.dispatchEvent(new Event('change'));
                    });
            }

            // Load Districts by Province
            function loadDistricts(provinceCode) {
                showLoading(districtSelect, 'Đang tải quận/huyện...');
                wardSelect.innerHTML = '<option value="" disabled selected>Chọn phường/xã</option>';
                wardSelect.disabled = true;

                fetch(`https://api.vnpost.vn/api/v1/district?provinceCode=${provinceCode}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.success && data.data) {
                            doneLoading(districtSelect, 'Chọn quận/huyện');
                            data.data.forEach(d => {
                                const opt = document.createElement('option');
                                opt.value = d.districtCode;
                                opt.textContent = d.districtName;
                                if (opt.value == "{{ old('business_district', session('register.business_district')) }}") opt.selected = true;
                                districtSelect.appendChild(opt);
                            });
                        } else {
                            throw new Error('VNPost districts failed');
                        }
                    })
                    .catch(() => {
                        // Fallback
                        fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
                            .then(r => r.json())
                            .then(p => {
                                doneLoading(districtSelect, 'Chọn quận/huyện');
                                p.districts.forEach(d => {
                                    const opt = document.createElement('option');
                                    opt.value = d.code;
                                    opt.textContent = d.name;
                                    if (opt.value == "{{ old('business_district', session('register.business_district')) }}") opt.selected = true;
                                    districtSelect.appendChild(opt);
                                });
                            })
                            .catch(() => showError('Không thể tải danh sách quận/huyện'));
                    })
                    .finally(() => {
                        if (districtSelect.value) districtSelect.dispatchEvent(new Event('change'));
                    });
            }

            // Load Wards by District
            function loadWards(districtCode) {
                showLoading(wardSelect, 'Đang tải phường/xã...');
                fetch(`https://api.vnpost.vn/api/v1/ward?districtCode=${districtCode}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.success && data.data) {
                            doneLoading(wardSelect, 'Chọn phường/xã');
                            data.data.forEach(w => {
                                const opt = document.createElement('option');
                                opt.value = w.wardCode;
                                opt.textContent = w.wardName;
                                if (opt.value == "{{ old('business_ward', session('register.business_ward')) }}") opt.selected = true;
                                wardSelect.appendChild(opt);
                            });
                        } else {
                            throw new Error('VNPost wards failed');
                        }
                    })
                    .catch(() => {
                        // Fallback
                        fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
                            .then(r => r.json())
                            .then(d => {
                                doneLoading(wardSelect, 'Chọn phường/xã');
                                d.wards.forEach(w => {
                                    const opt = document.createElement('option');
                                    opt.value = w.code;
                                    opt.textContent = w.name;
                                    if (opt.value == "{{ old('business_ward', session('register.business_ward')) }}") opt.selected = true;
                                    wardSelect.appendChild(opt);
                                });
                            })
                            .catch(() => showError('Không thể tải danh sách phường/xã'));
                    });
            }

            // Events
            provinceSelect.addEventListener('change', function() {
                const code = this.value;
                // lưu tên
                provinceNameInput.value = this.options[this.selectedIndex]?.text || '';
                districtSelect.innerHTML = '<option value="" disabled selected>Chọn Quận / Huyện</option>';
                wardSelect.innerHTML = '<option value="" disabled selected>Chọn Phường / Xã</option>';
                districtSelect.disabled = false;
                loadDistricts(code);
            });

            districtSelect.addEventListener('change', function() {
                const code = this.value;
                // lưu tên
                districtNameInput.value = this.options[this.selectedIndex]?.text || '';
                wardSelect.disabled = false;
                loadWards(code);
            });

            wardSelect.addEventListener('change', function() {
                // lưu tên
                wardNameInput.value = this.options[this.selectedIndex]?.text || '';
            });

            // Init
            loadProvinces();

            // Nếu đã có giá trị cũ thì điền tên tương ứng lần đầu tải
            const setInitialNames = () => {
                if (provinceSelect.value) {
                    provinceNameInput.value = provinceSelect.options[provinceSelect.selectedIndex]?.text || provinceNameInput.value;
                }
                if (districtSelect.value) {
                    districtNameInput.value = districtSelect.options[districtSelect.selectedIndex]?.text || districtNameInput.value;
                }
                if (wardSelect.value) {
                    wardNameInput.value = wardSelect.options[wardSelect.selectedIndex]?.text || wardNameInput.value;
                }
            };
            // đợi danh sách load xong rồi set
            setTimeout(setInitialNames, 1000);
        });

        // Xử lý hiển thị thông tin theo loại hình kinh doanh
        const businessTypeRadios = document.querySelectorAll('.business-type-radio');
        const businessTypeInfo = document.getElementById('business-type-info');
        const businessTypeDetails = document.getElementById('business-type-details');
        const additionalFields = document.getElementById('additional-business-fields');

        const businessTypeData = {
            individual: {
                title: 'Cá nhân / Kinh doanh cá nhân',
                requirements: [
                    'Chỉ cần CCCD/CMND hợp lệ',
                    'Mã số thuế cá nhân',
                    'Không cần giấy phép kinh doanh'
                ],
                approvalTime: '2-3 ngày làm việc',
                verificationLevel: 'Cơ bản',
                taxRequirements: 'Mã số thuế cá nhân (bắt buộc)'
            },
            household: {
                title: 'Hộ kinh doanh',
                requirements: [
                    'Cần giấy phép hộ kinh doanh',
                    'Mã số thuế hộ kinh doanh',
                    'Xác minh địa chỉ kinh doanh',
                    'Ảnh giấy phép kinh doanh (mặt trước + mặt sau)'
                ],
                approvalTime: '3-5 ngày làm việc',
                verificationLevel: 'Trung bình',
                taxRequirements: 'Mã số thuế hộ kinh doanh (bắt buộc)'
            },
            company: {
                title: 'Công ty / Doanh nghiệp',
                requirements: [
                    'Cần giấy phép kinh doanh công ty',
                    'Mã số thuế doanh nghiệp',
                    'Xác minh địa chỉ trụ sở chính',
                    'Kiểm tra tư cách pháp nhân',
                    'Ảnh giấy phép kinh doanh (mặt trước + mặt sau)'
                ],
                approvalTime: '5-7 ngày làm việc',
                verificationLevel: 'Cao',
                taxRequirements: 'Mã số thuế doanh nghiệp (bắt buộc)'
            }
        };

        function showBusinessTypeInfo(type) {
            if (!type || !businessTypeData[type]) {
                businessTypeInfo.classList.add('hidden');
                return;
            }

            const data = businessTypeData[type];
            businessTypeDetails.innerHTML = `
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <h5 class="font-semibold mb-2">Yêu cầu tài liệu:</h5>
                        <ul class="list-disc list-inside space-y-1">
                            ${data.requirements.map(req => `<li>${req}</li>`).join('')}
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-semibold mb-2">Thông tin khác:</h5>
                        <div class="space-y-2">
                            <div><strong>Thời gian xử lý:</strong> ${data.approvalTime}</div>
                            <div><strong>Mức độ xác thực:</strong> ${data.verificationLevel}</div>
                            <div><strong>Yêu cầu thuế:</strong> ${data.taxRequirements}</div>
                        </div>
                    </div>
                </div>
            `;
            
            businessTypeInfo.classList.remove('hidden');

            // Hiển thị/ẩn các trường bổ sung
            if (type === 'household' || type === 'company') {
                additionalFields.classList.remove('hidden');
            } else {
                additionalFields.classList.add('hidden');
            }
        }

        // Xử lý sự kiện thay đổi loại hình kinh doanh
        businessTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    showBusinessTypeInfo(this.value);
                }
            });
        });

        // Hiển thị thông tin nếu đã có giá trị được chọn
        const selectedBusinessType = document.querySelector('.business-type-radio:checked');
        if (selectedBusinessType) {
            showBusinessTypeInfo(selectedBusinessType.value);
        }

        // Function để preview ảnh
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];
            
            if (file) {
                // Kiểm tra kích thước file (5MB = 5 * 1024 * 1024 bytes)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File quá lớn! Kích thước tối đa là 5MB.');
                    input.value = '';
                    return;
                }
                
                // Kiểm tra định dạng file
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Chỉ chấp nhận file ảnh (JPG, PNG, JPEG)!');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded-lg" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            } else {
                // Nếu không có file mới, kiểm tra xem có ảnh cũ trong session không
                const sessionImage = preview.querySelector('img');
                if (sessionImage) {
                    // Giữ lại ảnh cũ
                    return;
                }
                preview.innerHTML = '<i class="fas fa-image text-gray-400 text-2xl"></i>';
            }
        }

        // Function để xóa ảnh
        function removeImage(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            
            input.value = '';
            preview.innerHTML = '<i class="fas fa-image text-gray-400 text-2xl"></i>';
        }
    </script>
@endpush
