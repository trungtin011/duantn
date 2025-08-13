@if (!$comboProducts->isEmpty())
    <div class="product-featured snow-container">
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
                    <a href="{{ route('combo.show', $combo->id) }}"
                        class="text-[#ef3248] hover:underline text-sm font-medium whitespace-nowrap absolute top-5 right-5">
                        Chi tiết
                    </a>
                    
                    <!-- Mobile Layout -->
                    <div class="block md:hidden">
                        <div class="flex flex-col gap-4">
                            <!-- Combo Image -->
                            <div class="flex-shrink-0">
                                <a href="{{ route('combo.show', $combo->id) }}" class="block">
                                    <img src="{{ asset('storage/' . $combo->image) }}" alt="{{ $combo->combo_name }}"
                                        class="w-full h-48 object-cover rounded-lg" loading="lazy">
                                </a>
                            </div>
                            
                            <!-- Combo Content -->
                            <div class="px-4">
                                <h3 class="showcase-title text-xl font-semibold mb-2">
                                    {{ \Illuminate\Support\Str::limit($combo->combo_name, 30, '...') }}
                                </h3>
                                <p class="text-sm text-gray-600 mb-3 flex items-center gap-1">
                                    <i class="fa-solid fa-store"></i>
                                    Shop: <a href="{{ route('shop.profile', $combo->products->first()->product->shop->id) }}"
                                        class="text-blue-600 hover:underline">{{ $combo->products->first()->product->shop->shop_name }}</a>
                                </p>
                                
                                <!-- Products List Mobile -->
                                <div class="space-y-2 mb-4">
                                    @foreach ($combo->products->take(3) as $comboProduct)
                                        @if($comboProduct->product)
                                            <div class="flex items-center gap-3 bg-gray-50 px-3 py-2 rounded-md">
                                                <img src="{{ $comboProduct->product->defaultImage ? asset('storage/' . $comboProduct->product->defaultImage->image_path) : asset('images/default.jpg') }}"
                                                    alt="{{ $comboProduct->product->name }}"
                                                    class="w-10 h-10 object-cover rounded" loading="lazy">
                                                <div class="flex-1 min-w-0">
                                                    <a href="{{ route('product.show', $comboProduct->product->slug) }}"
                                                        class="block text-sm font-medium text-gray-800 hover:text-blue-600 truncate">
                                                        {{ \Illuminate\Support\Str::limit($comboProduct->product->name, 20) }}
                                                    </a>
                                                    <div class="flex items-center justify-between mt-1">
                                                        <span class="text-xs text-gray-500">(x{{ $comboProduct->quantity }})</span>
                                                        <span class="text-sm font-semibold text-gray-800">
                                                            {{ number_format($comboProduct->product->display_price, 0, ',', '.') }}₫
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                    
                                    @if($combo->products->count() > 3)
                                        <div class="text-center text-sm text-gray-500 py-2">
                                            +{{ $combo->products->count() - 3 }} sản phẩm khác
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Price Summary Mobile -->
                                <div class="border-t pt-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-baseline gap-2">
                                            <span class="text-xl font-bold text-red-600">
                                                {{ number_format($combo->total_price - $combo->discount_value, 0, ',', '.') }}₫
                                            </span>
                                            <del class="text-sm text-gray-500">
                                                {{ number_format($combo->total_price, 0, ',', '.') }}₫
                                            </del>
                                        </div>
                                        @if ($combo->discount_value > 0)
                                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">
                                                -{{ round(($combo->discount_value / $combo->total_price) * 100) }}%
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Desktop Layout (giữ nguyên như cũ) -->
                    <div class="hidden md:flex gap-4 w-full">
                        <a href="{{ route('combo.show', $combo->id) }}"
                            class="showcase-banner block w-full h-48 overflow-hidden rounded-lg bg-gray-200 mb-4">
                            <img src="{{ asset('storage/' . $combo->image) }}" alt="{{ $combo->combo_name }}"
                                class="w-full h-full object-cover" loading="lazy">
                        </a>
                        <div class="showcase-content px-4 flex flex-col justify-between h-full relative">
                            <h3 class="showcase-title text-xl font-semibold mb-2">
                                {{ \Illuminate\Support\Str::limit($combo->combo_name, 30, '...') }}
                            </h3>
                            <p class="text-sm text-gray-600 mb-3 flex items-center gap-1">
                                <i class="fa-solid fa-store"></i>
                                Shop: <a href="{{ route('shop.profile', $combo->products->first()->product->shop->id) }}"
                                    class="text-blue-600 hover:underline">{{ $combo->products->first()->product->shop->shop_name }}</a>
                            </p>
                            <div class="combo-products-list flex flex-wrap gap-2 mb-4">
                                @foreach ($combo->products as $comboProduct)
                                    @if($comboProduct->product)
                                        <div class="combo-product-item flex items-center gap-2 bg-gray-100 px-2 py-1 rounded-md text-xs text-gray-700">
                                            <img src="{{ $comboProduct->product->defaultImage ? asset('storage/' . $comboProduct->product->defaultImage->image_path) : asset('images/default.jpg') }}"
                                                alt="{{ $comboProduct->product->name }}"
                                                class="w-6 h-6 object-cover rounded" loading="lazy">
                                            <a href="{{ route('product.show', $comboProduct->product->slug) }}"
                                                class="hover:underline">
                                                <span>{{ \Illuminate\Support\Str::limit($comboProduct->product->name, 15) }}</span>
                                            </a>
                                            <span class="text-gray-500"> (x{{ $comboProduct->quantity }})</span>
                                            <span class="font-semibold text-gray-800 ml-auto">{{ number_format($comboProduct->product->display_price, 0, ',', '.') }}₫</span>
                                        </div>
                                    @else
                                        <div class="combo-product-item flex items-center gap-2 bg-gray-100 px-2 py-1 rounded-md text-xs text-gray-700">
                                            <img src="{{ asset('images/default.jpg') }}" alt="Không xác định" class="w-6 h-6 object-cover rounded" loading="lazy">
                                            <span class="text-gray-400">Không xác định</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="price-box flex items-baseline gap-2 mt-auto">
                                <p class="price text-red-600 text-xl font-bold">
                                    {{ number_format($combo->total_price - $combo->discount_value, 0, ',', '.') }}₫</p>
                                <del class="text-gray-500 text-sm">
                                    {{ number_format($combo->total_price, 0, ',', '.') }}₫</del>
                                @if ($combo->discount_value > 0)
                                    <span class="bg-green-100 text-green-600 px-2 py-0.5 rounded-full text-xs font-semibold">
                                        -{{ round(($combo->discount_value / $combo->total_price) * 100) }}%
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
