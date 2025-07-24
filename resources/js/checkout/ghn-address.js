// GHN Address Handler
class GHNAddressHandler {
    constructor() {
        this.provinces = [];
        this.districts = [];
        this.wards = [];
        this.init();
    }

    init() {
        this.loadProvinces();
        this.bindEvents();
    }

    // Load danh sách tỉnh/thành phố
    async loadProvinces() {
        try {
            const response = await fetch('/api/ghn/provinces');
            const data = await response.json();
            
            if (data.success) {
                this.provinces = data.data;
                this.populateProvinceSelect();
            } else {
                console.error('Lỗi khi load tỉnh/thành phố:', data.message);
            }
        } catch (error) {
            console.error('Lỗi khi gọi API tỉnh/thành phố:', error);
        }
    }

    // Populate dropdown tỉnh/thành phố
    populateProvinceSelect() {
        const citySelect = document.getElementById('city');
        if (!citySelect) return;
        
        citySelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
        
        this.provinces.forEach(province => {
            const option = document.createElement('option');
            option.value = province.ProvinceID;
            option.textContent = province.ProvinceName;
            option.setAttribute('data-name', province.ProvinceName);
            citySelect.appendChild(option);
        });
        
        // Enable city select
        citySelect.disabled = false;
        citySelect.classList.remove('ghn-loading');
    }

    // Load danh sách quận/huyện
    async loadDistricts(provinceId) {
        const districtSelect = document.getElementById('district');
        if (districtSelect) {
            districtSelect.disabled = true;
            districtSelect.classList.add('ghn-loading');
        }
        
        try {
            const response = await fetch(`/api/ghn/districts?province_id=${provinceId}`);
            const data = await response.json();
            
            if (data.success) {
                this.districts = data.data;
                this.populateDistrictSelect();
                this.resetWardSelect();
            } else {
                console.error('Lỗi khi load quận/huyện:', data.message);
                this.showError('district', 'Không thể load danh sách quận/huyện');
            }
        } catch (error) {
            console.error('Lỗi khi gọi API quận/huyện:', error);
            this.showError('district', 'Lỗi kết nối');
        }
    }

    // Populate dropdown quận/huyện
    populateDistrictSelect() {
        const districtSelect = document.getElementById('district');
        if (!districtSelect) return;
        
        districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
        
        this.districts.forEach(district => {
            const option = document.createElement('option');
            option.value = district.DistrictID;
            option.textContent = district.DistrictName;
            option.setAttribute('data-name', district.DistrictName);
            districtSelect.appendChild(option);
        });
        
        // Enable district select
        districtSelect.disabled = false;
        districtSelect.classList.remove('ghn-loading');
    }

    // Load danh sách phường/xã
    async loadWards(districtId) {
        const wardSelect = document.getElementById('ward');
        if (wardSelect) {
            wardSelect.disabled = true;
            wardSelect.classList.add('ghn-loading');
        }
        
        try {
            const response = await fetch(`/api/ghn/wards?district_id=${districtId}`);
            const data = await response.json();
            
            if (data.success) {
                this.wards = data.data;
                this.populateWardSelect();
            } else {
                console.error('Lỗi khi load phường/xã:', data.message);
                this.showError('ward', 'Không thể load danh sách phường/xã');
            }
        } catch (error) {
            console.error('Lỗi khi gọi API phường/xã:', error);
            this.showError('ward', 'Lỗi kết nối');
        }
    }

    // Populate dropdown phường/xã
    populateWardSelect() {
        const wardSelect = document.getElementById('ward');
        if (!wardSelect) return;
        
        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        
        this.wards.forEach(ward => {
            const option = document.createElement('option');
            option.value = ward.WardCode;
            option.textContent = ward.WardName;
            option.setAttribute('data-name', ward.WardName);
            wardSelect.appendChild(option);
        });
        
        // Enable ward select
        wardSelect.disabled = false;
        wardSelect.classList.remove('ghn-loading');
    }

    // Reset ward dropdown
    resetWardSelect() {
        const wardSelect = document.getElementById('ward');
        if (wardSelect) {
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        }
    }

    // Bind events
    bindEvents() {
        const citySelect = document.getElementById('city');
        const districtSelect = document.getElementById('district');

        if (citySelect) {
            citySelect.addEventListener('change', (e) => {
                const provinceId = e.target.value;
                if (provinceId) {
                    this.loadDistricts(provinceId);
                    this.updateHiddenField('city', 'province_name');
                } else {
                    this.resetDistrictAndWard();
                    this.clearHiddenField('province_name');
                }
            });
        }

        if (districtSelect) {
            districtSelect.addEventListener('change', (e) => {
                const districtId = e.target.value;
                if (districtId) {
                    this.loadWards(districtId);
                    this.updateHiddenField('district', 'district_name');
                } else {
                    this.resetWardSelect();
                    this.clearHiddenField('district_name');
                }
            });
        }

        // Bind ward change event
        const wardSelect = document.getElementById('ward');
        if (wardSelect) {
            wardSelect.addEventListener('change', (e) => {
                if (e.target.value) {
                    this.updateHiddenField('ward', 'ward_name');
                } else {
                    this.clearHiddenField('ward_name');
                }
            });
        }



        // Bind address type buttons
        const addressTypeButtons = document.querySelectorAll('.address-type-btn');
        addressTypeButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                // Remove active state from all buttons
                addressTypeButtons.forEach(btn => {
                    btn.classList.remove('bg-primary', 'text-white');
                    btn.classList.add('bg-gray-100', 'text-gray-800');
                });
                
                // Add active state to clicked button
                button.classList.remove('bg-gray-100', 'text-gray-800');
                button.classList.add('bg-primary', 'text-white');
                
                // Update hidden input
                const addressTypeInput = document.getElementById('address_type_input');
                if (addressTypeInput) {
                    addressTypeInput.value = button.getAttribute('data-type');
                }
            });
        });
    }

    // Reset district và ward dropdowns
    resetDistrictAndWard() {
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');
        
        if (districtSelect) {
            districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
            districtSelect.disabled = true;
            districtSelect.classList.remove('ghn-loading', 'ghn-error', 'ghn-success');
        }
        if (wardSelect) {
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
            wardSelect.disabled = true;
            wardSelect.classList.remove('ghn-loading', 'ghn-error', 'ghn-success');
        }
        
        // Clear error messages
        this.clearError('district');
        this.clearError('ward');
    }

    // Show error message
    showError(field, message) {
        const errorElement = document.getElementById(`${field}-error`);
        const selectElement = document.getElementById(field);
        
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }
        
        if (selectElement) {
            selectElement.classList.add('ghn-error');
            selectElement.classList.remove('ghn-loading', 'ghn-success');
        }
    }

    // Clear error message
    clearError(field) {
        const errorElement = document.getElementById(`${field}-error`);
        const selectElement = document.getElementById(field);
        
        if (errorElement) {
            errorElement.classList.add('hidden');
        }
        
        if (selectElement) {
            selectElement.classList.remove('ghn-error');
        }
    }

    // Lấy tên địa chỉ từ ID
    getAddressName(type, id) {
        switch(type) {
            case 'province':
                const province = this.provinces.find(p => p.ProvinceID == id);
                return province ? province.ProvinceName : '';
            case 'district':
                const district = this.districts.find(d => d.DistrictID == id);
                return district ? district.DistrictName : '';
            case 'ward':
                const ward = this.wards.find(w => w.WardCode == id);
                return ward ? ward.WardName : '';
            default:
                return '';
        }
    }

    // Cập nhật hidden field với tên địa chỉ
    updateHiddenField(selectId, hiddenId) {
        const select = document.getElementById(selectId);
        const hidden = document.getElementById(hiddenId);
        
        if (select && select.value && hidden) {
            const selectedOption = select.options[select.selectedIndex];
            const name = selectedOption.getAttribute('data-name');
            if (name) {
                hidden.value = name;
            }
        }
    }

    // Xóa hidden field
    clearHiddenField(hiddenId) {
        const hidden = document.getElementById(hiddenId);
        if (hidden) {
            hidden.value = '';
        }
    }

    // Lấy tên địa chỉ từ option được chọn
    getSelectedAddressName(field) {
        const select = document.getElementById(field);
        if (!select || !select.value) return '';
        
        const selectedOption = select.options[select.selectedIndex];
        return selectedOption.getAttribute('data-name') || selectedOption.textContent;
    }

    // Lấy thông tin địa chỉ đầy đủ
    getFullAddress() {
        const city = document.getElementById('city')?.value;
        const district = document.getElementById('district')?.value;
        const ward = document.getElementById('ward')?.value;
        
        return {
            province_id: city,
            province_name: this.getSelectedAddressName('city'),
            district_id: district,
            district_name: this.getSelectedAddressName('district'),
            ward_code: ward,
            ward_name: this.getSelectedAddressName('ward')
        };
    }

    // Lấy thông tin địa chỉ chỉ có tên (cho form submission)
    getAddressNames() {
        return {
            province: this.getSelectedAddressName('city'),
            district: this.getSelectedAddressName('district'),
            ward: this.getSelectedAddressName('ward')
        };
    }
}

// Khởi tạo khi DOM ready
document.addEventListener('DOMContentLoaded', function() {
    window.ghnAddressHandler = new GHNAddressHandler();
}); 