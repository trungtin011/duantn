<div class="quick-view-inner" data-product-id="{{ $product->id }}">
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
                        data-src="{{ asset('storage/' . $image->image_path) }}" loading="lazy">
                @endforeach

                @if ($product->images->isEmpty())
                    <img src="{{ asset('storage/product_images/default.jpg') }}" alt="Ảnh mặc định"
                        class="w-[100px] h-[100px] object-cover rounded cursor-pointer sub-image" loading="lazy">
                @endif
            </div>
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="flex flex-col gap-3">
            <h2 class="text-2xl font-bold text-gray-900" title="{{ $product->name }}">
                {{ \Illuminate\Support\Str::limit($product->name, 50, '...') }}
            </h2>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-yellow-400 flex">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= round($product->reviews->avg('rating')))
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
                        ({{ $product->reviews->count() }} đánh giá) | Đã bán:
                        {{ $product->sold_quantity >= 1000 ? number_format($product->sold_quantity / 1000, 1) . 'k' : $product->sold_quantity }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-2" id="price-display"
                data-price="{{ $product->display_price }}"
                data-original-price="{{ $product->display_original_price }}">
                <span class="text-red-600 text-2xl font-bold">
                    {{ number_format($product->display_price, 0, ',', '.') }} VNĐ
                </span>
                @if ($product->display_price < $product->display_original_price)
                    <span class="text-gray-500 line-through text-md">
                        {{ number_format($product->display_original_price, 0, ',', '.') }} VNĐ
                    </span>
                    <span class="bg-red-100 text-red-600 px-3 py-1 rounded text-xs">
                        -{{ round((($product->display_original_price - $product->display_price) / $product->display_original_price) * 100) }}%
                    </span>
                @endif
            </div>
            <p class="text-gray-700 text-base leading-relaxed">{!! $product->meta_description ?? Str::limit(strip_tags($product->description), 200) !!}</p>

            <!-- Thuộc tính của sản phẩm -->
            <div class="flex flex-col gap-6">
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
                                    @endphp
                                    <button
                                        class="border border-gray-300 rounded-lg px-4 py-2 flex items-center gap-2 hover:bg-gray-100 transition-colors"
                                        data-value="{{ $value }}"
                                        data-price="{{ $variantId ? $variantData[$variantId]['price'] : $product->sale_price ?? $product->price }}"
                                        data-stock="{{ $variantId ? $variantData[$variantId]['stock'] : $product->stock_total }}"
                                        data-variant-id="{{ $variantId }}"
                                        data-attribute-name="{{ $attributeName }}">
                                        @if (isset($attributeImages[$attributeName][$value]))
                                            <img src="{{ $attributeImages[$attributeName][$value] }}" width="24"
                                                height="24" class="rounded" loading="lazy">
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
                        value="{{ $product->variants->isEmpty() ? 'default' : '' }}">
                    <button type="button" id="decreaseQty"
                        class="border border-gray-300 px-4 py-2 rounded-l-lg hover:bg-gray-100 text-lg">-</button>
                    <input type="text" name="quantity" id="quantity" value="1"
                        class="w-20 text-center px-3 py-2 border-t border-b border-gray-300 focus:outline-none text-lg">
                    <button type="button" id="increaseQty"
                        class="border border-gray-300 px-4 py-2 rounded-r-lg hover:bg-gray-100 text-lg">+</button>
                </form>
                <span class="text-sm text-gray-600" id="stock_info" data-stock="{{ $product->stock_total }}">
                    {{ $product->stock_total }} sản phẩm có sẵn
                </span>
            </div>

            <!-- Nút hành động -->
            <div class="mt-6">
                <button
                    class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 flex items-center gap-2 add-to-cart"
                    data-product-id="{{ $product->id }}">
                    <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ hàng
                </button>
            </div>
        </div>
    </div>
</div>