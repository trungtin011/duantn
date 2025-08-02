<div class="max-w-5xl mx-auto p-4">
    <div class="flex flex-col sm:flex-row gap-4">
        @if($advertisedProducts->isNotEmpty())
            <!-- Left side - Shop Info (Assuming all advertised products are from the same shop or you want to display the first product's shop) -->
            @php
                $firstProduct = $advertisedProducts->first();
                $shop = $firstProduct->shop ?? null;
            @endphp

            @if($shop)
                <div class="flex flex-col items-center sm:items-start sm:w-48">
                    <img alt="{{ $shop->name }} official store logo" class="rounded-full" height="64" src="{{ $shop->logo ? Storage::url($shop->logo) : asset('images/default_shop_logo.png') }}" width="64"/>
                    <p class="mt-2 text-center sm:text-left text-sm font-normal text-black">
                        {{ $shop->name }}
                    </p>
                    @if(optional($shop)->slogan)
                        <p class="text-center sm:text-left text-xs text-gray-600 mt-1 line-clamp-2">
                            {{ $shop->slogan }}
                        </p>
                    @elseif(optional($shop)->description)
                        <p class="text-center sm:text-left text-xs text-gray-600 mt-1 line-clamp-2">
                            {{ Str::limit(strip_tags($shop->description), 50) }} {{-- Giới hạn 50 ký tự và loại bỏ HTML --}}
                        </p>
                    @endif
                    <p class="text-center sm:text-left text-sm font-normal text-gray-700 mt-1">
                        {{ $firstProduct->ads_campaign_name ?? 'N/A' }}
                    </p>
                    <div class="mt-1 flex items-center space-x-1">
                        <span class="text-xs font-semibold text-red-600 border border-red-600 rounded px-1.5 py-0.5">
                            Mall
                        </span>
                        <div class="flex items-center space-x-1 text-xs font-semibold text-yellow-400">
                            <i class="fas fa-star"></i>
                            <span>{{ number_format($shop->shop_rating, 1) }}</span>
                        </div>
                        <span class="text-xs text-gray-400 font-normal">
                            {{ number_format($shop->total_followers) }} Followers
                        </span>
                    </div>
                    <a href="{{ route('shop.show', $shop->id) }}" class="mt-2 w-full sm:w-auto border border-red-600 text-red-600 text-sm font-normal rounded px-6 py-1 hover:bg-red-50 transition" type="button">
                        Xem Shop
                    </a>
                </div>
            @endif

            <!-- Right side - product cards -->
            <div class="flex flex-row space-x-3 overflow-x-auto scrollbar-hide">
                @foreach($advertisedProducts as $product)
                    <div class="flex-shrink-0 w-36 text-xs font-normal text-black relative">
                        <a href="{{ route('product.show', $product->slug) }}">
                            <img alt="{{ $product->name }} product image" class="mb-1" height="144" src="{{ $product->image_url }}" width="144"/>
                            <p class="line-clamp-2 leading-tight">
                                {{ $product->name }}
                            </p>
                            <p class="text-red-600 font-semibold mt-0.5">
                                ₫{{ number_format($product->getCurrentPriceAttribute()) }}
                            </p>
                            <p class="text-gray-400">
                                Đã bán {{ number_format($product->sold_quantity) }}
                            </p>
                            @if($product->getDiscountPercentageAttribute() > 0)
                                <div class="text-red-600 text-[10px] font-semibold absolute top-1 right-1 bg-white px-0.5 rounded">
                                    -{{ $product->getDiscountPercentageAttribute() }}%
                                </div>
                            @endif
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Không có sản phẩm quảng cáo nào để hiển thị --}}
        @endif
    </div>
    <div class="text-gray-400 text-xs text-right mt-1">
        Ad
    </div>
</div> 