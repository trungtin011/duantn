@if ($advertisedProductsByShop->isNotEmpty())
    @include('partials.advertised_products', [
        'advertisedProductsByShop' => $advertisedProductsByShop,
    ])
@endif

<div class="flex flex-wrap gap-4">
    @forelse ($products as $index => $product)
        <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 min-w-[200px]">
            <div
                class="border border-gray-200 rounded-lg bg-white shadow-sm hover:shadow-lg transition duration-200 overflow-hidden relative">
                @if ($product->is_featured)
                    <div class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full z-10">
                        Nổi bật
                    </div>
                @endif
                <a href="{{ route('product.show', $product->slug) }}">
                    @if ($product->images && $product->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                             class="w-full h-40 object-cover rounded-t-lg"
                             alt="{{ $product->name }}"
                             onerror="this.src='{{ asset('images/avatar.png') }}'">
                    @else
                        <img src="{{ asset('images/avatar.png') }}" 
                             class="w-full h-40 object-cover rounded-t-lg"
                             alt="{{ $product->name }}">
                    @endif
                </a>
                <div class="p-3 text-sm">
                    <h3 class="line-clamp-2 font-medium text-gray-800 min-h-[40px]">{{ $product->name }}</h3>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="text-red-500 font-bold">{{ number_format($product->sale_price) }}đ</span>
                        @if ($product->price > $product->sale_price)
                            <span
                                class="text-gray-400 text-xs line-through">{{ number_format($product->price) }}đ</span>
                        @endif
                    </div>
                    <div class="text-xs text-gray-500 mt-1">Đã bán {{ $product->sold_quantity }}</div>
                    @if ($product->stock_total < 10 && $product->stock_total > 0)
                        <div class="text-xs text-red-500 mt-1">Chỉ còn {{ $product->stock_total }} sản phẩm</div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="w-full text-center text-gray-500 py-10">
            @if(request('query'))
                Không tìm thấy sản phẩm phù hợp với từ khóa "{{ request('query') }}".
            @else
                Không tìm thấy sản phẩm phù hợp.
            @endif
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
</div>

<div id="loading" class="hidden text-center text-gray-500 py-4 animate-fade">
    <svg class="animate-spin h-5 w-5 mx-auto text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none"
        viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8h8a8 8 0 01-8 8 8 8 0 01-8-8z" />
    </svg>
    <p class="mt-2">Đang tải...</p>
</div>
