@extends('user.account.layout')

@section('account-content')
    <div class="bg-white px-6 py-4 rounded shadow">
        <div class="mb-3 border-b border-gray-200 pb-4 mb-8">
            <h2 class="text-lg">Hồ sơ của tôi</h2>
            <span class="text-sm text-gray-600">Quản lý thông tin hồ sơ để bảo mật tài khoản</span>
        </div>

        @if (session('success'))
            <div
                class="text-green-600 bg-green-100 border border-green-400 p-3 rounded mb-4 relative flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" class="text-green-600 hover:text-green-800 ml-4"
                    onclick="this.parentElement.style.display='none'">&times;</button>
            </div>
        @elseif (session('error'))
            <div
                class="text-red-600 bg-red-100 border border-red-400 p-3 rounded mb-4 relative flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button type="button" class="text-red-600 hover:text-red-800 ml-4"
                    onclick="this.parentElement.style.display='none'">&times;</button>
            </div>
        @endif

        <form method="POST" action="{{ route('account.profile.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="flex space-x-10 gap-10">
                <div class="flex justify-center items-center gap-10 px-[30px]">
                    <div class="text-right flex flex-col gap-[43px] w-[200px]">
                        <label class="block text-gray-400 text-sm">Tên đăng nhập</label>
                        <label class="block text-gray-400 text-sm">Họ tên</label>
                        <label class="block text-gray-400 text-sm">Số điện thoại</label>
                        <label class="block text-gray-400 text-sm">Email</label>
                        <label class="block text-gray-400 text-sm">Ngày sinh</label>
                        <label class="block text-gray-400 text-sm">Giới tính</label>
                    </div>
                    <div class="flex flex-col gap-[20px] w-[500px]">
                        <div class="">
                            <input type="text" name="username" value="{{ old('username', auth()->user()->username) }}"
                        class="w-full border rounded px-3 py-2" required>
                            @error('username')
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Họ & tên -->
                        <div class="">
                            <input type="text" name="fullname" value="{{ old('fullname', $user->fullname) }}"
                                class="w-full border rounded px-3 py-2" required>
                            @error('fullname')
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Số điện thoại -->
                        <div class="">
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full border rounded px-3 py-2">
                            @error('phone')
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="">
                            <input type="text" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full border rounded px-3 py-2">
                            @error('email')
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Ngày sinh -->
                        <div class="">
                            <div class="flex gap-2">
                                <select name="day" id="day" class="w-1/4 border rounded px-3 py-2">
                                    <option value="">Ngày</option>
                                    @for ($i = 1; $i <= 31; $i++)
                                        <option value="{{ $i }}"
                                            {{ old('day', optional($user->birthday)->format('d') == $i ? 'selected' : '') }}>
                                            {{ $i }}</option>
                                    @endfor
                                </select>
                                <select name="month" id="month" class="w-1/4 border rounded px-3 py-2"
                                    onchange="updateDays()">
                                    <option value="">Tháng</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}"
                                            {{ old('month', optional($user->birthday)->format('m') == sprintf('%02d', $i) ? 'selected' : '') }}>
                                            {{ $i }}</option>
                                    @endfor
                                </select>
                                <select name="year" id="year" class="w-2/4 border rounded px-3 py-2"
                                    onchange="updateDays()">
                                    <option value="">Năm</option>
                                    @php $currentYear = date('Y'); @endphp
                                    @for ($i = $currentYear; $i >= 1900; $i--)
                                        <option value="{{ $i }}"
                                            {{ old('year', optional($user->birthday)->format('Y') == $i ? 'selected' : '') }}>
                                            {{ $i }}</option>
                                    @endfor
                                </select>
                                @error('day')
                                    <p class="text-red-600 text-sm">{{ $message }}</p>
                                @enderror

                                @error('month')
                                    <p class="text-red-600 text-sm">{{ $message }}</p>
                                @enderror
                                @error('year')
                                    <p class="text-red-600 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Giới tính -->
                        <div class="">
                            <select name="gender" class="w-full border rounded px-3 py-2">
                                <option value="">-- Chọn --</option>
                                <option value="male"
                                    {{ old('gender', $user->gender->value ?? '') == 'male' ? 'selected' : '' }}>
                                    Nam
                                </option>
                                <option value="female"
                                    {{ old('gender', $user->gender->value ?? '') == 'female' ? 'selected' : '' }}>Nữ
                                </option>
                                <option value="other"
                                    {{ old('gender', $user->gender->value ?? '') == 'other' ? 'selected' : '' }}>
                                    Khác</option>
                            </select>
                            @error('gender')
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="border-l border-gray-200 pl-10 flex items-center">
                    <div class="flex flex-col items-center justify-center gap-5">
                        <div id="avatarPreview">
                            @if ($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}"
                                    class="mt-2 w-20 h-20 rounded-full object-cover">
                            @endif
                        </div>
                        <div class="flex flex-col items-center gap-2 w-[340px]">
                            <label for="avatar"
                                class="cursor-pointer bg-gray-100 text-gray-700 px-4 py-2 rounded hover:bg-gray-200">Chọn
                                ảnh
                            </label>
                            <span class="text-sm text-gray-500 w-[180px] text-center">Định dạng: jpg, png, jpeg. Kích thước
                                tối đa: 2MB</span>
                        </div>
                        <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png"
                            class="w-full border rounded px-3 py-2 hidden" onchange="previewImage()">
                        @error('avatar')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <br class="my-6 border-t border-gray-200">
            <!-- Submit Button -->
            <div class="w-[540px] transform translate-x-1/2">
                <button type="submit" class="bg-[#ef3248] hover:bg-red-600 text-white px-4 py-2">Lưu</button>
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
                // Tính số ngày tối đa trong tháng, bao gồm năm nhuận
                const lastDay = new Date(year, month, 0).getDate();

                // Lưu giá trị ngày hiện tại nếu hợp lệ
                let newSelectedDay = selectedDay;
                if (selectedDay > lastDay) {
                    newSelectedDay = lastDay; // Đặt lại ngày nếu vượt quá số ngày tối đa
                }

                // Xóa các option hiện tại
                daySelect.innerHTML = '<option value="">Ngày</option>';

                // Thêm các option mới dựa trên số ngày tối đa
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
                // Nếu chưa chọn tháng hoặc năm, khôi phục tất cả các ngày
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

        // Gọi hàm updateDays khi trang được tải và khi có thay đổi
        window.addEventListener('load', updateDays);
        document.getElementById('month').addEventListener('change', updateDays);
        document.getElementById('year').addEventListener('change', updateDays);

        function previewImage() {
            const fileInput = document.getElementById('avatar');
            const preview = document.getElementById('avatarPreview');
            const file = fileInput.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'mt-2 w-20 h-20 rounded-full object-cover';
                    preview.innerHTML = ''; // Xóa ảnh cũ
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            } else {
                // Nếu không có file, khôi phục ảnh đại diện hiện tại
                preview.innerHTML = '';
                @if ($user->avatar)
                    const img = document.createElement('img');
                    img.src = '{{ asset('storage/' . $user->avatar) }}';
                    img.className = 'mt-2 w-20 h-20 rounded-full object-cover';
                    preview.appendChild(img);
                @endif
            }
        }
    </script>
@endsection
