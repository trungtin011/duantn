@extends('layouts.app')

@section('title', 'Chi tiết sản phẩm')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <div class="container mx-auto">
        <!-- breadcrumb -->
        <div class="flex flex-wrap items-center gap-2 py-[80px] px-[10px] sm:px-0 text-sm md:text-base">
            <a href="{{ route('home') }}" class="text-gray-500 hover:underline">Trang chủ</a>
            <span class="text-gray-500">/</span>
            <span class="text-gray-500">Chi tiết sản phẩm</span>
            <span class="ml-2 text-gray-500">/</span>
            <span>{{ $product->name }}</span>
        </div>
        <!-- Hình ảnh và thông tin sản phẩm -->
        <div class="grid grid-cols-1 md:flex md:justify-center gap-6 bg-[#F5F5F5] p-4">
            <!-- Hình ảnh sản phẩm -->
            <div class="rounded-lg pr-4 w-2/3">
                <div class="flex gap-4">
                    <!-- Ảnh phụ bên trái -->
                    <div class="flex flex-col gap-2 w-1/4 overflow-y-auto h-[530px]"
                        style="scrollbar-width: none; -ms-overflow-style: none;">
                        @foreach ($product->images as $image)
                            <img src="{{ Storage::url($image->image_path) }}" alt="Ảnh phụ {{ $product->name }}"
                                class="w-full rounded cursor-pointer sub-image hover:opacity-75 transition-opacity"
                                onclick="changeMainImage('{{ Storage::url($image->image_path) }}')">
                        @endforeach
                        @if ($product->images->isEmpty())
                            <img src="{{ Storage::url('product_images/default.jpg') }}" alt="Ảnh mặc định"
                                class="w-full rounded cursor-pointer sub-image">
                        @endif
                    </div>
                    <style>
                        .overflow-y-auto::-webkit-scrollbar {
                            display: none;
                        }
                    </style>
                    <!-- Ảnh chính -->
                    <div class="w-3/4">
                        <img id="main-image"
                            src="{{ $product->images->first()->image_path ? Storage::url($product->images->first()->image_path) : Storage::url('product_images/default.jpg') }}"
                            alt="{{ $product->name }}" class="w-full rounded" style="height: 530px; object-fit: cover;">
                    </div>
                </div>
            </div>

            <script>
                function changeMainImage(imagePath) {
                    const mainImage = document.getElementById('main-image');
                    if (mainImage) {
                        mainImage.src = imagePath;
                    }
                }
            </script>

            <!-- Thông tin sản phẩm -->
            <div class="rounded-lg w-[600px]">
                <div class="flex flex-col gap-[15px] mb-3">
                    <h2 class="text-3xl font-bold text-gray-800">{{ $product->name }}</h2>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1 text-sm text-gray-500">
                            ({{ $product->reviews->count() > 0 ? number_format($product->reviews->avg('rating'), 1) . '/5' : '0' }})
                            <span class="text-yellow-400 flex">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= round($product->reviews->avg('rating')))
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="w-6 h-6">
                                            <path fill-rule="evenodd"
                                                d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                        </svg>
                                    @endif
                                @endfor
                            </span>
                            <span class="ml-2">
                                ({{ $product->reviews->count() }} đánh giá) | Đã bán:
                                {{ $product->sold_quantity >= 1000 ? $product->sold_quantity / 1000 . 'k' : $product->sold_quantity }}
                            </span>
                        </div>

                        <button id="reportProductBtn" class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6 text-yellow-600 hover:text-yellow-800 cursor-pointer">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center gap-5 bg-[#F2F2F2] p-3">
                        <span class="text-red-600 text-2xl">
                            {{ number_format($product->sale_price, 0, ',', '.') }} VNĐ
                        </span>
                        <span class="text-gray-500 line-through">
                            {{ number_format($product->price, 0, ',', '.') }} VNĐ
                        </span>
                        <span class="bg-[#FBD6D6] text-black px-2 rounded">
                            -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="text-sm">{!! $product->meta_description !!}</p>
                </div>

                {{-- divider --}}
                <div class="border-t border-black my-4"></div>

                {{-- Voucher --}}
                <div class="mb-6">
                    <div class="flex items-center gap-2">
                        <span class="">Voucher của Shop:</span>
                        <div class="flex items-center gap-3">
                            <div class="bg-[#FBD6D6] p-1 px-2">
                                <span class="text-red-600 text-sm">Giảm 10%</span>
                            </div>
                            <div class="bg-[#FBD6D6] p-1 px-2">
                                <span class="text-red-600 text-sm">Giảm 10%</span>
                            </div>
                            <div class="bg-[#FBD6D6] p-1 px-2">
                                <span class="text-red-600 text-sm">Giảm 10%</span>
                            </div>
                            <div class="bg-[#FBD6D6] p-1 px-2">
                                <span class="text-red-600 text-sm">Giảm 10%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-5 flex flex-col gap-4">
                    <div class="flex items-center gap-[50px]">
                        <span class="text-gray-700">Màu sắc:</span>
                        <div class="">
                            <button
                                class="bg-transparent border border-gray-300 rounded-[4px] w-fit mr-2 py-1 px-2 flex items-center gap-1">
                                <img src="https://4men.com.vn/images/thumbs/2021/11/ao-thun-tron-vai-so-go-mau-do-do-at045-16342-slide-products-618c8920b4646.jpg"
                                    width="25">
                                <span class="">Đỏ đô</span>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center gap-[35px]">
                        <span class="text-gray-700">Kích thước:</span>
                        <div class="">
                            <button class="bg-transparent border border-gray-300 mr-2 py-1 px-2 w-fit rounded-[4px]">
                                <span class="text-sm">2XL</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="flex items-center gap-2 w-full">
                        <span class="text-gray-700">Số lượng:</span>
                        <div class="border border-gray-300 rounded-[4px] flex items-center w-fit px-2 py-1">
                            <button class="">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <input type="text" value="1" class="w-[50px] text-center focus:outline-none ">
                            <button class="">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <small class="text-gray-500 block mt-1">{{ $product->stock_total }} Sản phẩm có sẵn</small>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button
                        class="bg-[#FBD6D6] px-4 py-3 rounded hover:bg-[#FAE3E3] flex items-center gap-2 hover:duration-300 duration-300">
                        <i class="fa-solid fa-cart-plus text-red-600 text-xl"></i>
                        Thêm Vào Giỏ Hàng
                    </button>
                    <button
                        class="bg-black text-white px-4 py-3 rounded w-[158px] hover:bg-transparent hover:border hover:border-black hover:text-black hover:duration-300 duration-300">
                        Mua ngay
                    </button>
                    <button
                        class="border border-gray-300 text-gray-700 px-4 py-3 rounded hover:bg-gray-100 hover:duration-300 duration-300 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Thông tin shop -->
        <div class="mt-6 bg-[#F5F5F5] flex items-center justify-center h-[147px] px-10 shadow">
            <div class="grid grid-cols-2 gap-4 w-full">
                <div class="col-span-1 w-[600px]">
                    <div class="flex items-center gap-10">
                        <img src="{{ $product->shop ? ($product->shop->shop_logo ? Storage::url($product->shop->shop_logo) : Storage::url('shop_logos/default_shop_logo.png')) : Storage::url('shop_logos/default_shop_logo.png') }}"
                            alt="Logo Shop" class="w-[100px] h-[100px] rounded-full object-cover">

                        <div class="flex flex-col">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">
                                {{ $product->shop ? $product->shop->shop_name : 'Tên Shop Không Xác Định' }}
                            </h3>
                            {{-- Hiển thị thời gian hoạt động của shop --}}
                            <p class="text-gray-600 text-sm mb-2">
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
                                    @if ($lastActivity)
                                        Online {{ $lastOnline }}
                                    @else
                                        Hoạt động từ:
                                        {{ \Carbon\Carbon::parse($product->shop->created_at)->locale('vi')->diffForHumans() }}
                                    @endif
                                @else
                                    Chưa có thông tin
                                @endif
                            </p>
                            {{-- Button chat & view shop --}}
                            <div class="flex gap-2">
                                <button
                                    class="bg-[#F1DADA] px-4 py-3 rounded hover:bg-[#FBD6D6] transition duration-300 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-6 text-[#DB4444]">
                                        <path fill-rule="evenodd"
                                            d="M12 2.25c-2.429 0-4.817.178-7.152.521C2.87 3.061 1.5 4.795 1.5 6.741v6.018c0 1.946 1.37 3.68 3.348 3.97.877.129 1.761.234 2.652.316V21a.75.75 0 0 0 1.28.53l4.184-4.183a.39.39 0 0 1 .266-.112c2.006-.05 3.982-.22 5.922-.506 1.978-.29 3.348-2.023 3.348-3.97V6.741c0-1.947-1.37-3.68-3.348-3.97A49.145 49.145 0 0 0 12 2.25ZM8.25 8.625a1.125 1.125 0 1 0 0 2.25 1.125 1.125 0 0 0 0-2.25Zm2.625 1.125a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Zm4.875-1.125a1.125 1.125 0 1 0 0 2.25 1.125 1.125 0 0 0 0-2.25Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Chat với Shop
                                </button>
                                <a href=""
                                    class="ml-3 bg-[#EDEDED] border border-gray-300 px-4 py-3 rounded hover:bg-[#E2E2E2] transition duration-300">
                                    Xem Shop
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-1">
                    <div class="flex flex-col h-full">
                        <div class="flex items-center gap-[50px] py-4">
                            <span class="text-[#7D8184]">
                                Đánh giá
                                <span class="text-[#DB4444] ml-10">2.5k</span>
                            </span>
                            <span class="text-[#7D8184]">
                                Tỉ lệ phản hồi
                                <span class="text-[#DB4444] ml-10">100%</span>
                            </span>
                            <span class="text-[#7D8184]">
                                Tham gia
                                <span class="text-[#DB4444] ml-10">36 tháng trước</span>
                            </span>
                        </div>
                        <div class="flex items-center gap-[50px] py-4">
                            <span class="text-[#7D8184]">
                                Sản phẩm
                                <span class="text-[#DB4444] ml-10">31</span>
                            </span>
                            <span class="text-[#7D8184]">
                                thời gian phản hồi
                                <span class="text-[#DB4444] ml-10">
                                    trong vài giờ</span>
                            </span>
                            <span class="text-[#7D8184]">Người theo dõi<span class="text-[#DB4444] ml-10">2k</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mô tả chi tiết -->
        <div class="mt-6 rounded-lg">
            <div class="flex gap-4">
                <div class="col-span-1 bg-[#F5F5F5] w-full shadow px-10">
                    <h3 class="text-xl text-gray-800 mb-3 mt-10 h-[50px] bg-[#EDEDED] flex items-center pl-4">
                        CHI TIẾT SẢN PHẨM</h3>
                    <div class="flex flex-col gap-4 mt-[50px]">
                        <div class="flex items-center gap-[10px] pl-4">
                            <span class="text-[#878889]">Danh mục</span>
                            <a href="" class="text-[#0055AA] ml-[175px]">
                                {{ $product->category ?: 'Chưa xác định' }}
                            </a>
                            <i class="fa-solid fa-chevron-right mx-2"></i>
                            <a href="{{ route('product.show', ['slug' => $product->slug]) }}" class="text-[#0055AA]">
                                {{ $product->name }}
                            </a>
                        </div>
                        <div class="flex items-center gap-[10px] pl-4">
                            <span class="text-[#878889]">Số sản phẩm còn lại</span>
                            <span href="" class="ml-[99px]">
                                {{ $product->stock_total ?: 'Chưa xác định' }}
                            </span>
                        </div>
                    </div>
                    <h3 class="text-xl text-gray-800 mb-3 mt-10 h-[50px] bg-[#EDEDED] flex items-center pl-4">
                        MÔ TẢ SẢN PHẨM
                    </h3>
                    <div class="mt-[50px]">
                        <div class="text-gray-600 mb-4 pl-4">
                            {!! $product->description !!}
                        </div>
                    </div>
                </div>
                <div class="col-span-1 bg-[#F5F5F5] w-1/3 shadow">

                </div>
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

    <!-- Report Product Modal -->
    <div id="reportProductModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
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
                    <label for="report_content" class="block text-sm font-medium text-gray-700">Nội dung báo
                        cáo</label>
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
        document.addEventListener('DOMContentLoaded', function() {
            const reportButton = document.getElementById('reportProductBtn');
            const reportModal = document.getElementById('reportProductModal');
            const cancelReportBtn = document.getElementById('cancelReportBtn');

            // Open modal
            reportButton.addEventListener('click', function() {
                reportModal.classList.remove('hidden');
            });

            // Close modal
            cancelReportBtn.addEventListener('click', function() {
                reportModal.classList.add('hidden');
            });

            // Close modal when clicking outside
            reportModal.addEventListener('click', function(event) {
                if (event.target === reportModal) {
                    reportModal.classList.add('hidden');
                }
            });

            // Tăng/giảm số lượng (existing code)
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
