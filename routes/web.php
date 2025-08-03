<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\QrLoginController;
use App\Http\Controllers\MomoPaymentController;

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
use App\Http\Controllers\Admin\HelpArticleController;
use App\Http\Controllers\Admin\HelpCategoryController;
use App\Http\Controllers\Admin\LogoController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\PostCategoryController;
use App\Http\Controllers\Admin\PostTagController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\HelpController;
use App\Http\Controllers\Admin\AdminShopController;
use App\Http\Controllers\Admin\NotificationsControllers as AdminNotificationsControllers;
// seller
use App\Http\Controllers\Seller\ProductControllerSeller;
use App\Http\Controllers\Seller\RegisterSeller\RegisterShopController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\ComboController;
use App\Http\Controllers\Seller\ReviewController as SellerReviewController;
use App\Http\Controllers\Seller\SellerSettingsController;
use App\Http\Controllers\Seller\ChatSettingsController;
use App\Http\Controllers\Seller\WalletController;
use App\Http\Controllers\Seller\WithdrawController;

//user
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\OrderReviewController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserAddressController;
use App\Http\Controllers\User\WishlistController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\User\CheckinController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ShippingFeeController;
use App\Http\Controllers\User\CouponController as UserCouponController;
use App\Http\Controllers\User\FrontendController;
use App\Http\Controllers\User\UserReviewController;
use App\Http\Controllers\ReviewLikeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\VNPayController;
use App\Http\Controllers\ShopController;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\User\ComboController as UserComboController;

// trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Thông báo
Route::get('/notifications/navbar', function () {
    $user = Auth::user();

    Log::info('Notification check', [
        'user_id' => $user->id,
        'role' => $user->role,
        'shop_id' => $user->shop_id,
    ]);

    $query = Notification::query();

    // Nếu là user bình thường (customer)
    if ($user->role === 'customer') {
        $query->where(function ($q) {
            $q->where('receiver_type', 'user')
                ->orWhere('receiver_type', 'all');
        });
    }

    // Nếu là chủ shop (seller)
    if ($user->role === 'seller') {
        $query->where(function ($q) use ($user) {
            $q->where('receiver_type', 'shop')
                ->orWhere('receiver_type', 'all');
        })->where(function ($q) use ($user) {
            $q->whereNull('shop_id')
                ->orWhere('shop_id', $user->shop_id);
        });
    }

    $notifications = $query->where('status', 'active')
        ->orderByDesc('created_at')
        ->limit(5)
        ->get();

    return response()->json($notifications);
})->middleware('auth');

// trang lỗi
Route::get('/404', function () {
    return view('error.404NotFound');
})->name('404');
Route::get('/403', function () {
    return view('error.403');
})->name('403');

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

    //quản lý user
    Route::get('/user', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/user', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    //quản lý review
    Route::get('/report', [AdminReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/report/{report}', [AdminReportController::class, 'show'])->name('admin.reports.show');
    Route::put('/reports/{id}/status', [AdminReportController::class, 'updateStatus'])->name('report.updateStatus');
    Route::get('/reports/{report}/edit', [AdminReportController::class, 'edit'])->name('admin.reports.edit');

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

    // orders
    Route::prefix('orders')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
        Route::put('/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
        Route::post('/{id}/refund', [AdminOrderController::class, 'refund'])->name('admin.orders.refund');
        Route::get('/report', [AdminOrderController::class, 'report'])->name('admin.orders.report');
    });


    // notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/edit/{id}', [AdminNotificationsControllers::class, 'edit'])->name('admin.notifications.edit');
        Route::get('/create', [AdminNotificationsControllers::class, 'create'])->name('admin.notifications.create');
        Route::get('/', [AdminNotificationsControllers::class, 'index'])->name('admin.notifications.index');
        Route::post('/', [AdminNotificationsControllers::class, 'store'])->name('admin.notifications.store');
        Route::post('/mark-all-as-read', [AdminNotificationsControllers::class, 'markAllAsRead'])->name('admin.notifications.markAllAsRead');
        Route::delete('/{id}', [AdminNotificationsControllers::class, 'destroy'])->name('admin.notifications.destroy');
        Route::get('/{id}', [AdminNotificationsControllers::class, 'show'])->name('admin.notifications.show');
        Route::put('/{id}', [AdminNotificationsControllers::class, 'update'])->name('admin.notifications.update');
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

    // reviews
    Route::get('/reviews', function () {
        return view('admin.reviews.index');
    })->name('admin.reviews.index');

    // settings
    Route::prefix('settings')->group(function () {
        Route::get('/', [AdminSettingsController::class, 'index'])->name('admin.settings.index');
        Route::get('/create', [AdminSettingsController::class, 'create'])->name('admin.settings.create');
        Route::put('/', [AdminSettingsController::class, 'update'])->name('admin.settings.update');
        Route::delete('/logo', [AdminSettingsController::class, 'destroyLogo'])->name('admin.settings.destroyLogo');
        Route::delete('/banner', [AdminSettingsController::class, 'destroyBanner'])->name('admin.settings.destroyBanner');
        Route::delete('/favicon', [AdminSettingsController::class, 'destroyFavicon'])->name('admin.settings.destroyFavicon');
    });

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
    // Post Category
    Route::resource('post-categories', PostCategoryController::class);
    // Post Tag
    Route::resource('post-tags', PostTagController::class);
    // Post
    Route::resource('post', PostController::class);
    // Help
    Route::resource('help-category', HelpCategoryController::class);
    Route::resource('help-article', HelpArticleController::class);
    Route::resource('logo', LogoController::class);

    // Shop Approval (Admin)
    Route::prefix('shops')->group(function () {
        Route::get('/pending', [AdminShopController::class, 'pending'])->name('admin.shops.pending');
        Route::get('/{shop}', [AdminShopController::class, 'show'])->name('admin.shops.show');
        Route::post('/{shop}/approve', [AdminShopController::class, 'approve'])->name('admin.shops.approve');
        Route::post('/{shop}/reject', [AdminShopController::class, 'reject'])->name('admin.shops.reject');
    });
});

// seller routes
Route::prefix('seller')->middleware('CheckRole:seller')->group(function () {
    Route::get('/dashboard', function () {
        return view('seller.home');
    })->name('seller.dashboard');

    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/withdraw', [WithdrawController::class, 'index'])->name('seller.withdraw.index');
    Route::post('/wallet/withdraw', [WithdrawController::class, 'requestWithdraw'])->name('seller.withdraw.request');
    Route::get('/withdraw', [WalletController::class, 'showWithdrawForm'])->name('seller.withdraw.create');
    Route::post('/withdraw', [WalletController::class, 'processWithdraw'])->name('seller.withdraw.store');
    Route::post('/wallet/withdraw', [WalletController::class, 'processWithdraw'])->name('wallet.withdraw.process');
    Route::get('/wallet/withdraw', [WalletController::class, 'showWithdrawForm'])->name('wallet.withdraw');
    Route::post('/wallet/transfer-revenue', [WalletController::class, 'transferCompletedOrdersToWallet'])->name('wallet.transfer.revenue');
    Route::get('linked-banks', [WalletController::class, 'showLinkedBanks'])->name('seller.linked-banks.index');
    Route::post('linked-banks', [WalletController::class, 'storeLinkedBank'])->name('seller.linked-banks.store');
    Route::delete('linked-banks/{id}', [WalletController::class, 'deleteLinkedBank'])->name('seller.linked-banks.destroy');
    Route::post('/wallet/reverse-revenue', [WalletController::class, 'reverseTransferredRevenue'])->name('wallet.reverse.revenue');




    Route::prefix('order')->group(function () {
        Route::get('/', [SellerOrderController::class, 'index'])->name('seller.order.index');
        Route::get('/{code}', [SellerOrderController::class, 'show'])->name('seller.order.show');
        Route::put('/cancel', [SellerOrderController::class, 'cancelOrder'])->name('seller.order.cancel');
        Route::post('/tracking', [SellerOrderController::class, 'trackingOrder'])->name('seller.order.tracking');
        Route::post('/refund', [SellerOrderController::class, 'refundOrder'])->name('seller.order.refund');
        Route::put('/{id}', [SellerOrderController::class, 'confirmOrder'])->name('seller.order.update-status');
        Route::post('/{id}/shipping', [SellerOrderController::class, 'shippingOrder'])->name('seller.order.shipping');
    });

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
    // Route chi tiết sản phẩm
    Route::get('/products/product_detail/{slug}', [ProductController::class, 'show'])->name('product.show');
    // Route xem nhanh sản phẩm
    Route::get('/products/{slug}/quick-view', [ProductController::class, 'quickView'])->name('product.quickView');
    Route::get('/search', [ProductController::class, 'search'])->name('search');
    // Route Hồ sơ người dùng
    Route::get('/profile/{id}', [ShopController::class, 'show'])->name('shop.profile');
    // Route follow shop
    Route::post('/shop/follow/{shop}', [ShopController::class, 'follow'])->name('shop.follow');
    // Route unfollow shop
    Route::post('/shop/unfollow/{shop}', [ShopController::class, 'unfollow'])->name('shop.unfollow');
    // Route bình luận sản phẩm
    Route::post('/product/{productId}/review', [ProductController::class, 'storeReview'])->name('product.review');
    // Route yêu thích sản phẩm
    Route::post('/product/{productId}/toggle-wishlist', [ProductController::class, 'toggleWishlist'])->name('product.toggleWishlist');
    // Route báo cáo sản phẩm
    Route::post('/product/{product}/report', [ProductController::class, 'reportProduct'])->name('product.report');
    // Route Lưu coupon
    Route::post('/coupon/{couponId}/save', [ProductController::class, 'saveCoupon'])->name('coupon.save');
    // Route Lưu tất cả coupon
    Route::post('/shop/{shopId}/save-all-coupons', [ProductController::class, 'saveAllCoupons'])->name('shop.saveAllCoupons');
    // Route like sản phẩm
    Route::post('/review/{review}/like', [ReviewLikeController::class, 'toggle'])->middleware('auth');
    // Route mua ngay
    Route::post('/instant-buy', [ProductController::class, 'instantBuy'])->name('instant-buy');
    // Trang liên hệ
    Route::get('/contact', function () {
        return view('user.contact');
    })->name('contact');

    // Trang giới thiệu
    Route::get('/about', function () {
        return view('user.about');
    })->name('about');

    Route::middleware('CheckRole:customer')->group(function () {
        // Trang đăng ký người dùng
        Route::get('/seller/index/', [RegisterShopController::class, 'index'])->name('seller.index');

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
            Route::post('/selected', [CartController::class, 'updateSelectedProducts'])->name('cart.selected');
        });

        //checkout
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
        Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
        Route::get('/checkout/success/{order_code}', [CheckoutController::class, 'successPayment'])->name('checkout.success');
        Route::get('/checkout/failed/{order_code}', [CheckoutController::class, 'failedPayment'])->name('checkout.failed');

        Route::get('/checkout/momo/return', [MomoPaymentController::class, 'momoReturn'])->name('payment.momo.return');
        Route::post('/checkout/momo/ipn', [MomoPaymentController::class, 'momoIpn'])->name('payment.momo.ipn');
        Route::get('/checkout/momo/payment/{order_code}', [MomoPaymentController::class, 'momoPayment'])->name('checkout.momo.payment');

        Route::post('/payment/vnpay/ipn', [VNPayController::class, 'vnpayIpn'])->name('payment.vnpay.ipn');
        Route::get('/payment/vnpay/return', [VNPayController::class, 'vnpayReturn'])->name('payment.vnpay.return');
        Route::get('/checkout/vnpay/payment/{order_code}', [VNPayController::class, 'vnpayPayment'])->name('checkout.vnpay.payment');

        Route::prefix('user/order')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('order_history');
            Route::get('/{orderID}', [OrderController::class, 'show'])->name('user.order.detail');
            Route::patch('/{order}/cancel', [OrderController::class, 'cancel'])->name('user.order.cancel');
            Route::get('/{orderID}/reorder', [OrderController::class, 'reorder'])->name('user.order.reorder');
            Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        });
    });


});

// thông báo mua hàng của người dùng
Route::get('/recent-purchases', [HomeController::class, 'getRecentPurchases'])->name('recent.purchases');

Route::get('/danh-muc/{slug}', function ($slug) {
    // Logic để lấy danh sách sản phẩm dựa trên slug của danh mục
    // Ví dụ: Truy vấn từ bảng categories và sản phẩm liên quan
    return view('product-list', ['categorySlug' => $slug]);
})->name('category.products');

// Blog
Route::get('/blog', [FrontendController::class, 'blog'])->name('blog');
Route::get('/blog-detail/{slug}', [FrontendController::class, 'blogDetail'])->name('blog.detail');
Route::get('/blog/search', [FrontendController::class, 'blogSearch'])->name('blog.search');
Route::post('/blog/filter', [FrontendController::class, 'blogFilter'])->name('blog.filter');
Route::get('blog-cat/{slug}', [FrontendController::class, 'blogByCategory'])->name('blog.category');
Route::get('blog-tag/{slug}', [FrontendController::class, 'blogByTag'])->name('blog.tag');

// Help
Route::get('/help', [FrontendController::class, 'helpCenter'])->name('help.center');
Route::get('/help/{slug}', [FrontendController::class, 'helpCategory'])->name('help.category');
Route::get('/help/article/{slug}', [FrontendController::class, 'helpDetail'])->name('help.detail');
Route::get('/help/ajax/category/{slug}', [FrontendController::class, 'ajaxHelpByCategory'])->name('help.category.ajax');
Route::get('/help/ajax/{slug}', [HelpCategoryController::class, 'ajaxDetail']);

// seller registration routes
Route::prefix('seller')->middleware('auth')->group(function () {
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
    Route::get('/settings', [SellerSettingsController::class, 'index'])->name('seller.settings');
    Route::post('/settings', [SellerSettingsController::class, 'update'])->name('seller.settings');
    Route::get('/profile', function () {
        return view('seller.profile');
    })->name('seller.profile');
});

// seller chat routes
Route::prefix('seller/chat')->middleware('CheckRole:seller')->group(function () {
    Route::get('/chatautomatically', function () {
        return view('seller.chat.chatautomatically');
    })->name('seller.chat.chatautomatically');
    Route::get('/QA', function () {
        return view('seller.chat.QA');
    })->name('seller.chat.QA');
    Route::get('/A', function () {
        return view('seller.chat.A');
    })->name('seller.chat.A');

    // seller chat settings (auto reply)
    Route::post('/auto-reply-toggle', [ChatSettingsController::class, 'toggleAutoReply'])
        ->middleware('CheckRole:seller')
        ->name('seller.chat.auto_reply_toggle');
});

Route::get('/api/shops-to-chat', [ChatController::class, 'getShopsToChat']);
Route::get('/api/chat/messages/{shopId}', [ChatController::class, 'getMessagesByShopId']);
Route::post('/api/chat/send-message', [ChatController::class, 'sendMessage']);

// API OCR CCCD cho frontend JS

Route::get('/orders', [UserOrderController::class, 'index'])->name('user.orders');


Route::post('/update-session', [App\Http\Controllers\SessionController::class, 'updateSession'])->name('update-session');
Route::post('/calculate-shipping-fee', [ShippingFeeController::class, 'calculateShippingFee'])->name('calculate-shipping-fee');

// API GHN - Lấy danh sách địa chỉ
Route::get('/api/ghn/provinces', [App\Http\Controllers\Service\DeliveryProvider\GHNController::class, 'getProvinces'])->name('api.ghn.provinces');
Route::get('/api/ghn/districts', [App\Http\Controllers\Service\DeliveryProvider\GHNController::class, 'getDistricts'])->name('api.ghn.districts');
Route::get('/api/ghn/wards', [App\Http\Controllers\Service\DeliveryProvider\GHNController::class, 'getWards'])->name('api.ghn.wards');

// API - VNPAY


Route::resource('wishlist', WishlistController::class)->only(['store', 'destroy']);

Route::get('/orders/{id}', [UserOrderController::class, 'show'])->name('user.order.show');

Route::post('/customer/cart/add-multi', [CartController::class, 'addMultiToCart'])->name('cart.addMulti');
Route::post('/customer/apply-app-discount', [UserCouponController::class, 'applyAppDiscount'])->name('customer.apply-app-discount');

Route::middleware(['auth'])->group(function () {
    Route::post('/order-review/store', [OrderReviewController::class, 'store'])->name('reviews.store');
});

Route::get('/login/qr', [QrLoginController::class, 'showQrLogin'])->name('login.qr.generate');
Route::get('/login/qr/generate', [QrLoginController::class, 'generate']);
Route::get('/qr-confirm', function () {
    return view('auth.qr-confirm');
})->name('qr.confirm.form');

Route::post('/qr-confirm', [QrLoginController::class, 'confirm'])->name('qr.confirm.submit');
Route::get('/login/qr/waiting/{token}', [LoginController::class, 'showQrWaiting'])->name('login.qr.waiting');
Route::get('/login/qr/confirm', function (Request $request) {
    $token = $request->input('token');
    $userId = Cache::pull("qr_login_confirm_{$token}");

    if ($userId) {
        Auth::loginUsingId($userId);
        return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
    }

    return redirect()->route('login')->with('error', 'Xác thực QR thất bại hoặc đã hết hạn.');
})->name('qr.confirm.login');



Route::get('/forgot-password', [ForgotPasswordController::class, 'showEmailForm'])->name('password.email.form');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetCode'])->name('password.code.send');

Route::get('/verify-code', [ForgotPasswordController::class, 'showVerifyForm'])->name('password.code.verify.form');
Route::post('/verify-code', [ForgotPasswordController::class, 'verifyCode'])->name('password.code.verify');

Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');
Route::middleware('auth')->group(function () {
    Route::get('/account/password/send-code', [UserController::class, 'changePasswordForm'])->name('account.password.code.form');
    Route::post('/account/password/send-code', [UserController::class, 'requestChangePasswordWithCode'])->name('account.password.request.code');

    Route::get('/account/password/verify', [UserController::class, 'showVerifyCodeForm'])->name('account.password.verify.form');
    Route::post('/account/password/verify', [UserController::class, 'verifyPasswordCode'])->name('account.password.verify.code');

    Route::get('/account/password/reset', [UserController::class, 'showPasswordResetForm'])->name('account.password.reset.form');
    Route::post('/account/password/reset', [UserController::class, 'confirmNewPassword'])->name('account.password.reset.confirm');
});
Route::post('/account/password/request-confirm', [UserController::class, 'requestPasswordChangeConfirm'])->name('account.password.request.confirm');
Route::post('/account/password/confirm-code', [UserController::class, 'confirmPasswordChangeCode'])->name('account.password.confirm.code');
Route::post('/account/password/verify-code', [UserController::class, 'confirmPasswordChangeCode'])
    ->name('account.password.code.verify');
Route::get('/account/password/verify-code', [UserController::class, 'showVerifyCodeForm'])
    ->name('account.password.code.verify.form');

Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

Route::get('/combos', [UserComboController::class, 'index'])->name('combo.index');
Route::get('/combos/{id}', [UserComboController::class, 'show'])->name('combo.show');
Route::post('/cart/add-combo', [CartController::class, 'addComboToCart'])->name('cart.addCombo');



