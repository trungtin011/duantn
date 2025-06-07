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
// seller
use App\Http\Controllers\Seller\ProductControllerSeller;
use App\Http\Controllers\Seller\RegisterSeller\RegisterShopController;
use App\Http\Controllers\Seller\OcrController;

///////////////////////////////////////////////////////////
// trang chủ
Route::get('/', function () {
    return view('user.home');
})->name('home');

// trang liên hệ
Route::get('/contact', function () {
    return view('user.contact');
})->name('contact');

// trang about
Route::get('/about', function () {
    return view('user.about');
})->name('about');

// trang chi tiết sản phẩm
Route::get('/user/product-detail', function () {
    return view('user.product_detail');
})->name('product_detail');

// trang wishlist
Route::get('/client/wishlist', function () {
    return view('client.wishlist');
})->name('wishlist');

// trang checkout
Route::get('/client/checkout', function () {
    return view('client.checkout');
})->name('checkout');

// trang lịch sử đơn hàng
Route::get('/user/order/order-history', function () {
    return view('user.order.order_history');
})->name('order_history');

// trang chi tiết đơn hàng
Route::get('/user/order/order-detail', function () {
    return view('user.order.orderDetail');
})->name('order_detail');

// trang giỏ hàng
Route::get('/user/cart', function () {
    return view('user.cart');
})->name('cart');

// trang 404
Route::get('/404', function () {
    return view('error.404NotFound');
})->name('404');

// trang 403
Route::get('/403', function () {
    return view('error.403');
})->name('403');

// trang đăng ký, đăng nhập, quên mật khẩu
Route::get('/signup', function () {
    return view('auth.register');
})->name('signup');
Route::post('/signup', [RegisterController::class, 'register'])->name('register.post');
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');



Route::prefix('admin')->middleware('CheckRole:admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // trang sản phẩm admin
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

    // trang attribute admin
    Route::prefix('/attributes')->group(function () {
        Route::get('/', [AttributeController::class, 'index'])->name('admin.products.attributes.index');
        Route::post('/', [AttributeController::class, 'store'])->name('admin.products.attributes.store');
        Route::delete('/{id}', [AttributeController::class, 'destroy'])->name('admin.products.attributes.destroy');
    });

    // trang order admin
    Route::prefix('orders')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
        Route::put('/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
        Route::post('/{id}/refund', [AdminOrderController::class, 'refund'])->name('admin.orders.refund');
        Route::get('/report', [AdminOrderController::class, 'report'])->name('admin.orders.report');
    });

    // trang danh mục admin
    Route::prefix('categories')->group(function () {
        Route::get('/', [AdminCategoryController::class, 'index'])->name('admin.categories.index');
        Route::post('/', [AdminCategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('/{id}/edit', [AdminCategoryController::class, 'edit'])->name('admin.categories.edit');
        Route::put('/{id}', [AdminCategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/{id}', [AdminCategoryController::class, 'destroy'])->name('admin.categories.destroy');
        Route::post('/remove-sub/{id}', [AdminCategoryController::class, 'removeSubCategory'])
            ->name('admin.categories.removeSubCategory');
    });

    // trang review admin
    Route::get('/reviews', function () {
        return view('admin.reviews.index');
    })->name('admin.reviews.index');

    // trang setting admin
    Route::get('/settings', function () {
        return view('admin.settings.index');
    })->name('admin.settings.index');
});

// trang seller
Route::prefix('seller')->group(function () {
    // trang sản phẩm seller
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductControllerSeller::class, 'index'])->name('seller.products.index');
        Route::get('/create', [ProductControllerSeller::class, 'create'])->name('seller.products.create');
        Route::post('/', [ProductControllerSeller::class, 'store'])->name('seller.products.store');
        Route::get('/{id}/edit', [ProductControllerSeller::class, 'edit'])->name('seller.products.edit');
        Route::put('/{id}', [ProductControllerSeller::class, 'update'])->name('seller.products.update');
        Route::delete('/{id}', [ProductControllerSeller::class, 'destroy'])->name('seller.products.destroy');
        Route::get('/{id}', [ProductControllerSeller::class, 'show'])->name('seller.products.show');
    });
});

// trang đăng ký seller (chỉ cho phép user đã đăng nhập mới vào được)
Route::middleware('auth')->group(function () {
    Route::get('/seller/home', function () {
        return view('seller.home');
    })->name('seller.home');

    Route::get('/seller/index', function () {
        return view('seller.register.index');
    })->name('seller.index');


    // Bước 1: Thông tin Shop
    Route::get('/seller/register', [RegisterShopController::class, 'showStep1'])->name('seller.register');
    Route::post('/seller/register', [RegisterShopController::class, 'step1'])->name('seller.register.step1');

    // Bước 2: Cài đặt vận chuyển
    Route::get('/seller/register1', [RegisterShopController::class, 'showStep2'])->name('seller.register.step2');
    Route::post('/seller/register1', [RegisterShopController::class, 'step2'])->name('seller.register.step2.post');

    // Bước 3: Thông tin thuế
    Route::get('/seller/register2', [RegisterShopController::class, 'showStep3'])->name('seller.register.step3');
    Route::post('/seller/register2', [RegisterShopController::class, 'step3'])->name('seller.register.step3.post');

    // Bước 4: Thông tin định danh
    Route::get('/seller/register3', [RegisterShopController::class, 'showStep4'])->name('seller.register.step4');
    Route::post('/seller/register3', [RegisterShopController::class, 'step4'])->name('seller.register.step4.post');

    // Bước 5: Hoàn tất đăng ký
    Route::get('/seller/register4', [RegisterShopController::class, 'showStep5'])->name('seller.register.step5');
    Route::post('/seller/register4', [RegisterShopController::class, 'finish'])->name('seller.register.finish');
});

// API OCR CCCD cho frontend JS
Route::post('/seller/ocr/scan-cccd', [OcrController::class, 'upload'])
    ->name('seller.ocr.scancccd');

// Route cho OCR
Route::get('/ocr', [OcrController::class, 'index'])->name('ocr.index');
Route::post('/ocr', [OcrController::class, 'upload'])->name('ocr.upload');
