// Stepper
function updateStepper(currentStep) {
    const steps = document.querySelectorAll('.stepper-step');
    const lines = document.querySelectorAll('.stepper-line');

    steps.forEach((step, index) => {
        const dot = step.querySelector('.step-dot');
        const label = step.querySelector('.step-label');

        dot.className = 'step-dot';
        label.className = 'step-label';

        if (index < currentStep) {
            dot.classList.add('done');
            label.classList.add('done');
        } else if (index === currentStep) {
            dot.classList.add('active');
            label.classList.add('active');
        } else {
            dot.classList.add('disabled');
            label.classList.add('disabled');
        }
    });

    lines.forEach((line, index) => {
        line.className = 'stepper-line';
        if (index < currentStep) {
            line.classList.add('done');
        } else {
            line.classList.add('disabled');
        }
    });
}

// Modal register
function openModal() {
    document.getElementById('addressModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('addressModal').classList.add('hidden');
}

// Dropdown register1
function toggleSection(sectionId, button) {
    const section = document.getElementById(sectionId);
    const icon = button.querySelector("i");

    section.classList.toggle("hidden");

    if (section.classList.contains("hidden")) {
        button.innerHTML = 'Mở rộng <i class="fa-solid fa-chevron-down ms-2"></i>';
    } else {
        button.innerHTML = 'Thu gọn <i class="fa-solid fa-chevron-up ms-2"></i>';
    }
}

// Dropdown register2
// Sửa lỗi: chỉ addEventListener nếu tồn tại phần tử

document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('toggle-address-dropdown');
    const dropdownPanel = document.getElementById('address-dropdown-panel');
    const label = document.getElementById('address-dropdown-label');
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    const addressDetail = document.getElementById('address-detail');
    const addressError = document.getElementById('address-error');
    const selectedAddress = document.getElementById('selected-address');

    // Toggle dropdown visibility
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            dropdownPanel.classList.toggle('hidden');
        });
    }

    // Fetch tỉnh/thành
    fetch("https://provinces.open-api.vn/api/?depth=1")
        .then(res => res.json())
        .then(data => {
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.code;
                option.textContent = item.name;
                provinceSelect.appendChild(option);
            });
        });

    // Khi chọn tỉnh
    provinceSelect.addEventListener('change', () => {
        districtSelect.disabled = false;
        districtSelect.innerHTML = '<option selected disabled>Chọn Quận / Huyện</option>';
        wardSelect.innerHTML = '<option selected disabled>Chọn Phường / Xã</option>';
        wardSelect.disabled = true;

        fetch(`https://provinces.open-api.vn/api/p/${provinceSelect.value}?depth=2`)
            .then(res => res.json())
            .then(data => {
                data.districts.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.code;
                    option.textContent = item.name;
                    districtSelect.appendChild(option);
                });
                updateAddressLabel();
            });
    });

    // Khi chọn huyện
    districtSelect.addEventListener('change', () => {
        wardSelect.disabled = false;
        wardSelect.innerHTML = '<option selected disabled>Chọn Phường / Xã</option>';

        fetch(`https://provinces.open-api.vn/api/d/${districtSelect.value}?depth=2`)
            .then(res => res.json())
            .then(data => {
                data.wards.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.name;
                    option.textContent = item.name;
                    wardSelect.appendChild(option);
                });
                updateAddressLabel();
            });
    });

    wardSelect.addEventListener('change', updateAddressLabel);
    addressDetail.addEventListener('input', updateAddressLabel);

    function updateAddressLabel() {
        const province = provinceSelect.options[provinceSelect.selectedIndex]?.text || '';
        const district = districtSelect.options[districtSelect.selectedIndex]?.text || '';
        const ward = wardSelect.options[wardSelect.selectedIndex]?.text || '';
        const detail = addressDetail.value.trim();

        if (!detail) {
            addressError.classList.remove('hidden');
            addressDetail.classList.add('border-red-500');
        } else {
            addressError.classList.add('hidden');
            addressDetail.classList.remove('border-red-500');
        }

        const fullAddress = [detail, ward, district, province].filter(Boolean).join(" / ");
        selectedAddress.value = fullAddress;
        label.textContent = fullAddress || 'Chọn địa chỉ';
    }

    // Đóng dropdown nếu click ra ngoài
    document.addEventListener('click', (e) => {
        if (!toggleBtn.contains(e.target) && !dropdownPanel.contains(e.target)) {
            dropdownPanel.classList.add('hidden');
        }
    });
});