@extends('layouts.app')

@section('title', 'Chi tiết sản phẩm')

@section('content')
<!-- Link CSS riêng -->
<link rel="stylesheet" href="{{ asset('css/product_detail.css') }}">

<div class="container py-5">
    <div class="row g-4 align-items-start">
        <!-- Hình ảnh sản phẩm -->
        <div class="col-md-6">
            <div class="border rounded p-3 bg-white shadow-sm h-100">
                <div class="row g-2">
                    <!-- Ảnh phụ bên trái -->
                    <div class="col-3 thumbnail-column">
                        <img src="https://gcs.tripi.vn/public-tripi/tripi-feed/img/474069QVt/hinh-anh-ban-phim-razer_033845591.jpg" class="img-thumbnail sub-image">
                        <img src="https://gongangshop.vn/wp-content/uploads/2024/05/Ban-phim-RGB-1024x576.png" class="img-thumbnail sub-image">
                        <img src="https://vn-test-11.slatic.net/p/6d2039790678530b6d5a9feb6925c9bb.jpg" class="img-thumbnail sub-image">
                        <img src="https://gongangshop.vn/wp-content/uploads/2024/05/Ban-phim-RGB-1024x576.png" class="img-thumbnail sub-image">
                    </div>

                    <!-- Ảnh chính bên phải -->
                    <div class="col-9 img-container">
                        <img id="mainProductImage"
                             src="https://img.tripi.vn/cdn-cgi/image/width=700,height=700/https://gcs.tripi.vn/public-tripi/tripi-feed/img/474069zWk/anh-ban-phim-co-dep_033839277.jpg"
                             class="img-fluid rounded w-100"
                             alt="Sản phẩm chính"
                             style="max-height: 480px; object-fit: cover; cursor: pointer;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-md-6">
            <div class="border rounded p-4 bg-white shadow-sm h-100">
                <h2 class="fw-bold mb-3">Havic HV G-92 Gamepad</h2>
                <div class="mb-2 text-muted small">
                    <span class="text-warning">★★★★☆</span> (150 đánh giá) | Đã bán: 3k
                </div>
                <div class="mb-3">
                    <span class="text-decoration-line-through text-muted">₫250.000</span>
                    <span class="text-danger fs-3 fw-semibold ms-2">₫199.000</span>
                </div>

                <p class="text-muted">
                    Tay cầm chơi game cho PlayStation 5, chất liệu cao cấp, thiết kế công thái học, kết nối không dây, hỗ trợ rung và cảm ứng lực.
                </p>

                <div class="mb-3">
                    <span class="badge bg-danger">-10%</span>
                    <span class="badge bg-warning text-dark">Giảm ₫15k</span>
                    <span class="badge bg-success">Flash Sale</span>
                </div>

                <div class="mb-3">
                    <label class="form-label">Màu sắc:</label>
                    <div class="d-flex gap-2">
                        <div class="color-circle" style="background-color: white;"></div>
                        <div class="color-circle" style="background-color: black;"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kích cỡ:</label>
                    <div class="btn-group" role="group">
                        <button class="btn btn-outline-secondary">XS</button>
                        <button class="btn btn-outline-secondary">S</button>
                        <button class="btn btn-outline-secondary">M</button>
                        <button class="btn btn-outline-secondary">L</button>
                        <button class="btn btn-outline-secondary">XL</button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Số lượng:</label>
                    <div class="input-group" style="max-width: 160px;">
                        <button class="btn btn-outline-secondary" type="button" id="decreaseQty">-</button>
                        <input type="number" class="form-control text-center" id="quantity" value="1" min="1">
                        <button class="btn btn-outline-secondary" type="button" id="increaseQty">+</button>
                    </div>
                    <small class="text-muted d-block mt-1">10 sản phẩm còn lại</small>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button class="btn btn-danger btn-lg">🛒 Thêm Vào Giỏ Hàng</button>
                    <button class="btn btn-outline-secondary btn-lg">❤️ Yêu thích</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mô tả chi tiết -->
    <div class="mt-5 p-4 bg-white shadow-sm rounded">
        <h3>Mô tả chi tiết</h3>
        <p>
            Gamepad Havic HV G-92 mang đến trải nghiệm chơi game mượt mà với thiết kế công thái học, kết nối không dây ổn định, cảm ứng lực và độ rung tùy biến. 
            Phù hợp với nhiều nền tảng như PC, PS5 và các thiết bị Android qua OTG.
        </p>
        <img src="https://gongangshop.vn/wp-content/uploads/2024/05/Ban-phim-RGB-1024x576.png" class="img-fluid my-3" style="height: 500px">
        <p>Sản phẩm được minh họa trong môi trường thực tế, hiển thị ánh sáng RGB khi sử dụng.</p>
        <img src="https://gongangshop.vn/wp-content/uploads/2024/05/Ban-phim-RGB-1024x576.png" class="img-fluid my-3" style="height: 500px">
    </div>

    <!-- Đánh giá -->
    <div class="mt-5 p-4 bg-white shadow-sm rounded">
        <h3>Đánh giá người dùng</h3>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Nguyễn Văn A</h5>
                <h6 class="card-subtitle mb-2 text-warning">★★★★★</h6>
                <p class="card-text">Sản phẩm rất tốt, chất lượng vượt mong đợi!</p>
                <small class="text-muted">27/05/2025 14:30</small>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Trần Thị B</h5>
                <h6 class="card-subtitle mb-2 text-warning">★★★★☆</h6>
                <p class="card-text">Giao hàng nhanh, sản phẩm như mô tả.</p>
                <small class="text-muted">26/05/2025 10:15</small>
            </div>
        </div>
    </div>
</div>

<!-- Overlay hiển thị ảnh full -->
{{-- <div class="fullscreen-overlay" id="fullscreenOverlay">
    <img src="" alt="Full image" id="fullscreenImage">
</div> --}}

<script src="{{ asset('js/product_detail.js') }}"></script>
@endsection
