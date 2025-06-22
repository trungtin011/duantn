@extends('user.account.profile')

@section('account-content')
    <div class="bg-white px-6 py-4 rounded shadow">
        <!-- Tổng điểm -->
        <div class="mb-3 border-b border-gray-200 pb-4 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    @if ($totalPoints > 0)
                        <span class="text-2xl font-bold text-[#ef3248]">{{ $totalPoints }}</span>
                        <span class="text-sm text-gray-600">Điểm</span>
                    @else
                        <span class="text-2xl font-bold text-gray-400">0</span>
                        <span class="text-sm text-gray-600">Điểm</span>
                    @endif
                </div>
                <div>
                    <a href="" class="text-sm text-[#ef3248] hover:text-red-600">
                        <span class="text-sm">Nhận thêm điểm <i class="fas fa-chevron-right text-[10px]"></i></span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Menu tab lịch sử -->
        <div class="mb-4">
            <div class="border-b border-gray-200">
                <nav class="flex flex justify-center" aria-label="Tabs">
                    <button
                        class="w-1/3 tab-link {{ request()->query('tab') == 'all' || !request()->query('tab') ? 'text-red-500 border-b-2 border-red-500 pb-4' : 'text-gray-500 hover:text-gray-700' }}"
                        onclick="window.location.href='{{ route('account.points') }}?tab=all'">
                        Tất cả lịch sử
                    </button>
                    <button
                        class="w-1/3 tab-link {{ request()->query('tab') == 'received' ? 'text-red-500 border-b-2 border-red-500 pb-4' : 'text-gray-500 hover:text-gray-700' }}"
                        onclick="window.location.href='{{ route('account.points') }}?tab=received'">
                        Đã nhận
                    </button>
                    <button
                        class="w-1/3 tab-link {{ request()->query('tab') == 'used' ? 'text-red-500 border-b-2 border-red-500 pb-4' : 'text-gray-500 hover:text-gray-700' }}"
                        onclick="window.location.href='{{ route('account.points') }}?tab=used'">
                        Đã dùng
                    </button>
                </nav>
            </div>
        </div>

        <!-- Lịch sử giao dịch điểm -->
        <div class="mt-6">
            @if ($points->isEmpty())
                <p class="text-gray-500">Chưa có giao dịch điểm nào.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse">
                        <tbody>
                            @foreach ($points as $transaction)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 w-16">
                                        <div class="flex items-center justify-center w-10 h-10 bg-yellow-400 rounded-full">
                                            <span class="text-white font-bold text-lg">VNĐ</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 w-2/3">
                                        <div>
                                            <p class="text-sm text-gray-800">
                                                {{ $transaction->description ?? 'Không có mô tả' }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ $transaction->created_at->format('d-m-Y H:i') }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right w-1/3">
                                        <span
                                            class="text-lg font-semibold {{ $transaction->points >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaction->points > 0 ? '+' : '' }}{{ $transaction->points }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Phân trang -->
                <div class="mt-4">
                    {{ $points->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
