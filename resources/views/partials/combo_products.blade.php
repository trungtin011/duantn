@if (!$comboProducts->isEmpty())
    <div class="product-featured pt-5 snow-container">
        <div class="flex items-center justify-between title">
            <h2 class="">Combo Sản Phẩm</h2>
            <ion-icon name="gift-outline" class="text-2xl text-[#ef3248]"></ion-icon>
        </div>
        
        <!-- Snow particles -->
        <div class="snow-particles">
            <div class="snowflake">❅</div>
            <div class="snowflake">❆</div>
            <div class="snowflake">❅</div>
            <div class="snowflake">❆</div>
            <div class="snowflake">❅</div>
            <div class="snowflake">❆</div>
            <div class="snowflake">❅</div>
            <div class="snowflake">❆</div>
            <div class="snowflake">❅</div>
            <div class="snowflake">❆</div>
        </div>

        <div class="showcase-wrapper has-scrollbar">
            @foreach ($comboProducts as $combo)
                <div class="showcase-container relative">
                    @if(isset($combo->products) && $combo->products->first() && $combo->products->first()->product && $combo->products->first()->product->shop)
                    <a href="{{ route('shop.profile', $combo->products->first()->product->shop->id) }}"
                        class="text-[#ef3248] hover:underline text-sm font-medium whitespace-nowrap absolute top-5 right-5">
                        Chi tiết
                    </a>
                    @endif
                    <div class="flex gap-4 w-full">
                        <a href="{{ route('combo.show', $combo->id) }}"
                            class="showcase-banner block w-full h-48 overflow-hidden rounded-lg bg-gray-200 mb-4">
                            <img src="{{ $combo->image ? asset('storage/' . $combo->image) : asset('images/default.jpg') }}" alt="{{ $combo->combo_name ?? 'Combo' }}"
                                class="w-full h-full object-cover" loading="lazy">
                        </a>
                        <div class="showcase-content px-4 flex flex-col justify-between h-full relative">

                            @if(!empty($combo->combo_name))
                                <h3 class="showcase-title text-xl font-semibold mb-2">
                                    {{ \Illuminate\Support\Str::limit($combo->combo_name, 30, '...') }}
                                </h3>
                            @endif

                            @if(isset($combo->products) && $combo->products->first() && $combo->products->first()->product && $combo->products->first()->product->shop)
                                <p class="text-sm text-gray-600 mb-3 flex items-center gap-1">
                                    <i class="fa-solid fa-store"></i>
                                    Shop: <a
                                        href="{{ route('shop.profile', $combo->products->first()->product->shop->id) }}"
                                        class="text-blue-600 hover:underline">{{ $combo->products->first()->product->shop->shop_name }}</a>
                                </p>
                            @endif
                            <div class="combo-products-list flex flex-wrap gap-2 mb-4">
                                @if(isset($combo->products) && $combo->products->count())
                                    @foreach ($combo->products as $comboProduct)
                                        @if($comboProduct->product)
                                            <div
                                                class="combo-product-item flex items-center gap-2 bg-gray-100 px-2 py-1 rounded-md text-xs text-gray-700">
                                                <img src="{{ $comboProduct->product->defaultImage ? asset('storage/' . $comboProduct->product->defaultImage->image_path) : asset('images/default.jpg') }}"
                                                    alt="{{ $comboProduct->product->name ?? 'Sản phẩm' }}"
                                                    class="w-6 h-6 object-cover rounded" loading="lazy">
                                                @if(!empty($comboProduct->product->slug))
                                                    <a href="{{ route('product.show', $comboProduct->product->slug) }}"
                                                        class="hover:underline">
                                                        <span>{{ \Illuminate\Support\Str::limit($comboProduct->product->name, 15) }}</span>
                                                    </a>
                                                @else
                                                    <span>{{ \Illuminate\Support\Str::limit($comboProduct->product->name, 15) }}</span>
                                                @endif
                                                @if(!empty($comboProduct->quantity))
                                                    <span class="text-gray-500"> (x{{ $comboProduct->quantity }})</span>
                                                @endif
                                                @if(isset($comboProduct->product->display_price))
                                                    <span
                                                        class="font-semibold text-gray-800 ml-auto">{{ number_format($comboProduct->product->display_price, 0, ',', '.') }}₫</span>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            @if(isset($combo->total_price))
                                @php
                                    $originalPrice = (int) ($combo->total_price ?? 0);
                                    $discountValue = (int) ($combo->discount_value ?? 0);
                                    $finalPrice = max(0, $originalPrice - $discountValue);
                                @endphp
                                <div class="price-box flex items-baseline gap-2 mt-auto">
                                    <p class="price text-red-600 text-xl font-bold">
                                        {{ number_format($finalPrice, 0, ',', '.') }}₫</p>
                                    @if($originalPrice > 0)
                                        <del class="text-gray-500 text-sm">
                                            {{ number_format($originalPrice, 0, ',', '.') }}₫</del>
                                    @endif
                                    @if ($discountValue > 0 && $originalPrice > 0)
                                        <span
                                            class="bg-green-100 text-green-600 px-2 py-0.5 rounded-full text-xs font-semibold">
                                            -{{ round(($discountValue / $originalPrice) * 100) }}%
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
