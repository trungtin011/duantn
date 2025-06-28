@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-10 px-4">
    <div class="bg-white shadow-md rounded-lg w-full max-w-md p-6">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Xác nhận mã</h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.code.verify') }}">
            @csrf

            <div class="mb-4 text-center">
                <label class="block text-gray-600 mb-2">Nhập mã xác nhận (6 chữ số)</label>
                <div class="flex justify-between gap-2">
                    @for ($i = 0; $i < 6; $i++)
                        <input type="text" name="code_digits[]" maxlength="1"
                            class="w-10 h-12 text-center text-lg border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            oninput="moveToNext(this, {{ $i }})" required>
                    @endfor
                </div>
                @error('code')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <input type="hidden" name="code" id="full-code">

            <button type="submit"
                class="w-full bg-[#ef4444] hover:bg-[#dc2626] text-white py-2 rounded font-medium transition">
                Xác nhận mã
            </button>
        </form>
    </div>
</div>

<script>
    function moveToNext(el, index) {
        const inputs = document.querySelectorAll('input[name="code_digits[]"]');
        if (el.value.length === 1 && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }

        // Gộp mã 6 số thành chuỗi hoàn chỉnh
        let fullCode = '';
        inputs.forEach(input => {
            fullCode += input.value;
        });
        document.getElementById('full-code').value = fullCode;
    }
</script>
@endsection
