@forelse ($reports as $report)
    <tr>
        <td class="py-4 pr-6">
            <input class="select-item w-[18px] h-[18px]" aria-label="Chọn báo cáo #{{ $report->id }}" type="checkbox" />
        </td>
        <td class="py-4 text-[13px]">{{ $report->id }}</td>
        <td class="py-4 text-[13px]">
            <a href="{{ route('product.show', $report->product->slug) }}" class="text-blue-600 hover:underline">
                {{ $report->product->name ?? 'Không có' }}
            </a>
        </td>
        <td class="py-4 text-[13px]">
            {{ $report->product && $report->product->shop ? $report->product->shop->shop_name : 'Không xác định' }}
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
                <a href="{{ route('admin.reports.show', $report->id) }}" class="transition-all duration-300">
                    <i class="fas fa-eye" title="Xem chi tiết"></i>
                </a>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="10" class="text-center text-gray-400 py-4">Không có báo cáo nào</td>
    </tr>
@endforelse
