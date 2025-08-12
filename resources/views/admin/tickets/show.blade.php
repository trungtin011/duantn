@extends('layouts.admin')

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
            <div class="flex gap-3">
                <a href="{{ route('admin.tickets.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Quay lại
                </a>
                @if ($ticket->status !== 'closed')
                    <form action="{{ route('admin.tickets.close', $ticket) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2"
                            onclick="return confirm('Bạn có chắc muốn đóng ticket này?')">
                            <i class="fas fa-times"></i>
                            Đóng ticket
                        </button>
                    </form>
                @endif
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

                        <!-- Customer Info -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Khách hàng</h3>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $ticket->user->fullname }}</p>
                                    <p class="text-xs text-gray-500">{{ $ticket->user->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="space-y-3">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Ngày tạo</h3>
                                <p class="text-sm text-gray-900">{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                            </div>

                            @if ($ticket->assignedTo)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Được phân công cho</h3>
                                    <p class="text-sm text-gray-900">{{ $ticket->assignedTo->fullname }}</p>
                                </div>
                            @endif

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

                        <!-- Status Update -->
                        @if ($ticket->status !== 'closed')
                            <div class="pt-4 border-t border-gray-200">
                                <h3 class="text-sm font-medium text-gray-700 mb-3">Cập nhật trạng thái</h3>
                                <form action="{{ route('admin.tickets.update.status', $ticket) }}" method="POST"
                                    class="space-y-3">
                                    @csrf
                                    <select name="status"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EF3248] focus:border-[#EF3248]">
                                        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Mở
                                        </option>
                                        <option value="in_progress"
                                            {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>Đang xử lý</option>
                                        <option value="waiting_for_customer"
                                            {{ $ticket->status == 'waiting_for_customer' ? 'selected' : '' }}>Chờ xử lý
                                        </option>
                                        <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Đã
                                            giải quyết</option>
                                    </select>
                                    <button type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                        Cập nhật
                                    </button>
                                </form>
                            </div>
                        @endif
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
                                Mô tả từ khách hàng
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

                <!-- Replies -->
                @if ($ticket->replies->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-comments mr-2 text-[#EF3248]"></i>
                                Lịch sử phản hồi ({{ $ticket->replies->count() }})
                            </h2>
                        </div>

                        <div class="divide-y divide-gray-200">
                            @foreach ($ticket->replies as $reply)
                                <div class="p-6 {{ $reply->user->role === 'admin' ? 'bg-blue-50' : 'bg-gray-50' }}">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-gray-900">{{ $reply->user->fullname }}</span>
                                            @if ($reply->user->role === 'admin')
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-[#EF3248] text-white">
                                                    Admin
                                                </span>
                                            @endif
                                            @if ($reply->is_internal)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-600 text-white">
                                                    Nội bộ
                                                </span>
                                            @endif
                                        </div>
                                        <span
                                            class="text-sm text-gray-500">{{ $reply->created_at->format('d/m/Y H:i') }}</span>
                                    </div>

                                    <div class="prose max-w-none">
                                        <p class="text-gray-700 leading-relaxed">{{ $reply->message }}</p>
                                    </div>

                                    @if ($reply->attachment_path)
                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <h5 class="text-sm font-medium text-gray-700 mb-2">Ảnh đính kèm:</h5>
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
                                                            alt="Ảnh đính kèm"
                                                            class="w-full h-20 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-80 transition-opacity duration-200"
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
                @endif

                <!-- Reply Form -->
                @if ($ticket->status !== 'closed')
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-reply mr-2 text-[#EF3248]"></i>
                                Phản hồi
                            </h2>
                        </div>

                        <div class="p-6">
                            <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="mb-6">
                                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nội dung phản hồi <span class="text-red-500">*</span>
                                    </label>
                                    <textarea
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EF3248] focus:border-[#EF3248] transition-colors duration-200 @error('message') border-red-500 @enderror"
                                        id="message" name="message" rows="4" placeholder="Nhập nội dung phản hồi..." required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-6">
                                    <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">
                                        File đính kèm <span class="text-gray-500">(tùy chọn)</span>
                                    </label>
                                    <div class="flex items-center justify-center w-full">
                                        <label for="attachment"
                                            class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                            <div class="flex flex-col items-center justify-center pt-2 pb-3">
                                                <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-1"></i>
                                                <p class="text-xs text-gray-500">Click để upload file</p>
                                            </div>
                                            <input id="attachment" name="attachment" type="file" class="hidden"
                                                accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx" />
                                        </label>
                                    </div>
                                    @error('attachment')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-xs text-gray-500">
                                        Hỗ trợ: JPG, JPEG, PNG, GIF, PDF, DOC, DOCX (tối đa 5MB)
                                    </p>
                                </div>

                                <div class="mb-6">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="is_internal" value="1"
                                            class="rounded border-gray-300 text-[#EF3248] focus:ring-[#EF3248]">
                                        <span class="ml-2 text-sm text-gray-700">Ghi chú nội bộ (chỉ admin thấy)</span>
                                    </label>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="bg-[#EF3248] hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                                        <i class="fas fa-paper-plane"></i>
                                        Gửi phản hồi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-600"></i>
                            <p class="text-blue-800">Ticket này đã được đóng và không thể phản hồi thêm.</p>
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
        document.getElementById('attachment').addEventListener('change', function(e) {
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
