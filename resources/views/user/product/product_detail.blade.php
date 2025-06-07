@extends('layouts.app')

@section('title', 'Chi tiết sản phẩm')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <div class="container mx-auto py-5">
        <!-- breadcrumb -->
        <div class="flex flex-wrap items-center gap-2 mb-10 px-[10px] sm:px-0 md:mb-20 text-sm md:text-base">
            <a href="{{ route('home') }}" class="text-gray-500 hover:underline">Trang chủ</a>
            <span>/</span>
            <span>Chi tiết sản phẩm</span>
        </div>
        <!-- Hình ảnh và thông tin sản phẩm -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Hình ảnh sản phẩm -->
            <div class="rounded-lg pr-4">
                <div class="flex gap-4">
                    <!-- Ảnh phụ bên trái -->
                    <div class="flex flex-col gap-2 w-1/4">
                        <img src="https://gcs.tripi.vn/public-tripi/tripi-feed/img/474069QVt/hinh-anh-ban-phim-razer_033845591.jpg"
                            class="w-full rounded cursor-pointer sub-image" alt="Thumbnail 1">
                        <img src="https://gongangshop.vn/wp-content/uploads/2024/05/Ban-phim-RGB-1024x576.png"
                            class="w-full rounded cursor-pointer sub-image" alt="Thumbnail 2">
                        <img src="https://vn-test-11.slatic.net/p/6d2039790678530b6d5a9feb6925c9bb.jpg"
                            class="w-full rounded cursor-pointer sub-image" alt="Thumbnail 3">
                        <img src="https://gongangshop.vn/wp-content/uploads/2024/05/Ban-phim-RGB-1024x576.png"
                            class="w-full rounded cursor-pointer sub-image" alt="Thumbnail 4">
                    </div>
                    <!-- Ảnh chính -->
                    <div class="w-3/4">
                        <img src="{{ $product->images->first()->image_path ?? 'default.jpg' }}" alt="{{ $product->name }}" class="w-full rounded">
                    </div>
                </div>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="rounded-lg">
                <h2 class="text-2xl font-bold text-gray-800 mb-3">Havic HV G-92 Gamepad</h2>
                <div class="flex items-center mb-2 text-sm text-gray-500">
                    <span class="text-yellow-400">★★★★☆</span>
                    <span class="ml-2">(150 đánh giá) | Đã bán: 3k</span>
                </div>
                <div class="mb-3 flex items-center">
                    <span class="text-gray-500 line-through">₫250.000</span>
                    <span class="text-red-600 text-2xl font-semibold ml-3">₫199.000</span>
                </div>

                <p class="text-gray-600 mb-4">
                    Tay cầm chơi game cho PlayStation 5, chất liệu cao cấp, thiết kế công thái học, kết nối không dây, hỗ
                    trợ rung và cảm ứng lực.
                </p>

                <div class="mb-3 flex gap-2">
                    <span class="bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded">-10%</span>
                    <span class="bg-yellow-400 text-gray-800 text-xs font-semibold px-2 py-1 rounded">Giảm ₫15k</span>
                    <span class="bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">Flash Sale</span>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Màu sắc:</label>
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full border-2 border-gray-300 cursor-pointer"
                            style="background-color: white;"></div>
                        <div class="w-8 h-8 rounded-full border-2 border-gray-300 cursor-pointer"
                            style="background-color: black;"></div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Kích cỡ:</label>
                    <div class="flex gap-2">
                        <button class="border border-gray-300 text-gray-700 px-3 py-1 rounded hover:bg-gray-100">XS</button>
                        <button class="border border-gray-300 text-gray-700 px-3 py-1 rounded hover:bg-gray-100">S</button>
                        <button class="border border-gray-300 text-gray-700 px-3 py-1 rounded hover:bg-gray-100">M</button>
                        <button class="border border-gray-300 text-gray-700 px-3 py-1 rounded hover:bg-gray-100">L</button>
                        <button class="border border-gray-300 text-gray-700 px-3 py-1 rounded hover:bg-gray-100">XL</button>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Số lượng:</label>
                    <div class="flex items-center gap-2" style="max-width: 160px;">
                        <button class="border border-gray-300 px-3 py-1 rounded hover:bg-gray-100"
                            id="decreaseQty">-</button>
                        <input type="number"
                            class="border border-gray-300 text-center w-16 p-1 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            id="quantity" value="1" min="1">
                        <button class="border border-gray-300 px-3 py-1 rounded hover:bg-gray-100"
                            id="increaseQty">+</button>
                    </div>
                    <small class="text-gray-500 block mt-1">10 sản phẩm còn lại</small>
                </div>

                <div class="flex gap-3">
                    <button class="bg-red-600 text-white px-6 py-3 rounded hover:bg-red-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                        Thêm Vào Giỏ Hàng
                    </button>
                    <button
                        class="border border-gray-300 text-gray-700 px-6 py-3 rounded hover:bg-gray-100 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 1.883 1.195 3.292 2.741 4.085C7.287 13.128 9.225 13.5 12 13.5s4.713-.372 6.259-1.165C19.805 11.542 21 10.133 21 8.25Z" />
                        </svg>
                        Yêu thích
                    </button>
                </div>
            </div>
        </div>

        <!-- Mô tả chi tiết -->
        <div class="mt-6 bg-white rounded-lg">
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Mô tả chi tiết</h3>
            <p class="text-gray-600 mb-4">
                Gamepad Havic HV G-92 mang đến trải nghiệm chơi game mượt mà với thiết kế công thái học, kết nối không dây
                ổn định, cảm ứng lực và độ rung tùy biến.
                Phù hợp với nhiều nền tảng như PC, PS5 và các thiết bị Android qua OTG.
            </p>
            <img src="https://gongangshop.vn/wp-content/uploads/2024/05/Ban-phim-RGB-1024x576.png"
                class="w-full my-3 rounded" style="height: 500px;" alt="Mô tả hình ảnh 1">
            <p class="text-gray-600 mb-4">Sản phẩm được minh họa trong môi trường thực tế, hiển thị ánh sáng RGB khi sử
                dụng.</p>
            <img src="https://gongangshop.vn/wp-content/uploads/2024/05/Ban-phim-RGB-1024x576.png"
                class="w-full my-3 rounded" style="height: 500px;" alt="Mô tả hình ảnh 2">
        </div>

        <!-- Đánh giá -->
        <div class="mt-6 bg-white rounded-lg">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Đánh giá người dùng</h3>
            <div class="border border-gray-200 rounded-lg p-4 mb-3">
                <h5 class="text-gray-800 font-semibold">Nguyễn Văn A</h5>
                <div class="text-yellow-400 mb-2">★★★★★</div>
                <p class="text-gray-600">Sản phẩm rất tốt, chất lượng vượt mong đợi!</p>
                <small class="text-gray-500">27/05/2025 14:30</small>
            </div>
            <div class="border border-gray-200 rounded-lg p-4 mb-3">
                <h5 class="text-gray-800 font-semibold">Trần Thị B</h5>
                <div class="text-yellow-400 mb-2">★★★★☆</div>
                <p class="text-gray-600">Giao hàng nhanh, sản phẩm như mô tả.</p>
                <small class="text-gray-500">26/05/2025 10:15</small>
            </div>
        </div>
    </div>

    <!-- JavaScript để thay đổi ảnh chính -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const subImages = document.querySelectorAll('.sub-image');
            const mainImage = document.getElementById('mainProductImage');

            subImages.forEach(image => {
                image.addEventListener('click', function() {
                    mainImage.src = this.src;
                });
            });

            // Tăng/giảm số lượng
            const decreaseBtn = document.getElementById('decreaseQty');
            const increaseBtn = document.getElementById('increaseQty');
            const quantityInput = document.getElementById('quantity');

            decreaseBtn.addEventListener('click', function() {
                let value = parseInt(quantityInput.value);
                if (value > 1) {
                    quantityInput.value = value - 1;
                }
            });

            increaseBtn.addEventListener('click', function() {
                let value = parseInt(quantityInput.value);
                quantityInput.value = value + 1;
            });
        });
    </script>
@endsection
