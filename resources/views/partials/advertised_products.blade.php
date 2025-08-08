<div class="max-w-5xl mx-auto p-4">
    @if($advertisedProductsByShop->isNotEmpty())
        @php
            $query = request('query', '');
            $totalShops = $advertisedProductsByShop->count();
        @endphp

        <!-- Hiển thị shop đầu tiên -->
        @php
            $firstShopAds = $advertisedProductsByShop->first();
            $firstShop = $firstShopAds['shop'];
            $firstProducts = $firstShopAds['products'];
            $firstCampaignName = $firstShopAds['campaign_name'];
        @endphp

        <div class="bg-white rounded-lg shadow-md p-6">
            <!-- Header với Shop Info và nút Chi tiết -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-4">
                    @if($firstShop)
                        <img alt="{{ $firstShop->shop_name }} official store logo" class="rounded-full w-16 h-16 object-cover"
                            src="{{ $firstShop->shop_logo ? Storage::url($firstShop->shop_logo) : asset('images/default_shop_logo.png') }}" />
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $firstShop->shop_name }}</h3>
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs">Mall</span>
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <span>{{ number_format($firstShop->shop_rating, 1) }}</span>
                                </div>
                                <span>{{ number_format($firstShop->total_followers) }} Followers</span>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Nút Chi tiết -->
                <a href="{{ route('shop.ads', ['shopId' => $firstShop->id, 'query' => $query]) }}" 
                   class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                    Chi tiết
                </a>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($firstProducts->take(5) as $product)
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
                                @if($product->getDiscountPercentageAttribute() > 0)
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
                        <button onclick="showMoreAds()" class="text-red-500 hover:text-red-600 text-sm font-medium">
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
                                        <div class="flex items-center space-x-4">
                                            <img alt="{{ $shop->shop_name }} official store logo" class="rounded-full w-12 h-12 object-cover"
                                                src="{{ $shop->shop_logo ? Storage::url($shop->shop_logo) : asset('images/default_shop_logo.png') }}" />
                                            <div>
                                                <h3 class="font-semibold text-gray-800">{{ $shop->shop_name }}</h3>
                                                <div class="flex items-center space-x-2 text-sm text-gray-600">
                                                    <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs">Mall</span>
                                                    <div class="flex items-center space-x-1">
                                                        <i class="fas fa-star text-yellow-400"></i>
                                                        <span>{{ number_format($shop->shop_rating, 1) }}</span>
                                                    </div>
                                                    <span>{{ number_format($shop->total_followers) }} Followers</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <a href="{{ route('shop.ads', ['shopId' => $shop->id, 'query' => $query]) }}" 
                                           class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                            Chi tiết
                                        </a>
                                    </div>

                                    <!-- Products Grid -->
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                        @foreach($products->take(4) as $product)
                                            <div class="border border-gray-200 rounded-lg p-2 hover:shadow-md transition-shadow">
                                                <div class="relative">
                                                    <a href="{{ route('product.show', $product->slug) }}">
                                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                                             class="w-full h-24 object-cover rounded-lg mb-1">
                                                        <div class="absolute top-1 right-1 bg-red-500 text-white text-xs px-1 py-0.5 rounded">
                                                            Quảng cáo
                                                        </div>
                                                    </a>
                                                </div>
                                                
                                                <div class="space-y-1">
                                                    <h4 class="font-medium text-gray-800 text-xs line-clamp-2">
                                                        <a href="{{ route('product.show', $product->slug) }}" class="hover:text-red-500">
                                                            {{ $product->name }}
                                                        </a>
                                                    </h4>
                                                    
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-red-500 font-bold text-xs">
                                                            ₫{{ number_format($product->getCurrentPriceAttribute()) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Campaign Info -->
                                    <div class="mt-3 text-xs text-gray-600">
                                        <span>Chiến dịch: {{ $campaignName ?? 'N/A' }}</span>
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
