<section class="flex flex-col w-80 border-r border-gray-200 bg-white">
    <!-- Search and filters -->
    <div class="p-3 border-b border-gray-100 bg-gray-50">
        <div class="relative">
            <input class="w-full h-8 pl-8 pr-3 rounded-lg bg-white border border-gray-200 text-gray-700 text-xs placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ef3248] focus:border-transparent transition-all" 
                   placeholder="Tìm kiếm shop..." 
                   type="text"
                   id="shop-search"/>
            <i class="fas fa-search absolute left-2.5 top-2 text-gray-400 text-xs"></i>
        </div>
        
        <!-- Filter buttons -->
        <div class="flex space-x-1 mt-2">
            <button class="px-2 py-1 text-xs font-medium text-[#ef3248] bg-red-100 rounded-full hover:bg-red-200 transition-colors">
                Tất cả
            </button>
            <button class="px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-full hover:bg-gray-200 transition-colors">
                Chưa đọc
            </button>
        </div>
    </div>
    
    <!-- Chat list -->
    <div class="flex-1 overflow-y-auto">
        <ul class="divide-y divide-gray-100" id="shop-list">
            @foreach($shopList as $shop)
                <li class="shop-item shop-btn group hover:bg-red-50 cursor-pointer transition-all duration-200"
                    data-shop-id="{{ $shop->id }}" 
                    data-shop-name="{{ $shop->shop_name }}">
                    
                    <div class="flex items-center px-3 py-2 space-x-2">
                        <div class="relative">
                            <img alt="Shop Logo" 
                                 class="w-8 h-8 rounded-full border border-gray-200 group-hover:border-[#ef3248] transition-colors" 
                                 src="{{ $shop->shop_logo ? \Illuminate\Support\Facades\Storage::url($shop->shop_logo) : asset('images/default_shop_logo.png') }}"/>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xs font-semibold text-gray-900 truncate group-hover:text-[#ef3248] transition-colors">
                                    {{ $shop->shop_name }}
                                </h3>
                                <!-- Unread count badge next to shop name -->
                                @if(isset($unreadCounts[$shop->id]) && $unreadCounts[$shop->id] > 0)
                                    <span class="unread-count-badge ml-2 w-4 h-4 bg-[#ef3248] text-white text-xs font-bold rounded-full flex items-center justify-center flex-shrink-0">
                                        {{ $unreadCounts[$shop->id] > 99 ? '99+' : $unreadCounts[$shop->id] }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="flex items-center justify-between mt-1">
                                <div class="flex items-center space-x-1">
                                    @if(isset($unreadCounts[$shop->id]) && $unreadCounts[$shop->id] > 0)
                                        <span class="w-4 h-4 bg-[#ef3248] text-white text-xs font-bold rounded-full flex items-center justify-center unread-badge">
                                            {{ $unreadCounts[$shop->id] > 99 ? '99+' : $unreadCounts[$shop->id] }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        
        <!-- Empty state -->
        <div id="empty-state" class="hidden flex flex-col items-center justify-center py-8 text-gray-400">
            <i class="fas fa-search text-2xl mb-2"></i>
            <p class="text-xs">Không tìm thấy shop nào</p>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('shop-search');
    const shopList = document.getElementById('shop-list');
    const emptyState = document.getElementById('empty-state');
    const shopItems = document.querySelectorAll('.shop-item');
    
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        let hasResults = false;
        
        shopItems.forEach(item => {
            const shopName = item.querySelector('h3').textContent.toLowerCase();
            if (shopName.includes(searchTerm)) {
                item.style.display = 'block';
                hasResults = true;
            } else {
                item.style.display = 'none';
            }
        });
        
        if (hasResults) {
            shopList.style.display = 'block';
            emptyState.classList.add('hidden');
        } else {
            shopList.style.display = 'none';
            emptyState.classList.remove('hidden');
        }
    });
});
</script> 