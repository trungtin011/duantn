@extends('layouts.app')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/user/report.css') . '?v=' . time() }}">
    @endpush
@endsection

@section('title', 'Chi tiết Báo cáo')

@section('content')
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
                <div class="flex flex-wrap gap-4 justify-center">
                    @foreach ($evidenceArray as $fileUrl)
                        @php
                            $extension = pathinfo($fileUrl, PATHINFO_EXTENSION);
                        @endphp
                        <div class="border rounded-lg overflow-hidden shadow-sm">
                            @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                <img src="{{ asset($fileUrl) }}" alt="Ảnh bằng chứng" class="w-full h-48 object-cover">
                            @elseif (in_array($extension, ['mp4', 'mov']))
                                <video src="{{ asset($fileUrl) }}" controls class="w-full h-48 object-cover"></video>
                            @elseif (in_array($extension, ['pdf', 'doc', 'docx']))
                                <a href="{{ asset($fileUrl) }}" target="_blank" class="w-full h-48 flex items-center justify-center bg-gray-200 text-gray-500 hover:underline">
                                    Xem tài liệu: {{ basename($fileUrl) }}
                                </a>
                            @else
                                <div class="w-full h-48 flex items-center justify-center bg-gray-200 text-gray-500">
                                    Không xem trước được
                                </div>
                            @endif
                            <div class="p-2 text-xs text-gray-600">{{ basename($fileUrl) }}</div>
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
                        <a href="{{ route('product.show', $report->product->slug) }}" class="text-blue-600 hover:underline">
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
                        {{ $report->report_type == 'product_violation' ? 'Vi phạm chính sách sản phẩm' : 
                           ($report->report_type == 'shop_violation' ? 'Vi phạm chính sách cửa hàng' : 
                           ($report->report_type == 'order_issue' ? 'Vấn đề đơn hàng' : 
                           ($report->report_type == 'user_violation' ? 'Vi phạm người dùng' : 
                           ($report->report_type == 'fake_product' ? 'Sản phẩm giả nhái' : 
                           ($report->report_type == 'copyright' ? 'Vi phạm bản quyền' : 
                           ($report->report_type == 'other' ? 'Khác' : $report->report_type)))))) }}
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
                        <p class="bg-gray-100 p-2 rounded-md max-h-32 overflow-y-auto">
                            {{ $report->report_content ?? 'Không có nội dung' }}
                        </p>
                    </span>
                </div>
                <div class="flex flex-col">
                    <span class="font-semibold text-gray-600">Trạng thái:</span>
                    <span class="text-sm">
                        <span class="relative inline-block px-2 py-1 text-xs font-semibold leading-tight">
                            <span aria-hidden="true" 
                                  class="absolute inset-0 opacity-50 {{ $report->status == 'pending' ? 'bg-yellow-100' : 
                                                                   ($report->status == 'under_review' ? 'bg-blue-100' : 
                                                                   ($report->status == 'processing' ? 'bg-indigo-100' : 
                                                                   ($report->status == 'resolved' ? 'bg-green-100' : 
                                                                   ($report->status == 'rejected' ? 'bg-red-100' : 'bg-gray-100')))) }} rounded-full"></span>
                            <span class="relative">
                                {{ $report->status == 'pending' ? 'Chờ xử lý' : 
                                   ($report->status == 'under_review' ? 'Đang xem xét' : 
                                   ($report->status == 'processing' ? 'Đang xử lý' : 
                                   ($report->status == 'resolved' ? 'Đã giải quyết' : 
                                   ($report->status == 'rejected' ? 'Từ chối' : 'Không xác định')))) }}
                            </span>
                        </span>
                    </span>
                </div>
                <div class="flex flex-col">
                    <span class="font-semibold text-gray-600">Kết quả xử lý:</span>
                    <span class="text-sm">
                        <span class="relative inline-block px-2 py-1 text-xs font-semibold leading-tight">
                            <span aria-hidden="true" 
                                  class="absolute inset-0 opacity-50 {{ $report->resolution == 'accepted' ? 'bg-green-100' : 
                                                                   ($report->resolution == 'rejected' ? 'bg-red-100' : 
                                                                   ($report->resolution == 'warning_issued' ? 'bg-yellow-100' : 
                                                                   ($report->resolution == 'suspended' ? 'bg-orange-100' : 
                                                                   ($report->resolution == 'banned' ? 'bg-red-900' : 'bg-gray-100')))) }} rounded-full"></span>
                            <span class="relative">
                                {{ $report->resolution == 'accepted' ? 'Chấp nhận' : 
                                   ($report->resolution == 'rejected' ? 'Từ chối' : 
                                   ($report->resolution == 'warning_issued' ? 'Cảnh cáo' : 
                                   ($report->resolution == 'suspended' ? 'Tạm đình chỉ' : 
                                   ($report->resolution == 'banned' ? 'Cấm' : 'Chưa xử lý')))) }}
                            </span>
                        </span>
                    </span>
                </div>
                <div class="flex flex-col">
                    <span class="font-semibold text-gray-600">Ghi chú xử lý:</span>
                    <span class="text-sm">
                        <p class="bg-gray-100 p-2 rounded-md max-h-32 overflow-y-auto">
                            {{ $report->resolution_note ?? 'Chưa có ghi chú xử lý.' }}
                        </p>
                    </span>
                </div>
            </div>
        </div>
    </section>
@endsection