<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\NotificationsControllers as AdminNotificationsControllers;
use App\Http\Controllers\User\NotificationControllers as UserNotificationControllers;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\User\SuggestedProductController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\VNPayController;

// admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductControllerAdmin;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\UserControllerAdmin;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\RefundController;
use App\Http\Controllers\Admin\BrandController;

// seller
use App\Http\Controllers\Seller\ProductControllerSeller;
use App\Http\Controllers\Seller\RegisterSeller\RegisterShopController;
use App\Http\Controllers\Seller\OcrController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\ComboController;

//user
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserAddressController;
use App\Http\Controllers\User\WishlistController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\ReviewLikeController;
use App\Http\Controllers\User\CheckinController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ShippingFeeController;

// trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// trang lỗi
Route::get('/404', function () {
    return view('error.404NotFound');
})->name('404');
Route::get('/403', function () {
    return view('error.403');
})->name('403');

// trang đăng ký, đăng nhập, quên mật khẩu
Route::get('/signup', function () {
    return view('auth.register');
})->name('signup');
Route::post('/signup', [RegisterController::class, 'register'])->name('register.post');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::get('/auth/google', [LoginController::class, 'redirectToGoogle'])->name('auth.google.login');
Route::get('/auth/google/callback', [LoginController::class, 'handleGoogleCallback']);
Route::get('/auth/facebook', [LoginController::class, 'redirectToFacebook'])->name('auth.facebook.login');
Route::get('/auth/facebook/callback', [LoginController::class, 'handleFacebookCallback']);

// admin routes
Route::prefix('admin')->middleware('CheckRole:admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/notification', [AdminNotificationsControllers::class, 'index'])->name('admin.notifications.index');

    // products admin
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductControllerAdmin::class, 'index'])->name('admin.products.index');
        Route::get('/create', [ProductControllerAdmin::class, 'create'])->name('admin.products.create');
        Route::post('/', [ProductControllerAdmin::class, 'store'])->name('admin.products.store');
        Route::get('/{product}/variants/create', [ProductControllerAdmin::class, 'createVariant'])->name('admin.products.variants.create');
        Route::post('/{product}/variants', [ProductControllerAdmin::class, 'storeVariant'])->name('admin.products.variants.store');
        Route::get('/{id}/edit', [ProductControllerAdmin::class, 'edit'])->name('admin.products.edit');
        Route::put('/{id}', [ProductControllerAdmin::class, 'update'])->name('admin.products.update');
        Route::delete('/{id}', [ProductControllerAdmin::class, 'destroy'])->name('admin.products.destroy');
        Route::get('/{id}', [ProductControllerAdmin::class, 'show'])->name('admin.products.show');
        Route::get('/get-sub-brands', [ProductControllerAdmin::class, 'getSubBrands'])->name('admin.get-sub-brands');
        Route::get('/get-sub-categories', [ProductControllerAdmin::class, 'getSubCategories'])->name('admin.get-sub-categories');
    });

    // attributes
    Route::prefix('/attributes')->group(function () {
        Route::get('/', [AttributeController::class, 'index'])->name('admin.attributes.index');
        Route::get('/{id}/edit', [AttributeController::class, 'edit'])->name('admin.attributes.edit');
        Route::post('', [AttributeController::class, 'store'])->name('admin.attributes.store');
        Route::post('/list', [AttributeController::class, 'storeList'])->name('admin.attributes.storeList');
        Route::put('/{id}', [AttributeController::class, 'update'])->name('admin.attributes.update');
        Route::delete('/{id}', [AttributeController::class, 'destroy'])->name('admin.attributes.destroy');
    });

    // products orders
    Route::prefix('orders')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
        Route::put('/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
        Route::post('/{id}/refund', [AdminOrderController::class, 'refund'])->name('admin.orders.refund');
        Route::get('/report', [AdminOrderController::class, 'report'])->name('admin.orders.report');
    });

    // products categories
    Route::prefix('categories')->group(function () {
        Route::get('/', [AdminCategoryController::class, 'index'])->name('admin.categories.index');
        Route::post('/', [AdminCategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('/{id}/edit', [AdminCategoryController::class, 'edit'])->name('admin.categories.edit');
        Route::put('/{id}', [AdminCategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/{id}', [AdminCategoryController::class, 'destroy'])->name('admin.categories.destroy');
        Route::post('/remove-sub/{id}', [AdminCategoryController::class, 'removeSubCategory'])->name('admin.categories.removeSubCategory');
    });

    // products reviews
    Route::get('/reviews', function () {
        return view('admin.reviews.index');
    })->name('admin.reviews.index');

    // products settings
    Route::get('/settings', function () {
        return view('admin.settings.index');
    })->name('admin.settings.index');

    // users
    Route::prefix('users')->group(function () {
        Route::get('/', [UserControllerAdmin::class, 'index'])->name('admin.users.index');
        Route::get('/{id}/edit', [UserControllerAdmin::class, 'edit'])->name('admin.users.edit');
        Route::put('/{id}', [UserControllerAdmin::class, 'update'])->name('admin.users.update');
        Route::get('/{id}', [UserControllerAdmin::class, 'show'])->name('admin.users.show');
        Route::delete('/{id}', [UserControllerAdmin::class, 'destroy'])->name('admin.users.destroy');
    });

    // reports
    Route::prefix('reports')->group(function () {
        Route::get('/', [AdminReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/{report}', [AdminReportController::class, 'show'])->name('admin.reports.show');
        Route::put('/{report}/update-status', [AdminReportController::class, 'updateStatus'])->name('admin.reports.updateStatus');
    });

    // Admin Coupon Routes
    Route::get('/', [CouponController::class, 'index'])->name('admin.coupon.index');
    Route::get('/create', [CouponController::class, 'create'])->name('admin.coupon.create');
    Route::post('/', [CouponController::class, 'store'])->name('admin.coupon.store');
    Route::get('/{id}/edit', [CouponController::class, 'edit'])->name('admin.coupon.edit');
    Route::put('/{id}', [CouponController::class, 'update'])->name('admin.coupon.update');
    Route::delete('/{id}', [CouponController::class, 'destroy'])->name('admin.coupon.destroy');

    Route::get('refunds', [RefundController::class, 'index'])->name('admin.refunds.index');
    Route::get('refunds/{id}', [RefundController::class, 'show'])->name('admin.refunds.show');
    Route::patch('refunds/{id}', [RefundController::class, 'update'])->name('admin.refunds.update');

    Route::get('/brands', [BrandController::class, 'index'])->name('admin.brands.index');
    Route::get('/brands/create', [BrandController::class, 'create'])->name('admin.brands.create');
    Route::post('/brands', [BrandController::class, 'store'])->name('admin.brands.store');
    Route::get('/brands/{brand}/edit', [BrandController::class, 'edit'])->name('admin.brands.edit');
    Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('admin.brands.update');
    Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('admin.brands.destroy');
});

// seller routes
Route::prefix('seller')->middleware('CheckRole:seller')->group(function () {
    Route::get('/dashboard', function () {
        return view('seller.home');
    })->name('seller.dashboard');

    Route::get('/profile', function () {
        return view('seller.profile');
    })->name('seller.profile');

    Route::get('/order/index', [SellerOrderController::class, 'index'])->name('seller.order.index');
    Route::get('/order/{id}', [SellerOrderController::class, 'show'])->name('seller.order.show');
    // seller.order.shipping
    Route::get('/order/{id}/shipping/create', [SellerOrderController::class, 'createShipping'])->name('seller.order.shipping.create');
    Route::put('/order/{id}/update-status', [SellerOrderController::class, 'updateStatus'])->name('seller.order.update-status');

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductControllerSeller::class, 'index'])->name('seller.products.index');
        Route::get('/create', [ProductControllerSeller::class, 'create'])->name('seller.products.create');
        Route::post('/', [ProductControllerSeller::class, 'store'])->name('seller.products.store');
        Route::get('/{id}/edit', [ProductControllerSeller::class, 'edit'])->name('seller.products.edit');
        Route::put('/{id}', [ProductControllerSeller::class, 'update'])->name('seller.products.update');
        Route::delete('/{id}', [ProductControllerSeller::class, 'destroy'])->name('seller.products.destroy');
        Route::get('/{id}', [ProductControllerSeller::class, 'show'])->name('seller.products.show');
        Route::get('/api/attribute-values', [ProductControllerSeller::class, 'getAttributeValues']);
        Route::post('/upload', [ProductControllerSeller::class, 'uploadImage'])->name('seller.upload.image');

        Route::get('/simple', [ProductController::class, 'simple'])->name('product.simple');
        Route::get('/variable', [ProductController::class, 'variable'])->name('product.variable');
    });

    Route::get('/orders', function () {
        return view('seller.orders');
    })->name('seller.orders');


    Route::get('/combos', [ComboController::class, 'index'])->name('seller.combo.index');
    Route::get('/combos/create', [ComboController::class, 'create'])->name('seller.combo.create');
    Route::post('/combos', [ComboController::class, 'store'])->name('seller.combo.store');
    Route::get('/combos/{id}/edit', [ComboController::class, 'edit'])->name('seller.combo.edit');
    Route::patch('/combos/{id}', [ComboController::class, 'update'])->name('seller.combo.update');
    Route::delete('/combos/{id}', [ComboController::class, 'destroy'])->name('seller.combo.destroy');
});



Route::prefix('customer')->group(function () {
    // customer routes
    Route::get('/products/product_detail/{slug}', [ProductController::class, 'show'])->name('product.show');
    Route::post('/product/{product}/review', [ProductReviewController::class, 'store'])->name('product.review')->middleware('auth');
    Route::post('/review/{review}/like', [ReviewLikeController::class, 'toggle'])->middleware('auth');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::middleware('CheckRole:customer')->group(function () {
        Route::get('/seller/register', [RegisterShopController::class, 'showStep1'])->name('seller.register');

        // Trang thông tin người dùng
        Route::prefix('user/account')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('account.profile');
            Route::get('/profile', [UserController::class, 'edit'])->name('account.profile');
            Route::post('/profile', [UserController::class, 'update'])->name('account.profile.update');

            Route::get('/password', [UserController::class, 'changePasswordForm'])->name('account.password');
            Route::post('/password', [UserController::class, 'updatePassword'])->name('account.password.update');
        });

        // Trang địa chỉ người dùng
        Route::prefix('user/account/addresses')->group(function () {
            Route::get('/', [UserAddressController::class, 'index'])->name('account.addresses');
            Route::get('/create', [UserAddressController::class, 'create'])->name('account.addresses.create');
            Route::post('/', [UserAddressController::class, 'store'])->name('account.addresses.store');
            Route::get('/{address}/edit', [UserAddressController::class, 'edit'])->name('account.addresses.edit');
            Route::put('/{address}', [UserAddressController::class, 'update'])->name('account.addresses.update');
            Route::delete('/{address}', [UserAddressController::class, 'destroy'])->name('account.addresses.delete');
            Route::get('/{address}/set-default', [UserAddressController::class, 'setDefault'])->name('account.addresses.set-default');
        });

        // Trang tích điểm
        Route::prefix('user/account/points')->group(function () {
            Route::get('/', [UserController::class, 'points'])->name('account.points');
            Route::get('/checkin', [CheckinController::class, 'index'])->name('account.checkin');
            Route::post('/checkin', [CheckinController::class, 'store'])->name('account.checkin.store');
        });

        // Trang yêu thích
        Route::get('/user/account/wishlist', [WishlistController::class, 'index'])->name('wishlist');

        // Trang giỏ hàng
        Route::prefix('cart')->group(function () {
            Route::get('/', [CartController::class, 'index'])->name('cart');
            Route::post('/add', [CartController::class, 'addToCart'])->name('cart.add');
            Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
            Route::put('/update/{id}', [CartController::class, 'update'])->name('cart.update');
        });

        // Trang liên hệ
        Route::get('/contact', function () {
            return view('user.contact');
        })->name('contact');

        // Trang giới thiệu
        Route::get('/about', function () {
            return view('user.about');
        })->name('about');

        // Trang thanh toán
        Route::get('/client/checkout', function () {
            return view('client.checkout');
        })->name('checkout');

        Route::prefix('user/order')->group(function () {
            Route::get('/order-history', [OrderController::class, 'index'])->name('order_history');
            Route::get('/orders/{id}', [UserOrderController::class, 'show'])->name('user.orders.show');
            Route::post('/orders/{orderID}/cancel', [OrderController::class, 'cancel'])->name('user.orders.cancel');
            Route::post('/orders/{orderID}/reorder', [OrderController::class, 'reorder'])->name('user.orders.reorder');
        });

        // Route báo cáo sản phẩm
        Route::post('/product/{product}/report', [ProductController::class, 'reportProduct'])->name('product.report');
    });

    //checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
    Route::get('/checkout/success/{order_code}', [CheckoutController::class, 'successPayment'])->name('checkout.success');
    Route::get('/checkout/failed/{order_code}', [CheckoutController::class, 'failedPayment'])->name('checkout.failed');
    Route::get('/checkout/momo/return', [CheckoutController::class, 'momoReturn'])->name('payment.momo.return');
    Route::post('/checkout/momo/ipn', [CheckoutController::class, 'momoIpn'])->name('payment.momo.ipn');
});

// seller registration routes
Route::prefix('seller')->group(function () {
    Route::get('/register', [RegisterShopController::class, 'showStep1'])->name('seller.register');
    Route::post('/register', [RegisterShopController::class, 'step1'])->name('seller.register.step1');
    Route::get('/register1', [RegisterShopController::class, 'showStep2'])->name('seller.register.step2');
    Route::post('/register1', [RegisterShopController::class, 'step2'])->name('seller.register.step2.post');
    Route::get('/register2', [RegisterShopController::class, 'showStep3'])->name('seller.register.step3');
    Route::post('/register2', [RegisterShopController::class, 'step3'])->name('seller.register.step3.post');
    Route::get('/register3', [RegisterShopController::class, 'showStep4'])->name('seller.register.step4');
    Route::post('/register3', [RegisterShopController::class, 'step4'])->name('seller.register.step4.post');
    Route::get('/register4', [RegisterShopController::class, 'showStep5'])->name('seller.register.step5');
    Route::post('/register4', [RegisterShopController::class, 'finish'])->name('seller.register.finish');
    Route::get('/settings', [\App\Http\Controllers\Seller\SellerSettingsController::class, 'index'])->name('seller.settings');
    Route::post('/settings', [\App\Http\Controllers\Seller\SellerSettingsController::class, 'update'])->name('seller.settings');
});

// API OCR CCCD cho frontend JS
Route::post('/seller/ocr/scan-cccd', [OcrController::class, 'upload'])->name('seller.ocr.scancccd');
Route::get('/ocr', [OcrController::class, 'index'])->name('ocr.index');
Route::post('/ocr', [OcrController::class, 'upload'])->name('ocr.upload');
Route::get('/orders', [UserOrderController::class, 'index'])->name('user.orders');


Route::post('/update-session', [App\Http\Controllers\SessionController::class, 'updateSession'])->name('update-session');
Route::post('/calculate-shipping-fee', [ShippingFeeController::class, 'calculateShippingFee'])->name('calculate-shipping-fee');
// API - VNPAY   
Route::post('/payment/vnpay/ipn', [VNPayController::class, 'ipn'])->name('payment.vnpay.ipn');
Route::get('/payment/vnpay/return', [VNPayController::class, 'vnpayReturn'])->name('payment.vnpay.return');
Route::get('/orders/{id}', [UserOrderController::class, 'show'])->name('user.order.show');
