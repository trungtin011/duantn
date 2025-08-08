{{-- Debug: Số lượng sản phẩm --}}
@if(isset($products))
    <div class="mb-4 p-2 bg-blue-100 text-blue-800 rounded">
        Debug: Tổng số sản phẩm: {{ $products->total() }}, Số sản phẩm hiện tại: {{ $products->count() }}
    </div>
@endif

<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-4">
    @forelse ($products as $product)
        <div
            class="border border-gray-200 rounded-lg bg-white shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden relative">
            @if ($product->is_featured)
                <div class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full z-10">
                    Nổi bật
                </div>
            @endif
            <a href="{{ route('product.show', $product->slug) }}">
                @php
                    $imagePath = $product->images->isNotEmpty()
                        ? 'storage/' . $product->images->first()->image_path
                        : 'images/placeholder.png';
                @endphp
                <img src="{{ asset($imagePath) }}" class="w-full h-40 object-cover" alt="{{ $product->name }}">
            </a>
            <div class="p-2 text-sm">
                <h3 class="line-clamp-2 min-h-[40px] font-medium text-gray-800 truncate">{{ $product->name }}</h3>
                <div class="flex items-center gap-3 mt-1">
                    <div class="text-red-500 font-bold">{{ number_format($product->sale_price) }}đ</div>
                    <div class="text-gray-400 font-thin text-xs line-through">{{ number_format($product->price) }}đ
                    </div>
                </div>
                <div class="text-xs text-gray-500 mt-1">Đã bán {{ $product->sold_quantity }}</div>
                @if ($product->stock_total < 10 && $product->stock_total > 0)
                    <div class="text-xs text-red-500 mt-1">Chỉ còn {{ $product->stock_total }} sản phẩm</div>
                @endif
            </div>
        </div>
    @empty
        <div class="col-span-full text-center text-gray-500 py-10">Không tìm thấy sản phẩm phù hợp.</div>
    @endforelse
</div>

<div class="mt-6">
    {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
</div>

<div id="loading" class="hidden text-center text-gray-500 py-2">
    <svg class="animate-spin h-5 w-5 mx-auto text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none"
        viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
        </circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8h8a8 8 0 01-8 8 8 8 0 01-8-8z"></path>
    </svg>
    Đang tải...
</div>
