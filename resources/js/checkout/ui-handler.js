function initializeUIHandler() {
    // Handle showing the address form
    const showAddressFormBtn = document.getElementById('showAddressForm');
    if (showAddressFormBtn) {
        showAddressFormBtn.addEventListener('click', function() {
            const form = document.getElementById('createAddressForm');
            form.classList.toggle('hidden');
            this.innerHTML = form.classList.contains('hidden') 
                ? '<i class="fas fa-plus mr-2"></i> Thêm địa chỉ mới' 
                : '<i class="fas fa-times mr-2"></i> Hủy thêm địa chỉ';
        });
    }

    // Handle canceling the address form
    const cancelAddressFormBtn = document.getElementById('cancelAddressForm');
    if (cancelAddressFormBtn) {
        cancelAddressFormBtn.addEventListener('click', function() {
            document.getElementById('createAddressForm').classList.add('hidden');
            const showBtn = document.getElementById('showAddressForm');
            if(showBtn) {
                showBtn.innerHTML = '<i class="fas fa-plus mr-2"></i> Thêm địa chỉ mới';
            }
        });
    }

    // Handle payment card selection
    const paymentCards = document.querySelectorAll('.payment-card');
    paymentCards.forEach(card => {
        card.addEventListener('click', function() {
            paymentCards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            const radio = this.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;
        });
    });

    // Handle address type button selection
    const addressTypeBtns = document.querySelectorAll('.address-type-btn');
    addressTypeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            addressTypeBtns.forEach(b => b.classList.remove('bg-primary', 'text-white'));
            this.classList.add('bg-primary', 'text-white');
            const type = this.getAttribute('data-type');
            const input = document.getElementById('address_type_input');
            if(input) {
                input.value = type;
            }
        });
    });

    // Product card hover effect
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.05)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.boxShadow = 'none';
        });
    });
}

export { initializeUIHandler }; 