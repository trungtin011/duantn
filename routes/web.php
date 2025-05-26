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

