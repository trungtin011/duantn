@extends('user.account.layout')

@section('account-content')
    <div class="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800">Đặt lại mật khẩu mới</h2>
            <p class="text-sm text-gray-600">Nhập mật khẩu mới cho tài khoản của bạn.</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('account.password.reset.confirm') }}" id="resetPasswordForm" class="space-y-6">
            @csrf

            <div class="space-y-4">
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-semibold text-gray-700">Mật khẩu mới</label>
                    <input type="password" name="password" id="password"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#f42f46] focus:border-transparent" 
                        required>
                </div>

                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Xác nhận mật khẩu</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#f42f46] focus:border-transparent" 
                        required>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-[#f42f46] text-white text-sm font-semibold px-6 py-2 rounded hover:bg-[#d91f35] focus:outline-none focus:ring-2 focus:ring-[#f42f46] focus:ring-opacity-50">
                        <i class="fas fa-key mr-2"></i> Đổi mật khẩu
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('resetPasswordForm');
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    const password = form.querySelector('input[name="password"]').value;
                    const passwordConfirmation = form.querySelector('input[name="password_confirmation"]').value;
                    
                    console.log('Form submitted:', {
                        password_length: password.length,
                        password_confirmation_length: passwordConfirmation.length,
                        passwords_match: password === passwordConfirmation
                    });
                });
            }
        });
    </script>
@endsection
