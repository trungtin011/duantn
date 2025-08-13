@extends('layouts.app')
@section('title', 'Trang chủ')
@section('meta-description',
    'Trang chủ của website bán hàng trực tuyến, nơi bạn có thể tìm thấy các sản phẩm mới nhất
    và ưu đãi hấp dẫn.')
@section('meta-keywords', 'trang chủ, mua sắm trực tuyến, sản phẩm mới, ưu đãi, thời trang, điện tử')

<!-- Custom style -->
@push('styles')
    @vite(['resources/css/user/style-home.css'])
    {{-- @vite(['resources/css/user/style.css']) --}}
    <style>
        /* Custom styles for enhanced shop ranking */
        .quick-view-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .quick-view-modal.active {
            display: flex;
        }

        /* Animation for shop ranking cards */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .shop-ranking-card {
            animation: slideInUp 0.3s ease-out;
        }



        /* Responsive fixes for shop ranking cards */
        @media (max-width: 640px) {
            .shop-ranking-card {
                padding: 0.75rem;
            }

            .shop-ranking-card .grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 0.5rem;
            }

            .shop-ranking-card .text-xs {
                font-size: 0.625rem;
            }
        }

        /* Ensure ranking badges don't overflow */
        .ranking-badge {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        /* Hiệu ứng lửa cho top 1 */
        .flame-effect {
            background: linear-gradient(45deg,
                    transparent 20%,
                    rgba(255, 107, 53, 0.7) 35%,
                    rgba(239, 50, 72, 0.9) 50%,
                    rgba(255, 107, 53, 0.7) 65%,
                    transparent 80%);
            animation: flame-glow 1.5s ease-in-out infinite alternate;
            pointer-events: none;
        }

        @keyframes flame-glow {
            0% {
                opacity: 0.6;
                transform: scale(1);
            }

            100% {
                opacity: 1;
                transform: scale(1.08);
            }
        }



        /* Hiệu ứng lửa thực tế */
        .flame-particle {
            animation: flame-flicker 1.2s ease-in-out infinite alternate;
        }

        .flame-particle:nth-child(2) {
            animation-delay: 0.3s;
        }

        .flame-particle:nth-child(3) {
            animation-delay: 0.6s;
        }

        .flame-particle:nth-child(4) {
            animation-delay: 0.9s;
        }

        @keyframes flame-flicker {
            0% {
                opacity: 0.4;
                transform: scale(0.8) translateY(0px);
            }

            50% {
                opacity: 0.8;
                transform: scale(1.1) translateY(-2px);
            }

            100% {
                opacity: 1;
                transform: scale(1) translateY(-1px);
            }
        }

        /* Shop ranking container */
        .shop-ranking-container {
            padding-top: 0.5rem;
        }

        /* Đồng nhất chiều cao tên sản phẩm */
        .showcase-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 3rem;
            line-height: 1.4;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .product-minimal .showcase-title {
            min-height: 2.5rem;
        }



        /* Responsive shop cards */
        @media (max-width: 768px) {
            .shop-ranking-container .grid {
                grid-template-columns: repeat(1, 1fr);
                gap: 0.75rem;
            }

            .shop-ranking-container .shop-ranking-card {
                width: 100%;
                min-width: unset;
                max-width: unset;
            }
        }

        @media (min-width: 769px) {
            .shop-ranking-container .grid {
                grid-template-columns: repeat(1, 1fr);
                gap: 1rem;
            }

            .shop-ranking-container .shop-ranking-card {
                width: 100%;
                min-width: unset;
                max-width: unset;
            }
        }

        .shop-title {
            -webkit-text-stroke-width: 0.5px;
            -webkit-text-stroke-color: rgb(0, 0, 0);
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
            text-transform: uppercase;
        }
    </style>
@endpush

@section('content')
    <div class="overlay" data-overlay></div>
    {{-- <div class="modal" data-modal>
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
                        <h3 class="newsletter-title">Đăng ký nhận tin</h3>
                        <p class="newsletter-desc">
                            Hãy đăng ký <b>Anon</b> để nhận thông tin sản phẩm mới và cập nhật khuyến mãi.
                        </p>
                    </div>
                    <input type="email" name="email" class="email-field" placeholder="Địa chỉ Email" required>
                    <button type="submit" class="btn-newsletter">Đăng ký</button>
                </form>
            </div>
        </div>
    </div> --}}

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
                    <li class="menu-category"></li>
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
                                                    alt="{{ $parentCategory->name }} banner" class="object-cover w-10">
                                            </a>
                                        </li>
                                    @else
                                        <li class="panel-list-item">
                                            <a href="#">
                                                <img src="{{ asset('assets/images/electronics-banner-1.jpg') }}"
                                                    alt="headphone collection" width="100" height="100">
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
                                                    alt="{{ $subCategory->name }} banner" class="object-cover w-10">
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

                    @if ($jewelrySub->isNotEmpty())
                        <li class="menu-category">
                            <a href="{{ route('search', ['category' => [$jewelryIsParent ? $jewelry->id : ($jewelryParent ? $jewelryParent->id : null)]]) }}"
                                class="menu-title">
                                {{ $jewelryIsParent ? 'Trang sức' : ($jewelryParent ? $jewelryParent->name : 'Trang sức') }}
                            </a>
                            <ul class="dropdown-list">
                                @foreach ($jewelrySub as $item)
                                    <li class="dropdown-item">
                                        <a
                                            href="{{ route('search', ['category' => [$item->id]]) }}">{{ $item->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif

                    <li class="menu-category">
                        <a href="{{ route('blog') }}" class="menu-title">BÀI VIẾT</a>
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
                    <a href="#" class="menu-title">Trang chủ</a>
                </li>
                <li class="menu-category">
                    <button class="accordion-menu" data-accordion-btn>
                        <p class="menu-title">Nam</p>
                        <div>
                            <ion-icon name="add-outline" class="add-icon"></ion-icon>
                            <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                        </div>
                    </button>
                    <ul class="submenu-category-list" data-accordion>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Áo sơ mi</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Quần short & Jeans</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Giày bảo hộ</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Ví</a>
                        </li>
                    </ul>
                </li>
                <li class="menu-category">
                    <button class="accordion-menu" data-accordion-btn>
                        <p class="menu-title">Nữ</p>
                        <div>
                            <ion-icon name="add-outline" class="add-icon"></ion-icon>
                            <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                        </div>
                    </button>
                    <ul class="submenu-category-list" data-accordion>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Váy & Đầm</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Khuyên tai</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Dây chuyền</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Bộ trang điểm</a>
                        </li>
                    </ul>
                </li>
                <li class="menu-category">
                    <button class="accordion-menu" data-accordion-btn>
                        <p class="menu-title">Trang sức</p>
                        <div>
                            <ion-icon name="add-outline" class="add-icon"></ion-icon>
                            <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                        </div>
                    </button>
                    <ul class="submenu-category-list" data-accordion>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Khuyên tai</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Nhẫn cặp</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Dây chuyền</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Vòng tay</a>
                        </li>
                    </ul>
                </li>
                <li class="menu-category">
                    <button class="accordion-menu" data-accordion-btn>
                        <p class="menu-title">Nước hoa</p>
                        <div>
                            <ion-icon name="add-outline" class="add-icon"></ion-icon>
                            <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                        </div>
                    </button>
                    <ul class="submenu-category-list" data-accordion>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Nước hoa quần áo</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Chất khử mùi</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Hương hoa</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Chất làm thơm không khí</a>
                        </li>
                    </ul>
                </li>
                <li class="menu-category">
                    <a href="#" class="menu-title">Bài viết</a>
                </li>
                <li class="menu-category">
                    <a href="#" class="menu-title">Ưu đãi hấp dẫn</a>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="banner">
            <div class="container">
                <div class="slider-container has-scrollbar">
                    @if ($banners->count() > 0)
                        @foreach ($banners as $banner)
                            <div class="slider-item">
                                <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="banner-img">
                                <div class="banner-content"
                                    style="
                                        @if ($banner->content_position == 'center') display: flex; align-items: center; justify-content: center;
                                        @elseif($banner->content_position == 'left')
                                            display: flex; align-items: center; justify-content: flex-start;
                                        @elseif($banner->content_position == 'right')
                                            display: flex; align-items: center; justify-content: flex-end;
                                        @elseif($banner->content_position == 'top-left')
                                            display: flex; align-items: flex-start; justify-content: flex-start;
                                        @elseif($banner->content_position == 'top-right')
                                            display: flex; align-items: flex-start; justify-content: flex-end;
                                        @elseif($banner->content_position == 'bottom-left')
                                            display: flex; align-items: flex-end; justify-content: flex-start;
                                        @elseif($banner->content_position == 'bottom-right')
                                            display: flex; align-items: flex-end; justify-content: flex-end; @endif
                                     ">
                                    <div
                                        style="
                                        text-align: {{ $banner->text_align ?? 'center' }};
                                        @if ($banner->title_color) color: {{ $banner->title_color }}; @endif
                                    ">
                                        @if ($banner->description)
                                            <p class="banner-subtitle"
                                                style="@if ($banner->subtitle_color) color: {{ $banner->subtitle_color }}; @endif @if ($banner->subtitle_font_size) font-size: {{ $banner->subtitle_font_size }}; @endif">
                                                {{ $banner->description }}
                                            </p>
                                        @endif
                                        <h2 class="banner-title"
                                            style="@if ($banner->title_color) color: {{ $banner->title_color }}; @endif @if ($banner->title_font_size) font-size: {{ $banner->title_font_size }}; @endif">
                                            {{ $banner->title }}
                                        </h2>
                                        @if ($banner->link_url)
                                            <a href="{{ $banner->link_url }}" data-banner-ad="{{ $banner->id }}"
                                                data-shop-id="0" class="banner-btn">Xem chi tiết</a>
                                        @else
                                            <a href="#" data-banner-ad="{{ $banner->id }}" data-shop-id="0"
                                                class="banner-btn">Xem chi tiết</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Fallback banners nếu không có banner nào từ database -->
                        <div class="slider-item">
                            <img src="{{ asset('assets/images/banner-1.jpg') }}" alt="women's latest fashion sale"
                                class="banner-img">
                            <div class="banner-content">
                                <p class="banner-subtitle">Sản phẩm thịnh hành</p>
                                <h2 class="banner-title">Thời trang nữ mới nhất</h2>
                                <p class="banner-text">bắt đầu từ <b>20</b>.000₫</p>
                                <a href="#" class="banner-btn">Mua ngay</a>
                            </div>
                        </div>
                        <div class="slider-item">
                            <img src="{{ asset('assets/images/banner-2.jpg') }}" alt="modern sunglasses"
                                class="banner-img">
                            <div class="banner-content">
                                <p class="banner-subtitle">Phụ kiện thịnh hành</p>
                                <h2 class="banner-title">Kính mát hiện đại</h2>
                                <p class="banner-text">bắt đầu từ <b>15</b>.000₫</p>
                                <a href="#" class="banner-btn">Mua ngay</a>
                            </div>
                        </div>
                        <div class="slider-item">
                            <img src="{{ asset('assets/images/banner-3.jpg') }}" alt="new fashion summer sale"
                                class="banner-img">
                            <div class="banner-content">
                                <p class="banner-subtitle">Ưu đãi giảm giá</p>
                                <h2 class="banner-title">Thời trang hè mới</h2>
                                <p class="banner-text">bắt đầu từ <b>29</b>.990₫</p>
                                <a href="#" class="banner-btn">Mua ngay</a>
                            </div>
                        </div>
                    @endif
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
                        <div class="category-item w-1/2 sm:w-[calc(25%-0.9375rem)] sm:min-w-[140px] flex-shrink-0">
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
                                    tất cả
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="product-container">
            <div class="container">
                <div class="sidebar has-scrollbar relative" data-mobile-menu>
                    <div class="mt-[100px] lg:mt-0">
                        <button
                            class="sidebar-close-btn p-2 hover:bg-orange-100 rounded-full transition-colors absolute top-20 right-2.5"
                            data-mobile-menu-close-btn>
                            <ion-icon name="close-outline" class="text-gray-500"></ion-icon>
                        </button>
                        <div class="rounded-xl border border-orange-200 p-3 md:p-4 mb-6 md:mb-8 lg:mt-0">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-base md:text-lg font-bold flex items-center justify-between gap-2 w-full">
                                    Shop Bán Chạy
                                    <ion-icon name="flame"
                                        class="text-orange-500 text-lg md:text-xl bg-white rounded-full p-1.5 md:p-2"></ion-icon>
                                </h2>
                            </div>
    
                            <!-- Header với legend -->
                            <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-2 md:p-3 mb-3 md:mb-4">
                                <h3
                                    class="text-sm md:text-md pl-3 pr-3 font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                    Top Shop theo doanh số bán hàng
                                </h3>
                                <div
                                    class="grid grid-cols-2 gap-x-1.5 md:gap-x-2 w-[160px] md:w-[200px] gap-y-1.5 md:gap-y-2 items-center mx-auto">
                                    <span
                                        class="inline-flex items-center px-2.5 md:px-3 py-0.5 md:py-1 rounded-full text-[11px] md:text-sm font-medium bg-gradient-to-r from-yellow-200 to-orange-200 text-orange-800 flex-shrink-0 border border-yellow-300">
                                        <ion-icon name="trophy" class="mr-1 text-md"></ion-icon>
                                        Top 1
                                    </span>
                                    <span
                                        class="inline-flex items-center px-2.5 md:px-3 py-0.5 md:py-1 rounded-full text-[11px] md:text-sm font-medium bg-gradient-to-r from-gray-200 to-slate-300 text-slate-700 flex-shrink-0 border border-gray-300">
                                        <ion-icon name="medal" class="mr-1 text-md"></ion-icon>
                                        Top 2
                                    </span>
                                    <span
                                        class="inline-flex items-center px-2.5 md:px-3 py-0.5 md:py-1 rounded-full text-[11px] md:text-sm font-medium bg-gradient-to-r from-amber-200 to-yellow-200 text-amber-800 flex-shrink-0 border border-amber-300">
                                        <ion-icon name="ribbon" class="mr-1 text-md"></ion-icon>
                                        Top 3
                                    </span>
                                    <span
                                        class="inline-flex items-center px-2.5 md:px-3 py-0.5 md:py-1 rounded-full text-[11px] md:text-sm font-medium bg-gradient-to-r from-blue-200 to-purple-200 text-blue-800 flex-shrink-0 border border-blue-300">
                                        <ion-icon name="star" class="mr-1 text-md"></ion-icon>
                                        Top 4
                                    </span>
                                </div>
                            </div>
    
                            <!-- Danh sách shop -->
                            <div class="shop-ranking-container">
                                <div class="grid grid-cols-1 gap-2 md:gap-3 pb-2 relative">
                                    @if ($rankingShops->count() > 0)
                                        @foreach ($rankingShops as $index => $shop)
                                            <div class="relative bg-white border border-gray-200 rounded-lg p-2 md:p-2.5 shop-ranking-card {{ $index < 3 ? 'ring-1 ring-opacity-30' : '' }} {{ $index === 0 ? 'ring-yellow-400 bg-gradient-to-r from-yellow-50 to-orange-50' : '' }} {{ $index === 1 ? 'ring-gray-400 bg-gradient-to-r from-gray-50 to-slate-50' : '' }} {{ $index === 2 ? 'ring-amber-600 bg-gradient-to-r from-amber-50 to-yellow-50' : '' }}"
                                                style="animation-delay: {{ $index * 0.1 }}s;">
    
                                                <!-- Badge xếp hạng -->
                                                <div
                                                    class="absolute -top-1.5 -left-1.5 w-4 h-4 md:w-5 md:h-5 rounded-full flex items-center justify-center text-white font-bold text-[10px] md:text-xs shadow-md z-10 {{ $index === 0 ? 'bg-gradient-to-r from-yellow-400 to-orange-500' : '' }} {{ $index === 1 ? 'bg-gradient-to-r from-gray-400 to-slate-500' : '' }} {{ $index === 2 ? 'bg-gradient-to-r from-amber-600 to-yellow-500' : '' }} {{ $index > 2 ? 'bg-gradient-to-r from-blue-500 to-purple-600' : '' }}">
                                                    {{ $index + 1 }}
                                                </div>
    
                                                <!-- Header shop -->
                                                <div class="flex items-start justify-between mb-3 gap-2">
                                                    <div class="flex items-center gap-2 flex-1 min-w-0">
                                                        <div class="relative flex-shrink-0">
                                                            <img src="{{ asset('storage/' . $shop->shop_logo) }}"
                                                                alt="{{ $shop->shop_name }}"
                                                                class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover border border-gray-200">
                                                            @if ($index < 3)
                                                                <div
                                                                    class="absolute -bottom-0.5 -right-0.5 w-4 h-4 md:w-5 md:h-5 rounded-full flex items-center justify-center text-[9px] md:text-xs {{ $index === 0 ? 'bg-yellow-500' : '' }} {{ $index === 1 ? 'bg-gray-500' : '' }} {{ $index === 2 ? 'bg-amber-600' : '' }}">
                                                                    <ion-icon
                                                                        name="{{ $index === 0 ? 'trophy' : ($index === 1 ? 'medal' : 'ribbon') }}"
                                                                        class="text-white text-[7px] md:text-[8px]"></ion-icon>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <h4
                                                                class="font-semibold text-gray-800 text-sm md:text-md truncate">
                                                                {{ $shop->shop_name }}</h4>
                                                            <div class="flex items-center gap-1 mt-1">
                                                                <div class="flex items-center gap-1">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        @if ($shop->shop_rating >= $i)
                                                                            <ion-icon name="star"
                                                                                class="text-yellow-400 text-sm md:text-md"></ion-icon>
                                                                        @elseif($shop->shop_rating >= $i - 0.5)
                                                                            <ion-icon name="star-half"
                                                                                class="text-yellow-400 text-sm md:text-md"></ion-icon>
                                                                        @else
                                                                            <ion-icon name="star-outline"
                                                                                class="text-gray-300 text-sm md:text-md"></ion-icon>
                                                                        @endif
                                                                    @endfor
                                                                </div>
                                                                <span
                                                                    class="text-sm md:text-md text-gray-600 font-medium">{{ number_format($shop->shop_rating, 1) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                    <!-- Ranking badge -->
                                                    <span
                                                        class="ranking-badge inline-flex items-center px-1 py-0.5 md:px-1.5 md:py-0.5 rounded-full text-[11px] md:text-[12px] font-medium flex-shrink-0 {{ $index === 0 ? 'bg-gradient-to-r from-yellow-200 to-orange-200 text-orange-800 border border-yellow-300' : '' }} {{ $index === 1 ? 'bg-gradient-to-r from-gray-200 to-slate-300 text-slate-700 border border-gray-300' : '' }} {{ $index === 2 ? 'bg-gradient-to-r from-amber-200 to-yellow-200 text-amber-800 border border-amber-300' : '' }} {{ $index > 2 ? 'bg-gradient-to-r from-blue-200 to-purple-200 text-blue-800 border border-blue-300' : '' }}">
                                                        <ion-icon
                                                            name="{{ $index === 0 ? 'trophy' : ($index === 1 ? 'medal' : ($index === 2 ? 'ribbon' : 'star')) }}"
                                                            class="mr-1 text-sm md:text-md"></ion-icon>
                                                        <span class="text-[11px] md:text-[12px]">{{ $index + 1 }}</span>
                                                    </span>
                                                </div>
    
                                                <!-- Stats -->
                                                <div
                                                    class="text-center p-1.5 md:p-2 bg-gradient-to-r from-green-50 to-blue-50 rounded border border-green-200 mb-1.5 md:mb-2">
                                                    <!-- Header với icon và label -->
                                                    <div class="flex items-center justify-center gap-1 mb-1">
                                                        <ion-icon name="bag-check-outline"
                                                            class="text-[#ef3248] text-sm md:text-md"></ion-icon>
                                                        <span class="text-sm md:text-md font-medium text-gray-800">Đã
                                                            bán</span>
                                                    </div>
    
                                                    <!-- Số lượng bán -->
                                                    <p class="text-base md:text-lg font-bold text-[#ef3248] mb-2">
                                                        {{ number_format($shop->total_products_sold) }}
                                                    </p>
    
                                                    <!-- Progress bar -->
                                                    <div class="relative h-2 md:h-3 bg-gray-200 rounded-full overflow-hidden">
                                                        @php
                                                            $maxSales = max(
                                                                $rankingShops->pluck('total_products_sold')->toArray(),
                                                            );
                                                            $percentage =
                                                                $maxSales > 0
                                                                    ? ($shop->total_products_sold / $maxSales) * 100
                                                                    : 0;
    
                                                            // Phối màu chủ đạo với gradient đẹp
                                                            $barColor = 'bg-gradient-to-r from-[#ef3248] to-[#ff6b35]';
                                                        @endphp
                                                        <div class="h-full {{ $barColor }} rounded-full transition-all duration-300 relative"
                                                            style="width: {{ min($percentage, 100) }}%">
                                                            @if ($shop->total_products_sold >= 100)
                                                                <div class="absolute -right-1 -top-0.5">
                                                                    <ion-icon name="flame"
                                                                        class="text-orange-500 text-sm md:text-md"></ion-icon>
                                                                </div>
                                                            @endif
                                                        </div>
    
                                                        <!-- Hiệu ứng lửa xung quanh cho top 1 -->
                                                        @if ($index === 0)
                                                            <div class="absolute inset-0 rounded-full flame-effect"></div>
                                                            <!-- Các ngọn lửa nhỏ -->
                                                            <div class="absolute -top-1 left-1 flame-particle">
                                                                <ion-icon name="flame"
                                                                    class="text-orange-500 text-sm md:text-md"></ion-icon>
                                                            </div>
                                                            <div class="absolute -top-1 right-1 flame-particle">
                                                                <ion-icon name="flame"
                                                                    class="text-red-500 text-sm md:text-md"></ion-icon>
                                                            </div>
                                                            <div class="absolute -bottom-1 left-3 flame-particle">
                                                                <ion-icon name="flame"
                                                                    class="text-yellow-500 text-sm md:text-md"></ion-icon>
                                                            </div>
                                                            <div class="absolute -bottom-1 right-3 flame-particle">
                                                                <ion-icon name="flame"
                                                                    class="text-orange-500 text-sm md:text-md"></ion-icon>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
    
                                                <!-- Action button -->
                                                <a href="{{ route('shop.show', $shop->id) }}"
                                                    class="block w-full text-center py-1 px-2 bg-[#ef3248] hover:bg-[#d62a3e] text-white font-medium rounded transition-colors duration-200 text-sm md:text-base">
                                                    <span class="flex items-center justify-center gap-1">
                                                        Xem shop
                                                        <ion-icon name="arrow-forward-outline"
                                                            class="text-sm md:text-md"></ion-icon>
                                                    </span>
                                                </a>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center py-8 text-gray-500">
                                            <ion-icon name="storefront-outline" class="text-4xl mb-2"></ion-icon>
                                            <p>Chưa có shop nào để hiển thị</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
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
                                                <a href="{{ route('product.show', $product->slug) }}" class="h-[30px]">
                                                    <h4 class="showcase-title">{{ $product->name }}</h4>
                                                </a>
                                                <div class="showcase-rating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($product->orderReviews->avg('rating') >= $i)
                                                            <ion-icon name="star"></ion-icon>
                                                        @elseif ($product->orderReviews->avg('rating') >= $i - 0.5)
                                                            <ion-icon name="star-half-outline"></ion-icon>
                                                        @else
                                                            <ion-icon name="star-outline"></ion-icon>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <div class="price-box">
                                                    @if ($product->display_original_price && $product->display_price < $product->display_original_price)
                                                        <p class="price">
                                                            {{ number_format($product->display_price, 0, ',', '.') }}₫</p>
                                                        <del>{{ number_format($product->display_original_price, 0, ',', '.') }}₫</del>
                                                    @else
                                                        <p class="price">
                                                            {{ number_format($product->display_price, 0, ',', '.') }}₫
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
                </div>

                <div class="product-box">
                    @if ($flashSaleProducts->count())
                        <div class="product-flash-sale">
                            <div class="flex items-center justify-between title-flash-sale">
                                {{-- Countdown --}}
                                <div class="countdown-box flex items-center gap-2">
                                    <h2 class="font-bold">Flash Sale</h2>
                                    @if ($flashSaleProducts->first()->flash_sale_end_at)
                                        <span class="countdown-timer"
                                            id="countdown-{{ $flashSaleProducts->first()->id }}"
                                            data-end-time="{{ $flashSaleProducts->first()->flash_sale_end_at->timestamp }}">
                                            <span class="display-number">00</span> :
                                            <span class="display-number">00</span> :
                                            <span class="display-number">00</span>
                                        </span>
                                    @endif
                                </div>
                                <a href=""
                                    class="text-[#ef3248] hover:underline text-sm font-medium whitespace-nowrap">
                                    Xem tất cả
                                </a>
                            </div>

                            <div class="product-demo">


                                {{-- Khung flash sale không cuộn --}}
                                <div class="flash-sale-container">
                                    <div class="flash-sale-scroll-container">
                                        <div class="flash-sale-box">
                                            <div class="showcase-wrapper flex gap-4">
                                                @foreach ($flashSaleProducts as $product)
                                                    <div
                                                        class="showcase-container flex flex-col justify-center items-center w-[200px]">
                                                        <div class="flash-sale-header w-[200px] h-[200px] relative">
                                                            <span class="discount absolute top-[10px] right-[10px]">
                                                                @if ($product->display_original_price_for_flash_sale > 0)
                                                                    -{{ round((1 - $product->display_flash_sale_price / $product->display_original_price_for_flash_sale) * 100) }}%
                                                                @else
                                                                    -0%
                                                                @endif
                                                            </span>
                                                            <img src="{{ $product->image_url }}"
                                                                alt="{{ $product->name }}"
                                                                class="w-full h-full object-cover">
                                                        </div>
                                                        <div class="flash-sale-price">
                                                            <span
                                                                class="new-price">₫{{ number_format($product->display_flash_sale_price, 0) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @include('partials.combo_products')

                    <div class="product-minimal">
                        <div class="product-showcase">
                            <h2 class="title">Sản phẩm nổi bật</h2>
                            @if ($featuredProducts->isEmpty())
                                <p>Không có sản phẩm nào.</p>
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
                                                    <a href="{{ route('product.show', $product->slug) }}"
                                                        class="h-[30px]">
                                                        <h4 class="showcase-title">{{ $product->name }}</h4>
                                                    </a>
                                                    <a href="{{ route('search', ['category' => [$category->id]]) }}"
                                                        class="showcase-category">
                                                        {{ $product->categories->first()->name ?? 'Không có danh mục' }}
                                                    </a>
                                                    <div class="price-box">
                                                        @if ($product->display_original_price && $product->display_price < $product->display_original_price)
                                                            <p class="price">
                                                                {{ number_format($product->display_price) }}₫
                                                            </p>
                                                            <del>{{ number_format($product->display_original_price) }}₫</del>
                                                        @else
                                                            <p class="price">
                                                                {{ number_format($product->display_price) }}₫</p>
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
                                                    <a href="{{ route('product.show', $product->slug) }}"
                                                        class="h-[30px]">
                                                        <h4 class="showcase-title">{{ $product->name }}</h4>
                                                    </a>
                                                    <a href="{{ route('search', ['category' => [$category->id]]) }}"
                                                        class="showcase-category">
                                                        {{ $product->categories->first()->name ?? 'Không có danh mục' }}
                                                    </a>
                                                    <div class="price-box">
                                                        @if ($product->display_original_price && $product->display_price < $product->display_original_price)
                                                            <p class="price">
                                                                {{ number_format($product->display_price) }}₫
                                                            </p>
                                                            <del>{{ number_format($product->display_original_price) }}₫</del>
                                                        @else
                                                            <p class="price">
                                                                {{ number_format($product->display_price) }}₫</p>
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
                            <h2 class="title">Được xem nhiều</h2>
                            @if ($trendingProducts->isEmpty())
                                <p class="mt-4">Hiện chưa có sản phẩm nào được xem.</p>
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
                                                    <img src="{{ $product->defaultImage ? asset('storage/' . $product->defaultImage->image_path) : asset('storage/product_images/default.jpg') }}"
                                                        alt="{{ $product->name }}" class="showcase-img" width="70">
                                                </a>
                                                <div class="showcase-content">
                                                    <a href="{{ route('product.show', $product->slug) }}"
                                                        class="h-[30px]">
                                                        <h4 class="showcase-title">{{ $product->name }}</h4>
                                                    </a>
                                                    <a href="{{ route('search', ['category' => [$product->categories->first()->id ?? 1]]) }}"
                                                        class="showcase-category">
                                                        {{ $product->categories->first()->name ?? 'Không có danh mục' }}
                                                    </a>
                                                    <div class="price-box">
                                                        @if ($product->is_variant && $product->variants->isNotEmpty())
                                                            @if ($product->display_price < $product->display_original_price)
                                                                <p class="price">
                                                                    {{ number_format($product->display_price) }}₫
                                                                </p>
                                                                <del>{{ number_format($product->display_original_price) }}₫</del>
                                                            @else
                                                                <p class="price">
                                                                    {{ number_format($product->display_price) }}₫</p>
                                                            @endif
                                                        @else
                                                            @if ($product->display_price < $product->display_original_price)
                                                                <p class="price">
                                                                    {{ number_format($product->display_price) }}₫
                                                                </p>
                                                                <del>{{ number_format($product->display_original_price) }}₫</del>
                                                            @else
                                                                <p class="price">
                                                                    {{ number_format($product->display_price) }}₫</p>
                                                            @endif
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
                                                    <img src="{{ $product->defaultImage ? asset('storage/' . $product->defaultImage->image_path) : asset('storage/product_images/default.jpg') }}"
                                                        alt="{{ $product->name }}" class="showcase-img" width="70">
                                                </a>
                                                <div class="showcase-content">
                                                    <a href="{{ route('product.show', $product->slug) }}"
                                                        class="h-[30px]">
                                                        <h4 class="showcase-title">{{ $product->name }}</h4>
                                                    </a>
                                                    <a href="{{ route('search', ['category' => [$product->categories->first()->id ?? 1]]) }}"
                                                        class="showcase-category">
                                                        {{ $product->categories->first()->name ?? 'Không có danh mục' }}
                                                    </a>
                                                    <div class="price-box">
                                                        @if ($product->is_variant && $product->variants->isNotEmpty())
                                                            @if ($product->display_price < $product->display_original_price)
                                                                <p class="price">
                                                                    {{ number_format($product->display_price) }}₫
                                                                </p>
                                                                <del>{{ number_format($product->display_original_price) }}₫</del>
                                                            @else
                                                                <p class="price">
                                                                    {{ number_format($product->display_price) }}₫</p>
                                                            @endif
                                                        @else
                                                            @if ($product->display_price < $product->display_original_price)
                                                                <p class="price">
                                                                    {{ number_format($product->display_price) }}₫
                                                                </p>
                                                                <del>{{ number_format($product->display_original_price) }}₫</del>
                                                            @else
                                                                <p class="price">
                                                                    {{ number_format($product->display_price) }}₫</p>
                                                            @endif
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
                                <p>Không có sản phẩm nào.</p>
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
                                                    <a href="{{ route('product.show', $product->slug) }}"
                                                        class="h-[30px]">
                                                        <h4 class="showcase-title">{{ $product->name }}</h4>
                                                    </a>
                                                    <a href="{{ route('search', ['category' => [$category->id]]) }}"
                                                        class="showcase-category">
                                                        {{ $product->categories->first()->name ?? 'Không có danh mục' }}
                                                    </a>
                                                    <div class="showcase-rating">
                                                        @php
                                                            $avg = round($product->orderReviews->avg('rating') ?? 0);
                                                        @endphp
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <ion-icon
                                                                name="{{ $i <= $avg ? 'star' : 'star-outline' }}"></ion-icon>
                                                        @endfor
                                                    </div>
                                                    <div class="price-box">
                                                        @if ($product->display_original_price && $product->display_price < $product->display_original_price)
                                                            <p class="price">
                                                                {{ number_format($product->display_price) }}₫
                                                            </p>
                                                            <del>{{ number_format($product->display_original_price) }}₫</del>
                                                        @else
                                                            <p class="price">
                                                                {{ number_format($product->display_price) }}₫</p>
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
                                                    <a href="{{ route('product.show', $product->slug) }}"
                                                        class="h-[30px]">
                                                        <h4 class="showcase-title">{{ $product->name }}</h4>
                                                    </a>
                                                    <a href="{{ route('search', ['category' => [$category->id]]) }}"
                                                        class="showcase-category">
                                                        {{ $product->categories->first()->name ?? 'Không có danh mục' }}
                                                    </a>
                                                    <div class="showcase-rating">
                                                        @php
                                                            $avg = round($product->orderReviews->avg('rating') ?? 0);
                                                        @endphp
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <ion-icon
                                                                name="{{ $i <= $avg ? 'star' : 'star-outline' }}"></ion-icon>
                                                        @endfor
                                                    </div>
                                                    <div class="price-box">
                                                        @if ($product->display_original_price && $product->display_price < $product->display_original_price)
                                                            <p class="price">
                                                                {{ number_format($product->display_price) }}₫
                                                            </p>
                                                            <del>{{ number_format($product->display_original_price) }}₫</del>
                                                        @else
                                                            <p class="price">
                                                                {{ number_format($product->display_price) }}₫</p>
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
                                        <a href="{{ route('search', ['category' => [$category->id]]) }}"
                                            class="showcase-category">
                                            {{ $product->categories->first()->name ?? 'Không có danh mục' }}
                                        </a>
                                        <a href="{{ route('product.show', $product->slug) }}" class="h-[30px]">
                                            <h3 class="showcase-title truncate">{{ $product->name }}</h3>
                                        </a>
                                        <div class="showcase-rating">
                                            @php
                                                $avg = round($product->orderReviews->avg('rating') ?? 0);
                                            @endphp
                                            @for ($i = 1; $i <= 5; $i++)
                                                <ion-icon name="{{ $i <= $avg ? 'star' : 'star-outline' }}"></ion-icon>
                                            @endfor
                                        </div>
                                        <div class="price-box">
                                            @if ($product->display_original_price && $product->display_price < $product->display_original_price)
                                                <p class="price">
                                                    {{ number_format($product->display_price, 0, ',', '.') }}₫
                                                </p>
                                                <del
                                                    class="truncate text-xs">{{ number_format($product->display_original_price, 0, ',', '.') }}₫</del>
                                            @else
                                                <p class="price">
                                                    {{ number_format($product->display_price, 0, ',', '.') }}₫</p>
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

                    @if ($testimonials->isNotEmpty())
                        <!-- Testimonials Slider Container -->
                        <div class="testimonials-slider-container">
                            @php
                                $testimonialsPerSlide = 1; // Hiển thị 1 testimonial mỗi slide
                                $totalTestimonialSlides = ceil($testimonials->count() / $testimonialsPerSlide);
                            @endphp

                            @for ($slideIndex = 0; $slideIndex < $totalTestimonialSlides; $slideIndex++)
                                <div class="testimonial-slide {{ $slideIndex === 0 ? 'active' : '' }}"
                                    data-slide="{{ $slideIndex }}">
                                    @foreach ($testimonials->slice($slideIndex * $testimonialsPerSlide, $testimonialsPerSlide) as $review)
                                        <div class="testimonial-card">
                                            <div class="flex items-center gap-2">
                                                @include('partials.user-avatar', [
                                                    'user' => $review->user,
                                                    'size' => '2xl',
                                                    'className' => 'testimonial-banner',
                                                ])
                                            </div>

                                            <p class="testimonial-name truncate">
                                                {{ $review->user->username ?? 'Khách hàng ẩn danh' }}
                                            </p>
                                            <p class="testimonial-title truncate">
                                                {{ $review->product->name ?? 'Sản phẩm đã mua' }}
                                            </p>

                                            <img src="{{ asset('assets/images/icons/quotes.svg') }}" alt="quotation"
                                                class="quotation-img" width="26">

                                            <p class="testimonial-desc truncate">
                                                {{ Str::limit($review->comment, 120) }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            @endfor
                        </div>

                        <!-- Testimonials Dots Navigation -->
                        @if ($totalTestimonialSlides > 1)
                            <div class="testimonials-dots">
                                @for ($dotIndex = 0; $dotIndex < $totalTestimonialSlides; $dotIndex++)
                                    <button class="testimonial-dot {{ $dotIndex === 0 ? 'active' : '' }}"
                                        data-slide="{{ $dotIndex }}"
                                        aria-label="Go to testimonial slide {{ $dotIndex + 1 }}">
                                    </button>
                                @endfor
                            </div>
                        @endif
                    @else
                        <p>Chưa có đánh giá nào từ khách hàng.</p>
                    @endif
                </div>

                <div class="cta-container">
                    @if ($advertisedProducts->isNotEmpty())
                        <div class="advertised-products-container">
                            <h3 class="advertised-title">Sản phẩm quảng cáo</h3>

                            <!-- Slides Container -->
                            <div class="advertised-slides-container">
                                @php
                                    $productsPerSlide = 2;
                                    $totalSlides = ceil($advertisedProducts->count() / $productsPerSlide);
                                @endphp

                                @for ($slideIndex = 0; $slideIndex < $totalSlides; $slideIndex++)
                                    <div class="advertised-slide {{ $slideIndex === 0 ? 'active' : '' }}"
                                        data-slide="{{ $slideIndex }}">
                                        <div class="advertised-grid">
                                            @foreach ($advertisedProducts->slice($slideIndex * $productsPerSlide, $productsPerSlide) as $adItem)
                                                <div class="advertised-item">
                                                    <div class="ad-badge">Quảng cáo</div>
                                                    <a href="{{ route('product.show', $adItem->product->slug) }}"
                                                        class="ad-product-link">
                                                        <img src="{{ $adItem->product->image_url }}"
                                                            alt="{{ $adItem->product->name }}" class="ad-product-img">
                                                        <div class="ad-product-info">
                                                            <h4 class="ad-product-name">
                                                                {{ Str::limit($adItem->product->name, 30) }}</h4>
                                                            <div class="ad-product-price">
                                                                @if (
                                                                    $adItem->product->display_original_price &&
                                                                        $adItem->product->display_price < $adItem->product->display_original_price)
                                                                    <span
                                                                        class="ad-price-new">{{ number_format($adItem->product->display_price) }}₫</span>
                                                                    <span
                                                                        class="ad-price-old">{{ number_format($adItem->product->display_original_price) }}₫</span>
                                                                @else
                                                                    <span
                                                                        class="ad-price-new">{{ number_format($adItem->product->display_price) }}₫</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            <!-- Dots Navigation -->
                            @if ($totalSlides > 1)
                                <div class="advertised-dots">
                                    @for ($dotIndex = 0; $dotIndex < $totalSlides; $dotIndex++)
                                        <button class="advertised-dot {{ $dotIndex === 0 ? 'active' : '' }}"
                                            data-slide="{{ $dotIndex }}"
                                            aria-label="Go to slide {{ $dotIndex + 1 }}">
                                        </button>
                                    @endfor
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Fallback banner nếu không có quảng cáo -->
                        <img src="{{ asset('assets/images/cta-banner.jpg') }}" alt="summer collection"
                            class="cta-banner">
                        <a href="#" class="cta-content">
                            <p class="discount">Giảm 25%</p>
                            <h2 class="cta-title">Bộ sưu tập hè</h2>
                            <p class="cta-text">Bắt đầu từ 10.000₫</p>
                            <button class="cta-btn">Mua ngay</button>
                        </a>
                    @endif
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

        <div class="py-10 bg-white">
            <div class="max-w-[1200px] mx-auto px-4">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900">Bài viết mới</h2>
                    <a href="{{ route('blog') }}" class="text-sm text-[#f42f46] hover:underline">Xem tất cả</a>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @forelse ($blogs as $blog)
                        @php
                            $photo = $blog->photo ?? ($blog->image_path ?? null);
                            $img =
                                $photo && file_exists(public_path($photo))
                                    ? asset($photo)
                                    : ($photo && filter_var($photo, FILTER_VALIDATE_URL)
                                        ? $photo
                                        : asset('frontend/img/default.jpg'));
                            $catTitle = $blog->cat_info->title ?? ($blog->category ?? null);
                            $catSlug =
                                $blog->cat_info->slug ?? ($catTitle ? \Illuminate\Support\Str::slug($catTitle) : null);
                            $author = $blog->author_info->username ?? ($blog->author ?? 'Ẩn danh');
                        @endphp

                        <article
                            class="group rounded-xl overflow-hidden border border-gray-100 bg-white shadow-sm hover:shadow-md transition">
                            <a href="{{ route('blog.detail', $blog->slug) }}"
                                class="block aspect-[16/10] overflow-hidden bg-gray-50">
                                <img src="{{ $img }}" alt="{{ $blog->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </a>
                            <div class="p-4">
                                @if ($catTitle)
                                    <a href="{{ $catSlug ? route('blog.category', $catSlug) : '#' }}"
                                        class="inline-block text-xs px-2 py-0.5 rounded bg-blue-50 text-blue-600">
                                        {{ $catTitle }}
                                    </a>
                                @endif
                                <a href="{{ route('blog.detail', $blog->slug) }}"
                                    class="block mt-2 text-base font-semibold text-gray-900 line-clamp-2">
                                    {{ $blog->title }}
                                </a>
                                <div class="mt-2 text-xs text-gray-500 flex items-center gap-2">
                                    <span>{{ $author }}</span>
                                    <span>•</span>
                                    <time
                                        datetime="{{ $blog->created_at->format('Y-m-d') }}">{{ $blog->created_at->format('d M, Y') }}</time>
                                </div>
                            </div>
                        </article>
                    @empty
                        <p class="text-gray-500">Chưa có bài viết nào.</p>
                    @endforelse
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
                    <button class="close-btn text-2xl">×</button>
                </div>
                <div class="quick-view-body"></div>
                <div class="p-4 border-t border-gray-200 flex justify-end">
                    <a data-login-link href="{{ route('login') }}?redirect={{ urlencode(request()->fullUrl()) }}"
                        class="hidden"></a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/home.js'])
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

                // Add Combo to Cart functionality (moved outside initQuickViewScripts)
                document.querySelectorAll('.add-combo-cart-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const comboId = this.getAttribute('data-combo-id');

                        fetch('/cart/add-combo', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    combo_id: comboId
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                Swal.fire({
                                    position: 'top-end',
                                    toast: true,
                                    icon: data.success ? 'success' : 'error',
                                    title: data.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            })
                            .catch(error => {
                                console.error('Error adding combo to cart:', error);
                                Swal.fire({
                                    position: 'top-end',
                                    toast: true,
                                    icon: 'error',
                                    title: 'Lỗi',
                                    text: 'Không thể thêm combo vào giỏ!',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
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
                    // Explicitly display default product price for simple products on modal open
                    const defaultPrice = parseFloat(priceDisplay.dataset.price);
                    const defaultOriginalPrice = parseFloat(priceDisplay.dataset.originalPrice);
                    const defaultStock = parseInt(stockInfo.dataset.stock);
                    resetToDefault(
                        mainImage?.src || '/storage/product_images/default.jpg', // Use main image src as default
                        defaultPrice,
                        defaultOriginalPrice,
                        defaultStock
                    );
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

                const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};

                // Xử lý thêm vào giỏ hàng
                addToCartButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        // Nếu chưa đăng nhập → mở Quick View (nếu đang đóng) và focus vùng chọn biến thể/CTA đăng nhập
                        if (!isAuthenticated) {
                            const modal = document.getElementById('quick-view-modal');
                            if (modal && !modal.classList.contains('active')) {
                                modal.classList.add('active');
                            }
                            const loginLink = document.querySelector('[data-login-link]');
                            if (loginLink) {
                                loginLink.click();
                            } else {
                                window.location.href = "{{ route('login') }}?redirect=" + encodeURIComponent(
                                    window.location.href);
                            }
                            return;
                        }
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
                            .then(res => {
                                if (res.status === 401) {
                                    const loginLink = document.querySelector('[data-login-link]');
                                    if (loginLink) loginLink.click();
                                    return Promise.reject('unauthenticated');
                                }
                                if (!res.ok) {
                                    return res.json().catch(() => ({})).then(err => Promise.reject(err
                                        .message || 'REQUEST_FAILED'));
                                }
                                return res.json();
                            })
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
                                if (error === 'unauthenticated') return;
                                Swal.fire({
                                    position: 'top-end',
                                    toast: true,
                                    icon: 'error',
                                    title: 'Lỗi',
                                    text: (typeof error === 'string' ? error :
                                        'Không thể thêm sản phẩm vào giỏ!'),
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

            // Initialize price handling when DOM is loaded
            document.addEventListener('DOMContentLoaded', function() {
                initPriceHandling();
            });

            // Handle long prices automatically
            function initPriceHandling() {
                const priceBoxes = document.querySelectorAll('.product-minimal .price-box');

                priceBoxes.forEach(priceBox => {
                    const price = priceBox.querySelector('.price');
                    const delPrice = priceBox.querySelector('del');

                    if (price) {
                        // Check if price is too long
                        if (price.scrollWidth > price.offsetWidth) {
                            price.style.fontSize = '0.75rem';
                        }
                    }

                    if (delPrice) {
                        // Check if del price is too long
                        if (delPrice.scrollWidth > delPrice.offsetWidth) {
                            delPrice.style.fontSize = '0.625rem';
                        }
                    }
                });
            }

            // Initialize price handling when DOM is loaded
            document.addEventListener('DOMContentLoaded', function() {
                initPriceHandling();
            });
        </script>
    @endpush
@endsection
