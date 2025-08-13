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
                            <form method="POST" action="{{ route('seller.register.step3') }}" class="space-y-8">
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
                                            <input type="radio" name="business_type" value="personal" class="sr-only peer"
                                                {{ old('business_type', session('register.business_type')) == 'personal' ? 'checked' : '' }}>
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
                                                class="sr-only peer"
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
                                            <input type="radio" name="business_type" value="company" class="sr-only peer"
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
                                            <a href="#" class="text-blue-600 hover:underline">Tìm hiểu thêm</a>
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
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
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
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
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
    <!-- Include address data file -->
    <script src="{{ asset('js/seller/address-data.js') }}"></script>
    <!-- Include register.js for other functionality -->
    <script src="{{ asset('js/seller/register.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('business_province');
            const districtSelect = document.getElementById('business_district');
            const wardSelect = document.getElementById('business_ward');

            // Populate provinces from local data
            VIETNAM_ADDRESS_DATA.provinces.forEach(province => {
                const option = document.createElement('option');
                option.value = province.code;
                option.textContent = province.name;
                if (option.value == "{{ old('business_province', session('register.business_province')) }}") {
                    option.selected = true;
                }
                provinceSelect.appendChild(option);
            });

            // Handle province change
            provinceSelect.addEventListener('change', function() {
                districtSelect.innerHTML = '<option value="" disabled selected>Chọn Quận / Huyện</option>';
                wardSelect.innerHTML = '<option value="" disabled selected>Chọn Phường / Xã</option>';
                wardSelect.disabled = true;
                districtSelect.disabled = false;

                const provinceCode = this.value;
                const districts = getDistrictsByProvince(provinceCode);
                
                districts.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.code;
                    option.textContent = district.name;
                    if (option.value == "{{ old('business_district', session('register.business_district')) }}") {
                        option.selected = true;
                    }
                    districtSelect.appendChild(option);
                });
            });

            // Handle district change
            districtSelect.addEventListener('change', function() {
                wardSelect.innerHTML = '<option value="" disabled selected>Chọn Phường / Xã</option>';
                wardSelect.disabled = false;

                const districtCode = this.value;
                const wards = getWardsByDistrict(districtCode);
                
                wards.forEach(ward => {
                    const option = document.createElement('option');
                    option.value = ward.code;
                    option.textContent = ward.name;
                    if (option.value == "{{ old('business_ward', session('register.business_ward')) }}") {
                        option.selected = true;
                    }
                    wardSelect.appendChild(option);
                });
            });

            // Trigger change event if province is pre-selected
            if (provinceSelect.value) {
                provinceSelect.dispatchEvent(new Event('change'));
            }
        });

        // Initialize other functionality from register.js
        if (typeof initializeAddressAPI === 'function') {
            // Don't call this as we have our own address handling
            // initializeAddressAPI();
        }
    </script>
@endpush
