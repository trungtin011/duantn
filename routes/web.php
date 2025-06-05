<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Seller\RegisterSeller\RegisterShopController;
use App\Http\Controllers\OcrController;

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
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Route logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

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


