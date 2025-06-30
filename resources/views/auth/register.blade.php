@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-8 px-4">
        <div class="w-full max-w-md shadow-2xl rounded-2xl overflow-hidden bg-white">
            <!-- Image Section -->
            <div class="w-full h-40 bg-cover bg-center" style="background-image: url('https://e-commerce-website-muzaffar-ali.vercel.app/_next/image?url=%2Fimages%2Fsignup%2Fmobile.png&w=828&q=75');">
            </div>
            <!-- Form Section -->
            <div class="w-full flex flex-col justify-center p-6">
                <h2 class="text-2xl font-bold mb-2 text-center">Tạo tài khoản</h2>
                <p class="text-gray-500 mb-6 text-center text-base">Nhập thông tin của bạn bên dưới</p>
                <form method="POST" action="{{ route('register.post') }}" class="space-y-3">
                    @csrf
                    <div class="flex gap-3">
                        <div class="w-1/2">
                            <input type="text" name="username" placeholder="Tên đăng nhập" value="{{ old('username') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-base focus:ring-2 focus:ring-black focus:outline-none placeholder-gray-400 @error('username') border-red-500 @enderror"
                                required />
                            @error('username')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="w-1/2">
                            <input type="text" name="fullname" placeholder="Họ và tên" value="{{ old('fullname') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-base focus:ring-2 focus:ring-black focus:outline-none placeholder-gray-400 @error('fullname') border-red-500 @enderror"
                                required />
                            @error('fullname')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <input type="text" name="phone" placeholder="Số điện thoại" value="{{ old('phone') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-base focus:ring-2 focus:ring-black focus:outline-none placeholder-gray-400 @error('phone') border-red-500 @enderror"
                        required />
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-base focus:ring-2 focus:ring-black focus:outline-none placeholder-gray-400 @error('email') border-red-500 @enderror"
                        required />
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <div class="flex gap-3">
                        <div class="w-1/2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/></svg>
                                </div>
                                <input id="default-datepicker" type="text" name="birthday" value="{{ old('birthday') }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 pl-10 text-base focus:ring-2 focus:ring-black focus:outline-none placeholder-gray-400 @error('birthday') border-red-500 @enderror"
                                    placeholder="Chọn ngày sinh" />
                            </div>
                            @error('birthday')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="w-1/2">
                            <select name="gender"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-base focus:ring-2 focus:ring-black focus:outline-none placeholder-gray-400 cursor-pointer @error('gender') border-red-500 @enderror">
                                <option value="" {{ old('gender') == '' ? 'selected' : '' }}>Chọn giới tính</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('gender')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <input type="password" name="password" placeholder="Mật khẩu"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-base focus:ring-2 focus:ring-black focus:outline-none placeholder-gray-400 @error('password') border-red-500 @enderror"
                        required />
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-base focus:ring-2 focus:ring-black focus:outline-none placeholder-gray-400" required />
                    <button type="submit"
                        class="w-full bg-black hover:bg-gray-800 text-white py-3 text-base font-semibold rounded-lg shadow-md transition duration-200 mt-2">
                        Tạo tài khoản
                    </button>
                    <div class="flex flex-col md:flex-row gap-2 mt-2">
                        <a href="{{ route('auth.google.login') }}"
                            class="w-full flex items-center justify-center border border-red-300 hover:bg-gray-100 py-2 text-base rounded-lg transition">
                            <img src="https://www.svgrepo.com/show/355037/google.svg" class="h-5 w-5 mr-2" alt="Google icon">
                            Google
                        </a>
                        <a href="{{ route('auth.facebook.login') }}"
                            class="w-full flex items-center justify-center border border-blue-300 hover:bg-gray-100 py-2 text-base rounded-lg transition">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b9/2023_Facebook_icon.svg/600px-2023_Facebook_icon.svg.png" class="h-5 w-5 mr-2" alt="Facebook icon">
                            Facebook
                        </a>
                    </div>
                    <div class="flex mt-4 text-xs flex-wrap gap-2 justify-center">
                        <span class="text-gray-600">Đã có tài khoản?
                            <a href="{{ route('login') }}" class="text-black hover:underline font-medium">Đăng nhập</a>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
