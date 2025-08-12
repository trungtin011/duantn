@extends('layouts.admin')

@section('title', 'Quản lý Tickets - Admin')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-[#EF3248] rounded-lg flex items-center justify-center">
                    <i class="fas fa-headset text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Quản lý Tickets</h1>
                    <p class="text-gray-600">Xem xét và phản hồi các yêu cầu hỗ trợ từ khách hàng</p>
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
                </div>

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

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-filter text-[#EF3248]"></i>
                Bộ lọc
            </h3>

            <form method="GET" action="{{ route('admin.tickets.index') }}"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EF3248] focus:border-[#EF3248]"
                        placeholder="Mã ticket, tiêu đề...">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                    <select name="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EF3248] focus:border-[#EF3248]">
                        <option value="">Tất cả</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Mở</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Đang xử lý
                        </option>
                        <option value="waiting_for_customer"
                            {{ request('status') == 'waiting_for_customer' ? 'selected' : '' }}>Chờ phản hồi</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Đã giải quyết
                        </option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Đã đóng</option>
                    </select>
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mức độ ưu tiên</label>
                    <select name="priority"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EF3248] focus:border-[#EF3248]">
                        <option value="">Tất cả</option>
                        <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Khẩn cấp</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Cao</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Trung bình</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Thấp</option>
                    </select>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
                    <select name="category"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EF3248] focus:border-[#EF3248]">
                        <option value="">Tất cả</option>
                        <option value="technical" {{ request('category') == 'technical' ? 'selected' : '' }}>Kỹ thuật
                        </option>
                        <option value="billing" {{ request('category') == 'billing' ? 'selected' : '' }}>Thanh toán
                        </option>
                        <option value="bug_report" {{ request('category') == 'bug_report' ? 'selected' : '' }}>Báo lỗi
                        </option>
                        <option value="feature_request" {{ request('category') == 'feature_request' ? 'selected' : '' }}>
                            Yêu cầu tính năng</option>
                        <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>Chung</option>
                        <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>

                <!-- Filter Buttons -->
                <div class="md:col-span-2 lg:col-span-4 flex gap-3">
                    <button type="submit"
                        class="bg-[#EF3248] hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-search mr-2"></i>
                        Lọc
                    </button>
                    <a href="{{ route('admin.tickets.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Xóa bộ lọc
                    </a>
                </div>
            </form>
        </div>

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
                                    Khách hàng</th>
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
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-gray-600 text-sm"></i>
                                            </div>
                                            <span
                                                class="text-sm font-medium text-gray-900">{{ $ticket->user->fullname }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.tickets.show', $ticket) }}"
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
                                            <a href="{{ route('admin.tickets.show', $ticket) }}"
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
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Không có ticket nào</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">Hiện tại chưa có yêu cầu hỗ trợ nào từ khách hàng.</p>
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
