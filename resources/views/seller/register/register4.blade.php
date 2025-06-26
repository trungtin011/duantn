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
                if (typeof updateStepper === 'function') {
                    updateStepper(4);
                } else {
                    console.error('updateStepper function is not defined');
                }
            </script>
            <!-- Success Card -->
            <div class="bg-white rounded-2xl p-6">
                <div class="flex flex-col items-center justify-center">
                    <div class="mb-4">
                        <span style="display:inline-block; background:#e6f9ed; border-radius:50%; padding:24px;">
                            <i class="fa fa-check" style="font-size:3rem; color:#22c55e;"></i>
                        </span>
                    </div>
                    <h4 class="fw-bold mb-2">Đăng ký thành công</h4>
                    <div class="mb-10" style="color:#4a5568; font-size:1rem;">Cảm ơn bạn đã đăng ký! Quá trình xử lý sẽ hoàn tất trong vòng 3–4 ngày.</div>
                </div>
            </div>
        </div>
    </div>
@endsection
