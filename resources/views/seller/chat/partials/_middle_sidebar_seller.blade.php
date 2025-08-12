<section class="flex flex-col w-80 border-r border-gray-300 bg-white">
    <!-- Header -->
    <header
        class="flex items-center gap-3 h-10 px-3 border-b border-gray-300 text-sm text-gray-700 font-semibold select-none">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
        </svg>
        <span>{{ $shop->shop_name }}</span>
    </header>
    <!-- Search and icons -->
    <div class="flex items-center space-x-2 px-3 py-2 border-b border-gray-200">
        <div class="relative flex-1">
            <input
                id="customer-search-input"
                class="w-full h-8 pl-9 pr-3 rounded bg-gray-100 text-gray-600 text-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500"
                placeholder="Tìm kiếm khách hàng" type="text" />
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
        @foreach ($customers as $customer)
            <li class="customer-btn flex items-start px-3 py-2 space-x-2 hover:bg-gray-100 cursor-pointer"
                data-customer-id="{{ $customer->id }}"
                data-customer-name="{{ $customer->fullname ?? ($customer->username ?? $customer->email) }}">
                <img alt="Customer Avatar" class="rounded-full w-10 h-10" height="40"
                    src="{{ $customer->avatar ? \Illuminate\Support\Facades\Storage::url($customer->avatar) : asset('images/default_avatar.png') }}"
                    width="40" />
                <div class="flex-1">
                    <div class="flex items-center justify-between text-sm font-semibold text-gray-900">
                        <span>{{ $customer->fullname ?? ($customer->username ?? $customer->email) }}</span>
                    </div>
                    <p class="text-xs text-gray-500 truncate">
                        {{-- Last message placeholder --}}
                    </p>
                </div>
                @if (isset($unreadCounts[$customer->id]) && $unreadCounts[$customer->id] > 0)
                    <div
                        class="flex items-center justify-center bg-red-600 text-white rounded-full w-5 h-5 text-xs font-semibold select-none">
                        {{ $unreadCounts[$customer->id] > 99 ? '99+' : $unreadCounts[$customer->id] }}
                    </div>
                @endif
            </li>
        @endforeach
    </ul>
</section>
