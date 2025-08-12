<div class="mb-4">
    <h3 class="font-semibold text-sm mb-2 text-gray-700">Thương hiệu</h3>
    @if($brands && $brands->isNotEmpty())
        @foreach ($brands as $brand)
            @if($brand && isset($brand->id) && isset($brand->name) && ($brand->products_count ?? 0) > 0)
                <div class="brand-group mb-1">
                    <div class="flex items-center bg-white rounded-md px-2 py-1">
                        <label class="flex items-center space-x-2 w-full cursor-pointer">
                            <input type="checkbox" class="filter-checkbox" name="brand[]"
                                value="{{ $brand->id }}"
                                {{ in_array($brand->id, request('brand', [])) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-800">{{ $brand->name }} <span
                                    class="text-gray-500">({{ $brand->products_count ?? 0 }})</span></span>
                        </label>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
</div>
