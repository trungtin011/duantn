@extends('layouts.app')
@section('title', 'Trang chủ')
@section('meta-description',
    'Trang chủ của website bán hàng trực tuyến, nơi bạn có thể tìm thấy các sản phẩm mới nhất
    và ưu đãi hấp dẫn.')
@section('meta-keywords', 'trang chủ, mua sắm trực tuyến, sản phẩm mới, ưu đãi, thời trang, điện tử')
<!-- Custom style -->
@push('styles')
    @vite(['resources/css/user/style-prefix.css', 'resources/css/user/style-home.css'])
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
                <img src="./assets/images/newsletter.png" alt="Đăng ký nhận tin" width="400" height="400">
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
                <p class="toast-message">
                    Ai đó vừa mua sản phẩm
                </p>
                <p class="toast-title">
                    {{ $product->name }}
                </p>
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
                                        <a href="#">{{ $parentCategory->name ?? 'Danh mục'}}</a>
                                    </li>

                                    @foreach ($subCategories as $subcategory)
                                        <li class="panel-list-item">
                                            <a href="#">{{ $subcategory->name }}</a>
                                        </li>
                                    @endforeach

                                    {{-- Banner nếu danh mục cha có ảnh --}}
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
                                                <img src="./assets/images/electronics-banner-1.jpg"
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

                                    {{-- Banner nếu danh mục con có ảnh --}}
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
                                <li class="menu-title"><a href="#">{{ $parentCategory->name ?? 'Danh mục'}}</a></li>

                                @foreach ($filteredSubCategories as $child)
                                    <li class="panel-list-item">
                                        <a href="#">{{ $child->name }}</a>
                                    </li>
                                @endforeach

                                {{-- Banner nếu danh mục cha có ảnh --}}
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
                                            <img src="./assets/images/electronics-banner-1.jpg" alt="headphone collection"
                                                width="250" height="119">
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
        <nav class="mobile-navigation-menu  has-scrollbar" data-mobile-menu>
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
                                <a href="#" class="submenu-title">Espa&ntilde;ol</a>
                            </li>
                            <li class="submenu-category">
                                <a href="#" class="submenu-title">Fren&ccedil;h</a>
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
                                <a href="#" class="submenu-title">USD &dollar;</a>
                            </li>
                            <li class="submenu-category">
                                <a href="#" class="submenu-title">EUR &euro;</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="menu-social-container">
                    <li>
                        <a href="#" class="social-link">
                            <ion-icon name="logo-facebook"></ion-icon>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="social-link">
                            <ion-icon name="logo-twitter"></ion-icon>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="social-link">
                            <ion-icon name="logo-instagram"></ion-icon>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="social-link">
                            <ion-icon name="logo-linkedin"></ion-icon>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <main>
        <div class="banner">
            <div class="container">
                <div class="slider-container has-scrollbar">
                    <div class="slider-item">
                        <img src="./assets/images/banner-1.jpg" alt="women's latest fashion sale" class="banner-img">
                        <div class="banner-content">
                            <p class="banner-subtitle">Trending item</p>
                            <h2 class="banner-title">Women's latest fashion sale</h2>
                            <p class="banner-text">
                                starting at &dollar; <b>20</b>.00
                            </p>
                            <a href="#" class="banner-btn">Shop now</a>
                        </div>
                    </div>
                    <div class="slider-item">
                        <img src="./assets/images/banner-2.jpg" alt="modern sunglasses" class="banner-img">
                        <div class="banner-content">
                            <p class="banner-subtitle">Trending accessories</p>
                            <h2 class="banner-title">Modern sunglasses</h2>
                            <p class="banner-text">
                                starting at &dollar; <b>15</b>.00
                            </p>
                            <a href="#" class="banner-btn">Shop now</a>
                        </div>
                    </div>
                    <div class="slider-item">
                        <img src="./assets/images/banner-3.jpg" alt="new fashion summer sale" class="banner-img">
                        <div class="banner-content">
                            <p class="banner-subtitle">Sale Offer</p>
                            <h2 class="banner-title">New fashion summer sale</h2>
                            <p class="banner-text">
                                starting at &dollar; <b>29</b>.99
                            </p>
                            <a href="#" class="banner-btn">Shop now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="category">
            <div class="container">
                <div class="category-item-container has-scrollbar">
                    @foreach ($homeCategories as $category)
                        <div class="category-item">
                            <div class="category-img-box">
                                {{-- Kiểm tra nếu không có ảnh --}}
                                @if ($category->image_path == null)
                                    <img src="{{ asset('assets/images/icons/' . Str::slug($category->name) . '.svg') }}"
                                        alt="{{ $category->name }}" width="30">
                                @else
                                    {{-- Nếu bạn có cột image_path --}}
                                    <img src="{{ asset('storage/' . $category->image_path) }}"
                                        alt="{{ $category->name }}" width="30">
                                @endif
                            </div>
                            <div class="category-content-box">
                                <div class="category-content-flex">
                                    <h3 class="category-item-title">{{ $category->name }}</h3>
                                    <p class="category-item-amount">({{ $category->products_count }})</p>
                                </div>
                                <a href="{{ route('search', $category->slug) }}" class="category-btn">Xem tất
                                    cả</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="product-container">
            <div class="container">
                <div class="sidebar  has-scrollbar" data-mobile-menu>
                    <div class="sidebar-category">
                        <div class="sidebar-top">
                            <h2 class="sidebar-title">Danh mục</h2>
                            <button class="sidebar-close-btn" data-mobile-menu-close-btn>
                                <ion-icon name="close-outline"></ion-icon>
                            </button>
                        </div>
                        <ul class="sidebar-menu-category-list">
                            @foreach ($sidebarCategories as $category)
                                <li class="sidebar-menu-category">
                                    <button class="sidebar-accordion-menu" data-accordion-btn>
                                        <div class="menu-title-flex">
                                            <img src="{{ $category->image_path ? asset('storage/' . $category->image_path) : asset('assets/images/icons/default.svg') }}"
                                                alt="{{ $category->name }}" class="menu-title-img" width="20"
                                                height="20">

                                            <p class="menu-title">{{ $category->name }}</p>
                                        </div>
                                        <div>
                                            <ion-icon name="add-outline" class="add-icon"></ion-icon>
                                            <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                                        </div>
                                    </button>

                                    @if ($category->subCategories->count())
                                        <ul class="sidebar-submenu-category-list" data-accordion>
                                            @foreach ($category->subCategories as $sub)
                                                <li class="sidebar-submenu-category">
                                                    <a href="#" class="sidebar-submenu-title">
                                                        <p class="product-name">{{ $sub->name }}</p>
                                                        <data value="{{ $sub->products->count() }}" class="stock"
                                                            title="Sản phẩm có sẵn">
                                                            {{ $sub->products->count() }}
                                                        </data>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
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
                    <div class="product-minimal">
                        <div class="product-showcase">
                            @php
                                $firstFour = $featuredProducts->take(4);
                                $others = $featuredProducts->slice(4);
                            @endphp
                            <h2 class="title">Sản phẩm nổi bật</h2>
                            @if ($featuredProducts->isEmpty())
                                <p>Hiện chưa có sản phẩm nổi bật nào.</p>
                            @else
                                <div class="showcase-wrapper has-scrollbar">
                                    {{-- BÊN TRÁI: 3 sản phẩm đầu --}}
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
                                                    <a href="#"
                                                        class="showcase-category">{{ optional($product->category)->name }}</a>
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

                                    {{-- BÊN PHẢI: Các sản phẩm còn lại --}}
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
                                                    <a href="#"
                                                        class="showcase-category">{{ optional($product->category)->name }}</a>
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
                            @php
                                $firstFourTrending = $trendingProducts->take(4);
                                $remainingTrending = $trendingProducts->slice(4);
                            @endphp

                            <h2 class="title">Đang thịnh hành</h2>

                            @if ($trendingProducts->isEmpty())
                                <p>Hiện chưa có sản phẩm nào thịnh hành.</p>
                            @else
                                <div class="showcase-wrapper has-scrollbar">

                                    {{-- BÊN TRÁI: 4 sản phẩm đầu --}}
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
                                                    <a href="#"
                                                        class="showcase-category">{{ optional($product->category)->name }}</a>
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

                                    {{-- BÊN PHẢI: Các sản phẩm còn lại --}}
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
                                                    <a href="#"
                                                        class="showcase-category">{{ optional($product->category)->name }}</a>
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
                            @php
                                $firstFourTopRated = $topRatedProducts->take(4);
                                $remainingTopRated = $topRatedProducts->slice(4);
                            @endphp

                            <h2 class="title">Đánh giá cao</h2>

                            @if ($topRatedProducts->isEmpty())
                                <p>Chưa có sản phẩm nào được đánh giá.</p>
                            @else
                                <div class="showcase-wrapper has-scrollbar">

                                    {{-- Trái: 4 sản phẩm đầu --}}
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
                                                    <a href="#"
                                                        class="showcase-category">{{ optional($product->category)->name }}</a>

                                                    {{-- Hiển thị sao --}}
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

                                    {{-- Phải: còn lại --}}
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
                                                    <a href="#"
                                                        class="showcase-category">{{ optional($product->category)->name }}</a>

                                                    {{-- Sao đánh giá --}}
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
                    <div class="product-featured">
                        <h2 class="title">Flash Sale</h2>
                        <div class="showcase-wrapper has-scrollbar">
                            @foreach ($flashSaleProducts as $product)
                                <div class="showcase-container">
                                    <div class="showcase">

                                        {{-- Hình ảnh --}}
                                        <div class="showcase-banner">
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                class="showcase-img">
                                        </div>

                                        <div class="showcase-content">

                                            {{-- Đánh giá sao --}}
                                            <div class="showcase-rating">
                                                @php
                                                    $avg = round($product->reviews->avg('rating') ?? 0);
                                                @endphp
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <ion-icon
                                                        name="{{ $i <= $avg ? 'star' : 'star-outline' }}"></ion-icon>
                                                @endfor
                                            </div>

                                            {{-- Tên sản phẩm --}}
                                            <a href="{{ route('product.show', $product->slug) }}">
                                                <h3 class="showcase-title">{{ $product->name }}</h3>
                                            </a>

                                            {{-- Mô tả ngắn --}}
                                            <p class="showcase-desc">
                                                {{ Str::limit(strip_tags($product->description), 100) }}
                                            </p>

                                            {{-- Giá flash sale --}}
                                            <div class="price-box">
                                                <p class="price">{{ number_format($product->flash_sale_price) }}₫</p>
                                                <del>{{ number_format($product->price) }}₫</del>
                                            </div>

                                            <button class="add-cart-btn">Thêm vào giỏ</button>

                                            {{-- Trạng thái số lượng --}}
                                            <div class="showcase-status">
                                                <div class="wrapper">
                                                    <p>Đã bán: <b>{{ $product->sold_quantity ?? 0 }}</b></p>
                                                    <p>Còn lại:
                                                        <b>{{ $product->stock_total - $product->sold_quantity }}</b>
                                                    </p>
                                                </div>
                                                <div class="showcase-status-bar"></div>
                                            </div>

                                            {{-- Countdown Flash Sale --}}
                                            @if ($product->flash_sale_end_at)
                                                <div class="countdown-box">
                                                    <p class="countdown-desc">Nhanh tay! Kết thúc sau:</p>

                                                    <div class="countdown"
                                                        data-end-time="{{ $product->flash_sale_end_at->timestamp }}">

                                                        {{-- Placeholder: sẽ cập nhật qua JS --}}
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

                    <div class="product-main">
                        <h2 class="title">New Products</h2>
                        <div class="product-grid">
                            <div class="showcase">
                                <div class="showcase-banner">
                                    <img src="./assets/images/products/jacket-3.jpg" alt="Mens Winter Leathers Jackets"
                                        width="300" class="product-img default">
                                    <img src="./assets/images/products/jacket-4.jpg" alt="Mens Winter Leathers Jackets"
                                        width="300" class="product-img hover">
                                    <p class="showcase-badge">15%</p>
                                    <div class="showcase-actions">
                                        <button class="btn-action">
                                            <ion-icon name="heart-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="eye-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="repeat-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="bag-add-outline"></ion-icon>
                                        </button>
                                    </div>
                                </div>
                                <div class="showcase-content">
                                    <a href="#" class="showcase-category">jacket</a>
                                    <a href="#">
                                        <h3 class="showcase-title">Mens Winter Leathers Jackets</h3>
                                    </a>
                                    <div class="showcase-rating">
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star-outline"></ion-icon>
                                        <ion-icon name="star-outline"></ion-icon>
                                    </div>
                                    <div class="price-box">
                                        <p class="price">$48.00</p>
                                        <del>$75.00</del>
                                    </div>
                                </div>
                            </div>
                            <div class="showcase">
                                <div class="showcase-banner">
                                    <img src="./assets/images/products/shirt-1.jpg" alt="Pure Garment Dyed Cotton Shirt"
                                        class="product-img default" width="300">
                                    <img src="./assets/images/products/shirt-2.jpg" alt="Pure Garment Dyed Cotton Shirt"
                                        class="product-img hover" width="300">
                                    <p class="showcase-badge angle black">sale</p>
                                    <div class="showcase-actions">
                                        <button class="btn-action">
                                            <ion-icon name="heart-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="eye-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="repeat-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="bag-add-outline"></ion-icon>
                                        </button>
                                    </div>
                                </div>
                                <div class="showcase-content">
                                    <a href="#" class="showcase-category">shirt</a>
                                    <h3>
                                        <a href="#" class="showcase-title">Pure Garment Dyed Cotton Shirt</a>
                                    </h3>
                                    <div class="showcase-rating">
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star-outline"></ion-icon>
                                        <ion-icon name="star-outline"></ion-icon>
                                    </div>
                                    <div class="price-box">
                                        <p class="price">$45.00</p>
                                        <del>$56.00</del>
                                    </div>
                                </div>
                            </div>
                            <div class="showcase">
                                <div class="showcase-banner">
                                    <img src="./assets/images/products/jacket-5.jpg" alt="MEN Yarn Fleece Full-Zip Jacket"
                                        class="product-img default" width="300">
                                    <img src="./assets/images/products/jacket-6.jpg" alt="MEN Yarn Fleece Full-Zip Jacket"
                                        class="product-img hover" width="300">
                                    <div class="showcase-actions">
                                        <button class="btn-action">
                                            <ion-icon name="heart-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="eye-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="repeat-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="bag-add-outline"></ion-icon>
                                        </button>
                                    </div>
                                </div>
                                <div class="showcase-content">
                                    <a href="#" class="showcase-category">Jacket</a>
                                    <h3>
                                        <a href="#" class="showcase-title">MEN Yarn Fleece Full-Zip Jacket</a>
                                    </h3>
                                    <div class="showcase-rating">
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star-outline"></ion-icon>
                                        <ion-icon name="star-outline"></ion-icon>
                                    </div>
                                    <div class="price-box">
                                        <p class="price">$58.00</p>
                                        <del>$65.00</del>
                                    </div>
                                </div>
                            </div>
                            <div class="showcase">
                                <div class="showcase-banner">
                                    <img src="./assets/images/products/clothes-3.jpg" alt="Black Floral Wrap Midi Skirt"
                                        class="product-img default" width="300">
                                    <img src="./assets/images/products/clothes-4.jpg" alt="Black Floral Wrap Midi Skirt"
                                        class="product-img hover" width="300">
                                    <p class="showcase-badge angle pink">new</p>
                                    <div class="showcase-actions">
                                        <button class="btn-action">
                                            <ion-icon name="heart-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="eye-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="repeat-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="bag-add-outline"></ion-icon>
                                        </button>
                                    </div>
                                </div>
                                <div class="showcase-content">
                                    <a href="#" class="showcase-category">skirt</a>
                                    <h3>
                                        <a href="#" class="showcase-title">Black Floral Wrap Midi Skirt</a>
                                    </h3>
                                    <div class="showcase-rating">
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                    </div>
                                    <div class="price-box">
                                        <p class="price">$25.00</p>
                                        <del>$35.00</del>
                                    </div>
                                </div>
                            </div>
                            <div class="showcase">
                                <div class="showcase-banner">
                                    <img src="./assets/images/products/shoe-2.jpg" alt="Casual Men's Brown shoes"
                                        class="product-img default" width="300">
                                    <img src="./assets/images/products/shoe-2_1.jpg" alt="Casual Men's Brown shoes"
                                        class="product-img hover" width="300">
                                    <div class="showcase-actions">
                                        <button class="btn-action">
                                            <ion-icon name="heart-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="eye-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="repeat-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="bag-add-outline"></ion-icon>
                                        </button>
                                    </div>
                                </div>
                                <div class="showcase-content">
                                    <a href="#" class="showcase-category">casual</a>
                                    <h3>
                                        <a href="#" class="showcase-title">Casual Men's Brown shoes</a>
                                    </h3>
                                    <div class="showcase-rating">
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                    </div>
                                    <div class="price-box">
                                        <p class="price">$99.00</p>
                                        <del>$105.00</del>
                                    </div>
                                </div>
                            </div>
                            <div class="showcase">
                                <div class="showcase-banner">
                                    <img src="./assets/images/products/watch-3.jpg" alt="Pocket Watch Leather Pouch"
                                        class="product-img default" width="300">
                                    <img src="./assets/images/products/watch-4.jpg" alt="Pocket Watch Leather Pouch"
                                        class="product-img hover" width="300">
                                    <p class="showcase-badge angle black">sale</p>
                                    <div class="showcase-actions">
                                        <button class="btn-action">
                                            <ion-icon name="heart-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="eye-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="repeat-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="bag-add-outline"></ion-icon>
                                        </button>
                                    </div>
                                </div>
                                <div class="showcase-content">
                                    <a href="#" class="showcase-category">watches</a>
                                    <h3>
                                        <a href="#" class="showcase-title">Pocket Watch Leather Pouch</a>
                                    </h3>
                                    <div class="showcase-rating">
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star-outline"></ion-icon>
                                        <ion-icon name="star-outline"></ion-icon>
                                    </div>
                                    <div class="price-box">
                                        <p class="price">$150.00</p>
                                        <del>$170.00</del>
                                    </div>
                                </div>
                            </div>
                            <div class="showcase">
                                <div class="showcase-banner">
                                    <img src="./assets/images/products/watch-1.jpg" alt="Smart watche Vital Plus"
                                        class="product-img default" width="300">
                                    <img src="./assets/images/products/watch-2.jpg" alt="Smart watche Vital Plus"
                                        class="product-img hover" width="300">
                                    <div class="showcase-actions">
                                        <button class="btn-action">
                                            <ion-icon name="heart-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="eye-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="repeat-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="bag-add-outline"></ion-icon>
                                        </button>
                                    </div>
                                </div>
                                <div class="showcase-content">
                                    <a href="#" class="showcase-category">watches</a>
                                    <h3>
                                        <a href="#" class="showcase-title">Smart watche Vital Plus</a>
                                    </h3>
                                    <div class="showcase-rating">
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star-outline"></ion-icon>
                                    </div>
                                    <div class="price-box">
                                        <p class="price">$100.00</p>
                                        <del>$120.00</del>
                                    </div>
                                </div>
                            </div>
                            <div class="showcase">
                                <div class="showcase-banner">
                                    <img src="./assets/images/products/party-wear-1.jpg" alt="Womens Party Wear Shoes"
                                        class="product-img default" width="300">
                                    <img src="./assets/images/products/party-wear-2.jpg" alt="Womens Party Wear Shoes"
                                        class="product-img hover" width="300">
                                    <p class="showcase-badge angle black">sale</p>
                                    <div class="showcase-actions">
                                        <button class="btn-action">
                                            <ion-icon name="heart-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="eye-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="repeat-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="bag-add-outline"></ion-icon>
                                        </button>
                                    </div>
                                </div>
                                <div class="showcase-content">
                                    <a href="#" class="showcase-category">party wear</a>
                                    <h3>
                                        <a href="#" class="showcase-title">Womens Party Wear Shoes</a>
                                    </h3>
                                    <div class="showcase-rating">
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star-outline"></ion-icon>
                                        <ion-icon name="star-outline"></ion-icon>
                                    </div>
                                    <div class="price-box">
                                        <p class="price">$25.00</p>
                                        <del>$30.00</del>
                                    </div>
                                </div>
                            </div>
                            <div class="showcase">
                                <div class="showcase-banner">
                                    <img src="./assets/images/products/jacket-1.jpg" alt="Mens Winter Leathers Jackets"
                                        class="product-img default" width="300">
                                    <img src="./assets/images/products/jacket-2.jpg" alt="Mens Winter Leathers Jackets"
                                        class="product-img hover" width="300">
                                    <div class="showcase-actions">
                                        <button class="btn-action">
                                            <ion-icon name="heart-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="eye-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="repeat-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="bag-add-outline"></ion-icon>
                                        </button>
                                    </div>
                                </div>
                                <div class="showcase-content">
                                    <a href="#" class="showcase-category">jacket</a>
                                    <h3>
                                        <a href="#" class="showcase-title">Mens Winter Leathers Jackets</a>
                                    </h3>
                                    <div class="showcase-rating">
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star-outline"></ion-icon>
                                    </div>
                                    <div class="price-box">
                                        <p class="price">$32.00</p>
                                        <del>$45.00</del>
                                    </div>
                                </div>
                            </div>
                            <div class="showcase">
                                <div class="showcase-banner">
                                    <img src="./assets/images/products/sports-2.jpg"
                                        alt="Trekking & Running Shoes - black" class="product-img default"
                                        width="300">
                                    <img src="./assets/images/products/sports-4.jpg"
                                        alt="Trekking & Running Shoes - black" class="product-img hover" width="300">
                                    <p class="showcase-badge angle black">sale</p>
                                    <div class="showcase-actions">
                                        <button class="btn-action">
                                            <ion-icon name="heart-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="eye-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="repeat-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="bag-add-outline"></ion-icon>
                                        </button>
                                    </div>
                                </div>
                                <div class="showcase-content">
                                    <a href="#" class="showcase-category">sports</a>
                                    <h3>
                                        <a href="#" class="showcase-title">Trekking & Running Shoes - black</a>
                                    </h3>
                                    <div class="showcase-rating">
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star-outline"></ion-icon>
                                        <ion-icon name="star-outline"></ion-icon>
                                    </div>
                                    <div class="price-box">
                                        <p class="price">$58.00</p>
                                        <del>$64.00</del>
                                    </div>
                                </div>
                            </div>
                            <div class="showcase">
                                <div class="showcase-banner">
                                    <img src="./assets/images/products/shoe-1.jpg" alt="Men's Leather Formal Wear shoes"
                                        class="product-img default" width="300">
                                    <img src="./assets/images/products/shoe-1_1.jpg" alt="Men's Leather Formal Wear shoes"
                                        class="product-img hover" width="300">
                                    <div class="showcase-actions">
                                        <button class="btn-action">
                                            <ion-icon name="heart-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="eye-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="repeat-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="bag-add-outline"></ion-icon>
                                        </button>
                                    </div>
                                </div>
                                <div class="showcase-content">
                                    <a href="#" class="showcase-category">formal</a>
                                    <h3>
                                        <a href="#" class="showcase-title">Men's Leather Formal Wear shoes</a>
                                    </h3>
                                    <div class="showcase-rating">
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star-outline"></ion-icon>
                                    </div>
                                    <div class="price-box">
                                        <p class="price">$50.00</p>
                                        <del>$65.00</del>
                                    </div>
                                </div>
                            </div>
                            <div class="showcase">
                                <div class="showcase-banner">
                                    <img src="./assets/images/products/shorts-1.jpg"
                                        alt="Better Basics French Terry Sweatshorts" class="product-img default"
                                        width="300">
                                    <img src="./assets/images/products/shorts-2.jpg"
                                        alt="Better Basics French Terry Sweatshorts" class="product-img hover"
                                        width="300">
                                    <p class="showcase-badge angle black">sale</p>
                                    <div class="showcase-actions">
                                        <button class="btn-action">
                                            <ion-icon name="heart-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="eye-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="repeat-outline"></ion-icon>
                                        </button>
                                        <button class="btn-action">
                                            <ion-icon name="bag-add-outline"></ion-icon>
                                        </button>
                                    </div>
                                </div>
                                <div class="showcase-content">
                                    <a href="#" class="showcase-category">shorts</a>
                                    <h3>
                                        <a href="#" class="showcase-title">Better Basics French Terry
                                            Sweatshorts</a>
                                    </h3>
                                    <div class="showcase-rating">
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star"></ion-icon>
                                        <ion-icon name="star-outline"></ion-icon>
                                        <ion-icon name="star-outline"></ion-icon>
                                    </div>
                                    <div class="price-box">
                                        <p class="price">$78.00</p>
                                        <del>$85.00</del>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="container">
                <div class="testimonials-box">
                    <div class="testimonial">
                        <h2 class="title">testimonial</h2>
                        <div class="testimonial-card">
                            <img src="./assets/images/testimonial-1.jpg" alt="alan doe" class="testimonial-banner"
                                width="80" height="80">
                            <p class="testimonial-name">Alan Doe</p>
                            <p class="testimonial-title">CEO & Founder Invision</p>
                            <img src="./assets/images/icons/quotes.svg" alt="quotation" class="quotation-img"
                                width="26">
                            <p class="testimonial-desc">
                                Lorem ipsum dolor sit amet consectetur Lorem ipsum
                                dolor dolor sit amet.
                            </p>
                        </div>
                    </div>
                    <div class="cta-container">
                        <img src="./assets/images/cta-banner.jpg" alt="summer collection" class="cta-banner">
                        <a href="#" class="cta-content">
                            <p class="discount">25% Discount</p>
                            <h2 class="cta-title">Summer collection</h2>
                            <p class="cta-text">Starting @ $10</p>
                            <button class="cta-btn">Shop now</button>
                        </a>
                    </div>
                    <div class="service">
                        <h2 class="title">Our Services</h2>
                        <div class="service-container">
                            <a href="#" class="service-item">
                                <div class="service-icon">
                                    <ion-icon name="boat-outline"></ion-icon>
                                </div>
                                <div class="service-content">
                                    <h3 class="service-title">Worldwide Delivery</h3>
                                    <p class="service-desc">For Order Over $100</p>
                                </div>
                            </a>
                            <a href="#" class="service-item">
                                <div class="service-icon">
                                    <ion-icon name="rocket-outline"></ion-icon>
                                </div>
                                <div class="service-content">
                                    <h3 class="service-title">Next Day delivery</h3>
                                    <p class="service-desc">UK Orders Only</p>
                                </div>
                            </a>
                            <a href="#" class="service-item">
                                <div class="service-icon">
                                    <ion-icon name="call-outline"></ion-icon>
                                </div>
                                <div class="service-content">
                                    <h3 class="service-title">Best Online Support</h3>
                                    <p class="service-desc">Hours: 8AM - 11PM</p>
                                </div>
                            </a>
                            <a href="#" class="service-item">
                                <div class="service-icon">
                                    <ion-icon name="arrow-undo-outline"></ion-icon>
                                </div>
                                <div class="service-content">
                                    <h3 class="service-title">Return Policy</h3>
                                    <p class="service-desc">Easy & Free Return</p>
                                </div>
                            </a>
                            <a href="#" class="service-item">
                                <div class="service-icon">
                                    <ion-icon name="ticket-outline"></ion-icon>
                                </div>
                                <div class="service-content">
                                    <h3 class="service-title">30% money back</h3>
                                    <p class="service-desc">For Order Over $100</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="blog">
            <div class="container">
                <div class="blog-container has-scrollbar">
                    <div class="blog-card">
                        <a href="#">
                            <img src="./assets/images/blog-1.jpg"
                                alt="Clothes Retail KPIs 2021 Guide for Clothes Executives" width="300"
                                class="blog-banner">
                        </a>
                        <div class="blog-content">
                            <a href="#" class="blog-category">Fashion</a>
                            <a href="#">
                                <h3 class="blog-title">Clothes Retail KPIs 2021 Guide for Clothes Executives.</h3>
                            </a>
                            <p class="blog-meta">
                                By <cite>Mr Admin</cite> / <time datetime="2022-04-06">Apr 06, 2022</time>
                            </p>
                        </div>
                    </div>
                    <div class="blog-card">
                        <a href="#">
                            <img src="./assets/images/blog-2.jpg"
                                alt="Curbside fashion Trends: How to Win the Pickup Battle." class="blog-banner"
                                width="300">
                        </a>
                        <div class="blog-content">
                            <a href="#" class="blog-category">Clothes</a>
                            <h3>
                                <a href="#" class="blog-title">Curbside fashion Trends: How to Win the Pickup
                                    Battle.</a>
                            </h3>
                            <p class="blog-meta">
                                By <cite>Mr Robin</cite> / <time datetime="2022-01-18">Jan 18, 2022</time>
                            </p>
                        </div>
                    </div>
                    <div class="blog-card">
                        <a href="#">
                            <img src="./assets/images/blog-3.jpg"
                                alt="EBT vendors: Claim Your Share of SNAP Online Revenue." class="blog-banner"
                                width="300">
                        </a>
                        <div class="blog-content">
                            <a href="#" class="blog-category">Shoes</a>
                            <h3>
                                <a href="#" class="blog-title">EBT vendors: Claim Your Share of SNAP Online
                                    Revenue.</a>
                            </h3>
                            <p class="blog-meta">
                                By <cite>Mr Selsa</cite> / <time datetime="2022-02-10">Feb 10, 2022</time>
                            </p>
                        </div>
                    </div>
                    <div class="blog-card">
                        <a href="#">
                            <img src="./assets/images/blog-4.jpg"
                                alt="Curbside fashion Trends: How to Win the Pickup Battle." class="blog-banner"
                                width="300">
                        </a>
                        <div class="blog-content">
                            <a href="#" class="blog-category">Electronics</a>
                            <h3>
                                <a href="#" class="blog-title">Curbside fashion Trends: How to Win the Pickup
                                    Battle.</a>
                            </h3>
                            <p class="blog-meta">
                                By <cite>Mr Pawar</cite> / <time datetime="2022-03-15">Mar 15, 2022</time>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- JavaScript -->
    @push('scripts')
        <script src="{{ asset('js/home.js') }}"></script>
    @endpush
@endsection
