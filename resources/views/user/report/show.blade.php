@extends('user.account.layout')


@section('title', 'Chi tiết Báo cáo')

@section('account-content')
    <div class="container mx-auto px-4 py-8">
        <div class="user-page-header">
            <h1 class="user-page-title">Chi tiết Báo cáo #{{ $report->id }}</h1>
            <div class="user-breadcrumb">
                <a href="{{ route('home') }}" class="user-breadcrumb-link">Trang chủ</a> /
                <a href="{{ route('report.index') }}" class="user-breadcrumb-link">Danh sách báo cáo</a> /
                Chi tiết báo cáo
            </div>
        </div>

        <section class="bg-white rounded-lg shadow-sm p-6">
            @include('layouts.notification')

            <!-- Hiển thị bằng chứng -->
            <div class="mb-6">
                @php
                    $evidenceArray = [];
                    if (is_string($report->evidence)) {
                        $evidenceArray = json_decode($report->evidence, true) ?: [];
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            $evidenceArray = [];
                        }
                    } elseif (is_array($report->evidence)) {
                        $evidenceArray = $report->evidence;
                    }
                @endphp
                @if (!empty($evidenceArray))
                    <h3 class="text-lg font-semibold mb-3">Bằng chứng</h3>
                    <div class="evidence-grid">
                        @foreach ($evidenceArray as $fileUrl)
                            @php
                                $extension = pathinfo($fileUrl, PATHINFO_EXTENSION);
                            @endphp
                            <div class="evidence-item">
                                @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{ asset($fileUrl) }}" alt="Ảnh bằng chứng" class="evidence-preview">
                                @elseif (in_array($extension, ['mp4', 'mov']))
                                    <video src="{{ asset($fileUrl) }}" controls class="evidence-preview"></video>
                                @elseif (in_array($extension, ['pdf', 'doc', 'docx']))
                                    <a href="{{ asset($fileUrl) }}" target="_blank" class="evidence-document">
                                        <div class="text-center">
                                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-sm">Xem tài liệu</span>
                                        </div>
                                    </a>
                                @else
                                    <div class="evidence-document">
                                        <div class="text-center">
                                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <span class="text-sm">Không xem trước được</span>
                                        </div>
                                    </div>
                                @endif
                                <div class="evidence-filename">{{ basename($fileUrl) }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600">Không có bằng chứng được cung cấp.</p>
                @endif
            </div>

            <!-- Thông tin báo cáo -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-600">Sản phẩm:</span>
                        <span class="text-sm">
                            <a href="{{ route('product.show', $report->product->slug) }}"
                                class="text-blue-600 hover:underline">
                                {{ $report->product->name ?? 'Không có' }}
                            </a>
                        </span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-600">Người báo cáo:</span>
                        <span class="text-sm">
                            {{ $report->is_anonymous ? 'Ẩn danh' : $report->reporter->fullname ?? 'Không có' }}
                        </span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-600">Loại báo cáo:</span>
                        <span class="text-sm">
                            {{ $report->report_type == 'product_violation'
                                ? 'Vi phạm chính sách sản phẩm'
                                : ($report->report_type == 'shop_violation'
                                    ? 'Vi phạm chính sách cửa hàng'
                                    : ($report->report_type == 'order_issue'
                                        ? 'Vấn đề đơn hàng'
                                        : ($report->report_type == 'user_violation'
                                            ? 'Vi phạm người dùng'
                                            : ($report->report_type == 'fake_product'
                                                ? 'Sản phẩm giả nhái'
                                                : ($report->report_type == 'copyright'
                                                    ? 'Vi phạm bản quyền'
                                                    : ($report->report_type == 'other'
                                                        ? 'Khác'
                                                        : $report->report_type)))))) }}
                        </span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-600">Ngày tạo:</span>
                        <span class="text-sm">{{ $report->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-600">Nội dung báo cáo:</span>
                        <span class="text-sm">
                            <p class="content-box">
                                {{ $report->report_content ?? 'Không có nội dung' }}
                            </p>
                        </span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-600">Trạng thái:</span>
                        <span class="text-sm">
                            <span class="status-badge {{ $report->status == 'pending'
                                ? 'status-pending'
                                : ($report->status == 'under_review'
                                    ? 'status-under-review'
                                    : ($report->status == 'processing'
                                        ? 'status-processing'
                                        : ($report->status == 'resolved'
                                            ? 'status-resolved'
                                            : 'status-rejected'))) }}">
                                {{ $report->status == 'pending'
                                    ? 'Chờ xử lý'
                                    : ($report->status == 'under_review'
                                        ? 'Đang xem xét'
                                        : ($report->status == 'processing'
                                            ? 'Đang xử lý'
                                            : ($report->status == 'resolved'
                                                ? 'Đã giải quyết'
                                                : 'Từ chối'))) }}
                            </span>
                        </span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-600">Kết quả xử lý:</span>
                        <span class="text-sm">
                            <span class="resolution-badge {{ $report->resolution == 'accepted'
                                ? 'resolution-accepted'
                                : ($report->resolution == 'rejected'
                                    ? 'resolution-rejected'
                                    : ($report->resolution == 'warning_issued'
                                        ? 'resolution-warning'
                                        : ($report->resolution == 'suspended'
                                            ? 'resolution-suspended'
                                            : ($report->resolution == 'banned'
                                                ? 'resolution-banned'
                                                : '')))) }}">
                                {{ $report->resolution == 'accepted'
                                    ? 'Chấp nhận'
                                    : ($report->resolution == 'rejected'
                                        ? 'Từ chối'
                                        : ($report->resolution == 'warning_issued'
                                            ? 'Cảnh cáo'
                                            : ($report->resolution == 'suspended'
                                                ? 'Tạm đình chỉ'
                                                : ($report->resolution == 'banned'
                                                    ? 'Cấm'
                                                    : 'Chưa xử lý')))) }}
                            </span>
                        </span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-600">Ghi chú xử lý:</span>
                        <span class="text-sm">
                            <p class="content-box">
                                {{ $report->resolution_note ?? 'Chưa có ghi chú xử lý.' }}
                            </p>
                        </span>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
