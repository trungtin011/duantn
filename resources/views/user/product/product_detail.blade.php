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
                 <div class="mt-10 bg-white p-6 rounded shadow-md border border-gray-200">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Đánh giá sản phẩm</h3>

            @php
            $userReview = auth()->check() ? $product->reviews->where('user_id', auth()->id())->first() : null;
            @endphp

            <!-- Form đánh giá -->
            @if (!auth()->check())
            <p class="text-sm text-gray-600">
                <a href="{{ route('login') }}" class="text-blue-600 underline">Đăng nhập</a> để gửi đánh giá cho sản phẩm này.
            </p>
            @elseif (!$userReview)
            <form action="{{ route('product.review', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Chọn sao -->
                <label class="block mb-2">Đánh giá sao:</label>
                <div class="flex items-center space-x-1 mb-4">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg data-value="{{ $i }}" xmlns="http://www.w3.org/2000/svg"
                        class="star w-7 h-7 cursor-pointer text-gray-300 transition duration-150 ease-in-out"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 0 0 .95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 0 0-.364 1.118l1.286 3.966c.3.921-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 0 0-1.176 0l-3.385 2.46c-.784.57-1.838-.197-1.54-1.118l1.286-3.966a1 1 0 0 0-.364-1.118L2.045 9.393c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 0 0 .95-.69l1.286-3.966z" />
                        </svg>
                        @endfor
                        <input type="hidden" name="rating" id="ratingInput" required>
                </div>

                <!-- Nhận xét -->
                <textarea name="comment" rows="3" class="w-full border p-2 rounded mb-4" placeholder="Viết nhận xét..."></textarea>

                <!-- Tải ảnh -->
                <label class="block mb-2">Hình ảnh:</label>
                <input type="file" name="images[]" multiple accept="image/*" class="mb-4 block w-full">

                <!-- Tải video -->
                <label class="block mb-2">Video:</label>
                <input type="file" name="video" accept="video/*" class="mb-4 block w-full">

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Gửi đánh giá</button>
            </form>
            @endif

            <!-- Danh sách tất cả đánh giá -->
            <section class="border border-[#f5f0eb] bg-[#fffaf5] rounded-md p-6 mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 mb-6">
                    <div class="flex items-center space-x-3 mb-4 sm:mb-0">
                        <div class="text-[#f15a29] text-3xl font-light leading-none">
                            {{ number_format($product->reviews->avg('rating'), 1) }}
                        </div>
                        <div class="text-[#f15a29] text-xs font-normal leading-none pt-1">
                            trên 5
                        </div>
                    </div>
                    <!-- Bộ lọc -->
                    <div class="flex flex-wrap gap-2 text-xs font-normal text-[#1a1a1a]">
                        <a href="{{ route('product.show', $product->slug) }}"
                            class="border {{ !$ratingFilter ? 'border-[#f15a29] text-[#f15a29]' : 'border-[#e5e5e5] text-[#1a1a1a]' }} rounded px-3 py-1 leading-none">
                            Tất cả
                        </a>
                        @for ($i = 5; $i >= 1; $i--)
                        <a href="{{ route('product.show', [$product->slug, 'rating' => $i]) }}"
                            class="border {{ $ratingFilter == $i ? 'border-[#f15a29] text-[#f15a29]' : 'border-[#e5e5e5] text-[#1a1a1a]' }} rounded px-3 py-1 leading-none">
                            {{ $i }} Sao ({{ $product->reviews->where('rating', $i)->count() }})
                        </a>
                        @endfor
                    </div>

                    <div class="flex items-center mt-4 sm:mt-0 text-[#f15a29] text-xl leading-none">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <=round($product->reviews->avg('rating')))
                            <i class="fas fa-star"></i>
                            @elseif ($i - $product->reviews->avg('rating') < 1)
                                <i class="fas fa-star-half-alt"></i>
                                @else
                                <i class="far fa-star"></i>
                                @endif
                                @endfor
                    </div>
                </div>
            </section>

            <section class="divide-y divide-[#e5e5e5]">
                @forelse ($filteredReviews->sortByDesc('created_at') as $review)
                <article class="py-6">
                    <div class="flex items-center space-x-3 mb-2">
                        <img class="w-8 h-8 rounded-full" src="{{ asset('images/default-avatar.png') }}" alt="avatar">
                        <span class="text-xs font-normal text-[#666666]">{{ $review->user->fullname ?? 'Ẩn danh' }}</span>
                    </div>
                    <div class="text-[#f15a29] text-xs mb-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                    </div>
                    <div class="text-xs text-[#666666] mb-3">
                        {{ $review->created_at->format('Y-m-d H:i') }} | Phân loại: {{ $review->variant_info ?? 'N/A' }}
                    </div>
                    @if($review->comment)
                    <div class="text-xs text-[#1a1a1a] font-semibold mb-2">
                        {{ $review->comment }}
                    </div>
                    @endif
                    @if ($review->images)
                    <div class="flex gap-2 mt-2">
                        @foreach ($review->images as $img)
                        <img src="{{ asset('storage/' . $img->image_path) }}"
                            class="w-20 h-20 object-cover rounded shadow cursor-pointer review-media"
                            data-full="{{ asset('storage/' . $img->image_path) }}">
                        @endforeach
                    </div>
                    @endif

                    @if ($review->video_path)
                    <video class="w-20 h-20 mt-2 rounded shadow cursor-pointer review-media" muted data-full="{{ asset('storage/' . $review->video_path) }}">
                        <source src="{{ asset('storage/' . $review->video_path) }}" type="video/mp4">
                    </video>
                    @endif
                    @auth
                    <div class="text-xs text-blue-600 flex items-center space-x-1 mt-2 cursor-pointer like-review"
                        data-id="{{ $review->id }}">
                        <i class="fas fa-thumbs-up"></i>
                        <span>{{ $review->likes->count() }} Hữu Ích</span>
                    </div>
                    @else
                    <div class="text-xs text-gray-500 flex items-center space-x-1 mt-2 cursor-not-allowed">
                        <i class="fas fa-thumbs-up"></i>
                        <span>{{ $review->likes->count() }} Hữu Ích</span>
                    </div>
                    @endauth

                </article>
                @empty
                <p class="text-gray-600">Chưa có đánh giá nào cho sản phẩm này.</p>
                @endforelse
                <div id="mediaModal" class="fixed inset-0 z-50 bg-black bg-opacity-80 hidden items-center justify-center">
                    <span id="closeModal" class="absolute top-4 right-6 text-white text-3xl cursor-pointer">&times;</span>
                    <div id="mediaContent" class="max-w-3xl max-h-[80vh] mx-auto"></div>
                </div>
                @if ($recentProducts->count())
                <div class="mt-10">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Sản phẩm bạn đã xem gần đây</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach ($recentProducts as $item)
                        <a href="{{ route('product.show', $item->slug) }}" class="block border rounded hover:shadow p-2">
                            <img src="{{ asset($item->images->first()->image_path ?? 'images/default.jpg') }}"
                                class="h-40 w-full object-cover rounded mb-2">
                            <div class="text-sm font-medium text-gray-700 truncate">{{ $item->name }}</div>
                            <div class="text-red-600 font-bold text-sm">
                                {{ number_format($item->sale_price ?? $item->price, 0, ',', '.') }}đ
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </section>




            <!-- JavaScript để thay đổi ảnh chính -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const stars = document.querySelectorAll('.star');
                    const ratingInput = document.getElementById('ratingInput');

                    let selectedRating = 0;

                    stars.forEach(star => {
                        star.addEventListener('mouseover', () => {
                            const val = parseInt(star.getAttribute('data-value'));
                            highlightStars(val);
                        });

                        star.addEventListener('mouseout', () => {
                            highlightStars(selectedRating);
                        });

                        star.addEventListener('click', () => {
                            selectedRating = parseInt(star.getAttribute('data-value'));
                            ratingInput.value = selectedRating;
                            highlightStars(selectedRating);
                        });
                    });

                    function highlightStars(rating) {
                        stars.forEach(s => {
                            const val = parseInt(s.getAttribute('data-value'));
                            if (val <= rating) {
                                s.classList.remove('text-gray-300');
                                s.classList.add('text-yellow-400');
                            } else {
                                s.classList.remove('text-yellow-400');
                                s.classList.add('text-gray-300');
                            }
                        });
                    }

                    // Chặn người dùng gửi cả ảnh và video
                    const imageInput = document.getElementById('imageInput');
                    const videoInput = document.getElementById('videoInput');

                    if (imageInput && videoInput) {
                        imageInput.addEventListener('change', () => {
                            if (imageInput.files.length > 0) {
                                videoInput.value = '';
                            }
                        });

                        videoInput.addEventListener('change', () => {
                            if (videoInput.files.length > 0) {
                                imageInput.value = '';
                            }
                        });
                    }
                });
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelectorAll('.like-review').forEach(el => {
                        el.addEventListener('click', function() {
                            const reviewId = el.getAttribute('data-id');

                            fetch(`/review/${reviewId}/like`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        'Accept': 'application/json'
                                    },
                                })
                                .then(res => res.json())
                                .then(data => {
                                    el.querySelector('span').innerText = data.likes_count + ' Hữu Ích';
                                    el.classList.toggle('text-blue-600', data.liked);
                                    el.classList.toggle('text-gray-500', !data.liked);
                                });
                        });
                    });
                });
                document.addEventListener('DOMContentLoaded', function() {
                    const modal = document.getElementById('mediaModal');
                    const content = document.getElementById('mediaContent');
                    const close = document.getElementById('closeModal');

                    document.querySelectorAll('.review-media').forEach(media => {
                        media.addEventListener('click', () => {
                            const src = media.getAttribute('data-full');
                            let html = '';

                            if (media.tagName === 'IMG') {
                                html = `<img src="${src}" class="w-full h-auto rounded shadow">`;
                            } else if (media.tagName === 'VIDEO') {
                                html = `<video src="${src}" class="w-full rounded shadow" controls autoplay></video>`;
                            }

                            content.innerHTML = html;
                            modal.classList.remove('hidden');
                            modal.classList.add('flex');
                        });
                    });

                    close.addEventListener('click', () => {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                        content.innerHTML = '';
                    });
                });
            </script>

            @endsection