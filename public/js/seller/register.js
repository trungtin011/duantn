// Modern Seller Registration JavaScript

// Include address data
// Note: This file should be included before register.js in the HTML
// <script src="{{ asset('js/seller/address-data.js') }}"></script>

// Stepper functionality
function updateStepper(currentStep) {
    const steps = document.querySelectorAll('.stepper-step');
    const progressBar = document.getElementById('progress-bar');
    
    if (progressBar) {
        // Calculate progress percentage
        const progressPercentage = (currentStep / (steps.length - 1)) * 100;
        progressBar.style.width = progressPercentage + '%';
    }
    
    steps.forEach((step, index) => {
        const dot = step.querySelector('.step-dot');
        const label = step.querySelector('.step-label');
        const description = step.querySelector('.step-description');
        
        // Remove all classes
        step.className = 'stepper-step flex flex-col items-center relative z-10';
        dot.className = 'step-dot w-12 h-12 rounded-full border-4 border-gray-200 bg-white flex items-center justify-center transition-all duration-300 mb-3';
        label.className = 'step-label font-semibold text-sm text-gray-600 transition-all duration-300';
        
        if (index < currentStep) {
            // Completed steps
            step.classList.add('done');
            dot.classList.add('done');
            label.classList.add('done');
        } else if (index === currentStep) {
            // Current step
            step.classList.add('active');
            dot.classList.add('active');
            label.classList.add('active');
        } else {
            // Future steps
            step.classList.add('disabled');
            dot.classList.add('disabled');
            label.classList.add('disabled');
        }
    });
}

// File upload preview functionality
function renderImageList(inputId, listId, maxSizeMB) {
    const input = document.getElementById(inputId);
    const list = document.getElementById(listId);
    
    if (!input || !list) return;
    
    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        list.innerHTML = '';
        
        if (file) {
            const maxSize = maxSizeMB * 1024 * 1024;
            if (file.size > maxSize) {
                showNotification(`Kích thước ảnh vượt quá ${maxSizeMB}MB.`, 'error');
                e.target.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(ev) {
                const img = document.createElement('img');
                img.src = ev.target.result;
                img.className = inputId === 'shop_logo_input' 
                    ? 'w-20 h-20 object-cover rounded-lg shadow-md' 
                    : 'w-32 h-20 object-cover rounded-lg shadow-md';
                img.alt = 'Preview';
                list.appendChild(img);
                
                // Add success animation
                img.style.animation = 'success-checkmark 0.5s ease-in-out';
            };
            reader.readAsDataURL(file);
        }
    });
}

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg transform transition-all duration-300 translate-x-full`;
    
    const colors = {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        warning: 'bg-yellow-500 text-white',
        info: 'bg-blue-500 text-white'
    };
    
    notification.className += ` ${colors[type]}`;
    notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation' : 'info'}-circle"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('border-red-500');
            isValid = false;
        } else {
            field.classList.remove('border-red-500');
        }
    });
    
    return isValid;
}

// Smooth scrolling
function smoothScrollTo(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Loading states - TẠM THỜI BỎ
function setLoadingState(button, isLoading) {
    // Tạm thời bỏ loading state
    return;
    
    if (isLoading) {
        button.disabled = true;
        button.innerHTML = `
            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
            Đang xử lý...
        `;
    } else {
        button.disabled = false;
        button.innerHTML = button.dataset.originalText || 'Tiếp theo';
    }
}

// Address API integration
function initializeAddressAPI() {
    const provinceSelect = document.getElementById('business_province');
    const districtSelect = document.getElementById('business_district');
    const wardSelect = document.getElementById('business_ward');

    if (!provinceSelect || !districtSelect || !wardSelect) return;

    // Populate provinces from local data
    VIETNAM_ADDRESS_DATA.provinces.forEach(province => {
        const option = document.createElement('option');
        option.value = province.code;
        option.textContent = province.name;
        provinceSelect.appendChild(option);
    });

    // Province change handler
    provinceSelect.addEventListener('change', function () {
        districtSelect.innerHTML = '<option value="" disabled selected>Chọn Quận / Huyện</option>';
        wardSelect.innerHTML = '<option value="" disabled selected>Chọn Phường / Xã</option>';
        wardSelect.disabled = true;
        districtSelect.disabled = false;
        
        const provinceCode = this.value;
        if (!provinceCode) return;
        
        const districts = getDistrictsByProvince(provinceCode);
        districts.forEach(district => {
            const option = document.createElement('option');
            option.value = district.code;
            option.textContent = district.name;
            districtSelect.appendChild(option);
        });
    });

    // District change handler
    districtSelect.addEventListener('change', function () {
        wardSelect.innerHTML = '<option value="" disabled selected>Chọn Phường / Xã</option>';
        wardSelect.disabled = false;
        
        const districtCode = this.value;
        if (!districtCode) return;
        
        const wards = getWardsByDistrict(districtCode);
        wards.forEach(ward => {
            const option = document.createElement('option');
            option.value = ward.code;
            option.textContent = ward.name;
            wardSelect.appendChild(option);
        });
    });
}

// CCCD scan integration
function initializeCCCDScan() {
    const scanBtn = document.getElementById('scan-cccd-btn');
    const fileInput = document.getElementById('filechoose');
    const loadingDiv = document.getElementById('scan-cccd-loading');
    const errorDiv = document.getElementById('scan-cccd-error');
    const successDiv = document.getElementById('scan-cccd-success');

    if (!scanBtn || !fileInput) return;

    scanBtn.addEventListener('click', function() {
        errorDiv.textContent = '';
        successDiv.classList.add('hidden');
        successDiv.textContent = '';
        
        if (!fileInput.files[0] || !document.getElementById('backfilechoose').files[0]) {
            errorDiv.textContent = 'Vui lòng chọn đủ ảnh mặt trước và mặt sau CCCD trước khi quét.';
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
            
            successDiv.textContent = 'Quét CCCD thành công! Dữ liệu đã được điền vào form.';
            successDiv.classList.remove('hidden');
            showNotification('Quét CCCD thành công!', 'success');
        })
        .catch(err => {
            loadingDiv.classList.add('hidden');
            scanBtn.disabled = false;
            errorDiv.textContent = 'Không thể quét CCCD. Vui lòng thử lại.';
            showNotification('Không thể quét CCCD. Vui lòng thử lại.', 'error');
        });
    });
}

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
            if (input) {
                if (apiField === 'birth_date' || apiField === 'identity_card_date') {
                    // Handle date formatting
                    const date = new Date(data[apiField]);
                    if (!isNaN(date.getTime())) {
                        input.value = date.toISOString().split('T')[0];
                    }
                } else {
                    input.value = data[apiField];
                }
            }
        }
    });

    // Handle gender
    if (data.gender) {
        const radios = document.querySelectorAll('input[name="gender"]');
        radios.forEach(radio => {
            if (radio.value === data.gender.toLowerCase()) {
                radio.checked = true;
            }
        });
    }
}

// Initialize all functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize address API
    initializeAddressAPI();
    
    // Initialize CCCD scan
    initializeCCCDScan();
    
    // Initialize image previews
    renderImageList('shop_logo_input', 'shop_logo_list', 2);
    renderImageList('shop_banner_input', 'shop_banner_list', 4);
    renderImageList('filechoose', 'filepreview', 5);
    renderImageList('backfilechoose', 'backfilepreview', 5);
    
    // Add form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(form.id)) {
                e.preventDefault();
                showNotification('Vui lòng điền đầy đủ thông tin bắt buộc', 'error');
            }
        });
    });
    
    // TẠM THỜI BỎ LOADING STATES
    // const submitButtons = document.querySelectorAll('button[type="submit"]');
    // submitButtons.forEach(button => {
    //     button.dataset.originalText = button.innerHTML;
    //     button.addEventListener('click', function() {
    //         if (validateForm(button.closest('form').id)) {
    //             setLoadingState(button, true);
    //         }
    //     });
    // });
    
    // Add smooth scrolling to anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            smoothScrollTo(targetId);
        });
    });
});

// Export functions for global use
window.updateStepper = updateStepper;
window.showNotification = showNotification;
window.validateForm = validateForm;
window.smoothScrollTo = smoothScrollTo;
window.setLoadingState = setLoadingState;