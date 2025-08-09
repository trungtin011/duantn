<div class="max-w-5xl mx-auto p-4">
    @if ($advertisedProductsByShop->isNotEmpty())
        @php
            $totalShops = $advertisedProductsByShop->count();
            $firstShop = $advertisedProductsByShop->first()['shop'];
            $firstProducts = $advertisedProductsByShop->first()['products'];
            $firstShopAds = $advertisedProductsByShop->first();
            $firstCampaignName = $firstShopAds['campaign_name'];
        @endphp

        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-ad text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $firstShop->shop_name }}</h3>
                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            <p>{{ $firstCampaignName }}</p>
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                <span class="text-xs">{{ number_format($firstShop->order_reviews_avg_rating ?? 0, 1) }}</span>
                                <span class="text-xs text-gray-500">({{ $firstShop->order_reviews_count ?? 0 }} đánh giá)</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-heart text-red-400 text-xs"></i>
                                <span class="text-xs">{{ number_format($firstShop->followers_count ?? 0) }} follow</span>
                            </div>
                            @if(isset($firstShopAds['bid_amount']))
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-gavel text-blue-400 text-xs"></i>
                                <span class="text-xs text-blue-600 font-medium">{{ number_format($firstShopAds['bid_amount']) }}đ</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('ad.click') }}?ad_click_type=shop_detail&shop_id={{ $firstShop->id }}&campaign_id={{ $firstShopAds['all_campaigns']->first()['campaign']->id }}" 
                   class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                    Chi tiết
                </a>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($firstProducts->take(5) as $product)
                    <div class="border border-gray-200 rounded-lg p-3 hover:shadow-lg transition-shadow">
                        <div class="relative">
                            <a href="{{ route('ad.click') }}?ad_click_type=product_detail&shop_id={{ $firstShop->id }}&campaign_id={{ $firstShopAds['all_campaigns']->first()['campaign']->id }}&product_id={{ $product->id }}">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                     class="w-full h-32 object-cover rounded-lg mb-2">
                                <div class="absolute top-1 right-1 bg-red-500 text-white text-xs px-1 py-0.5 rounded">
                                    Quảng cáo
                                </div>
                            </a>
                        </div>

                        <div class="space-y-1">
                            <h4 class="font-medium text-gray-800 text-sm line-clamp-2">
                                <a href="{{ route('ad.click') }}?ad_click_type=product_detail&shop_id={{ $firstShop->id }}&campaign_id={{ $firstShopAds['all_campaigns']->first()['campaign']->id }}&product_id={{ $product->id }}" 
                                   class="hover:text-red-500">
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

            <!-- Footer với thông tin chiến dịch và nút xem thêm -->
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                <div class="text-sm text-gray-600">
                    <span>Chiến dịch:</span>
                    <span class="font-medium">{{ $firstCampaignName ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="text-gray-400 text-xs">Ad</div>
                    @if($totalShops > 1)
                        <button onclick="showMoreAds()" 
                                class="text-red-500 hover:text-red-600 text-sm font-medium">
                            Xem thêm {{ $totalShops - 1 }} shop quảng cáo
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal hiển thị tất cả shop quảng cáo -->
        @if($totalShops > 1)
            <div id="adsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                        <div class="flex items-center justify-between p-6 border-b">
                            <h2 class="text-xl font-semibold text-gray-800">Tất cả shop quảng cáo</h2>
                            <button onclick="closeAdsModal()" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            @foreach($advertisedProductsByShop as $index => $shopAds)
                                @php
                                    $shop = $shopAds['shop'];
                                    $products = $shopAds['products'];
                                    $campaignName = $shopAds['campaign_name'];
                                @endphp
                                
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <!-- Shop Header -->
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                                                <i class="fas fa-store text-white"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-800">{{ $shop->shop_name }}</h3>
                                                <div class="flex items-center space-x-3 text-xs text-gray-600">
                                                    <span>{{ $campaignName }}</span>
                                                    <div class="flex items-center space-x-1">
                                                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                                                        <span>{{ number_format($shop->order_reviews_avg_rating ?? 0, 1) }}</span>
                                                        <span class="text-gray-500">({{ $shop->order_reviews_count ?? 0 }})</span>
                                                    </div>
                                                    <div class="flex items-center space-x-1">
                                                        <i class="fas fa-heart text-red-400 text-xs"></i>
                                                        <span>{{ number_format($shop->followers_count ?? 0) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <a href="{{ route('ad.click') }}?ad_click_type=shop_detail&shop_id={{ $shop->id }}&campaign_id={{ $shopAds['all_campaigns']->first()['campaign']->id }}" 
                                           class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                            Chi tiết
                                        </a>
                                    </div>

                                    <!-- Products Grid -->
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                        @foreach($products->take(4) as $product)
                                            <div class="border border-gray-200 rounded p-2">
                                                <a href="{{ route('ad.click') }}?ad_click_type=product_detail&shop_id={{ $shop->id }}&campaign_id={{ $shopAds['all_campaigns']->first()['campaign']->id }}&product_id={{ $product->id }}">
                                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                                         class="w-full h-20 object-cover rounded mb-1">
                                                    <div class="absolute top-1 right-1 bg-red-500 text-white text-xs px-1 py-0.5 rounded">
                                                        Ad
                                                    </div>
                                                </a>
                                                
                                                <h4 class="font-medium text-gray-800 text-xs line-clamp-2">
                                                    <a href="{{ route('ad.click') }}?ad_click_type=product_detail&shop_id={{ $shop->id }}&campaign_id={{ $shopAds['all_campaigns']->first()['campaign']->id }}&product_id={{ $product->id }}" 
                                                       class="hover:text-red-500">
                                                        {{ $product->name }}
                                                    </a>
                                                </h4>
                                                
                                                <div class="text-red-500 font-bold text-xs">
                                                    ₫{{ number_format($product->getCurrentPriceAttribute()) }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function showMoreAds() {
                    document.getElementById('adsModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }

                function closeAdsModal() {
                    document.getElementById('adsModal').classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }

                // Đóng modal khi click bên ngoài
                document.getElementById('adsModal').addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeAdsModal();
                    }
                });

                // Đóng modal khi nhấn ESC
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeAdsModal();
                    }
                });
            </script>
        @endif
    @else
        {{-- Không có sản phẩm quảng cáo nào để hiển thị --}}
    @endif
</div>
