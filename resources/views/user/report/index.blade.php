@extends('user.account.layout')

@section('title', 'Danh sách Báo cáo của Tôi')

@section('account-content')
    <div class="container mx-auto">
        <div class="w-[900px]">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Danh sách Báo cáo của Tôi</h1>
            </div>
    
            <!-- Bảng báo cáo -->
            <div class="overflow-x-auto min-w-[100px]">
                <table class="divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại báo
                                cáo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng
                                thái</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ưu tiên
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($reports as $report)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <a href="{{ route('product.show', $report->product->slug ?? '#') }}"
                                        class="report-link">
                                        {{ $report->product->name ?? 'Không có' }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $report->report_type == 'product_violation' ? 'Vi phạm chính sách sản phẩm' : ($report->report_type == 'fake_product' ? 'Sản phẩm giả nhái' : ($report->report_type == 'copyright' ? 'Vi phạm bản quyền' : ($report->report_type == 'other' ? 'Khác' : $report->report_type))) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge {{ $report->status == 'pending' ? 'status-pending' : ($report->status == 'under_review' ? 'status-under-review' : ($report->status == 'processing' ? 'status-processing' : ($report->status == 'resolved' ? 'status-resolved' : 'status-rejected'))) }}">
                                        {{ $report->status == 'pending' ? 'Chờ xử lý' : ($report->status == 'under_review' ? 'Đang xem xét' : ($report->status == 'processing' ? 'Đang xử lý' : ($report->status == 'resolved' ? 'Đã giải quyết' : 'Từ chối'))) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="priority-badge {{ $report->priority == 'low' ? 'priority-low' : ($report->priority == 'medium' ? 'priority-medium' : 'priority-high') }}">
                                        {{ $report->priority == 'low' ? 'Thấp' : ($report->priority == 'medium' ? 'Trung bình' : 'Cao') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $report->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('report.show', $report->id) }}"
                                        class="report-link">Xem chi tiết</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4">
                                    <div class="empty-state">
                                        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-lg font-medium mb-2">Bạn chưa có báo cáo nào</p>
                                        <p class="text-sm">Hãy tìm sản phẩm để báo cáo khi cần thiết.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
    
            <!-- Phân trang -->
            <div class="pagination-wrapper">
                {{ $reports->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
