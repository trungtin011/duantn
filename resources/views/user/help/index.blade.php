@extends('layouts.app')

@section('title', 'Chính sách')
@section('content')
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
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            ;
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
        }

        .search-btn:hover {
            background: #d63031;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .categories {
            background: white;
            padding: 30px 0;
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .category-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .category-item:hover {
            border-color: #ee4d2d;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .category-icon {
            width: 24px;
            height: 24px;
            margin-right: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .category-icon.shopping {
            color: #ee4d2d;
        }

        .category-icon.promotion {
            color: #ff6b35;
        }

        .category-icon.payment {
            color: #f39c12;
        }

        .category-icon.shipping {
            color: #27ae60;
        }

        .category-icon.refund {
            color: #e74c3c;
        }

        .category-icon.notification {
            color: #3498db;
        }

        .category-text {
            font-size: 14px;
            color: #333;
            font-weight: 500;
        }

        .faq-section {
            background: white;
            padding: 30px 0;
            margin-top: 30px;
        }

        .faq-item {
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .faq-item:hover {
            color: #ee4d2d;
        }

        .faq-item:last-child {
            border-bottom: none;
        }

        .faq-text {
            font-size: 14px;
            color: #666;
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

        .contact-btn {
            display: inline-flex;
            align-items: center;
            padding: 12px 24px;
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            color: #666;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .contact-btn:hover {
            border-color: #ee4d2d;
            color: #ee4d2d;
        }

        .contact-icon {
            margin-right: 8px;
            width: 16px;
            height: 16px;
        }
    </style>
    <div class="header">
        <h1>Xin chào, Có thể giúp gì cho bạn? </h1>
        <div class="search-container">
            <input type="text" class="search-box" placeholder="Nhập từ khóa hoặc nội dung cần tìm">
            <button class="search-btn"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </button>
        </div>
    </div>

    <div class="container">
        <div class="categories">
            <h2 class="section-title">Danh mục</h2>
            <div class="category-grid">
                @foreach ($categories as $cat)
                    <a href="{{ route('help.category', $cat->slug) }}" class="category-item">
                        <div class="category-icon">
                            @if (Str::startsWith($cat->icon, 'help-category-icons/'))
                                {{-- icon là ảnh --}}
                                <img src="{{ Storage::url($cat->icon) }}" alt="icon" style="width: 32px; height: 32px;">
                            @else
                                {{-- icon là emoji hoặc FontAwesome class --}}
                                <span>{!! $cat->icon !!}</span>
                            @endif
                        </div>
                        <div class="category-text">{{ $cat->title }}</div>
                    </a>
                @endforeach
            </div>


            <div class="faq-section">
                <h2 class="section-title">Câu hỏi thường gặp</h2>
                <div class="faq-item">
                    <div class="faq-text">[Cảnh báo lừa đão] Mua sắm an toàn cùng Shopee</div>
                </div>
                <div class="faq-item">
                    <div class="faq-text">[Dịch vụ] Cách liên hệ Chăm sóc khách hàng, Hotline, Tổng đài Shopee</div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.submenu-item a').forEach(link => {
                    link.addEventListener('click', function(e) {
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
            // Thêm hiệu ứng tìm kiếm
            document.querySelector('.search-btn').addEventListener('click', function() {
                const searchTerm = document.querySelector('.search-box').value;
                if (searchTerm.trim()) {
                    alert('Tìm kiếm: ' + searchTerm);
                }
            });

            // Thêm hiệu ứng enter cho ô tìm kiếm
            document.querySelector('.search-box').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const searchTerm = this.value;
                    if (searchTerm.trim()) {
                        alert('Tìm kiếm: ' + searchTerm);
                    }
                }
            });

            // Thêm hiệu ứng click cho các danh mục
            document.querySelectorAll('.category-item').forEach(item => {
                item.addEventListener('click', function() {
                    const categoryName = this.querySelector('.category-text').textContent;
                    alert('Bạn đã chọn: ' + categoryName);
                });
            });

            // Thêm hiệu ứng click cho FAQ
            document.querySelectorAll('.faq-item').forEach(item => {
                item.addEventListener('click', function() {
                    const faqText = this.querySelector('.faq-text').textContent;
                    alert('Xem thêm: ' + faqText);
                });
            });

            // Thêm hiệu ứng cho nút liên hệ
            document.querySelector('.contact-btn').addEventListener('click', function(e) {
                e.preventDefault();
                alert('Liên hệ với Shopee Support');
            });
        </script>
    @endsection
