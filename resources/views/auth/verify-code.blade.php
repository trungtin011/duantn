@extends('layouts.app')

@section('title', 'Xác nhận mã')

@section('content')
    <div class="flex items-center justify-center px-4 pt-8">
        <div class="flex flex-col md:flex-row w-full max-w-6xl shadow-xl rounded-xl overflow-hidden">
            <img class="w-full md:w-1/2 bg-cover bg-center min-h-[200px] md:min-h-[500px]"
                src="{{ asset('images/verifypw_image.avif') }}" alt="">

            <div class="w-full md:w-1/2 bg-white p-4 md:p-12">
                <h2 class="text-2xl md:text-3xl font-bold mb-3">Xác nhận mã</h2>
                <p class="text-gray-600 mb-6 text-sm md:text-base">Nhập 6 chữ số đã được gửi về email của bạn.</p>

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.code.verify') }}">
                    @csrf

                    <div class="mb-4 text-center">
                        <label class="block text-gray-600 mb-2">Mã xác nhận (6 chữ số)</label>
                        <div class="flex justify-between gap-2 md:gap-3">
                            @for ($i = 0; $i < 6; $i++)
                                <input type="text" name="code_digits[]" maxlength="1"
                                    class="w-10 h-12 md:w-12 md:h-14 text-center text-lg border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#f42f46] focus:border-transparent" 
                                    inputmode="numeric" required>
                            @endfor
                        </div>
                        @error('code')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <input type="hidden" name="code" id="full-code">

                    <button type="submit"
                        class="w-full bg-[#f42f46] hover:bg-[#d91f35] text-white py-2 text-sm md:text-base rounded focus:outline-none focus:ring-2 focus:ring-[#f42f46] focus:ring-opacity-50">
                        <i class="fas fa-check mr-2"></i> Xác nhận mã
                    </button>

                    <div class="flex mt-4 text-xs flex-wrap gap-2">
                        <a href="{{ route('password.email.form') }}" class="text-gray-600 hover:underline">Gửi lại mã</a>
                        <span class="text-gray-400">•</span>
                        <a href="{{ route('login') }}" class="text-gray-600 hover:underline">Quay lại đăng nhập</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const inputs = Array.from(document.querySelectorAll('input[name="code_digits[]"]'));
            const hidden = document.getElementById('full-code');

            function updateHidden() {
                hidden.value = inputs.map(i => i.value).join('');
            }
            inputs.forEach((input, idx) => {
                input.addEventListener('input', function() {
                    if (this.value.length === 1 && idx < inputs.length - 1) {
                        inputs[idx + 1].focus();
                    }
                    updateHidden();
                });
            });
        })();
    </script>
@endsection
