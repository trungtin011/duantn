@extends('layouts.admin')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/user.css') }}">
    @endpush
@endsection

@section('title', 'Chi tiết Báo cáo')

@section('content')
<div class="admin-page-header mb-4">
    <h1 class="admin-page-title">Chi tiết Báo cáo</h1>
    <div class="admin-breadcrumb">
        <a href="#" class="admin-breadcrumb-link">Trang chủ</a> /
        <a href="{{ route('admin.reports.index') }}" class="admin-breadcrumb-link">Danh sách báo cáo</a> /
        Chi tiết báo cáo
    </div>
</div>

<section class="bg-white rounded-lg shadow-sm py-6 px-8">
    @include('layouts.notification')

    {{-- Bằng chứng --}}
    <div class="mb-8">
        <h2 class="font-semibold text-gray-700 mb-2">Bằng chứng</h2>
        @php
            $evidenceArray = [];
            if (is_string($report->evidence)) {
                $evidenceArray = json_decode($report->evidence, true) ?: [];
                if (json_last_error() !== JSON_ERROR_NONE) $evidenceArray = [];
            } elseif (is_array($report->evidence)) {
                $evidenceArray = $report->evidence;
            }
        @endphp
        @if (!empty($evidenceArray))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($evidenceArray as $fileUrl)
                    @php $extension = pathinfo($fileUrl, PATHINFO_EXTENSION); @endphp
                    <div class="border rounded-lg overflow-hidden shadow-sm bg-gray-50 flex flex-col">
                        @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                            <img src="{{ asset($fileUrl) }}" alt="Ảnh bằng chứng" class="w-full h-48 object-cover">
                        @elseif (in_array($extension, ['mp4', 'mov']))
                            <video src="{{ asset($fileUrl) }}" controls class="w-full h-48 object-cover"></video>
                        @else
                            <div class="w-full h-48 flex items-center justify-center bg-gray-200 text-gray-500">
                                Không xem trước được
                            </div>
                        @endif
                        <div class="p-2 text-xs text-gray-600 text-center border-t">{{ basename($fileUrl) }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">Không có bằng chứng được cung cấp.</p>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Thông tin báo cáo --}}
        <div class="space-y-4">
            <h2 class="font-semibold text-gray-700 mb-2">Thông tin báo cáo</h2>
            <div>
                <span class="font-semibold text-gray-600">Sản phẩm:</span>
                <span class="text-[13px]">
                    <a href="{{ route('product.show', $report->product->slug) }}"
                        class="text-blue-600 hover:underline">{{ $report->product->name ?? 'Không có' }}</a>
                </span>
            </div>
            <div>
                <span class="font-semibold text-gray-600">Người báo cáo:</span>
                <span class="text-[13px]">
                    {{ $report->is_anonymous ? 'Ẩn danh' : $report->reporter->fullname ?? 'Không có' }}
                </span>
            </div>
            <div>
                <span class="font-semibold text-gray-600">Loại báo cáo:</span>
                <span class="text-[13px]">
                    {{ $report->report_type == 'product_violation'
                        ? 'Vi phạm chính sách sản phẩm'
                        : ($report->report_type == 'fake_product'
                            ? 'Sản phẩm giả nhái'
                            : ($report->report_type == 'copyright'
                                ? 'Vi phạm bản quyền'
                                : ($report->report_type == 'other'
                                    ? 'Khác'
                                    : $report->report_type))) }}
                </span>
            </div>
            <div>
                <span class="font-semibold text-gray-600">Ngày tạo:</span>
                <span class="text-[13px]">{{ $report->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div>
                <span class="font-semibold text-gray-600">Nội dung báo cáo:</span>
                <span class="text-[13px]">
                    <p class="bg-gray-100 p-2 rounded-md max-h-[80px] overflow-y-auto">
                        {{ $report->report_content }}
                    </p>
                </span>
            </div>
            <div>
                <span class="font-semibold text-gray-600">Trạng thái:</span>
                <span class="inline-block px-2 py-1 rounded-full text-[11px] font-semibold
                    @if ($report->status == 'pending') bg-yellow-100 text-yellow-700
                    @elseif ($report->status == 'under_review') bg-blue-100 text-blue-700
                    @elseif ($report->status == 'processing') bg-indigo-100 text-indigo-700
                    @elseif ($report->status == 'resolved') bg-green-100 text-green-700
                    @elseif ($report->status == 'rejected') bg-red-100 text-red-700
                    @else bg-gray-100 text-gray-700 @endif">
                    {{ $report->status == 'pending'
                        ? 'Chờ xử lý'
                        : ($report->status == 'under_review'
                            ? 'Đang xem xét'
                            : ($report->status == 'processing'
                                ? 'Đang xử lý'
                                : ($report->status == 'resolved'
                                    ? 'Đã giải quyết'
                                    : ($report->status == 'rejected'
                                        ? 'Từ chối'
                                        : $report->status)))) }}
                </span>
            </div>
            <div>
                <span class="font-semibold text-gray-600">Ưu tiên:</span>
                <span class="inline-block px-2 py-1 rounded-full text-[11px] font-semibold
                    @if ($report->priority == 'low') bg-gray-100 text-gray-700
                    @elseif ($report->priority == 'medium') bg-orange-100 text-orange-700
                    @elseif ($report->priority == 'high') bg-red-100 text-red-700
                    @else bg-gray-100 text-gray-700 @endif">
                    {{ $report->priority == 'low'
                        ? 'Thấp'
                        : ($report->priority == 'medium'
                            ? 'Trung bình'
                            : ($report->priority == 'high'
                                ? 'Cao'
                                : $report->priority)) }}
                </span>
            </div>
            <div>
                <span class="font-semibold text-gray-600">Ngày xử lý:</span>
                <span class="text-[13px]">
                    {{ $report->resolved_at ? $report->resolved_at->format('d/m/Y H:i') : '-' }}
                </span>
            </div>
            <div>
                <span class="font-semibold text-gray-600">Người xử lý:</span>
                <span class="text-[13px]">
                    {{ $report->resolvedBy ? $report->resolvedBy->fullname ?? 'Không có' : '-' }}
                </span>
            </div>
        </div>

        {{-- Form cập nhật trạng thái --}}
        <div class="bg-gray-50 rounded-lg p-6 shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Cập nhật Trạng thái Báo cáo</h3>
            <form action="{{ route('admin.reports.updateStatus', $report->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Trạng thái</label>
                    <select name="status" id="status"
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none sm:text-sm">
                        <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="under_review" {{ $report->status == 'under_review' ? 'selected' : '' }}>Đang xem xét</option>
                        <option value="processing" {{ $report->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Đã giải quyết</option>
                        <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="resolution_note" class="block text-sm font-medium text-gray-700">Ghi chú xử lý (tùy chọn)</label>
                    <textarea name="resolution_note" id="resolution_note" rows="4"
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none sm:text-sm"
                        placeholder="Thêm ghi chú về cách báo cáo này đã được giải quyết...">{{ $report->resolution_note }}</textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                        Cập nhật Trạng thái
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
