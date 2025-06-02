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

                <form class="space-y-6">
                    <!-- Loại hình kinh doanh -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <label class="w-full sm:w-1/3 text-right"><sup class="text-red-500 text-[12px]">*</sup>Loại
                            hình kinh doanh</label>
                        <div class="flex gap-6">
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="business_type" value="personal" checked class="form-radio">
                                <span>Cá nhân</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="business_type" value="household" class="form-radio">
                                <span>Hộ kinh doanh</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="business_type" value="company" class="form-radio">
                                <span>Công ty</span>
                            </label>
                        </div>
                    </div>

                    <!-- Địa chỉ đăng ký kinh doanh -->
                    <div class="flex flex-col sm:flex-row gap-4 mt-6">
                        <label class="w-full sm:w-1/3 text-right font-medium pt-2"><sup
                                class="text-red-500 text-[12px]">*</sup>Địa chỉ đăng ký kinh doanh</label>
                        <div class="relative w-full sm:w-1/3">
                            <!-- Nút kích hoạt dropdown -->
                            <button id="toggle-address-dropdown" type="button"
                                class="w-full flex justify-between items-center border px-3 py-2 rounded bg-white shadow-sm text-sm">
                                <span id="address-dropdown-label">Chọn địa chỉ</span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Nội dung dropdown -->
                            <div id="address-dropdown-panel"
                                class="absolute z-10 mt-1 bg-white border rounded shadow-md w-full p-3 space-y-2 hidden">

                                <!-- Dropdown chọn tỉnh / huyện / xã -->
                                <div class="flex gap-2">
                                    <select id="province" class="w-1/3 border rounded px-3 py-2 text-sm">
                                        <option selected disabled>Tỉnh / Thành phố</option>
                                    </select>
                                    <select id="district" class="w-1/3 border rounded px-3 py-2 text-sm" disabled>
                                        <option selected disabled>Quận / Huyện</option>
                                    </select>
                                    <select id="ward" class="w-1/3 border rounded px-3 py-2 text-sm" disabled>
                                        <option selected disabled>Phường / Xã</option>
                                    </select>
                                </div>

                                <!-- Địa chỉ cụ thể -->
                                <textarea id="address-detail" rows="2"
                                    class="w-full border rounded px-3 py-2 text-sm placeholder-gray-400 focus:outline-none focus:ring focus:ring-red-400"
                                    placeholder="Số nhà, tên đường..v.v."></textarea>

                                <!-- Cảnh báo lỗi -->
                                <p id="address-error" class="text-xs text-red-500 hidden">
                                    Vui lòng điền địa chỉ cụ thể. Ví dụ: “Số nhà/đường, Quận/Huyện,...”
                                </p>

                                <!-- Kết quả địa chỉ tổng hợp -->
                                <input id="selected-address" class="w-full bg-gray-100 border px-3 py-2 rounded text-sm"
                                    readonly placeholder="Địa chỉ đầy đủ sẽ hiển thị ở đây">
                            </div>
                        </div>

                    </div>


                    <!-- Email -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <label class="w-full sm:w-1/3 text-right"><sup class="text-red-500 text-[12px]">*</sup>Email
                            nhận hóa đơn điện tử</label>
                        <div class="w-full sm:w-1/3">
                            <input type="email" name="invoice_email" class="w-full border rounded px-3 py-2"
                                placeholder="">
                            <p class="text-sm text-gray-500 mt-1">Hóa đơn điện tử của bạn sẽ được gửi đến địa chỉ email này
                            </p>
                        </div>
                    </div>

                    <!-- Mã số thuế -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <label class="w-full sm:w-1/3 text-right"><sup class="text-red-500 text-[12px]">*</sup>Mã số
                            thuế</label>
                        <div class="w-full sm:w-1/3">
                            <input type="text" name="tax_code" class="w-full border rounded px-3 py-2" placeholder="">
                            <p class="text-sm text-gray-500 mt-1">
                                Mã số thuế là mã số thuế kinh doanh. <a href="#" class="text-blue-600 underline">Tìm
                                    hiểu thêm.</a>
                            </p>
                        </div>
                    </div>

                    <!-- hr -->
                    <hr class="my-10">
                    <!-- Nút -->
                    <div class="flex justify-between">
                        <div class="">
                            <button type="button" class="px-4 py-2 bg-white border rounded hover:bg-gray-100">Quay
                                lại</button>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="submit" class="px-4 py-2 bg-white border rounded hover:bg-gray-100">Lưu</button>
                            <button type="button" class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700">Tiếp
                                theo</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
