@extends('layouts.app')

@section('title', 'Danh sách Báo cáo của Tôi')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Danh sách Báo cáo của Tôi</h1>
            <a href="#" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tạo Báo cáo Mới
            </a>
        </div>

        <!-- Tìm kiếm và lọc -->
        <div class="mb-6 flex flex-col sm:flex-row gap-4">
            <div class="w-full sm:w-1/3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm theo ID hoặc sản phẩm" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="w-full sm:w-1/3">
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Đang xem xét</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Đã giải quyết</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                </select>
            </div>
            <div class="w-full sm:w-1/3">
                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Lọc</button>
            </div>
        </div>

        <!-- Bảng báo cáo -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại báo cáo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ưu tiên</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($reports as $report)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <a href="{{ route('product.show', $report->product->slug ?? '#') }}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ $report->product->name ?? 'Không có' }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $report->report_type == 'product_violation' ? 'Vi phạm chính sách sản phẩm' : ($report->report_type == 'fake_product' ? 'Sản phẩm giả nhái' : ($report->report_type == 'copyright' ? 'Vi phạm bản quyền' : ($report->report_type == 'other' ? 'Khác' : $report->report_type))) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $report->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($report->status == 'under_review' ? 'bg-blue-100 text-blue-800' : ($report->status == 'processing' ? 'bg-indigo-100 text-indigo-800' : ($report->status == 'resolved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'))) }}">
                                    {{ $report->status == 'pending' ? 'Chờ xử lý' : ($report->status == 'under_review' ? 'Đang xem xét' : ($report->status == 'processing' ? 'Đang xử lý' : ($report->status == 'resolved' ? 'Đã giải quyết' : 'Từ chối'))) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $report->priority == 'low' ? 'bg-gray-100 text-gray-800' : ($report->priority == 'medium' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $report->priority == 'low' ? 'Thấp' : ($report->priority == 'medium' ? 'Trung bình' : 'Cao') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('report.show', $report->id) }}" class="text-indigo-600 hover:text-indigo-900">Xem chi tiết</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Bạn chưa có báo cáo nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        <div class="mt-6">
            {{ $reports->links() }}
        </div>
    </div>
@endsection