@extends('layouts.app')

@section('title', 'Tickets của tôi')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-[#EF3248] rounded-lg flex items-center justify-center">
                    <i class="fas fa-headset text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tickets của tôi</h1>
                    <p class="text-gray-600">Quản lý các yêu cầu hỗ trợ của bạn</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        <span>Tổng cộng {{ $tickets->total() }} ticket</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-clock text-yellow-500"></i>
                        <span>{{ $tickets->where('status', 'open')->count() }} chờ xử lý</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>{{ $tickets->where('status', 'resolved')->count() }} đã giải quyết</span>
                    </div>
                </div>
                <a href="{{ route('user.tickets.create') }}"
                    class="inline-flex items-center gap-2 bg-[#EF3248] hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-plus"></i>
                    Tạo ticket mới
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-green-400 text-lg"></i>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-triangle text-red-400 text-lg"></i>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Tickets List -->
        @if ($tickets->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-list text-[#EF3248]"></i>
                        Danh sách tickets
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mã ticket</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tiêu đề</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Danh mục</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mức độ ưu tiên</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Trạng thái</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ngày tạo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($tickets as $ticket)
                                <tr
                                    class="hover:bg-gray-50 transition-colors duration-150 {{ $ticket->status === 'open' ? 'bg-blue-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                            <i class="fas fa-hashtag mr-1 text-gray-500"></i>
                                            {{ $ticket->ticket_code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('user.tickets.show', $ticket) }}"
                                            class="text-[#EF3248] hover:text-red-700 font-medium transition-colors duration-150 hover:underline">
                                            {{ Str::limit($ticket->subject, 40) }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @switch($ticket->category)
                                            @case('technical')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                                    <i class="fas fa-cog mr-1"></i> Kỹ thuật
                                                </span>
                                            @break

                                            @case('billing')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                                    <i class="fas fa-credit-card mr-1"></i> Thanh toán
                                                </span>
                                            @break

                                            @case('bug_report')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                                    <i class="fas fa-bug mr-1"></i> Báo lỗi
                                                </span>
                                            @break

                                            @case('feature_request')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                                    <i class="fas fa-lightbulb mr-1"></i> Yêu cầu tính năng
                                                </span>
                                            @break

                                            @default
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-50 text-gray-700 border border-gray-200">
                                                    <i class="fas fa-ellipsis-h mr-1"></i> Khác
                                                </span>
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @switch($ticket->priority)
                                            @case('urgent')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i> Khẩn cấp
                                                </span>
                                            @break

                                            @case('high')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-orange-50 text-orange-700 border border-orange-200">
                                                    <i class="fas fa-arrow-up mr-1"></i> Cao
                                                </span>
                                            @break

                                            @case('medium')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                                    <i class="fas fa-minus mr-1"></i> Trung bình
                                                </span>
                                            @break

                                            @case('low')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-50 text-gray-700 border border-gray-200">
                                                    <i class="fas fa-arrow-down mr-1"></i> Thấp
                                                </span>
                                            @break
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @switch($ticket->status)
                                            @case('open')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                                    <i class="fas fa-circle mr-1"></i> Mở
                                                </span>
                                            @break

                                            @case('in_progress')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                    <i class="fas fa-spinner mr-1"></i> Đang xử lý
                                                </span>
                                            @break

                                            @case('waiting_for_customer')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                                    <i class="fas fa-clock mr-1"></i> Chờ xử lý
                                                </span>
                                            @break

                                            @case('resolved')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                                    <i class="fas fa-check-circle mr-1"></i> Đã giải quyết
                                                </span>
                                            @break

                                            @case('closed')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-50 text-gray-700 border border-gray-200">
                                                    <i class="fas fa-times-circle mr-1"></i> Đã đóng
                                                </span>
                                            @break
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-calendar text-gray-400 text-xs"></i>
                                            {{ $ticket->created_at->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('user.tickets.show', $ticket) }}"
                                                class="inline-flex items-center gap-1 text-[#EF3248] hover:text-red-700 transition-colors duration-150 hover:underline">
                                                <i class="fas fa-eye text-xs"></i>
                                                <span>Xem</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                {{ $tickets->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-20">
                <div
                    class="w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-8">
                    <i class="fas fa-headset text-5xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Bạn chưa có ticket nào</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">Bắt đầu tạo ticket đầu tiên để nhận hỗ trợ từ admin.</p>
                <a href="{{ route('user.tickets.create') }}"
                    class="inline-flex items-center gap-2 bg-[#EF3248] hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-plus"></i>
                    Tạo ticket mới
                </a>
            </div>
        @endif
    </div>

    <style>
        /* Custom pagination styles */
        .pagination {
            @apply flex items-center justify-center space-x-1;
        }

        .pagination .page-item {
            @apply inline-flex;
        }

        .pagination .page-link {
            @apply px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors duration-150;
        }

        .pagination .page-item.active .page-link {
            @apply bg-[#EF3248] border-[#EF3248] text-white hover:bg-red-700;
        }

        .pagination .page-item.disabled .page-link {
            @apply text-gray-300 cursor-not-allowed hover:bg-white;
        }
    </style>
@endsection
