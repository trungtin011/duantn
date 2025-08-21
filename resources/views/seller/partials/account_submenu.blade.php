@if (session('success'))
    <div
        class="bg-green-50 border-l-4 border-green-400 text-green-700 px-4 py-3 rounded-md mb-4 text-sm flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-400 text-red-700 px-4 py-3 rounded-md mb-4 text-sm">
        <ul class="mb-0 pl-5 list-disc space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="bg-white rounded-lg p-3 shadow-sm mb-3">
    <nav class="flex items-center gap-3 text-xs">
        <a href="{{ route('seller.settings') }}"
            class="px-3 py-2 rounded-md {{ request()->routeIs('seller.settings') ? 'bg-black text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            Cài đặt cửa hàng
        </a>
        <a href="{{ route('seller.profile') }}"
            class="px-3 py-2 rounded-md {{ request()->routeIs('seller.profile') ? 'bg-black text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            Hồ sơ
        </a>
        <a href="{{ route('seller.password') }}"
            class="px-3 py-2 rounded-md {{ request()->routeIs('seller.password*') ? 'bg-black text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            Mật khẩu
        </a>
    </nav>
</div>
