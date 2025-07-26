<section class="flex flex-col w-80 border-r border-gray-300 bg-white">
    <!-- Header -->
    <header class="flex items-center justify-between h-10 px-3 border-b border-gray-300 text-sm text-gray-700 font-semibold select-none">
        <span>ZynoxMall Chat</span>
    </header>
    <!-- Search and icons -->
    <div class="flex items-center space-x-2 px-3 py-2 border-b border-gray-200">
        <div class="relative flex-1">
            <input class="w-full h-8 pl-9 pr-3 rounded bg-gray-100 text-gray-600 text-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Tìm kiếm shop" type="text"/>
            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
        </div>
        {{-- <button aria-label="Add friend" class="w-7 h-7 flex items-center justify-center text-gray-600 hover:text-gray-900">
            <i class="fas fa-user-plus text-lg"></i>
        </button>
        <button aria-label="Add group" class="w-7 h-7 flex items-center justify-center text-gray-600 hover:text-gray-900">
            <i class="fas fa-users text-lg"></i>
        </button> --}}
    </div>
    <!-- Tabs -->
    <nav class="flex text-xs font-semibold text-blue-600 border-b border-gray-200 select-none px-3">
        <button class="flex-1 py-2 border-b-2 border-blue-600">Tất cả</button>
        {{-- <button class="flex-1 py-2 text-gray-500">Chưa đọc</button> --}}
        {{-- <button class="flex-1 py-2 text-gray-500 flex items-center justify-end space-x-1">
            <span>Phân loại</span>
            <i class="fas fa-chevron-down text-xs"></i>
        </button> --}}
        {{-- <button class="w-6 flex items-center justify-center text-gray-500">
            <i class="fas fa-ellipsis-h"></i>
        </button> --}}
    </nav>
    <!-- Chat list -->
    <ul class="flex-1 overflow-y-auto divide-y divide-gray-200" id="shop-list">
        @foreach($shopList as $shop)
            <li class="shop-btn flex items-start px-3 py-2 space-x-2 hover:bg-gray-100 cursor-pointer"
                data-shop-id="{{ $shop->id }}" data-shop-name="{{ $shop->shop_name }}">
                <img alt="Shop Logo" class="rounded-full w-10 h-10" height="40" src="{{ $shop->shop_logo ? \Illuminate\Support\Facades\Storage::url($shop->shop_logo) : asset('images/default_shop_logo.png') }}" width="40"/>
                <div class="flex-1">
                    <div class="flex items-center justify-between text-sm font-semibold text-gray-900">
                        <span>{{ $shop->shop_name }}</span>
                        {{-- <span class="text-gray-400 text-xs">3 phút</span> --}}
                    </div>
                    <p class="text-xs text-gray-500 truncate">
                        {{-- Last message placeholder --}}
                    </p>
                </div>
                {{-- <div class="flex items-center justify-center bg-gray-300 rounded-full px-2 text-xs font-semibold text-gray-700 select-none">5+</div> --}}
            </li>
        @endforeach
    </ul>
</section> 