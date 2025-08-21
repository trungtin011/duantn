@extends('layouts.admin')

@section('title', 'Cài đặt Email')

@section('content')
    <div class="pb-10 mx-auto">
        <div class="admin-page-header">
            <h1 class="admin-page-title">Cài đặt Email</h1>
            <div class="admin-breadcrumb"><a href="{{ route('admin.settings.index') }}" class="admin-breadcrumb-link">Cài
                    đặt</a> / Email
            </div>
        </div>
        <div class="mb-6">
            <ul class="flex flex-wrap gap-2 border-b border-gray-200">
                <li><a href="{{ route('admin.settings.index') }}"
                        class="inline-block px-4 py-2 font-semibold text-gray-700 hover:text-blue-600">Tổng quan</a></li>
                <li><a href="{{ route('admin.settings.emails') }}"
                        class="inline-block px-4 py-2 font-semibold text-blue-700 border-b-2 border-blue-600">Emails</a>
                </li>
                <li><a href="{{ route('admin.password') }}"
                        class="inline-block px-4 py-2 font-semibold text-gray-700 hover:text-blue-600">Mật khẩu</a></li>
            </ul>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md max-w-2xl">
            <h2 class="text-lg font-semibold mb-4">Thiết lập Email hệ thống</h2>
            @if (session('success'))
                <div class="mb-4 p-2 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif
            <form action="{{ route('admin.settings.emails.update') }}" method="post" class="space-y-6">
                @csrf
                <div class="flex items-center space-x-4">
                    <label class="w-40 text-sm font-semibold text-gray-700" for="mail_from_address">Địa chỉ gửi đi</label>
                    <input
                        class="flex-grow border border-gray-300 rounded px-2 py-1 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-600"
                        id="mail_from_address" type="email" name="mail_from_address"
                        value="{{ old('mail_from_address', $settings['mail_from_address'] ?? '') }}" required />
                </div>
                <div class="flex items-center space-x-4">
                    <label class="w-40 text-sm font-semibold text-gray-700" for="mail_from_name">Tên người gửi</label>
                    <input
                        class="flex-grow border border-gray-300 rounded px-2 py-1 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-600"
                        id="mail_from_name" type="text" name="mail_from_name"
                        value="{{ old('mail_from_name', $settings['mail_from_name'] ?? '') }}" required />
                </div>
                <div class="flex items-center space-x-4">
                    <label class="w-40 text-sm font-semibold text-gray-700" for="mail_reply_to">Email trả lời
                        (Reply-to)</label>
                    <input
                        class="flex-grow border border-gray-300 rounded px-2 py-1 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-600"
                        id="mail_reply_to" type="email" name="mail_reply_to"
                        value="{{ old('mail_reply_to', $settings['mail_reply_to'] ?? '') }}" />
                </div>
                <div class="flex items-center space-x-4">
                    <label class="w-40 text-sm font-semibold text-gray-700" for="mail_host">SMTP Host</label>
                    <input
                        class="flex-grow border border-gray-300 rounded px-2 py-1 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-600"
                        id="mail_host" type="text" name="mail_host"
                        value="{{ old('mail_host', $settings['mail_host'] ?? '') }}" required />
                </div>
                <div class="flex items-center space-x-4">
                    <label class="w-40 text-sm font-semibold text-gray-700" for="mail_port">SMTP Port</label>
                    <input
                        class="flex-grow border border-gray-300 rounded px-2 py-1 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-600"
                        id="mail_port" type="number" name="mail_port"
                        value="{{ old('mail_port', $settings['mail_port'] ?? '') }}" required />
                </div>
                <div class="flex items-center space-x-4">
                    <label class="w-40 text-sm font-semibold text-gray-700" for="mail_username">SMTP Username</label>
                    <input
                        class="flex-grow border border-gray-300 rounded px-2 py-1 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-600"
                        id="mail_username" type="text" name="mail_username"
                        value="{{ old('mail_username', $settings['mail_username'] ?? '') }}" />
                </div>
                <div class="flex items-center space-x-4">
                    <label class="w-40 text-sm font-semibold text-gray-700" for="mail_password">SMTP Password</label>
                    <div class="flex-grow relative">
                        <input
                            class="w-full border border-gray-300 rounded px-2 py-1 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-600 pr-10"
                            id="mail_password" type="password" name="mail_password"
                            value="{{ old('mail_password', $settings['mail_password'] ?? '') }}"
                            autocomplete="new-password" />
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 focus:outline-none"
                            tabindex="-1">
                            <span id="eye-icon"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg></span>
                        </button>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <label class="w-40 text-sm font-semibold text-gray-700" for="mail_encryption">Encryption</label>
                    <select
                        class="flex-grow border border-gray-300 rounded px-2 py-1 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-600"
                        id="mail_encryption" name="mail_encryption">
                        <option value="tls" @if (($settings['mail_encryption'] ?? '') == 'tls') selected @endif>TLS</option>
                        <option value="ssl" @if (($settings['mail_encryption'] ?? '') == 'ssl') selected @endif>SSL</option>
                        <option value="null" @if (($settings['mail_encryption'] ?? '') == 'null' || ($settings['mail_encryption'] ?? '') == '') selected @endif>Không mã hóa</option>
                    </select>
                </div>
                <div class="flex items-center space-x-4">
                    <label class="w-40 text-sm font-semibold text-gray-700" for="mail_driver">Driver</label>
                    <select
                        class="flex-grow border border-gray-300 rounded px-2 py-1 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-600"
                        id="mail_driver" name="mail_driver">
                        <option value="smtp">SMTP</option>
                        <option value="sendmail">Sendmail</option>
                        <option value="mailgun">Mailgun</option>
                        <option value="ses">SES</option>
                    </select>
                </div>
                <div>
                    <button
                        class="bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-600"
                        type="submit">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function togglePassword() {
            const input = document.getElementById('mail_password');
            const icon = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML =
                    `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.368m3.087-2.742A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.421 5.818M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" /></svg>`;
            } else {
                input.type = 'password';
                icon.innerHTML =
                    `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>`;
            }
        }
    </script>
@endsection
