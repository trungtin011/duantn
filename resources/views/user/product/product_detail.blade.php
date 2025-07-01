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
                            src="{{ $product->images->where('is_default', 1)->first() ? asset('storage/' . $product->images->where('is_default', 1)->first()->image_path) : ($product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('storage/product_images/default.jpg')) }}"
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
                                    @for ($i = 1; $i <= 2; $i++)
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
                        <div class="flex items-center gap-4" id="price-display">
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
                            @php
                                $colorValues = $product->variants->isNotEmpty()
                                    ? $product->variants->flatMap->attributeValues
                                        ->where('attribute.name', 'Màu sắc')
                                        ->pluck('value')
                                        ->unique()
                                    : collect();
                                $sizeValues = $product->variants->isNotEmpty()
                                    ? $product->variants->flatMap->attributeValues
                                        ->where('attribute.name', 'Kích cỡ')
                                        ->pluck('value')
                                        ->unique()
                                    : collect();
                            @endphp
                            @if ($colorValues->isNotEmpty())
                                <div class="flex items-center gap-4" id="color-options">
                                    <span class="text-gray-700">Màu sắc:</span>
                                    @foreach ($colorValues as $color)
                                        <button
                                            class="border border-gray-300 rounded px-3 py-1 flex items-center gap-2 hover:bg-gray-100"
                                            data-value="{{ $color }}"
                                            data-price="{{ $variantData[$product->variants->firstWhere(function ($v) use ($color) {return $v->attributeValues->where('attribute.name', 'Màu sắc')->first()->value == $color;})->id]['price'] ?? $product->sale_price }}"
                                            data-stock="{{ $variantData[$product->variants->firstWhere(function ($v) use ($color) {return $v->attributeValues->where('attribute.name', 'Màu sắc')->first()->value == $color;})->id]['stock'] ?? $product->stock_total }}">
                                            <img src="{{ isset($colorImages[$color]) ? Storage::url($colorImages[$color]) : asset('images/default_product_image.png') }}"
                                                width="20" class="rounded">
                                            <span>{{ $color }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">Không có tùy chọn màu sắc.</p>
                            @endif
                            @if ($sizeValues->isNotEmpty())
                                <div class="flex items-center gap-4" id="size-options">
                                    <span class="text-gray-700">Kích thước:</span>
                                    @foreach ($sizeValues as $size)
                                        <button class="border border-gray-300 rounded px-3 py-1 hover:bg-gray-100"
                                            data-value="{{ $size }}"
                                            data-price="{{ $variantData[$product->variants->firstWhere(function ($v) use ($size) {return $v->attributeValues->where('attribute.name', 'Kích cỡ')->first()->value == $size;})->id]['price'] ?? $product->sale_price }}"
                                            data-stock="{{ $variantData[$product->variants->firstWhere(function ($v) use ($size) {return $v->attributeValues->where('attribute.name', 'Kích cỡ')->first()->value == $size;})->id]['stock'] ?? $product->stock_total }}">
                                            <span>{{ $size }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">Không có tùy chọn kích thước.</p>
                            @endif
                        </div>

                        <!-- Số lượng và biến thể được chọn -->
                        <div class="flex items-center gap-4">
                            <span class="text-gray-700">Số lượng:</span>
                            <form action="{{ route('cart.add') }}" method="POST" class="flex items-center">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="variant_id" id="selected_variant_id" value="">
                                <button type="button" id="decreaseQty"
                                    class="border border-gray-300 px-3 py-1 rounded-l hover:bg-gray-100">-</button>
                                <input type="text" name="quantity" id="quantity" value="1"
                                    class="w-16 text-center px-3 py-1 border-t border-b border-gray-300 focus:outline-none">
                                <button type="button" id="increaseQty"
                                    class="border border-gray-300 px-3 py-1 rounded-r hover:bg-gray-100">+</button>
                            </form>
                            <span class="text-sm text-gray-500" id="stock_info">{{ $product->stock_total }} sản phẩm có
                                sẵn</span>
                        </div>

                        <!-- Nút hành động -->
                        <div class="flex gap-3 mt-10">
                            <button
                                class="bg-red-100 text-red-600 px-6 py-3 rounded hover:bg-red-200 flex items-center gap-2 add-to-cart"
                                data-product-id="{{ $product->id }}">
                                <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ
                            </button>
                            <button class="bg-black text-white px-6 py-3 rounded hover:bg-gray-800">Mua ngay</button>
                        </div>
                    </div>
                </div>

                @push('scripts')
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const variantButtons = document.querySelectorAll('button[data-value]');
                            const addToCartButtons = document.querySelectorAll('.add-to-cart');
                            const token = '{{ csrf_token() }}';
                            let selectedVariantId = null;
                            const mainImage = document.getElementById('main-image');
                            const priceDisplay = document.getElementById('price-display');
                            const stockInfo = document.getElementById('stock_info');
                            const selectedVariantIdInput = document.getElementById('selected_variant_id');

                            // Hàm thay đổi ảnh chính
                            function changeMainImage(src) {
                                mainImage.src = src;
                            }

                            // Hàm reset về trạng thái ban đầu
                            function resetToDefault() {
                                selectedVariantId = null;
                                selectedVariantIdInput.value = '';
                                mainImage.src =
                                    '{{ $product->images->where('is_default', 1)->first() ? asset('storage/' . $product->images->where('is_default', 1)->first()->image_path) : ($product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('storage/product_images/default.jpg')) }}';
                                priceDisplay.innerHTML = `
                                    <span class="text-red-600 text-2xl font-bold">{{ number_format($product->sale_price, 0, ',', '.') }} VNĐ</span>
                                    <span class="text-gray-500 line-through">{{ number_format($product->price, 0, ',', '.') }} VNĐ</span>
                                    <span class="bg-red-100 text-red-600 px-2 rounded">-{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%</span>
                                `;
                                stockInfo.textContent = '{{ $product->stock_total }} sản phẩm có sẵn';
                            }

                            // Xử lý chọn biến thể (chỉ cập nhật UI)
                            variantButtons.forEach(button => {
                                button.addEventListener('click', function() {
                                    const value = this.getAttribute('data-value');
                                    const isColor = this.closest('#color-options') !== null;
                                    const allButtons = isColor ? document.querySelectorAll(
                                        '#color-options button[data-value]') : document.querySelectorAll(
                                        '#size-options button[data-value]');

                                    if (this.classList.contains('bg-gray-200') && this.classList.contains(
                                            'border-gray-500')) {
                                        this.classList.remove('bg-gray-200', 'border-gray-500');
                                        this.classList.add('border-gray-300');
                                        resetToDefault();
                                        return;
                                    }

                                    allButtons.forEach(btn => {
                                        btn.classList.remove('bg-gray-200', 'border-gray-500');
                                        btn.classList.add('border-gray-300');
                                    });

                                    this.classList.remove('border-gray-300');
                                    this.classList.add('bg-gray-200', 'border-gray-500');

                                    const selectedColor = document.querySelector(
                                        '#color-options button[data-value].bg-gray-200')?.getAttribute(
                                        'data-value');
                                    const selectedSize = document.querySelector(
                                        '#size-options button[data-value].bg-gray-200')?.getAttribute(
                                        'data-value');
                                    const variants = @json($product->variants->toArray(), JSON_HEX_TAG | JSON_HEX_AMP);

                                    if (selectedColor || selectedSize) {
                                        const variant = variants.find(v => {
                                            const attrs = v.attribute_values.map(a => ({
                                                name: a.attribute.name,
                                                value: a.value
                                            }));
                                            const matchesColor = selectedColor ? attrs.some(a => a.name ===
                                                'Màu sắc' && a.value === selectedColor) : true;
                                            const matchesSize = selectedSize ? attrs.some(a => a.name ===
                                                'Kích cỡ' && a.value === selectedSize) : true;
                                            return matchesColor && matchesSize;
                                        });
                                        if (variant) {
                                            selectedVariantId = variant.id;
                                            selectedVariantIdInput.value = variant.id;

                                            // Cập nhật ảnh từ biến thể
                                            const imagePath = variant.images.length > 0 ? variant.images[0]
                                                .image_path :
                                                '{{ $product->images->where('is_default', 1)->first() ? asset('storage/' . $product->images->where('is_default', 1)->first()->image_path) : ($product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('storage/product_images/default.jpg')) }}';
                                            mainImage.src = '{{ asset('storage/') }}/' + imagePath;

                                            // Cập nhật giá
                                            const price = variant.sale_price || variant.price;
                                            const originalPrice = variant.price;
                                            priceDisplay.innerHTML = `
                                                <span class="text-red-600 text-2xl font-bold">${number_format(price, 0, ',', '.')} VNĐ</span>
                                                <span class="text-gray-500 line-through">${number_format(originalPrice, 0, ',', '.')} VNĐ</span>
                                                <span class="bg-red-100 text-red-600 px-2 rounded">${Math.round(((originalPrice - price) / originalPrice) * 100)}%</span>
                                            `;

                                            // Cập nhật số lượng có sẵn
                                            const stock = variant.stock || {{ $product->stock_total }};
                                            stockInfo.textContent = `${stock} sản phẩm có sẵn`;
                                        }
                                    }
                                });
                            });

                            // Xử lý thêm vào giỏ hàng khi nhấn nút
                            addToCartButtons.forEach(button => {
                                button.addEventListener('click', () => {
                                    if (!selectedVariantId) {
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

                                    const quantityInput = document.getElementById('quantity');
                                    const quantity = quantityInput ? parseInt(quantityInput.value) : 1;
                                    const stock = parseInt(stockInfo.textContent.split(' ')[0]) ||
                                        {{ $product->stock_total }};

                                    if (quantity > stock) {
                                        Swal.fire({
                                            position: 'top-end',
                                            toast: true,
                                            icon: 'warning',
                                            title: 'Số lượng vượt quá tồn kho!',
                                            text: `Tồn kho chỉ còn ${stock} sản phẩm.`,
                                            timer: 1500,
                                            showConfirmButton: false
                                        });
                                        quantityInput.value = stock;
                                        return;
                                    }

                                    fetch('/customer/cart/add', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': token,
                                                'Accept': 'application/json',
                                            },
                                            body: JSON.stringify({
                                                product_id: button.getAttribute('data-product-id'),
                                                variant_id: selectedVariantId,
                                                quantity: quantity
                                            })
                                        })
                                        .then(response => {
                                            if (!response.ok) throw new Error(
                                                `HTTP error! Status: ${response.status}`);
                                            return response.json();
                                        })
                                        .then(data => {
                                            Swal.fire({
                                                position: 'top-end',
                                                toast: true,
                                                icon: 'success',
                                                title: data.message,
                                                timer: 1500,
                                                showConfirmButton: false
                                            });
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            Swal.fire({
                                                position: 'top-end',
                                                toast: true,
                                                icon: 'error',
                                                title: 'Lỗi',
                                                text: 'Không thể thêm vào giỏ hàng!',
                                                timer: 1500,
                                                showConfirmButton: false
                                            });
                                        });
                                });
                            });

                            // Xử lý nút tăng/giảm số lượng
                            document.getElementById('decreaseQty').addEventListener('click', function() {
                                let qtyInput = document.getElementById('quantity');
                                let qty = parseInt(qtyInput.value);
                                if (qty > 1) qtyInput.value = qty - 1;
                            });

                            document.getElementById('increaseQty').addEventListener('click', function() {
                                let qtyInput = document.getElementById('quantity');
                                let qty = parseInt(qtyInput.value);
                                const stock = selectedVariantId ? parseInt(stockInfo.textContent.split(' ')[0]) :
                                    {{ $product->stock_total }};
                                if (qty < stock) qtyInput.value = qty + 1;
                            });

                            // Hàm format số
                            function number_format(number, decimals, dec_point, thousands_sep) {
                                number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
                                let n = !isFinite(+number) ? 0 : +number;
                                let prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
                                let sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep;
                                let dec = (typeof dec_point === 'undefined') ? '.' : dec_point;
                                let s = '';
                                let toFixedFix = function(n, prec) {
                                    let k = Math.pow(10, prec);
                                    return '' + Math.round(n * k) / k;
                                };
                                s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
                                if (s[0].length > 3) {
                                    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                                }
                                if ((s[1] || '').length < prec) {
                                    s[1] = s[1] || '';
                                    s[1] += new Array(prec - s[1].length + 1).join('0');
                                }
                                return s.join(dec);
                            }
                        });
                    </script>
                @endpush

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
               <!-- ĐÁNH GIÁ SẢN PHẨM -->
<div class="bg-white rounded-lg p-6 mt-6 shadow">
    <h3 class="text-xl font-semibold mb-4">Đánh giá sản phẩm</h3>

    @if (auth()->check() && $hasPurchased && !$hasReviewed)
        <form action="{{ route('product.review', $product->id) }}" method="POST" enctype="multipart/form-data" class="mb-6">
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
            <textarea name="comment" rows="3" class="w-full border p-2 rounded mb-4 text-sm" placeholder="Viết nhận xét..."></textarea>
            <input type="file" name="images[]" multiple accept="image/*" class="mb-4 block w-full text-sm">
            <input type="file" name="video" accept="video/*" class="mb-4 block w-full text-sm">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm">Gửi đánh giá</button>
        </form>
    @endif

    <!-- HIỂN THỊ DANH SÁCH ĐÁNH GIÁ -->
    @if ($product->orderReviews->isEmpty())
        <p class="text-gray-500">Chưa có đánh giá nào cho sản phẩm này.</p>
    @else
        @foreach ($product->orderReviews->sortByDesc('created_at') as $review)
            <div class="border-b pb-4 mb-4">
                <div class="flex items-center mb-2">
                    <strong class="mr-2">{{ $review->user->fullname ?? 'Người dùng' }}</strong>
                    <div class="text-yellow-400">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                        @endfor
                    </div>
                </div>
                <p class="text-sm text-gray-700 mb-2">{{ $review->comment }}</p>

                <!-- Hình ảnh đánh giá -->
                @if ($review->images && $review->images->count())
                    <div class="flex gap-2 mb-2">
                        @foreach ($review->images as $img)
                            <img src="{{ Storage::url($img->image_path) }}" alt="Ảnh đánh giá"
                                 class="w-24 h-24 object-cover rounded border" />
                        @endforeach
                    </div>
                @endif

                <!-- Video đánh giá -->
                @if ($review->videos && $review->videos->count())
                    <div class="mb-2">
                        @foreach ($review->videos as $vid)
                            <video controls class="w-64 rounded">
                                <source src="{{ Storage::url($vid->video_path) }}" type="video/mp4">
                                Trình duyệt của bạn không hỗ trợ video.
                            </video>
                        @endforeach
                    </div>
                @endif

                <div class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</div>
            </div>
        @endforeach
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('ratingInput');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const value = star.dataset.value;
                ratingInput.value = value;
                stars.forEach(s => {
                    s.classList.toggle('text-yellow-400', s.dataset.value <= value);
                    s.classList.toggle('text-gray-300', s.dataset.value > value);
                });
            });
        });
    });
</script>
@endpush
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