<div class="mb-4">
    <h3 class="font-semibold text-sm mb-2 text-gray-700">Thương hiệu</h3>
    @if($brands && $brands->isNotEmpty())
        @foreach ($brands as $brand)
            @if($brand && $brand->product_count > 0)
                <div class="brand-group mb-1">
                    <div class="flex items-center bg-white rounded-md px-2 py-1">
                        <label class="flex items-center space-x-2 w-full cursor-pointer">
                            <input type="checkbox" class="filter-checkbox" name="brand[]"
                                value="{{ $brand->id }}"
                                {{ in_array($brand->id, request('brand', [])) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-800">{{ $brand->name }} <span
                                    class="text-gray-500">({{ $brand->product_count }})</span></span>
                        </label>
                    </div>
                    @if ($brand->subBrands && $brand->subBrands->isNotEmpty())
                        <div id="brand-dropdown-{{ $brand->id }}" class="ml-4 mt-1 space-y-1">
                            @foreach ($brand->subBrands as $sub)
                                @if($sub && ($sub->product_count ?? 0) > 0)
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" class="filter-checkbox" name="brand[]"
                                            value="{{ $sub->id }}"
                                            {{ in_array($sub->id, request('brand', [])) ? 'checked' : '' }}>
                                        <span class="text-sm text-gray-700">-- {{ $sub->name }} <span
                                                class="text-gray-500">({{ $sub->product_count ?? 0 }})</span></span>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        @endforeach
    @endif
</div>
