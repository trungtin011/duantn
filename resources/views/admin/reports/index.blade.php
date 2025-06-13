@extends('layouts.admin')

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