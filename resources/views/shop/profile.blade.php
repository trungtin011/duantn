<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png" />
    <title>@yield('title', 'Default Title')</title>

    <!-- Font + Tailwind + Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <div class="container w-[1200px] mx-auto px-4">
        <div class="container mx-auto px-[10px] sm:px-0 pb-3 flex justify-between items-center">
            <!-- Logo -->
            @if (empty($settings->logo))
                <a class="w-full lg:w-[14%] flex items-center gap-2 py-2" href="/">
                    <div class="bg-black flex items-center gap-2 py-1 px-2 rounded lg:w-[175px]">
                        <img src="{{ asset('images/logo.svg') }}" alt="logo" class="w-[30%] h-[30%]">
                        <div class="text-white grid">
                            <h5 class="m-0 text-xl">ZynoxMall</h5>
                            <span class="text-xs text-right">zynoxmall.xyz</span>
                        </div>
                    </div>
                </a>
            @else
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="logo" class="w-20">
                </a>
            @endif
            <!-- Form tìm kiếm sản phẩm theo shop -->
            <form action="{{ route('shop.search', $shop->id) }}" method="GET"
                class="w-full md:w-1/2 flex items-center">
                <input type="text" name="query" placeholder="Tìm kiếm sản phẩm trong shop..."
                    value="{{ request('query') }}"
                    class="w-full md:w-full px-4 py-2 border shadow-sm focus:outline-none">
                <button type="submit" class="px-4 py-2 bg-[#e03e2f] text-white hover:bg-red-600 transition">
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>

        <!-- Banner và Logo -->
        <div class="relative w-full h-52 md:h-[400px] rounded-lg overflow-hidden mb-6 shadow-md">
            <img src="{{ asset('storage/' . $shop->shop_banner) }}" alt="Banner"
                class="absolute w-full h-full object-cover" alt="Banner shop">
            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-end justify-between px-6 py-4">
                <div class="flex items-center">
                    <img src="{{ asset('storage/' . $shop->shop_logo) }}" alt="Logo"
                        class="w-20 h-20 rounded-full border-4 border-white object-cover shadow-md">
                    <div class="ml-4 text-white">
                        <h1 class="text-2xl font-bold">{{ $shop->shop_name }}</h1>
                        <p class="text-sm opacity-90">Chủ shop: {{ $shop->owner->fullname ?? 'Chưa cập nhật' }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    @auth
                        @php
                            $isFollowing = auth()->user()->followedShops->contains($shop->id);
                        @endphp

                        @if ($isFollowing)
                            <form method="POST" action="{{ route('shop.unfollow', $shop->id) }}">
                                @csrf
                                <button
                                    class="flex items-center gap-1 border border-white rounded text-white text-[13px] px-3 py-[5px] hover:bg-white hover:text-[#5a4a1a] transition">
                                    <i class="fas fa-heart text-red-500"></i> Huỷ theo dõi
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('shop.follow', $shop->id) }}">
                                @csrf
                                <button
                                    class="flex items-center gap-1 border border-white rounded text-white text-[13px] px-3 py-[5px] hover:bg-white hover:text-[#5a4a1a] transition">
                                    <i class="fas fa-heart"></i> Theo dõi
                                </button>
                            </form>
                        @endif
                    @endauth
                    <button
                        class="flex items-center gap-1 border border-white rounded text-white text-[13px] px-3 py-[5px] hover:bg-white hover:text-[#5a4a1a] transition"
                        onclick="window.location.href='/chat?shop_id={{ $shop->id }}&product_id={{ $shop->id }}'">
                        <i class="far fa-comment-alt text-[13px]"></i> Nhắn tin
                    </button>
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-lg shadow-md p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm text-gray-700 mb-10">
            <div class="flex items-center gap-2"><i class="fas fa-box-open text-[#e03e2f]"></i> <span>Sản phẩm:
                    <strong>{{ $shop->products->count() }}</strong></span></div>
            <div class="flex items-center gap-2"><i class="fas fa-user-friends text-[#e03e2f]"></i>
                <p class="text-sm">
                    Người theo dõi: <strong>{{ $shop->followers->count() }}</strong>
                </p>
            </div>
            <div class="flex items-center gap-2"><i class="fas fa-user-check text-[#e03e2f]"></i> <span>Chủ shop:
                    <strong>{{ $shop->owner->fullname ?? 'Chưa cập nhật' }}</strong></span></div>
            <div class="flex items-center gap-2"><i class="far fa-envelope text-[#e03e2f]"></i> <span>Email:
                    <strong>{{ $shop->shop_email }}</strong></span></div>
            <div class="flex items-center gap-2"><i class="fas fa-phone-alt text-[#e03e2f]"></i> <span>SĐT:
                    <strong>{{ $shop->shop_phone }}</strong></span></div>
            <div class="flex items-center gap-2"><i class="fas fa-map-marker-alt text-[#e03e2f]"></i>
                <span>Địa chỉ:
                    <strong>{{ $shop->address->shop_address ?? 'Chưa cập nhật' }}</strong>
                </span>
            </div>
        </div>
        <!-- Danh mục sản phẩm của shop -->
        @if ($shop->categories && $shop->categories->count())
            <div class="mb-6">
                <h3 class="text-base font-semibold text-gray-700 mb-2">Danh mục sản phẩm</h3>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('shop.show', $shop->id) }}"
                        class="px-3 py-1 text-sm border rounded-full text-gray-700 hover:bg-[#e03e2f] hover:text-white transition">Tất
                        cả</a>
                    @foreach ($shop->categories as $category)
                        <a href="{{ route('shop.category', [$shop->id, $category->id]) }}"
                            class="px-3 py-1 text-sm border rounded-full text-gray-700 hover:bg-[#e03e2f] hover:text-white transition">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif


        <!-- Danh sách sản phẩm -->
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Sản phẩm từ shop</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @if (isset($products) && $products && $products->count() > 0)
                @foreach ($products as $product)
                    <a href="{{ route('product.show', $product->slug) }}"
                        class="bg-white border rounded-lg shadow-sm hover:shadow-md p-3 transition block">
                        <img src="{{ $product->images && $product->images->where('is_default', 1)->first() ? asset('storage/' . $product->images->where('is_default', 1)->first()->image_path) : ($product->images && $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('storage/product_images/default.jpg')) }}"
                            class="rounded-md w-full h-36 object-cover mb-2" alt="{{ $product->name }}">
                        <div class="font-semibold text-sm truncate">{{ $product->name }}</div>
                        <div class="text-[#e03e2f] font-bold text-sm">
                            {{ number_format($product->display_price ?? $product->price, 0, ',', '.') }}đ</div>
                        <div class="text-[11px] text-[#777] mt-1">
                            <i class="fas fa-star text-yellow-400"></i>
                            0.0 • Đã bán {{ $product->sold_quantity ?? 0 }}
                        </div>
                    </a>
                @endforeach
            @elseif($shop->products && $shop->products->count() > 0)
                @foreach ($shop->products as $product)
                    <a href="{{ route('product.show', $product->slug) }}"
                        class="bg-white border rounded-lg shadow-sm hover:shadow-md p-3 transition block">
                        <img src="{{ $product->images && $product->images->where('is_default', 1)->first() ? asset('storage/' . $product->images->where('is_default', 1)->first()->image_path) : ($product->images && $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('storage/product_images/default.jpg')) }}"
                            class="rounded-md w-full h-36 object-cover mb-2" alt="{{ $product->name }}">
                        <div class="font-semibold text-sm truncate">{{ $product->name }}</div>
                        <div class="text-[#e03e2f] font-bold text-sm">
                            {{ number_format($product->display_price ?? $product->price, 0, ',', '.') }}đ</div>
                        <div class="text-[11px] text-[#777] mt-1">
                            <i class="fas fa-star text-yellow-400"></i>
                            0.0 • Đã bán {{ $product->sold_quantity ?? 0 }}
                        </div>
                    </a>
                @endforeach
            @else
                <p class="text-gray-500 col-span-full text-center py-8">Không có sản phẩm nào.</p>
            @endif
        </div>

        <!-- Danh sách combo -->
        <h2 class="text-lg font-semibold text-gray-800 mb-4 mt-10">Combo từ shop</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @if ($shop->combos && $shop->combos->count() > 0)
                @foreach ($shop->combos as $combo)
                    <a href="{{ route('combo.show', $combo->id) }}"
                        class="bg-white border rounded-lg shadow-sm hover:shadow-md p-3 transition block">
                        @php
                            $comboImage = 'images/default.jpg';
                            if ($combo->products && $combo->products->count() > 0) {
                                $firstComboProduct = $combo->products->first();
                                if ($firstComboProduct && $firstComboProduct->product) {
                                    if (
                                        $firstComboProduct->product->images &&
                                        $firstComboProduct->product->images->count() > 0
                                    ) {
                                        $comboImage = asset(
                                            'storage/' . $firstComboProduct->product->images->first()->image_path,
                                        );
                                    }
                                }
                            }
                        @endphp
                        <img src="{{ $comboImage }}" class="rounded-md w-full h-36 object-cover mb-2"
                            alt="{{ $combo->combo_name }}">
                        <div class="font-semibold text-sm truncate">{{ $combo->combo_name }}</div>
                        <div class="text-[#e03e2f] font-bold text-sm">
                            {{ number_format($combo->total_price, 0, ',', '.') }}đ</div>
                        <div class="text-[11px] text-[#777] mt-1">
                            <i class="fas fa-star text-yellow-400"></i>
                            0.0 • Đã bán {{ $combo->sold_quantity ?? 0 }}
                        </div>
                    </a>
                @endforeach
            @else
                <p class="text-gray-500 col-span-full text-center py-8">Không có combo nào.</p>
            @endif
        </div>
    </div>

    @if(($shop->shop_status instanceof \App\Enums\ShopStatus ? $shop->shop_status->value : $shop->shop_status) === 'suspended')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Shop đang tạm ngưng bán',
                    text: 'Cửa hàng này đang tạm ngưng hoạt động. Bạn không thể mua sản phẩm này vào lúc này.',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    backdrop: true,
                    didOpen: () => {
                        document.querySelectorAll('button, a, input, select, textarea').forEach(el => {
                            el.disabled = true;
                            el.style.pointerEvents = 'none';
                        });
                    }
                });
            });
        </script>
    @endif
</body>

</html>
