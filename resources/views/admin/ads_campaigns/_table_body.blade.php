@foreach ($campaigns as $campaign)
    <tr>
        <td class="py-4 pr-6">
            <input class="select-item w-[18px] h-[18px]" aria-label="Select {{ $campaign->name }}" type="checkbox" />
        </td>
        <td class="py-4 text-[13px]">{{ $campaign->id }}</td>
        <td class="py-4 text-[13px]">{{ $campaign->name }}</td>
        <td class="py-4 text-[13px]">{{ $campaign->shop->shop_name ?? ('Shop #'.$campaign->shop_id) }}</td>
        <td class="py-4 text-[13px]">
            {{ optional($campaign->start_date)->format('d/m/Y') }} - {{ optional($campaign->end_date)->format('d/m/Y') }}
        </td>
        <td class="py-4 text-[13px]">{{ number_format((float)($campaign->bid_amount ?? 0), 0, ',', '.') }} đ</td>
        <td class="py-4">
            <span class="inline-block {{ $campaign->status === 'active' ? 'bg-green-100 text-green-600' : ($campaign->status === 'pending' ? 'bg-yellow-100 text-yellow-600' : ($campaign->status === 'ended' ? 'bg-gray-100 text-gray-600' : 'bg-red-100 text-red-600')) }} text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                @switch($campaign->status)
                    @case('pending')
                        Chờ duyệt
                        @break
                    @case('active')
                        Hoạt động
                        @break
                    @case('ended')
                        Đã kết thúc
                        @break
                    @case('cancelled')
                        Đã hủy
                        @break
                    @default
                        {{ ucfirst($campaign->status) }}
                @endswitch
            </span>
        </td>
        <td class="py-4 pr-6 text-right flex items-center gap-2 justify-end">
            @if($campaign->status === 'pending' || $campaign->status === 'cancelled')
                <form method="POST" action="{{ route('admin.ads_campaigns.approve', $campaign->id) }}" class="inline">
                    @csrf
                    <button class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-md focus:outline-none" type="submit" title="Duyệt">
                        <i class="fas fa-check text-xs"></i>
                    </button>
                </form>
            @endif
            @if($campaign->status === 'pending' || $campaign->status === 'active')
                <form method="POST" action="{{ route('admin.ads_campaigns.reject', $campaign->id) }}" class="inline">
                    @csrf
                    <button class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md focus:outline-none" type="submit" title="Từ chối">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </form>
            @endif
        </td>
    </tr>
@endforeach
@if ($campaigns->isEmpty())
    <tr>
        <td colspan="8" class="text-center text-gray-400 py-4">Không có chiến dịch nào.</td>
    </tr>
@endif

