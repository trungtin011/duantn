@extends('user.account.layout')

@section('account-content')
    <div class="bg-white p-6 rounded shadow max-w-md mx-auto">
        <h2 class="text-xl font-semibold mb-4">Xác nhận đổi mật khẩu</h2>

        @if (session('success'))
            <div class="text-green-600 mb-4">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="text-red-600 mb-4">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('account.password.code.verify') }}" id="verifyForm">
            @csrf

            <div class="flex gap-2 justify-center mb-4">
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" name="code[]" maxlength="1"
                        class="w-12 h-12 text-center text-lg border rounded focus:ring-2 focus:ring-black"
                        inputmode="numeric" required>
                @endfor
            </div>

            <input type="hidden" name="code" id="full_code">

            <button type="submit" class="bg-black text-white px-4 py-2 rounded w-full">
                Xác nhận
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input[name="code[]"]');
            const form = document.getElementById('verifyForm');
            const fullCodeInput = document.getElementById('full_code');
            
            // Tự động focus chuyển ô
            inputs.forEach((input, index) => {
                if (input) {
                    input.addEventListener('input', () => {
                        if (input.value.length === 1 && index < inputs.length - 1) {
                            const nextInput = inputs[index + 1];
                            if (nextInput) {
                                nextInput.focus();
                            }
                        }
                    });
                    
                    // Xử lý backspace
                    input.addEventListener('keydown', (e) => {
                        if (e.key === 'Backspace' && input.value === '' && index > 0) {
                            const prevInput = inputs[index - 1];
                            if (prevInput) {
                                prevInput.focus();
                            }
                        }
                    });

                    // Xử lý paste
                    input.addEventListener('paste', (e) => {
                        e.preventDefault();
                        const pastedData = (e.clipboardData || window.clipboardData).getData('text');
                        const numbers = pastedData.replace(/\D/g, '').slice(0, 6); // Chỉ lấy số, tối đa 6 chữ số
                        
                        if (numbers.length === 6) {
                            // Phân tách và điền vào các ô
                            inputs.forEach((inputField, i) => {
                                inputField.value = numbers[i] || '';
                            });
                            
                            // Focus vào ô cuối cùng
                            inputs[inputs.length - 1].focus();
                        } else if (numbers.length > 0) {
                            // Nếu ít hơn 6 số, điền từ ô hiện tại
                            const remainingSlots = inputs.length - index;
                            const numbersToFill = numbers.slice(0, remainingSlots);
                            
                            numbersToFill.forEach((num, i) => {
                                if (index + i < inputs.length) {
                                    inputs[index + i].value = num;
                                }
                            });
                            
                            // Focus vào ô tiếp theo sau khi điền
                            const nextFocusIndex = Math.min(index + numbersToFill.length, inputs.length - 1);
                            inputs[nextFocusIndex].focus();
                        }
                    });
                }
            });
            
            // Gộp 6 số lại thành 1 chuỗi trước khi gửi
            if (form && fullCodeInput) {
                form.addEventListener('submit', function(e) {
                    const code = Array.from(inputs).map(input => input ? input.value : '').join('');
                    fullCodeInput.value = code;
                });
            }
        });
    </script>
@endsection
