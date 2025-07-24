@extends('layouts.seller')

@section('content')
    <div class="container mx-auto py-5 flex flex-col" style="min-height: 80vh;">
        <!-- Breadcrumb -->
        <div class="flex flex-wrap items-center gap-2 my-10 md:my-10 text-sm md:text-base">
            <a href="{{ route('home') }}" class="text-gray-500 hover:underline">Trang chủ</a>
            <span>/</span>
            <span>Đăng ký trở thành người bán</span>
        </div>
        <div class="p-6 w-full shadow-md rounded-[10px]">
            <!-- Stepper -->
            @include('seller.register.stepper')
            <script>
                if (typeof updateStepper === 'function') {
                    updateStepper(3);
                } else {
                    console.error('updateStepper function is not defined');
                }
            </script>
            <form action="{{ route('seller.register.step4.post') }}" method="POST" enctype="multipart/form-data" class="rounded-2xl p-6">
                @csrf
                @if ($errors->any())
                    <!-- Đã chuyển lỗi validate xuống từng trường -->
                @endif
                <div class="min-h-screen bg-white flex items-center justify-center">
                    <div class="rounded-2xl p-6 flex flex-col gap-10">
                        <!-- Tiêu đề và thông báo -->
                        <div class="flex items-center bg-blue-100 text-blue-700 border border-blue-300 p-3 rounded text-sm">
                            <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8,1 C11.8659932,1 15,4.13400675 15,8 C15,11.8659932 11.8659932,15 8,15 C4.13400675,15 1,11.8659932 1,8 C1,4.13400675 4.13400675,1 8,1 Z M8.30163718,10.5595183 C8.14108673,10.7430046 8.00347205,10.8347477 7.91172893,10.8347477 C7.86585737,10.8347477 7.84292159,10.8347477 7.79705003,10.8118119 C7.75117847,10.7888761 7.75117847,10.7430046 7.75117847,10.6741972 C7.75117847,10.6053899 7.77411425,10.4219037 7.84292159,10.1696101 C7.86585737,10.077867 7.91172893,9.89438073 7.98053627,9.64208716 L8.80622434,6.6375 L8.34750874,6.72924312 C8.18695829,6.7521789 7.91172893,6.79805046 7.54475645,6.84392202 C7.15484819,6.88979358 6.87961884,6.91272936 6.67319682,6.93566514 L6.67319682,7.2108945 C6.90255462,7.2108945 7.06310507,7.23383028 7.15484819,7.27970183 C7.24659131,7.32557339 7.29246287,7.39438073 7.29246287,7.53199541 L7.29246287,7.60080275 C7.29246287,7.62373853 7.29246287,7.64667431 7.26952709,7.69254587 L6.62732526,10.077867 C6.5814537,10.2613532 6.53558214,10.3989679 6.51264636,10.490711 C6.4667748,10.6741972 6.44383902,10.8118119 6.44383902,10.903555 C6.44383902,11.1558486 6.51264636,11.3393349 6.67319682,11.4540138 C6.83374728,11.5686927 6.99429774,11.6375 7.20071975,11.6375 C7.54475645,11.6375 7.86585737,11.4998853 8.16402251,11.2017202 C8.34750874,11.0182339 8.59980232,10.6741972 8.94383902,10.1696101 L8.71448122,10.0090596 C8.59980232,10.1925459 8.43925186,10.3760321 8.30163718,10.5595183 L8.30163718,10.5595183 Z M8.19383902,4.3625 C7.97609708,4.3625 7.80674225,4.43508065 7.66158095,4.58024194 C7.51641966,4.72540323 7.44383902,4.89475806 7.44383902,5.1125 C7.44383902,5.33024194 7.51641966,5.49959677 7.66158095,5.64475806 C7.80674225,5.78991935 7.97609708,5.8625 8.19383902,5.8625 C8.41158095,5.8625 8.58093579,5.78991935 8.72609708,5.64475806 C8.87125837,5.49959677 8.94383902,5.33024194 8.94383902,5.1125 C8.94383902,4.89475806 8.87125837,4.72540323 8.72609708,4.58024194 C8.58093579,4.43508065 8.41158095,4.3625 8.19383902,4.3625 L8.19383902,4.3625 Z"></path>
                            </svg>
                            <p>
                                Vui lòng cung cấp Thông Tin Định Danh của Chủ Shop (nếu là cá nhân), hoặc Người Đại Diện Pháp Lý trên giấy đăng ký kinh doanh.
                            </p>
                        </div>

                        <!-- Hình thức định danh -->
                        <div class="grid grid-cols-1 md:grid-cols-5 items-center gap-4">
                            <label class="col-span-1 font-medium text-sm text-gray-700 text-end">
                                <sup class="text-red-500 text-[12px]">*</sup> Hình Thức Định Danh
                            </label>
                            <div class="col-span-4 flex items-center gap-6 text-sm">
                                <label>
                                    <input type="radio" name="id_type" value="cccd" {{ old('id_type', 'cccd') == 'cccd' ? 'checked' : '' }}>
                                    Căn Cước Công Dân (CCCD)
                                </label>
                                <label>
                                    <input type="radio" name="id_type" value="cmnd" {{ old('id_type') == 'cmnd' ? 'checked' : '' }}>
                                    Chứng Minh Nhân Dân (CMND)
                                </label>
                                <label>
                                    <input type="radio" name="id_type" value="passport" {{ old('id_type') == 'passport' ? 'checked' : '' }}>
                                    Hộ chiếu
                                </label>
                            </div>
                            @error('id_type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Các trường nhập liệu định danh, bọc trong div để dễ ẩn/hiện -->
                        <div id="identity-fields" @if(!$errors->any() && !old('id_number')) style="display:none;" @endif class="space-y-6 mt-6">

                            <!-- Phần Thông tin cá nhân -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">📄 Thông tin cá nhân</h3>
                                <div class="space-y-4 p-4 border rounded-lg bg-gray-50">
                                    <!-- Họ và tên -->
                                    <div class="grid grid-cols-1 md:grid-cols-5 items-start gap-4">
                                        <label class="col-span-1 text-sm font-medium text-gray-700 text-end pt-2">Họ & Tên</label>
                                        <div class="col-span-4 space-y-1">
                                            <input type="text" name="full_name" maxlength="100" required class="w-full md:w-1/2 border rounded px-3 py-2 text-sm" placeholder="Nhập vào" value="{{ old('full_name') }}">
                                            @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                            <p class="text-xs text-gray-500">Theo CMND/CCCD/Hộ Chiếu</p>
                                        </div>
                                    </div>
                                    <!-- Giới tính -->
                                    <div class="grid grid-cols-1 md:grid-cols-5 items-center gap-4">
                                        <label class="col-span-1 text-sm font-medium text-gray-700 text-end">Giới tính</label>
                                        <div class="col-span-4 flex items-center gap-6 text-sm">
                                            <label><input type="radio" name="gender" value="male" {{ old('gender') == 'male' ? 'checked' : '' }}> Nam</label>
                                            <label><input type="radio" name="gender" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}> Nữ</label>
                                            @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <!-- Ngày sinh -->
                                    <div class="grid grid-cols-1 md:grid-cols-5 items-center gap-4">
                                        <label class="col-span-1 text-sm font-medium text-gray-700 text-end">Ngày sinh</label>
                                        <input type="date" name="birthday" required class="col-span-2 border rounded px-3 py-2 text-sm" value="{{ old('birthday') }}">
                                        @error('birthday') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <!-- Quốc tịch -->
                                    <div class="grid grid-cols-1 md:grid-cols-5 items-center gap-4">
                                        <label class="col-span-1 text-sm font-medium text-gray-700 text-end">Quốc tịch</label>
                                        <input type="text" name="nationality" maxlength="50" required class="col-span-2 border rounded px-3 py-2 text-sm" placeholder="Nhập vào" value="{{ old('nationality') }}">
                                        @error('nationality') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <!-- Quê quán -->
                                    <div class="grid grid-cols-1 md:grid-cols-5 items-center gap-4">
                                        <label class="col-span-1 text-sm font-medium text-gray-700 text-end">Quê quán</label>
                                        <input type="text" name="hometown" maxlength="100" required class="col-span-2 border rounded px-3 py-2 text-sm" placeholder="Nhập vào" value="{{ old('hometown') }}">
                                        @error('hometown') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <!-- Nơi thường trú -->
                                    <div class="grid grid-cols-1 md:grid-cols-5 items-center gap-4">
                                        <label class="col-span-1 text-sm font-medium text-gray-700 text-end">Nơi thường trú</label>
                                        <input type="text" name="residence" maxlength="100" required class="col-span-2 border rounded px-3 py-2 text-sm" placeholder="Nhập vào" value="{{ old('residence') }}">
                                        @error('residence') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Phần Thông tin CCCD -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">🪪 Thông tin CCCD</h3>
                                <div class="space-y-4 p-4 border rounded-lg bg-gray-50">
                                    <!-- Số giấy tờ -->
                                    <div class="grid grid-cols-1 md:grid-cols-5 items-center gap-4">
                                        <label class="col-span-1 text-sm font-medium text-gray-700 text-end">Số CCCD</label>
                                        <input type="text" name="id_number" maxlength="20" required class="col-span-2 border rounded px-3 py-2 text-sm" placeholder="Nhập vào" value="{{ old('id_number') }}">
                                        @error('id_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <!-- Ngày cấp -->
                                    <div class="grid grid-cols-1 md:grid-cols-5 items-center gap-4">
                                        <label class="col-span-1 text-sm font-medium text-gray-700 text-end">Ngày cấp</label>
                                        <input type="date" name="identity_card_date" required class="col-span-2 border rounded px-3 py-2 text-sm" value="{{ old('identity_card_date') }}">
                                        @error('identity_card_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <!-- Nơi cấp -->
                                    <div class="grid grid-cols-1 md:grid-cols-5 items-center gap-4">
                                        <label class="col-span-1 text-sm font-medium text-gray-700 text-end">Nơi cấp</label>
                                        <input type="text" name="identity_card_place" maxlength="255" required class="col-span-2 border rounded px-3 py-2 text-sm" placeholder="Nhập vào" value="{{ old('identity_card_place') }}">
                                        @error('identity_card_place') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <!-- Đặc điểm nhận dạng -->
                                    <div class="grid grid-cols-1 md:grid-cols-5 items-center gap-4">
                                        <label class="col-span-1 text-sm font-medium text-gray-700 text-end">Đặc điểm nhận dạng</label>
                                        <input type="text" name="dac_diem_nhan_dang" maxlength="255" class="col-span-2 border rounded px-3 py-2 text-sm" placeholder="Nhập vào" value="{{ old('dac_diem_nhan_dang') }}">
                                        @error('dac_diem_nhan_dang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ảnh mặt trước CCCD -->
                        <div class="grid grid-cols-1 md:grid-cols-5 items-start gap-4 mb-4">
                            <label class="col-span-1 block text-sm font-medium text-gray-700 text-end pt-2">
                                <sup class="text-red-500 text-[12px]">*</sup> Ảnh mặt trước CCCD
                            </label>
                            <div class="col-span-4 w-full md:w-1/3 flex flex-col items-center justify-center border border-dashed border-blue-400 rounded p-6 text-center space-y-2 relative bg-blue-50">
                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <p class="text-sm text-blue-700">Tải lên ảnh mặt trước CCCD (không vượt quá 5MB)</p>
                                <input type="file" name="file" accept="image/*" class="hidden" id="filechoose" required>
                                <button type="button" onclick="document.getElementById('filechoose').click()" class="px-4 py-2 bg-blue-500 text-white rounded text-sm">Chọn ảnh mặt trước</button>
                                <img id="filepreview" src="#" alt="Preview" style="display:none;max-width:200px;margin-top:10px;border-radius:8px;box-shadow:0 0 4px #ccc;" />
                                @error('file')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <!-- Ảnh mặt sau CCCD -->
                        <div class="grid grid-cols-1 md:grid-cols-5 items-start gap-4 mb-4">
                            <label class="col-span-1 block text-sm font-medium text-gray-700 text-end pt-2">
                                <sup class="text-red-500 text-[12px]">*</sup> Ảnh mặt sau CCCD
                            </label>
                            <div class="col-span-4 w-full md:w-1/3 flex flex-col items-center justify-center border border-dashed border-green-400 rounded p-6 text-center space-y-2 relative bg-green-50">
                                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <p class="text-sm text-green-700">Tải lên ảnh mặt sau CCCD (không vượt quá 5MB)</p>
                                <input type="file" name="backfile" accept="image/*" class="hidden" id="backfilechoose" required>
                                <button type="button" onclick="document.getElementById('backfilechoose').click()" class="px-4 py-2 bg-green-600 text-white rounded text-sm">Chọn ảnh mặt sau</button>
                                <img id="backfilepreview" src="#" alt="Preview" style="display:none;max-width:200px;margin-top:10px;border-radius:8px;box-shadow:0 0 4px #ccc;" />
                                <div id="scan-cccd-back-error" class="text-red-500 text-xs mt-2"></div>
                            </div>
                        </div>
                        <!-- Nút Quét CCCD đặt dưới cả hai khối -->
                        <div class="flex justify-end mt-4 mb-2">
                            <button type="button" id="scan-cccd-btn" class="px-4 py-2 bg-yellow-500 text-white rounded text-sm">Quét CCCD</button>
                        </div>
                        <div id="scan-cccd-loading" style="display:none;" class="mt-2"><span class="loader"></span> Đang quét...</div>
                        <div id="scan-cccd-error" class="text-red-500 text-xs mt-2"></div>
                        <div id="scan-cccd-success" class="text-green-600 text-sm mt-2" style="display:none;"></div>

                        <!-- Xác nhận -->
                        <div class="flex items-center space-x-2 text-sm bg-gray-100 p-3 rounded">
                            <input type="checkbox" id="confirm" name="confirm" required>
                            <label for="confirm">Tôi xác nhận tất cả dữ liệu đã cung cấp là chính xác và trung thực. Tôi đã đọc và đồng ý với <a href="" class="text-blue-500 hover:underline">Chính Sách Bảo Mật</a>.</label>
                            @error('confirm')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- hr -->
                <hr class="my-5">
                <!-- Nút điều hướng -->
                <div class="flex justify-between">
                    <div>
                        <button type="button" onclick="window.history.back()" class="px-4 py-2 bg-white border rounded hover:bg-gray-100">Quay lại</button>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700">Tiếp theo</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('filechoose').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('filepreview');
            if (file) {
                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    alert('Kích thước tệp vượt quá 5MB. Vui lòng chọn tệp nhỏ hơn.');
                    e.target.value = '';
                    preview.style.display = 'none';
                } else {
                    const fileName = file.name;
                    const label = document.createElement('p');
                    label.textContent = `Tệp đã chọn: ${fileName}`;
                    label.className = 'text-sm text-gray-500 mt-2';
                    const container = this.parentElement;
                    const existingLabel = container.querySelector('p.text-sm');
                    if (existingLabel) existingLabel.remove();
                    container.appendChild(label);
                    // Hiển thị preview ảnh
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        preview.src = ev.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            } else {
                preview.style.display = 'none';
            }
        });
        document.getElementById('backfilechoose').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    alert('Kích thước tệp vượt quá 5MB. Vui lòng chọn tệp nhỏ hơn.');
                    e.target.value = '';
                    document.getElementById('backfilepreview').style.display = 'none';
                } else {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        document.getElementById('backfilepreview').src = ev.target.result;
                        document.getElementById('backfilepreview').style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            } else {
                document.getElementById('backfilepreview').style.display = 'none';
            }
        });
        // CCCD scan integration (gửi 2 file từ input đã có)
        const scanBtn = document.getElementById('scan-cccd-btn');
        const fileInput = document.getElementById('filechoose'); // mặt trước
        const loadingDiv = document.getElementById('scan-cccd-loading');
        const errorDiv = document.getElementById('scan-cccd-error');
        scanBtn.addEventListener('click', function() {
            errorDiv.textContent = '';
            document.getElementById('scan-cccd-success').style.display = 'none';
            document.getElementById('scan-cccd-success').textContent = '';
            if (!fileInput.files[0] || !document.getElementById('backfilechoose').files[0]) {
                errorDiv.textContent = 'Vui lòng chọn đủ ảnh mặt trước và mặt sau CCCD trước khi quét.';
                return;
            }
            const formData = new FormData();
            formData.append('front_image', fileInput.files[0]);
            formData.append('back_image', document.getElementById('backfilechoose').files[0]);
            loadingDiv.style.display = 'block';
            scanBtn.disabled = true;
            fetch('http://127.0.0.1:5000/process_cccd', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                loadingDiv.style.display = 'none';
                scanBtn.disabled = false;
                if (data.error) {
                    errorDiv.textContent = data.error;
                    return;
                }
                // ƯU TIÊN DỮ LIỆU FINAL_DATA (kiểu mới)
                if (data.full_name) document.querySelector('input[name="full_name"]').value = data.full_name;
                if (data.identity_number) document.querySelector('input[name="id_number"]').value = data.identity_number;
                if (data.birth_date) {
                    if (data.birth_date.length === 10) {
                        document.querySelector('input[name="birthday"]').value = data.birth_date;
                    } else {
                        const parts = data.birth_date.split('-');
                        if (parts.length === 3) {
                            document.querySelector('input[name="birthday"]').value = `${parts[0]}-${parts[1].padStart(2,'0')}-${parts[2].padStart(2,'0')}`;
                        }
                    }
                }
                if (data.nationality) document.querySelector('input[name="nationality"]').value = data.nationality;
                if (data.residence) document.querySelector('input[name="residence"]').value = data.residence;
                if (data.hometown) document.querySelector('input[name="hometown"]').value = data.hometown;

                // BỔ SUNG CÁC TRƯỜNG CÒN LẠI
                if (data.gender) {
                    const radios = document.querySelectorAll('input[name="gender"]');
                    radios.forEach(radio => {
                        if (radio.value === data.gender.toLowerCase()) radio.checked = true;
                    });
                }
                if (data.identity_card_date) {
                    const parts = data.identity_card_date.split('-');
                    if (parts.length === 3) {
                        document.querySelector('input[name="identity_card_date"]').value = `${parts[0]}-${parts[1].padStart(2,'0')}-${parts[2].padStart(2,'0')}`;
                    }
                }
                if (data.identity_card_place) document.querySelector('input[name="identity_card_place"]').value = data.identity_card_place;
                if (data.dac_diem_nhan_dang) document.querySelector('input[name="dac_diem_nhan_dang"]').value = data.dac_diem_nhan_dang;

                // Nếu không có final_data thì fallback sang mat_truoc
                if (!data.full_name && data.mat_truoc) {
                    if (data.mat_truoc.ho_ten) document.querySelector('input[name="full_name"]').value = data.mat_truoc.ho_ten;
                }
                if (!data.identity_number && data.mat_truoc) {
                    if (data.mat_truoc.so_CCCD) document.querySelector('input[name="id_number"]').value = data.mat_truoc.so_CCCD;
                }
                if (!data.birth_date && data.mat_truoc && data.mat_truoc.ngay_sinh) {
                    const parts = data.mat_truoc.ngay_sinh.split('/');
                    if (parts.length === 3) {
                        document.querySelector('input[name="birthday"]').value = `${parts[2]}-${parts[1].padStart(2,'0')}-${parts[0].padStart(2,'0')}`;
                    }
                }
                if (!data.nationality && data.mat_truoc && data.mat_truoc.quoc_tich) document.querySelector('input[name="nationality"]').value = data.mat_truoc.quoc_tich;
                if (!data.residence && data.mat_truoc && data.mat_truoc.noi_thuong_tru) document.querySelector('input[name="residence"]').value = data.mat_truoc.noi_thuong_tru;
                if (!data.hometown && data.mat_truoc && data.mat_truoc.que_quan) document.querySelector('input[name="hometown"]').value = data.mat_truoc.que_quan;
                document.getElementById('scan-cccd-success').textContent = 'Quét CCCD thành công! Dữ liệu đã được điền vào form.';
                document.getElementById('scan-cccd-success').style.display = 'block';
                document.getElementById('identity-fields').style.display = 'block';
            })
            .catch(err => {
                loadingDiv.style.display = 'none';
                scanBtn.disabled = false;
                errorDiv.textContent = 'Không thể quét CCCD. Vui lòng thử lại.';
            });
        });
    </script>
    <style>
    .loader {
      border: 4px solid #f3f3f3;
      border-top: 4px solid #3498db;
      border-radius: 50%;
      width: 32px;
      height: 32px;
      animation: spin 1s linear infinite;
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    </style>
@endsection