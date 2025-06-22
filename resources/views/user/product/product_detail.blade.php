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
                        @foreach ($product->images as $image)
                            <img src="{{ asset($image->image_path) }}" alt="Ảnh phụ"
                                class="w-full rounded cursor-pointer sub-image">
                        @endforeach
                    </div>
                    <!-- Ảnh chính -->
                    <div class="w-3/4">
                        <img src="{{ $product->images->first()->image_path ?? 'default.jpg' }}" alt="{{ $product->name }}"
                            class="w-full rounded" , style="height: 500px">
                    </div>
                </div>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="rounded-lg">
                <h2 class="text-2xl font-bold text-gray-800 mb-3">{{ $product->name }}</h2>
                <div class="flex items-center mb-2 text-sm text-gray-500">
                    <span class="text-yellow-400">
                        @for ($i = 1; $i <= 5; $i++)
                            {{ $i <= round($product->reviews->avg('rating')) ? '★' : '☆' }}
                        @endfor
                    </span>
                    <span class="ml-2">
                        ({{ $product->reviews->count() }} đánh giá) | Đã bán: {{ $product->sold_quantity }}
                    </span>
                </div>
                @if ($product->isFlashSaleActive())
                    <div class="flash-sale-box">
                        <div class="flash-sale-header">
                            <strong>⚡FLASH SALE</strong>
                            <span class="countdown-label">KẾT THÚC TRONG</span>
                            <span class="countdown-timer" id="flash-sale-countdown"
                                data-end-time="{{ $product->flash_sale_end_at }}">
                                <span id="hours">00</span> :
                                <span id="minutes">00</span> :
                                <span id="seconds">00</span>
                            </span>
                        </div>

                        <div class="flash-sale-price">
                            <span class="new-price">₫{{ number_format($product->flash_sale_price, 0) }}</span>
                            <span class="old-price">₫{{ number_format($product->price, 0) }}</span>
                            <span class="discount">
                                -{{ round((1 - $product->flash_sale_price / $product->price) * 100) }}%
                            </span>
                        </div>
                    </div>
                @endif

                <div class="mb-3 flex items-center">
                    @if ($product->hasDiscount())
                        <span class="text-gray-500 line-through">{{ number_format($product->price, 0, ',', '.') }}</span>
                        <span
                            class="text-red-600 text-2xl font-semibold ml-3">{{ number_format($product->sale_price, 0, ',', '.') }}</span>
                    @else
                        <span
                            class="text-red-600 text-2xl font-semibold">{{ number_format($product->price, 0, ',', '.') }}</span>
                    @endif
                </div>

                <div class="mb-3 flex gap-2">
                    <span class="bg-yellow-400 text-gray-800 text-xs font-semibold px-2 py-1 rounded">Giảm:
                        {{ $product->discount_percentage }}%</span>
                    <span class="bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">Flash Sale</span>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Màu sắc:</label>
                    <div class="flex gap-3">
                        @foreach ($product->variants->unique('color') as $variant)
                            <div class="flex items-center gap-1">
                                <div class="w-5 h-5 rounded-full border" style="background-color: '{{ $variant->color }}'">
                                </div>
                                <span class="text-sm">{{ $variant->color }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Kích cỡ:</label>
                    <div class="flex gap-2">
                        @foreach ($product->variants->unique('size') as $variant)
                            <span class="px-3 py-1 border rounded text-sm">{{ $variant->size }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Số lượng</label>
                    <div class="flex items-center gap-2" style="max-width: 160px;">
                        <button class="border border-gray-300 px-3 py-1 rounded hover:bg-gray-100"
                            id="decreaseQty">-</button>
                        <input type="number"
                            class="border border-gray-300 text-center w-16 p-1 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            id="quantity" value="1" min="1">
                        <button class="border border-gray-300 px-3 py-1 rounded hover:bg-gray-100"
                            id="increaseQty">+</button>
                    </div>
                    <small class="text-gray-500 block mt-1">Sản phẩm còn lại: {{ $product->stock_total }}</small>
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
                    <button id="reportProductBtn"
                        class="border border-gray-300 text-gray-700 px-6 py-3 rounded hover:bg-gray-100 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 3v1.5h15A2.25 2.25 0 0 1 20.25 6v1.5m-8.25 0V9m0 0v-1.5M9.75 8.25H21A2.25 2.25 0 0 1 23.25 10.5v7.5A2.25 2.25 0 0 1 21 20.25H3.75a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-2.25M9 12.75h6" />
                        </svg>
                        Báo cáo
                    </button>
                </div>
            </div>
        </div>

        <!-- Mô tả chi tiết -->
        <div class="mt-6 bg-white rounded-lg">
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Mô tả chi tiết</h3>
            <div class="text-gray-600 mb-4">
                {!! $product->description !!}
            </div>
        </div>

        <!-- Đánh giá -->
        <div class="mt-6 bg-white rounded-lg">
            <h3 class="text-xl text-gray-800 font-semibold mb-4">Đánh giá người dùng</h3>
            @forelse($product->reviews as $review)
                <div class="review py-4 border-b">
                    <strong>{{ $review->user->fullname ?? 'Ẩn danh' }}</strong>
                    <div>
                        <span class="text-yellow-400">
                            @for ($i = 1; $i <= 5; $i++)
                                {{ $i <= $review->rating ? '★' : '☆' }}
                            @endfor
                        </span>
                        <span class="ml-2 text-gray-600">({{ $review->rating }} sao)</span>
                    </div>
                    <p class="text-gray-700">{{ $review->comment ?? 'Không có bình luận' }}</p>
                    <small class="text-gray-500">{{ $review->created_at->diffForHumans() }}</small>
                </div>
            @empty
                <p class="text-gray-600">Chưa có đánh giá nào.</p>
            @endforelse
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let countdownElem = document.getElementById('flash-sale-countdown');
            if (!countdownElem) return;

            let endTime = new Date(countdownElem.getAttribute('data-end-time')).getTime();

            let countdown = setInterval(function () {
                let now = new Date().getTime();
                let distance = endTime - now;

                let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("hours").innerText = String(hours).padStart(2, '0');
                document.getElementById("minutes").innerText = String(minutes).padStart(2, '0');
                document.getElementById("seconds").innerText = String(seconds).padStart(2, '0');

                if (distance < 0) {
                    clearInterval(countdown);
                    countdownElem.innerHTML = "Đã kết thúc";
                }
            }, 1000);
        });
    </script>


    <!-- JavaScript để thay đổi ảnh chính -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const subImages = document.querySelectorAll('.sub-image');
            const mainImage = document.getElementById('mainProductImage');

            subImages.forEach(image => {
                image.addEventListener('click', function () {
                    mainImage.src = this.src;
                });
            });

            // Tăng/giảm số lượng
            const decreaseBtn = document.getElementById('decreaseQty');
            const increaseBtn = document.getElementById('increaseQty');
            const quantityInput = document.getElementById('quantity');

            decreaseBtn.addEventListener('click', function () {
                let value = parseInt(quantityInput.value);
                if (value > 1) {
                    quantityInput.value = value - 1;
                }
            });

            increaseBtn.addEventListener('click', function () {
                let value = parseInt(quantityInput.value);
                quantityInput.value = value + 1;
            });
        });
    </script>

    <!-- Report Product Modal -->
    <div id="reportProductModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-semibold mb-4">Báo cáo sản phẩm</h3>
            <form action="{{ route('product.report', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="shop_id" value="{{ $product->shopID }}">

                <div class="mb-4">
                    <label for="report_type" class="block text-sm font-medium text-gray-700">Loại vi phạm</label>
                    <select name="report_type" id="report_type"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="fake_product">Sản phẩm giả nhái</option>
                        <option value="product_violation">Vi phạm chính sách sản phẩm</option>
                        <option value="copyright">Vi phạm bản quyền</option>
                        <option value="other">Khác</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="report_content" class="block text-sm font-medium text-gray-700">Nội dung báo cáo</label>
                    <textarea name="report_content" id="report_content" rows="4"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        placeholder="Mô tả chi tiết vi phạm"></textarea>
                </div>

                <div class="mb-4">
                    <label for="evidence" class="block text-sm font-medium text-gray-700">Bằng chứng (Hình
                        ảnh/Video)</label>
                    <input type="file" name="evidence[]" id="evidence" multiple
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div class="mb-4 flex items-center">
                    <input type="checkbox" name="is_anonymous" id="is_anonymous"
                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    <label for="is_anonymous" class="ml-2 block text-sm text-gray-900">Báo cáo ẩn danh</label>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" id="cancelReportBtn"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Hủy</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Gửi báo
                        cáo</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reportButton = document.getElementById('reportProductBtn');
            const reportModal = document.getElementById('reportProductModal');
            const cancelReportBtn = document.getElementById('cancelReportBtn');

            // Open modal
            reportButton.addEventListener('click', function () {
                reportModal.classList.remove('hidden');
            });

            // Close modal
            cancelReportBtn.addEventListener('click', function () {
                reportModal.classList.add('hidden');
            });

            // Close modal when clicking outside
            reportModal.addEventListener('click', function (event) {
                if (event.target === reportModal) {
                    reportModal.classList.add('hidden');
                }
            });

            // Tăng/giảm số lượng (existing code)
            const decreaseBtn = document.getElementById('decreaseQty');
            const increaseBtn = document.getElementById('increaseQty');
            const quantityInput = document.getElementById('quantity');

            decreaseBtn.addEventListener('click', function () {
                let value = parseInt(quantityInput.value);
                if (value > 1) {
                    quantityInput.value = value - 1;
                }
            });

            increaseBtn.addEventListener('click', function () {
                let value = parseInt(quantityInput.value);
                quantityInput.value = value + 1;
            });
        });
    </script>
@endsection
