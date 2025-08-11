<div class="mb-4">
    @if($categories && $categories->isNotEmpty())
        @foreach ($categories as $cat)
            @if($cat && $cat->product_count > 0)
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
                    @if ($cat->subCategories && $cat->subCategories->isNotEmpty())
                        <div id="dropdown-{{ $cat->id }}" class="ml-4 mt-1 space-y-1">
                            @foreach ($cat->subCategories as $sub)
                                @if($sub && ($sub->product_count ?? 0) > 0)
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" class="filter-checkbox" name="category[]"
                                            value="{{ $sub->id }}"
                                            {{ in_array($sub->id, request('category', [])) ? 'checked' : '' }}>
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
