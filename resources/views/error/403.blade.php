@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 min-h-screen">
        <div class="flex flex-col items-center py-20 max-w-4xl mx-auto md:py-10">
            <!-- Nội dung lỗi -->
            <div class="flex flex-col items-center mt-36 w-full md:mt-10">
                <div class="text-8xl font-medium tracking-widest text-center text-black md:text-4xl">
                    403 Forbidden
                </div>
                <div class="mt-10 text-base text-center text-black">
                    Bạn không có quyền truy cập trang này. Vui lòng quay lại trang chủ.
                </div>
            </div>

            <!-- Nút quay lại -->
            <button onclick="window.location.href='{{ route('home') }}'"
                class="flex items-center justify-center px-12 py-4 mt-20 text-base font-medium text-white bg-black rounded-md md:px-5 md:mt-10 hover:bg-gray-800">
                Quay lại trang chủ
            </button>
        </div>
    </div>
@endsection
