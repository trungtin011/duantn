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

// trang wishlist
Route::get('/client/wishlist', function () {
    return view('client.wishlist');
});

Route::get('/client/checkout', function () {
    return view('client.checkout');
});

// trang lịch sử đơn hàng
Route::get('/user/order/order-history', function () {
    return view('user.order.order_history');
});

// trang giỏ hàng
Route::get('/user/cart', function () {
    return view('user.cart');
});

// trang 404
Route::get('/404', function () {
    return view('error.404NotFound');
});
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

///////////////////////////////////////////////////////////
