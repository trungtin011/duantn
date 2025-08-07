import { initializeCheckoutFormHandler } from './checkout-form-handler.js';
import { initializeDiscountHandler } from './discount-handler.js';
import { initializeTotalCalculator } from './total-calculator.js';
import { initializeUIHandler } from './ui-handler.js';
import { initializeShippingCalculator } from './shipping-calculator.js';
import { initializeAddressDisplay } from './address-display-handler.js';
import { initializePointsHandler } from './points-handler.js';

document.addEventListener('DOMContentLoaded', function() {
    
    const a = window.checkoutData;
    if (!a) {
        console.error("Có lỗi gì đó đã xảy ra.");
        return;
    }

    initializeCheckoutFormHandler();
    initializeAddressDisplay(a.addresses);
    initializeShippingCalculator(a.addresses, a.shops, a.csrfToken);
    initializeDiscountHandler(window.calculateSubtotal(), a.applyDiscountUrl, a.csrfToken);
    initializeTotalCalculator();
    initializeUIHandler();
    initializePointsHandler(a.user_points, window.calculateSubtotal());
}); 