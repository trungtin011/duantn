@extends('layouts.seller')

@section('content')
    <div class="container mx-auto py-5 flex flex-col" style="min-height: 100vh;">
        <!-- Breadcrumb -->
        <div class="flex flex-wrap items-center gap-2 my-10 text-sm md:text-base">
            <a href="{{ route('home') }}" class="text-gray-500 hover:underline">Trang chủ</a>
            <span>/</span>
            <span>Đăng ký trở thành người bán</span>
        </div>

        <div class="p-6 w-full shadow-[0_0_10px_0_rgba(0,0,0,0.1)] rounded-[10px]">
            <!-- Stepper -->
            @include('seller.register.stepper')
            <script>
                updateStepper(0);
            </script>

            <!-- Form -->
            <div class="bg-white rounded-2xl p-6">
                <form method="POST" action="{{ route('seller.register.step1') }}" enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                        <div class="mb-4 text-red-600">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="w-full max-w-2xl mx-auto">
                        <div class="flex flex-col md:flex-row md:items-center mb-5">
                            <label class="md:w-48 w-full md:text-right md:pr-4 font-medium"><sup class="text-red-500">*</sup> Tên Shop:</label>
                            <div class="flex-1 w-full">
                                <input type="text" name="shop_name" value="{{ old('shop_name') }}"
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 mt-2 md:mt-0"
                                    placeholder="Nhập tên shop" maxlength="100" required>
                                @error('shop_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center mb-5">
                            <label class="md:w-48 w-full md:text-right md:pr-4 font-medium"><sup class="text-red-500">*</sup> Địa chỉ lấy hàng:</label>
                            <div class="flex-1 w-full">
                                <input type="text" name="address" value="{{ old('address') }}"
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 mt-2 md:mt-0"
                                    placeholder="Nhập địa chỉ lấy hàng" maxlength="255" required>
                                @error('address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center mb-5">
                            <label class="md:w-48 w-full md:text-right md:pr-4 font-medium"><sup class="text-red-500">*</sup> Email:</label>
                            <div class="flex-1 w-full">
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 mt-2 md:mt-0"
                                    placeholder="Nhập email shop" maxlength="100" required>
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center mb-5">
                            <label class="md:w-48 w-full md:text-right md:pr-4 font-medium"><sup class="text-red-500">*</sup> Số điện thoại:</label>
                            <div class="flex-1 w-full">
                                <input type="text" name="phone" value="{{ old('phone') }}"
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 mt-2 md:mt-0"
                                    placeholder="Nhập số điện thoại shop" maxlength="11" required>
                                @error('phone')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center mb-5">
                            <label class="md:w-48 w-full md:text-right md:pr-4 font-medium">Mô tả shop:</label>
                            <div class="flex-1 w-full">
                                <textarea name="shop_description" rows="3"
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 mt-2 md:mt-0"
                                    placeholder="Mô tả shop (bắt buộc)" required maxlength="65535">{{ old('shop_description') }}</textarea>
                                @error('shop_description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center mb-5">
                            <label class="md:w-48 w-full md:text-right md:pr-4 font-medium">Logo shop:</label>
                            <div class="flex-1 w-full mt-2 md:mt-0">
                                <input type="file" name="shop_logo" accept="image/*"
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required id="shop_logo_input">
                                <span class="text-xs text-gray-500">Chọn ảnh logo shop (bắt buộc, jpg/png/jpeg, tối đa 2MB)</span>
                                <div id="shop_logo_list" class="flex flex-wrap gap-2 mt-2">
                                    @if(old('shop_logo_url'))
                                        <img src="{{ old('shop_logo_url') }}" alt="Logo preview" style="max-width:120px;max-height:120px;border-radius:8px;box-shadow:0 0 4px #ccc;">
                                    @elseif(session('shop_logo_url'))
                                        <img src="{{ session('shop_logo_url') }}" alt="Logo preview" style="max-width:120px;max-height:120px;border-radius:8px;box-shadow:0 0 4px #ccc;">
                                    @endif
                                </div>
                                @error('shop_logo')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center mb-5">
                            <label class="md:w-48 w-full md:text-right md:pr-4 font-medium">Banner shop:</label>
                            <div class="flex-1 w-full mt-2 md:mt-0">
                                <input type="file" name="shop_banner" accept="image/*"
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required id="shop_banner_input">
                                <span class="text-xs text-gray-500">Chọn ảnh banner shop (bắt buộc, jpg/png/jpeg, tối đa 4MB)</span>
                                <div id="shop_banner_list" class="flex flex-wrap gap-2 mt-2">
                                    @if(old('shop_banner_url'))
                                        <img src="{{ old('shop_banner_url') }}" alt="Banner preview" style="max-width:200px;max-height:120px;border-radius:8px;box-shadow:0 0 4px #ccc;">
                                    @elseif(session('shop_banner_url'))
                                        <img src="{{ session('shop_banner_url') }}" alt="Banner preview" style="max-width:200px;max-height:120px;border-radius:8px;box-shadow:0 0 4px #ccc;">
                                    @endif
                                </div>
                                @error('shop_banner')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Hr -->
                    <hr class="my-10">

                    <!-- Buttons -->
                    <div class="flex justify-between">
                        <a href="{{ route('home') }}"
                            class="px-4 py-2 bg-white border rounded hover:bg-gray-100">Quay lại</a>
                        <button type="submit"
                            class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700">Tiếp theo</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal -->
        @include('seller.register.modal')
    </div>
@endsection

@section('scripts')
<script>
function renderImageList(inputId, listId, maxSizeMB) {
    document.getElementById(inputId).addEventListener('change', function(e) {
        const file = e.target.files[0];
        const list = document.getElementById(listId);
        list.innerHTML = '';
        if (file) {
            const maxSize = maxSizeMB * 1024 * 1024;
            if (file.size > maxSize) {
                alert('Kích thước ảnh vượt quá ' + maxSizeMB + 'MB.');
                e.target.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(ev) {
                const img = document.createElement('img');
                img.src = ev.target.result;
                img.style.maxWidth = inputId === 'shop_logo_input' ? '120px' : '200px';
                img.style.maxHeight = '120px';
                img.style.borderRadius = '8px';
                img.style.boxShadow = '0 0 4px #ccc';
                img.alt = 'Preview';
                list.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });
}
renderImageList('shop_logo_input', 'shop_logo_list', 2);
renderImageList('shop_banner_input', 'shop_banner_list', 4);
</script>
@endsection