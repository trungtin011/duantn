@extends('layouts.admin')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/user.css') }}">
    @endpush
@endsection

@section('title', 'Quản lý Báo cáo')

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Quản lý Báo cáo</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / Danh sách báo cáo</div>
    </div>

    <section class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4 h-[72px]">
            <form class="w-full md:w-[223px] relative" method="GET" action="{{ route('admin.reports.index') }}">
                <input name="search"
                    class="w-full h-[42px] border border-[#F2F2F6] rounded-md py-2 pl-10 pr-4 text-xs placeholder:text-gray-400 focus:outline-none"
                    placeholder="Tìm kiếm theo ID hoặc sản phẩm" type="text" value="{{ request('search') }}" />
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">
                    <i class="fas fa-search text-[#55585b]"></i>
                </span>
            </form>

            <div class="flex gap-4 items-center h-full">
                <form method="GET" action="{{ route('admin.reports.index') }}">
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Trạng thái:</span>
                        <select name="status" id="statusFilter"
                            class="dropdown px-3 py-2 text-gray-600 text-xs focus:outline-none w-[100px]">
                            <option value="">Tất cả</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý
                            </option>
                            <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Đang
                                xem xét
                            </option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý
                            </option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Đã giải quyết
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Từ chối
                            </option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <table class="w-full text-xs text-left text-gray-400 border-t border-gray-100">
            <thead class="text-gray-300 font-semibold border-b border-gray-100">
                <tr>
                    <th class="w-6 py-3 pr-6">
                        <input id="select-all" class="w-[18px] h-[18px]" aria-label="Chọn tất cả báo cáo" type="checkbox" />
                    </th>
                    <th scope="col">ID</th>
                    <th scope="col">Sản phẩm</th>
                    <th scope="col">Người báo cáo</th>
                    <th scope="col">Loại báo cáo</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Ưu tiên</th>
                    <th scope="col">Ngày tạo</th>
                    <th scope="col" class="text-right pr-[24px]">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                @forelse ($reports as $report)
                    <tr>
                        <td class="py-4 pr-6">
                            <input class="select-item w-[18px] h-[18px]" aria-label="Chọn báo cáo #{{ $report->id }}"
                                type="checkbox" />
                        </td>
                        <td class="py-4 text-[13px]">{{ $report->id }}</td>
                        <td class="py-4 text-[13px]">
                            <a href="{{ route('product.show', $report->product->slug) }}"
                                class="text-blue-600 hover:underline">
                                {{ $report->product->name ?? 'Không có' }}
                            </a>
                        </td>
                        <td class="py-4 text-[13px]">
                            {{ $report->is_anonymous ? 'Ẩn danh' : $report->reporter->fullname ?? 'N/A' }}
                        </td>
                        <td class="py-4 text-[13px]">
                            {{ $report->report_type == 'product_violation'
                                ? 'Vi phạm chính sách sản phẩm'
                                : ($report->report_type == 'fake_product'
                                    ? 'Sản phẩm giả nhái'
                                    : ($report->report_type == 'copyright'
                                        ? 'Vi phạm bản quyền'
                                        : ($report->report_type == 'other'
                                            ? 'Khác'
                                            : $report->report_type))) }}
                        </td>
                        <td class="py-4">
                            <span class="relative inline-block px-3 py-0.5 text-[10px] font-semibold leading-tight">
                                <span aria-hidden="true"
                                    class="absolute inset-0 opacity-50 {{ $report->status == 'pending'
                                        ? 'bg-yellow-100'
                                        : ($report->status == 'under_review'
                                            ? 'bg-blue-100'
                                            : ($report->status == 'processing'
                                                ? 'bg-indigo-100'
                                                : ($report->status == 'resolved'
                                                    ? 'bg-green-100'
                                                    : ($report->status == 'rejected'
                                                        ? 'bg-red-100'
                                                        : '')))) }} rounded-full"></span>
                                <span
                                    class="relative">{{ $report->status == 'pending'
                                        ? 'Chờ xử lý'
                                        : ($report->status == 'under_review'
                                            ? 'Đang xem xét'
                                            : ($report->status == 'processing'
                                                ? 'Đang xử lý'
                                                : ($report->status == 'resolved'
                                                    ? 'Đã giải quyết'
                                                    : ($report->status == 'rejected'
                                                        ? 'Từ chối'
                                                        : '')))) }}
                                                        </span>
                            </span>
                        </td>
                        <td class="py-4">
                            <span class="relative inline-block px-3 py-0.5 text-[10px] font-semibold leading-tight">
                                <span aria-hidden="true"
                                    class="absolute inset-0 opacity-50 {{ $report->priority == 'low'
                                        ? 'bg-gray-100'
                                        : ($report->priority == 'medium'
                                            ? 'bg-orange-100'
                                            : ($report->priority == 'high'
                                                ? 'bg-red-100'
                                                : '')) }} rounded-full">
                                </span>
                                <span
                                    class="relative">{{ $report->priority == 'low'
                                        ? 'Thấp'
                                        : ($report->priority == 'medium'
                                            ? 'Trung bình'
                                            : ($report->priority == 'high'
                                                ? 'Cao'
                                                : '')) }}
                                </span>
                            </span>
                        </td>
                        <td class="py-4 text-[13px]">{{ $report->created_at->format('d/m/Y H:i') }}</td>
                        <td class="py-4 pr-6 flex items-center justify-end">
                            <div
                                class="bg-[#f2f2f6] hover:bg-[#E8A252] hover:text-white w-[37px] h-[35px] rounded-md flex items-center justify-center mr-2">
                                <a href="{{ route('admin.reports.show', $report->id) }}"
                                    class="transition-all duration-300">
                                    <i class="fas fa-eye" title="Xem chi tiết"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-gray-400 py-4">Không có báo cáo nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-6 flex items-center justify-between text-[11px] text-gray-500 select-none">
            <div>
                Hiển thị {{ $reports->count() }} báo cáo trên {{ $reports->total() }} báo cáo
            </div>
            {{ $reports->links('pagination::bootstrap-5') }}
        </div>
    </section>
@endsection
