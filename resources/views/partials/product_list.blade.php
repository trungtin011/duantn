@if ($advertisedProductsByShop->isNotEmpty())
    @include('partials.advertised_products', [
        'advertisedProductsByShop' => $advertisedProductsByShop,
    ])
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-4">
    @forelse ($products as $index => $product)
        <div class="group">
            <div class="relative bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden border border-gray-100">
                <!-- Featured Badge -->
                @if ($product->is_featured)
                    <div class="absolute top-2 left-2 z-10">
                        <div class="bg-red-500 text-white text-xs px-2 py-1 rounded-md font-medium">
                            Nổi bật
                        </div>
                    </div>
                @endif

                <!-- Product Image -->
                <div class="relative overflow-hidden bg-gray-50">
                    <a href="{{ route('product.show', $product->slug) }}" class="block">
                        @if ($product->images && $product->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300"
                                alt="{{ $product->name }}"
                                onerror="this.src='{{ asset('images/avatar.png') }}'">
                        @else
                            <img src="{{ asset('images/avatar.png') }}" 
                                class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300"
                                alt="{{ $product->name }}">
                        @endif
                    </a>
                </div>

                <!-- Product Info -->
                <div class="p-3">
                    <!-- Rating Stars -->
                    @php
                        $reviewsCount = $product->orderReviews->count();
                        $avgRating = $reviewsCount > 0 ? round($product->orderReviews->avg('rating'), 1) : 0;
                        $avgRounded = $reviewsCount > 0 ? round($avgRating) : 0;
                    @endphp
                    
                    <div class="flex items-center gap-1 mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($reviewsCount > 0 && $i <= $avgRounded)
                                <svg class="w-3 h-3 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @else
                                <svg class="w-3 h-3 text-gray-300 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endif
                        @endfor
                        @if ($reviewsCount > 0)
                            <span class="text-xs text-gray-500 ml-1">({{ $reviewsCount }})</span>
                        @endif
                    </div>

                    <!-- Product Name -->
                    <h3 class="font-medium text-gray-800 text-sm leading-tight mb-2 min-h-[40px] line-clamp-2 group-hover:text-gray-900 transition-colors duration-200">
                        <a href="{{ route('product.show', $product->slug) }}">
                            {{ $product->name }}
                        </a>
                    </h3>

                    <!-- Price Section -->
                    @php
                        if ($product->is_variant && $product->variants->isNotEmpty()) {
                            $minPrice = $product->variants->min('price') ?? 0;
                            $minSalePrice = $product->variants->min('sale_price') ?? 0;

                            if ($minSalePrice > 0 && $minSalePrice < $minPrice) {
                                $displayPrice = $minSalePrice;
                                $displayOriginalPrice = $minPrice;
                            } else {
                                $displayPrice = $minPrice;
                                $displayOriginalPrice = $minPrice;
                            }
                        } else {
                            $displayPrice = $product->sale_price > 0 ? $product->sale_price : $product->price;
                            $displayOriginalPrice = $product->price;
                        }
                    @endphp
                    
                    <div class="mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-base font-semibold text-gray-900">{{ number_format($displayPrice) }}đ</span>
                            @if ($displayOriginalPrice > $displayPrice)
                                <span class="text-sm text-gray-400 line-through">{{ number_format($displayOriginalPrice) }}đ</span>
                            @endif
                        </div>
                    </div>

                    <!-- Stats Row -->
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                        <span>Đã bán {{ $product->sold_quantity }}</span>
                        @if ($product->stock_total < 10 && $product->stock_total > 0)
                            <span class="text-red-500">Còn {{ $product->stock_total }}</span>
                        @endif
                    </div>

                    <!-- Shop Info -->
                    <div class="border-t border-gray-100 pt-2">
                        <div class="flex items-center">
                            <i class="fas fa-store text-gray-400 text-xs mr-2"></i>
                            @if ($product->shop && isset($product->shop->name))
                                <span class="text-xs text-gray-600 truncate">{{ $product->shop->name }}</span>
                            @else
                                <span class="text-xs text-gray-400">Không có thông tin cửa hàng</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full">
            <div class="text-center py-12 bg-gray-50 rounded-lg">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                    <i class="fas fa-search text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-600 mb-2">Không tìm thấy sản phẩm</h3>
                @if (request('query'))
                    <p class="text-gray-500">Không có sản phẩm nào phù hợp với từ khóa "{{ request('query') }}"</p>
                @else
                    <p class="text-gray-500">Hãy thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
                @endif
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($products->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
@endif

<!-- Loading Indicator -->
<div id="loading" class="hidden text-center py-6">
    <div class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg">
        <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8h8a8 8 0 01-8 8 8 8 0 01-8-8z"></path>
        </svg>
        <span class="text-gray-600">Đang tải...</span>
    </div>
</div>
