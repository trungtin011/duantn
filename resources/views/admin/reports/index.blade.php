@extends('layouts.admin')

<<<<<<< HEAD
@section('title', 'Quản lý Báo cáo')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Quản lý Báo cáo</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            ID
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Sản phẩm
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Người báo cáo
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Loại báo cáo
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Trạng thái
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Ưu tiên
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Ngày tạo
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $report)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ $report->id }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <a href="{{ route('product.show', $report->product->slug) }}" class="text-blue-600 hover:underline">
                                    {{ $report->product->name ?? 'Không có' }}
                                </a>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ $report->is_anonymous ? 'Ẩn danh' : ($report->reporter->fullname ?? 'N/A') }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ 
                                    $report->report_type == 'product_violation' ? 'Vi phạm chính sách sản phẩm' : 
                                    ($report->report_type == 'fake_product' ? 'Sản phẩm giả nhái' : 
                                    ($report->report_type == 'copyright' ? 'Vi phạm bản quyền' : 
                                    ($report->report_type == 'other' ? 'Khác' : $report->report_type)))
                                }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <span class="relative inline-block px-3 py-1 font-semibold leading-tight">
                                    <span aria-hidden="true" class="absolute inset-0 opacity-50 {{ 
                                        $report->status == 'pending' ? 'bg-yellow-200' : 
                                        ($report->status == 'under_review' ? 'bg-blue-200' : 
                                        ($report->status == 'processing' ? 'bg-indigo-200' : 
                                        ($report->status == 'resolved' ? 'bg-green-200' : 
                                        ($report->status == 'rejected' ? 'bg-red-200' : ''))))
                                    }} rounded-full"></span>
                                    <span class="relative">{{ 
                                        $report->status == 'pending' ? 'Chờ xử lý' : 
                                        ($report->status == 'under_review' ? 'Đang xem xét' : 
                                        ($report->status == 'processing' ? 'Đang xử lý' : 
                                        ($report->status == 'resolved' ? 'Đã giải quyết' : 
                                        ($report->status == 'rejected' ? 'Từ chối' : ''))))
                                    }}</span>
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <span class="relative inline-block px-3 py-1 font-semibold leading-tight">
                                    <span aria-hidden="true" class="absolute inset-0 opacity-50 {{ 
                                        $report->priority == 'low' ? 'bg-gray-200' : 
                                        ($report->priority == 'medium' ? 'bg-orange-200' : 
                                        ($report->priority == 'high' ? 'bg-red-200' : ''))
                                    }} rounded-full"></span>
                                    <span class="relative">{{ 
                                        $report->priority == 'low' ? 'Thấp' : 
                                        ($report->priority == 'medium' ? 'Trung bình' : 
                                        ($report->priority == 'high' ? 'Cao' : ''))
                                    }}</span>
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ $report->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                <a href="{{ route('admin.reports.show', $report->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                    Xem chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">Không có báo cáo nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-5">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
@endsection 
=======
@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-title">Danh sách Báo Cáo</h1>
    <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / Báo Cáo</div>
</div>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<table class="table table-bordered table-hover">
    <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Người báo cáo</th>
            <th>Người bị báo cáo</th>
            <th>Sản phẩm</th>
            <th>Shop</th>
            <th>Đơn hàng</th>
            <th>Loại vi phạm</th>
            <th>Nội dung</th>
            <th>Mức độ ưu tiên</th>
            <th>Trạng thái</th>
            <th>Hạn xử lý</th>
            <th>Người xử lý</th>
            <th>Kết quả</th>
            <th>Ghi chú xử lý</th>
            <th>Thời gian xử lý</th>
            <th>Ẩn danh</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reports as $report)
            <tr>
                <td>{{ $report->id }}</td>
                <td>{{ $report->reporter->fullname ?? 'N/A' }}</td>
                <td>{{ $report->reportedUser->fullname ?? 'N/A' }}</td>
                <td>{{ $report->product->name ?? 'N/A' }}</td>
                <td>{{ $report->shop->shop_name ?? 'N/A' }}</td>
                <td>{{ $report->order->id ?? 'N/A' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $report->report_type)) }}</td>
                <td>{{ Str::limit($report->report_content, 50) }}</td>
                <td>{{ ucfirst($report->priority) }}</td>
                <td>
                    <form action="{{ route('report.updateStatus', $report->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            @foreach (['pending', 'under_review', 'processing', 'resolved', 'rejected'] as $status)
                                <option value="{{ $status }}" {{ $report->status === $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </td>
                <td>{{ $report->due_date ? $report->due_date->format('d/m/Y') : 'N/A' }}</td>
                <td>
                    @if (in_array($report->status, ['under_review', 'processing', 'resolved', 'rejected']))
                        {{ $report->resolvedBy->fullname ?? 'N/A' }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @php
                        $resolutions = [
                            'accepted' => 'Đã xử lý',
                            'rejected' => 'Đã từ chối',
                            'warning_issued' => 'Đã cảnh cáo',
                            'suspended' => 'Tạm khóa',
                            'banned' => 'Cấm vĩnh viễn',
                        ];
                    @endphp
                    {{ $resolutions[$report->resolution] ?? '-' }}
                </td>
                <td>
                    @if(in_array($report->status, ['resolved', 'rejected']))
                        {{ $report->resolution_note ?? '-' }}
                    @else
                        -
                    @endif
                </td>
                {{-- <td>
                    {{ $report->status === 'resolved' ? ($report->resolution_note ?? '-') : '-' }}
                </td> --}}
                <td>
                    {{ $report->status === 'resolved' && $report->resolved_at ? $report->resolved_at->format('d/m/Y H:i') : '-' }}
                </td>
                <td>{{ $report->is_anonymous ? 'Có' : 'Không' }}</td>
                <td>
                    <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-sm btn-primary mb-1">Xem</a>
                    <form action="{{ route('report.updateStatus', $report->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="under_review">
                        <button class="btn btn-sm btn-success mb-1">Chấp nhận</button>
                    </form>
                    <button type="button" class="btn btn-sm btn-warning text-white mb-1" data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $report->id }}">
                        Từ chối
                    </button>
                    <div class="modal fade" id="rejectModal-{{ $report->id }}" tabindex="-1" aria-labelledby="rejectModalLabel-{{ $report->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('report.updateStatus', $report->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="rejected">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="rejectModalLabel-{{ $report->id }}">Lý do từ chối báo cáo #{{ $report->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="resolution_note_{{ $report->id }}" class="form-label">Nhập lý do từ chối</label>
                                            <textarea class="form-control" name="resolution_note" id="resolution_note_{{ $report->id }}" rows="3" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                        <button type="submit" class="btn btn-danger">Từ chối báo cáo</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <form action="{{ route('admin.reports.destroy', $report->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa báo cáo này không?');">
                        @csrf
                        @method('DELETE')
                        {{-- <button class="btn btn-sm btn-danger">Xóa</button> --}}
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
>>>>>>> linh
