@extends('layouts.app')

@section('title', 'Chi tiết sản phẩm')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 mb-6 text-sm">
            <a href="{{ route('home') }}" class="text-gray-500 hover:underline">Trang chủ</a>
            <span class="text-gray-500">/</span>
            <span class="text-gray-500">Chi tiết sản phẩm</span>
            <span class="ml-2 text-gray-500">/</span>
            <span>{{ $product->name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Cột chính (Hình ảnh + Thông tin sản phẩm) -->
            <div class="lg:col-span-3">
                <!-- Hình ảnh và thông tin sản phẩm -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white rounded-lg p-6 shadow">
                    <!-- Hình ảnh sản phẩm -->
                    <div class="relative">
                        <img id="main-image"
                            src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('storage/product_images/default.jpg') }}"
                            alt="{{ $product->name }}"
                            class="w-full h-[400px] object-cover rounded-lg transform transition-transform duration-300">
                        <div class="flex gap-2 mt-4 overflow-x-auto">
                            @foreach ($product->images as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Ảnh phụ {{ $product->name }}"
                                    class="w-[100px] rounded cursor-pointer hover:opacity-75 transition-opacity sub-image"
                                    onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}')">
                            @endforeach
                            @if ($product->images->isEmpty())
                                <img src="{{ asset('storage/product_images/default.jpg') }}" alt="Ảnh mặc định"
                                    class="w-[100px] rounded cursor-pointer sub-image">
                            @endif
                        </div>
                    </div>

                    <!-- Thông tin sản phẩm -->
                    <div class="flex flex-col gap-4">
                        <h2 class="text-2xl font-bold text-gray-800" title="{{ $product->name }}">
                            {{ \Illuminate\Support\Str::limit($product->name, 40, '...') }}
                        </h2>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-yellow-400 flex">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= round($product->reviews->avg('rating')))
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2.25l2.82 5.73h5.88l-4.77 4.12 1.82 5.73-5.73-3.49-5.73 3.49 1.82-5.73-4.77-4.12h5.88z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path
                                                    d="M12 2.25l2.82 5.73h5.88l-4.77 4.12 1.82 5.73-5.73-3.49-5.73 3.49 1.82-5.73-4.77-4.12h5.88z" />
                                            </svg>
                                        @endif
                                    @endfor
                                </span>
                                <span class="text-sm text-gray-500">
                                    ({{ $product->reviews->count() }} đánh giá) | Đã bán:
                                    {{ $product->sold_quantity >= 1000 ? $product->sold_quantity / 1000 . 'k' : $product->sold_quantity }}
                                </span>
                            </div>
                            <div class="relative">
                                <button id="menuButton" class="rounded hover:bg-gray-100">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div id="menuDropdown"
                                    class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-md shadow-lg hidden z-10">
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-red-600"
                                        id="favoriteBtn">
                                        Yêu thích
                                        <i class="fas fa-heart ml-1"></i>
                                    </a>
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-red-600"
                                        id="reportBtn">
                                        Báo cáo
                                        <i class="fas fa-exclamation-triangle ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-red-600 text-2xl font-bold">
                                {{ number_format($product->sale_price, 0, ',', '.') }} VNĐ
                            </span>
                            <span class="text-gray-500 line-through">
                                {{ number_format($product->price, 0, ',', '.') }} VNĐ
                            </span>
                            <span class="bg-red-100 text-red-600 px-2 rounded">
                                -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                            </span>
                        </div>
                        <p class="text-sm text-gray-600">{!! $product->meta_description !!}</p>

                        <!-- Màu sắc và kích thước -->
                        <div class="flex flex-col gap-4">
                            <div class="flex items-center gap-4">
                                <span class="text-gray-700">Màu sắc:</span>
                                <button
                                    class="border border-gray-300 rounded px-3 py-1 flex items-center gap-2 hover:bg-gray-100">
                                    <img src="https://4men.com.vn/images/thumbs/2021/11/ao-thun-tron-vai-so-go-mau-do-do-at045-16342-slide-products-618c8920b4646.jpg"
                                        width="20" class="rounded">
                                    <span>Đỏ đô</span>
                                </button>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-gray-700">Kích thước:</span>
                                <button class="border border-gray-300 rounded px-3 py-1 hover:bg-gray-100">
                                    <span>2XL</span>
                                </button>
                            </div>
                        </div>

                        <!-- Số lượng -->
                        <div class="flex items-center gap-4">
                            <span class="text-gray-700">Số lượng:</span>
                            <form action="" method="POST" class="flex items-center">
                                @csrf
                                <button type="button" id="decreaseQty"
                                    class="border border-gray-300 px-3 py-1 rounded-l hover:bg-gray-100">-</button>
                                <input type="text" name="quantity" id="quantity" value="1"
                                    class="w-16 text-center px-3 py-1 border-t border-b border-gray-300 focus:outline-none">
                                <button type="button" id="increaseQty"
                                    class="border border-gray-300 px-3 py-1 rounded-r hover:bg-gray-100">+</button>
                            </form>
                            <span class="text-sm text-gray-500">{{ $product->stock_total }} sản phẩm có sẵn</span>
                        </div>

                        <!-- Nút hành động -->
                        <div class="flex gap-3 mt-10">
                        <form action="{{ route('cart.add') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="variant_id" value="{{ $variant->id ?? '' }}">
                            <input type="hidden" name="quantity" value="1">

                            <button type="submit"
                                class="bg-red-100 text-red-600 px-6 py-3 rounded hover:bg-red-200 flex items-center gap-2">
                                <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ
                            </button>
                        </form>
                            <button class="bg-black text-white px-6 py-3 rounded hover:bg-gray-800">Mua ngay</button>
                        </div>
                    </div>
                </div>

                <!-- Mô tả sản phẩm -->
                <div class="bg-white rounded-lg p-6 mt-6 shadow">
                    <h3 class="text-xl font-semibold mb-4">Mô tả sản phẩm</h3>
                    <div class="">
                        <div class="text-gray-600 text-sm block description-content">
                            <span id="shortDescription" class="relative">
                                <div class="h-[200px] overflow-hidden">
                                    {!! $product->description !!}
                                </div>
                                <div class="w-[1074px]"
                                    style="position: absolute; bottom: 0px; left: 0px; height: 200px; background-image: linear-gradient(rgba(255, 255, 255, 0), rgb(255, 255, 255));">
                                </div>
                            </span>
                            <div id="fullDescription" class="hidden">{!! $product->description !!}</div>
                        </div>
                    </div>
                    <div class="flex justify-center mt-5">
                        <button id="readMore"
                            class="text-[#EF3248] px-4 py-2 rounded text-sm transition-all duration-300">
                            Xem thêm
                        </button>
                    </div>
                </div>

                <!-- Đánh giá -->
                <div class="bg-white rounded-lg p-6 mt-6 shadow">
                    <div class="flex justify-between">
                        <div class="flex flex-col w-[25%]">
                            <h3 class="text-xl font-semibold mb-4">Khách hàng đánh giá</h3>
                            <div class="flex items-center gap-4 mb-4">
                                <span class="text-2xl text-red-600">
                                    {{ number_format($product->reviews->avg('rating'), 1) }}/5
                                </span>
                                <div class="flex gap-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i
                                            class="{{ $i <= round($product->reviews->avg('rating')) ? 'fas' : 'far' }} fa-star text-yellow-400"></i>
                                    @endfor
                                </div>
                            </div>
                            <div class="flex flex-col">
                                @php
                                    $ratingSummary = $product->reviews->groupBy('rating')->map->count();
                                    $totalReviews = $product->reviews->count();
                                    $maxCount = 5;
                                @endphp

                                <div class="mb-6">
                                    @for ($i = 5; $i >= 1; $i--)
                                        @php
                                            $count = $ratingSummary->get($i, 0);
                                            $percent = min(($count / $maxCount) * 100, 100);
                                        @endphp
                                        <div class="flex items-center gap-3 mb-1 cursor-pointer">
                                            <a href="{{ request()->fullUrlWithQuery(['filter' => 'star-' . $i]) }}"
                                                class="flex items-center gap-2 group w-full">
                                                <div class="w-fit flex gap-1">
                                                    @for ($j = 1; $j <= 5; $j++)
                                                        <i
                                                            class="{{ $j <= $i ? 'fas' : 'far' }} fa-star text-yellow-400 text-xs"></i>
                                                    @endfor
                                                </div>
                                                <div class="flex bg-gray-200 h-2 rounded w-[152px]">
                                                    <div class="bg-[#0A68FF] h-2 rounded"
                                                        style="width: {{ $percent }}%"></div>
                                                </div>
                                                <div
                                                    class="w-fit text-right text-sm text-gray-600 ml-2 group-hover:text-blue-600">
                                                    {{ $count }}
                                                </div>
                                            </a>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-center gap-2 w-[75%]" id="review-filters">
                            <button data-filter=""
                                class="review-filter-btn text-sm px-3 py-1 rounded border text-gray-700">
                                Mới nhất
                            </button>
                            <button data-filter="images"
                                class="review-filter-btn text-sm px-3 py-1 rounded border text-gray-700">
                                Có hình ảnh
                            </button>
                            @for ($i = 5; $i >= 1; $i--)
                                <button data-filter="star-{{ $i }}"
                                    class="review-filter-btn text-sm px-3 py-1 rounded border text-gray-700">
                                    {{ $i }} sao
                                </button>
                            @endfor
                        </div>
                    </div>

                    <!-- Form đánh giá nếu người dùng đã mua hàng -->
                    @if (auth()->check() && $hasPurchased)
                        <form action="{{ route('product.review', $product->id) }}" method="POST"
                            enctype="multipart/form-data" class="mb-6">
                            @csrf
                            <label class="block mb-2 text-sm">Đánh giá sao:</label>
                            <div class="flex gap-1 mb-4">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg data-value="{{ $i }}"
                                        class="star w-6 h-6 cursor-pointer text-gray-300 hover:text-yellow-400"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 0 0 .95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 0 0-.364 1.118l1.286 3.966c.3.921-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 0 0-1.176 0l-3.385 2.46c-.784.57-1.838-.197-1.54-1.118l1.286-3.966a1 1 0 0 0-.364-1.118L2.045 9.393c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 0 0 .95-.69l1.286-3.966z" />
                                    </svg>
                                @endfor
                                <input type="hidden" name="rating" id="ratingInput" required>
                            </div>
                            <textarea name="comment" rows="3" class="w-full border p-2 rounded mb-4 text-sm"
                                placeholder="Viết nhận xét..."></textarea>
                            <input type="file" name="images[]" multiple accept="image/*"
                                class="mb-4 block w-full text-sm">
                            <input type="file" name="video" accept="video/*" class="mb-4 block w-full text-sm">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm">Gửi đánh
                                giá</button>
                        </form>
                    @endif

                    <!-- Hiển thị danh sách đánh giá bằng partial -->
                    <div id="review-list">
                        @include('partials.review_list', ['reviews' => $filteredReviews])
                    </div>
                </div>
            </div>

            <!-- Cột bên phải (Thông tin shop) -->
            <div class="lg:col-span-1">
                <div class="sticky top-5">
                    <div class="bg-white rounded-lg p-6 shadow mb-6">
                        <h2 class="text-xl mb-4 italic border-b pb-2 border-dashed">Cửa hàng</h2>
                        <div class="flex items-center gap-4 mb-4">
                            <img src="{{ $product->shop ? ($product->shop->shop_logo ? Storage::url($product->shop->shop_logo) : Storage::url('shop_logos/default_shop_logo.png')) : Storage::url('shop_logos/default_shop_logo.png') }}"
                                alt="Logo Shop" class="w-16 h-16 rounded-full object-cover">
                            <div>
                                <h3 class="text-lg font-semibold">
                                    {{ $product->shop ? $product->shop->shop_name : 'Tên Shop Không Xác Định' }}</h3>
                                <p class="text-sm text-gray-600">
                                    @if ($product->shop)
                                        @php
                                            $lastActivity = DB::table('sessions')
                                                ->where('user_id', $product->shop->ownerID)
                                                ->max('last_activity');
                                            $lastOnline = $lastActivity
                                                ? \Carbon\Carbon::createFromTimestamp($lastActivity)
                                                    ->locale('vi')
                                                    ->diffForHumans()
                                                : 'Không xác định';
                                        @endphp
                                        {{ $lastActivity ? "Online $lastOnline" : 'Hoạt động từ: ' . \Carbon\Carbon::parse($product->shop->created_at)->locale('vi')->diffForHumans() }}
                                    @else
                                        Chưa có thông tin
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex justify-center gap-2">
                            <button
                                class="bg-red-100 text-red-600 px-4 py-2 rounded hover:bg-red-200 flex items-center gap-2">
                                <i class="fa-solid fa-comment"></i> Nhắn tin
                            </button>
                            <a href="#" class="border border-gray-300 px-4 py-2 rounded hover:bg-gray-100">Xem cửa
                                hàng</a>
                        </div>
                    </div>
                    {{-- Voucher shop --}}
                    @if ($shop && $shop->coupons->where('status', 'active')->count() > 0)
                        <div class="bg-white rounded-lg p-6 shadow">
                            <h2 class="text-xl mb-4 italic border-b pb-2 border-dashed">Voucher Cửa hàng</h2>

                            <div class="flex flex-col gap-4 mb-4">
                                @foreach ($shop->coupons->where('status', 'active') as $coupon)
                                    <div
                                        class="flex justify-between items-center border border-dashed px-4 py-3 rounded text-sm">
                                        <div>
                                            <div class="text-red-600 font-semibold">
                                                Giảm
                                                {{ $coupon->discount_type === 'percentage' ? $coupon->discount_value . '%' : number_format($coupon->discount_value) . 'đ' }}
                                            </div>
                                            @if ($coupon->min_order_amount)
                                                <div>Đơn tối thiểu {{ number_format($coupon->min_order_amount) }}đ</div>
                                            @endif
                                            <div class="text-gray-500 italic">HSD:
                                                {{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}</div>
                                        </div>
                                        <button class="bg-black text-white px-4 py-1.5 rounded hover:bg-gray-800">
                                            Lưu
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            <div class="flex justify-end gap-2">
                                <button class="bg-black text-white rounded hover:bg-gray-800 flex items-center gap-2">
                                    <div class="border-r border-white border-dashed px-4 h-full flex items-center">
                                        <i class="fa-solid fa-ticket"></i>
                                    </div>
                                    <div class="pr-4 pl-2 py-2">
                                        Lưu tất cả
                                    </div>
                                </button>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <!-- Modal báo cáo sản phẩm -->
        <div id="reportProductModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <h3 class="text-lg font-semibold mb-4">Báo cáo sản phẩm</h3>
                <form action="{{ route('product.report', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="shop_id" value="{{ $product->shopID }}">
                    <div class="mb-4">
                        <label for="report_type" class="block text-sm font-medium">Loại vi phạm</label>
                        <select name="report_type" id="report_type"
                            class="mt-1 block w-full border-gray-300 rounded-md text-sm">
                            <option value="fake_product">Sản phẩm giả nhái</option>
                            <option value="product_violation">Vi phạm chính sách sản phẩm</option>
                            <option value="copyright">Vi phạm bản quyền</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="report_content" class="block text-sm font-medium">Nội dung báo cáo</label>
                        <textarea name="report_content" id="report_content" rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-md text-sm" placeholder="Mô tả chi tiết vi phạm"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="evidence" class="block text-sm font-medium">Bằng chứng</label>
                        <input type="file" name="evidence[]" id="evidence" multiple
                            class="mt-1 block w-full text-sm">
                    </div>
                    <div class="mb-4 flex items-center">
                        <input type="checkbox" name="is_anonymous" id="is_anonymous" class="h-4 w-4">
                        <label for="is_anonymous" class="ml-2 text-sm">Báo cáo ẩn danh</label>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" id="cancelReportBtn" class="px-4 py-2 bg-gray-200 rounded">Hủy</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Gửi</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Đánh giá -->
        <div class="mt-6 bg-white rounded-lg">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Đánh giá người dùng</h3>
            @forelse($reviews as $review)
                <div class="review mb-4 border-b pb-2">
                    <strong>{{ $review->user->fullname ?? 'Người dùng ẩn danh' }}</strong>
                    <span class="text-yellow-500">{{ $review->rating }} sao</span>
                    <p>{{ $review->comment }}</p>
                    <small class="text-gray-500">{{ $review->created_at->diffForHumans() }}</small>
                </div>
            @empty
                <p>Chưa có đánh giá nào.</p>
            @endforelse
        </div>
    </div>
    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Thay đổi ảnh chính
            function changeMainImage(imagePath) {
                document.getElementById('main-image').src = imagePath;
            }
            window.changeMainImage = changeMainImage;

            // Tăng/giảm số lượng
            const decreaseBtn = document.getElementById('decreaseQty');
            const increaseBtn = document.getElementById('increaseQty');
            const quantityInput = document.getElementById('quantity');
            decreaseBtn.addEventListener('click', () => {
                let value = parseInt(quantityInput.value);
                if (value > 1) quantityInput.value = value - 1;
            });
            increaseBtn.addEventListener('click', () => {
                let value = parseInt(quantityInput.value);
                quantityInput.value = value + 1;
            });
            // Modal báo cáo
            const reportButton = document.getElementById('reportProductBtn');
            const reportModal = document.getElementById('reportProductModal');
            const cancelReportBtn = document.getElementById('cancelReportBtn');
            reportButton.addEventListener('click', () => reportModal.classList.remove('hidden'));
            cancelReportBtn.addEventListener('click', () => reportModal.classList.add('hidden'));
            reportModal.addEventListener('click', (e) => {
                if (e.target === reportModal) reportModal.classList.add('hidden');
            });

            // Chọn sao đánh giá
            const stars = document.querySelectorAll('.star');
            const ratingInput = document.getElementById('ratingInput');
            stars.forEach(star => {
                star.addEventListener('click', () => {
                    const value = star.getAttribute('data-value');
                    ratingInput.value = value;
                    stars.forEach(s => {
                        s.classList.toggle('text-yellow-400', s.getAttribute(
                            'data-value') <= value);
                        s.classList.toggle('text-gray-300', s.getAttribute('data-value') >
                            value);
                    });
                });
            });
        });

        // Hiển thị mô tả sản phẩm
        document.addEventListener('DOMContentLoaded', () => {
            const readMore = document.getElementById('readMore');
            const shortDescription = document.getElementById('shortDescription');
            const fullDescription = document.getElementById('fullDescription');
            const descriptionContent = document.querySelector('.description-content');

            readMore.addEventListener('click', () => {
                if (readMore.textContent.trim() === 'Xem thêm') {
                    // Hiển thị toàn bộ mô tả
                    shortDescription.style.display = 'none';
                    const fullDescClone = fullDescription.cloneNode(true);
                    fullDescClone.classList.remove('hidden');
                    descriptionContent.innerHTML = '';
                    descriptionContent.appendChild(fullDescClone);
                    readMore.textContent = 'Thu gọn';
                } else {
                    // Thu gọn lại
                    shortDescription.style.display = 'block';
                    fullDescription.classList.add('hidden');
                    descriptionContent.innerHTML = shortDescription.outerHTML;
                    readMore.textContent = 'Xem thêm';
                }
            });
        });

        // Hiển thị menu khi nhấn nút
        document.addEventListener('DOMContentLoaded', function() {
            const menuButton = document.getElementById('menuButton');
            const menuDropdown = document.getElementById('menuDropdown');
            const reportBtn = document.getElementById('reportBtn');
            const favoriteBtn = document.getElementById('favoriteBtn');

            menuButton.addEventListener('click', function(e) {
                e.preventDefault();
                menuDropdown.classList.toggle('hidden');
            });

            // Ẩn dropdown khi nhấp ra ngoài
            document.addEventListener('click', function(e) {
                if (!menuButton.contains(e.target) && !menuDropdown.contains(e.target)) {
                    menuDropdown.classList.add('hidden');
                }
            });

            // Xử lý yêu thích sản phẩm
            favoriteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                alert('Thêm vào danh sách yêu thích!');
                menuDropdown.classList.add('hidden');
            });

            // Xử lý báo cáo sản phẩm (mở modal nếu cần)
            reportBtn.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('reportProductModal').classList.remove('hidden');
                menuDropdown.classList.add('hidden');
            });
        });

        // Xử lý ajax lọc
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.review-filter-btn');
            const reviewList = document.getElementById('review-list');
            let activeFilter = null;

            filterButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault(); // Ngăn hành vi mặc định của nút

                    const selected = this.getAttribute('data-filter'); // Lấy giá trị data-filter
                    // Nếu bấm lại bộ lọc đang chọn, reset về không lọc
                    const applyFilter = activeFilter === selected ? null : selected;

                    // Cập nhật activeFilter
                    activeFilter = applyFilter;

                    // Tạo URL cho yêu cầu AJAX
                    const url = applyFilter ?
                        `${window.location.pathname}?filter=${encodeURIComponent(applyFilter)}` :
                        window.location.pathname;

                    fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.text())
                        .then(html => {
                            if (reviewList) {
                                reviewList.innerHTML = html;

                                // Reset trạng thái tất cả các nút
                                filterButtons.forEach(btn => {
                                    btn.classList.remove('bg-blue-600', 'text-white');
                                    btn.classList.add('text-gray-700');
                                });

                                // Cập nhật trạng thái nút được chọn
                                if (applyFilter) {
                                    this.classList.remove('text-gray-700');
                                    this.classList.add('bg-blue-600', 'text-white');
                                }
                                // Khi applyFilter là null, không tô màu bất kỳ nút nào
                            }
                        })
                        .catch(err => console.error('Lỗi khi tải đánh giá:', err));
                });
            });
        });
    </script>
    </div>
@endsection
