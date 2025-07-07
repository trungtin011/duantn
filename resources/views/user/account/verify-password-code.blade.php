@extends('user.account.layout')

@section('account-content')
    <div class="bg-white p-6 rounded shadow max-w-md mx-auto">
        <h2 class="text-xl font-semibold mb-4">Xác nhận đổi mật khẩu</h2>

        @if (session('success'))
            <div class="text-green-600 mb-4">{{ session('success') }}</div>
        @endif

      <form method="POST" action="{{ route('account.password.code.verify') }}">
    @csrf

    <div class="flex gap-2 justify-center mb-4">
        @for ($i = 0; $i < 6; $i++)
            <input type="text" name="code[]" maxlength="1"
                class="w-12 h-12 text-center text-lg border rounded focus:ring-2 focus:ring-black"
                inputmode="numeric" required>
        @endfor
    </div>

    <input type="hidden" name="code" id="full_code">

    <button type="submit"
        onclick="mergeCode()"
        class="bg-black text-white px-4 py-2 rounded w-full">
        Xác nhận
    </button>
</form>

<script>
    function mergeCode() {
        const inputs = document.querySelectorAll('input[name="code[]"]');
        const code = Array.from(inputs).map(input => input.value).join('');
        document.getElementById('full_code').value = code;
    }
</script>
    </div>

    <script>
        // Tự động focus chuyển ô
        const inputs = document.querySelectorAll('input[name="code[]"]');
        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
        });

        // Gộp 6 số lại thành 1 chuỗi trước khi gửi
       function mergeCode() {
        const inputs = document.querySelectorAll('input[name="code[]"]');
        const code = Array.from(inputs).map(input => input.value).join('');
        document.getElementById('full_code').value = code;
    }
    </script>
@endsection
