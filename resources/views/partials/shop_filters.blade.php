<div class="mb-4">
    <h3 class="font-semibold text-sm mb-2 text-gray-700">Cửa hàng</h3>
    @if($shops && $shops->isNotEmpty())
        @foreach ($shops as $shop)
            @if($shop && isset($shop->id) && isset($shop->name) && ($shop->products_count ?? 0) > 0)
                <div class="shop-group mb-1">
                    <div class="flex items-center bg-white rounded-md px-2 py-1">
                        <label class="flex items-center space-x-2 w-full cursor-pointer">
                            <input type="checkbox" class="filter-checkbox" name="shop[]"
                                value="{{ $shop->id }}"
                                {{ in_array($shop->id, request('shop', [])) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-800">{{ $shop->name }} <span
                                    class="text-gray-500">({{ $shop->products_count ?? 0 }})</span></span>
                        </label>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
</div>
