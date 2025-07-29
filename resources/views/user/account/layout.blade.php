@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-10 flex gap-8">
        <div class="w-1/6">
            <div class="p-4 rounded">
                <div class="flex gap-5 border-b border-[#efefef] pb-5 mb-8">
                    <div class="">
                        <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('images/default-avatar.png') }}"
                            class="w-[28px] h-[28px] rounded-full object-cover">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold">{{ auth()->user()->fullname }}</span>
                        <div>
                            <a class="flex items-center text-[#9B9B9B]" href="{{ route('account.profile') }}">
                                <svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg"
                                    style="margin-right: 4px;">
                                    <path
                                        d="M8.54 0L6.987 1.56l3.46 3.48L12 3.48M0 8.52l.073 3.428L3.46 12l6.21-6.18-3.46-3.48"
                                        fill="#9B9B9B" fill-rule="evenodd">
                                    </path>
                                </svg>
                                Sửa hồ sơ
                            </a>
                            
                        </div>
                    </div>
                </div>
               <!-- Rank and Experience Section -->
<div class="bg-gray-50 rounded-lg p-4 mb-8">
    <div class="flex items-center space-x-3 mb-4">
        <span class="text-xl">
            @switch($user->rank)
                @case('iron') <i class="fas fa-circle text-gray-400"></i> @break
                @case('bronze') <i class="fas fa-circle text-yellow-500"></i> @break
                @case('silver') <i class="fas fa-circle text-gray-300"></i> @break
                @case('gold') <i class="fas fa-circle text-yellow-600"></i> @break
                @case('diamond') <i class="fas fa-gem text-blue-500"></i> @break
                @case('supreme') <i class="fas fa-crown text-purple-600"></i> @break
                @default <i class="fas fa-question"></i> @endswitch
        </span>
        <span class="text-xl font-bold text-gray-800">
            @switch($user->rank)
                @case('iron') Sắt @break
                @case('bronze') Đồng @break
                @case('silver') Bạc @break
                @case('gold') Vàng @break
                @case('diamond') Kim Cương @break
                @case('supreme') Chí Tôn @break
                @default Không xác định @endswitch
        </span>
    </div>
    <div>
        <div class="text-sm font-medium text-gray-600 mb-2">Kinh nghiệm</div>
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

            // Tính tiến độ dựa trên tổng số tiền so với ngưỡng tiếp theo
            $progress = min(100, max(0, ($user->total_spent / $nextThreshold) * 100));
            $remaining = $nextThreshold > $user->total_spent ? $nextThreshold - $user->total_spent : 0;
        @endphp
        <div class="relative w-full bg-gray-200 rounded-full h-8 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-green-600 h-8 rounded-full transition-all duration-300" 
                style="width: {{ $progress }}%"></div>
            <div class="absolute inset-0 flex items-center justify-between px-3 text-xs text-black font-medium">
                <span>{{ number_format($user->total_spent, 0, ',', '.') }}</span>
                <span>{{ number_format($nextThreshold, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>
                <ul class="space-y-3 min-h-screen">
                    <li>
                        <div id="dropdownToggle">
                            <div class="flex items-center gap-1">
                                <button class="toggle-dropdown cursor-pointer hover:text-[#ef3248] flex items-center gap-1"
                                    onclick="navigateToDashboard()">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                    Tài khoản của tôi
                                </button>
                            </div>
                            <div id="dropdown" class="hidden opacity-0 translate-y-[-10px] transition-all duration-200">
                                <div class="pl-[34px] flex flex-col py-2 text-gray-500">
                                    <a class="hover:text-[#ef3248] py-1 font-normal" href="{{ route('account.profile') }}"
                                        id="profile-link"><span>Hồ sơ</span></a>
                                    <a class="hover:text-[#ef3248] py-1 font-normal" href="{{ route('account.addresses') }}"
                                        id="address-link"><span>Địa chỉ</span></a>
                                    <a class="hover:text-[#ef3248] py-1 font-normal" href="{{ route('account.password') }}"
                                        id="password-link"><span>Đổi mật khẩu</span></a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a href="{{ route('wishlist') }}" class="hover:text-[#ef3248] flex items-center gap-1">
                            <div class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                </svg>

                                Yêu thích
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('account.points') }}" class="hover:text-[#ef3248] flex items-center gap-1">
                            <div class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3" />
                                </svg>

                                Nhận xu
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="w-full">
            @yield('account-content')
        </div>
    </div>

    <script>
        function navigateToDashboard() {
            window.location.href = "{{ route('account.profile') }}";
        }

        window.addEventListener('DOMContentLoaded', () => {
            const dropdown = document.getElementById('dropdown');
            const profileLink = document.getElementById('profile-link');
            const addressLink = document.getElementById('address-link');
            const passwordLink = document.getElementById('password-link');
            const dashboardUrl = "{{ route('account.profile') }}";
            const addressesUrl = "{{ route('account.addresses') }}";
            const passwordUrl = "{{ route('account.password') }}";

            if (window.location.href === dashboardUrl) {
                dropdown.classList.remove('hidden', 'opacity-0', 'translate-y-[-10px]');
                profileLink.classList.add('text-[#ef3248]', 'font-bold');
            } else if (window.location.href === addressesUrl) {
                dropdown.classList.remove('hidden', 'opacity-0', 'translate-y-[-10px]');
                addressLink.classList.add('text-[#ef3248]', 'font-bold');
            } else if (window.location.href === passwordUrl) {
                dropdown.classList.remove('hidden', 'opacity-0', 'translate-y-[-10px]');
                passwordLink.classList.add('text-[#ef3248]', 'font-bold');
            }
        });
    </script>
@endsection
