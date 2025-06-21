<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
// admin
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\UserControllerAdmin;
use App\Http\Controllers\Admin\AdminShopController;
// seller
use App\Http\Controllers\Seller\ProductControllerSeller;
use App\Http\Controllers\Seller\RegisterSeller\RegisterShopController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\ChatSettingsController;
use App\Http\Controllers\Seller\SellerSettingsController;
//user
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserAddressController;
use App\Http\Controllers\ChatController;


// trang chủ
Route::get('/', function () {
    return view('user.home');
})->name('home');

// các trang user
Route::get('/contact', function () {
    return view('user.contact');
})->name('contact');
Route::get('/about', function () {
    return view('user.about');
})->name('about');

Route::get('/user/product/product_detail', function () {
    return view('user.product.product_detail');
})->name('product_detail');

Route::get('/user/cart', function () {
    return view('user.cart');
})->name('cart');
Route::get('/client/wishlist', function () {
    return view('client.wishlist');
})->name('wishlist');
Route::get('/client/checkout', function () {
    return view('client.checkout');
})->name('checkout');
Route::get('/user/order/order-history', function () {
    return view('user.order.order_history');
})->name('order_history');
Route::get('/user/order/order-detail', function () {
    return view('user.order.orderDetail');
})->name('order_detail');


// Route giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

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
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // products admin
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('admin.products.index');
        Route::get('/create', [ProductController::class, 'create'])->name('admin.products.create');
        Route::post('/', [ProductController::class, 'store'])->name('admin.products.store');
        Route::get('/{product}/variants/create', [ProductController::class, 'createVariant'])->name('admin.products.variants.create');
        Route::post('/{product}/variants', [ProductController::class, 'storeVariant'])->name('admin.products.variants.store');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
        Route::put('/{id}', [ProductController::class, 'update'])->name('admin.products.update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
        Route::get('/{id}', [ProductController::class, 'show'])->name('admin.products.show');
        Route::get('/get-sub-brands', [ProductController::class, 'getSubBrands'])->name('admin.get-sub-brands');
        Route::get('/get-sub-categories', [ProductController::class, 'getSubCategories'])->name('admin.get-sub-categories');
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

    // Shop Approval (Admin)
    Route::prefix('shops')->group(function () {
        Route::get('/pending', [AdminShopController::class, 'pending'])->name('admin.shops.pending');
        Route::post('/{shop}/approve', [AdminShopController::class, 'approve'])->name('admin.shops.approve');
        Route::post('/{shop}/reject', [AdminShopController::class, 'reject'])->name('admin.shops.reject');
    });
});

// seller routes
Route::prefix('seller')->middleware('CheckRole:seller')->group(function () {
    Route::get('/dashboard', function () {
        return view('seller.home');
    })->name('seller.dashboard');
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductControllerSeller::class, 'index'])->name('seller.products.index');
        Route::get('/create', [ProductControllerSeller::class, 'create'])->name('seller.products.create');
        Route::post('/', [ProductControllerSeller::class, 'store'])->name('seller.products.store');
        Route::get('/{id}/edit', [ProductControllerSeller::class, 'edit'])->name('seller.products.edit');
        Route::put('/{id}', [ProductControllerSeller::class, 'update'])->name('seller.products.update');
        Route::delete('/{id}', [ProductControllerSeller::class, 'destroy'])->name('seller.products.destroy');
        Route::get('/{id}', [ProductControllerSeller::class, 'show'])->name('seller.products.show');
        Route::get('/api/attribute-values', [ProductControllerSeller::class, 'getAttributeValues']);
    });
    Route::get('/orders', function () {
        return view('seller.orders');
    })->name('seller.orders');
});

// customer routes
Route::middleware('CheckRole:customer')->group(function () {
    Route::get('/account/profile', function () {
        return view('user.profile');
    })->name('account.profile');
    Route::get('/order-history', function () {
        return view('user.order.order_history');
    })->name('order_history');
    Route::get('/wishlist', function () {
        return view('client.wishlist');
    })->name('wishlist');


    

    Route::get('/seller/register', [RegisterShopController::class, 'showStep1'])->name('seller.register');

    // Trang thông tin người dùng
    Route::prefix('user')->middleware('auth', 'CheckRole:customer', redirect('/account'))->group(function () {
        Route::get('/', [UserController::class, 'dashboard'])->name('account.dashboard');
        Route::get('/profile', [UserController::class, 'edit'])->name('account.profile');
        Route::post('/profile', [UserController::class, 'update'])->name('account.profile.update');

        Route::get('/password', [UserController::class, 'changePasswordForm'])->name('account.password');
        Route::post('/password', [UserController::class, 'updatePassword'])->name('account.password.update');
    });
    // Trang địa chỉ người dùng
    Route::prefix('user')->middleware('auth', 'CheckRole:customer', redirect('/addresses'))->group(function () {
        Route::get('/', [UserAddressController::class, 'index'])->name('account.addresses');
        Route::get('/create', [UserAddressController::class, 'create'])->name('account.addresses.create');
        Route::post('/', [UserAddressController::class, 'store'])->name('account.addresses.store');
        Route::get('/{address}/edit', [UserAddressController::class, 'edit'])->name('account.addresses.edit');
        Route::put('/{address}', [UserAddressController::class, 'update'])->name('account.addresses.update');
        Route::delete('/{address}', [UserAddressController::class, 'destroy'])->name('account.addresses.delete');
    });
});

// seller registration routes
Route::prefix('seller')->group(function () {
    Route::get('/home', function () {
        return view('seller.home');
    })->name('seller.home');
    Route::get('/index', function () {
        return view('seller.register.index');
    })->name('seller.index');
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



    Route::get('/order/index', [SellerOrderController::class, 'index'])->name('seller.order.index');
    Route::get('/order/{id}', [SellerOrderController::class, 'show'])->name('seller.order.show');
    Route::put('/order/{id}/update-status', [SellerOrderController::class, 'updateStatus'])->name('seller.order.update-status');
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

