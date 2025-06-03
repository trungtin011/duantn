<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\Auth;

>>>>>>> tin
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
<<<<<<< HEAD
///////////////////////////////////////////////////////////
=======
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Route logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
>>>>>>> tin

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
///////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////
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
});
///////////////////////////////////////////////////////////