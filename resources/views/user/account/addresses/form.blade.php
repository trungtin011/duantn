<div class="grid grid-cols-1 gap-4">
    <!-- Error Display -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
            <div class="flex items-center space-x-2 mb-2">
                <i class="fas fa-exclamation-circle text-red-500"></i>
                <h3 class="font-semibold text-red-800">Có lỗi xảy ra</h3>
            </div>
            <ul class="text-sm text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Người nhận <span class="text-red-500">*</span></label>
        <input name="receiver_name" value="{{ old('receiver_name', $address->receiver_name ?? '') }}" 
            placeholder="Họ và tên người nhận"
            required class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
        @error('receiver_name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại <span class="text-red-500">*</span></label>
        <input name="receiver_phone" value="{{ old('receiver_phone', $address->receiver_phone ?? '') }}" 
            placeholder="Số điện thoại (10-11 số)"
            required class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
        @error('receiver_phone')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ chi tiết <span class="text-red-500">*</span></label>
        <input name="address" value="{{ old('address', $address->address ?? '') }}" 
            placeholder="Số nhà, tên đường, phường/xã..."
            required class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
        @error('address')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tỉnh/Thành phố <span class="text-red-500">*</span></label>
            <select name="province" id="province" class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                <option value="">Chọn Tỉnh/Thành phố</option>
            </select>
            @error('province')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Quận/Huyện <span class="text-red-500">*</span></label>
            <select name="district" id="district" class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required disabled>
                <option value="">Chọn Quận/Huyện</option>
            </select>
            @error('district')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Phường/Xã <span class="text-red-500">*</span></label>
            <select name="ward" id="ward" class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required disabled>
                <option value="">Chọn Phường/Xã</option>
            </select>
            @error('ward')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Hidden names to always submit readable text -->
    <input type="hidden" id="province_name" name="province_name" value="{{ old('province_name', $address->province ?? '') }}">
    <input type="hidden" id="district_name" name="district_name" value="{{ old('district_name', $address->district ?? '') }}">
    <input type="hidden" id="ward_name" name="ward_name" value="{{ old('ward_name', $address->ward ?? '') }}">

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Loại địa chỉ</label>
        <select name="address_type" class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="home" {{ old('address_type', $address->address_type ?? '') === 'home' ? 'selected' : '' }}>Nhà riêng</option>
            <option value="office" {{ old('address_type', $address->address_type ?? '') === 'office' ? 'selected' : '' }}>Công ty</option>
            <option value="other" {{ old('address_type', $address->address_type ?? '') === 'other' ? 'selected' : '' }}>Khác</option>
        </select>
        @error('address_type')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
        <textarea name="note" rows="3" placeholder="Ghi chú thêm về địa chỉ (không bắt buộc)"
            class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('note', $address->note ?? '') }}</textarea>
        @error('note')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <div class="flex items-center">
        <input type="checkbox" name="is_default" id="is_default" class="mr-2"
            {{ old('is_default', $address->is_default ?? false) ? 'checked' : '' }}>
        <label for="is_default" class="text-sm text-gray-700">Đặt làm địa chỉ mặc định</label>
    </div>

    <div class="flex justify-end pt-4">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
            <i class="fas fa-save mr-2"></i>Lưu địa chỉ
        </button>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province');
            const districtSelect = document.getElementById('district');
            const wardSelect = document.getElementById('ward');
            const provinceNameInput = document.getElementById('province_name');
            const districtNameInput = document.getElementById('district_name');
            const wardNameInput = document.getElementById('ward_name');

            if (!provinceSelect || !districtSelect || !wardSelect) {
                console.error('Address selector elements not found');
                return;
            }

            // Lấy giá trị cũ từ old() hoặc từ address object
            const oldProvince = @json(old('province', $address->province ?? ''));
            const oldDistrict = @json(old('district', $address->district ?? ''));
            const oldWard = @json(old('ward', $address->ward ?? ''));

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
                const errorDiv = document.createElement('div');
                errorDiv.className = 'mt-2 text-sm text-red-600 p-2 bg-red-50 border border-red-200 rounded';
                errorDiv.textContent = message;
                
                // Xóa error cũ nếu có
                const existingError = provinceSelect.parentNode.querySelector('.text-red-600');
                if (existingError) {
                    existingError.remove();
                }
                
                provinceSelect.parentNode.appendChild(errorDiv);
                setTimeout(() => errorDiv.remove(), 5000);
            }

            // Load Provinces (VNPost -> fallback)
            function loadProvinces() {
                showLoading(provinceSelect, 'Đang tải tỉnh/thành...');
                
                fetch('https://api.vnpost.vn/api/v1/province')
                    .then(r => r.json())
                    .then(data => {
                        if (data.success && data.data) {
                            doneLoading(provinceSelect, 'Chọn Tỉnh/Thành phố');
                            data.data.forEach(p => {
                                const opt = document.createElement('option');
                                opt.value = p.provinceCode;
                                opt.textContent = p.provinceName;
                                opt.dataset.name = p.provinceName;
                                
                                // Preselect nếu có giá trị cũ
                                if (opt.value == oldProvince || opt.textContent == oldProvince) {
                                    opt.selected = true;
                                }
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
                                doneLoading(provinceSelect, 'Chọn Tỉnh/Thành phố');
                                list.forEach(p => {
                                    const opt = document.createElement('option');
                                    opt.value = p.code;
                                    opt.textContent = p.name;
                                    opt.dataset.name = p.name;
                                    
                                    // Preselect nếu có giá trị cũ
                                    if (opt.value == oldProvince || opt.textContent == oldProvince) {
                                        opt.selected = true;
                                    }
                                    provinceSelect.appendChild(opt);
                                });
                            })
                            .catch(() => showError('Không thể tải danh sách tỉnh/thành'));
                    })
                    .finally(() => {
                        if (provinceSelect.value) {
                            provinceNameInput.value = provinceSelect.options[provinceSelect.selectedIndex]?.text || '';
                            provinceSelect.dispatchEvent(new Event('change'));
                        }
                    });
            }

            // Load Districts by Province
            function loadDistricts(provinceCode) {
                showLoading(districtSelect, 'Đang tải quận/huyện...');
                wardSelect.innerHTML = '<option value="" disabled selected>Chọn Phường/Xã</option>';
                wardSelect.disabled = true;

                fetch(`https://api.vnpost.vn/api/v1/district?provinceCode=${provinceCode}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.success && data.data) {
                            doneLoading(districtSelect, 'Chọn Quận/Huyện');
                            data.data.forEach(d => {
                                const opt = document.createElement('option');
                                opt.value = d.districtCode;
                                opt.textContent = d.districtName;
                                opt.dataset.name = d.districtName;
                                
                                // Preselect nếu có giá trị cũ
                                if (opt.value == oldDistrict || opt.textContent == oldDistrict) {
                                    opt.selected = true;
                                }
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
                                doneLoading(districtSelect, 'Chọn Quận/Huyện');
                                p.districts.forEach(d => {
                                    const opt = document.createElement('option');
                                    opt.value = d.code;
                                    opt.textContent = d.name;
                                    opt.dataset.name = d.name;
                                    
                                    // Preselect nếu có giá trị cũ
                                    if (opt.value == oldDistrict || opt.textContent == oldDistrict) {
                                        opt.selected = true;
                                    }
                                    districtSelect.appendChild(opt);
                                });
                            })
                            .catch(() => showError('Không thể tải danh sách quận/huyện'));
                    })
                    .finally(() => {
                        if (districtSelect.value) {
                            districtNameInput.value = districtSelect.options[districtSelect.selectedIndex]?.text || '';
                            districtSelect.dispatchEvent(new Event('change'));
                        }
                    });
            }

            // Load Wards by District
            function loadWards(districtCode) {
                showLoading(wardSelect, 'Đang tải phường/xã...');
                
                fetch(`https://api.vnpost.vn/api/v1/ward?districtCode=${districtCode}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.success && data.data) {
                            doneLoading(wardSelect, 'Chọn Phường/Xã');
                            data.data.forEach(w => {
                                const opt = document.createElement('option');
                                opt.value = w.wardCode;
                                opt.textContent = w.wardName;
                                opt.dataset.name = w.wardName;
                                
                                // Preselect nếu có giá trị cũ
                                if (opt.value == oldWard || opt.textContent == oldWard) {
                                    opt.selected = true;
                                }
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
                                doneLoading(wardSelect, 'Chọn Phường/Xã');
                                d.wards.forEach(w => {
                                    const opt = document.createElement('option');
                                    opt.value = w.code;
                                    opt.textContent = w.name;
                                    opt.dataset.name = w.name;
                                    
                                    // Preselect nếu có giá trị cũ
                                    if (opt.value == oldWard || opt.textContent == oldWard) {
                                        opt.selected = true;
                                    }
                                    wardSelect.appendChild(opt);
                                });
                            })
                            .catch(() => showError('Không thể tải danh sách phường/xã'));
                    });
            }

            // Events
            provinceSelect.addEventListener('change', function() {
                const code = this.value;
                // Lưu tên
                provinceNameInput.value = this.options[this.selectedIndex]?.text || '';
                districtSelect.innerHTML = '<option value="" disabled selected>Chọn Quận/Huyện</option>';
                wardSelect.innerHTML = '<option value="" disabled selected>Chọn Phường/Xã</option>';
                districtSelect.disabled = false;
                if (code) {
                    loadDistricts(code);
                }
            });

            districtSelect.addEventListener('change', function() {
                const code = this.value;
                // Lưu tên
                districtNameInput.value = this.options[this.selectedIndex]?.text || '';
                wardSelect.disabled = false;
                if (code) {
                    loadWards(code);
                }
            });

            wardSelect.addEventListener('change', function() {
                // Lưu tên
                wardNameInput.value = this.options[this.selectedIndex]?.text || '';
            });

            // Form validation before submit
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Kiểm tra xem đã chọn đầy đủ địa chỉ chưa
                    if (!provinceSelect.value || !districtSelect.value || !wardSelect.value) {
                        e.preventDefault();
                        showError('Vui lòng chọn đầy đủ Tỉnh/Thành, Quận/Huyện và Phường/Xã');
                        return false;
                    }

                    // Đảm bảo gửi tên địa chỉ thay vì mã
                    const selectedProvince = provinceSelect.options[provinceSelect.selectedIndex];
                    const selectedDistrict = districtSelect.options[districtSelect.selectedIndex];
                    const selectedWard = wardSelect.options[wardSelect.selectedIndex];

                    if (selectedProvince) {
                        provinceNameInput.value = selectedProvince.textContent;
                    }
                    if (selectedDistrict) {
                        districtNameInput.value = selectedDistrict.textContent;
                    }
                    if (selectedWard) {
                        wardNameInput.value = selectedWard.textContent;
                    }
                });
            }

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
    </script>
@endpush
