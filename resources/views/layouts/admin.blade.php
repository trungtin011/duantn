<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @yield('head')
</head>
<body>
<div class="d-flex">
    <div class="sidebar p-4">
        <div class="logo mb-4"><i class="fa-solid fa-bag-shopping me-2"></i>eBazer</div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
        <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.index') ? 'active' : '' }}"><i class="fa-solid fa-box"></i> Products</a>
        <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.index') ? 'active' : '' }}"><i class="fa-solid fa-list"></i> Categories</a>
        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.index') ? 'active' : '' }}"><i class="fa-solid fa-cart-shopping"></i> Orders</a>
        <a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews.index') ? 'active' : '' }}"><i class="fa-solid fa-star"></i> Reviews</a>
        <a href="#"><i class="fa-solid fa-ticket"></i> Coupons</a>
        <a href="#"><i class="fa-solid fa-user"></i> Profile</a>
        <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.index') ? 'active' : '' }}"><i class="fa-solid fa-gear"></i> Shop Settings</a>
        <a href="#"><i class="fa-solid fa-file"></i> Pages</a>
    </div>
    <div class="flex-grow-1">
        <div class="header">
            <input class="form-control search-box" placeholder="Search..." />
            <div class="right">
                <i class="fa-regular fa-bell fa-lg text-secondary"></i>
                <img src="https://i.pravatar.cc/38" class="avatar" alt="avatar">
            </div>
        </div>
        <main>
            @yield('content')
        </main>
    </div>
</div>
@yield('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 