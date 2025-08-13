<div class="mb-6">
    <h3 class="font-semibold text-sm mb-3 text-gray-700">Thương hiệu</h3>
    @if($brands && $brands->isNotEmpty())
        <div class="space-y-2">
            <ul class="space-y-1">
                @foreach ($brands as $brand)
                    @include('partials.brand_tree_node', ['node' => $brand, 'level' => 0])
                @endforeach
            </ul>
        </div>
    @else
        <div class="text-center py-4 text-gray-500 text-sm">
            <p>Không có thương hiệu nào</p>
        </div>
    @endif
</div>
