<div class="mb-6">
    <h3 class="font-semibold text-sm mb-3 text-gray-700">Danh mục</h3>
    @if($categories && $categories->isNotEmpty())
        <div class="space-y-2">
            <ul class="space-y-1">
                @foreach ($categories as $cat)
                    @include('partials.category_tree_node', ['node' => $cat, 'level' => 0])
                @endforeach
            </ul>
        </div>
    @else
        <div class="text-center py-4 text-gray-500 text-sm">
            <p>Không có danh mục nào</p>
        </div>
    @endif
</div>
