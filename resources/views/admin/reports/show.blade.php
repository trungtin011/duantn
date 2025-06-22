@extends('layouts.admin')

@section('title', 'Chi tiết Báo cáo')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Chi tiết Báo cáo #{{ $report->id }}</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="mb-2"><strong class="font-semibold">Sản phẩm:</strong> <a href="{{ route('product.show', $report->product->slug) }}" class="text-blue-600 hover:underline">{{ $report->product->name ?? 'Không có' }}</a></p>
                    <p class="mb-2"><strong class="font-semibold">Người báo cáo:</strong> {{ $report->is_anonymous ? 'Ẩn danh' : ($report->reporter->fullname ?? 'Không có') }}</p>
                    <p class="mb-2"><strong class="font-semibold">Loại báo cáo:</strong> 
                        {{ 
                            $report->report_type == 'product_violation' ? 'Vi phạm chính sách sản phẩm' : 
                            ($report->report_type == 'fake_product' ? 'Sản phẩm giả nhái' : 
                            ($report->report_type == 'copyright' ? 'Vi phạm bản quyền' : 
                            ($report->report_type == 'other' ? 'Khác' : $report->report_type)))
                        }}
                    </p>
                    <p class="mb-2"><strong class="font-semibold">Nội dung báo cáo:</strong></p>
                    <p class="bg-gray-100 p-3 rounded-md">{{ $report->report_content }}</p>
                </div>
                <div>
                    <p class="mb-2"><strong class="font-semibold">Trạng thái:</strong> 
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
                    </p>
                    <p class="mb-2"><strong class="font-semibold">Ưu tiên:</strong> 
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
                    </p>
                    <p class="mb-2"><strong class="font-semibold">Ngày tạo:</strong> {{ $report->created_at->format('d/m/Y H:i') }}</p>
                    @if ($report->resolved_at)
                        <p class="mb-2"><strong class="font-semibold">Ngày xử lý:</strong> {{ $report->resolved_at->format('d/m/Y H:i') }}</p>
                        <p class="mb-2"><strong class="font-semibold">Người xử lý:</strong> {{ $report->resolvedBy->fullname ?? 'Không có' }}</p>
                    @endif
                    @if ($report->resolution_note)
                        <p class="mb-2"><strong class="font-semibold">Ghi chú xử lý:</strong></p>
                        <p class="bg-gray-100 p-3 rounded-md">{{ $report->resolution_note }}</p>
                    @endif
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-3">Bằng chứng</h3>
                @if ($report->evidence && count($report->evidence) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($report->evidence as $fileUrl)
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
                                <div class="p-2 text-xs text-gray-600 truncate">{{ basename($fileUrl) }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600">Không có bằng chứng được cung cấp.</p>
                @endif
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-3">Cập nhật Trạng thái Báo cáo</h3>
            <form action="{{ route('admin.reports.updateStatus', $report->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Trạng thái</label>
                    <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="under_review" {{ $report->status == 'under_review' ? 'selected' : '' }}>Đang xem xét</option>
                        <option value="processing" {{ $report->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Đã giải quyết</option>
                        <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="resolution_note" class="block text-sm font-medium text-gray-700">Ghi chú xử lý (tùy chọn)</label>
                    <textarea name="resolution_note" id="resolution_note" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Thêm ghi chú về cách báo cáo này đã được giải quyết...">{{ $report->resolution_note }}</textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Cập nhật Trạng thái</button>
                </div>
            </form>
        </div>
    </div>
@endsection 