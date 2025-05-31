<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <style>
        body { background: #f4f6fa; font-family: 'Inter', sans-serif; }
        .sidebar { min-height: 100vh; background: #fff; color: #232946; width: 230px; box-shadow: 2px 0 12px 0 rgba(44,62,80,0.06); border-radius: 0 24px 24px 0; }
        .sidebar .logo { font-size: 1.6em; font-weight: bold; letter-spacing: 1px; margin-bottom: 32px; color: #3b82f6; }
        .sidebar a { color: #232946; text-decoration: none; display: flex; align-items: center; gap: 12px; padding: 12px 24px; border-radius: 10px; margin-bottom: 6px; font-weight: 500; transition: background 0.2s, color 0.2s; }
        .sidebar a i { color: #64748b; transition: color 0.2s; }
        .sidebar a.active, .sidebar a:hover { background: #f4f6fa; color: #3b82f6; }
        .sidebar a.active i, .sidebar a:hover i { color: #3b82f6; }
        .header { background: #fff; border-bottom: 1px solid #eee; padding: 18px 32px; display: flex; align-items: center; justify-content: space-between; }
        .header .search-box { width: 260px; border-radius: 12px; }
        .header .right { display: flex; align-items: center; gap: 18px; }
        .header .avatar { width: 38px; height: 38px; border-radius: 50%; object-fit: cover; }
        main { padding: 32px 32px 0 32px; }
    </style>
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
        <a href="#"><i class="fa-solid fa-ticket"></i> Coupons</a> {{-- No admin route in web.php --}}
        <a href="#"><i class="fa-solid fa-user"></i> Profile</a> {{-- No admin route in web.php --}}
        <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.index') ? 'active' : '' }}"><i class="fa-solid fa-gear"></i> Shop Settings</a>
        <a href="#"><i class="fa-solid fa-file"></i> Pages</a> {{-- No admin route in web.php --}}
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
</body>
</html> 