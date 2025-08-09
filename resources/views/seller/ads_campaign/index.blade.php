@extends('layouts.seller_home')

    @section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}"> {{-- Tái sử dụng CSS của sản phẩm nếu phù hợp --}}
    @endpush
@endsection

@section('content')
    <div class="admin-page-header mb-5">
        <h1 class="admin-page-title text-2xl">Quản lý quảng cáo</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Home</a> / Danh sách chiến dịch quảng cáo</div>
    </div>

    @include('layouts.notification')

    <section class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4 h-[72px]">
            <form class="w-full md:w-[223px] relative" method="GET" action="{{ route('seller.ads_campaigns.index') }}">
                <input name="search"
                    class="w-full h-[42px] border border-[#F2F2F6] rounded-md py-2 pl-10 pr-4 text-xs placeholder:text-gray-400 focus:outline-none"
                    placeholder="Tìm kiếm chiến dịch" type="text" value="{{ request('search') }}" />
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">
                    <i class="fas fa-search text-[#55585b]"></i>
                </span>
            </form>

            <div class="flex gap-4 items-center h-full">
                <form method="GET" action="{{ route('seller.ads_campaigns.index') }}">
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Trạng thái:</span>
                        <select name="status" id="statusFilter" onchange="this.form.submit()"
                            class="dropdown border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="">Tất cả</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>Đã kết thúc</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy
                            </option>
                        </select>
                    </div>
                </form>
                <a href="{{ route('seller.ads_campaigns.create') }}"
                    class="h-[44px] text-[15px] bg-blue-500 text-white px-4 py-2 flex items-center justify-center rounded-md hover:bg-blue-700 focus:outline-none">
                    Tạo chiến dịch mới
                </a>
            </div>
        </div>

        <table class="w-full text-xs text-left text-gray-400 border-t border-gray-100">
            <thead class="text-gray-300 font-semibold border-b border-gray-100">
                <tr>
                    <th class="w-6 py-3 pr-6">
                        <input id="select-all" class="w-[18px] h-[18px]" aria-label="Select all campaigns" type="checkbox" />
                    </th>
                    <th class="py-3">ID</th>
                    <th class="py-3">Tên chiến dịch</th>
                    <th class="py-3">Ngày bắt đầu</th>
                    <th class="py-3">Ngày kết thúc</th>
                    <th class="py-3">Trạng thái</th>
                    <th class="py-3 pr-6 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                @foreach ($campaigns as $campaign)
                    <tr>
                        <td class="py-4 pr-6">
                            <input class="select-item w-[18px] h-[18px]" aria-label="Select {{ $campaign->name }}"
                                type="checkbox" />
                        </td>
                        <td class="py-4 text-[13px]">{{ $campaign->id }}</td>
                        <td class="py-4 text-[13px]">
                            {{ $campaign->name }}
                        </td>
                        <td class="py-4 text-[13px]">
                            {{ $campaign->start_date ? \Carbon\Carbon::parse($campaign->start_date)->format('d/m/Y H:i') : 'N/A' }}
                        </td>
                        <td class="py-4 text-[13px]">
                            {{ $campaign->end_date ? \Carbon\Carbon::parse($campaign->end_date)->format('d/m/Y H:i') : 'N/A' }}
                        </td>
                        <td class="py-4">
                            <span
                                class="inline-block 
                                {{ $campaign->status == 'active'
                                    ? 'bg-green-100 text-green-600'
                                    : ($campaign->status == 'pending'
                                        ? 'bg-blue-100 text-blue-600'
                                        : ($campaign->status == 'ended'
                                            ? 'bg-gray-200 text-gray-500'
                                            : 'bg-red-100 text-red-600')) }} 
                                text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                {{ $campaign->status == 'active'
                                    ? 'Hoạt động'
                                    : ($campaign->status == 'pending'
                                        ? 'Chờ xử lý'
                                        : ($campaign->status == 'ended'
                                            ? 'Đã kết thúc'
                                            : 'Đã hủy')) }}
                            </span>
                        </td>
                        <td class="py-4 pr-6 text-right flex items-center gap-2 justify-end">
                            <a href="{{ route('seller.ads_campaigns.edit', $campaign->id) }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-md focus:outline-none">
                                <i class="fas fa-pencil-alt text-xs"></i>
                            </a>
                            <a href="{{ route('seller.ads_campaigns.add_products', $campaign->id) }}"
                                class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-md focus:outline-none">
                                <i class="fas fa-plus text-xs"></i> {{-- Icon thêm sản phẩm --}}
                            </a>
                            <form action="{{ route('seller.ads_campaigns.destroy', $campaign->id) }}" method="POST"
                                onsubmit="return confirm('Bạn có chắc muốn xóa chiến dịch này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="Delete {{ $campaign->name }}"
                                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md focus:outline-none">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                            <form action="{{ route('seller.ads_campaigns.toggle_status', $campaign->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white p-2 rounded-md focus:outline-none">
                                    <i class="fas {{ $campaign->status == 'active' ? 'fa-pause' : 'fa-play' }} text-xs"></i> {{-- Icon tạm dừng/kích hoạt --}}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if ($campaigns->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center text-gray-400 py-4">Không tìm thấy chiến dịch nào</td>
                    </tr>
                @endif
            </tbody>
        </table>
        {{-- Tôi sẽ tạm thời bỏ qua phần phân trang vì không có thông tin về pagination trong controller hiện tại --}}
        {{-- <div class="mt-6 flex items-center justify-between text-[11px] text-gray-500 select-none">
            <div>
                Hiển thị {{ $campaigns->count() }} chiến dịch trên {{ $campaigns->total() }} chiến dịch
            </div>
            {{ $campaigns->links('pagination::bootstrap-5') }}
        </div> --}}
    </section>
@endsection 