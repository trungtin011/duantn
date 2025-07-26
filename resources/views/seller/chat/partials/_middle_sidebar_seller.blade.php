<section class="flex flex-col w-80 border-r border-gray-300 bg-white">
    <!-- Header -->
    <header class="flex items-center justify-between h-10 px-3 border-b border-gray-300 text-sm text-gray-700 font-semibold select-none">
        <span>Khách hàng của {{ $shop->shop_name }}</span>
    </header>
    <!-- Search and icons -->
    <div class="flex items-center space-x-2 px-3 py-2 border-b border-gray-200">
        <div class="relative flex-1">
            <input class="w-full h-8 pl-9 pr-3 rounded bg-gray-100 text-gray-600 text-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Tìm kiếm khách hàng" type="text"/>
            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
        </div>
    </div>
    <!-- Tabs -->
    <nav class="flex text-xs font-semibold text-blue-600 border-b border-gray-200 select-none px-3">
        <button class="flex-1 py-2 border-b-2 border-blue-600" id="all-chats-tab">Tất cả</button>
        <button class="flex-1 py-2 text-gray-500" id="unread-chats-tab">Chưa đọc</button>
    </nav>
    <!-- Chat list -->
    <ul class="flex-1 overflow-y-auto divide-y divide-gray-200" id="customer-list">
        @foreach($customers as $customer)
            <li class="customer-btn flex items-start px-3 py-2 space-x-2 hover:bg-gray-100 cursor-pointer"
                data-customer-id="{{ $customer->id }}" data-customer-name="{{ $customer->fullname ?? $customer->username ?? $customer->email }}">
                <img alt="Customer Avatar" class="rounded-full w-10 h-10" height="40" src="{{ $customer->avatar ? \Illuminate\Support\Facades\Storage::url($customer->avatar) : asset('images/default_avatar.png') }}" width="40"/>
                <div class="flex-1">
                    <div class="flex items-center justify-between text-sm font-semibold text-gray-900">
                        <span>{{ $customer->fullname ?? $customer->username ?? $customer->email }}</span>
                    </div>
                    <p class="text-xs text-gray-500 truncate">
                        {{-- Last message placeholder --}}
                    </p>
                </div>
                @if(isset($unreadCounts[$customer->id]) && $unreadCounts[$customer->id] > 0)
                    <div class="flex items-center justify-center bg-red-600 text-white rounded-full w-5 h-5 text-xs font-semibold select-none">
                        {{ $unreadCounts[$customer->id] > 99 ? '99+' : $unreadCounts[$customer->id] }}
                    </div>
                @endif
            </li>
        @endforeach
    </ul>
</section> 