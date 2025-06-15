@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Chi tiết Báo Cáo #{{ $report->id }}</h1>

    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary mb-3">← Quay lại danh sách</a>

    <div class="table-responsive">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>Người báo cáo</th>
                    <td>{{ $report->reporter->fullname ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Người bị báo cáo</th>
                    <td>{{ $report->reportedUser->fullname ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Sản phẩm</th>
                    <td>{{ $report->product->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Shop</th>
                    <td>{{ $report->shop->shop_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Đơn hàng</th>
                    <td>{{ $report->order->order_code ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Loại vi phạm</th>
                    <td>{{ ucfirst(str_replace('_', ' ', $report->report_type)) }}</td>
                </tr>
                <tr>
                    <th>Nội dung báo cáo</th>
                    <td>{{ $report->report_content }}</td>
                </tr>
                <tr>
                    <th>Minh chứng</th>
                    <td>
                        @php
                            $evidences = is_string($report->evidence) ? json_decode($report->evidence, true) : $report->evidence;
                        @endphp
                        @if($evidences && is_array($evidences))
                            @foreach($evidences as $evidence)
                                <a href="{{ asset('storage/' . $evidence) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $evidence) }}" alt="evidence" width="100" class="me-2 mb-2">
                                </a>
                            @endforeach
                        @else
                            Không có
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Mức độ ưu tiên</th>
                    <td>{{ ucfirst($report->priority) }}</td>
                </tr>
                <tr>
                    <th>Trạng thái</th>
                    <td>{{ ucfirst(str_replace('_', ' ', $report->status)) }}</td>
                </tr>
                <tr>
                    <th>Hạn xử lý</th>
                    <td>{{ $report->due_date ? $report->due_date->format('d/m/Y') : 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Người xử lý</th>
                    <td>{{ $report->resolvedBy->fullname ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Kết quả</th>
                    <td>{{ ucfirst($report->resolution ?? '-') }}</td>
                </tr>
                <tr>
                    <th>Ghi chú xử lý</th>
                    <td>{{ $report->resolution_note ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Thời gian xử lý</th>
                    <td>{{ $report->resolved_at ? $report->resolved_at->format('d/m/Y H:i') : '-' }}</td>
                </tr>
                <tr>
                    <th>Báo cáo ẩn danh</th>
                    <td>{{ $report->is_anonymous ? 'Có' : 'Không' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary mt-3">← Quay lại</a>
</div>
@endsection
