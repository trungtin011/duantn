@extends('layouts.admin')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/user.css') }}">
    @endpush
@endsection

@section('title', 'Chi tiết Báo cáo')

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Chi tiết Báo cáo #{{ $report->id }}</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / <a
                href="{{ route('admin.reports.index') }}" class="admin-breadcrumb-link">Danh sách báo cáo</a> / Chi tiết báo
            cáo</div>
    </div>

    <section class="bg-white rounded-lg shadow-sm py-6">
        @include('layouts.notification')

        <div class="mb-6">
            @php
                // Kiểm tra kiểu dữ liệu của $report->evidence
                $evidenceArray = [];
                if (is_string($report->evidence)) {
                    // Nếu là chuỗi, giải mã JSON thành mảng
                    $evidenceArray = json_decode($report->evidence, true) ?: [];
                    // Kiểm tra lỗi JSON
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $evidenceArray = [];
                    }
                } elseif (is_array($report->evidence)) {
                    // Nếu đã là mảng, sử dụng trực tiếp
                    $evidenceArray = $report->evidence;
                }
            @endphp
            @if (!empty($evidenceArray))
                <div class="flex justify-center gap-4">
                    @foreach ($evidenceArray as $fileUrl)
                        @php
                            $extension = pathinfo($fileUrl, PATHINFO_EXTENSION);
                        @endphp
                        <div class="border rounded-lg overflow-hidden shadow-sm">
                            @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                <img src="{{ asset($fileUrl) }}" alt="Ảnh bằng chứng" class="w-full h-48 object-cover">
                            @elseif (in_array($extension, ['mp4', 'mov']))
                                <video src="{{ asset($fileUrl) }}" controls class="w-full h-48 object-cover"></video>
                            @else
                                <div class="w-full h-48 flex items-center justify-center bg-gray-200 text-gray-500">
                                    Không xem trước được
                                </div>
                            @endif
                            <div class="p-2 text-xs text-gray-600 ">{{ basename($fileUrl) }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600">Không có bằng chứng được cung cấp.</p>
            @endif
        </div>

        <div class="mt-6 flex p-6 gap-4">
            <div class="w-1/2 text-gray-900 font-normal gap-[100px] flex justify-center p-6">
                <div class="space-y-4 w-fit">
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-600">Sản phẩm:</span>
                        <span class="text-[13px]">
                            <a href="{{ route('product.show', $report->product->slug) }}"
                                class="text-blue-600 hover:underline">{{ $report->product->name ?? 'Không có' }}</a>
                        </span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-600">Người báo cáo:</span>
                        <span class="text-[13px]">
                            {{ $report->is_anonymous ? 'Ẩn danh' : $report->reporter->fullname ?? 'Không có' }}
                        </span>
                    </div>
                    <div class="flex flex-col">
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
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-600">Ngày tạo:</span>
                        <span class="text-[13px]">{{ $report->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                <div class="space-y-4 w-fit">
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-600">Nội dung báo cáo:</span>
                        <span class="text-[13px]">
                            <p class="bg-gray-100 p-1 rounded-md max-h-[50px] overflow-y-auto">
                                {{ \Illuminate\Support\Str::limit($report->report_content, 50) }}
                            </p>
                        </span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-600">Trạng thái:</span>
                        <span class="text-[13px]">
                            <span class="relative inline-block px-1 py-0.25 text-[10px] font-semibold leading-tight">
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
                                                        : '')))) }}</span>
                            </span>
                        </span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-600">Ưu tiên:</span>
                        <span class="text-[13px]">
                            <span class="relative inline-block px-1 py-0.25 text-[10px] font-semibold leading-tight">
                                <span aria-hidden="true"
                                    class="absolute inset-0 opacity-50 {{ $report->priority == 'low'
                                        ? 'bg-gray-100'
                                        : ($report->priority == 'medium'
                                            ? 'bg-orange-100'
                                            : ($report->priority == 'high'
                                                ? 'bg-red-100'
                                                : '')) }} rounded-full"></span>
                                <span
                                    class="relative">{{ $report->priority == 'low'
                                        ? 'Thấp'
                                        : ($report->priority == 'medium'
                                            ? 'Trung bình'
                                            : ($report->priority == 'high'
                                                ? 'Cao'
                                                : '')) }}</span>
                            </span>
                        </span>
                    </div>
                </div>
                <div class="space-y-4 w-fit">
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-600">Ngày xử lý:</span>
                        <span class="text-[13px]">
                            {{ $report->resolved_at ? $report->resolved_at->format('d/m/Y H:i') : '-' }}
                        </span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-600">Người xử lý:</span>
                        <span class="text-[13px]">
                            {{ $report->resolvedBy ? $report->resolvedBy->fullname ?? 'Không có' : '-' }}
                        </span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-600">Ghi chú xử lý:</span>
                        <span class="text-[13px]">
                            <p class="bg-gray-100 p-1 rounded-md max-h-[50px] overflow-y-auto">
                                {{ \Illuminate\Support\Str::limit($report->resolution_note, 50) }}
                            </p>
                        </span>
                    </div>
                </div>
            </div>

            <div class="w-[600px] p-6">
                <h3 class="text-lg font-semibold mb-3">Cập nhật Trạng thái Báo cáo</h3>
                <form action="{{ route('admin.reports.updateStatus', $report->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700">Trạng thái</label>
                        <select name="status" id="status"
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none sm:text-sm">
                            <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Chờ xử lý
                            </option>
                            <option value="under_review" {{ $report->status == 'under_review' ? 'selected' : '' }}>Đang
                                xem
                                xét
                            </option>
                            <option value="processing" {{ $report->status == 'processing' ? 'selected' : '' }}>Đang xử lý
                            </option>
                            <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Đã giải quyết
                            </option>
                            <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>Từ chối
                            </option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="resolution_note" class="block text-sm font-medium text-gray-700">Ghi chú xử lý (tùy
                            chọn)</label>
                        <textarea name="resolution_note" id="resolution_note" rows="4"
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none sm:text-sm"
                            placeholder="Thêm ghi chú về cách báo cáo này đã được giải quyết...">{{ $report->resolution_note }}</textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Cập
                            nhật Trạng thái</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
