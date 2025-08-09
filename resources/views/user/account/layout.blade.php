@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-0 py-6 md:py-10 flex flex-col md:flex-row gap-6 md:gap-8">
        <!-- Left Sidebar -->
        <div class="w-full md:w-1/4">
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <!-- User Profile Section -->
                <div class="flex items-center gap-4 border-b border-gray-200 pb-6 mb-6">
                    <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                        @include('partials.user-avatar', ['size' => 'lg'])
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg font-semibold text-gray-900">{{ auth()->user()->fullname }}</span>
                        <a class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1" href="{{ route('account.profile') }}">
                            <svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.54 0L6.987 1.56l3.46 3.48L12 3.48M0 8.52l.073 3.428L3.46 12l6.21-6.18-3.46-3.48" fill="currentColor" fill-rule="evenodd"></path>
                            </svg>
                            Sửa hồ sơ
                        </a>
                    </div>
                </div>

                <!-- Rank and Experience Section -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                            @switch($user->rank)
                                @case('iron')
                                    <i class="fas fa-circle text-gray-400 text-sm"></i>
                                @break
                                @case('bronze')
                                    <i class="fas fa-circle text-yellow-500 text-sm"></i>
                                @break
                                @case('silver')
                                    <i class="fas fa-circle text-gray-300 text-sm"></i>
                                @break
                                @case('gold')
                                    <i class="fas fa-circle text-yellow-600 text-sm"></i>
                                @break
                                @case('diamond')
                                    <i class="fas fa-gem text-blue-500 text-sm"></i>
                                @break
                                @case('supreme')
                                    <i class="fas fa-crown text-purple-600 text-sm"></i>
                                @break
                                @default
                                    <i class="fas fa-circle text-gray-400 text-sm"></i>
                            @endswitch
                        </div>
                        <span class="text-sm font-medium text-gray-700">
                            @switch($user->rank)
                                @case('iron') Sắt @break
                                @case('bronze') Đồng @break
                                @case('silver') Bạc @break
                                @case('gold') Vàng @break
                                @case('diamond') Kim Cương @break
                                @case('supreme') Chí Tôn @break
                                @default Sắt
                            @endswitch
                        </span>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-gray-600 mb-2">Kinh nghiệm</div>
                        @php
                            $ranks = [
                                'iron' => ['min' => 0, 'max' => 1000000],
                                'bronze' => ['min' => 1000000, 'max' => 5000000],
                                'silver' => ['min' => 5000000, 'max' => 10000000],
                                'gold' => ['min' => 10000000, 'max' => 20000000],
                                'diamond' => ['min' => 20000000, 'max' => 50000000],
                                'supreme' => ['min' => 50000000, 'max' => PHP_INT_MAX],
                            ];
                            $currentRank = $user->rank ?? 'iron';
                            $currentRange = $ranks[$currentRank];
                            $currentMin = $currentRange['min'];
                            $currentMax = $currentRange['max'];
                            $nextRankIndex = array_search($currentRank, array_keys($ranks)) + 1;
                            $nextThreshold = $nextRankIndex < count($ranks) ? $ranks[array_keys($ranks)[$nextRankIndex]]['min'] : $currentMax;
                            $progress = min(100, max(0, ($user->total_spent / $nextThreshold) * 100));
                            $remaining = $nextThreshold > $user->total_spent ? $nextThreshold - $user->total_spent : 0;
                        @endphp
                        <div class="text-sm font-bold text-gray-900 mb-2">{{ number_format($user->total_spent, 0, ',', '.') }}</div>
                        <div class="relative w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <ul class="space-y-2">
                    <li>
                        <div class="mb-2">
                            <button class="w-full text-left flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors" onclick="toggleDropdown()">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                <span class="font-medium">Tài khoản của tôi</span>
                                <svg class="w-4 h-4 ml-auto transition-transform" id="dropdownArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="accountDropdown" class="hidden ml-8 space-y-1">
                                <a href="{{ route('account.profile') }}" class="block px-3 py-2 text-sm rounded hover:bg-gray-50 transition-colors" id="profile-link">Hồ sơ</a>
                                <a href="{{ route('account.addresses') }}" class="block px-3 py-2 text-sm rounded hover:bg-gray-50 transition-colors" id="address-link">Địa chỉ</a>
                                <a href="{{ route('account.password') }}" class="block px-3 py-2 text-sm rounded hover:bg-gray-50 transition-colors" id="password-link">Đổi mật khẩu</a>
                                <a href="{{ route('account.coupons') }}" class="block px-3 py-2 text-sm rounded hover:bg-gray-50 transition-colors" id="coupons-link">Mã giảm giá đã lưu</a>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a href="{{ route('wishlist') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                            </svg>
                            <span>Yêu thích</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('account.points') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3" />
                            </svg>
                            <span>Nhận xu</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            @yield('account-content')
        </div>
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('accountDropdown');
            const arrow = document.getElementById('dropdownArrow');
            
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
            
            if (arrow) {
                arrow.classList.toggle('rotate-180');
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            const profileLink = document.getElementById('profile-link');
            const addressLink = document.getElementById('address-link');
            const passwordLink = document.getElementById('password-link');
            const couponsLink = document.getElementById('coupons-link');
            const dropdown = document.getElementById('accountDropdown');
            const arrow = document.getElementById('dropdownArrow');
            
            const dashboardUrl = "{{ route('account.profile') }}";
            const addressesUrl = "{{ route('account.addresses') }}";
            const passwordUrl = "{{ route('account.password') }}";
            const couponsUrl = "{{ route('account.coupons') }}";

            // Show dropdown and highlight active link
            if (window.location.href === dashboardUrl) {
                if (dropdown) dropdown.classList.remove('hidden');
                if (arrow) arrow.classList.add('rotate-180');
                if (profileLink) profileLink.classList.add('text-red-600', 'font-semibold');
            } else if (window.location.href === addressesUrl) {
                if (dropdown) dropdown.classList.remove('hidden');
                if (arrow) arrow.classList.add('rotate-180');
                if (addressLink) addressLink.classList.add('text-red-600', 'font-semibold');
            } else if (window.location.href === passwordUrl) {
                if (dropdown) dropdown.classList.remove('hidden');
                if (arrow) arrow.classList.add('rotate-180');
                if (passwordLink) passwordLink.classList.add('text-red-600', 'font-semibold');
            } else if (window.location.href === couponsUrl || window.location.href.startsWith(couponsUrl)) {
                if (dropdown) dropdown.classList.remove('hidden');
                if (arrow) arrow.classList.add('rotate-180');
                if (couponsLink) couponsLink.classList.add('text-red-600', 'font-semibold');
            }
        });
    </script>
@endsection
