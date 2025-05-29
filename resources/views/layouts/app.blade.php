<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&amp;display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <style>
        .main-header {
            background: #fff;
            border-bottom: 1px solid #eee;
            padding: 0.5rem 0;
        }

        .main-header .navbar-nav .nav-link {
            color: #222;
            font-weight: 500;
            margin-right: 1rem;
        }

        .main-header .navbar-nav .nav-link.active {
            color: #ff7a00;
        }

        .main-header .search-box {
            border-radius: 20px;
            border: 1px solid #eee;
            padding: 0.25rem 1rem;
            width: 220px;
        }

        .main-header .icon-btn {
            background: none;
            border: none;
            margin-left: 10px;
            color: #222;
            font-size: 1.2rem;
        }

        .main-header .icon-btn:hover {
            color: #ff7a00;
        }

        .main-header .user-icon {
            border-radius: 50%;
            background: #f5f5f5;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .footer {
            background: #111;
            color: #fff;
            padding: 40px 0 0 0;
        }

        .footer .footer-title {
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .footer .footer-link,
        .footer .footer-link:visited {
            color: #bbb;
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
        }

        .footer .footer-link:hover {
            color: #ff7a00;
        }

        .footer .footer-app img {
            width: 120px;
            margin-bottom: 10px;
        }

        .footer .footer-social a {
            color: #fff;
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .footer .footer-social a:hover {
            color: #ff7a00;
        }

        .footer-bottom {
            border-top: 1px solid #222;
            margin-top: 30px;
            padding: 15px 0;
            text-align: center;
            color: #bbb;
            font-size: 0.95rem;
        }
    </style>
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>

<body>
    <!-- Header -->
    <header class="main-header">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand fw-bold fs-4" href="/">Exclusive</a>
            <nav>
                <ul class="navbar-nav flex-row align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="/">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Liên hệ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Về chúng tôi</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('signup') }}">Đăng ký</a></li>
                </ul>
            </nav>
            <form class="d-flex align-items-center" style="gap:10px;">
                <input class="search-box" type="text" placeholder="Bạn muốn tìm kiếm gì ?">
                <button class="icon-btn" type="button"><i class="fa fa-search"></i></button>
                <button class="icon-btn" type="button"><i class="fa fa-heart"></i></button>
                <button class="icon-btn" type="button"><i class="fa fa-shopping-cart"></i></button>
                <span class="user-icon"><i class="fa fa-user"></i></span>
                <select class="form-select form-select-sm ms-2" style="width:90px;">
                    <option>English</option>
                    <option>Tiếng Việt</option>
                </select>
            </form>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-2 mb-4">
                    <div class="footer-title">Exclusive</div>
                    <div>Đăng ký</div>
                    <div class="mb-2" style="font-size:0.95rem; color:#bbb;">Giảm giá 10% cho đơn hàng đầu tiên</div>
                    <form class="d-flex">
                        <input type="email" class="form-control form-control-sm" placeholder="Nhập email của bạn">
                        <button class="btn btn-dark btn-sm ms-2"><i class="fa fa-arrow-right"></i></button>
                    </form>
                </div>
                <div class="col-md-2 mb-4">
                    <div class="footer-title">Hỗ trợ</div>
                    <div style="font-size:0.95rem; color:#bbb;">403 Quang Trung, Buôn Ma Thuột, Đaklak</div>
                    <div style="font-size:0.95rem; color:#bbb;">exclusive@gmail.com</div>
                    <div style="font-size:0.95rem; color:#bbb;">0915571415</div>
                </div>
                <div class="col-md-2 mb-4">
                    <div class="footer-title">Tài khoản</div>
                    <a href="#" class="footer-link">Tài khoản của tôi</a>
                    <a href="#" class="footer-link">Đăng nhập/Đăng ký</a>
                    <a href="#" class="footer-link">Giỏ hàng</a>
                    <a href="#" class="footer-link">Danh sách ước</a>
                    <a href="#" class="footer-link">Cửa hàng</a>
                </div>
                <div class="col-md-2 mb-4">
                    <div class="footer-title">Liên kết nhanh</div>
                    <a href="#" class="footer-link">Chính sách bảo mật</a>
                    <a href="#" class="footer-link">Điều khoản sử dụng</a>
                    <a href="#" class="footer-link">Câu hỏi thường gặp</a>
                    <a href="#" class="footer-link">Liên hệ</a>
                </div>
                <div class="col-md-2 mb-4">
                    <div class="footer-title">Tải App</div>
                    <div class="footer-app mb-2">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                            alt="Google Play">
                        <img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg"
                            alt="App Store">
                    </div>
                    <div style="font-size:0.85rem; color:#bbb;">Tiết kiệm 5.3 ứng dụng dành cho người dùng mới</div>
                </div>
                <div class="col-md-2 mb-4">
                    <div class="footer-title">Kết nối</div>
                    <div class="footer-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; Copyright Rimel 2022. All right reserved
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
