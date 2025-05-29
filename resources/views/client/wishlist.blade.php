@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/client-wishlist.css') }}">
@endpush
@section('content')
<div class="container">
        <div class="wishlist-section" style="margin-top: 75px;">
            <div class="section-header">
                <div class="section-title">Danh sách ước (4)</div>
                <div class="add-all-to-cart">Chuyển tất cả vào giỏ hàng</div>
            </div>
            <div class="product-grid">
                <!-- Product Card 1 -->
                <div class="product-card">
                    <div class="product-image-container">
                        <div class="product-image-wrapper">
                            <div class="discount-badge">-35%</div>
                            <div class="action-icon">
                                <svg id="I165:4929;165:4785" layer-name="Fill Eye" width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg" class="delete-icon">
                                    <circle cx="17" cy="17" r="17" fill="white"></circle>
                                    <path d="M25 10.5714H10.3333L11.6667 26H22.3333L23.6667 10.5714H9M17 14.4286V22.1429M20.3333 14.4286L19.6667 22.1429M13.6667 14.4286L14.3333 22.1429M14.3333 10.5714L15 8H19L19.6667 10.5714" stroke="black" stroke-width="1.56" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <div class="product-image">
                                <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/81f9830de49264a5347c8b2000b897fc3e820018?placeholderIfAbsent=true" alt="Gucci duffle bag" class="product-img">
                            </div>
                            <button class="add-to-cart-bar" type="submit">
                                <div class="add-to-cart-content">
                                    <svg id="I165:4929;165:4813" layer-name="Cart1" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="cart-icon">
                                        <path d="M8.25 20.25C8.66421 20.25 9 19.9142 9 19.5C9 19.0858 8.66421 18.75 8.25 18.75C7.83579 18.75 7.5 19.0858 7.5 19.5C7.5 19.9142 7.83579 20.25 8.25 20.25Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M18.75 20.25C19.1642 20.25 19.5 19.9142 19.5 19.5C19.5 19.0858 19.1642 18.75 18.75 18.75C18.3358 18.75 18 19.0858 18 19.5C18 19.9142 18.3358 20.25 18.75 20.25Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M2.25 3.75H5.25L7.5 16.5H19.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M7.5 12.5H19.1925C19.2792 12.5001 19.3633 12.4701 19.4304 12.4151C19.4975 12.3601 19.5434 12.2836 19.5605 12.1986L20.9105 5.44859C20.9214 5.39417 20.92 5.338 20.9066 5.28414C20.8931 5.23029 20.8679 5.18009 20.8327 5.13717C20.7975 5.09426 20.7532 5.05969 20.703 5.03597C20.6528 5.01225 20.598 4.99996 20.5425 5H6" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <div class="add-to-cart-text">Thêm vào giỏ hàng</div>
                                </div>
                            </button>
                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-name">Gucci duffle bag</div>
                        <div class="product-price">
                            <div class="sale-price">$960</div>
                            <div class="original-price">$1160</div>
                        </div>
                    </div>
                </div>

                <!-- Product Card 2 -->
                <div class="product-card">
                    <div class="product-image-container">
                        <div class="product-image-wrapper">
                            <div class="action-icon">
                                <svg id="I165:4930;165:4823" layer-name="Fill Eye" width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg" class="delete-icon">
                                    <circle cx="17" cy="17" r="17" fill="white"></circle>
                                    <path d="M25 10.5714H10.3333L11.6667 26H22.3333L23.6667 10.5714H9M17 14.4286V22.1429M20.3333 14.4286L19.6667 22.1429M13.6667 14.4286L14.3333 22.1429M14.3333 10.5714L15 8H19L19.6667 10.5714" stroke="black" stroke-width="1.56" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <div class="product-image">
                                <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/089b895840e29b0fb4090c83fd3f828bc90389a4?placeholderIfAbsent=true" alt="RGB liquid CPU Cooler" class="product-img">
                            </div>
                            <div class="add-to-cart-bar">
                                <div class="add-to-cart-content">
                                    <svg id="I165:4930;165:4828" layer-name="Cart1" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="cart-icon">
                                        <path d="M8.25 20.25C8.66421 20.25 9 19.9142 9 19.5C9 19.0858 8.66421 18.75 8.25 18.75C7.83579 18.75 7.5 19.0858 7.5 19.5C7.5 19.9142 7.83579 20.25 8.25 20.25Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M18.75 20.25C19.1642 20.25 19.5 19.9142 19.5 19.5C19.5 19.0858 19.1642 18.75 18.75 18.75C18.3358 18.75 18 19.0858 18 19.5C18 19.9142 18.3358 20.25 18.75 20.25Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M2.25 3.75H5.25L7.5 16.5H19.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M7.5 12.5H19.1925C19.2792 12.5001 19.3633 12.4701 19.4304 12.4151C19.4975 12.3601 19.5434 12.2836 19.5605 12.1986L20.9105 5.44859C20.9214 5.39417 20.92 5.338 20.9066 5.28414C20.8931 5.23029 20.8679 5.18009 20.8327 5.13717C20.7975 5.09426 20.7532 5.05969 20.703 5.03597C20.6528 5.01225 20.598 4.99996 20.5425 5H6" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <div class="add-to-cart-text">Thêm vào giỏ hàng</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-name">RGB liquid CPU Cooler</div>
                        <div class="product-price">
                            <div class="sale-price">$1960</div>
                        </div>
                    </div>
                </div>

                <!-- Product Card 3 -->
                <div class="product-card">
                    <div class="product-image-container">
                        <div class="product-image-wrapper">
                            <div class="action-icon">
                                <svg id="I165:5156;165:5009" layer-name="Fill Eye" width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg" class="delete-icon">
                                    <circle cx="17" cy="17" r="17" fill="white"></circle>
                                    <path d="M25 10.5714H10.3333L11.6667 26H22.3333L23.6667 10.5714H9M17 14.4286V22.1429M20.3333 14.4286L19.6667 22.1429M13.6667 14.4286L14.3333 22.1429M14.3333 10.5714L15 8H19L19.6667 10.5714" stroke="black" stroke-width="1.56" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <div class="product-image">
                                <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/6b91f582cab1bbd0abbe77d68b0b975d6115d3d7?placeholderIfAbsent=true" alt="GP11 Shooter USB Gamepad" class="product-img">
                            </div>
                            <div class="add-to-cart-bar">
                                <div class="add-to-cart-content">
                                    <svg id="I165:5156;165:5011" layer-name="Cart1" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="cart-icon">
                                        <path d="M8.25 20.25C8.66421 20.25 9 19.9142 9 19.5C9 19.0858 8.66421 18.75 8.25 18.75C7.83579 18.75 7.5 19.0858 7.5 19.5C7.5 19.9142 7.83579 20.25 8.25 20.25Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M18.75 20.25C19.1642 20.25 19.5 19.9142 19.5 19.5C19.5 19.0858 19.1642 18.75 18.75 18.75C18.3358 18.75 18 19.0858 18 19.5C18 19.9142 18.3358 20.25 18.75 20.25Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M2.25 3.75H5.25L7.5 16.5H19.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M7.5 12.5H19.1925C19.2792 12.5001 19.3633 12.4701 19.4304 12.4151C19.4975 12.3601 19.5434 12.2836 19.5605 12.1986L20.9105 5.44859C20.9214 5.39417 20.92 5.338 20.9066 5.28414C20.8931 5.23029 20.8679 5.18009 20.8327 5.13717C20.7975 5.09426 20.7532 5.05969 20.703 5.03597C20.6528 5.01225 20.598 4.99996 20.5425 5H6" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <div class="add-to-cart-text">Thêm vào giỏ hàng</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-name">GP11 Shooter USB Gamepad</div>
                        <div class="product-price">
                            <div class="sale-price">$550</div>
                        </div>
                    </div>
                </div>

                <!-- Product Card 4 -->
                <div class="product-card">
                    <div class="product-image-container">
                        <div class="product-image-wrapper">
                            <div class="action-icon">
                                <svg id="I165:5375;165:5181" layer-name="Fill Eye" width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg" class="delete-icon">
                                    <circle cx="17" cy="17" r="17" fill="white"></circle>
                                    <path d="M25 10.5714H10.3333L11.6667 26H22.3333L23.6667 10.5714H9M17 14.4286V22.1429M20.3333 14.4286L19.6667 22.1429M13.6667 14.4286L14.3333 22.1429M14.3333 10.5714L15 8H19L19.6667 10.5714" stroke="black" stroke-width="1.56" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <div class="product-image">
                                <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/b74531b92771582a3fe3bbad1ee11c545d3a3806?placeholderIfAbsent=true" alt="Quilted Satin Jacket" class="product-img">
                            </div>
                            <div class="add-to-cart-bar">
                                <div class="add-to-cart-content">
                                    <svg id="I165:5375;165:5183" layer-name="Cart1" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="cart-icon">
                                        <path d="M8.25 20.25C8.66421 20.25 9 19.9142 9 19.5C9 19.0858 8.66421 18.75 8.25 18.75C7.83579 18.75 7.5 19.0858 7.5 19.5C7.5 19.9142 7.83579 20.25 8.25 20.25Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M18.75 20.25C19.1642 20.25 19.5 19.9142 19.5 19.5C19.5 19.0858 19.1642 18.75 18.75 18.75C18.3358 18.75 18 19.0858 18 19.5C18 19.9142 18.3358 20.25 18.75 20.25Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M2.25 3.75H5.25L7.5 16.5H19.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M7.5 12.5H19.1925C19.2792 12.5001 19.3633 12.4701 19.4304 12.4151C19.4975 12.3601 19.5434 12.2836 19.5605 12.1986L20.9105 5.44859C20.9214 5.39417 20.92 5.338 20.9066 5.28414C20.8931 5.23029 20.8679 5.18009 20.8327 5.13717C20.7975 5.09426 20.7532 5.05969 20.703 5.03597C20.6528 5.01225 20.598 4.99996 20.5425 5H6" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <div class="add-to-cart-text">Thêm vào giỏ hàng</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-name">Quilted Satin Jacket</div>
                        <div class="product-price">
                            <div class="sale-price">$750</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="recommendations-section">
            <div class="section-header">
                <div class="section-title-with-indicator">
                    <div class="indicator-bar"></div>
                    <div class="section-title">Dành cho bạn</div>
                </div>
                <div class="view-all">Xem tất cả</div>
            </div>
            <div class="product-grid">
                <!-- Recommended Product 1 -->
                <div class="product-card">
                    <div class="product-image-container">
                        <div class="product-image-wrapper">
                            <div class="discount-badge">-35%</div>
                            <div class="action-icon">
                                <svg id="I165:5567;165:5535" layer-name="Fill Eye" width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg" class="view-icon">
                                    <circle cx="17" cy="17" r="17" fill="white"></circle>
                                    <path d="M26.257 15.962C26.731 16.582 26.731 17.419 26.257 18.038C24.764 19.987 21.182 24 17 24C12.818 24 9.23601 19.987 7.74301 18.038C7.51239 17.7411 7.38721 17.3759 7.38721 17C7.38721 16.6241 7.51239 16.2589 7.74301 15.962C9.23601 14.013 12.818 10 17 10C21.182 10 24.764 14.013 26.257 15.962V15.962Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M17 20C18.6569 20 20 18.6569 20 17C20 15.3431 18.6569 14 17 14C15.3431 14 14 15.3431 14 17C14 18.6569 15.3431 20 17 20Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <div class="product-image">
                                <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/7cc2d9ab6d6f8dcf8f4d69264eca4c8110a4dadc?placeholderIfAbsent=true" alt="ASUS FHD Gaming Laptop" class="product-img">
                            </div>
                            <button class="add-to-cart-bar" type="button">
                                <div class="add-to-cart-content small">
                                    <svg id="I165:5567;165:5561" layer-name="Cart1" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="cart-icon">
                                        <path d="M8.25 20.25C8.66421 20.25 9 19.9142 9 19.5C9 19.0858 8.66421 18.75 8.25 18.75C7.83579 18.75 7.5 19.0858 7.5 19.5C7.5 19.9142 7.83579 20.25 8.25 20.25Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M18.75 20.25C19.1642 20.25 19.5 19.9142 19.5 19.5C19.5 19.0858 19.1642 18.75 18.75 18.75C18.3358 18.75 18 19.0858 18 19.5C18 19.9142 18.3358 20.25 18.75 20.25Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M2.25 3.75H5.25L7.5 16.5H19.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M7.5 12.5H19.1925C19.2792 12.5001 19.3633 12.4701 19.4304 12.4151C19.4975 12.3601 19.5434 12.2836 19.5605 12.1986L20.9105 5.44859C20.9214 5.39417 20.92 5.338 20.9066 5.28414C20.8931 5.23029 20.8679 5.18009 20.8327 5.13717C20.7975 5.09426 20.7532 5.05969 20.703 5.03597C20.6528 5.01225 20.598 4.99996 20.5425 5H6" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <div class="add-to-cart-text-small">Thêm vào giỏ hàng</div>
                                </div>
                            </button>
                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-name">ASUS FHD Gaming Laptop</div>
                        <div class="product-price">
                            <div class="sale-price">$960</div>
                            <div class="original-price">$1160</div>
                        </div>
                        <div class="product-rating">
                            <svg id="I165:5567;165:5545" layer-name="Five star" width="100" height="20" viewBox="0 0 100 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="rating-stars">
                                <path d="M16.673 10.1717C17.7437 9.36184 17.1709 7.65517 15.8284 7.65517H13.3992C12.7853 7.65517 12.243 7.25521 12.0617 6.66868L11.3375 4.32637C10.9309 3.01106 9.0691 3.01106 8.66246 4.32637L7.93832 6.66868C7.75699 7.25521 7.21469 7.65517 6.60078 7.65517H4.12961C2.79142 7.65517 2.21592 9.35274 3.27822 10.1665L5.39469 11.7879C5.85885 12.1435 6.05314 12.7501 5.88196 13.3092L5.11296 15.8207C4.71416 17.1232 6.22167 18.1704 7.30301 17.342L9.14861 15.9281C9.65097 15.5432 10.349 15.5432 10.8514 15.9281L12.6807 17.3295C13.7636 18.159 15.2725 17.1079 14.8696 15.8046L14.09 13.2827C13.9159 12.7198 14.113 12.1081 14.5829 11.7526L16.673 10.1717Z" fill="#FFAD33"></path>
                                <path d="M36.673 10.1717C37.7437 9.36184 37.1709 7.65517 35.8284 7.65517H33.3992C32.7853 7.65517 32.243 7.25521 32.0617 6.66868L31.3375 4.32637C30.9309 3.01106 29.0691 3.01106 28.6625 4.32637L27.9383 6.66868C27.757 7.25521 27.2147 7.65517 26.6008 7.65517H24.1296C22.7914 7.65517 22.2159 9.35274 23.2782 10.1665L25.3947 11.7879C25.8588 12.1435 26.0531 12.7501 25.882 13.3092L25.113 15.8207C24.7142 17.1232 26.2217 18.1704 27.303 17.342L29.1486 15.9281C29.651 15.5432 30.349 15.5432 30.8514 15.9281L32.6807 17.3295C33.7636 18.159 35.2725 17.1079 34.8696 15.8046L34.09 13.2827C33.9159 12.7198 34.113 12.1081 34.5829 11.7526L36.673 10.1717Z" fill="#FFAD33"></path>
                                <path d="M56.673 10.1717C57.7437 9.36184 57.1709 7.65517 55.8284 7.65517H53.3992C52.7853 7.65517 52.243 7.25521 52.0617 6.66868L51.3375 4.32637C50.9309 3.01106 49.0691 3.01106 48.6625 4.32637L47.9383 6.66868C47.757 7.25521 47.2147 7.65517 46.6008 7.65517H44.1296C42.7914 7.65517 42.2159 9.35274 43.2782 10.1665L45.3947 11.7879C45.8588 12.1435 46.0531 12.7501 45.882 13.3092L45.113 15.8207C44.7142 17.1232 46.2217 18.1704 47.303 17.342L49.1486 15.9281C49.651 15.5432 50.349 15.5432 50.8514 15.9281L52.6807 17.3295C53.7636 18.159 55.2725 17.1079 54.8696 15.8046L54.09 13.2827C53.9159 12.7198 54.113 12.1081 54.5829 11.7526L56.673 10.1717Z" fill="#FFAD33"></path>
                                <path d="M76.673 10.1717C77.7437 9.36184 77.1709 7.65517 75.8284 7.65517H73.3992C72.7853 7.65517 72.243 7.25521 72.0617 6.66868L71.3375 4.32637C70.9309 3.01106 69.0691 3.01106 68.6625 4.32637L67.9383 6.66868C67.757 7.25521 67.2147 7.65517 66.6008 7.65517H64.1296C62.7914 7.65517 62.2159 9.35274 63.2782 10.1665L65.3947 11.7879C65.8588 12.1435 66.0531 12.7501 65.882 13.3092L65.113 15.8207C64.7142 17.1232 66.2217 18.1704 67.303 17.342L69.1486 15.9281C69.651 15.5432 70.349 15.5432 70.8514 15.9281L72.6807 17.3295C73.7636 18.159 75.2725 17.1079 74.8696 15.8046L74.09 13.2827C73.9159 12.7198 74.113 12.1081 74.5829 11.7526L76.673 10.1717Z" fill="#FFAD33"></path>
                                <path d="M96.673 10.1717C97.7437 9.36184 97.1709 7.65517 95.8284 7.65517H93.3992C92.7853 7.65517 92.243 7.25521 92.0617 6.66868L91.3375 4.32637C90.9309 3.01106 89.0691 3.01106 88.6625 4.32637L87.9383 6.66868C87.757 7.25521 87.2147 7.65517 86.6008 7.65517H84.1296C82.7914 7.65517 82.2159 9.35274 83.2782 10.1665L85.3947 11.7879C85.8588 12.1435 86.0531 12.7501 85.882 13.3092L85.113 15.8207C84.7142 17.1232 86.2217 18.1704 87.303 17.342L89.1486 15.9281C89.651 15.5432 90.349 15.5432 90.8514 15.9281L92.6807 17.3295C93.7636 18.159 95.2725 17.1079 94.8696 15.8046L94.09 13.2827C93.9159 12.7198 94.113 12.1081 94.5829 11.7526L96.673 10.1717Z" fill="#FFAD33"></path>
                            </svg>
                            <div class="rating-count">(65)</div>
                        </div>
                    </div>
                </div>

                <!-- Recommended Product 2 -->
                <div class="product-card">
                    <div class="product-image-container">
                        <div class="product-image-wrapper">
                            <div class="action-icon">
                                <svg id="I165:6877;165:6749" layer-name="Fill Eye" width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg" class="view-icon">
                                    <circle cx="17" cy="17" r="17" fill="white"></circle>
                                    <path d="M26.2565 15.962C26.7305 16.582 26.7305 17.419 26.2565 18.038C24.7635 19.987 21.1815 24 16.9995 24C12.8175 24 9.23552 19.987 7.74252 18.038C7.51191 17.7411 7.38672 17.3759 7.38672 17C7.38672 16.6241 7.51191 16.2589 7.74252 15.962C9.23552 14.013 12.8175 10 16.9995 10C21.1815 10 24.7635 14.013 26.2565 15.962V15.962Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M17 20C18.6569 20 20 18.6569 20 17C20 15.3431 18.6569 14 17 14C15.3431 14 14 15.3431 14 17C14 18.6569 15.3431 20 17 20Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <div class="product-image">
                                <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/bf84cddb31b0f222a69d9e6da9b3c4c87a2110df?placeholderIfAbsent=true" alt="IPS LCD Gaming Monitor" class="product-img">
                            </div>
                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-name">IPS LCD Gaming Monitor</div>
                        <div class="product-price">
                            <div class="sale-price">$1160</div>
                        </div>
                        <div class="product-rating">
                            <svg id="I165:6877;165:6760" layer-name="Five star" width="100" height="20" viewBox="0 0 100 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="rating-stars">
                                <path d="M16.673 10.1717C17.7437 9.36184 17.1709 7.65517 15.8284 7.65517H13.3992C12.7853 7.65517 12.243 7.25521 12.0617 6.66868L11.3375 4.32637C10.9309 3.01106 9.0691 3.01106 8.66246 4.32637L7.93832 6.66868C7.75699 7.25521 7.21469 7.65517 6.60078 7.65517H4.12961C2.79142 7.65517 2.21592 9.35274 3.27822 10.1665L5.39469 11.7879C5.85885 12.1435 6.05314 12.7501 5.88196 13.3092L5.11296 15.8207C4.71416 17.1232 6.22167 18.1704 7.30301 17.342L9.14861 15.9281C9.65097 15.5432 10.349 15.5432 10.8514 15.9281L12.6807 17.3295C13.7636 18.159 15.2725 17.1079 14.8696 15.8046L14.09 13.2827C13.9159 12.7198 14.113 12.1081 14.5829 11.7526L16.673 10.1717Z" fill="#FFAD33"></path>
                                <path d="M36.673 10.1717C37.7437 9.36184 37.1709 7.65517 35.8284 7.65517H33.3992C32.7853 7.65517 32.243 7.25521 32.0617 6.66868L31.3375 4.32637C30.9309 3.01106 29.0691 3.01106 28.6625 4.32637L27.9383 6.66868C27.757 7.25521 27.2147 7.65517 26.6008 7.65517H24.1296C22.7914 7.65517 22.2159 9.35274 23.2782 10.1665L25.3947 11.7879C25.8588 12.1435 26.0531 12.7501 25.882 13.3092L25.113 15.8207C24.7142 17.1232 26.2217 18.1704 27.303 17.342L29.1486 15.9281C29.651 15.5432 30.349 15.5432 30.8514 15.9281L32.6807 17.3295C33.7636 18.159 35.2725 17.1079 34.8696 15.8046L34.09 13.2827C33.9159 12.7198 34.113 12.1081 34.5829 11.7526L36.673 10.1717Z" fill="#FFAD33"></path>
                                <path d="M56.673 10.1717C57.7437 9.36184 57.1709 7.65517 55.8284 7.65517H53.3992C52.7853 7.65517 52.243 7.25521 52.0617 6.66868L51.3375 4.32637C50.9309 3.01106 49.0691 3.01106 48.6625 4.32637L47.9383 6.66868C47.757 7.25521 47.2147 7.65517 46.6008 7.65517H44.1296C42.7914 7.65517 42.2159 9.35274 43.2782 10.1665L45.3947 11.7879C45.8588 12.1435 46.0531 12.7501 45.882 13.3092L45.113 15.8207C44.7142 17.1232 46.2217 18.1704 47.303 17.342L49.1486 15.9281C49.651 15.5432 50.349 15.5432 50.8514 15.9281L52.6807 17.3295C53.7636 18.159 55.2725 17.1079 54.8696 15.8046L54.09 13.2827C53.9159 12.7198 54.113 12.1081 54.5829 11.7526L56.673 10.1717Z" fill="#FFAD33"></path>
                                <path d="M76.673 10.1717C77.7437 9.36184 77.1709 7.65517 75.8284 7.65517H73.3992C72.7853 7.65517 72.243 7.25521 72.0617 6.66868L71.3375 4.32637C70.9309 3.01106 69.0691 3.01106 68.6625 4.32637L67.9383 6.66868C67.757 7.25521 67.2147 7.65517 66.6008 7.65517H64.1296C62.7914 7.65517 62.2159 9.35274 63.2782 10.1665L65.3947 11.7879C65.8588 12.1435 66.0531 12.7501 65.882 13.3092L65.113 15.8207C64.7142 17.1232 66.2217 18.1704 67.303 17.342L69.1486 15.9281C69.651 15.5432 70.349 15.5432 70.8514 15.9281L72.6807 17.3295C73.7636 18.159 75.2725 17.1079 74.8696 15.8046L74.09 13.2827C73.9159 12.7198 74.113 12.1081 74.5829 11.7526L76.673 10.1717Z" fill="#FFAD33"></path>
                                <path d="M96.673 10.1717C97.7437 9.36184 97.1709 7.65517 95.8284 7.65517H93.3992C92.7853 7.65517 92.243 7.25521 92.0617 6.66868L91.3375 4.32637C90.9309 3.01106 89.0691 3.01106 88.6625 4.32637L87.9383 6.66868C87.757 7.25521 87.2147 7.65517 86.6008 7.65517H84.1296C82.7914 7.65517 82.2159 9.35274 83.2782 10.1665L85.3947 11.7879C85.8588 12.1435 86.0531 12.7501 85.882 13.3092L85.113 15.8207C84.7142 17.1232 86.2217 18.1704 87.303 17.342L89.1486 15.9281C89.651 15.5432 90.349 15.5432 90.8514 15.9281L92.6807 17.3295C93.7636 18.159 95.2725 17.1079 94.8696 15.8046L94.09 13.2827C93.9159 12.7198 94.113 12.1081 94.5829 11.7526L96.673 10.1717Z" fill="#FFAD33"></path>
                            </svg>
                            <div class="rating-count">(65)</div>
                        </div>
                    </div>
                </div>

                <!-- Recommended Product 3 -->
                <div class="product-card">
                    <div class="product-image-container">
                        <div class="product-image-wrapper">
                            <div class="new-badge">Mới</div>
                            <div class="action-icon">
                                <svg id="I165:6875;165:6089" layer-name="Fill Eye" width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg" class="view-icon">
                                    <circle cx="17" cy="17" r="17" fill="white"></circle>
                                    <path d="M26.2565 15.962C26.7305 16.582 26.7305 17.419 26.2565 18.038C24.7635 19.987 21.1815 24 16.9995 24C12.8175 24 9.23552 19.987 7.74252 18.038C7.51191 17.7411 7.38672 17.3759 7.38672 17C7.38672 16.6241 7.51191 16.2589 7.74252 15.962C9.23552 14.013 12.8175 10 16.9995 10C21.1815 10 24.7635 14.013 26.2565 15.962V15.962Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M17 20C18.6569 20 20 18.6569 20 17C20 15.3431 18.6569 14 17 14C15.3431 14 14 15.3431 14 17C14 18.6569 15.3431 20 17 20Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <div class="product-image">
                                <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/c294479147fb98a8501bd8b224b5735f97c19f58?placeholderIfAbsent=true" alt="HAVIT HV-G92 Gamepad" class="product-img">
                            </div>
                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-name">HAVIT HV-G92 Gamepad</div>
                        <div class="product-price">
                            <div class="sale-price">$560</div>
                        </div>
                        <div class="product-rating">
                            <svg id="I165:6875;165:6102" layer-name="Five star" width="100" height="20" viewBox="0 0 100 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="rating-stars">
                                <path d="M16.673 10.1717C17.7437 9.36184 17.1709 7.65517 15.8284 7.65517H13.3992C12.7853 7.65517 12.243 7.25521 12.0617 6.66868L11.3375 4.32637C10.9309 3.01106 9.0691 3.01106 8.66246 4.32637L7.93832 6.66868C7.75699 7.25521 7.21469 7.65517 6.60078 7.65517H4.12961C2.79142 7.65517 2.21592 9.35274 3.27822 10.1665L5.39469 11.7879C5.85885 12.1435 6.05314 12.7501 5.88196 13.3092L5.11296 15.8207C4.71416 17.1232 6.22167 18.1704 7.30301 17.342L9.14861 15.9281C9.65097 15.5432 10.349 15.5432 10.8514 15.9281L12.6807 17.3295C13.7636 18.159 15.2725 17.1079 14.8696 15.8046L14.09 13.2827C13.9159 12.7198 14.113 12.1081 14.5829 11.7526L16.673 10.1717Z" fill="#FFAD33"></path>
                                <path d="M36.673 10.1717C37.7437 9.36184 37.1709 7.65517 35.8284 7.65517H33.3992C32.7853 7.65517 32.243 7.25521 32.0617 6.66868L31.3375 4.32637C30.9309 3.01106 29.0691 3.01106 28.6625 4.32637L27.9383 6.66868C27.757 7.25521 27.2147 7.65517 26.6008 7.65517H24.1296C22.7914 7.65517 22.2159 9.35274 23.2782 10.1665L25.3947 11.7879C25.8588 12.1435 26.0531 12.7501 25.882 13.3092L25.113 15.8207C24.7142 17.1232 26.2217 18.1704 27.303 17.342L29.1486 15.9281C29.651 15.5432 30.349 15.5432 30.8514 15.9281L32.6807 17.3295C33.7636 18.159 35.2725 17.1079 34.8696 15.8046L34.09 13.2827C33.9159 12.7198 34.113 12.1081 34.5829 11.7526L36.673 10.1717Z" fill="#FFAD33"></path>
                                <path d="M56.673 10.1717C57.7437 9.36184 57.1709 7.65517 55.8284 7.65517H53.3992C52.7853 7.65517 52.243 7.25521 52.0617 6.66868L51.3375 4.32637C50.9309 3.01106 49.0691 3.01106 48.6625 4.32637L47.9383 6.66868C47.757 7.25521 47.2147 7.65517 46.6008 7.65517H44.1296C42.7914 7.65517 42.2159 9.35274 43.2782 10.1665L45.3947 11.7879C45.8588 12.1435 46.0531 12.7501 45.882 13.3092L45.113 15.8207C44.7142 17.1232 46.2217 18.1704 47.303 17.342L49.1486 15.9281C49.651 15.5432 50.349 15.5432 50.8514 15.9281L52.6807 17.3295C53.7636 18.159 55.2725 17.1079 54.8696 15.8046L54.09 13.2827C53.9159 12.7198 54.113 12.1081 54.5829 11.7526L56.673 10.1717Z" fill="#FFAD33"></path>
                                <path d="M76.673 10.1717C77.7437 9.36184 77.1709 7.65517 75.8284 7.65517H73.3992C72.7853 7.65517 72.243 7.25521 72.0617 6.66868L71.3375 4.32637C70.9309 3.01106 69.0691 3.01106 68.6625 4.32637L67.9383 6.66868C67.757 7.25521 67.2147 7.65517 66.6008 7.65517H64.1296C62.7914 7.65517 62.2159 9.35274 63.2782 10.1665L65.3947 11.7879C65.8588 12.1435 66.0531 12.7501 65.882 13.3092L65.113 15.8207C64.7142 17.1232 66.2217 18.1704 67.303 17.342L69.1486 15.9281C69.651 15.5432 70.349 15.5432 70.8514 15.9281L72.6807 17.3295C73.7636 18.159 75.2725 17.1079 74.8696 15.8046L74.09 13.2827C73.9159 12.7198 74.113 12.1081 74.5829 11.7526L76.673 10.1717Z" fill="#FFAD33"></path>
                                <path d="M96.673 10.1717C97.7437 9.36184 97.1709 7.65517 95.8284 7.65517H93.3992C92.7853 7.65517 92.243 7.25521 92.0617 6.66868L91.3375 4.32637C90.9309 3.01106 89.0691 3.01106 88.6625 4.32637L87.9383 6.66868C87.757 7.25521 87.2147 7.65517 86.6008 7.65517H84.1296C82.7914 7.65517 82.2159 9.35274 83.2782 10.1665L85.3947 11.7879C85.8588 12.1435 86.0531 12.7501 85.882 13.3092L85.113 15.8207C84.7142 17.1232 86.2217 18.1704 87.303 17.342L89.1486 15.9281C89.651 15.5432 30.349 15.5432 90.8514 15.9281L92.6807 17.3295C93.7636 18.159 95.2725 17.1079 94.8696 15.8046L94.09 13.2827C93.9159 12.7198 94.113 12.1081 94.5829 11.7526L96.673 10.1717Z" fill="#FFAD33"></path>
                            </svg>
                            <div class="rating-count">(65)</div>
                        </div>
                    </div>
                </div>

                <!-- Recommended Product 4 -->
                <div class="product-card">
                    <div class="product-image-container">
                        <div class="product-image-wrapper">
                            <div class="action-icon">
                                <svg id="I165:6876;165:6606" layer-name="Fill Eye" width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg" class="view-icon">
                                    <circle cx="17" cy="17" r="17" fill="white"></circle>
                                    <path d="M26.2565 15.962C26.7305 16.582 26.7305 17.419 26.2565 18.038C24.7635 19.987 21.1815 24 16.9995 24C12.8175 24 9.23552 19.987 7.74252 18.038C7.51191 17.7411 7.38672 17.3759 7.38672 17C7.38672 16.6241 7.51191 16.2589 7.74252 15.962C9.23552 14.013 12.8175 10 16.9995 10C21.1815 10 24.7635 14.013 26.2565 15.962V15.962Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M17 20C18.6569 20 20 18.6569 20 17C20 15.3431 18.6569 14 17 14C15.3431 14 14 15.3431 14 17C14 18.6569 15.3431 20 17 20Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <div class="product-image">
                                <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/bc4214fc345cbcc392ba2adc1b5ec88b6e6df170?placeholderIfAbsent=true" alt="AK-900 Wired Keyboard" class="product-img">
                            </div>
                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-name">AK-900 Wired Keyboard</div>
                        <div class="product-price">
                            <div class="sale-price">$200</div>
                        </div>
                        <div class="product-rating">
                            <svg id="I165:6876;165:6618" layer-name="Five star" width="100" height="20" viewBox="0 0 100 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="rating-stars">
                                <path d="M16.673 10.1717C17.7437 9.36184 17.1709 7.65517 15.8284 7.65517H13.3992C12.7853 7.65517 12.243 7.25521 12.0617 6.66868L11.3375 4.32637C10.9309 3.01106 9.0691 3.01106 8.66246 4.32637L7.93832 6.66868C7.75699 7.25521 7.21469 7.65517 6.60078 7.65517H4.12961C2.79142 7.65517 2.21592 9.35274 3.27822 10.1665L5.39469 11.7879C5.85885 12.1435 6.05314 12.7501 5.88196 13.3092L5.11296 15.8207C4.71416 17.1232 6.22167 18.1704 7.30301 17.342L9.14861 15.9281C9.65097 15.5432 10.349 15.5432 10.8514 15.9281L12.6807 17.3295C13.7636 18.159 15.2725 17.1079 14.8696 15.8046L14.09 13.2827C13.9159 12.7198 14.113 12.1081 14.5829 11.7526L16.673 10.1717Z" fill="#FFAD33"></path>
                                <path d="M36.673 10.1717C37.7437 9.36184 37.1709 7.65517 35.8284 7.65517H33.3992C32.7853 7.65517 32.243 7.25521 32.0617 6.66868L31.3375 4.32637C30.9309 3.01106 29.0691 3.01106 28.6625 4.32637L27.9383 6.66868C27.757 7.25521 27.2147 7.65517 26.6008 7.65517H24.1296C22.7914 7.65517 22.2159 9.35274 23.2782 10.1665L25.3947 11.7879C25.8588 12.1435 26.0531 12.7501 25.882 13.3092L25.113 15.8207C24.7142 17.1232 26.2217 18.1704 27.303 17.342L29.1486 15.9281C29.651 15.5432 30.349 15.5432 30.8514 15.9281L32.6807 17.3295C33.7636 18.159 35.2725 17.1079 34.8696 15.8046L34.09 13.2827C33.9159 12.7198 34.113 12.1081 34.5829 11.7526L36.673 10.1717Z" fill="#FFAD33"></path>
                                <path d="M56.673 10.1717C57.7437 9.36184 57.1709 7.65517 55.8284 7.65517H53.3992C52.7853 7.65517 52.243 7.25521 52.0617 6.66868L51.3375 4.32637C50.9309 3.01106 49.0691 3.01106 48.6625 4.32637L47.9383 6.66868C47.757 7.25521 47.2147 7.65517 46.6008 7.65517H44.1296C42.7914 7.65517 42.2159 9.35274 43.2782 10.1665L45.3947 11.7879C45.8588 12.1435 46.0531 12.7501 45.882 13.3092L45.113 15.8207C44.7142 17.1232 46.2217 18.1704 47.303 17.342L49.1486 15.9281C49.651 15.5432 50.349 15.5432 50.8514 15.9281L52.6807 17.3295C53.7636 18.159 55.2725 17.1079 54.8696 15.8046L54.09 13.2827C53.9159 12.7198 54.113 12.1081 54.5829 11.7526L56.673 10.1717Z" fill="#FFAD33"></path>
                                <path d="M76.673 10.1717C77.7437 9.36184 77.1709 7.65517 75.8284 7.65517H73.3992C72.7853 7.65517 72.243 7.25521 72.0617 6.66868L71.3375 4.32637C70.9309 3.01106 69.0691 3.01106 68.6625 4.32637L67.9383 6.66868C67.757 7.25521 67.2147 7.65517 66.6008 7.65517H64.1296C62.7914 7.65517 62.2159 9.35274 63.2782 10.1665L65.3947 11.7879C65.8588 12.1435 66.0531 12.7501 65.882 13.3092L65.113 15.8207C64.7142 17.1232 66.2217 18.1704 67.303 17.342L69.1486 15.9281C69.651 15.5432 70.349 15.5432 70.8514 15.9281L72.6807 17.3295C73.7636 18.159 75.2725 17.1079 74.8696 15.8046L74.09 13.2827C73.9159 12.7198 74.113 12.1081 74.5829 11.7526L76.673 10.1717Z" fill="#FFAD33"></path>
                                <path d="M96.673 10.1717C97.7437 9.36184 97.1709 7.65517 95.8284 7.65517H93.3992C92.7853 7.65517 92.243 7.25521 92.0617 6.66868L91.3375 4.32637C90.9309 3.01106 89.0691 3.01106 88.6625 4.32637L87.9383 6.66868C87.757 7.25521 87.2147 7.65517 86.6008 7.65517H84.1296C82.7914 7.65517 82.2159 9.35274 83.2782 10.1665L85.3947 11.7879C85.8588 12.1435 86.0531 12.7501 85.882 13.3092L85.113 15.8207C84.7142 17.1232 86.2217 18.1704 87.303 17.342L89.1486 15.9281C89.651 15.5432 90.349 15.5432 90.8514 15.9281L92.6807 17.3295C93.7636 18.159 95.2725 17.1079 94.8696 15.8046L94.09 13.2827C93.9159 12.7198 94.113 12.1081 94.5829 11.7526L96.673 10.1717Z" fill="#FFAD33"></path>
                            </svg>
                            <div class="rating-count">(65)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
/
@endsection
