@extends('layouts.admin')

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-title">Danh sách Báo Cáo</h1>
    <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / Báo Cáo</div>
</div>

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
                <td>{{ ucfirst(str_replace('_', ' ', $report->status)) }}</td>
                <td>{{ $report->due_date ? $report->due_date->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ $report->resolvedBy->fullname ?? 'N/A' }}</td>
                <td>{{ ucfirst($report->resolution ?? '-') }}</td>
                <td>{{ $report->resolution_note ?? '-' }}</td>
                <td>{{ $report->resolved_at ? $report->resolved_at->format('d/m/Y H:i') : '-' }}</td>
                <td>{{ $report->is_anonymous ? 'Có' : 'Không' }}</td>
                <td>
                    <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-sm btn-primary mb-1">Xem</a>
                    <form action="{{ route('admin.reports.destroy', $report->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa báo cáo này không?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
