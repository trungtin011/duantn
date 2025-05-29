<?php

use Illuminate\Support\Facades\Route;
// trang chủ
Route::get('/', function () {
    return view('welcome');
});

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
