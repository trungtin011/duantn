@extends('layouts.app')
@section('title', 'Chi tiết sản phẩm')
@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 mb-6 text-sm font-medium">
            <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">Trang chủ</a>
            <span class="text-gray-400">/</span>
            <a href="#" class="text-blue-600 hover:text-blue-800">Chi tiết sản phẩm</a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-800 font-semibold">{{ \Illuminate\Support\Str::limit($product->name, 30, '...') }}</span>
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
                            class="w-full object-cover rounded-lg transform transition-transform duration-300 hover:scale-105"
                            loading="lazy">
                        <div class="flex gap-2 mt-4 overflow-x-auto">
                            @foreach ($product->images as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Ảnh phụ {{ $product->name }}"
                                    class="w-[100px] h-[100px] object-cover rounded cursor-pointer hover:opacity-90 transition-opacity sub-image"
                                    onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}')"
                                    loading="lazy">
                            @endforeach
                            @if ($product->images->isEmpty())
                                <img src="{{ asset('storage/product_images/default.jpg') }}" alt="Ảnh mặc định"
                                    class="w-[100px] h-[100px] object-cover rounded cursor-pointer sub-image"
                                    loading="lazy">
                            @endif
                        </div>
                    </div>

                    <!-- Thông tin sản phẩm -->
                    <div class="flex flex-col gap-6">
                        <h2 class="text-3xl font-bold text-gray-900" title="{{ $product->name }}">
                            {{ \Illuminate\Support\Str::limit($product->name, 50, '...') }}
                        </h2>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-yellow-400 flex">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= round($product->orderReviews->avg('rating')))
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2.25l2.82 5.73h5.88l-4.77 4.12 1.82 5.73-5.73-3.49-5.73 3.49 1.82-5.73-4.77-4.12h5.88z" />
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2.25l2.82 5.73h5.88l-4.77 4.12 1.82 5.73-5.73-3.49-5.73 3.49 1.82-5.73-4.77-4.12h5.88z" />
                                            </svg>
                                        @endif
                                    @endfor
                                </span>
                                <span class="text-sm text-gray-600">
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
                        <div class="flex items-center gap-4" id="price-display">
                            @if ($product->variants->isNotEmpty())
                                <!-- Sản phẩm có biến thể, lấy giá từ biến thể đầu tiên làm mặc định -->
                                <span class="text-red-600 text-3xl font-bold">
                                    {{ number_format($product->variants->first()->sale_price ?: $product->sale_price, 0, ',', '.') }}
                                    VNĐ
                                </span>
                                @if ($product->variants->first()->price > 0)
                                    <span class="text-gray-500 line-through text-lg">
                                        {{ number_format($product->variants->first()->price ?: $product->price, 0, ',', '.') }}
                                        VNĐ
                                    </span>
                                    <span class="bg-red-100 text-red-600 px-3 py-1 rounded text-sm">
                                        -{{ round((($product->variants->first()->price - $product->variants->first()->sale_price) / $product->variants->first()->price) * 100) ?: 0 }}%
                                    </span>
                                @endif
                            @else
                                <!-- Sản phẩm đơn, lấy giá từ bảng products -->
                                <span class="text-red-600 text-3xl font-bold">
                                    {{ number_format($product->sale_price, 0, ',', '.') }} VNĐ
                                </span>
                                @if ($product->price > 0)
                                    <span class="text-gray-500 line-through text-lg">
                                        {{ number_format($product->price, 0, ',', '.') }} VNĐ
                                    </span>
                                    <span class="bg-red-100 text-red-600 px-3 py-1 rounded text-sm">
                                        -{{ round((($product->price - $product->sale_price) / $product->price) * 100) ?: 0 }}%
                                    </span>
                                @endif
                            @endif
                        </div>
                        <p class="text-gray-700 text-base leading-relaxed">{!! $product->meta_description !!}</p>

                        <!-- Thuộc tính của sản phẩm -->
                        <div class="flex flex-col gap-6">
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
                                    <div class="flex items-center gap-4" id="{{ Str::slug($attributeName, '-') }}-options">
                                        <span class="text-gray-800 font-medium">{{ $attributeName }}:</span>
                                        <div class="flex gap-2">
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
                                                    class="border border-gray-300 rounded-lg px-4 py-2 flex items-center gap-2 hover:bg-gray-100 transition-colors"
                                                    data-value="{{ $value }}"
                                                    data-price="{{ $variantId ? $variantData[$variantId]['price'] : $product->sale_price }}"
                                                    data-stock="{{ $stock }}" data-variant-id="{{ $variantId }}"
                                                    data-attribute-name="{{ $attributeName }}">
                                                    @if (isset($attributeImages[$attributeName][$value]))
                                                        <img src="{{ $attributeImages[$attributeName][$value] }}"
                                                            width="24" height="24" class="rounded" loading="lazy">
                                                    @endif
                                                    <span>{{ $value }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <p class="text-gray-600">Không có tùy chọn {{ $attributeName }}.</p>
                                @endif
                            @endforeach
                        </div>

                        <!-- Số lượng và biến thể được chọn -->
                        <div class="flex items-center gap-6 mt-4">
                            <span class="text-gray-800 font-medium">Số lượng:</span>
                            <form action="{{ route('cart.add') }}" method="POST" class="flex items-center">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="variant_id" id="selected_variant_id"
                                    value="{{ $selectedVariant ? $selectedVariant->id : ($product->variants->isEmpty() ? 'default' : '') }}">
                                <button type="button" id="decreaseQty"
                                    class="border border-gray-300 px-4 py-2 rounded-l-lg hover:bg-gray-100 text-lg">-</button>
                                <input type="text" name="quantity" id="quantity" value="1"
                                    class="w-20 text-center px-3 py-2 border-t border-b border-gray-300 focus:outline-none text-lg">
                                <button type="button" id="increaseQty"
                                    class="border border-gray-300 px-4 py-2 rounded-r-lg hover:bg-gray-100 text-lg">+</button>
                            </form>
                            <span class="text-sm text-gray-600" id="stock_info">
                                {{ $defaultStock }} sản phẩm có sẵn
                            </span>
                        </div>

                        <!-- Nút hành động -->
                        <div class="flex gap-4 mt-6">
                            <button
                                class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 flex items-center gap-2 add-to-cart"
                                data-product-id="{{ $product->id }}">
                                <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ
                            </button>
                            <button id="instant_buy_btn"
                                class="bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-700">Mua ngay</button>
                        </div>
                    </div>
                </div>

                <!-- Mô tả sản phẩm -->
                <div class="bg-white rounded-lg p-6 mt-6 shadow">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Mô tả sản phẩm</h3>
                    <div class="text-gray-700 text-base">
                        <div class="description-content">
                            <span id="shortDescription" class="relative">
                                <div class="h-[200px] overflow-hidden">
                                    {!! $product->description !!}
                                </div>
                                <div class="w-full"
                                    style="position: absolute; bottom: 0; left: 0; height: 80px; background-image: linear-gradient(rgba(255, 255, 255, 0), rgb(255, 255, 255));">
                                </div>
                            </span>
                            <div id="fullDescription" class="hidden">{!! $product->description !!}</div>
                        </div>
                        <div class="flex justify-center mt-5">
                            <button id="readMore"
                                class="text-red-600 px-4 py-2 rounded text-sm hover:bg-red-100 transition-all duration-300">
                                Xem thêm
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Đánh giá sản phẩm -->
                <div class="bg-white rounded-lg p-6 mt-6 shadow">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Đánh giá sản phẩm</h3>

                    @if (auth()->check() && $hasPurchased && !$hasReviewed)
                        <form id="reviewForm" action="{{ route('product.review', $product->id) }}" method="POST"
                            enctype="multipart/form-data" class="mb-6">
                            @csrf
                            <label class="block mb-2 text-sm font-medium text-gray-700">Đánh giá sao:</label>
                            <div class="flex gap-1 mb-4">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg data-value="{{ $i }}"
                                        class="star w-6 h-6 cursor-pointer text-gray-300 hover:text-yellow-400 transition-colors"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 0 0 .95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 0 0-.364 1.118l1.286 3.966c.3.921-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 0 0-1.176 0l-3.385 2.46c-.784.57-1.838-.197-1.54-1.118l1.286-3.966a1 1 0 0 0-.364-1.118L2.045 9.393c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 0 0 .95-.69l1.286-3.966z" />
                                    </svg>
                                @endfor
                                <input type="hidden" name="rating" id="ratingInput" required>
                            </div>
                            <textarea name="comment" rows="4"
                                class="w-full border p-3 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Viết nhận xét..."></textarea>
                            <input type="file" name="images[]" multiple accept="image/*"
                                class="mt-4 block w-full text-sm">
                            <input type="file" name="video" accept="video/*" class="mt-2 block w-full text-sm">
                            <button type="submit"
                                class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 text-sm">Gửi đánh
                                giá</button>
                        </form>
                    @endif

                    @if ($filteredReviews->isEmpty())
                        <p class="text-gray-600">Chưa có đánh giá nào cho sản phẩm này.</p>
                    @else
                        <div class="flex bg-red-100/50 p-4 py-10 rounded-lg gap-10">
                            <div class="flex flex-wrap items-center justify-center gap-3 w-[200px]">
                                <div class="flex flex-col items-center">
                                    <div class="flex items-baseline space-x-1">
                                        <span
                                            class="text-red-600 text-[28px] font-light leading-none">{{ $averageRating }}</span>
                                        <span class="text-red-500 text-[16px] font-light leading-none">trên 5</span>
                                    </div>
                                    <div class="flex space-x-[2px] mt-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="fas fa-star {{ $i <= round($averageRating) ? 'text-[#e94e1b]' : 'text-gray-300' }} text-[18px]"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button
                                    class="filter-btn {{ !$filter ? 'text-[#e94e1b] border-[#e94e1b]' : 'text-[#333] border-[#ddd]' }} border rounded px-3 py-1 text-[13px] leading-none font-normal"
                                    data-filter="all">
                                    Tất Cả ({{ number_format($totalReviews, 0, ',', '.') }})
                                </button>
                                @foreach ([5, 4, 3, 2, 1] as $star)
                                    <button
                                        class="filter-btn {{ $filter == 'star-' . $star ? 'text-[#e94e1b] border-[#e94e1b]' : 'text-[#333] border-[#ddd]' }} border rounded px-3 py-1 text-[13px] leading-none font-normal"
                                        data-filter="star-{{ $star }}">
                                        {{ $star }} Sao ({{ number_format($ratingCounts[$star], 0, ',', '.') }})
                                    </button>
                                @endforeach
                                <button
                                    class="filter-btn {{ $filter == 'comments' ? 'text-[#e94e1b] border-[#e94e1b]' : 'text-[#333] border-[#ddd]' }} border rounded px-3 py-1 text-[13px] leading-none font-normal"
                                    data-filter="comments">
                                    Có Bình Luận ({{ number_format($commentCount, 0, ',', '.') }})
                                </button>
                                <button
                                    class="filter-btn {{ $filter == 'images' ? 'text-[#e94e1b] border-[#e94e1b]' : 'text-[#333] border-[#ddd]' }} border rounded px-3 py-1 text-[13px] leading-none font-normal"
                                    data-filter="images">
                                    Có Hình Ảnh / Video ({{ number_format($mediaCount, 0, ',', '.') }})
                                </button>
                            </div>
                        </div>
                        <div id="reviewList" class="mt-5">
                            @include('partials.review_list', ['reviews' => $filteredReviews])
                            {{ $filteredReviews->appends(['filter' => $filter])->links() }}
                        </div>
                    @endif
                </div>

            </div>

            <!-- Cột bên phải (Thông tin shop) -->
            <div class="lg:col-span-1">
                <div class="sticky top-5">
                    <div class="bg-white rounded-lg p-6 shadow">
                        <h2 class="text-xl font-semibold mb-4 border-b pb-2 text-gray-800">Cửa hàng</h2>
                        <div class="flex items-center gap-4 mb-4">
                            <img src="{{ $product->shop ? ($product->shop->shop_logo ? Storage::url($product->shop->shop_logo) : Storage::url('shop_logos/default_shop_logo.png')) : Storage::url('shop_logos/default_shop_logo.png') }}"
                                alt="Logo Shop" class="w-16 h-16 rounded-full object-cover border" loading="lazy">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $product->shop ? $product->shop->shop_name : 'Tên Shop Không Xác Định' }}
                                </h3>
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
                        <div class="flex justify-center gap-3">
                            <button
                                class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 flex items-center gap-2">
                                <i class="fa-solid fa-comment"></i> Nhắn tin
                            </button>
                            <a href="{{ route('shop.profile', $product->shop->id) }}"
                                class="border border-gray-300 px-5 py-2 rounded-lg hover:bg-gray-100">Xem
                                cửa hàng
                            </a>
                        </div>
                    </div>

                    <!-- Voucher shop -->
                    @if ($shop && $shop->coupons->where('status', 'active')->count() > 0)
                        <div class="bg-white rounded-lg p-6 shadow mt-6">
                            <h2 class="text-xl font-semibold mb-4 border-b pb-2 text-gray-800">Voucher Cửa hàng</h2>
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
                                        <button
                                            class="bg-black text-white px-4 py-1.5 rounded hover:bg-gray-800 save-coupon-btn"
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
                                    class="bg-black text-white rounded hover:bg-gray-800 flex items-center gap-2 save-all-coupons-btn"
                                    data-shop-id="{{ $shop->id }}">
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
        <div id="reportProductModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-75 hidden z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-xl">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Báo cáo sản phẩm</h3>
                <form action="{{ route('product.report', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="shop_id" value="{{ $product->shopID }}">
                    <div class="mb-4">
                        <label for="report_type" class="block text-sm font-medium text-gray-700">Loại vi phạm</label>
                        <select name="report_type" id="report_type"
                            class="mt-1 block w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
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
                            class="mt-1 block w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Mô tả chi tiết vi phạm"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="evidence" class="block text-sm font-medium text-gray-700">Bằng chứng</label>
                        <input type="file" name="evidence[]" id="evidence" multiple
                            class="mt-1 block w-full text-sm">
                    </div>
                    <div class="mb-4 flex items-center">
                        <input type="checkbox" name="is_anonymous" id="is_anonymous" class="h-4 w-4 text-blue-600">
                        <label for="is_anonymous" class="ml-2 text-sm text-gray-700">Báo cáo ẩn danh</label>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" id="cancelReportBtn"
                            class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Hủy</button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Gửi</button>
                    </div>
                </form>
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
                        <span class="text-red-600 text-3xl font-bold">${number_format(defaultPrice, 0, ',', '.')} VNĐ</span>
                        ${defaultOriginalPrice > 0 ? `<span class="text-gray-500 line-through text-lg">${number_format(defaultOriginalPrice, 0, ',', '.')} VNĐ</span>` : ''}
                        ${defaultDiscount > 0 ? `<span class="bg-red-100 text-red-600 px-3 py-1 rounded text-sm>${defaultDiscount}%</span>` : ''}
                    `;
                        const defaultStock = {{ $defaultStock }}; // Sử dụng defaultStock từ controller
                    @else
                        const defaultPrice = {{ $product->sale_price }};
                        const defaultOriginalPrice = {{ $product->price }};
                        const defaultDiscount = defaultOriginalPrice > 0 ? Math.round(((defaultOriginalPrice -
                            defaultPrice) / defaultOriginalPrice) * 100) : 0;
                        priceDisplay.innerHTML = `
                            <span class="text-red-600 text-3xl font-bold">${number_format(defaultPrice, 0, ',', '.')} VNĐ</span>
                            ${defaultOriginalPrice > 0 ? `<span class="text-gray-500 line-through text-lg">${number_format(defaultOriginalPrice, 0, ',', '.')} VNĐ</span>` : ''}
                            ${defaultDiscount > 0 ? `<span class="bg-red-100 text-red-600 px-3 py-1 rounded text-sm>${defaultDiscount}%</span>` : ''}
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
                            <span class="text-red-600 text-3xl font-bold">${number_format(price, 0, ',', '.')} VNĐ</span>
                            ${originalPrice > 0 ? `<span class="text-gray-500 line-through text-lg">${number_format(originalPrice, 0, ',', '.')} VNĐ</span>` : ''}
                            ${discount > 0 ? `<span class="bg-red-100 text-red-600 px-3 py-1 rounded text-sm>${discount}%</span>` : ''}
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
            });
        </script>
        <script></script>
    @endpush
@endsection
