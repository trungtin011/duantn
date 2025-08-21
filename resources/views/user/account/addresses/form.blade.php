<div class="grid grid-cols-1 gap-4">
    <input name="receiver_name" value="{{ old('receiver_name', $address->receiver_name ?? '') }}" placeholder="Người nhận"
        required class="border rounded px-3 py-2" />
    <input name="receiver_phone" value="{{ old('receiver_phone', $address->receiver_phone ?? '') }}" placeholder="SĐT"
        required class="border rounded px-3 py-2" />
    <input name="address" value="{{ old('address', $address->address ?? '') }}" placeholder="Địa chỉ chi tiết" required
        class="border rounded px-3 py-2" />

    <div class="row flex justify-between gap-2">
        <div class="col-md-4 w-full">
            <select name="province" id="province" class="border rounded px-3 py-2 w-full" required
                data-old-value="{{ old('province', $address->province ?? '') }}">
                <option value="">Chọn Tỉnh/Thành phố</option>
            </select>
        </div>
        <div class="col-md-4 w-full">
            <select name="district" id="district" class="border rounded px-3 py-2 w-full" required
                data-old-value="{{ old('district', $address->district ?? '') }}">
                <option value="">Chọn Quận/Huyện</option>
            </select>
        </div>
        <div class="col-md-4 w-full">
            <select name="ward" id="ward" class="border rounded px-3 py-2 w-full" required
                data-old-value="{{ old('ward', $address->ward ?? '') }}">
                <option value="">Chọn Phường/Xã</option>
            </select>
        </div>
    </div>

    <!-- Hidden names to always submit readable text -->
    <input type="hidden" id="province_name" name="province_name" value="{{ old('province_name', $address->province ?? '') }}">
    <input type="hidden" id="district_name" name="district_name" value="{{ old('district_name', $address->district ?? '') }}">
    <input type="hidden" id="ward_name" name="ward_name" value="{{ old('ward_name', $address->ward ?? '') }}">

    <select name="address_type" class="border rounded px-3 py-2">
        <option value="home" {{ old('address_type', $address->address_type ?? '') === 'home' ? 'selected' : '' }}>Nhà
            riêng</option>
        <option value="office" {{ old('address_type', $address->address_type ?? '') === 'office' ? 'selected' : '' }}>
            Công ty</option>
        <option value="other" {{ old('address_type', $address->address_type ?? '') === 'other' ? 'selected' : '' }}>
            Khác</option>
    </select>
    
    <textarea name="note" class="border rounded px-3 py-2" placeholder="Ghi chú">{{ old('note', $address->note ?? '') }}</textarea>
    
    <label>
        <input type="checkbox" name="is_default"
            {{ old('is_default', $address->is_default ?? false) ? 'checked' : '' }}> Đặt làm mặc định
    </label>

    <button type="submit" class="bg-black text-white px-4 py-2 rounded">Lưu</button>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province');
            const districtSelect = document.getElementById('district');
            const wardSelect = document.getElementById('ward');

            if (!provinceSelect || !districtSelect || !wardSelect) {
                console.warn('Address selector elements not found');
                return;
            }

            // Lấy dữ liệu tỉnh/thành phố từ VNPost API
            loadProvincesFromVNPost();

            function loadProvincesFromVNPost() {
                fetch('https://api.vnpost.vn/api/v1/province')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.data) {
                            data.data.forEach(province => {
                                const option = document.createElement('option');
                                option.value = province.provinceCode;
                                option.textContent = province.provinceName;
                                option.dataset.name = province.provinceName;
                                provinceSelect.appendChild(option);
                            });

                            // Khôi phục giá trị cũ nếu có
                            restoreOldValues();
                        } else {
                            console.warn('VNPost API failed, trying fallback API...');
                            loadProvincesFromFallback();
                        }
                    })
                    .catch(error => {
                        console.error('Error loading provinces from VNPost:', error);
                        console.warn('Trying fallback API...');
                        loadProvincesFromFallback();
                    });
            }

            function loadProvincesFromFallback() {
                // Fallback API: provinces.open-api.vn
                fetch('https://provinces.open-api.vn/api/p/')
                    .then(response => response.json())
                    .then(provinces => {
                        provinces.forEach(province => {
                            const option = document.createElement('option');
                            option.value = province.code;
                            option.textContent = province.name;
                            option.dataset.name = province.name;
                            provinceSelect.appendChild(option);
                        });

                        // Khôi phục giá trị cũ nếu có
                        restoreOldValues();
                    })
                    .catch(error => {
                        console.error('Error loading provinces from fallback API:', error);
                        showError('Không thể tải danh sách tỉnh/thành phố. Vui lòng thử lại sau.');
                    });
            }

            // Xử lý khi chọn tỉnh/thành phố
            provinceSelect.addEventListener('change', function() {
                const provinceCode = this.value;
                showLoading(districtSelect, 'Đang tải quận/huyện...');
                showLoading(wardSelect, 'Chọn quận/huyện trước');

                if (provinceCode) {
                    loadDistricts(provinceCode);
                } else {
                    hideLoading(districtSelect);
                    hideLoading(wardSelect);
                    districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
                    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                }
                // set tên tỉnh vào hidden
                const provinceNameHidden = document.getElementById('province_name');
                provinceNameHidden.value = this.options[this.selectedIndex]?.dataset.name || this.options[this.selectedIndex]?.textContent || '';
            });

            // Xử lý khi chọn quận/huyện
            districtSelect.addEventListener('change', function() {
                const districtCode = this.value;
                showLoading(wardSelect, 'Đang tải phường/xã...');

                if (districtCode) {
                    loadWards(districtCode);
                } else {
                    hideLoading(wardSelect);
                    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                }
                // set tên quận vào hidden
                const districtNameHidden = document.getElementById('district_name');
                districtNameHidden.value = this.options[this.selectedIndex]?.dataset.name || this.options[this.selectedIndex]?.textContent || '';
            });

            // Load quận/huyện từ VNPost API
            function loadDistricts(provinceCode) {
                loadDistrictsFromVNPost(provinceCode);
            }

            function loadDistrictsFromVNPost(provinceCode) {
                fetch(`https://api.vnpost.vn/api/v1/district?provinceCode=${provinceCode}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.data) {
                            districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
                            data.data.forEach(district => {
                                const option = document.createElement('option');
                                option.value = district.districtCode;
                                option.textContent = district.districtName;
                                option.dataset.name = district.districtName;
                                districtSelect.appendChild(option);
                            });

                            hideLoading(districtSelect);
                            // Khôi phục giá trị district cũ nếu có
                            restoreDistrictValue();
                        } else {
                            console.warn('VNPost district API failed, trying fallback...');
                            loadDistrictsFromFallback(provinceCode);
                        }
                    })
                    .catch(error => {
                        console.error('Error loading districts from VNPost:', error);
                        console.warn('Trying fallback API...');
                        loadDistrictsFromFallback(provinceCode);
                    });
            }

            function loadDistrictsFromFallback(provinceCode) {
                // Fallback API: provinces.open-api.vn
                fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
                    .then(response => response.json())
                    .then(province => {
                        districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
                        province.districts.forEach(district => {
                            const option = document.createElement('option');
                            option.value = district.code;
                            option.textContent = district.name;
                            option.dataset.name = district.name;
                            districtSelect.appendChild(option);
                        });

                        hideLoading(districtSelect);
                        // Khôi phục giá trị district cũ nếu có
                        restoreDistrictValue();
                    })
                    .catch(error => {
                        console.error('Error loading districts from fallback API:', error);
                        showError('Không thể tải danh sách quận/huyện. Vui lòng thử lại sau.');
                        hideLoading(districtSelect);
                    });
            }

            // Load phường/xã từ VNPost API
            function loadWards(districtCode) {
                loadWardsFromVNPost(districtCode);
            }

            function loadWardsFromVNPost(districtCode) {
                fetch(`https://api.vnpost.vn/api/v1/ward?districtCode=${districtCode}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.data) {
                            wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                            data.data.forEach(ward => {
                                const option = document.createElement('option');
                                option.value = ward.wardCode || ward.wardName; // nếu có code dùng code, nhưng ta sẽ đổi khi submit
                                option.textContent = ward.wardName;
                                option.dataset.name = ward.wardName;
                                wardSelect.appendChild(option);
                            });

                            hideLoading(wardSelect);
                            // Khôi phục giá trị ward cũ nếu có
                            restoreWardValue();
                        } else {
                            console.warn('VNPost ward API failed, trying fallback...');
                            loadWardsFromFallback(districtCode);
                        }
                    })
                    .catch(error => {
                        console.error('Error loading wards from VNPost:', error);
                        console.warn('Trying fallback API...');
                        loadWardsFromFallback(districtCode);
                    });
            }

            function loadWardsFromFallback(districtCode) {
                // Fallback API: provinces.open-api.vn
                fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
                    .then(response => response.json())
                    .then(district => {
                        wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                        district.wards.forEach(ward => {
                            const option = document.createElement('option');
                            option.value = ward.code || ward.name;
                            option.textContent = ward.name;
                            option.dataset.name = ward.name;
                            wardSelect.appendChild(option);
                        });

                        hideLoading(wardSelect);
                        // Khôi phục giá trị ward cũ nếu có
                        restoreWardValue();
                    })
                    .catch(error => {
                        console.error('Error loading wards from fallback API:', error);
                        showError('Không thể tải danh sách phường/xã. Vui lòng thử lại sau.');
                        hideLoading(wardSelect);
                    });
            }

            // Khôi phục giá trị cũ
            function restoreOldValues() {
                const oldProvince = provinceSelect.dataset.oldValue;
                if (oldProvince) {
                    const provinceOption = Array.from(provinceSelect.options).find(option =>
                        option.dataset.name === oldProvince || option.value === oldProvince
                    );
                    if (provinceOption) {
                        provinceOption.selected = true;
                        loadDistricts(provinceOption.value);
                    }
                }
            }

            function restoreDistrictValue() {
                const oldDistrict = districtSelect.dataset.oldValue;
                if (oldDistrict) {
                    const districtOption = Array.from(districtSelect.options).find(option =>
                        option.dataset.name === oldDistrict || option.value === oldDistrict
                    );
                    if (districtOption) {
                        districtOption.selected = true;
                        loadWards(districtOption.value);
                    }
                }
            }

            function restoreWardValue() {
                const oldWard = wardSelect.dataset.oldValue;
                if (oldWard) {
                    const wardOption = Array.from(wardSelect.options).find(option =>
                        option.dataset.name === oldWard || option.value === oldWard
                    );
                    if (wardOption) {
                        wardOption.selected = true;
                        const wardNameHidden = document.getElementById('ward_name');
                        wardNameHidden.value = wardOption.dataset.name || wardOption.textContent;
                    }
                }
            }

            function showError(message) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'text-red-500 text-sm mt-2';
                errorDiv.textContent = message;

                provinceSelect.parentNode.appendChild(errorDiv);

                setTimeout(() => {
                    errorDiv.remove();
                }, 5000);
            }

            // Xử lý form submit
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Lấy giá trị hiển thị thay vì code
                    const selectedProvince = provinceSelect.options[provinceSelect.selectedIndex];
                    const selectedDistrict = districtSelect.options[districtSelect.selectedIndex];
                    const selectedWard = wardSelect.options[wardSelect.selectedIndex];

                    // Gán thẳng value của option thành tên để server luôn nhận CHỮ (không phải mã)
                    if (selectedProvince) {
                        selectedProvince.value = selectedProvince.dataset.name || selectedProvince.textContent;
                    }
                    if (selectedDistrict) {
                        selectedDistrict.value = selectedDistrict.dataset.name || selectedDistrict.textContent;
                    }
                    if (selectedWard) {
                        selectedWard.value = selectedWard.dataset.name || selectedWard.textContent;
                    }

                    // Tạo input hidden để gửi giá trị hiển thị (đặt tên khác để không đè giá trị code)
                    if (selectedProvince && selectedProvince.value) {
                        const provinceInput = document.createElement('input');
                        provinceInput.type = 'hidden';
                        provinceInput.name = 'province_name';
                        provinceInput.value = selectedProvince.dataset.name || selectedProvince.textContent;
                        e.target.appendChild(provinceInput);
                    }

                    if (selectedDistrict && selectedDistrict.value) {
                        const districtInput = document.createElement('input');
                        districtInput.type = 'hidden';
                        districtInput.name = 'district_name';
                        districtInput.value = selectedDistrict.dataset.name || selectedDistrict.textContent;
                        e.target.appendChild(districtInput);
                    }

                    if (selectedWard && selectedWard.value) {
                        const wardInput = document.createElement('input');
                        wardInput.type = 'hidden';
                        wardInput.name = 'ward_name';
                        wardInput.value = selectedWard.dataset.name || selectedWard.textContent;
                        e.target.appendChild(wardInput);
                    }
                });
            }

            // Thêm loading indicator
            function showLoading(selectElement, message = 'Đang tải...') {
                selectElement.innerHTML = `<option value="">${message}</option>`;
                selectElement.disabled = true;
            }

            function hideLoading(selectElement) {
                selectElement.disabled = false;
            }

            // Cải thiện error handling
            function showError(message, duration = 5000) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'text-red-500 text-sm mt-2 p-2 bg-red-50 border border-red-200 rounded';
                errorDiv.textContent = message;

                // Xóa error cũ nếu có
                const existingError = provinceSelect.parentNode.querySelector('.text-red-500');
                if (existingError) {
                    existingError.remove();
                }

                provinceSelect.parentNode.appendChild(errorDiv);

                setTimeout(() => {
                    errorDiv.remove();
                }, duration);
            }
        });
    </script>
@endpush
