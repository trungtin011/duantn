<div class="mb-6">
    <h3 class="font-semibold text-sm mb-3 text-gray-700">Danh mục</h3>
    @if($categories && $categories->isNotEmpty())
        <div class="space-y-2">
            @foreach ($categories as $cat)
                @if($cat && isset($cat->id) && isset($cat->name) && ($cat->products_count ?? 0) > 0)
                    <div class="category-group">
                        <label class="flex items-center w-full cursor-pointer p-2 rounded-md hover:bg-gray-50 transition-colors duration-200 {{ in_array($cat->id, request('category', [])) ? 'bg-gray-100 border border-gray-200' : '' }}">
                            <input type="checkbox" class="filter-checkbox mr-3" name="category[]"
                                value="{{ $cat->id }}"
                                {{ in_array($cat->id, request('category', [])) ? 'checked' : '' }}>
                            
                            <div class="flex items-center space-x-2 flex-1">
                                <span class="text-sm text-gray-700">{{ $cat->name }}</span>
                                <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-600 ml-auto">
                                    {{ $cat->products_count ?? 0 }}
                                </span>
                            </div>
                        </label>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div class="text-center py-4 text-gray-500 text-sm">
            <p>Không có danh mục nào</p>
        </div>
    @endif
</div>
