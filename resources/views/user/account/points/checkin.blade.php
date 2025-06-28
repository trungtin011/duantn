@extends('user.account.profile')

@section('account-content')
    <div class="bg-white px-6 py-4 rounded shadow">
        <div class="py-10 w-1/2 p-3 rounded" style="background: linear-gradient(to top, #ec838f, #EF3248)">
            <h2 class="text-2xl font-semibold mb-4 text-center text-white">Ưu đãi ZyNox Xu</h2>
            <div class="bg-white px-6 py-4 rounded shadow">
                <div class="grid grid-cols-6 gap-2 text-center mb-6">
                    @foreach ($weekDates as $date)
                        @php
                            $isCheckedIn = in_array($date->format('Y-m-d'), $checkinDates);
                            $isToday = $date->isToday();

                            $weekday = match ($date->dayOfWeek) {
                                1 => 'Thứ 2',
                                2 => 'Thứ 3',
                                3 => 'Thứ 4',
                                4 => 'Thứ 5',
                                5 => 'Thứ 6',
                                6 => 'Thứ 7',
                                default => '',
                            };

                            // Tìm giao dịch điểm danh trong ngày
                            $transaction = $checkins->firstWhere(
                                fn($item) => $item->created_at->format('Y-m-d') === $date->format('Y-m-d'),
                            );

                            // Mặc định: điểm = 100, Thứ 7 = 200
                            $defaultPoint = $date->dayOfWeek == 6 ? 200 : 100;

                            // Nếu đã điểm danh, lấy điểm thực tế
                            $pointForDay = $transaction?->points ?? $defaultPoint;

                            $classes =
                                'py-2 px-2 rounded text-center ' . ($isCheckedIn ? 'bg-gray-200' : 'bg-white border');
                            if ($isToday) {
                                $classes .= ' ring-2 ring-red-500';
                            }
                        @endphp

                        <div class="{{ $classes }}">
                            <div class="text-xs {{ $pointForDay > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $pointForDay > 0 ? '+' : '' }}{{ $pointForDay }} điểm
                            </div>
                            <div class="font-semibold text-sm">{{ $weekday }}</div>
                            <div class="text-xs text-gray-400">{{ $date->format('d/m') }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-center">
                    @if (!$checkinToday)
                        <form action="{{ route('account.checkin.store') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">
                                Điểm danh hôm nay
                            </button>
                        </form>
                    @else
                        <span class="bg-red-500/10 py-2 px-4 rounded-full">
                            Quay lại vào ngày mai để nhận 100 xu
                        </span>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection
