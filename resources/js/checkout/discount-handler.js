function initializeDiscountHandler(subtotal, applyDiscountUrl, csrfToken) {
    const discountForm = document.getElementById('discount-form');
    if (discountForm) {
        discountForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const codeInput = discountForm.querySelector('input[name="discount_code"]');
            const discountCode = codeInput ? codeInput.value.trim() : '';
            if (!discountCode) {
                showError('Vui lòng nhập mã giảm giá');
                return;
            }
            try {
                const response = await fetch(applyDiscountUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        discount_code: discountCode,
                        subtotal: subtotal,
                    })
                });
                const data = await response.json();
                console.log(data);
                if(data.discount_amount) {
                    document.getElementById('discount_amount').textContent = Number(data.discount_amount).toLocaleString('vi-VN');
                    showSuccess('Áp dụng mã giảm giá thành công!');
                    document.querySelector('.discount-row-platform').style.display = 'flex';
                    window.updateTotal();
                } else {
                    showError(data.message || 'Mã giảm giá không hợp lệ.');
                }
            } catch (error) {
                console.error(error);
                showError('Có lỗi xảy ra khi áp dụng mã giảm giá');
            }
        });
    }

    window.showError = function(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in-down';
        toast.innerHTML = `<div class="flex items-center"><i class="fas fa-exclamation-circle mr-2"></i><span>${message}</span></div>`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    window.showSuccess = function(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in-down';
        toast.innerHTML = `<div class="flex items-center"><i class="fas fa-check-circle mr-2"></i><span>${message}</span></div>`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }
}

export { initializeDiscountHandler }; 