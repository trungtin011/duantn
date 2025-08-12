<div class="mb-4">
    <h3 class="font-semibold text-sm mb-2 text-gray-700">Danh mục</h3>
    @if($categories && $categories->isNotEmpty())
        @foreach ($categories as $cat)
            @if($cat && isset($cat->id) && isset($cat->name) && ($cat->products_count ?? 0) > 0)
                <div class="category-group mb-1">
                    <div class="flex items-center bg-white rounded-md px-2 py-1">
                        <label class="flex items-center space-x-2 w-full cursor-pointer">
                            <input type="checkbox" class="filter-checkbox" name="category[]"
                                value="{{ $cat->id }}"
                                {{ in_array($cat->id, request('category', [])) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-800">{{ $cat->name }} <span
                                    class="text-gray-500">({{ $cat->products_count ?? 0 }})</span></span>
                        </label>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
</div>
