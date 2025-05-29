<?php

use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return view('welcome');
});

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
<<<<<<< Updated upstream

Route::get('/404', function () {
    return view('error.404NotFound');
});

Route::get('/client/wishlist', function () {
    return view('client.wishlist');
});

Route::get('/client/checkout', function () {
    return view('client.checkout');
});

Route::get('/signup', function () {
    return view('register');
});
Route::get('/login', function () {
    return view('login');
});
=======
Route::get('/user/order/order-history', function () {
    return view('user.order.order_history');
}); 

Route::get('/user/cart', function () {
    return view('user.cart');
});


>>>>>>> Stashed changes
