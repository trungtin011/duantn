<?php

use Illuminate\Support\Facades\Route;

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

// trang wishlist
Route::get('/client/wishlist', function () {
    return view('client.wishlist');
})->name('wishlist');

Route::get('/client/checkout', function () {
    return view('client.checkout');
})->name('checkout');

// trang lịch sử đơn hàng
Route::get('/user/order/order-history', function () {
    return view('user.order.order_history');
});

// trang giỏ hàng
Route::get('/user/cart', function () {
    return view('user.cart');
})->name('cart');

// trang 404
Route::get('/404', function () {
    return view('error.404NotFound');
})->name('404');
///////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////
// trang đăng ký, đăng nhập, quên mật khẩu
Route::get('/signup', function () {
    return view('auth.register');
})->name('signup');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
///////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////
// trang đăng ký seller
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

<<<<<<< HEAD
// trang 404
Route::get('/404', function () {
    return view('error.404NotFound');
});

Route::get('/client/wishlist', function () {
    return view('client.wishlist');
});

Route::get('/client/checkout', function () {
    return view('client.checkout');
});

// trang đăng ký và đăng nhập
Route::get('/signup', function () {
    return view('register');
});
Route::get('/login', function () {
    return view('login');
});

// trang lịch sử đơn hàng
Route::get('/user/order/order-history', function () {
    return view('user.order.order_history');
}); 

// trang giỏ hàng
Route::get('/user/cart', function () {
    return view('user.cart');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/admin/products', function () {
    return view('admin.products.index');
})->name('admin.products.index');

Route::get('/admin/categories', function () {
    return view('admin.categories.index');
})->name('admin.categories.index');

Route::get('/admin/orders', function () {
    return view('admin.orders.index');
})->name('admin.orders.index');

Route::get('/admin/reviews', function () {
    return view('admin.reviews.index');
})->name('admin.reviews.index');

Route::get('/admin/settings', function () {
    return view('admin.settings.index');
})->name('admin.settings.index');
=======
///////////////////////////////////////////////////////////
>>>>>>> 5385a0bd8dd4305a3022e05da8b0f4ee0329656b
