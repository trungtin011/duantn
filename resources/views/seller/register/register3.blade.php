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
                updateStepper(3);
            </script>
            <form action="" class="rounded-2xl p-6">
                <div class="min-h-screen bg-white flex items-center justify-center">
                    <div class="rounded-2xl p-6 flex flex-col gap-10">
                        <!-- Tiêu đề và thông báo -->
                        <div class="flex items-center bg-blue-100 text-blue-700 border border-blue-300 p-3 rounded text-sm">
                            <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8,1 C11.8659932,1 15,4.13400675 15,8 C15,11.8659932 11.8659932,15 8,15 C4.13400675,15 1,11.8659932 1,8 C1,4.13400675 4.13400675,1 8,1 Z M8.30163718,10.5595183 C8.14108673,10.7430046 8.00347205,10.8347477 7.91172893,10.8347477 C7.86585737,10.8347477 7.84292159,10.8347477 7.79705003,10.8118119 C7.75117847,10.7888761 7.75117847,10.7430046 7.75117847,10.6741972 C7.75117847,10.6053899 7.77411425,10.4219037 7.84292159,10.1696101 C7.86585737,10.077867 7.91172893,9.89438073 7.98053627,9.64208716 L8.80622434,6.6375 L8.34750874,6.72924312 C8.18695829,6.7521789 7.91172893,6.79805046 7.54475645,6.84392202 C7.15484819,6.88979358 6.87961884,6.91272936 6.67319682,6.93566514 L6.67319682,7.2108945 C6.90255462,7.2108945 7.06310507,7.23383028 7.15484819,7.27970183 C7.24659131,7.32557339 7.29246287,7.39438073 7.29246287,7.53199541 L7.29246287,7.60080275 C7.29246287,7.62373853 7.29246287,7.64667431 7.26952709,7.69254587 L6.62732526,10.077867 C6.5814537,10.2613532 6.53558214,10.3989679 6.51264636,10.490711 C6.4667748,10.6741972 6.44383902,10.8118119 6.44383902,10.903555 C6.44383902,11.1558486 6.51264636,11.3393349 6.67319682,11.4540138 C6.83374728,11.5686927 6.99429774,11.6375 7.20071975,11.6375 C7.54475645,11.6375 7.86585737,11.4998853 8.16402251,11.2017202 C8.34750874,11.0182339 8.59980232,10.6741972 8.94383902,10.1696101 L8.71448122,10.0090596 C8.59980232,10.1925459 8.43925186,10.3760321 8.30163718,10.5595183 L8.30163718,10.5595183 Z M8.19383902,4.3625 C7.97609708,4.3625 7.80674225,4.43508065 7.66158095,4.58024194 C7.51641966,4.72540323 7.44383902,4.89475806 7.44383902,5.1125 C7.44383902,5.33024194 7.51641966,5.49959677 7.66158095,5.64475806 C7.80674225,5.78991935 7.97609708,5.8625 8.19383902,5.8625 C8.41158095,5.8625 8.58093579,5.78991935 8.72609708,5.64475806 C8.87125837,5.49959677 8.94383902,5.33024194 8.94383902,5.1125 C8.94383902,4.89475806 8.87125837,4.72540323 8.72609708,4.58024194 C8.58093579,4.43508065 8.41158095,4.3625 8.19383902,4.3625 L8.19383902,4.3625 Z">
                                </path>
                            </svg>
                            <p>
                                Vui lòng cung cấp Thông Tin Định Danh của Chủ Shop (nếu là cá nhân), hoặc Người Đại Diện
                                Pháp Lý
                                trên
                                giấy đăng ký kinh doanh.
                            </p>
                        </div>

                        <!-- Hình thức định danh -->
                        <div class="grid grid-cols-5 items-center gap-4">
                            <label class="col-span-1 font-medium text-sm text-gray-700 text-end">
                                <sup class="text-red-500 text-[12px]">*</sup> Hình Thức Định Danh
                            </label>
                            <div class="col-span-4 flex items-center gap-6 text-sm">
                                <label>
                                    <input type="radio" name="id_type" value="cccd" checked>
                                    Căn Cước Công Dân (CCCD)
                                </label>
                                <label>
                                    <input type="radio" name="id_type" value="cmnd">
                                    Chứng Minh Nhân Dân (CMND)
                                </label>
                                <label>
                                    <input type="radio" name="id_type" value="passport">
                                    Hộ chiếu
                                </label>
                            </div>
                        </div>


                        <!-- Số hộ chiếu -->
                        <div class="grid grid-cols-5 items-center gap-4">
                            <label class="col-span-1 text-sm font-medium text-gray-700 text-end">
                                <sup class="text-red-500 text-[12px]">*</sup> Số Hộ chiếu
                            </label>
                            <input type="text" maxlength="20" class="col-span-2 border rounded px-3 py-2 text-sm"
                                placeholder="Nhập vào">
                        </div>


                        <!-- Họ và tên -->
                        <div class="grid grid-cols-5 items-start gap-4">
                            <label class="col-span-1 text-sm font-medium text-gray-700 text-end">
                                <sup class="text-red-500 text-[12px]">*</sup> Họ & Tên
                            </label>
                            <div class="col-span-4 space-y-1">
                                <input type="text" maxlength="100" class="w-1/2 border rounded px-3 py-2 text-sm"
                                    placeholder="Nhập vào">
                                <p class="text-xs text-gray-500">Theo CMND/CCCD/Hộ Chiếu</p>
                            </div>
                        </div>


                        <!-- Ảnh CMND / CCCD / Hộ chiếu -->
                        <div class="grid grid-cols-5 items-start gap-4">
                            <label class="col-span-1 block text-sm font-medium text-gray-700 text-end">
                                <sup class="text-red-500 text-[12px]">*</sup> Ảnh chụp giấy tờ
                            </label>
                            <div
                                class="col-span-4 w-1/3 flex flex-col items-center justify-center border border-dashed border-gray-400 rounded p-6 text-center space-y-2">
                                <!-- SVG + hướng dẫn + nút tải ảnh -->
                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4">
                                    </path>
                                </svg>
                                <p class="text-sm text-gray-500">Vui lòng cung cấp ảnh chụp thẻ CMND/CCCD/hộ chiếu của
                                    bạn<br>
                                    Các thông tin trên CMND/CCCD/Hộ chiếu phải được hiển thị rõ ràng (Kích thước ảnh không
                                    vượt
                                    quá 5.0 MB)
                                </p>
                                <input type="file" class="hidden" id="upload-id-front">
                                <button onclick="document.getElementById('upload-id-front').click()"
                                    class="px-4 py-2 bg-blue-500 text-white rounded text-sm">Tải ảnh lên</button>
                            </div>
                        </div>

                        <div class="grid grid-cols-5 items-start gap-4">
                            <label class="col-span-1 block text-sm font-medium text-gray-700 text-end">
                                <sup class="text-red-500 text-[12px]">*</sup> Ảnh đang cầm giấy tờ
                            </label>
                            <div
                                class="col-span-4 w-1/3 flex flex-col items-center justify-center border border-dashed border-gray-400 rounded p-6 text-center space-y-2">
                                <!-- SVG + hướng dẫn + nút tải ảnh -->
                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4">
                                    </path>
                                </svg>
                                <p class="text-sm text-gray-500">Vui lòng cung cấp ảnh bạn đang cầm CMND/CCCD/hộ chiếu như
                                    ảnh
                                    mẫu.<br>
                                    Các thông tin trên CMND/CCCD/hộ chiếu và hình ảnh của bạn phải hiển thị rõ ràng (Kích
                                    thước
                                    ảnh không vượt quá 5.0 MB)
                                </p>
                                <input type="file" class="hidden" id="upload-id-selfie">
                                <button onclick="document.getElementById('upload-id-selfie').click()"
                                    class="px-4 py-2 bg-blue-500 text-white rounded text-sm">Tải ảnh lên</button>
                            </div>
                        </div>

                        <!-- Xác nhận -->
                        <div class="flex items-center space-x-2 text-sm bg-gray-100 p-3 rounded">
                            <input type="checkbox" id="confirm" class="">
                            <label for="confirm">Tôi xác nhận tất cả dữ liệu đã cung cấp là chính xác và trung thực. Tôi đã
                                đọc
                                và
                                đồng ý với
                                Chính Sách Bảo Mật.</label>
                        </div>
                    </div>
                </div>
                <!-- hr -->
                <hr class="my-5">
                <!-- Nút điều hướng -->
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
    @endsection
