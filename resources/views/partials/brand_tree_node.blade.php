@php
    $isChecked = in_array($node->id, request('brand', []));
    $count = $node->products_count ?? $node->product_count ?? 0;
    $children = (isset($node->subBrands) && $node->subBrands) ? $node->subBrands : collect();

    $hasVisibleBrand = function($n) use (&$hasVisibleBrand) {
        $cnt = $n->products_count ?? $n->product_count ?? 0;
        $kids = (isset($n->subBrands) && $n->subBrands) ? $n->subBrands : collect();
        if ($cnt > 0) return true;
        foreach ($kids as $k) {
            if ($hasVisibleBrand($k)) return true;
        }
        return false;
    };

    $shouldShow = $isChecked || !request('query') || $hasVisibleBrand($node);

    $filteredChildren = $children->filter(function($c) use ($hasVisibleBrand) {
        $checked = in_array($c->id, request('brand', []));
        return $checked || $hasVisibleBrand($c);
    });

    $hasChildren = $filteredChildren->isNotEmpty();
@endphp

@if($shouldShow)
<li>
    <div class="flex items-start">
        @if($hasChildren)
            <button type="button" class="mt-1 mr-2 text-gray-500 hover:text-gray-700 focus:outline-none toggle-branch"
                aria-expanded="{{ $level < 1 ? 'true' : 'false' }}">
                <svg class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 6a1 1 0 011.707-.707l4 4a1 1 0 010 1.414l-4 4A1 1 0 016 13.586L9.586 10 6 6.414A1 1 0 016 6z" clip-rule="evenodd" />
                </svg>
            </button>
        @else
            <span class="mt-1 mr-2 w-3"></span>
        @endif

        <label class="flex items-center w-full cursor-pointer p-2 rounded-md hover:bg-gray-50 transition-colors duration-200 {{ $isChecked ? 'bg-gray-100 border border-gray-200' : '' }}">
            <input type="checkbox" class="filter-checkbox mr-3" name="brand[]" value="{{ $node->id }}" {{ $isChecked ? 'checked' : '' }}>
            <div class="flex items-center space-x-2 flex-1">
                <span class="text-sm text-gray-700">{{ $node->name }}</span>
                <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-600 ml-auto">{{ $count }}</span>
            </div>
        </label>
    </div>

    @if($hasChildren)
        <ul class="ml-5 border-l pl-3 mt-1 space-y-1 {{ $level < 1 ? '' : 'hidden' }}">
            @foreach($filteredChildren as $child)
                @include('partials.brand_tree_node', ['node' => $child, 'level' => $level + 1])
            @endforeach
        </ul>
    @endif
</li>

@once
    @push('scripts')
        <script>
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.toggle-branch');
                if (!btn) return;
                const li = btn.closest('li');
                if (!li) return;
                const sublist = li.querySelector('ul');
                if (sublist) {
                    const isHidden = sublist.classList.contains('hidden');
                    sublist.classList.toggle('hidden');
                    btn.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
                }
            });
        </script>
    @endpush
@endonce

@endif
