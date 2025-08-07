
function initializeShippingCalculator(addresses, shops, csrfToken) {
    let isFetchingShippingFee = false;
    let feeCache = {};
    let lastAddressId = null;

    function showLoading(message = "Đang tính phí vận chuyển...") {
        const overlay = document.createElement('div');
        overlay.id = 'shipping-fee-overlay';
        overlay.style.cssText = 'position:fixed;top:0;left:0;width:100vw;height:100vh;background-color:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;z-index:9999;';
        overlay.innerHTML = `<div style="background:white;padding:20px 30px;border-radius:8px;font-size:16px;">${message}</div>`;
        document.body.appendChild(overlay);
    }

    function hideLoading() {
        const overlay = document.getElementById('shipping-fee-overlay');
        if (overlay) overlay.remove();
    }

    async function fetchShippingFee(addressId, shop) {
        try {
            const response = await fetch('/calculate-shipping-fee', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    address_id: addressId,
                    shop_id: shop.id
                })
            });
            const data = await response.json();
            return (data && !data.error) ? Number(data.shipping_fee) || 0 : 0;
        } catch (error) {
            console.error('Lỗi:', error);
            return 0;
        }
    }

    async function calculateAndDisplayShippingFee(address) {
        if (!address || isFetchingShippingFee) return;
        // Nếu đã có phí cho địa chỉ này trong cache thì chỉ hiển thị lại, không gọi API
        if (lastAddressId === address.id && Object.keys(feeCache).length > 0) {
            let total_shipping_fee = 0;
            Object.entries(feeCache).forEach(([shopId, fee]) => {
                total_shipping_fee += fee;
                const el = document.getElementById('shipping-fee-shop-' + shopId);
                if (el) el.textContent = fee.toLocaleString('vi-VN') + '₫';
            });
            document.getElementById('total_shipping_fee').textContent = total_shipping_fee.toLocaleString('vi-VN') + '₫';
            if (typeof window.updateTotal === 'function') {
                window.updateTotal();
            }
            return;
        }
        isFetchingShippingFee = true;
        showLoading();
        try {
            const feePromises = shops.map(shop => fetchShippingFee(address.id, shop));
            const fees = await Promise.all(feePromises);
            feeCache = {};
            fees.forEach((fee, index) => {
                feeCache[shops[index].id] = fee;
            });
            let total_shipping_fee = 0;
            Object.entries(feeCache).forEach(([shopId, fee]) => {
                total_shipping_fee += fee;
                const el = document.getElementById('shipping-fee-shop-' + shopId);
                if (el) el.textContent = fee.toLocaleString('vi-VN') + '₫';
            });
            document.getElementById('total_shipping_fee').textContent = total_shipping_fee.toLocaleString('vi-VN') + '₫';
            if (typeof window.updateTotal === 'function') {
                window.updateTotal();
            }
            lastAddressId = address.id;
        } finally {
            hideLoading();
            isFetchingShippingFee = false;
        }
    }
    
    // Auto-calculate for default/initial address
    (function autoCalculateDefaultAddressFee() {
        let checkedRadio = document.querySelector('input[name="receiver_address_id"]:checked');
        let defaultAddress = null;

        if (checkedRadio) {
            defaultAddress = addresses.find(addr => addr.id == checkedRadio.value);
        } else {
            defaultAddress = addresses.find(addr => addr.is_default == 1) || (addresses.length > 0 ? addresses[0] : null);
            if (defaultAddress) {
                const radio = document.querySelector('input[name="receiver_address_id"][value="' + defaultAddress.id + '"]');
                if (radio) radio.checked = true;
            }
        }

        if (defaultAddress) {
            calculateAndDisplayShippingFee(defaultAddress);
        }
    })();
    
    // Event listener for address change
    document.querySelectorAll('input[name="receiver_address_id"]').forEach(function (radio) {
        radio.addEventListener('change', async function () {
            if (isFetchingShippingFee) return;
            const address = addresses.find(addr => addr.id == this.value);
            if(address) {
                calculateAndDisplayShippingFee(address);
            }
        });
    });
}

export { initializeShippingCalculator }; 