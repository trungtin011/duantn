<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\AttributeController;
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
///////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////
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
///////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////
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
    Route::prefix('admin')->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
        Route::get('/products/{product}/variants/create', [ProductController::class, 'createVariant'])->name('admin.products.variants.create');
        Route::post('/products/{product}/variants', [ProductController::class, 'storeVariant'])->name('admin.products.variants.store');
        Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
        Route::put('/products/{id}', [ProductController::class, 'update'])->name('admin.products.update');
        Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
        Route::get('/products/{id}', [ProductController::class, 'show'])->name('admin.products.show');
        Route::get('/get-sub-brands', [ProductController::class, 'getSubBrands'])->name('admin.get-sub-brands');
        Route::get('/get-sub-categories', [ProductController::class, 'getSubCategories'])->name('admin.get-sub-categories');

        // Attribute
        Route::prefix('products/attributes')->group(function () {
            Route::get('/', [AttributeController::class, 'index'])->name('admin.products.attributes.index');
            Route::post('/', [AttributeController::class, 'store'])->name('admin.products.attributes.store');
            Route::delete('/{id}', [AttributeController::class, 'destroy'])->name('admin.products.attributes.destroy');
        });

    });

    Route::prefix('admin')->group(function () {
        // trang danh mục admin
        Route::get('/categories', function () {
            return view('admin.categories.index');
        })->name('admin.categories.index');


    });


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