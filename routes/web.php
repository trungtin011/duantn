<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\WishlistController;
use App\Http\Controllers\Seller\ReviewController;
use App\Http\Controllers\User\ReportController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\ProductImportController;
use Illuminate\Support\Facades\Auth;

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
Route::get('/product/{id}', [ProductController::class, 'show'])->name('productDetail');
Route::get('/products/import', [ProductImportController::class, 'showImportForm'])->name('products.import.form');
Route::post('/products/import', [ProductImportController::class, 'import'])->name('products.import');

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
Route::get("order/{id}", [OrderController::class, 'show'])->name('orderDetail');

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

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Route logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//Report
Route::middleware(['auth'])->group(function () {
    Route::get('/report', [ReportController::class, 'create'])->name('user.report');
    Route::post('/report', [ReportController::class, 'store'])->name('report.store');
});

// trang đăng ký seller
Route::middleware('CheckRole:seller')->group(function () {
    Route::get('/seller', function () {
        return view('shop.index');
    })->name('seller.index');

    Route::get('/seller/register', function () {
        return view('seller.register.register');
    });

    Route::get('/seller/register1', function () {
        return view('seller.register.register1');
    });

    Route::get('/seller/register2', function () {
        return view('seller.register.register2');
    });

    Route::get('/seller/register3', function () {
        return view('seller.register.register3');
    });

    Route::get('/seller/register4', function () {
        return view('seller.register.register4');
    });
});

// trang chủ admin
Route::middleware('CheckRole:admin')->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // trang sản phẩm admin
    Route::get('/admin/products', function () {
        return view('admin.products.index');
    })->name('admin.products.index');

    // trang danh mục admin
    Route::get('/admin/categories', function () {
        return view('admin.categories.index');
    })->name('admin.categories.index');

    // trang đơn hàng admin
    Route::get('/admin/orders', function () {
        return view('admin.orders.index');
    })->name('admin.orders.index');

    // trang đánh giá admin
    Route::get('/admin/reviews', function () {
        return view('admin.reviews.index');
    })->name('admin.reviews.index');

    // trang cài đặt admin
    Route::get('/admin/settings', function () {
        return view('admin.settings.index');
    })->name('admin.settings.index');

    //quản lý user
    Route::get('/admin/user', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/user/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/user', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/user/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/user/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/user/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    //quản lý review
    Route::get('/seller/review', [ReviewController::class, 'index'])->name('seller.reviews.index');
    Route::get('/seller/review/{review}', [ReviewController::class, 'show'])->name('seller.reviews.show');
    Route::get('/seller/review/create', [ReviewController::class, 'create'])->name('seller.reviews.create');
    Route::post('/seller/review', [ReviewController::class, 'store'])->name('seller.reviews.store');
    Route::delete('/seller/review/{review}', [ReviewController::class, 'destroy'])->name('seller.reviews.destroy');

    //quản lý review
    Route::get('/admin/report', [AdminReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/admin/report/{report}', [AdminReportController::class, 'show'])->name('admin.reports.show');
    Route::put('/reports/{id}/status', [AdminReportController::class, 'updateStatus'])->name('report.updateStatus');
    Route::delete('/admin/report/{report}', [AdminReportController::class, 'destroy'])->name('admin.reports.destroy');
});