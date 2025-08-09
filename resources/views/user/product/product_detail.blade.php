@extends('layouts.app')
@section('title', 'Chi tiết sản phẩm')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/user/product_detail.css') }}">
@endpush

@section('content')
    <div class="container mx-auto px-2 sm:px-4 py-4 sm:py-8">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 mb-4 sm:mb-6 text-xs sm:text-sm font-medium overflow-x-auto">
            <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 whitespace-nowrap">Trang chủ</a>
            <span class="text-gray-400">/</span>
            <a href="#" class="text-blue-600 hover:text-blue-800 whitespace-nowrap">Chi tiết sản phẩm</a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-800 font-semibold whitespace-nowrap">{{ \Illuminate\Support\Str::limit($product->name, 30, '...') }}</span>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-4 gap-4 sm:gap-6 product-grid">
            <!-- Cột chính (Hình ảnh + Thông tin sản phẩm) -->
            <div class="xl:col-span-3">
                <!-- Hình ảnh và thông tin sản phẩm -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 bg-white rounded-lg p-3 sm:p-6 shadow">
                    <!-- Hình ảnh sản phẩm -->
                    <div class="relative">
                        <img id="main-image"
                            src="{{ $product->images->where('is_default', 1)->first() ? asset('storage/' . $product->images->where('is_default', 1)->first()->image_path) : ($product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('storage/product_images/default.jpg')) }}"
                            alt="{{ $product->name }}"
                            class="w-full h-64 sm:h-80 lg:h-96 object-cover rounded-lg transform transition-transform duration-300 hover:scale-105 product-main-image"
                            loading="lazy">
                        <div class="flex gap-2 mt-3 sm:mt-4 overflow-x-auto pb-2 product-thumbnails">
                            @foreach ($product->images as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Ảnh phụ {{ $product->name }}"
                                    class="w-16 h-16 sm:w-[100px] sm:h-[100px] object-cover rounded cursor-pointer hover:opacity-90 transition-opacity sub-image flex-shrink-0 product-thumbnail"
                                    onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}')"
                                    loading="lazy">
                            @endforeach
                            @if ($product->images->isEmpty())
                                <img src="{{ asset('storage/product_images/default.jpg') }}" alt="Ảnh mặc định"
                                    class="w-16 h-16 sm:w-[100px] sm:h-[100px] object-cover rounded cursor-pointer sub-image flex-shrink-0 product-thumbnail"
                                    loading="lazy">
                            @endif
                        </div>
                    </div>

                    <!-- Thông tin sản phẩm -->
                    <div class="flex flex-col gap-4 sm:gap-6">
                        <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 product-title" title="{{ $product->name }}">
                            {{ \Illuminate\Support\Str::limit($product->name, 50, '...') }}
                        </h2>
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                                <span class="text-yellow-400 flex">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= round($product->orderReviews->avg('rating')))
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2.25l2.82 5.73h5.88l-4.77 4.12 1.82 5.73-5.73-3.49-5.73 3.49 1.82-5.73-4.77-4.12h5.88z" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2.25l2.82 5.73h5.88l-4.77 4.12 1.82 5.73-5.73-3.49-5.73 3.49 1.82-5.73-4.77-4.12h5.88z" />
                                            </svg>
                                        @endif
                                    @endfor
                                </span>
                                <span class="text-xs sm:text-sm text-gray-600">
                                    ({{ $product->orderReviews->count() }} đánh giá) | Đã bán:
                                    {{ $product->sold_quantity >= 1000 ? number_format($product->sold_quantity / 1000, 1) . 'k' : $product->sold_quantity }}
                                </span>
                            </div>
                            <div class="relative">
                                <button id="menuButton" class="p-2 rounded hover:bg-gray-100">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div id="menuDropdown"
                                    class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg hidden z-10">
                                    <button id="wishlistBtn"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-red-600 w-full text-left wishlist-btn"
                                        data-product-id="{{ $product->id }}"
                                        data-is-wishlisted="{{ $isWishlisted ? 'true' : 'false' }}">
                                        <span
                                            class="wishlist-text">{{ $isWishlisted ? 'Bỏ yêu thích' : 'Yêu thích' }}</span>
                                        <i class="fas fa-heart ml-1 {{ $isWishlisted ? 'text-red-600' : '' }}"></i>
                                    </button>
                                    <button
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-red-600"
                                        id="reportBtn">
                                        Báo cáo <i class="fas fa-exclamation-triangle ml-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-4 flex-wrap" id="price-display">
                            @if ($product->variants->isNotEmpty())
                                <!-- Sản phẩm có biến thể, lấy giá từ biến thể đầu tiên làm mặc định -->
                                <span class="text-red-600 text-xl sm:text-2xl lg:text-3xl font-bold">
                                    {{ number_format($product->variants->first()->sale_price ?: $product->sale_price, 0, ',', '.') }}
                                    VNĐ
                                </span>
                                @if ($product->variants->first()->price > 0)
                                    <span class="text-gray-500 line-through text-base sm:text-lg">
                                        {{ number_format($product->variants->first()->price ?: $product->price, 0, ',', '.') }}
                                        VNĐ
                                    </span>
                                    <span class="bg-red-100 text-red-600 px-2 sm:px-3 py-1 rounded text-xs sm:text-sm">
                                        -{{ round((($product->variants->first()->price - $product->variants->first()->sale_price) / $product->variants->first()->price) * 100) ?: 0 }}%
                                    </span>
                                @endif
                            @else
                                <!-- Sản phẩm đơn, lấy giá từ bảng products -->
                                <span class="text-red-600 text-xl sm:text-2xl lg:text-3xl font-bold">
                                    {{ number_format($product->sale_price, 0, ',', '.') }} VNĐ
                                </span>
                                @if ($product->price > 0)
                                    <span class="text-gray-500 line-through text-base sm:text-lg">
                                        {{ number_format($product->price, 0, ',', '.') }} VNĐ
                                    </span>
                                    <span class="bg-red-100 text-red-600 px-2 sm:px-3 py-1 rounded text-xs sm:text-sm">
                                        -{{ round((($product->price - $product->sale_price) / $product->price) * 100) ?: 0 }}%
                                    </span>
                                @endif
                            @endif
                        </div>
                        <p class="text-gray-700 text-sm sm:text-base leading-relaxed">{!! $product->meta_description !!}</p>

                        <!-- Thuộc tính của sản phẩm -->
                        <div class="flex flex-col gap-4 sm:gap-6">
                            @php
                                // Lấy tất cả các thuộc tính duy nhất từ attribute_values của các biến thể
                                $attributes = $product->variants->isNotEmpty()
                                    ? $product->variants->flatMap->attributeValues
                                        ->groupBy('attribute.name')
                                        ->map->pluck('value')
                                        ->map->unique()
                                    : collect();
                            @endphp

                            @foreach ($attributes as $attributeName => $values)
                                @if ($values->isNotEmpty())
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4" id="{{ Str::slug($attributeName, '-') }}-options">
                                        <span class="text-gray-800 font-medium text-sm sm:text-base">{{ $attributeName }}:</span>
                                        <div class="flex gap-2 flex-wrap">
                                            @foreach ($values as $value)
                                                @php
                                                    $variant = $product->variants->firstWhere(function ($v) use (
                                                        $value,
                                                        $attributeName,
                                                    ) {
                                                        return $v->attributeValues
                                                            ->where('attribute.name', $attributeName)
                                                            ->where('value', $value)
                                                            ->isNotEmpty();
                                                    });
                                                    $variantId = $variant ? $variant->id : null;
                                                    $stock = $variantId
                                                        ? $variantData[$variantId]['stock']
                                                        : $defaultStock;
                                                @endphp
                                                <button
                                                    class="border border-gray-300 rounded-lg px-2 sm:px-4 py-2 flex items-center gap-2 hover:bg-gray-100 transition-colors text-sm sm:text-base"
                                                    data-value="{{ $value }}"
                                                    data-price="{{ $variantId ? $variantData[$variantId]['price'] : $product->sale_price }}"
                                                    data-stock="{{ $stock }}" data-variant-id="{{ $variantId }}"
                                                    data-attribute-name="{{ $attributeName }}">
                                                    @if (isset($attributeImages[$attributeName][$value]))
                                                        <img src="{{ $attributeImages[$attributeName][$value] }}"
                                                            width="20" height="20" class="rounded w-5 h-5 sm:w-6 sm:h-6" loading="lazy">
                                                    @endif
                                                    <span>{{ $value }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <p class="text-gray-600 text-sm sm:text-base">Không có tùy chọn {{ $attributeName }}.</p>
                                @endif
                            @endforeach
                        </div>

                        <!-- Số lượng và biến thể được chọn -->
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-6 mt-4">
                            <span class="text-gray-800 font-medium text-sm sm:text-base">Số lượng:</span>
                            <form action="{{ route('cart.add') }}" method="POST" class="flex items-center">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="variant_id" id="selected_variant_id"
                                    value="{{ $selectedVariant ? $selectedVariant->id : ($product->variants->isEmpty() ? 'default' : '') }}">
                                <button type="button" id="decreaseQty"
                                    class="border border-gray-300 px-3 sm:px-4 py-2 rounded-l-lg hover:bg-gray-100 text-base sm:text-lg">-</button>
                                <input type="text" name="quantity" id="quantity" value="1"
                                    class="w-16 sm:w-20 text-center px-2 sm:px-3 py-2 border-t border-b border-gray-300 focus:outline-none text-base sm:text-lg">
                                <button type="button" id="increaseQty"
                                    class="border border-gray-300 px-3 sm:px-4 py-2 rounded-r-lg hover:bg-gray-100 text-base sm:text-lg">+</button>
                            </form>
                            <span class="text-xs sm:text-sm text-gray-600" id="stock_info">
                                {{ $defaultStock }} sản phẩm có sẵn
                            </span>
                        </div>

                        <!-- Nút hành động -->
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 mt-4 sm:mt-6">
                            <button
                                class="bg-red-600 text-white px-4 sm:px-6 py-3 rounded-lg hover:bg-red-700 flex items-center justify-center gap-2 add-to-cart text-sm sm:text-base"
                                data-product-id="{{ $product->id }}">
                                <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ
                            </button>
                            <button id="instant_buy_btn"
                                class="bg-black text-white px-4 sm:px-6 py-3 rounded-lg hover:bg-gray-700 text-sm sm:text-base">Mua ngay</button>
                        </div>
                    </div>
                </div>

                <!-- Mô tả sản phẩm -->
                <div class="bg-white rounded-lg p-3 sm:p-6 mt-4 sm:mt-6 shadow">
                    <h3 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4 text-gray-800">Mô tả sản phẩm</h3>
                    <div class="text-gray-700 text-sm sm:text-base">
                        <div class="description-content">
                            <span id="shortDescription" class="relative">
                                <div class="h-[150px] sm:h-[200px] overflow-hidden">
                                    {!! $product->description !!}
                                </div>
                                <div class="w-full"
                                    style="position: absolute; bottom: 0; left: 0; height: 60px; background-image: linear-gradient(rgba(255, 255, 255, 0), rgb(255, 255, 255));">
                                </div>
                            </span>
                            <div id="fullDescription" class="hidden">{!! $product->description !!}</div>
                        </div>
                        <div class="flex justify-center mt-4 sm:mt-5">
                            <button id="readMore"
                                class="text-red-600 px-3 sm:px-4 py-2 rounded text-sm hover:bg-red-100 transition-all duration-300">
                                Xem thêm
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Đánh giá sản phẩm -->
                <div class="bg-white rounded-lg p-3 sm:p-6 mt-4 sm:mt-6 shadow">
                    <h3 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4 text-gray-800">Đánh giá sản phẩm</h3>

                    @if ($filteredReviews->isEmpty())
                        <p class="text-gray-600 text-center text-sm sm:text-base">Chưa có đánh giá nào cho sản phẩm này.</p>
                    @else
                        <div class="flex flex-col lg:flex-row bg-red-100/50 p-3 sm:p-4 py-6 sm:py-10 rounded-lg gap-6 sm:gap-10">
                            <div class="flex flex-wrap items-center justify-center gap-3 w-full lg:w-[200px]">
                                <div class="flex flex-col items-center">
                                    <div class="flex items-baseline space-x-1">
                                        <span
                                            class="text-red-600 text-2xl sm:text-[28px] font-light leading-none">{{ $averageRating }}</span>
                                        <span class="text-red-500 text-sm sm:text-[16px] font-light leading-none">trên 5</span>
                                    </div>
                                    <div class="flex space-x-[2px] mt-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="fas fa-star {{ $i <= round($averageRating) ? 'text-[#e94e1b]' : 'text-gray-300' }} text-base sm:text-[18px]"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 justify-center lg:justify-start">
                                <button
                                    class="filter-btn {{ !$filter ? 'text-[#e94e1b] border-[#e94e1b]' : 'text-[#333] border-[#ddd]' }} border rounded px-2 sm:px-3 py-1 text-xs sm:text-[13px] leading-none font-normal"
                                    data-filter="all">
                                    Tất Cả ({{ number_format($totalReviews, 0, ',', '.') }})
                                </button>
                                @foreach ([5, 4, 3, 2, 1] as $star)
                                    <button
                                        class="filter-btn {{ $filter == 'star-' . $star ? 'text-[#e94e1b] border-[#e94e1b]' : 'text-[#333] border-[#ddd]' }} border rounded px-2 sm:px-3 py-1 text-xs sm:text-[13px] leading-none font-normal"
                                        data-filter="star-{{ $star }}">
                                        {{ $star }} Sao ({{ number_format($ratingCounts[$star], 0, ',', '.') }})
                                    </button>
                                @endforeach
                                <button
                                    class="filter-btn {{ $filter == 'comments' ? 'text-[#e94e1b] border-[#e94e1b]' : 'text-[#333] border-[#ddd]' }} border rounded px-2 sm:px-3 py-1 text-xs sm:text-[13px] leading-none font-normal"
                                    data-filter="comments">
                                    Có Bình Luận ({{ number_format($commentCount, 0, ',', '.') }})
                                </button>
                                <button
                                    class="filter-btn {{ $filter == 'images' ? 'text-[#e94e1b] border-[#e94e1b]' : 'text-[#333] border-[#ddd]' }} border rounded px-2 sm:px-3 py-1 text-xs sm:text-[13px] leading-none font-normal"
                                    data-filter="images">
                                    Có Hình Ảnh / Video ({{ number_format($mediaCount, 0, ',', '.') }})
                                </button>
                                <button
                                    class="filter-btn {{ $filter == 'most-liked' ? 'text-[#e94e1b] border-[#e94e1b]' : 'text-[#333] border-[#ddd]' }} border rounded px-2 sm:px-3 py-1 text-xs sm:text-[13px] leading-none font-normal"
                                    data-filter="most-liked">
                                    Được thích nhiều nhất ({{ number_format($mostLikedCount, 0, ',', '.') }})
                                </button>
                            </div>
                        </div>
                        <div id="reviewList" class="mt-4 sm:mt-5">
                            @include('partials.review_list', ['reviews' => $filteredReviews])
                            {{ $filteredReviews->appends(['filter' => $filter])->links() }}
                        </div>
                        
                    @endif
                </div>

            </div>

            <!-- Cột bên phải (Thông tin shop) -->
            <div class="xl:col-span-1">
                <div class="sticky top-5">
                    <div class="bg-white rounded-lg p-3 sm:p-6 shadow">
                        <h2 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4 border-b pb-2 text-gray-800">Cửa hàng</h2>
                        <div class="flex items-center gap-3 sm:gap-4 mb-3 sm:mb-4">
                            <img src="{{ $product->shop ? ($product->shop->shop_logo ? Storage::url($product->shop->shop_logo) : Storage::url('shop_logos/default_shop_logo.png')) : Storage::url('shop_logos/default_shop_logo.png') }}"
                                alt="Logo Shop" class="w-12 h-12 sm:w-16 sm:h-16 rounded-full object-cover border flex-shrink-0" loading="lazy">
                            <div class="min-w-0 flex-1">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">
                                    {{ $product->shop ? $product->shop->shop_name : 'Tên Shop Không Xác Định' }}
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-600">
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
                        <div class="flex flex-col sm:flex-row justify-center gap-2 sm:gap-3">
                            <button
                                class="bg-red-600 text-white px-4 sm:px-5 py-2 rounded-lg hover:bg-red-700 flex items-center justify-center gap-2 text-sm sm:text-base">
                                <i class="fa-solid fa-comment"></i> Nhắn tin
                            </button>
                            <a href="{{ route('shop.profile', $product->shop->id) }}"
                                class="border border-gray-300 px-4 sm:px-5 py-2 rounded-lg hover:bg-gray-100 text-center text-sm sm:text-base">Xem
                                cửa hàng
                            </a>
                        </div>
                    </div>

                    <!-- Voucher shop -->
                    @if ($shop && $shop->coupons->where('status', 'active')->count() > 0)
                        <div class="bg-white rounded-lg p-3 sm:p-6 shadow mt-4 sm:mt-6">
                            <h2 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4 border-b pb-2 text-gray-800">Voucher Cửa hàng</h2>
                            <div class="flex flex-col gap-3 sm:gap-4 mb-3 sm:mb-4">
                                @foreach ($shop->coupons->where('status', 'active') as $coupon)
                                    <div
                                        class="flex justify-between items-center border border-dashed px-3 sm:px-4 py-2 sm:py-3 rounded text-xs sm:text-sm">
                                        <div class="min-w-0 flex-1">
                                            <div class="text-red-600 font-semibold">
                                                Giảm
                                                {{ $coupon->discount_type === 'percentage' ? $coupon->discount_value . '%' : number_format($coupon->discount_value) . 'đ' }}
                                            </div>
                                            @if ($coupon->min_order_amount)
                                                <div class="truncate">Đơn tối thiểu {{ number_format($coupon->min_order_amount) }}đ</div>
                                            @endif
                                            <div class="text-gray-500 italic text-xs">HSD:
                                                {{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}</div>
                                        </div>
                                        <button
                                            class="bg-black text-white px-3 sm:px-4 py-1 sm:py-1.5 rounded hover:bg-gray-800 save-coupon-btn text-xs sm:text-sm ml-2 flex-shrink-0"
                                            data-coupon-id="{{ $coupon->id }}"
                                            data-is-saved="{{ in_array($coupon->id, $savedCoupons) ? 'true' : 'false' }}"
                                            {{ in_array($coupon->id, $savedCoupons) ? 'disabled' : '' }}>
                                            {{ in_array($coupon->id, $savedCoupons) ? 'Đã lưu' : 'Lưu' }}
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex justify-end gap-2">
                                <button
                                    class="bg-black text-white rounded hover:bg-gray-800 flex items-center gap-2 save-all-coupons-btn text-xs sm:text-sm"
                                    data-shop-id="{{ $shop->id }}">
                                    <div class="border-r border-white border-dashed px-3 sm:px-4 h-full flex items-center">
                                        <i class="fa-solid fa-ticket"></i>
                                    </div>
                                    <div class="pr-3 sm:pr-4 pl-2 py-2">
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
        <div id="reportProductModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg p-4 sm:p-6 w-full max-w-md shadow-xl">
                <h3 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4 text-gray-800">Báo cáo sản phẩm</h3>
                <form action="{{ route('product.report', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="shop_id" value="{{ $product->shopID }}">
                    <div class="mb-3 sm:mb-4">
                        <label for="report_type" class="block text-sm font-medium text-gray-700">Loại vi phạm</label>
                        <select name="report_type" id="report_type"
                            class="mt-1 block w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="fake_product">Sản phẩm giả nhái</option>
                            <option value="product_violation">Vi phạm chính sách sản phẩm</option>
                            <option value="copyright">Vi phạm bản quyền</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                    <div class="mb-3 sm:mb-4">
                        <label for="report_content" class="block text-sm font-medium text-gray-700">Nội dung báo
                            cáo</label>
                        <textarea name="report_content" id="report_content" rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Mô tả chi tiết vi phạm"></textarea>
                    </div>
                    <div class="mb-3 sm:mb-4">
                        <label for="evidence" class="block text-sm font-medium text-gray-700">Bằng chứng</label>
                        <input type="file" name="evidence[]" id="evidence" multiple
                            class="mt-1 block w-full text-sm">
                    </div>
                    <div class="mb-3 sm:mb-4 flex items-center">
                        <input type="checkbox" name="is_anonymous" id="is_anonymous" class="h-4 w-4 text-blue-600">
                        <label for="is_anonymous" class="ml-2 text-sm text-gray-700">Báo cáo ẩn danh</label>
                    </div>
                    <div class="flex justify-end gap-2 sm:gap-3">
                        <button type="button" id="cancelReportBtn"
                            class="px-3 sm:px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 text-sm">Hủy</button>
                        <button type="submit"
                            class="px-3 sm:px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">Gửi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal chọn biến thể cho mua ngay -->
    <div id="variantSelectionModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg p-4 sm:p-6 w-full max-w-2xl shadow-xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-800">Chọn biến thể sản phẩm</h3>
                <button type="button" id="closeVariantModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="mb-4">
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ $product->images->where('is_default', 1)->first() ? asset('storage/' . $product->images->where('is_default', 1)->first()->image_path) : ($product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('storage/product_images/default.jpg')) }}" 
                         alt="{{ $product->name }}" 
                         class="w-16 h-16 object-cover rounded-lg">
                    <div>
                        <h4 class="font-medium text-gray-900">{{ $product->name }}</h4>
                        <p class="text-sm text-gray-600">Vui lòng chọn biến thể để mua ngay</p>
                    </div>
                </div>
            </div>

            @if($product->variants->isNotEmpty())
                @foreach($product->attributes as $attribute)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ $attribute->name }}</label>
                        <div class="flex flex-wrap gap-2" id="modal-{{ $attribute->id }}-options">
                            @foreach($attribute->values as $value)
                                @php
                                    $variant = $product->variants->first(function($v) use ($attribute, $value) {
                                        $avs = $v->attributeValues ?? collect();
                                        return $avs->contains(function($av) use ($attribute, $value) {
                                            return (int) ($av->attribute_id ?? 0) === (int) $attribute->id && (string) ($av->value ?? '') === (string) $value->value;
                                        });
                                    });
                                    $stock = $variant ? $variant->stock : 0;
                                    $isAvailable = $stock > 0;
                                @endphp
                                <button type="button" 
                                        class="variant-option-modal border border-gray-300 rounded-lg px-3 py-2 text-sm transition-colors {{ $isAvailable ? 'hover:bg-gray-50 cursor-pointer' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}"
                                        data-value="{{ $value->value }}"
                                        data-attribute-name="{{ $attribute->name }}"
                                        data-stock="{{ $stock }}"
                                        data-variant-id="{{ $variant ? $variant->id : '' }}"
                                        {{ !$isAvailable ? 'disabled' : '' }}>
                                    {{ $value->value }}
                                    @if(!$isAvailable)
                                        <span class="text-xs text-gray-400 block">Hết hàng</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Số lượng</label>
                    <div class="flex items-center gap-3">
                        <button type="button" id="modalDecreaseQty" class="border border-gray-300 px-3 py-2 rounded-l-lg hover:bg-gray-100 text-lg">-</button>
                        <input type="text" id="modalQuantity" value="1" class="w-20 text-center px-3 py-2 border-t border-b border-gray-300 focus:outline-none text-lg">
                        <button type="button" id="modalIncreaseQty" class="border border-gray-300 px-3 py-2 rounded-r-lg hover:bg-gray-100 text-lg">+</button>
                    </div>
                    <div class="mt-2">
                        <span id="modalStockInfo" class="text-sm text-gray-600">Chọn biến thể để xem tồn kho</span>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Tổng tiền:</span>
                        <span id="modalTotalPrice" class="text-lg font-bold text-red-600">Chọn biến thể để xem giá</span>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-600">Sản phẩm này không có biến thể</p>
                </div>
            @endif

            <div class="flex justify-end gap-3">
                <button type="button" id="cancelVariantSelection" 
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">Hủy</button>
                <button type="button" id="confirmVariantSelection" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    Xác nhận mua ngay
                </button>
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
                const quantityInput = document.getElementById('quantity');
                const decreaseBtn = document.getElementById('decreaseQty');
                const increaseBtn = document.getElementById('increaseQty');
                const menuButton = document.getElementById('menuButton');
                const menuDropdown = document.getElementById('menuDropdown');
                const reportBtn = document.getElementById('reportBtn');
                const wishlistBtn = document.getElementById('wishlistBtn');
                const reportModal = document.getElementById('reportProductModal');
                const cancelReportBtn = document.getElementById('cancelReportBtn');
                const readMore = document.getElementById('readMore');
                const shortDescription = document.getElementById('shortDescription');
                const fullDescription = document.getElementById('fullDescription');
                const descriptionContent = document.querySelector('.description-content');
                const stars = document.querySelectorAll('.star');
                const ratingInput = document.getElementById('ratingInput');
                const saveCouponButtons = document.querySelectorAll('.save-coupon-btn');
                const saveAllCouponsBtn = document.querySelector('.save-all-coupons-btn');
                const reviewForm = document.getElementById('reviewForm');
                
                // Modal chọn biến thể
                const variantSelectionModal = document.getElementById('variantSelectionModal');
                const closeVariantModal = document.getElementById('closeVariantModal');
                const cancelVariantSelection = document.getElementById('cancelVariantSelection');
                const confirmVariantSelection = document.getElementById('confirmVariantSelection');
                const modalQuantity = document.getElementById('modalQuantity');
                const modalDecreaseQty = document.getElementById('modalDecreaseQty');
                const modalIncreaseQty = document.getElementById('modalIncreaseQty');
                const modalStockInfo = document.getElementById('modalStockInfo');
                const modalTotalPrice = document.getElementById('modalTotalPrice');
                const variantOptionModal = document.querySelectorAll('.variant-option-modal');
                
                let modalSelectedVariantId = null;
                let modalSelectedVariant = null;

                // Initialize selectedVariantId and selectedVariantIdInput if product has variants
                @if ($product->variants->isNotEmpty() && $selectedVariant)
                    selectedVariantId = '{{ $selectedVariant->id }}';
                    selectedVariantIdInput.value = '{{ $selectedVariant->id }}';
                @endif

                // Format số
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

                window.changeMainImage = function(src) {
                    mainImage.src = src;
                };

                function resetToDefault() {
                    selectedVariantId = null;
                    selectedVariantIdInput.value = '';
                    mainImage.src =
                        '{{ $product->images->where('is_default', 1)->first() ? asset('storage/' . $product->images->where('is_default', 1)->first()->image_path) : ($product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('storage/product_images/default.jpg')) }}';

                    @if ($product->variants->isNotEmpty())
                        const defaultPrice = {{ $product->variants->first()->sale_price ?: 0 }};
                        const defaultOriginalPrice = {{ $product->variants->first()->price ?: 0 }};
                        const defaultDiscount = defaultOriginalPrice > 0 ? Math.round(((defaultOriginalPrice -
                            defaultPrice) / defaultOriginalPrice) * 100) : 0;
                        priceDisplay.innerHTML = `
                        <span class="text-red-600 text-xl sm:text-2xl lg:text-3xl font-bold">${number_format(defaultPrice, 0, ',', '.')} VNĐ</span>
                        ${defaultOriginalPrice > 0 ? `<span class="text-gray-500 line-through text-base sm:text-lg">${number_format(defaultOriginalPrice, 0, ',', '.')} VNĐ</span>` : ''}
                        ${defaultDiscount > 0 ? `<span class="bg-red-100 text-red-600 px-2 sm:px-3 py-1 rounded text-xs sm:text-sm>${defaultDiscount}%</span>` : ''}
                    `;
                        const defaultStock = {{ $defaultStock }}; // Sử dụng defaultStock từ controller
                    @else
                        const defaultPrice = {{ $product->sale_price }};
                        const defaultOriginalPrice = {{ $product->price }};
                        const defaultDiscount = defaultOriginalPrice > 0 ? Math.round(((defaultOriginalPrice -
                            defaultPrice) / defaultOriginalPrice) * 100) : 0;
                        priceDisplay.innerHTML = `
                            <span class="text-red-600 text-xl sm:text-2xl lg:text-3xl font-bold">${number_format(defaultPrice, 0, ',', '.')} VNĐ</span>
                            ${defaultOriginalPrice > 0 ? `<span class="text-gray-500 line-through text-base sm:text-lg">${number_format(defaultOriginalPrice, 0, ',', '.')} VNĐ</span>` : ''}
                            ${defaultDiscount > 0 ? `<span class="bg-red-100 text-red-600 px-2 sm:px-3 py-1 rounded text-xs sm:text-sm>${defaultDiscount}%</span>` : ''}
                        `;
                        const defaultStock = {{ $product->stock_total }};
                    @endif
                    stockInfo.textContent = `${defaultStock} sản phẩm có sẵn`;
                }

                // Xử lý chọn biến thể
                variantButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const value = this.getAttribute('data-value');
                        const attributeName = this.getAttribute('data-attribute-name');
                        const optionsContainer = this.closest(`[id$="-options"]`);
                        const allButtons = optionsContainer.querySelectorAll('button[data-value]');

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

                        const selectedAttributes = {};
                        document.querySelectorAll('[id$="-options"] button[data-value].bg-gray-200')
                            .forEach(btn => {
                                const attrName = btn.getAttribute('data-attribute-name');
                                const attrValue = btn.getAttribute('data-value');
                                selectedAttributes[attrName] = attrValue;
                            });

                        const variants = @json($product->variants->toArray(), JSON_HEX_TAG | JSON_HEX_AMP);

                        const variant = variants.find(v => {
                            const attrs = (v.attribute_values || []).map(a => ({
                                name: a.attribute ? a.attribute.name : '',
                                value: a.value || ''
                            }));
                            return Object.entries(selectedAttributes).every(([name, value]) => {
                                return attrs.some(a => a.name === name && a.value ===
                                    value);
                            });
                        });

                        if (variant) {
                            selectedVariantId = variant.id;
                            selectedVariantIdInput.value = variant.id;

                            const imagePath = (variant.images && variant.images.length > 0) ? variant
                                .images[0].image_path :
                                '{{ $product->images->where('is_default', 1)->first() ? asset('storage/' . $product->images->where('is_default', 1)->first()->image_path) : ($product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('storage/product_images/default.jpg')) }}';
                            mainImage.src = '{{ asset('storage/') }}/' + imagePath;

                            const price = variant.sale_price || variant.price || 0;
                            const originalPrice = variant.price || 0;
                            const discount = originalPrice > 0 ? Math.round(((originalPrice - price) /
                                originalPrice) * 100) : 0;
                            priceDisplay.innerHTML = `
                            <span class="text-red-600 text-xl sm:text-2xl lg:text-3xl font-bold">${number_format(price, 0, ',', '.')} VNĐ</span>
                            ${originalPrice > 0 ? `<span class="text-gray-500 line-through text-base sm:text-lg">${number_format(originalPrice, 0, ',', '.')} VNĐ</span>` : ''}
                            ${discount > 0 ? `<span class="bg-red-100 text-red-600 px-2 sm:px-3 py-1 rounded text-xs sm:text-sm>${discount}%</span>` : ''}
                        `;

                            // Sử dụng stock từ data-stock của nút
                            const stock = parseInt(this.getAttribute('data-stock')) ||
                                {{ $defaultStock }};
                            stockInfo.textContent = `${stock} sản phẩm có sẵn`;
                        } else {
                            resetToDefault();
                        }
                    });
                });

                // Xử lý thêm vào giỏ hàng
                addToCartButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        if (!selectedVariantId &&
                            {{ $product->variants->count() > 0 ? 'true' : 'false' }}) {
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

                        const quantity = parseInt(quantityInput.value);
                        const stock = selectedVariantId ? parseInt(stockInfo.textContent.split(' ')[
                                0]) : // Use the stock from selected variant if available
                            {{ $defaultStock }}; // Fallback to product total stock if no variant selected

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

                // Xử lý nút Mua ngay
                const instantBuyBtn = document.getElementById('instant_buy_btn');
                if (instantBuyBtn) {
                    instantBuyBtn.addEventListener('click', () => {
                        @if($product->variants->isNotEmpty())
                            // Nếu sản phẩm có biến thể: yêu cầu đã chọn biến thể, sau đó mua trực tiếp
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

                            const quantity = parseInt(quantityInput.value);
                            const stock = selectedVariantId ? parseInt(stockInfo.textContent.split(' ')[0]) : {{ $defaultStock }};

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

                            window.location.href = `/customer/direct-checkout?product_id={{ $product->id }}&variant_id=${selectedVariantId}&quantity=${quantity}`;
                        @else
                            // Nếu sản phẩm đơn, mua trực tiếp
                            const quantity = parseInt(quantityInput.value);
                            const stock = {{ $product->stock_total }};
                            
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
                            
                            // Chuyển hướng đến trang checkout
                            window.location.href = `/customer/direct-checkout?product_id={{ $product->id }}&quantity=${quantity}`;
                        @endif
                    });
                }

                // Tăng/giảm số lượng
                decreaseBtn.addEventListener('click', () => {
                    let qty = parseInt(quantityInput.value);
                    if (qty > 1) quantityInput.value = qty - 1;
                });

                increaseBtn.addEventListener('click', () => {
                    let qty = parseInt(quantityInput.value);
                    const stock = parseInt(stockInfo.textContent.split(' ')[0]) || {{ $defaultStock }};
                    if (qty < stock) quantityInput.value = qty + 1;
                });

                // Menu dropdown
                menuButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    menuDropdown.classList.toggle('hidden');
                });

                document.addEventListener('click', (e) => {
                    if (!menuButton.contains(e.target) && !menuDropdown.contains(e.target)) {
                        menuDropdown.classList.add('hidden');
                    }
                });

                // Xử lý yêu thích sản phẩm
                if (wishlistBtn) {
                    wishlistBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const productId = wishlistBtn.getAttribute('data-product-id');
                        const isWishlisted = wishlistBtn.getAttribute('data-is-wishlisted') === 'true';

                        fetch(`/customer/product/${productId}/toggle-wishlist`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json',
                                },
                            })
                            .then(response => {
                                if (!response.ok) {
                                    if (response.status === 401) {
                                        return response.json().then(data => {
                                            throw new Error(data.message);
                                        });
                                    }
                                    throw new Error(`HTTP error! Status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                wishlistBtn.setAttribute('data-is-wishlisted', data.isWishlisted);
                                wishlistBtn.querySelector('.wishlist-text').textContent = data
                                    .isWishlisted ? 'Bỏ yêu thích' : 'Yêu thích';
                                wishlistBtn.querySelector('.fa-heart').classList.toggle('text-red-600', data
                                    .isWishlisted);

                                Swal.fire({
                                    position: 'top-end',
                                    toast: true,
                                    icon: 'success',
                                    title: data.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                menuDropdown.classList.add('hidden');
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    position: 'top-end',
                                    toast: true,
                                    icon: 'error',
                                    title: 'Lỗi',
                                    text: error.message || 'Không thể thực hiện hành động!',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            });
                    });
                }

                // Xử lý mở modal báo cáo
                if (reportBtn) {
                    reportBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        reportModal.classList.remove('hidden');
                        menuDropdown.classList.add('hidden');
                    });
                }

                // Modal báo cáo
                if (cancelReportBtn) {
                    cancelReportBtn.addEventListener('click', () => reportModal.classList.add('hidden'));
                }
                if (reportModal) {
                    reportModal.addEventListener('click', (e) => {
                        if (e.target === reportModal) reportModal.classList.add('hidden');
                    });
                }

                // Xử lý lưu voucher
                saveCouponButtons.forEach(button => {
                    button.addEventListener('click', (e) => {
                        e.preventDefault();
                        const couponId = button.getAttribute('data-coupon-id');
                        const isSaved = button.getAttribute('data-is-saved') === 'true';

                        if (isSaved) {
                            Swal.fire({
                                position: 'top-end',
                                toast: true,
                                icon: 'info',
                                title: 'Voucher đã được lưu!',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            return;
                        }

                        fetch(`/customer/coupon/${couponId}/save`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json',
                                },
                            })
                            .then(response => {
                                if (!response.ok) {
                                    if (response.status === 401) {
                                        return response.json().then(data => {
                                            throw new Error(data.message);
                                        });
                                    }
                                    throw new Error(`HTTP error! Status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                button.setAttribute('data-is-saved', 'true');
                                button.textContent = 'Đã lưu';
                                button.disabled = true;
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
                                    text: error.message || 'Không thể lưu voucher!',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            });
                    });
                });

                // Xử lý lưu tất cả voucher
                if (saveAllCouponsBtn) {
                    saveAllCouponsBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const shopId = saveAllCouponsBtn.getAttribute('data-shop-id');

                        fetch(`/customer/shop/${shopId}/save-all-coupons`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json',
                                },
                            })
                            .then(response => {
                                if (!response.ok) {
                                    if (response.status === 401) {
                                        return response.json().then(data => {
                                            throw new Error(data.message);
                                        });
                                    }
                                    throw new Error(`HTTP error! Status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                saveCouponButtons.forEach(button => {
                                    button.setAttribute('data-is-saved', 'true');
                                    button.textContent = 'Đã lưu';
                                    button.disabled = true;
                                });
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
                                    text: error.message || 'Không thể lưu tất cả voucher!',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            });
                    });
                }

                // Xử lý gửi đánh giá
                if (reviewForm) {
                    reviewForm.addEventListener('submit', (e) => {
                        e.preventDefault();
                        const formData = new FormData(reviewForm);

                        fetch(reviewForm.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json',
                                },
                                body: formData
                            })
                            .then(response => {
                                if (!response.ok) {
                                    if (response.status === 401 || response.status === 403) {
                                        return response.json().then(data => {
                                            throw new Error(data.message);
                                        });
                                    }
                                    throw new Error(`HTTP error! Status: ${response.status}`);
                                }
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
                                reviewForm.reset();
                                reviewForm.classList.add('hidden'); // Ẩn form sau khi gửi
                                stars.forEach(star => star.classList.add('text-gray-300')); // Reset sao
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    position: 'top-end',
                                    toast: true,
                                    icon: 'error',
                                    title: 'Lỗi',
                                    text: error.message || 'Không thể gửi đánh giá!',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            });
                    });
                }

                // Xử lý modal chọn biến thể
                function resetModalVariantSelection() {
                    modalSelectedVariantId = null;
                    modalSelectedVariant = null;
                    modalQuantity.value = 1;
                    modalStockInfo.textContent = 'Chọn biến thể để xem tồn kho';
                    modalTotalPrice.textContent = 'Chọn biến thể để xem giá';
                    confirmVariantSelection.disabled = true;
                    
                    // Reset tất cả button biến thể
                    variantOptionModal.forEach(btn => {
                        btn.classList.remove('bg-gray-200', 'border-gray-500');
                        btn.classList.add('border-gray-300');
                    });
                }

                // Xử lý chọn biến thể trong modal
                variantOptionModal.forEach(button => {
                    button.addEventListener('click', function() {
                        if (this.disabled) return;
                        
                        const value = this.getAttribute('data-value');
                        const attributeName = this.getAttribute('data-attribute-name');
                        const optionsContainer = this.closest(`[id^="modal-"]`);
                        const allButtons = optionsContainer.querySelectorAll('button[data-value]');

                        // Bỏ chọn tất cả button trong cùng thuộc tính
                        allButtons.forEach(btn => {
                            btn.classList.remove('bg-gray-200', 'border-gray-500');
                            btn.classList.add('border-gray-300');
                        });

                        // Chọn button này
                        this.classList.remove('border-gray-300');
                        this.classList.add('bg-gray-200', 'border-gray-500');

                        // Tìm biến thể phù hợp
                        const selectedAttributes = {};
                        document.querySelectorAll('[id^="modal-"] button[data-value].bg-gray-200').forEach(btn => {
                            const attrName = btn.getAttribute('data-attribute-name');
                            const attrValue = btn.getAttribute('data-value');
                            selectedAttributes[attrName] = attrValue;
                        });

                        const variants = @json($product->variants->toArray(), JSON_HEX_TAG | JSON_HEX_AMP);
                        const variant = variants.find(v => {
                            const attrs = (v.attribute_values || []).map(a => ({
                                name: a.attribute ? a.attribute.name : '',
                                value: a.value || ''
                            }));
                            return Object.entries(selectedAttributes).every(([name, value]) => {
                                return attrs.some(a => a.name === name && a.value === value);
                            });
                        });

                        if (variant) {
                            modalSelectedVariantId = variant.id;
                            modalSelectedVariant = variant;
                            
                            // Cập nhật thông tin tồn kho
                            const stock = parseInt(this.getAttribute('data-stock')) || 0;
                            modalStockInfo.textContent = `${stock} sản phẩm có sẵn`;
                            
                            // Cập nhật giá
                            const price = variant.sale_price || variant.price || 0;
                            const originalPrice = variant.price || 0;
                            const discount = originalPrice > 0 ? Math.round(((originalPrice - price) / originalPrice) * 100) : 0;
                            
                            modalTotalPrice.innerHTML = `
                                <span class="text-red-600">${number_format(price, 0, ',', '.')} VNĐ</span>
                                ${originalPrice > 0 ? `<span class="text-gray-500 line-through text-sm ml-2">${number_format(originalPrice, 0, ',', '.')} VNĐ</span>` : ''}
                                ${discount > 0 ? `<span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs ml-2">${discount}%</span>` : ''}
                            `;
                            
                            // Enable nút xác nhận
                            confirmVariantSelection.disabled = false;
                            
                            // Cập nhật số lượng tối đa
                            modalQuantity.max = stock;
                            if (parseInt(modalQuantity.value) > stock) {
                                modalQuantity.value = stock;
                            }
                        } else {
                            modalSelectedVariantId = null;
                            modalSelectedVariant = null;
                            modalStockInfo.textContent = 'Chọn biến thể để xem tồn kho';
                            modalTotalPrice.textContent = 'Chọn biến thể để xem giá';
                            confirmVariantSelection.disabled = true;
                        }
                    });
                });

                // Xử lý tăng/giảm số lượng trong modal
                modalDecreaseQty.addEventListener('click', () => {
                    let qty = parseInt(modalQuantity.value);
                    if (qty > 1) modalQuantity.value = qty - 1;
                });

                modalIncreaseQty.addEventListener('click', () => {
                    let qty = parseInt(modalQuantity.value);
                    if (modalSelectedVariant && qty < modalSelectedVariant.stock) {
                        modalQuantity.value = qty + 1;
                    }
                });

                // Xử lý đóng modal
                closeVariantModal.addEventListener('click', () => {
                    variantSelectionModal.classList.add('hidden');
                });

                cancelVariantSelection.addEventListener('click', () => {
                    variantSelectionModal.classList.add('hidden');
                });

                // Đóng modal khi click bên ngoài
                variantSelectionModal.addEventListener('click', (e) => {
                    if (e.target === variantSelectionModal) {
                        variantSelectionModal.classList.add('hidden');
                    }
                });

                // Xác nhận mua ngay
                confirmVariantSelection.addEventListener('click', () => {
                    if (!modalSelectedVariantId) {
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

                    const quantity = parseInt(modalQuantity.value);
                    const stock = modalSelectedVariant.stock;

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
                        modalQuantity.value = stock;
                        return;
                    }

                    // Chuyển hướng đến trang checkout với biến thể đã chọn
                    window.location.href = `/customer/direct-checkout?product_id={{ $product->id }}&variant_id=${modalSelectedVariantId}&quantity=${quantity}`;
                });

                // Xem thêm/thu gọn mô tả
                readMore.addEventListener('click', () => {
                    if (readMore.textContent.trim() === 'Xem thêm') {
                        shortDescription.style.display = 'none';
                        const fullDescClone = fullDescription.cloneNode(true);
                        fullDescClone.classList.remove('hidden');
                        descriptionContent.innerHTML = '';
                        descriptionContent.appendChild(fullDescClone);
                        readMore.textContent = 'Thu gọn';
                    } else {
                        shortDescription.style.display = 'block';
                        fullDescription.classList.add('hidden');
                        descriptionContent.innerHTML = shortDescription.outerHTML;
                        readMore.textContent = 'Xem thêm';
                    }
                });

                // Chọn sao đánh giá
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

                // Gắn sự kiện cho nút like ban đầu
                attachLikeReviewEvents();

                // Xử lý bộ lọc đánh giá
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();

                        // Bỏ active tất cả button
                        document.querySelectorAll('.filter-btn').forEach(b => {
                            b.classList.remove('text-[#e94e1b]', 'border-[#e94e1b]');
                            b.classList.add('text-[#333]', 'border-[#ddd]');
                        });

                        // Active button được click
                        this.classList.remove('text-[#333]', 'border-[#ddd]');
                        this.classList.add('text-[#e94e1b]', 'border-[#e94e1b]');

                        const filter = this.getAttribute('data-filter');

                        // Hiển thị loading
                        const reviewList = document.getElementById('reviewList');
                        reviewList.innerHTML =
                            '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i><p class="mt-2 text-gray-500">Đang tải...</p></div>';

                        // Gọi AJAX để lấy danh sách review mới
                        const currentUrl = new URL(window.location);
                        currentUrl.searchParams.set('filter', filter);

                        fetch(currentUrl.toString(), {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'text/html'
                                }
                            })
                            .then(res => res.text())
                            .then(html => {
                                reviewList.innerHTML = html;

                                // Gắn lại sự kiện cho nút like mới
                                attachLikeReviewEvents();
                            })
                            .catch(error => {
                                console.error('Error loading reviews:', error);
                                reviewList.innerHTML =
                                    '<div class="text-center py-8 text-red-500">Có lỗi xảy ra khi tải đánh giá</div>';
                            });
                    });
                });

                // Xử lý phân trang đánh giá
                document.addEventListener('click', function(e) {
                    if (e.target.matches('.pagination a')) {
                        e.preventDefault();

                        const pageUrl = e.target.href;

                        // Hiển thị loading
                        const reviewList = document.getElementById('reviewList');
                        reviewList.innerHTML =
                            '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i><p class="mt-2 text-gray-500">Đang tải...</p></div>';

                        // Gọi AJAX để lấy trang mới
                        fetch(pageUrl, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'text/html'
                                }
                            })
                            .then(res => res.text())
                            .then(html => {
                                reviewList.innerHTML = html;

                                // Gắn lại sự kiện cho nút like mới
                                attachLikeReviewEvents();

                                // Scroll to top of review section
                                reviewList.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'start'
                                });
                            })
                            .catch(error => {
                                console.error('Error loading reviews:', error);
                                reviewList.innerHTML =
                                    '<div class="text-center py-8 text-red-500">Có lỗi xảy ra khi tải đánh giá</div>';
                            });
                    }
                });

                // Hàm gắn lại sự kiện cho nút like
                function attachLikeReviewEvents() {
                    document.querySelectorAll('.like-review-btn').forEach(btn => {
                        btn.addEventListener('click', function(e) {
                            e.preventDefault();

                            // Disable button và thêm loading
                            this.disabled = true;
                            const originalHTML = this.innerHTML;

                            // Chỉ thay đổi icon, không thay đổi toàn bộ innerHTML
                            const icon = this.querySelector('i');
                            if (icon) {
                                icon.className = 'fas fa-spinner fa-spin';
                            }

                            const reviewId = this.getAttribute('data-review-id');

                            fetch(`/customer/review/${reviewId}/like`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': token,
                                        'Accept': 'application/json',
                                    },
                                })
                                .then(res => {
                                    return res.json();
                                })
                                .then(data => {

                                    if (data.success) {

                                        // Cập nhật trạng thái like
                                        this.setAttribute('data-liked', data.liked ? 'true' :
                                            'false');

                                        // Tìm lại button sau khi cập nhật
                                        const button = this;

                                        // Cập nhật icon - tìm trong button
                                        const icon = button.querySelector('i');
                                        if (icon) {
                                            const newIconClass = data.liked ? 'fas fa-heart' :
                                                'far fa-heart';
                                            icon.className = newIconClass;
                                        }

                                        // Cập nhật số lượng like - tìm trong button
                                        const likeCount = button.querySelector('.like-count');
                                        if (likeCount) {
                                            likeCount.textContent = data.like_count;
                                        }

                                        // Bỏ thông báo SweetAlert
                                        // Swal.fire({
                                        //     position: 'top-end',
                                        //     toast: true,
                                        //     icon: 'success',
                                        //     title: data.message || (data.liked ? 'Đã thích đánh giá!' : 'Đã bỏ thích đánh giá!'),
                                        //     timer: 1500,
                                        //     showConfirmButton: false
                                        // });
                                    } else {
                                        console.log('Server returned error:', data.message);
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Lỗi',
                                            text: data.message || 'Không thể like!',
                                            timer: 1500,
                                            showConfirmButton: false
                                        });
                                    }
                                })
                                .catch((error) => {
                                    console.error('Fetch error:', error);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Lỗi',
                                        text: 'Không thể kết nối server!',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                })
                                .finally(() => {
                                    // Enable button và khôi phục text
                                    this.disabled = false;
                                    // Khôi phục icon nếu có lỗi
                                    const icon = this.querySelector('i');
                                    if (icon && icon.classList.contains('fa-spinner')) {
                                        const isLiked = this.getAttribute('data-liked') === 'true';
                                        icon.className = isLiked ? 'fas fa-heart' : 'far fa-heart';
                                    }
                                });
                        });
                    });
                }
            });
        </script>
    @endpush
@endsection
