@extends('layouts.admin')
@section('title', 'Xác nhận đổi mật khẩu')
@section('content')
<div class="pb-10 mx-auto">
    <div class="admin-page-header">
        <h1 class="admin-page-title">Xác nhận đổi mật khẩu</h1>
        <div class="admin-breadcrumb">
            <a href="#" class="admin-breadcrumb-link">Trang chủ</a> / 
            <a href="{{ route('admin.settings.index') }}" class="admin-breadcrumb-link">Cài đặt</a> / 
            <a href="{{ route('admin.password') }}" class="admin-breadcrumb-link">Mật khẩu</a> / 
            Xác nhận
        </div>
    </div>

    <!-- Menu -->
    <div class="mb-6">
        <ul class="flex flex-wrap gap-2 border-b border-gray-200">
            <li><a href="{{ route('admin.settings.index') }}" class="inline-block px-4 py-2 font-semibold text-gray-700 hover:text-blue-600">Tổng quan</a></li>
            <li><a href="{{ route('admin.settings.emails') }}" class="inline-block px-4 py-2 font-semibold text-gray-700 hover:text-blue-600">Emails</a></li>
            <li><a href="{{ route('admin.password') }}" class="inline-block px-4 py-2 font-semibold text-gray-700 hover:text-blue-600 border-b-2 border-blue-600">Mật khẩu</a></li>
        </ul>
    </div>

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

        <form method="POST" action="{{ route('admin.password.verify.code') }}" id="verifyForm" class="space-y-6">
            @csrf

            <div class="space-y-4">
                <div class="text-center">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Nhập mã xác nhận 6 số:</label>
                    <div class="flex gap-2 justify-center mb-4">
                        @for ($i = 0; $i < 6; $i++)
                            <input type="text" name="code[]" maxlength="1"
                                class="w-12 h-12 text-center border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-lg"
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
                    const numbers = pastedData.replace(/\D/g, '').slice(0, 6);
                    
                    if (numbers.length === 6) {
                        inputs.forEach((inputField, i) => {
                            inputField.value = numbers[i] || '';
                        });
                        inputs[inputs.length - 1].focus();
                    } else if (numbers.length > 0) {
                        const remainingSlots = inputs.length - index;
                        const numbersToFill = numbers.slice(0, remainingSlots);
                        
                        numbersToFill.forEach((num, i) => {
                            if (index + i < inputs.length) {
                                inputs[index + i].value = num;
                            }
                        });
                        
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
