@extends('layouts.app')

@section('title', 'Chính sách')
@section('content')
    <div class="header">
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: Arial, sans-serif;
                background-color: #f5f5f5;
            }

            .header {
                background: linear-gradient(135deg, #ee4d2d 0%, #ff6b35 100%);
                padding: 40px 20px;
                text-align: center;
                color: white;
            }

            .header h1 {
                font-size: 28px;
                margin-bottom: 30px;
                font-weight: 500;
            }

            .search-container {
                max-width: 600px;
                margin: 0 auto;
                position: relative;
            }

            .search-box {
                width: 100%;
                padding: 15px 20px;
                border: none;
                border-radius: 6px;
                font-size: 16px;
                outline: none;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .search-box::placeholder {
                color: #999;
            }

            .search-btn {
                position: absolute;
                right: 8px;
                top: 50%;
                transform: translateY(-50%);
                background: #ee4d2d;
                border: none;
                padding: 8px 12px;
                border-radius: 4px;
                cursor: pointer;
                color: white;
                font-size: 16px;
            }

            .search-btn:hover {
                background: #d63031;
            }

            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 20px;
                display: flex;
                gap: 30px;
            }

            .sidebar {
                flex: 0 0 300px;
                background: white;
                border-radius: 8px;
                padding: 0;
                height: fit-content;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .main-content {
                flex: 1;
                background: white;
                border-radius: 8px;
                padding: 30px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .category-menu {
                list-style: none;
            }

            .category-item {
                border-bottom: 1px solid #f0f0f0;
            }

            .category-item:last-child {
                border-bottom: none;
            }

            .category-header {
                display: flex;
                align-items: center;
                justify-content: between;
                padding: 15px 20px;
                cursor: pointer;
                transition: all 0.3s ease;
                font-size: 14px;
                font-weight: 500;
                color: #333;
            }

            .category-header:hover {
                background-color: #f8f9fa;
                color: #ee4d2d;
            }

            .category-header.active {
                background-color: #fff5f3;
                color: #ee4d2d;
            }

            .dropdown-arrow {
                margin-left: auto;
                transition: transform 0.3s ease;
                font-size: 12px;
                color: #666;
            }

            .dropdown-arrow.open {
                transform: rotate(180deg);
            }

            .category-submenu {
                display: none;
                background-color: #fafafa;
                padding: 0;
            }

            .category-submenu.show {
                display: block;
            }

            .submenu-item {
                padding: 12px 40px;
                font-size: 13px;
                color: #666;
                cursor: pointer;
                transition: all 0.3s ease;
                border-bottom: 1px solid #f0f0f0;
            }

            .submenu-item:last-child {
                border-bottom: none;
            }

            .submenu-item:hover {
                background-color: #f0f0f0;
                color: #ee4d2d;
            }

            .content-title {
                font-size: 20px;
                font-weight: 600;
                margin-bottom: 20px;
                color: #333;
            }

            .faq-list {
                list-style: none;
            }

            .faq-item {
                padding: 15px 0;
                border-bottom: 1px solid #f0f0f0;
                cursor: pointer;
                transition: color 0.3s ease;
            }

            .faq-item:hover {
                color: #linear-gradient(135deg, #fa709a 0%, #fee140 100%);;
            }

            .faq-item:last-child {
                border-bottom: none;
            }

            .faq-text {
                font-size: 14px;
                line-height: 1.5;
                color: #666;
            }

            .faq-item:hover .faq-text {
                color: linear-gradient(135deg, #fa709a 0%, #fee140 100%);;
            }

            .help-section {
                background: white;
                padding: 30px 0;
                margin-top: 30px;
                text-align: center;
            }

            .help-question {
                font-size: 16px;
                color: #333;
                margin-bottom: 20px;
            }

            @media (max-width: 768px) {
                .container {
                    flex-direction: column;
                    padding: 10px;
                }

                .sidebar {
                    flex: none;
                }
            }
        </style>
        <h1>Xin chào, có thể giúp gì cho bạn?</h1>
        <div class="search-container">
            <input type="text" class="search-box" placeholder="Nhập từ khóa hoặc nội dung cần tìm">
            <button class="search-btn">🔍</button>
        </div>
    </div>
    <div class="container d-flex">
        {{-- SIDEBAR DANH MỤC --}}
        <div class="sidebar" style="width: 280px;">
            <ul class="category-menu">
                @foreach($category->parent ? $category->parent->children : \App\Models\HelpCategory::whereNull('parent_id')->where('status', 'active')->get() as $cat)
                    <li class="category-item">
                        <div class="category-header {{ $cat->id == $category->id ? 'active' : '' }}"
                            data-category="{{ $cat->slug }}">
                            {{ $cat->title }}
                            <span class="dropdown-arrow {{ $cat->id == $category->id ? 'open' : '' }}">▼</span>
                        </div>
                        @if($cat->children->count())
                            <ul class="category-submenu {{ $cat->id == $category->id ? 'show' : '' }}">
                                @foreach($cat->children as $child)
                                    <li class="submenu-item">
                                        <a href="{{ route('help.category.ajax', $child->slug) }}">{{ $child->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- MAIN CONTENT BÀI VIẾT --}}
        <div class="main-content" style="flex: 1; padding: 0 20px;">
            <h2 class="content-title">{{ $category->title }}</h2>

            @if($category->articles->count())
                <ul class="faq-list">
                    @foreach($category->articles as $article)
                        <li class="faq-item">
                            <div class="faq-text">
                                <a href="{{ route('help.detail', $article->slug) }}">
                                    {{ $article->title }}
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Không có bài viết nào trong danh mục này.</p>
            @endif
        </div>
    </div>
    <div class="container">
        <div class="sidebar">
            <ul class="category-menu">
                <li class="category-item">
                    <div class="category-header" data-category="shopping">
                        Mua Sắm Cùng Shopee
                        <span class="dropdown-arrow">▼</span>
                    </div>
                    <ul class="category-submenu">
                        <li class="submenu-item">Hướng dẫn mua hàng</li>
                        <li class="submenu-item">Tìm kiếm sản phẩm</li>
                        <li class="submenu-item">Đặt hàng</li>
                        <li class="submenu-item">Giỏ hàng</li>
                    </ul>
                </li>

                <li class="category-item">
                    <div class="category-header" data-category="promotion">
                        Khuyến Mãi & Ưu Đãi
                        <span class="dropdown-arrow">▼</span>
                    </div>
                    <ul class="category-submenu">
                        <li class="submenu-item">Mã giảm giá</li>
                        <li class="submenu-item">Khuyến mãi flash sale</li>
                        <li class="submenu-item">Ưu đãi thành viên</li>
                    </ul>
                </li>

                <li class="category-item">
                    <div class="category-header" data-category="payment">
                        Thanh Toán
                        <span class="dropdown-arrow">▼</span>
                    </div>
                    <ul class="category-submenu">
                        <li class="submenu-item">Phương thức thanh toán</li>
                        <li class="submenu-item">ShopeePay</li>
                        <li class="submenu-item">Thẻ tín dụng</li>
                        <li class="submenu-item">Chuyển khoản</li>
                    </ul>
                </li>

                <li class="category-item">
                    <div class="category-header" data-category="shipping">
                        Đơn Hàng & Vận Chuyển
                        <span class="dropdown-arrow">▼</span>
                    </div>
                    <ul class="category-submenu">
                        <li class="submenu-item">Theo dõi đơn hàng</li>
                        <li class="submenu-item">Thời gian giao hàng</li>
                        <li class="submenu-item">Phí vận chuyển</li>
                    </ul>
                </li>

                <li class="category-item">
                    <div class="category-header" data-category="refund">
                        Trả Hàng & Hoàn Tiền
                        <span class="dropdown-arrow">▼</span>
                    </div>
                    <ul class="category-submenu">
                        <li class="submenu-item">Chính sách đổi trả</li>
                        <li class="submenu-item">Hoàn tiền</li>
                        <li class="submenu-item">Khiếu nại</li>
                    </ul>
                </li>

                <li class="category-item">
                    <div class="category-header active" data-category="general">
                        Thông Tin Chung
                        <span class="dropdown-arrow open">▼</span>
                    </div>
                    <ul class="category-submenu show">
                        <li class="submenu-item">Chính sách Shopee</li>
                        <li class="submenu-item">Tài khoản Shopee</li>
                        <li class="submenu-item">Mua sắm an toàn</li>
                        <li class="submenu-item">Thư viện thông tin</li>
                        <li class="submenu-item">Ứng dụng Shopee</li>
                        <li class="submenu-item">Khác</li>
                        <li class="submenu-item">Hướng dẫn chung</li>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="main-content">

            <ul class="faq-list">
                <li class="faq-item">
                    <div class="faq-text">[Đánh giá sản phẩm] Tôi có thể xóa/chỉnh sửa đánh giá sản phẩm của mình trên
                        Shopee không?</div>
                </li>

                <li class="faq-item">
                    <div class="faq-text">[Đánh giá sản phẩm] Tại Sao Đánh Giá Sản Phẩm Của Tôi Bị Xóa / Không Hiển Thị?
                    </div>
                </li>

                <li class="faq-item">
                    <div class="faq-text">[Đánh giá sản phẩm] Làm thế nào để có 1 bài viết đánh giá chất lượng?</div>
                </li>

                <li class="faq-item">
                    <div class="faq-text">[Thành viên mới] Tôi nhận được bao nhiều Shopee Xu cho mỗi lần đánh giá sản phẩm
                        thành công?</div>
                </li>

                <li class="faq-item">
                    <div class="faq-text">[Thành viên mới] Hướng dẫn đánh giá sản phẩm</div>
                </li>

                <li class="faq-item">
                    <div class="faq-text">[Đánh giá sản phẩm] Các câu hỏi thường gặp</div>
                </li>
            </ul>
        </div>
    </div>

    <div class="help-section">
        <div class="help-question">Bạn có muốn tìm thêm thông tin gì không?</div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.submenu-item a').forEach(link => {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();
                        const url = this.href;

                        fetch(url)
                            .then(res => res.json())
                            .then(data => {
                                document.querySelector('.main-content').innerHTML = data.html;
                            })
                            .catch(err => {
                                console.error('Lỗi khi load bài viết:', err);
                            });
                    });
                });
            });
        // Xử lý dropdown menu
        document.querySelectorAll('.category-header').forEach(header => {
            header.addEventListener('click', function () {
                const submenu = this.nextElementSibling;
                const arrow = this.querySelector('.dropdown-arrow');

                // Đóng tất cả menu khác
                document.querySelectorAll('.category-submenu').forEach(menu => {
                    if (menu !== submenu) {
                        menu.classList.remove('show');
                    }
                });

                document.querySelectorAll('.dropdown-arrow').forEach(arr => {
                    if (arr !== arrow) {
                        arr.classList.remove('open');
                    }
                });

                document.querySelectorAll('.category-header').forEach(h => {
                    if (h !== this) {
                        h.classList.remove('active');
                    }
                });

                // Toggle menu hiện tại
                submenu.classList.toggle('show');
                arrow.classList.toggle('open');
                this.classList.toggle('active');
            });
        });

        // Xử lý click submenu
        document.querySelectorAll('.submenu-item').forEach(item => {
            item.addEventListener('click', function () {

            });
        });

        // Xử lý tìm kiếm
        document.querySelector('.search-btn').addEventListener('click', function () {
            const searchTerm = document.querySelector('.search-box').value;
            if (searchTerm.trim()) {
                alert('Tìm kiếm: ' + searchTerm);
            }
        });

        document.querySelector('.search-box').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value;
                if (searchTerm.trim()) {
                    alert('Tìm kiếm: ' + searchTerm);
                }
            }
        });

        // Xử lý click FAQ
        document.querySelectorAll('.faq-item').forEach(item => {
            item.addEventListener('click', function () {
                const faqText = this.querySelector('.faq-text').textContent;
                alert('Xem chi tiết: ' + faqText);
            });
        });
    </script>
@endsection
