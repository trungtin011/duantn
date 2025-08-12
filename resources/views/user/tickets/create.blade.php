@extends('layouts.app')

@section('title', 'Tạo ticket mới')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-[#EF3248] rounded-lg flex items-center justify-center">
                    <i class="fas fa-headset text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tạo ticket mới</h1>
                    <p class="text-gray-600">Gửi yêu cầu hỗ trợ cho admin</p>
                </div>
            </div>

            <div class="flex justify-start">
                <a href="{{ route('user.tickets.index') }}"
                    class="inline-flex items-center gap-2 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-arrow-left"></i>
                    Quay lại danh sách
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-edit text-[#EF3248]"></i>
                            Thông tin ticket
                        </h2>
                    </div>

                    <div class="p-6">
                        <form action="{{ route('user.tickets.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Subject -->
                                <div class="md:col-span-2">
                                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tiêu đề <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EF3248] focus:border-[#EF3248] transition-colors duration-200 @error('subject') border-red-500 @enderror"
                                        id="subject" name="subject" value="{{ old('subject') }}"
                                        placeholder="Nhập tiêu đề ngắn gọn cho vấn đề của bạn..." required>
                                    @error('subject')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                        Danh mục <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EF3248] focus:border-[#EF3248] transition-colors duration-200 @error('category') border-red-500 @enderror"
                                        id="category" name="category" required>
                                        <option value="">Chọn danh mục</option>
                                        <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>🔧
                                            Kỹ thuật</option>
                                        <option value="billing" {{ old('category') == 'billing' ? 'selected' : '' }}>💳
                                            Thanh toán</option>
                                        <option value="bug_report" {{ old('category') == 'bug_report' ? 'selected' : '' }}>
                                            🐛 Báo lỗi</option>
                                        <option value="feature_request"
                                            {{ old('category') == 'feature_request' ? 'selected' : '' }}>💡 Yêu cầu tính
                                            năng</option>
                                        <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>📋
                                            Chung</option>
                                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>❓ Khác
                                        </option>
                                    </select>
                                    @error('category')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Priority -->
                                <div>
                                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                                        Mức độ ưu tiên <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EF3248] focus:border-[#EF3248] transition-colors duration-200 @error('priority') border-red-500 @enderror"
                                        id="priority" name="priority" required>
                                        <option value="">Chọn mức độ</option>
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>🟢 Thấp
                                        </option>
                                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>🟡 Trung
                                            bình</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>🟠 Cao
                                        </option>
                                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>🔴 Khẩn
                                            cấp</option>
                                    </select>
                                    @error('priority')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mt-6">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Mô tả chi tiết <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EF3248] focus:border-[#EF3248] transition-colors duration-200 @error('description') border-red-500 @enderror"
                                    id="description" name="description" rows="6"
                                    placeholder="Mô tả chi tiết vấn đề của bạn để admin có thể hiểu rõ và hỗ trợ tốt nhất..." required>{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Attachment -->
                            <div class="mt-6">
                                <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">
                                    File đính kèm <span class="text-gray-500">(tùy chọn)</span>
                                </label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="attachment"
                                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                        <div class="flex flex-col items-center justify-center pt-2 pb-3">
                                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                            <p class="text-sm text-gray-500">Click để upload file</p>
                                            <p class="text-xs text-gray-400">hoặc kéo thả file vào đây</p>
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

                            <!-- Submit Button -->
                            <div class="mt-8 flex justify-end">
                                <button type="submit"
                                    class="bg-[#EF3248] hover:bg-red-700 text-white px-8 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                                    <i class="fas fa-paper-plane"></i>
                                    Gửi ticket cho admin
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Guidelines -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden sticky top-8">
                    <div class="px-6 py-4 bg-blue-50 border-b border-blue-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-600"></i>
                            Hướng dẫn tạo ticket
                        </h2>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="flex items-start gap-3">
                            <div
                                class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-xs font-medium text-blue-600">1</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-1">Viết tiêu đề rõ ràng</h4>
                                <p class="text-xs text-gray-600">Tiêu đề ngắn gọn giúp admin hiểu nhanh vấn đề của bạn</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div
                                class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-xs font-medium text-blue-600">2</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-1">Mô tả chi tiết</h4>
                                <p class="text-xs text-gray-600">Cung cấp đầy đủ thông tin để admin có thể hỗ trợ tốt nhất
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div
                                class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-xs font-medium text-blue-600">3</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-1">Chọn danh mục phù hợp</h4>
                                <p class="text-xs text-gray-600">Giúp admin phân loại và xử lý ticket nhanh chóng</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div
                                class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-xs font-medium text-blue-600">4</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-1">Đính kèm file nếu cần</h4>
                                <p class="text-xs text-gray-600">Hình ảnh, tài liệu giúp admin hiểu rõ vấn đề hơn</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Info -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mt-6">
                    <div class="px-6 py-4 bg-green-50 border-b border-green-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-clock text-green-600"></i>
                            Quy trình xử lý
                        </h2>
                    </div>

                    <div class="p-6 space-y-3">
                        <div class="flex items-center gap-2 text-sm">
                            <i class="fas fa-circle text-blue-500 text-xs"></i>
                            <span class="text-gray-700">Ticket được gửi</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class="fas fa-spinner text-indigo-500 text-xs"></i>
                            <span class="text-gray-700">Admin xem xét và xử lý</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class="fas fa-reply text-yellow-500 text-xs"></i>
                            <span class="text-gray-700">Admin phản hồi</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class="fas fa-check-circle text-green-500 text-xs"></i>
                            <span class="text-gray-700">Vấn đề được giải quyết</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // File upload preview
        document.getElementById('attachment').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const label = e.target.parentElement;
                const icon = label.querySelector('.fas');
                const text = label.querySelector('p');
                const subText = label.querySelector('p:last-child');

                icon.className = 'fas fa-file text-green-600 text-3xl mb-2';
                text.innerHTML = `<span class="font-semibold">${file.name}</span>`;
                subText.innerHTML = `<span class="text-green-600">File đã được chọn</span>`;
            }
        });
    </script>
@endsection
