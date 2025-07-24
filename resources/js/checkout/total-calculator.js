
function parseCurrency(str) {
    return parseFloat(str.replace(/[^\d]/g, '')) || 0;
}
function calculateSubtotal() {
    let subtotal = 0;
    for (const shop of window.checkoutData.shops) {
        const shopTotalElems = document.getElementById('total-product-price-shop-' + shop.id);
        subtotal += parseCurrency(shopTotalElems.textContent ?? '0');
    }
    return subtotal;
}
function updateTotal() {
    const subtotal = calculateSubtotal();
    console.log('tính tổng: ' + subtotal);
    const discount_amount = parseCurrency(document.getElementById('discount_amount')?.textContent ?? '0');
    const total_shipping_fee = parseCurrency(document.getElementById('total_shipping_fee')?.textContent ?? '0');
    const points_amount = parseCurrency(document.getElementById('points_amount')?.textContent ?? '0');

    let total = subtotal - discount_amount + total_shipping_fee - points_amount;


    const totalAmountEl = document.getElementById('total_amount');
    if (totalAmountEl) {
        totalAmountEl.textContent = new Intl.NumberFormat('vi-VN').format(total) + '₫';
    }

    const placeOrderBtn = document.getElementById('place-order-btn');
    if(placeOrderBtn) {
        placeOrderBtn.innerHTML = `<i class="fas fa-shopping-bag mr-2"></i> Đặt hàng (${new Intl.NumberFormat('vi-VN').format(total)}₫)`;
    }
}

// Make updateTotal global so it can be called from other scripts
window.updateTotal = updateTotal;
window.calculateSubtotal = calculateSubtotal;

function initializeTotalCalculator() {
    updateTotal();
}


export { initializeTotalCalculator, updateTotal }; 