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

                <p class="text-gray-600 mb-4">
                    {{ $product->description }}
                </p>

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
                            <div class="w-5 h-5 rounded-full border"
                                style="background-color: {{ $variant->color_code }}"></div>
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
                </div>
            </div>
        </div>
        @if (isset($shop))
        <div class="mb-4">
            <div class="flex items-center">
                <img src="{{ asset($shop->shop_logo) }}" class="w-10 h-10 rounded-full mr-3" alt="logo cửa hàng">
                <div>
                    <a href="{{ route('shop.profile', $shop->id) }}" class="text-blue-600 hover:underline font-semibold">
                        {{ $shop->shop_name }}
                    </a>
                    <p class="text-sm text-gray-500">Đánh giá: {{ $shop->shop_rating }}/5 ({{ $shop->total_ratings }} đánh giá)</p>
                </div>
            </div>
        </div>
        @endif


        <!-- Mô tả chi tiết -->
        <div class="mt-6 bg-white rounded-lg">
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Mô tả chi tiết</h3>
            <p class="text-gray-600 mb-4">
                {{ $product->meta_title }}
            </p>
            <img src="https://gongangshop.vn/wp-content/uploads/2024/05/Ban-phim-RGB-1024x576.png"
                class="w-full my-3 rounded" style="height: 500px;" alt="Mô tả hình ảnh 1">
            <p class="text-gray-600 mb-4">{{ $product->meta_description }}</p>
            <img src="https://gongangshop.vn/wp-content/uploads/2024/05/Ban-phim-RGB-1024x576.png"
                class="w-full my-3 rounded" style="height: 500px;" alt="Mô tả hình ảnh 2">
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
