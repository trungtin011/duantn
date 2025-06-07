@extends('layouts.seller')

@section('content')
    <div class="container mx-auto py-5 flex flex-col" style="min-height: 80vh;">
        <!-- Breadcrumb -->
        <div class="flex flex-wrap items-center gap-2 my-10 md:my-10 text-sm md:text-base">
            <a href="{{ route('home') }}" class="text-gray-500 hover:underline">Trang chủ</a>
            <span>/</span>
            <span>Đăng ký trở thành người bán</span>
        </div>
        <div class="p-6 w-full shadow-[0_0_10px_0_rgba(0,0,0,0.1)] rounded-[10px]">
            <!-- Stepper -->
            @include('seller.register.stepper')
            <script>
                updateStepper(2);
            </script>
            <!-- Card Form -->
            <div class="bg-white rounded-2xl p-6">
                <div
                    class="border border-blue-300 bg-blue-100 text-[#666666] text-sm p-4 rounded flex items-start space-x-2 mb-5">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8,1 C11.8659932,1 15,4.13400675 15,8 C15,11.8659932 11.8659932,15 8,15 C4.13400675,15 1,11.8659932 1,8 C1,4.13400675 4.13400675,1 8,1 Z M8.30163718,10.5595183 C8.14108673,10.7430046 8.00347205,10.8347477 7.91172893,10.8347477 C7.86585737,10.8347477 7.84292159,10.8347477 7.79705003,10.8118119 C7.75117847,10.7888761 7.75117847,10.7430046 7.75117847,10.6741972 C7.75117847,10.6053899 7.77411425,10.4219037 7.84292159,10.1696101 C7.86585737,10.077867 7.91172893,9.89438073 7.98053627,9.64208716 L8.80622434,6.6375 L8.34750874,6.72924312 C8.18695829,6.7521789 7.91172893,6.79805046 7.54475645,6.84392202 C7.15484819,6.88979358 6.87961884,6.91272936 6.67319682,6.93566514 L6.67319682,7.2108945 C6.90255462,7.2108945 7.06310507,7.23383028 7.15484819,7.27970183 C7.24659131,7.32557339 7.29246287,7.39438073 7.29246287,7.53199541 L7.29246287,7.60080275 C7.29246287,7.62373853 7.29246287,7.64667431 7.26952709,7.69254587 L6.62732526,10.077867 C6.5814537,10.2613532 6.53558214,10.3989679 6.51264636,10.490711 C6.4667748,10.6741972 6.44383902,10.8118119 6.44383902,10.903555 C6.44383902,11.1558486 6.51264636,11.3393349 6.67319682,11.4540138 C6.83374728,11.5686927 6.99429774,11.6375 7.20071975,11.6375 C7.54475645,11.6375 7.86585737,11.4998853 8.16402251,11.2017202 C8.34750874,11.0182339 8.59980232,10.6741972 8.94383902,10.1696101 L8.71448122,10.0090596 C8.59980232,10.1925459 8.43925186,10.3760321 8.30163718,10.5595183 L8.30163718,10.5595183 Z M8.19383902,4.3625 C7.97609708,4.3625 7.80674225,4.43508065 7.66158095,4.58024194 C7.51641966,4.72540323 7.44383902,4.89475806 7.44383902,5.1125 C7.44383902,5.33024194 7.51641966,5.49959677 7.66158095,5.64475806 C7.80674225,5.78991935 7.97609708,5.8625 8.19383902,5.8625 C8.41158095,5.8625 8.58093579,5.78991935 8.72609708,5.64475806 C8.87125837,5.49959677 8.94383902,5.33024194 8.94383902,5.1125 C8.94383902,4.89475806 8.87125837,4.72540323 8.72609708,4.58024194 C8.58093579,4.43508065 8.41158095,4.3625 8.19383902,4.3625 L8.19383902,4.3625 Z">
                        </path>
                    </svg>
                    <p>
                        Việc thu thập Thông Tin Thuế và Thông Tin Định Danh là bắt buộc
                        theo quy định của Luật an ninh mạng, Thương mại điện tử và Thuế của Việt Nam. Thông Tin Thuế và
                        Thông Tin Định Danh sẽ được bảo vệ theo chính sách bảo mật của Shopee. Thông tin Người bán cung cấp
                        sẽ được sử dụng cho mục đích khấu trừ thuế (nếu có) và xuất hóa đơn do Người bán hoàn toàn chịu
                        trách nhiệm về tính chính xác của các thông tin đã cung cấp.
                    </p>
                </div>

                <form method="POST" action="{{ route('seller.register.step3') }}" class="space-y-6">
                    @csrf
                    <!-- Loại hình kinh doanh -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <label class="w-full sm:w-1/3 text-right"><sup class="text-red-500 text-[12px]">*</sup>Loại
                            hình kinh doanh</label>
                        <div class="flex gap-6">
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="business_type" value="personal" class="form-radio"
                                    {{ old('business_type', session('register.business_type')) == 'personal' ? 'checked' : '' }}>
                                <span>Cá nhân</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="business_type" value="household" class="form-radio"
                                    {{ old('business_type', session('register.business_type')) == 'household' ? 'checked' : '' }}>
                                <span>Hộ kinh doanh</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="business_type" value="company" class="form-radio"
                                    {{ old('business_type', session('register.business_type')) == 'company' ? 'checked' : '' }}>
                                <span>Công ty</span>
                            </label>
                        </div>
                    </div>

                    <!-- Địa chỉ đăng ký kinh doanh -->
                    <div class="flex flex-col sm:flex-row gap-4 mt-6">
                        <label class="w-full sm:w-1/3 text-right font-medium pt-2"><sup
                                class="text-red-500 text-[12px]">*</sup>Địa chỉ đăng ký kinh doanh</label>
                        <div class="w-full sm:w-1/3 space-y-2">
                            <div class="flex gap-2">
                                <select name="business_province" id="business_province" class="w-1/3 border rounded px-3 py-2 text-sm">
                                    <option value="" disabled {{ !old('business_province', session('register.business_province')) ? 'selected' : '' }}>Tỉnh / Thành phố</option>
                                    <!-- Option tỉnh/thành sẽ được render bằng JS -->
                                </select>
                                <select name="business_district" id="business_district" class="w-1/3 border rounded px-3 py-2 text-sm">
                                    <option value="" disabled {{ !old('business_district', session('register.business_district')) ? 'selected' : '' }}>Quận / Huyện</option>
                                    <!-- Option quận/huyện sẽ được render bằng JS -->
                                </select>
                                <select name="business_ward" id="business_ward" class="w-1/3 border rounded px-3 py-2 text-sm">
                                    <option value="" disabled {{ !old('business_ward', session('register.business_ward')) ? 'selected' : '' }}>Phường / Xã</option>
                                    <!-- Option phường/xã sẽ được render bằng JS -->
                                </select>
                            </div>
                            <input type="text" name="business_address_detail"
                                class="w-full border rounded px-3 py-2 text-sm mt-2"
                                placeholder="Số nhà, tên đường..."
                                value="{{ old('business_address_detail', session('register.business_address_detail')) }}">
                        </div>
                    </div>


                    <!-- Email -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <label class="w-full sm:w-1/3 text-right"><sup class="text-red-500 text-[12px]">*</sup>Email
                            nhận hóa đơn điện tử</label>
                        <div class="w-full sm:w-1/3">
                            <input type="email" name="invoice_email" class="w-full border rounded px-3 py-2"
                                value="{{ old('invoice_email', session('register.invoice_email')) }}">
                            <p class="text-sm text-gray-500 mt-1">Hóa đơn điện tử của bạn sẽ được gửi đến địa chỉ email này
                            </p>
                        </div>
                    </div>

                    <!-- Mã số thuế -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <label class="w-full sm:w-1/3 text-right"><sup class="text-red-500 text-[12px]">*</sup>Mã số
                            thuế</label>
                        <div class="w-full sm:w-1/3">
                            <input type="text" name="tax_code" class="w-full border rounded px-3 py-2"
                                value="{{ old('tax_code', session('register.tax_code')) }}">
                            <p class="text-sm text-gray-500 mt-1">
                                Mã số thuế là mã số thuế kinh doanh. <a href="#" class="text-blue-600 underline">Tìm
                                    hiểu thêm.</a>
                            </p>
                        </div>
                    </div>

                    <!-- Hiển thị lỗi validate -->
                    @if ($errors->any())
                        <div class="mb-4 text-red-600">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- hr -->
                    <hr class="my-10">
                    <!-- Nút -->
                    <div class="flex justify-between">
                        <div class="">
                            <a href="{{ route('seller.register.step2') }}"
                                class="px-4 py-2 bg-white border rounded hover:bg-gray-100">Quay lại</a>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="submit"
                                class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700">Tiếp
                                theo</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const provinceSelect = document.getElementById('business_province');
        const districtSelect = document.getElementById('business_district');
        const wardSelect = document.getElementById('business_ward');

        // Fetch provinces
        fetch('https://provinces.open-api.vn/api/?depth=1')
            .then(res => res.json())
            .then(data => {
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.code; // dùng code
                    option.textContent = item.name;
                    if (option.value == "{{ old('business_province', session('register.business_province')) }}") option.selected = true;
                    provinceSelect.appendChild(option);
                });
            });

        provinceSelect.addEventListener('change', function () {
            districtSelect.innerHTML = '<option value="" disabled selected>Quận / Huyện</option>';
            wardSelect.innerHTML = '<option value="" disabled selected>Phường / Xã</option>';
            wardSelect.disabled = true;
            districtSelect.disabled = false;
            const provinceCode = this.value;
            fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
                .then(res => res.json())
                .then(data => {
                    data.districts.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.code; // dùng code
                        option.textContent = item.name;
                        if (option.value == "{{ old('business_district', session('register.business_district')) }}") option.selected = true;
                        districtSelect.appendChild(option);
                    });
                });
        });

        districtSelect.addEventListener('change', function () {
            wardSelect.innerHTML = '<option value="" disabled selected>Phường / Xã</option>';
            wardSelect.disabled = false;
            const districtCode = this.value;
            fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
                .then(res => res.json())
                .then(data => {
                    data.wards.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.code; // dùng code
                        option.textContent = item.name;
                        if (option.value == "{{ old('business_ward', session('register.business_ward')) }}") option.selected = true;
                        wardSelect.appendChild(option);
                    });
                });
        });
    });
</script>
@endpush