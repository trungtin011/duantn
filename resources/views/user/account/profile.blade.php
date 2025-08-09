@extends('user.account.layout')

@section('account-content')
    <div class="bg-white rounded-lg shadow-sm border">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Hồ sơ của tôi</h2>
            <p class="text-sm text-gray-600 mt-1">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mx-6 mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" class="text-green-600 hover:text-green-800" onclick="this.parentElement.remove()">×</button>
            </div>
        @elseif (session('error'))
            <div class="mx-6 mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button type="button" class="text-red-600 hover:text-red-800" onclick="this.parentElement.remove()">×</button>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('account.profile.update') }}" enctype="multipart/form-data" class="p-4 sm:p-6">
            @csrf
            <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
                <!-- Left Column - Form Fields -->
                <div class="flex-1">
                    <div class="space-y-6">
                        <!-- Username -->
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <label class="sm:w-40 md:w-48 text-sm font-medium text-gray-700">Tên đăng nhập</label>
                            <div class="flex-1">
                                <input type="text" name="username" value="{{ old('username', auth()->user()->username) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                @error('username')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <label class="sm:w-40 md:w-48 text-sm font-medium text-gray-700">Họ tên</label>
                            <div class="flex-1">
                                <input type="text" name="fullname" value="{{ old('fullname', $user->fullname) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                @error('fullname')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <label class="sm:w-40 md:w-48 text-sm font-medium text-gray-700">Số điện thoại</label>
                            <div class="flex-1">
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                @error('phone')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <label class="sm:w-40 md:w-48 text-sm font-medium text-gray-700">Email</label>
                            <div class="flex-1">
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                @error('email')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Date of Birth -->
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <label class="sm:w-40 md:w-48 text-sm font-medium text-gray-700">Ngày sinh</label>
                            <div class="flex-1 grid grid-cols-3 gap-2">
                                <select name="day" id="day" class="col-span-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">Ngày</option>
                                    @for ($i = 1; $i <= 31; $i++)
                                        <option value="{{ $i }}" {{ old('day', optional($user->birthday)->format('d') == $i ? 'selected' : '') }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                                <select name="month" id="month" class="col-span-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" onchange="updateDays()">
                                    <option value="">Tháng</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('month', optional($user->birthday)->format('m') == sprintf('%02d', $i) ? 'selected' : '') }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                                <select name="year" id="year" class="col-span-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" onchange="updateDays()">
                                    <option value="">Năm</option>
                                    @php $currentYear = date('Y'); @endphp
                                    @for ($i = $currentYear; $i >= 1900; $i--)
                                        <option value="{{ $i }}" {{ old('year', optional($user->birthday)->format('Y') == $i ? 'selected' : '') }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        @error('day')<p class="text-red-600 text-sm sm:ml-40 md:ml-48">{{ $message }}</p>@enderror
                        @error('month')<p class="text-red-600 text-sm sm:ml-40 md:ml-48">{{ $message }}</p>@enderror
                        @error('year')<p class="text-red-600 text-sm sm:ml-40 md:ml-48">{{ $message }}</p>@enderror

                        <!-- Gender -->
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <label class="sm:w-40 md:w-48 text-sm font-medium text-gray-700">Giới tính</label>
                            <div class="flex-1">
                                <select name="gender" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">-- Chọn --</option>
                                    <option value="male" {{ old('gender', $user->gender->value ?? '') == 'male' ? 'selected' : '' }}>Nam</option>
                                    <option value="female" {{ old('gender', $user->gender->value ?? '') == 'female' ? 'selected' : '' }}>Nữ</option>
                                    <option value="other" {{ old('gender', $user->gender->value ?? '') == 'other' ? 'selected' : '' }}>Khác</option>
                                </select>
                                @error('gender')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="mt-6 sm:mt-8">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            Lưu
                        </button>
                    </div>
                </div>

                <!-- Right Column - Avatar Upload -->
                <div class="w-full lg:w-80 lg:border-l border-gray-200 lg:pl-8">
                    <div class="flex flex-col items-center sm:flex-row lg:flex-col gap-4">
                        <!-- Current Avatar -->
                        <div class="mb-2 lg:mb-4">
                            <div id="avatarPreview" class="w-24 h-24 sm:w-28 sm:h-28 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                                @include('partials.user-avatar', ['user' => $user, 'size' => '2xl'])
                            </div>
                        </div>

                        <!-- Upload Button -->
                        <div class="text-center sm:text-left lg:text-center">
                            <label for="avatar" class="inline-block cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                                Chọn ảnh
                            </label>
                            <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png" class="hidden" onchange="previewImage()">
                            
                            <div class="mt-3 text-xs text-gray-500">
                                <p>Định dạng: jpg, png, jpeg.</p>
                                <p>Kích thước tối đa: 2MB</p>
                            </div>
                            
                            @error('avatar')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function updateDays() {
            const monthSelect = document.getElementById('month');
            const yearSelect = document.getElementById('year');
            const daySelect = document.getElementById('day');

            const month = parseInt(monthSelect.value);
            const year = parseInt(yearSelect.value);
            const selectedDay = parseInt(daySelect.value) || 1;

            if (month && year) {
                const lastDay = new Date(year, month, 0).getDate();
                let newSelectedDay = selectedDay;
                if (selectedDay > lastDay) {
                    newSelectedDay = lastDay;
                }

                daySelect.innerHTML = '<option value="">Ngày</option>';
                for (let i = 1; i <= lastDay; i++) {
                    const option = document.createElement('option');
                    option.value = i;
                    option.text = i;
                    if (i === newSelectedDay) {
                        option.selected = true;
                    }
                    daySelect.appendChild(option);
                }
            } else {
                daySelect.innerHTML = '<option value="">Ngày</option>';
                for (let i = 1; i <= 31; i++) {
                    const option = document.createElement('option');
                    option.value = i;
                    option.text = i;
                    if (i === selectedDay && i <= 31) {
                        option.selected = true;
                    }
                    daySelect.appendChild(option);
                }
            }
        }

        function previewImage() {
            const fileInput = document.getElementById('avatar');
            const preview = document.getElementById('avatarPreview');
            const file = fileInput.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-full object-cover';

                    preview.innerHTML = '';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
                @if ($user->avatar)
                    const img = document.createElement('img');
                    img.src = '{{ getUserAvatar($user->avatar) }}';
                    img.className = 'w-full h-full object-cover';
                    preview.appendChild(img);
                @else
                    const placeholder = document.createElement('div');
                    placeholder.className = 'w-full h-full flex items-center justify-center text-2xl font-bold text-gray-400';
                    placeholder.textContent = '{{ strtoupper(substr($user->fullname ?? ($user->username ?? 'U'), 0, 1)) }}';
                    preview.appendChild(placeholder);
                @endif
            }
        }

        // Initialize
        window.addEventListener('load', updateDays);
        
        // Add null checks before adding event listeners
        const monthSelect = document.getElementById('month');
        const yearSelect = document.getElementById('year');
        
        if (monthSelect) {
            monthSelect.addEventListener('change', updateDays);
        }
        
        if (yearSelect) {
            yearSelect.addEventListener('change', updateDays);
        }
    </script>
@endsection
