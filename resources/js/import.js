
// Import.js - Central file for importing all JavaScript modules
// This file consolidates all JavaScript imports for the project
// Last updated: 2025

// ========================================
// ROOT LEVEL JAVASCRIPT FILES
// ========================================
import './app.js';
import './bootstrap.js';
import './home.js';
import './seller_chat.js';
import './chat.js';
import './script.js';
import './echo.js';

// ========================================
// ADMIN JAVASCRIPT FILES
// ========================================
import './admin/admin.js';
import './admin/category.js';
import './admin/create-product.js';
import './admin/order.js';
import './admin/product.js';

// ========================================
// CHECKOUT JAVASCRIPT FILES
// ========================================
import './checkout/address-display-handler.js';
import './checkout/checkout-form-handler.js';
import './checkout/discount-handler.js';
import './checkout/ghn-address.js';
import './checkout/index.js';
import './checkout/points-handler.js';
import './checkout/shipping-calculator.js';
import './checkout/total-calculator.js';
import './checkout/ui-handler.js';

// ========================================
// SELLER JAVASCRIPT FILES
// ========================================
// import './seller/chat-auto-reply.js';
import './seller/product.js';
import './seller/qa-chat.js';
import './seller/register.js';

// ========================================
// PUBLIC JAVASCRIPT FILES (Optional)
// ========================================
// Note: These files are in the public/js directory
// Uncomment if you need to import them based on your build configuration
// import '../public/js/home.js';
// import '../public/js/snow-animation.js';
// import '../public/js/seller/address-data.js';
// import '../public/js/seller/product.js';
// import '../public/js/seller/register.js';

// ========================================
// EXPORT FOR MODULE USAGE
// ========================================
// If you need to export specific functionality, you can do so here
export default {
    // Add any specific exports here if needed
    version: '1.0.0',
    description: 'Central import file for all JavaScript modules',
    lastUpdated: '2025',
    totalFiles: 26 // Total number of JavaScript files imported
};