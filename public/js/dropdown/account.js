

// Dropdown functionality
const dropdownToggle = document.getElementById('userDropdownToggle');
const dropdownMenu = document.getElementById('userDropdownMenu');
let isDropdownOpen = false;

// Toggle dropdown
dropdownToggle.addEventListener('click', function (e) {
    e.stopPropagation();
    toggleDropdown();
});

// Close dropdown when clicking outside
document.addEventListener('click', function (e) {
    if (isDropdownOpen && !dropdownMenu.contains(e.target) && !dropdownToggle.contains(e.target)) {
        closeDropdown();
    }
});

// Close dropdown on escape key
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && isDropdownOpen) {
        closeDropdown();
    }
});

function toggleDropdown() {
    if (isDropdownOpen) {
        closeDropdown();
    } else {
        openDropdown();
    }
}

function openDropdown() {
    dropdownMenu.classList.add('show');
    dropdownToggle.style.backgroundColor = 'var(--hover-color)';
    isDropdownOpen = true;
}

function closeDropdown() {
    dropdownMenu.classList.remove('show');
    dropdownToggle.style.backgroundColor = 'var(--primary-color)';
    isDropdownOpen = false;
}

// Logout function
function logout() {
    if (confirm('Bạn có chắc chắn muốn đăng xuất?')) {
        alert('Đã đăng xuất thành công!');
        closeDropdown();
        // Redirect to login page or home page
        // window.location.href = '/login';
    }
}

// Add click handlers for menu items
document.querySelectorAll('.dropdown-item-custom').forEach(item => {
    item.addEventListener('click', function (e) {
        if (!this.onclick) { // Don't close for logout button
            e.preventDefault();
            console.log('Navigating to:', this.textContent.trim());
            closeDropdown();
            // Add your navigation logic here
        }
    });
});

// Search functionality
const searchInput = document.querySelector('.search-box');
searchInput.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const searchTerm = this.value.trim();
        if (searchTerm) {
            console.log('Searching for:', searchTerm);
            // Add your search logic here
            alert(`Tìm kiếm: "${searchTerm}"`);
        }
    }
});

// Add hover effects for better UX
document.querySelectorAll('.icon-btn').forEach(btn => {
    btn.addEventListener('mouseenter', function () {
        this.style.transform = 'scale(1.1)';
    });

    btn.addEventListener('mouseleave', function () {
        this.style.transform = 'scale(1)';
    });
});

