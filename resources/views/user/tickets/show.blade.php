@extends('layouts.app')

@section('title', 'Chi tiết Ticket - ' . $ticket->ticket_code)

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-headset text-[#EF3248] mr-3"></i>
                    Ticket #{{ $ticket->ticket_code }}
                </h1>
                <p class="text-gray-600 text-lg">{{ $ticket->subject }}</p>
            </div>
            <a href="{{ route('user.tickets.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Quay lại
            </a>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Ticket Info Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden sticky top-8">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Thông tin ticket</h2>
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- Status -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Trạng thái</h3>
                            @switch($ticket->status)
                                @case('open')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-circle mr-2"></i> Mở
                                    </span>
                                @break

                                @case('in_progress')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                        <i class="fas fa-spinner mr-2"></i> Đang xử lý
                                    </span>
                                @break

                                @case('waiting_for_customer')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-2"></i> Chờ xử lý
                                    </span>
                                @break

                                @case('resolved')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-2"></i> Đã giải quyết
                                    </span>
                                @break

                                @case('closed')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-times-circle mr-2"></i> Đã đóng
                                    </span>
                                @break
                            @endswitch
                        </div>

                        <!-- Priority -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Mức độ ưu tiên</h3>
                            @switch($ticket->priority)
                                @case('urgent')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-triangle mr-2"></i> Khẩn cấp
                                    </span>
                                @break

                                @case('high')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                        <i class="fas fa-arrow-up mr-2"></i> Cao
                                    </span>
                                @break

                                @case('medium')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-minus mr-2"></i> Trung bình
                                    </span>
                                @break

                                @case('low')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-arrow-down mr-2"></i> Thấp
                                    </span>
                                @break
                            @endswitch
                        </div>

                        <!-- Category -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Danh mục</h3>
                            @switch($ticket->category)
                                @case('technical')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-cog mr-2"></i> Kỹ thuật
                                    </span>
                                @break

                                @case('billing')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-credit-card mr-2"></i> Thanh toán
                                    </span>
                                @break

                                @case('bug_report')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-bug mr-2"></i> Báo lỗi
                                    </span>
                                @break

                                @case('feature_request')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-lightbulb mr-2"></i> Yêu cầu tính năng
                                    </span>
                                @break

                                @default
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-ellipsis-h mr-2"></i> Khác
                                    </span>
                            @endswitch
                        </div>

                        <!-- Dates -->
                        <div class="space-y-3">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Ngày tạo</h3>
                                <p class="text-sm text-gray-900">{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                            </div>

                            @if ($ticket->resolved_at)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Ngày giải quyết</h3>
                                    <p class="text-sm text-gray-900">{{ $ticket->resolved_at->format('d/m/Y H:i') }}</p>
                                </div>
                            @endif

                            @if ($ticket->closed_at)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Ngày đóng</h3>
                                    <p class="text-sm text-gray-900">{{ $ticket->closed_at->format('d/m/Y H:i') }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Status Info -->
                        <div class="pt-4 border-t border-gray-200">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                                    <div>
                                        <h4 class="text-sm font-medium text-blue-800 mb-1">Thông tin trạng thái</h4>
                                        @switch($ticket->status)
                                            @case('open')
                                                <p class="text-sm text-blue-700">Ticket của bạn đã được gửi và đang chờ admin xem
                                                    xét.</p>
                                            @break

                                            @case('in_progress')
                                                <p class="text-sm text-blue-700">Admin đang xử lý ticket của bạn.</p>
                                            @break

                                            @case('waiting_for_customer')
                                                <p class="text-sm text-blue-700">Admin đã phản hồi, vui lòng kiểm tra phản hồi bên
                                                    dưới.</p>
                                            @break

                                            @case('resolved')
                                                <p class="text-sm text-blue-700">Ticket của bạn đã được giải quyết.</p>
                                            @break

                                            @case('closed')
                                                <p class="text-sm text-blue-700">Ticket này đã được đóng.</p>
                                            @break
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Original Description -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-user mr-2 text-[#EF3248]"></i>
                                Mô tả của bạn
                            </h2>
                            <span class="text-sm text-gray-500">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="prose max-w-none">
                            <p class="text-gray-700 leading-relaxed">{{ $ticket->description }}</p>
                        </div>

                        @if ($ticket->attachment_path)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Ảnh đính kèm:</h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                    @php
                                        $ext = strtolower(pathinfo($ticket->attachment_path, PATHINFO_EXTENSION));
                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp

                                    @if ($isImage)
                                        <div class="relative group">
                                            <img src="{{ asset('storage/' . $ticket->attachment_path) }}"
                                                alt="Ảnh đính kèm"
                                                class="w-full h-24 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-80 transition-opacity duration-200"
                                                onclick="openImageModal('{{ asset('storage/' . $ticket->attachment_path) }}')">
                                            <div
                                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
                                                <i
                                                    class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200"></i>
                                            </div>
                                        </div>
                                    @else
                                        <div
                                            class="w-full h-24 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                                            <i class="fas fa-file text-gray-400 text-2xl"></i>
                                            <span class="ml-2 text-xs text-gray-500">{{ strtoupper($ext) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Admin Replies -->
                @if ($ticket->replies->where('user.role', 'admin')->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-blue-100 border-b border-blue-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-headset mr-2 text-[#EF3248]"></i>
                                Phản hồi từ Admin ({{ $ticket->replies->where('user.role', 'admin')->count() }})
                            </h2>
                        </div>

                        <div class="divide-y divide-gray-100">
                            @foreach ($ticket->replies->where('user.role', 'admin') as $reply)
                                <div class="p-6 bg-blue-50">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-gray-900">Admin</span>
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-[#EF3248] text-white">
                                                <i class="fas fa-shield-alt mr-1"></i>
                                                Hỗ trợ
                                            </span>
                                        </div>
                                        <span
                                            class="text-sm text-gray-500">{{ $reply->created_at->format('d/m/Y H:i') }}</span>
                                    </div>

                                    <div class="prose max-w-none">
                                        <p class="text-gray-700 leading-relaxed">{{ $reply->message }}</p>
                                    </div>

                                    @if ($reply->attachment_path)
                                        <div class="mt-3 pt-3 border-t border-blue-200">
                                            <h5 class="text-sm font-medium text-gray-700 mb-2">Ảnh đính kèm từ admin:</h5>
                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                                @php
                                                    $ext = strtolower(
                                                        pathinfo($reply->attachment_path, PATHINFO_EXTENSION),
                                                    );
                                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                @endphp

                                                @if ($isImage)
                                                    <div class="relative group">
                                                        <img src="{{ asset('storage/' . $reply->attachment_path) }}"
                                                            alt="Ảnh đính kèm từ admin"
                                                            class="w-full h-20 object-cover rounded-lg border border-blue-200 cursor-pointer hover:opacity-80 transition-opacity duration-200"
                                                            onclick="openImageModal('{{ asset('storage/' . $reply->attachment_path) }}')">
                                                        <div
                                                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
                                                            <i
                                                                class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200"></i>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div
                                                        class="w-full h-20 bg-gray-100 rounded-lg border border-blue-200 flex items-center justify-center">
                                                        <i class="fas fa-file text-gray-400 text-xl"></i>
                                                        <span
                                                            class="ml-2 text-xs text-gray-500">{{ strtoupper($ext) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    @if ($ticket->status !== 'closed')
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-clock text-blue-600 text-lg"></i>
                                <div>
                                    <h4 class="text-lg font-medium text-blue-800 mb-1">Đang chờ phản hồi</h4>
                                    <p class="text-blue-700">Admin sẽ xem xét và phản hồi ticket của bạn sớm nhất có thể.
                                        Bạn có thể theo dõi trạng thái ở sidebar bên trái.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif



                <!-- Close Ticket Button (if resolved) -->
                @if ($ticket->status === 'resolved' && $ticket->status !== 'closed')
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-check-circle text-green-600 text-lg"></i>
                                <div>
                                    <h4 class="text-lg font-medium text-green-800 mb-1">Ticket đã được giải quyết</h4>
                                    <p class="text-green-700">Nếu bạn hài lòng với giải pháp, bạn có thể đóng ticket này.
                                    </p>
                                </div>
                            </div>
                            <form action="{{ route('user.tickets.close', $ticket) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2"
                                    onclick="return confirm('Bạn có chắc muốn đóng ticket này?')">
                                    <i class="fas fa-check"></i>
                                    Đóng ticket
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 flex items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-full">
            <img id="modalImage" src="" alt="Ảnh toàn màn hình" class="max-w-full max-h-full object-contain">
            <button onclick="closeImageModal()"
                class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300 transition-colors duration-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <script>
        // File upload preview for reply form
        document.getElementById('attachment')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const label = e.target.parentElement;
                const icon = label.querySelector('.fas');
                const text = label.querySelector('p');

                icon.className = 'fas fa-file text-green-600 text-2xl mb-1';
                text.innerHTML = `<span class="font-semibold">${file.name}</span>`;
            }
        });

        // Image modal functions
        function openImageModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
@endsection
