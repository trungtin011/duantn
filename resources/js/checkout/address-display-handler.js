function initializeAddressDisplay(addresses) {

    function displaySelectedAddress(address) {
        if (!address) return;
        
        const container = document.getElementById('selected-address-container');
        const content = document.getElementById('selected-address-content');
        const badge = container.querySelector('.address-badge');
        
        content.innerHTML = `
            <p class="font-medium mb-1">${address.receiver_name}</p>
            <p class="text-gray-600 text-sm mb-1">(+84) ${address.receiver_phone}</p>
            <p class="text-gray-600">${address.address}, ${address.ward}, ${address.district}, ${address.province}</p>
        `;
        
        if (address.is_default) {
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
        
        container.classList.remove('hidden');
    }
    
    function toggleAddressList() {
        const addressList = document.getElementById('address-list');
        addressList.classList.toggle('expanded');
    }
    window.toggleAddressList = toggleAddressList;

    // Auto-select default address on page load
    let defaultAddress = null;
    const checkedRadio = document.querySelector('.address-radio:checked');
    if (checkedRadio) {
        defaultAddress = JSON.parse(checkedRadio.getAttribute('data-address'));
    } else if (addresses.length > 0) {
        defaultAddress = addresses.find(addr => addr.is_default == 1) || addresses[0];
        const radioToSelect = document.querySelector(`.address-radio[value="${defaultAddress.id}"]`);
        if(radioToSelect) radioToSelect.checked = true;
    }
    
    if (defaultAddress) {
        displaySelectedAddress(defaultAddress);
    }
    
    // Handle user selecting a new address
    document.querySelectorAll('.address-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                const address = JSON.parse(this.getAttribute('data-address'));
                displaySelectedAddress(address);
                document.getElementById('address-list').classList.remove('expanded');
            }
        });
    });
}

export { initializeAddressDisplay }; 