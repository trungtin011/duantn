@extends('layouts.seller_home')
@section('title', 'Thông tin cá nhân')
@section('content')
    <div class="admin-page-header mb-5">
        <h1 class="admin-page-title text-2xl">Thông tin cá nhân</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / Cập nhật thông
            tin cá nhân
        </div>
    </div>
    @include('seller.partials.account_submenu')
    <div class="bg-white rounded-lg p-4 shadow-sm">
        <form action="{{ route('seller.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('POST')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-600 font-medium mb-1">Họ và tên</label>
                            <input type="text" name="fullname" value="{{ old('fullname', auth()->user()->fullname) }}"
                                class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 font-medium mb-1">Tên người dùng <span class="text-red-500">*</span></label>
                            <input type="text" name="username" value="{{ old('username', auth()->user()->username) }}"
                                class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full" maxlength="50">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 font-medium mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 font-medium mb-1">Số điện thoại</label>
                            <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                                class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-600 font-medium mb-1">Giới tính</label>
                            <select name="gender"
                                class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full">
                                @php $g = old('gender', auth()->user()->gender); @endphp
                                <option value="male" {{ $g == 'male' ? 'selected' : '' }}>Nam</option>
                                <option value="female" {{ $g == 'female' ? 'selected' : '' }}>Nữ</option>
                                <option value="other" {{ $g == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 font-medium mb-1">Ngày sinh</label>
                            <input type="date" name="birthday"
                                value="{{ old('birthday', optional(auth()->user()->birthday)->format('Y-m-d')) }}"
                                class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full">
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-gray-600 font-medium mb-2">Ảnh đại diện</label>
                    <input id="avatar" type="file" name="avatar" accept="image/*" class="hidden">
                    @if (auth()->user()->avatar)
                        <div id="avatar_preview"
                            class="mt-1 border rounded-md p-2 bg-gray-50 flex items-center justify-center">
                            <img src="{{ asset(auth()->user()->avatar) }}" alt="Avatar"
                                class="max-h-28 rounded object-contain">
                        </div>
                        <label for="avatar"
                            class="mt-2 inline-flex items-center text-xs text-blue-600 rounded cursor-pointer hover:underline hover:text-blue-600">Chỉnh
                            sửa ảnh</label>
                    @else
                        <label id="avatar_placeholder" for="avatar"
                            class="mt-1 block border-2 border-dashed border-gray-300 rounded-md p-6 text-center cursor-pointer hover:bg-gray-50">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <i class="fas fa-cloud-upload-alt text-2xl mb-2"></i>
                                <div class="text-sm"><span class="text-blue-600 font-medium">Click để tải lên</span> hoặc
                                    kéo thả</div>
                                <div class="text-xs mt-1">PNG, JPG, JPEG (Tối đa 2MB)</div>
                            </div>
                        </label>
                    @endif
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center text-xs bg-[#f42f46] hover:bg-[#d62a3e] text-white px-5 py-2 rounded-md shadow-sm transition-colors">
                    <i class="fas fa-save mr-2"></i> Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
    <script>
        (function() {
            const input = document.getElementById('avatar');
            const previewId = 'avatar_preview';
            input?.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const url = URL.createObjectURL(this.files[0]);
                    let container = document.getElementById(previewId);
                    if (!container) {
                        container = document.createElement('div');
                        container.id = previewId;
                        container.className =
                            'mt-1 border rounded-md p-2 bg-gray-50 flex items-center justify-center';
                        input.parentElement.appendChild(container);
                    }
                    container.innerHTML = '';
                    const img = document.createElement('img');
                    img.src = url;
                    img.className = 'max-h-28 rounded object-contain';
                    container.appendChild(img);
                    const placeholder = document.getElementById('avatar_placeholder');
                    if (placeholder) placeholder.classList.add('hidden');
                }
            });
        })();
    </script>
@endsection
