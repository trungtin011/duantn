// checkout-form-handler.js
function initializeCheckoutFormHandler() {
    let isSubmitting = false;

    const placeOrderBtn = document.getElementById('place-order-btn');
    if (placeOrderBtn) {
        placeOrderBtn.addEventListener('click', function() {
            if (isSubmitting) return;

            if (validateForm()) {
                collectAndSubmitData();
            }
        });
    }

    function validateForm() {
        const selectedAddress = document.querySelector('input[name="receiver_address_id"]:checked');
        if (!selectedAddress) {
            showError('Vui lòng chọn địa chỉ giao hàng');
            return false;
        }

        const selectedPayment = document.querySelector('input[name="payment"]:checked');
        if (!selectedPayment) {
            showError('Vui lòng chọn phương thức thanh toán');
            return false;
        }
        return true;
    }

    function collectDiscountAmount() {
        const discountAmount = parseInt(document.getElementById('discount_amount').textContent.replace(/[^\d]/g, '')) || 0;
        const shopDiscountAmount = parseInt(document.getElementById('discount_shop_fee').textContent.replace(/[^\d]/g, '')) || 0;
        const discountShippingFee = parseInt(document.getElementById('discount_shipping_fee').textContent.replace(/[^\d]/g, '')) || 0;
        const pointsAmount = parseInt(document.getElementById('points_amount').textContent.replace(/[^\d]/g, '')) || 0;
        return discountAmount + shopDiscountAmount + discountShippingFee + pointsAmount;
    }

    function collectFormData(token) {
        return {
            selected_address_id: document.querySelector('input[name="receiver_address_id"]:checked')?.value,
            payment_method: document.querySelector('input[name="payment"]:checked')?.value,
            shop_notes: collectShopNotes(),
            subtotal: document.getElementById('subtotal').textContent.replace(/[^\d]/g, '') || 0,
            discount_amount: collectDiscountAmount(),
            shipping_fee: document.getElementById('total_shipping_fee').textContent.replace(/[^\d]/g, '') || 0,
            total_amount: document.getElementById('total_amount').textContent.replace(/[^\d]/g, '') || 0,
            discount_code: document.querySelector('input[name="discount_code"]')?.value || null,
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            used_points: document.getElementById('used_points').value || 0,
            coupons_code: collectCouponsCode(),
            recaptcha_token: token // Thêm token reCAPTCHA
        };
    }

    function collectShopNotes() {
        const notes = {};
        document.querySelectorAll('textarea[name^="note_for_shop"]').forEach(textarea => {
            const shopId = textarea.getAttribute('data-shop-id');
            const note = textarea.value.trim();
            if (note) {
                notes[shopId] = note;
            }
        });
        return notes;
    }

    function collectCouponsCode() {
        let couponCode = [];
        let codes = JSON.parse(localStorage.getItem('coupons_code') || '[]');
        codes.forEach(code => {
            couponCode.push({
                code: code.couponData.code,
                shopId: code.shopId
            });
        });
        return couponCode;
    }

    async function collectAndSubmitData() {
        isSubmitting = true;

        const submitBtn = document.getElementById('place-order-btn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';
        submitBtn.disabled = true;

        try {
            // Kiểm tra script reCAPTCHA
            if (typeof grecaptcha === 'undefined') {
                throw new Error('reCAPTCHA script chưa được tải');
            }

            // Lấy token reCAPTCHA
            const token = await new Promise((resolve, reject) => {
                grecaptcha.ready(function() {
                const siteKey = '6LfatpErAAAAAEioR0Zd9nmkFwVEX6nzGGaY81CI';
                grecaptcha.execute(siteKey, { action: 'submit_order' })
                        .then(resolve)
                        .catch(reject);
                });
            });

            // Thu thập dữ liệu form với token
            const formData = collectFormData(token);
            console.log('Dữ liệu gửi đi:', formData);

            // Gán token vào input ẩn (dự phòng cho form HTML)
            const recaptchaInput = document.getElementById('recaptcha_token');
            if (recaptchaInput) {
                recaptchaInput.value = token;
            }

            const response = await fetch('/customer/checkout/submit', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': formData._token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(formData)
            });

            if (!response.ok) {
                const err = await response.json();
                throw new Error(err.message || `HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Phản hồi từ server:', data);

            if (data.success) {
                showSuccess('Đặt hàng thành công!');
                if (data.redirectUrl) {
                    window.location.href = data.redirectUrl;
                }
            } else {
                showError(data.message || 'Có lỗi xảy ra khi đặt hàng');
            }
        } catch (error) {
            console.error('Lỗi:', error);
            showError('Có lỗi xảy ra: ' + error.message);
        } finally {
            isSubmitting = false;
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    function showError(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in-down';
        toast.innerHTML = `<div class="flex items-center"><i class="fas fa-exclamation-circle mr-2"></i><span>${message}</span></div>`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    function showSuccess(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in-down';
        toast.innerHTML = `<div class="flex items-center"><i class="fas fa-check-circle mr-2"></i><span>${message}</span></div>`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }
}

export { initializeCheckoutFormHandler };