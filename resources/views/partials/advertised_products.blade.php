<div class="max-w-5xl mx-auto p-4">
    @if ($advertisedProductsByShop->isNotEmpty())
        @php
            $shopAds = $advertisedProductsByShop->first();
            $shop = $shopAds['shop'];
            $products = $shopAds['products'];
            $campaignName = $shopAds['campaign_name'];
            $query = request('query', '');
        @endphp

        <div class="bg-white rounded-lg shadow-md p-6">
            <!-- Header với Shop Info và nút Chi tiết -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-4">
                    @if ($shop)
                        <img alt="{{ $shop->name }} official store logo" class="rounded-full w-16 h-16"
                            src="{{ $shop->logo ? Storage::url($shop->logo) : asset('images/default_shop_logo.png') }}" />
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $shop->name }}</h3>
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs">Mall</span>
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <span>{{ number_format($shop->shop_rating, 1) }}</span>
                                </div>
                                <span>{{ number_format($shop->total_followers) }} Followers</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Nút Chi tiết -->
                <a href="{{ route('shop.ads', ['shopId' => $shop->id, 'query' => $query]) }}"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                    Chi tiết
                </a>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach ($products->take(5) as $product)
                    <div class="border border-gray-200 rounded-lg p-3 hover:shadow-lg transition-shadow">
                        <div class="relative">
                            <a href="{{ route('product.show', $product->slug) }}">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                    class="w-full h-32 object-cover rounded-lg mb-2">
                                <div class="absolute top-1 right-1 bg-red-500 text-white text-xs px-1 py-0.5 rounded">
                                    Quảng cáo
                                </div>
                            </a>
                        </div>

                        <div class="space-y-1">
                            <h4 class="font-medium text-gray-800 text-sm line-clamp-2">
                                <a href="{{ route('product.show', $product->slug) }}" class="hover:text-red-500">
                                    {{ $product->name }}
                                </a>
                            </h4>

                            <div class="flex items-center gap-1">
                                <span class="text-red-500 font-bold text-sm">
                                    ₫{{ number_format($product->getCurrentPriceAttribute()) }}
                                </span>
                                @if ($product->getDiscountPercentageAttribute() > 0)
                                    <span class="text-gray-400 line-through text-xs">
                                        ₫{{ number_format($product->price) }}
                                    </span>
                                @endif
                            </div>

                            <div class="text-xs text-gray-500">
                                Đã bán {{ number_format($product->sold_quantity) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Footer với thông tin chiến dịch -->
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                <div class="text-sm text-gray-600">
                    <span>Chiến dịch:</span>
                    <span class="font-medium">{{ $campaignName ?? 'N/A' }}</span>
                </div>
                <div class="text-gray-400 text-xs">Ad</div>
            </div>
        </div>
    @else
        {{-- Không có sản phẩm quảng cáo nào để hiển thị --}}
    @endif
</div>
