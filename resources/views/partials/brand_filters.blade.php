<div class="mb-6">
    <h3 class="font-semibold text-sm mb-3 text-gray-700">Thương hiệu</h3>
    @if($brands && $brands->isNotEmpty())
        <div class="space-y-2">
            @foreach ($brands as $brand)
                @if($brand && isset($brand->id) && isset($brand->name) && ($brand->products_count ?? 0) > 0)
                    <div class="brand-group">
                        <label class="flex items-center w-full cursor-pointer p-2 rounded-md hover:bg-gray-50 transition-colors duration-200 {{ in_array($brand->id, request('brand', [])) ? 'bg-gray-100 border border-gray-200' : '' }}">
                            <input type="checkbox" class="filter-checkbox mr-3" name="brand[]"
                                value="{{ $brand->id }}"
                                {{ in_array($brand->id, request('brand', [])) ? 'checked' : '' }}>
                            
                            <div class="flex items-center space-x-2 flex-1">
                                <span class="text-sm text-gray-700">{{ $brand->name }}</span>
                                <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-600 ml-auto">
                                    {{ $brand->products_count ?? 0 }}
                                </span>
                            </div>
                        </label>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div class="text-center py-4 text-gray-500 text-sm">
            <p>Không có thương hiệu nào</p>
        </div>
    @endif
</div>
