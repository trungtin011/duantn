@extends('layouts.app')
@section('title', 'Đăng ký')
@section('content')
    <div class="flex items-center justify-center px-4 pt-8">
        <div class="flex flex-col md:flex-row w-full max-w-6xl shadow-xl rounded-xl overflow-hidden">
            <!-- Image Section -->
            <img class="w-full md:w-1/2 bg-cover bg-center min-h-[200px] md:min-h-[500px]"
                src="{{ asset('images/register_image.avif') }}" alt="">

            <!-- Form Section -->
            <div class="w-full md:w-1/2 bg-white p-4 md:p-12">
                <h2 class="text-2xl md:text-3xl font-bold mb-3">Tạo một tài khoản</h2>
                <p class="text-gray-600 mb-6 text-sm md:text-base">Nhập thông tin của bạn bên dưới</p>

                <form method="POST" action="{{ route('register.post') }}">
                    @csrf

                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Username -->
                        <div class="mb-4 md:w-1/2">
                            <input type="text" name="username" placeholder="Tên đăng nhập" value="{{ old('username') }}"
                                class="text-sm md:text-base w-full border rounded px-3 py-2 placeholder-gray-400 @error('username') border-red-500 @enderror"
                                required />
                            @error('username')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fullname -->
                        <div class="mb-4 md:w-1/2">
                            <input type="text" name="fullname" placeholder="Họ và tên" value="{{ old('fullname') }}"
                                class="text-sm md:text-base w-full border rounded px-3 py-2 placeholder-gray-400 @error('fullname') border-red-500 @enderror"
                                required />
                            @error('fullname')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="mb-4">
                        <input type="text" name="phone" placeholder="Số điện thoại" value="{{ old('phone') }}"
                            class="text-sm md:text-base w-full border rounded px-3 py-2 placeholder-gray-400 @error('phone') border-red-500 @enderror"
                            required />
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                            class="text-sm md:text-base w-full border rounded px-3 py-2 placeholder-gray-400 @error('email') border-red-500 @enderror"
                            required />
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col md:flex-row justify-between gap-4">
                        <!-- Birthday -->
                        <div class="mb-4 md:w-3/4">
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                                <input datepicker id="default-datepicker" type="text" name="birthday"
                                    value="{{ old('birthday') }}"
                                    class="border border-gray-600 text-gray-900 text-sm rounded focus:ring-blue-500 focus:outline-none block w-full ps-10 p-2.5 dark:border-gray-300 dark:placeholder-gray-400 dark:text-black dark:focus:outline-none"
                                    placeholder="Chọn ngày sinh">
                            </div>
                            @error('birthday')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div class="mb-4 md:w-1/2">
                            <div class="min-w-[200px]">
                                <div class="relative">
                                    <select name="gender"
                                        class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded pl-3 pr-8 py-[10px] transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md appearance-none cursor-pointer">
                                        <option value="" {{ old('gender') == '' ? 'selected' : '' }}>Chọn giới tính
                                        </option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ
                                        </option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác
                                        </option>
                                    </select>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.2" stroke="currentColor"
                                        class="h-5 w-5 ml-1 absolute top-2.5 right-2.5 text-slate-700">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                    </svg>
                                </div>
                            </div>
                            @error('gender')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <input type="password" name="password" placeholder="Mật khẩu"
                            class="text-sm md:text-base w-full border rounded px-3 py-2 placeholder-gray-400 @error('password') border-red-500 @enderror"
                            required />
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div class="mb-4">
                        <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu"
                            class="text-sm md:text-base w-full border rounded px-3 py-2 placeholder-gray-400" required />
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-black hover:bg-gray-800 text-white py-2 text-sm md:text-base rounded">
                        Tạo tài khoản
                    </button>

                    <!-- Google & Facebook Signup -->
                    <div class="flex flex-col md:flex-row gap-2 mt-4">
                        <a href="{{ route('auth.google.login') }}"
                            class="w-full flex items-center justify-center border border-red-300 hover:bg-gray-100 py-2 text-sm md:text-base rounded">
                            <img src="https://www.svgrepo.com/show/355037/google.svg" class="h-4 w-4 mr-2"
                                alt="Google icon">
                            Google
                        </a>
                    </div>

                    <!-- Login Link -->
                    <div class="flex mt-4 text-xs flex-wrap gap-2">
                        <span class="text-gray-600">Đã có tài khoản?
                            <a href="{{ route('login') }}" class="text-gray-600 hover:underline">Đăng nhập</a>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
