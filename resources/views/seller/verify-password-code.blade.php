@extends('layouts.seller_home')
@section('title', 'Xác nhận đổi mật khẩu')
@section('content')
    <div class="admin-page-header mb-5">
        <h1 class="admin-page-title text-2xl">Đổi mật khẩu</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Cập nhật thông tin cửa</a> / Đổi
            mật khẩu
        </div>
    </div>
    @include('seller.partials.account_submenu')
    <div class="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800">Xác nhận đổi mật khẩu</h2>
            <p class="text-sm text-gray-600">Nhập mã xác nhận 6 số đã được gửi tới email của bạn.</p>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('seller.password.verify.code') }}" id="verifyForm" class="space-y-6">
            @csrf

            <div class="space-y-4">
                <div class="text-center">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Nhập mã xác nhận 6 số:</label>
                    <div class="flex gap-2 justify-center mb-4">
                        @for ($i = 0; $i < 6; $i++)
                            <input type="text" name="code[]" maxlength="1"
                                class="w-12 h-12 text-center border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#f42f46] focus:border-transparent text-lg"
                                inputmode="numeric" required>
                        @endfor
                    </div>
                </div>

                <input type="hidden" name="code" id="full_code">

                <div class="text-center">
                    <button type="submit" class="bg-[#f42f46] text-white text-sm font-semibold px-6 py-2 rounded hover:bg-[#d91f35] focus:outline-none focus:ring-2 focus:ring-[#f42f46] focus:ring-opacity-50">
                        <i class="fas fa-check mr-2"></i> Xác nhận
                    </button>
                </div>
            </div>
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
                        const pastedData = (e.clipboardData || window.clipboardData).getData(
                        'text');
                        const numbers = pastedData.replace(/\D/g, '').slice(0,
                        6); // Chỉ lấy số, tối đa 6 chữ số

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
                            const nextFocusIndex = Math.min(index + numbersToFill.length, inputs
                                .length - 1);
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
