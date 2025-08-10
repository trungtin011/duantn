<div class="mb-4">
    <h3 class="font-semibold text-sm mb-2 text-gray-700">Danh mục</h3>
    @foreach ($categories as $cat)
        <div class="category-group mb-1">
            <div class="flex items-center bg-white rounded-md px-2 py-1">
                <label class="flex items-center space-x-2 w-full cursor-pointer">
                    <input type="checkbox" class="filter-checkbox" name="category[]"
                        value="{{ $cat->id }}"
                        {{ in_array($cat->id, request('category', [])) ? 'checked' : '' }}>
                    <span class="text-sm text-gray-800">{{ $cat->name }} <span
                            class="text-gray-500">({{ $cat->product_count }})</span></span>
                </label>
            </div>
            @if ($cat->subCategories->isNotEmpty())
                <div id="dropdown-{{ $cat->id }}" class="ml-4 mt-1 space-y-1">
                    @foreach ($cat->subCategories as $sub)
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" class="filter-checkbox" name="category[]"
                                value="{{ $sub->id }}"
                                {{ in_array($sub->id, request('category', [])) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">-- {{ $sub->name }} <span
                                    class="text-gray-500">({{ $sub->product_count ?? 0 }})</span></span>
                        </label>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
</div>
