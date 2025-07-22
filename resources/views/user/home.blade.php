@extends('layouts.app')
@section('title', 'Trang chủ')
@section('meta-description',
    'Trang chủ của website bán hàng trực tuyến, nơi bạn có thể tìm thấy các sản phẩm mới nhất
    và ưu đãi hấp dẫn.')
@section('meta-keywords', 'trang chủ, mua sắm trực tuyến, sản phẩm mới, ưu đãi, thời trang, điện tử')

<!-- Custom style -->
@push('styles')
    @vite(['resources/css/user/style-prefix.css', 'resources/css/user/style-home.css'])
    <style>
        /* Xếp hạng shop sidebar */
        .ranking-header {
            padding: 15px 0 10px 0;
            border-bottom: 1px solid #eee;
        }
        .ranking-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .ranking-legend {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .legend-item {
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 12px;
        }
        .legend-item.diamond { background: #E3F2FD; color: #1976D2; }
        .legend-item.gold { background: #FFF3E0; color: #F57C00; }
        .legend-item.silver { background: #ECEFF1; color: #455A64; }
        .legend-item.bronze { background: #EFEBE9; color: #795548; }

        .shop-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            transition: background 0.2s;
        }
        .shop-item:hover {
            background: #f8f9fa;
        }
        .shop-item.top-0 { background: #fff9c4; }
        .shop-item.top-1 { background: #f5f5f5; }
        .shop-item.top-2 { background: #ffe0b2; }

        .shop-rank-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        .rank-number {
            font-size: 16px;
            font-weight: bold;
            color: #666;
        }
        .shop-tier {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
        .shop-tier.diamond { background: #E3F2FD; color: #1976D2; }
        .shop-tier.gold { background: #FFF3E0; color: #F57C00; }
        .shop-tier.silver { background: #ECEFF1; color: #455A64; }
        .shop-tier.bronze { background: #EFEBE9; color: #795548; }

        .shop-main-content {
            display: flex;
            gap: 10px;
            margin-bottom: 8px;
        }
        .shop-avatar img {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #eee;
        }
        .shop-details {
            flex: 1;
        }
        .shop-name {
            font-weight: 600;
            margin-bottom: 3px;
            color: #333;
            font-size: 15px;
        }
        .shop-rating {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .stars {
            color: #ffd700;
            display: flex;
            gap: 1px;
            font-size: 15px;
        }
        .rating-text {
            color: #666;
            font-size: 13px;
        }
        .shop-stats {
            display: flex;
            justify-content: space-between;
            padding: 8px 0 0 0;
            margin-bottom: 8px;
        }
        .stat-item {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 13px;
            color: #666;
        }
        .shop-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            width: 100%;
            padding: 7px 0;
            background: #f5f5f5;
            border-radius: 5px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
            transition: background 0.2s;
            text-decoration: none;
        }
        .shop-link:hover {
            background: #e0e0e0;
        }
    </style>
@endpush

@section('content')
    <div class="overlay" data-overlay></div>
    <div class="modal" data-modal>
        <div class="modal-close-overlay" data-modal-overlay></div>
        <div class="modal-content">
            <button class="modal-close-btn" data-modal-close>
                <ion-icon name="close-outline"></ion-icon>
            </button>
            <div class="newsletter-img">
                <img src="{{ asset('assets/images/newsletter.png') }}" alt="Đăng ký nhận tin" width="400" height="400">
            </div>
            <div class="newsletter">
                <form action="#">
                    <div class="newsletter-header">
                        <h3 class="newsletter-title">Đăng ký nhận tin.</h3>
                        <p class="newsletter-desc">
                            Hãy đăng ký <b>Anon</b> để nhận thông tin sản phẩm mới và cập nhật khuyến mãi.
                        </p>
                    </div>
                    <input type="email" name="email" class="email-field" placeholder="Địa chỉ Email" required>
                    <button type="submit" class="btn-newsletter">Đăng ký</button>
                </form>
            </div>
        </div>
    </div>

    @foreach ($purchasedProducts as $product)
        <div class="notification-toast" data-toast>
            <button class="toast-close-btn" data-toast-close>
                <ion-icon name="close-outline"></ion-icon>
            </button>
            <div class="toast-banner">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" width="80" height="70">
            </div>
            <div class="toast-detail">
                <p class="toast-message">Ai đó vừa mua sản phẩm</p>
                <p class="toast-title">{{ $product->name }}</p>
                <p class="toast-meta">
                    <time datetime="{{ $product->updated_at->toIso8601String() }}">
                        {{ $product->updated_at->diffForHumans() }}
                    </time> trước
                </p>
            </div>
        </div>
    @endforeach

    <header>
        <nav class="desktop-navigation-menu">
            <div class="container">
                <ul class="desktop-menu-category-list">
                    <li class="menu-category">
                        <a href="#" class="menu-title">Trang chủ</a>
                    </li>
                    <li class="menu-category">
                        <a href="{{ route('combo.index') }}" class="menu-title">Combo</a>
                    </li>
                    <li class="menu-category">
                        <a href="#" class="menu-title">Danh mục</a>
                        <div class="dropdown-panel">
                            <ul class="dropdown-panel-list">
                                @if ($parentCategory)
                                    <li class="menu-title">
                                        <a href="#">{{ $parentCategory->name ?? 'Danh mục' }}</a>
                                    </li>
                                    @foreach ($subCategories as $subcategory)
                                        <li class="panel-list-item">
                                            <a href="#">{{ $subcategory->name }}</a>
                                        </li>
                                    @endforeach
                                    @if ($parentCategory->image_path)
                                        <li class="panel-list-item">
                                            <a href="#" class="overflow-hidden">
                                                <img src="{{ asset('storage/' . $parentCategory->image_path) }}"
                                                    alt="{{ $parentCategory->name }} banner" class="object-cover">
                                            </a>
                                        </li>
                                    @else
                                        <li class="panel-list-item">
                                            <a href="#">
                                                <img src="{{ asset('assets/images/electronics-banner-1.jpg') }}"
                                                    alt="headphone collection" width="250" height="119">
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            </ul>

                            @foreach ($fashionSub as $subCategory)
                                <ul class="dropdown-panel-list">
                                    <li class="menu-title">
                                        <a href="#">{{ $subCategory->name }}</a>
                                    </li>
                                    @foreach ($subCategory->subCategories as $child)
                                        <li class="panel-list-item">
                                            <a href="#">{{ $child->name }}</a>
                                        </li>
                                    @endforeach
                                    @if ($subCategory->image_path)
                                        <li class="panel-list-item">
                                            <a href="#" class="overflow-hidden">
                                                <img src="{{ asset('storage/' . $subCategory->image_path) }}"
                                                    alt="{{ $subCategory->name }} banner" class="object-cover">
                                            </a>
                                        </li>
                                    @else
                                        <li class="panel-list-item">
                                            <a href="#">
                                                @if ($subCategory->name === 'Nam')
                                                    <img src="{{ asset('assets/images/mens-banner.jpg') }}"
                                                        alt="Men's Fashion" width="250" height="119">
                                                @elseif ($subCategory->name === 'Nữ')
                                                    <img src="{{ asset('assets/images/womens-banner.jpg') }}"
                                                        alt="Women's Fashion" width="250" height="119">
                                                @else
                                                    <img src="{{ asset('assets/images/default.jpg') }}" alt="Fashion"
                                                        width="250" height="119">
                                                @endif
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            @endforeach

                            <ul class="dropdown-panel-list">
                                <li class="menu-title"><a href="#">{{ $parentCategory->name ?? 'Danh mục' }}</a></li>
                                @foreach ($filteredSubCategories as $child)
                                    <li class="panel-list-item">
                                        <a href="#">{{ $child->name }}</a>
                                    </li>
                                @endforeach
                                @if (isset($parentCategory) && $parentCategory->image_path)
                                    <li class="panel-list-item">
                                        <a href="#" class="overflow-hidden">
                                            <img src="{{ asset('storage/' . $parentCategory->image_path) }}"
                                                alt="{{ $parentCategory->name }} banner" class="object-cover">
                                        </a>
                                    </li>
                                @else
                                    <li class="panel-list-item">
                                        <a href="#">
                                            <img src="{{ asset('assets/images/electronics-banner-1.jpg') }}"
                                                alt="headphone collection" width="250" height="119">
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @foreach ($fashionSub as $subCategory)
                        <li class="menu-category">
                            <a href="#" class="menu-title">{{ $subCategory->name }}</a>
                            <ul class="dropdown-list">
                                @foreach ($subCategory->subCategories as $child)
                                    <li class="dropdown-item">
                                        <a href="#">{{ $child->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                    <li class="menu-category">
                        <a href="#" class="menu-title">Trang sức</a>
                        <ul class="dropdown-list">
                            @foreach ($jewelrySub as $item)
                                <li class="dropdown-item">
                                    <a href="#">{{ $item->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="menu-category">
                        <a href="#" class="menu-title">Nước hoa</a>
                        <ul class="dropdown-list">
                            @foreach ($perfumeSub as $item)
                                <li class="dropdown-item">
                                    <a href="#">{{ $item->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="menu-category">
                        <a href="#" class="menu-title">Blog</a>
                    </li>
                    <li class="menu-category">
                        <a href="#" class="menu-title">Ưu đãi hấp dẫn</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="mobile-bottom-navigation">
            <button class="action-btn" data-mobile-menu-open-btn>
                <ion-icon name="menu-outline"></ion-icon>
            </button>
            <button class="action-btn">
                <ion-icon name="bag-handle-outline"></ion-icon>
                <span class="count">0</span>
            </button>
            <button class="action-btn">
                <ion-icon name="home-outline"></ion-icon>
            </button>
            <button class="action-btn">
                <ion-icon name="heart-outline"></ion-icon>
                <span class="count">0</span>
            </button>
            <button class="action-btn" data-mobile-menu-open-btn>
                <ion-icon name="grid-outline"></ion-icon>
            </button>
        </div>

        <nav class="mobile-navigation-menu has-scrollbar" data-mobile-menu>
            <div class="menu-top">
                <h2 class="menu-title">Menu</h2>
                <button class="menu-close-btn" data-mobile-menu-close-btn>
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <ul class="mobile-menu-category-list">
                <li class="menu-category">
                    <a href="#" class="menu-title">Home</a>
                </li>
                <li class="menu-category">
                    <button class="accordion-menu" data-accordion-btn>
                        <p class="menu-title">Men's</p>
                        <div>
                            <ion-icon name="add-outline" class="add-icon"></ion-icon>
                            <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                        </div>
                    </button>
                    <ul class="submenu-category-list" data-accordion>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Shirt</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Shorts & Jeans</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Safety Shoes</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Wallet</a>
                        </li>
                    </ul>
                </li>
                <li class="menu-category">
                    <button class="accordion-menu" data-accordion-btn>
                        <p class="menu-title">Women's</p>
                        <div>
                            <ion-icon name="add-outline" class="add-icon"></ion-icon>
                            <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                        </div>
                    </button>
                    <ul class="submenu-category-list" data-accordion>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Dress & Frock</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Earrings</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Necklace</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Makeup Kit</a>
                        </li>
                    </ul>
                </li>
                <li class="menu-category">
                    <button class="accordion-menu" data-accordion-btn>
                        <p class="menu-title">Jewelry</p>
                        <div>
                            <ion-icon name="add-outline" class="add-icon"></ion-icon>
                            <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                        </div>
                    </button>
                    <ul class="submenu-category-list" data-accordion>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Earrings</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Couple Rings</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Necklace</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Bracelets</a>
                        </li>
                    </ul>
                </li>
                <li class="menu-category">
                    <button class="accordion-menu" data-accordion-btn>
                        <p class="menu-title">Perfume</p>
                        <div>
                            <ion-icon name="add-outline" class="add-icon"></ion-icon>
                            <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                        </div>
                    </button>
                    <ul class="submenu-category-list" data-accordion>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Clothes Perfume</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Deodorant</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Flower Fragrance</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Air Freshener</a>
                        </li>
                    </ul>
                </li>
                <li class="menu-category">
                    <a href="#" class="menu-title">Blog</a>
                </li>
                <li class="menu-category">
                    <a href="#" class="menu-title">Hot Offers</a>
                </li>
            </ul>
            <div class="menu-bottom">
                <ul class="menu-category-list">
                    <li class="menu-category">
                        <button class="accordion-menu" data-accordion-btn>
                            <p class="menu-title">Language</p>
                            <ion-icon name="caret-back-outline" class="caret-back"></ion-icon>
                        </button>
                        <ul class="submenu-category-list" data-accordion>
                            <li class="submenu-category">
                                <a href="#" class="submenu-title">English</a>
                            </li>
                            <li class="submenu-category">
                                <a href="#" class="submenu-title">Español</a>
                            </li>
                            <li class="submenu-category">
                                <a href="#" class="submenu-title">Frençh</a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-category">
                        <button class="accordion-menu" data-accordion-btn>
                            <p class="menu-title">Currency</p>
                            <ion-icon name="caret-back-outline" class="caret-back"></ion-icon>
                        </button>
                        <ul class="submenu-category-list" data-accordion>
                            <li class="submenu-category">
                                <a href="#" class="submenu-title">USD $</a>
                            </li>
                            <li class="submenu-category">
                                <a href="#" class="submenu-title">EUR €</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="menu-social-container">
                    <li><a href="#" class="social-link"><ion-icon name="logo-facebook"></ion-icon></a></li>
                    <li><a href="#" class="social-link"><ion-icon name="logo-twitter"></ion-icon></a></li>
                    <li><a href="#" class="social-link"><ion-icon name="logo-instagram"></ion-icon></a></li>
                    <li><a href="#" class="social-link"><ion-icon name="logo-linkedin"></ion-icon></a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <div class="banner">
            <div class="container">
                <div class="slider-container has-scrollbar">
                    <div class="slider-item">
                        <img src="{{ asset('assets/images/banner-1.jpg') }}" alt="women's latest fashion sale"
                            class="banner-img">
                        <div class="banner-content">
                            <p class="banner-subtitle">Trending item</p>
                            <h2 class="banner-title">Women's latest fashion sale</h2>
                            <p class="banner-text">starting at $ <b>20</b>.00</p>
                            <a href="#" class="banner-btn">Shop now</a>
                        </div>
                    </div>
                    <div class="slider-item">
                        <img src="{{ asset('assets/images/banner-2.jpg') }}" alt="modern sunglasses" class="banner-img">
                        <div class="banner-content">
                            <p class="banner-subtitle">Trending accessories</p>
                            <h2 class="banner-title">Modern sunglasses</h2>
                            <p class="banner-text">starting at $ <b>15</b>.00</p>
                            <a href="#" class="banner-btn">Shop now</a>
                        </div>
                    </div>
                    <div class="slider-item">
                        <img src="{{ asset('assets/images/banner-3.jpg') }}" alt="new fashion summer sale"
                            class="banner-img">
                        <div class="banner-content">
                            <p class="banner-subtitle">Sale Offer</p>
                            <h2 class="banner-title">New fashion summer sale</h2>
                            <p class="banner-text">starting at $ <b>29</b>.99</p>
                            <a href="#" class="banner-btn">Shop now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="category">
            <div class="container">
                <div class="category-item-container has-scrollbar flex flex-wrap gap-5 max-w-full">
                    @php
                        $homeCategories = $homeCategories->take(8); // Giới hạn tối đa 8 danh mục
                    @endphp
                    @foreach ($homeCategories as $category)
                        <div class="category-item w-[calc(25%-0.9375rem)] min-w-[140px] flex-shrink-0">
                            <div class="category-img-box h-10 w-10 flex items-center justify-center bg-gray-200">
                                @if ($category->image_path && file_exists(public_path('storage/' . $category->image_path)))
                                    <img src="{{ asset('storage/' . $category->image_path) }}"
                                        alt="{{ Str::limit($category->name, 20) }}" width="30"
                                        class="object-contain">
                                @elseif (file_exists(public_path('assets/images/icons/' . Str::slug($category->name) . '.svg')))
                                    <img src="{{ asset('assets/images/icons/' . Str::slug($category->name) . '.svg') }}"
                                        alt="{{ Str::limit($category->name, 20) }}" width="30"
                                        class="object-contain">
                                @else
                                    <span class="text-xs text-gray-500">{{ Str::limit($category->name, 5) }}</span>
                                @endif
                            </div>
                            <div class="category-content-box">
                                <div class="category-content-flex">
                                    <h3 class="category-item-title">{{ $category->name }}</h3>
                                    <p class="category-item-amount">({{ $category->products_count }})</p>
                                </div>
                                <a href="{{ route('search', ['category' => [$category->id]]) }}" class="category-btn">Xem
                                    tất cả</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="product-container">
            <div class="container">
                <div class="sidebar has-scrollbar" data-mobile-menu>
                    <div class="sidebar-category mt-[30px]">
                        <div class="sidebar-top">
                            <h2 class="sidebar-title">Xếp hạng shop </h2>
                            <button class="sidebar-close-btn" data-mobile-menu-close-btn>
                                <ion-icon name="close-outline"></ion-icon>
                            </button>
                        </div>
                        <ul class="sidebar-menu-category-list">
                            <li class="sidebar-menu-category">
                                <div class="ranking-header">
                                    <h3 class="ranking-title">Top Shop</h3>
                                    <div class="ranking-legend">
                                        <span class="legend-item diamond">Kim cương</span>
                                        <span class="legend-item gold">Vàng</span>
                                        <span class="legend-item silver">Bạc</span>
                                        <span class="legend-item bronze">Đồng</span>
                                    </div>
                                </div>
                            </li>
                            @foreach($rankingShops as $index => $shop)
                                <li class="sidebar-menu-category shop-item {{ $index < 3 ? 'top-'.$index : '' }}">
                                    <div class="shop-rank-info">
                                        <span class="rank-number">#{{ $index + 1 }}</span>
                                        <span class="shop-tier {{ $shop->tier }}">
                                            <ion-icon name="{{ $shop->tier_icon }}-outline"></ion-icon>
                                            {{ ucfirst($shop->tier) }}
                                        </span>
                                    </div>
                                    <div class="shop-main-content">
                                        <div class="shop-avatar">
                                            <img src="{{ asset('storage/' . $shop->shop_logo) }}" alt="{{ $shop->shop_name }}">
                                        </div>
                                        <div class="shop-details">
                                            <h4 class="shop-name">{{ $shop->shop_name }}</h4>
                                            <div class="shop-rating">
                                                <div class="stars">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($shop->shop_rating >= $i)
                                                            <ion-icon name="star"></ion-icon>
                                                        @elseif($shop->shop_rating >= $i - 0.5)
                                                            <ion-icon name="star-half"></ion-icon>
                                                        @else
                                                            <ion-icon name="star-outline"></ion-icon>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="rating-text">{{ number_format($shop->shop_rating, 1) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="shop-stats">
                                        <div class="stat-item">
                                            <ion-icon name="cash-outline"></ion-icon>
                                            <span>{{ $shop->formatted_sales }}đ</span>
                                        </div>
                                        <div class="stat-item">
                                            <ion-icon name="cube-outline"></ion-icon>
                                            <span>{{ $shop->total_products }}</span>
                                        </div>
                                        <div class="stat-item">
                                            <ion-icon name="people-outline"></ion-icon>
                                            <span>{{ $shop->total_followers }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('shop.show', $shop->id) }}" class="shop-link">
                                        Xem shop <ion-icon name="arrow-forward-outline"></ion-icon>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="product-showcase">
                        <h3 class="showcase-heading">Sản phẩm bán chạy</h3>
                        <div class="showcase-wrapper">
                            <div class="showcase-container">
                                @foreach ($bestSellers as $product)
                                    <div class="showcase">
                                        <a href="{{ route('product.show', $product->slug) }}" class="showcase-img-box">
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                width="75" height="75" class="showcase-img">
                                        </a>
                                        <div class="showcase-content">
                                            <a href="{{ route('product.show', $product->slug) }}">
                                                <h4 class="showcase-title">{{ $product->name }}</h4>
                                            </a>
                                            <div class="showcase-rating">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($product->reviews->avg('rating') >= $i)
                                                        <ion-icon name="star"></ion-icon>
                                                    @elseif ($product->reviews->avg('rating') >= $i - 0.5)
                                                        <ion-icon name="star-half-outline"></ion-icon>
                                                    @else
                                                        <ion-icon name="star-outline"></ion-icon>
                                                    @endif
                                                @endfor
                                            </div>
                                            <div class="price-box">
                                                @if ($product->sale_price)
                                                    <del>{{ number_format($product->price, 0, ',', '.') }}₫</del>
                                                    <p class="price">
                                                        {{ number_format($product->sale_price, 0, ',', '.') }}₫</p>
                                                @else
                                                    <p class="price">{{ number_format($product->price, 0, ',', '.') }}₫
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="product-box">
                    @if ($flashSaleProducts->count())
                        <div class="product-demo">
                            <h2 class="product-title">⚡ FLASH SALE</h2>
                            @foreach ($flashSaleProducts as $product)
                                <div class="flash-sale-box mb-4">
                                    {{-- Header: FLASH SALE + Countdown --}}
                                    <div class="flash-sale-header">
                                        <strong class="truncate w-[500px]">{{ $product->name }}</strong>
                                        <span class="countdown-label">KẾT THÚC TRONG</span>
                                        @if ($product->flash_sale_end_at)
                                            <span class="countdown-timer" id="countdown-{{ $product->id }}"
                                                data-end-time="{{ $product->flash_sale_end_at->timestamp }}">
                                                <span class="display-number">00</span> :
                                                <span class="display-number">00</span> :
                                                <span class="display-number">00</span>
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Giá và giảm giá --}}
                                    <div class="flash-sale-price">
                                        <span class="new-price">₫{{ number_format($product->flash_sale_price, 0) }}</span>
                                        <span
                                            class="old-price truncate w-[95px]">₫{{ number_format($product->price, 0) }}</span>
                                        <span class="discount">
                                            -{{ round((1 - $product->flash_sale_price / $product->price) * 100) }}%
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="product-featured">
                        <h2 class="title">Flash Sale</h2>
                        <div class="showcase-wrapper has-scrollbar">
                            @foreach ($flashSaleProducts as $product)
                                <div class="showcase-container">
                                    <div class="showcase">
                                        <div class="showcase-banner">
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                class="showcase-img">
                                        </div>
                                        <div class="showcase-content">
                                            <div class="showcase-rating">
                                                @php
                                                    $avg = round($product->reviews->avg('rating') ?? 0);
                                                @endphp
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <ion-icon
                                                        name="{{ $i <= $avg ? 'star' : 'star-outline' }}"></ion-icon>
                                                @endfor
                                            </div>
                                            <a href="{{ route('product.show', $product->slug) }}">
                                                <h3 class="showcase-title">{{ $product->name }}</h3>
                                            </a>
                                            <p class="showcase-desc">
                                                {{ Str::limit(strip_tags($product->description), 100) }}
                                            </p>
                                            <div class="price-box">
                                                <p class="price">{{ number_format($product->flash_sale_price) }}₫</p>
                                                <del>{{ number_format($product->price) }}₫</del>
                                            </div>
                                            <button class="add-cart-btn">Thêm vào giỏ</button>
                                            <div class="showcase-status">
                                                <div class="wrapper">
                                                    <p>Đã bán: <b>{{ $product->sold_quantity ?? 0 }}</b></p>
                                                    <p>Còn lại:
                                                        <b>{{ $product->stock_total - $product->sold_quantity }}</b>
                                                    </p>
                                                </div>
                                                <div class="showcase-status-bar"></div>
                                            </div>
                                            @if ($product->flash_sale_end_at)
                                                <div class="countdown-box">
                                                    <p class="countdown-desc">Nhanh tay! Kết thúc sau:</p>
                                                    <div class="countdown"
                                                        data-end-time="{{ $product->flash_sale_end_at->timestamp }}">
                                                        <div class="countdown-content">
                                                            <p class="display-number">00</p>
                                                            <p class="display-text">Ngày</p>
                                                        </div>
                                                        <div class="countdown-content">
                                                            <p class="display-number">00</p>
                                                            <p class="display-text">Giờ</p>
                                                        </div>
                                                        <div class="countdown-content">
                                                            <p class="display-number">00</p>
                                                            <p class="display-text">Phút</p>
                                                        </div>
                                                        <div class="countdown-content">
                                                            <p class="display-number">00</p>
                                                            <p class="display-text">Giây</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="product-minimal">
                        <div class="product-showcase">
                            <h2 class="title">Sản phẩm nổi bật</h2>
                            @if ($featuredProducts->isEmpty())
                                <p>Hiện chưa có sản phẩm nổi bật nào.</p>
                            @else
                                @php
                                    $featuredProducts = $featuredProducts->take(8); // Giới hạn tổng cộng 8 sản phẩm
                                    $firstFour = $featuredProducts->take(4); // Lấy 4 sản phẩm đầu
                                    $others = $featuredProducts->slice(4, 4); // Lấy 4 sản phẩm tiếp theo
                                @endphp
                                <div class="showcase-wrapper has-scrollbar">
                                    <div class="showcase-container">
                                        @foreach ($firstFour as $product)
                                            <div class="showcase">
                                                <a href="{{ route('product.show', $product->slug) }}"
                                                    class="showcase-img-box">
                                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                        class="showcase-img" width="70">
                                                </a>
                                                <div class="showcase-content">
                                                    <a href="{{ route('product.show', $product->slug) }}">
                                                        <h4 class="showcase-title">{{ $product->name }}</h4>
                                                    </a>
                                                    <a href="#" class="showcase-category">
                                                        {{ $product->categories->first()->name ?? 'Không có danh mục' }}
                                                    </a>
                                                    <div class="price-box">
                                                        @if ($product->sale_price)
                                                            <p class="price">{{ number_format($product->sale_price) }}₫
                                                            </p>
                                                            <del>{{ number_format($product->price) }}₫</del>
                                                        @else
                                                            <p class="price">{{ number_format($product->price) }}₫</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="showcase-container">
                                        @foreach ($others as $product)
                                            <div class="showcase">
                                                <a href="{{ route('product.show', $product->slug) }}"
                                                    class="showcase-img-box">
                                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                        class="showcase-img" width="70">
                                                </a>
                                                <div class="showcase-content">
                                                    <a href="{{ route('product.show', $product->slug) }}">
                                                        <h4 class="showcase-title">{{ $product->name }}</h4>
                                                    </a>
                                                    <a href="#" class="showcase-category">
                                                        {{ $product->categories->first()->name ?? 'Không có danh mục' }}
                                                    </a>
                                                    <div class="price-box">
                                                        @if ($product->sale_price)
                                                            <p class="price">{{ number_format($product->sale_price) }}₫
                                                            </p>
                                                            <del>{{ number_format($product->price) }}₫</del>
                                                        @else
                                                            <p class="price">{{ number_format($product->price) }}₫</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="product-showcase">
                            <h2 class="title">Đang thịnh hành</h2>
                            @if ($trendingProducts->isEmpty())
                                <p>Hiện chưa có sản phẩm nào thịnh hành.</p>
                            @else
                                @php
                                    $trendingProducts = $trendingProducts->take(8); // Giới hạn tổng cộng 8 sản phẩm
                                    $firstFourTrending = $trendingProducts->take(4); // Lấy 4 sản phẩm đầu
                                    $remainingTrending = $trendingProducts->slice(4, 4); // Lấy 4 sản phẩm tiếp theo
                                @endphp
                                <div class="showcase-wrapper has-scrollbar">
                                    <div class="showcase-container">
                                        @foreach ($firstFourTrending as $product)
                                            <div class="showcase">
                                                <a href="{{ route('product.show', $product->slug) }}"
                                                    class="showcase-img-box">
                                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                        class="showcase-img" width="70">
                                                </a>
                                                <div class="showcase-content">
                                                    <a href="{{ route('product.show', $product->slug) }}">
                                                        <h4 class="showcase-title">{{ $product->name }}</h4>
                                                    </a>
                                                    <a href="#" class="showcase-category">
                                                        {{ $product->categories->first()->name ?? 'Không có danh mục' }}
                                                    </a>
                                                    <div class="price-box">
                                                        @if ($product->sale_price)
                                                            <p class="price">{{ number_format($product->sale_price) }}₫
                                                            </p>
                                                            <del>{{ number_format($product->price) }}₫</del>
                                                        @else
                                                            <p class="price">{{ number_format($product->price) }}₫</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="showcase-container">
                                        @foreach ($remainingTrending as $product)
                                            <div class="showcase">
                                                <a href="{{ route('product.show', $product->slug) }}"
                                                    class="showcase-img-box">
                                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                        class="showcase-img" width="70">
                                                </a>
                                                <div class="showcase-content">
                                                    <a href="{{ route('product.show', $product->slug) }}">
                                                        <h4 class="showcase-title">{{ $product->name }}</h4>
                                                    </a>
                                                    <a href="#" class="showcase-category">
                                                        {{ $product->categories->first()->name ?? 'Không có danh mục' }}
                                                    </a>
                                                    <div class="price-box">
                                                        @if ($product->sale_price)
                                                            <p class="price">{{ number_format($product->sale_price) }}₫
                                                            </p>
                                                            <del>{{ number_format($product->price) }}₫</del>
                                                        @else
                                                            <p class="price">{{ number_format($product->price) }}₫</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="product-showcase">
                            <h2 class="title">Đánh giá cao</h2>
                            @if ($topRatedProducts->isEmpty())
                                <p>Chưa có sản phẩm nào được đánh giá.</p>
                            @else
                                @php
                                    $topRatedProducts = $topRatedProducts->take(8); // Giới hạn tổng cộng 8 sản phẩm
                                    $firstFourTopRated = $topRatedProducts->take(4); // Lấy 4 sản phẩm đầu
                                    $remainingTopRated = $topRatedProducts->slice(4, 4); // Lấy 4 sản phẩm tiếp theo
                                @endphp
                                <div class="showcase-wrapper has-scrollbar">
                                    <div class="showcase-container">
                                        @foreach ($firstFourTopRated as $product)
                                            <div class="showcase">
                                                <a href="{{ route('product.show', $product->slug) }}"
                                                    class="showcase-img-box">
                                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                        class="showcase-img" width="70">
                                                </a>
                                                <div class="showcase-content">
                                                    <a href="{{ route('product.show', $product->slug) }}">
                                                        <h4 class="showcase-title">{{ $product->name }}</h4>
                                                    </a>
                                                    <a href="#" class="showcase-category">
                                                        {{ $product->categories->first()->name ?? 'Không có danh mục' }}
                                                    </a>
                                                    <div class="showcase-rating">
                                                        @php
                                                            $avg = round($product->reviews_avg_rating ?? 0);
                                                        @endphp
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <ion-icon
                                                                name="{{ $i <= $avg ? 'star' : 'star-outline' }}"></ion-icon>
                                                        @endfor
                                                    </div>
                                                    <div class="price-box">
                                                        @if ($product->sale_price)
                                                            <p class="price">{{ number_format($product->sale_price) }}₫
                                                            </p>
                                                            <del>{{ number_format($product->price) }}₫</del>
                                                        @else
                                                            <p class="price">{{ number_format($product->price) }}₫</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="showcase-container">
                                        @foreach ($remainingTopRated as $product)
                                            <div class="showcase">
                                                <a href="{{ route('product.show', $product->slug) }}"
                                                    class="showcase-img-box">
                                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                        class="showcase-img" width="70">
                                                </a>
                                                <div class="showcase-content">
                                                    <a href="{{ route('product.show', $product->slug) }}">
                                                        <h4 class="showcase-title">{{ $product->name }}</h4>
                                                    </a>
                                                    <a href="#" class="showcase-category">
                                                        {{ $product->categories->first()->name ?? 'Không có danh mục' }}
                                                    </a>
                                                    <div class="showcase-rating">
                                                        @php
                                                            $avg = round($product->reviews_avg_rating ?? 0);
                                                        @endphp
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <ion-icon
                                                                name="{{ $i <= $avg ? 'star' : 'star-outline' }}"></ion-icon>
                                                        @endfor
                                                    </div>
                                                    <div class="price-box">
                                                        @if ($product->sale_price)
                                                            <p class="price">{{ number_format($product->sale_price) }}₫
                                                            </p>
                                                            <del>{{ number_format($product->price) }}₫</del>
                                                        @else
                                                            <p class="price">{{ number_format($product->price) }}₫</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="product-main">
                        <h2 class="title">Sản phẩm mới</h2>
                        <div class="product-grid">
                            @foreach ($newProducts as $product)
                                <div class="showcase">
                                    <div class="showcase-banner">
                                        @php
                                            $images = $product->images;
                                            $defaultImage = $images->where('is_default', 1)->first();
                                            $secondImage = $images->where('is_default', 0)->first(); // hoặc đơn giản: $images->get(1);
                                        @endphp
                                        <img src="{{ $defaultImage ? asset('storage/' . $defaultImage->image_path) : asset('images/default.jpg') }}"
                                            alt="{{ $product->name }}" class="product-img default h-[200px]"
                                            width="300">

                                        <img src="{{ $secondImage ? asset('storage/' . $secondImage->image_path) : asset('images/default.jpg') }}"
                                            alt="{{ $product->name }}" class="product-img hover h-[200px]"
                                            width="300">
                                        @if ($product->created_at >= now()->subDays(7))
                                            <p class="showcase-badge angle pink">Mới</p>
                                        @elseif ($product->sale_price)
                                            <p class="showcase-badge">
                                                {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                                            </p>
                                            <p class="showcase-badge angle black">sale</p>
                                        @endif
                                        <div class="showcase-actions">
                                            <button class="btn-action toggle-wishlist-btn"
                                                data-product-id="{{ $product->id }}"
                                                data-is-wishlisted="{{ Auth::check() && Auth::user()->wishlist()->where('productID', $product->id)->exists() ? '1' : '0' }}">
                                                <ion-icon
                                                    name="{{ Auth::check() && Auth::user()->wishlist()->where('productID', $product->id)->exists() ? 'heart' : 'heart-outline' }}"></ion-icon>
                                            </button>
                                            <button class="btn-action quick-view-btn"
                                                data-product-slug="{{ $product->slug }}">
                                                <ion-icon name="eye-outline"></ion-icon>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="showcase-content">
                                        <a href="#" class="showcase-category">
                                            {{ $product->categories->first()->name ?? 'Không có danh mục' }}
                                        </a>
                                        <a href="{{ route('product.show', $product->slug) }}">
                                            <h3 class="showcase-title">{{ $product->name }}</h3>
                                        </a>
                                        <div class="showcase-rating">
                                            @php
                                                $avg = round($product->reviews->avg('rating') ?? 0);
                                            @endphp
                                            @for ($i = 1; $i <= 5; $i++)
                                                <ion-icon name="{{ $i <= $avg ? 'star' : 'star-outline' }}"></ion-icon>
                                            @endfor
                                        </div>
                                        <div class="price-box">
                                            @if ($product->sale_price)
                                                <p class="price">{{ number_format($product->sale_price, 0, ',', '.') }}₫
                                                </p>
                                                <del>{{ number_format($product->price, 0, ',', '.') }}₫</del>
                                            @else
                                                <p class="price">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="testimonials-box">
            <div class="container flex gap-[30px]">
                <div class="testimonial">
                    <h2 class="title">Đánh giá khách hàng</h2>

                    @foreach ($testimonials as $review)
                        <div class="testimonial-card">
                            <img src="{{ $review->user->avatar_url ?? asset('images/default_avatar.png') }}"
                                alt="{{ $review->user->name ?? 'Khách hàng ảnh' }}" class="testimonial-banner"
                                width="80" height="80">

                            <p class="testimonial-name">{{ $review->user->name ?? 'Khách hàng ẩn danh' }}</p>
                            <p class="testimonial-title">
                                {{ $review->product->name ?? 'Sản phẩm đã mua' }}
                            </p>

                            <img src="{{ asset('assets/images/icons/quotes.svg') }}" alt="quotation"
                                class="quotation-img" width="26">

                            <p class="testimonial-desc">
                                {{ Str::limit($review->comment, 120) }}
                            </p>
                        </div>
                    @endforeach
                    @if ($testimonials->isEmpty())
                        <p>Chưa có đánh giá nào từ khách hàng.</p>
                    @endif
                </div>

                <div class="cta-container">
                    <img src="{{ asset('assets/images/cta-banner.jpg') }}" alt="summer collection" class="cta-banner">
                    <a href="#" class="cta-content">
                        <p class="discount">25% Discount</p>
                        <h2 class="cta-title">Summer collection</h2>
                        <p class="cta-text">Starting @ $10</p>
                        <button class="cta-btn">Shop now</button>
                    </a>
                </div>
                <div class="service">
                    <h2 class="title">Dịch vụ của chúng tôi</h2>
                    <div class="service-container">
                        <a href="#" class="service-item">
                            <div class="service-icon">
                                <ion-icon name="rocket-outline"></ion-icon>
                            </div>
                            <div class="service-content">
                                <h3 class="service-title">Giao hàng nhanh</h3>
                                <p class="service-desc">Nhận hàng trong 1–3 ngày</p>
                            </div>
                        </a>

                        <a href="#" class="service-item">
                            <div class="service-icon">
                                <ion-icon name="call-outline"></ion-icon>
                            </div>
                            <div class="service-content">
                                <h3 class="service-title">Hỗ trợ 24/7</h3>
                                <p class="service-desc">Tư vấn mọi lúc mọi nơi</p>
                            </div>
                        </a>

                        <a href="#" class="service-item">
                            <div class="service-icon">
                                <ion-icon name="return-up-back-outline"></ion-icon>
                            </div>
                            <div class="service-content">
                                <h3 class="service-title">Đổi trả dễ dàng</h3>
                                <p class="service-desc">Miễn phí trong 7 ngày</p>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        <div class="blog">
            <div class="container">
                <div class="blog-container has-scrollbar">
                    @foreach ($blogs as $blog)
                        <div class="blog-card">
                            <a href="{{ route('blog.detail', $blog->slug) }}">
                                <img src="{{ asset($blog->image_path) }}" alt="{{ $blog->title }}" width="300"
                                    class="blog-banner">
                            </a>
                            <div class="blog-content">
                                <a href="#" class="blog-category">{{ $blog->category }}</a>
                                <a href="{{ route('blog.detail', $blog->slug) }}">
                                    <h3 class="blog-title">{{ $blog->title }}</h3>
                                </a>
                                <p class="blog-meta">
                                    By <cite>{{ $blog->author ?? 'Admin' }}</cite> /
                                    <time
                                        datetime="{{ $blog->created_at->format('Y-m-d') }}">{{ $blog->created_at->format('M d, Y') }}</time>
                                </p>
                            </div>
                        </div>
                    @endforeach
                    @if ($blogs->isEmpty())
                        <p>Hiện chưa có bài viết nào.</p>
                    @endif
                </div>
            </div>
        </div>
    </main>


    <!-- Modal Quick View -->
    <div id="quick-view-modal" class="quick-view-modal">
        <div class="w-[1200px] bg-[#fff] rounded-[10px]">
            <div class="flex flex-col">
                <div class="flex items-center justify-between p-4 border-b border-gray-300 border-dashed mb-4">
                    <h3>Xem nhanh</h3>
                    <button class="close-btn">×</button>
                </div>
                <div class="quick-view-body"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/home.js'])
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('quick-view-modal');
                const closeBtn = modal.querySelector('.close-btn');
                closeBtn.addEventListener('click', function() {
                    modal.classList.remove('active');
                });
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.remove('active');
                    }
                });
            });
            document.addEventListener('DOMContentLoaded', function() {
                // Xử lý toggle wishlist
                document.querySelectorAll('.toggle-wishlist-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const productId = this.getAttribute('data-product-id');
                        const isWishlisted = this.getAttribute('data-is-wishlisted') === '1';
                        const icon = this.querySelector('ion-icon');

                        axios.post(`/customer/product/${productId}/toggle-wishlist`, {}, {
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => {
                                if (response.data.success) {
                                    this.setAttribute('data-is-wishlisted', response.data
                                        .isWishlisted ? '1' : '0');
                                    icon.setAttribute('name', response.data.isWishlisted ? 'heart' :
                                        'heart-outline');
                                    Swal.fire({
                                        position: 'top-end',
                                        toast: true,
                                        icon: 'success',
                                        title: 'Thành công',
                                        text: response.data.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                } else {
                                    Swal.fire({
                                        position: 'top-end',
                                        toast: true,
                                        icon: 'error',
                                        title: 'Lỗi',
                                        text: response.data.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    position: 'top-end',
                                    toast: true,
                                    icon: 'error',
                                    title: 'Lỗi',
                                    text: error.response?.status === 401 ?
                                        'Vui lòng đăng nhập để sử dụng chức năng này!' :
                                        'Đã có lỗi xảy ra!',
                                    showConfirmButton: error.response?.status === 401,
                                    confirmButtonText: 'Đăng nhập',
                                }).then(result => {
                                    if (result.isConfirmed && error.response?.status ===
                                        401) {
                                        window.location.href = '/login';
                                    }
                                });
                            });
                    });
                });

                // Xử lý quick view
                document.querySelectorAll('.quick-view-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const slug = this.getAttribute('data-product-slug');
                        const modal = document.getElementById('quick-view-modal');
                        const modalBody = modal.querySelector('.quick-view-body');

                        axios.get(`/customer/products/${slug}/quick-view`)
                            .then(response => {
                                if (response.data.success) {
                                    modalBody.innerHTML = response.data.html;

                                    // ✅ Gán dữ liệu biến thể
                                    window.variantData = response.data.variantData;
                                    console.log('✅ Gán variantData từ Laravel:', window
                                        .variantData);

                                    modal.classList.add('active');

                                    if (typeof initQuickViewScripts === 'function') {
                                        initQuickViewScripts();
                                    }
                                } else {
                                    Swal.fire('Lỗi', response.data.message, 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Lỗi tải QuickView:', error);
                                Swal.fire('Lỗi', 'Không thể tải sản phẩm!', 'error');
                            });
                    });
                });

            });

            function initQuickViewScripts() {
                const mainImage = document.getElementById('main-image');
                const priceDisplay = document.getElementById('price-display');
                const stockInfo = document.getElementById('stock_info');
                const quantityInput = document.getElementById('quantity');
                const decreaseBtn = document.getElementById('decreaseQty');
                const increaseBtn = document.getElementById('increaseQty');
                const selectedVariantIdInput = document.getElementById('selected_variant_id');
                const addToCartButtons = document.querySelectorAll('.add-to-cart');
                const variantButtons = document.querySelectorAll('button[data-value]');
                const token = document.querySelector('meta[name="csrf-token"]')?.content;
                let selectedVariantId = null;

                if (!mainImage || !priceDisplay || !stockInfo || !quantityInput || !selectedVariantIdInput) {
                    console.warn('Thiếu phần tử DOM trong quick view, dừng init.');
                    return;
                }

                const hasVariants = variantButtons.length > 0;
                if (!hasVariants) {
                    selectedVariantId = 'default'; // sản phẩm không có biến thể
                    selectedVariantIdInput.value = 'default';
                }

                // Thay ảnh chính khi click ảnh phụ
                document.querySelectorAll('.sub-image').forEach(img => {
                    img.addEventListener('click', function() {
                        const newSrc = this.dataset.src;
                        if (mainImage && newSrc) mainImage.src = newSrc;
                    });
                });

                // Format số
                function number_format(number, decimals = 0, dec_point = ',', thousands_sep = '.') {
                    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
                    let n = !isFinite(+number) ? 0 : +number;
                    let prec = Math.abs(decimals);
                    let s = (prec ? (Math.round(n * Math.pow(10, prec)) / Math.pow(10, prec)).toFixed(prec) : '' + Math.round(
                        n)).split('.');
                    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, thousands_sep);
                    if ((s[1] || '').length < prec) {
                        s[1] = (s[1] || '') + '0'.repeat(prec - s[1].length);
                    }
                    return s.join(dec_point);
                }

                // Tăng/giảm số lượng
                if (decreaseBtn) {
                    decreaseBtn.addEventListener('click', () => {
                        let qty = parseInt(quantityInput.value);
                        if (qty > 1) quantityInput.value = qty - 1;
                    });
                }

                if (increaseBtn) {
                    increaseBtn.addEventListener('click', () => {
                        let qty = parseInt(quantityInput.value);
                        const stock = parseInt(stockInfo.textContent.split(' ')[0]) || 0;
                        if (qty < stock) quantityInput.value = qty + 1;
                    });
                }


                // Reset về trạng thái mặc định
                function resetToDefault(defaultImage, price, originalPrice, stock) {
                    selectedVariantId = null;
                    selectedVariantIdInput.value = 'default';
                    if (mainImage && defaultImage) mainImage.src = defaultImage;

                    priceDisplay.innerHTML = `
                        <span class="text-red-600 text-2xl font-bold">${number_format(price)} VNĐ</span>
                        ${originalPrice > price ? `<span class="text-gray-500 line-through text-md">${number_format(originalPrice)} VNĐ</span>
                                                                                                                                                                                                                                                                                                                <span class="bg-red-100 text-red-600 px-3 py-1 rounded text-xs">-${Math.round(((originalPrice - price) / originalPrice) * 100)}%</span>` : ''}
                    `;
                    stockInfo.textContent = `${stock} sản phẩm có sẵn`;
                }

                // Xử lý chọn biến thể
                variantButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        const value = button.getAttribute('data-value');
                        const attributeName = button.getAttribute('data-attribute-name');
                        const optionsContainer = button.closest(`[id$="-options"]`);
                        const allButtons = optionsContainer.querySelectorAll('button[data-value]');

                        if (button.classList.contains('bg-gray-200') && button.classList.contains(
                                'border-gray-500')) {
                            button.classList.remove('bg-gray-200', 'border-gray-500');
                            button.classList.add('border-gray-300');
                            resetToDefault(button.dataset.defaultImage, button.dataset.price, button.dataset
                                .originalPrice, button.dataset.stock);
                            return;
                        }

                        allButtons.forEach(btn => {
                            btn.classList.remove('bg-gray-200', 'border-gray-500');
                            btn.classList.add('border-gray-300');
                        });

                        button.classList.remove('border-gray-300');
                        button.classList.add('bg-gray-200', 'border-gray-500');

                        const selectedAttributes = {};
                        document.querySelectorAll('[id$="-options"] button[data-value].bg-gray-200').forEach(
                            btn => {
                                const attrName = btn.getAttribute('data-attribute-name');
                                const attrValue = btn.getAttribute('data-value');
                                selectedAttributes[attrName] = attrValue;
                            });

                        const variantData = window.variantData || {};
                        let matched = null;

                        for (let id in variantData) {
                            let variant = variantData[id];
                            let matchedAll = true;
                            for (let attr in selectedAttributes) {
                                if (!variant.attributes || variant.attributes[attr] !== selectedAttributes[
                                        attr]) {
                                    matchedAll = false;
                                    break;
                                }
                            }
                            if (matchedAll) {
                                matched = {
                                    id,
                                    ...variant
                                };
                                break;
                            }
                        }

                        console.log('Đã chọn attributes:', selectedAttributes);
                        console.log('Dữ liệu variantData:', variantData);
                        console.log('Biến thể phù hợp:', matched);

                        if (matched) {
                            selectedVariantId = matched.id;
                            selectedVariantIdInput.value = matched.id;

                            // Hiển thị lại giá
                            if (priceDisplay) {
                                priceDisplay.innerHTML = `
                                    <span class="text-red-600 text-2xl font-bold">${number_format(matched.price)} VNĐ</span>
                                    <span class="text-gray-500 line-through text-md">${number_format(matched.original_price)} VNĐ</span>
                                    <span class="bg-red-100 text-red-600 px-3 py-1 rounded text-xs">-${matched.discount_percentage}%</span>
                                `;
                            }

                            if (mainImage) {
                                console.log('Hiển thị ảnh biến thể:', matched.image);
                                mainImage.src = matched.image || '/storage/product_images/default.jpg';
                            }
                            if (stockInfo) stockInfo.textContent = `${matched.stock} sản phẩm có sẵn`;
                        } else {
                            // Không tìm thấy biến thể phù hợp → reset
                            selectedVariantId = null;
                            selectedVariantIdInput.value = '';
                            resetToDefault(
                                mainImage?.dataset.default || '/storage/product_images/default.jpg',
                                parseFloat(priceDisplay?.dataset.price || 0),
                                parseFloat(priceDisplay?.dataset.originalPrice || 0),
                                parseInt(stockInfo?.dataset.stock || 0)
                            );

                        }

                    });
                });

                // Xử lý thêm vào giỏ hàng
                addToCartButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        if (!selectedVariantId && hasVariants) {
                            Swal.fire({
                                position: 'top-end',
                                toast: true,
                                icon: 'warning',
                                title: 'Vui lòng chọn biến thể!',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            return;
                        }

                        const quantity = parseInt(quantityInput.value);
                        const stock = parseInt(stockInfo.textContent.split(' ')[0]);

                        if (quantity > stock) {
                            Swal.fire({
                                position: 'top-end',
                                toast: true,
                                icon: 'warning',
                                title: 'Vượt quá số lượng tồn kho!',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            return;
                        }

                        fetch('/customer/cart/add', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    product_id: button.getAttribute('data-product-id'),
                                    variant_id: selectedVariantId,
                                    quantity: quantity
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                Swal.fire({
                                    position: 'top-end',
                                    toast: true,
                                    icon: 'success',
                                    title: data.message || 'Thêm vào giỏ thành công',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            })
                            .catch(error => {
                                Swal.fire({
                                    position: 'top-end',
                                    toast: true,
                                    icon: 'error',
                                    title: 'Lỗi',
                                    text: 'Không thể thêm sản phẩm vào giỏ!',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            });
                    });
                });
            }


            if (typeof initQuickViewScripts === 'function') {
                initQuickViewScripts();
            }
        </script>
    @endpush
@endsection
