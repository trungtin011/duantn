@extends('layouts.app')

@section('content')
    <div class="register-container">
        <div class="register-box">
            <div class="register-image"></div>
            <div class="register-form">
                <h2 class="title">Tạo một tài khoản</h2>
                <p class="subtitle">Nhập thông tin của bạn bên dưới</p>
                <form>
                    <div class="form-group">
                        <input type="text" placeholder="Tên" class="input-text">
                    </div>
                    <div class="form-group">
                        <input type="email" placeholder="Email hoặc số điện thoại" class="input-text">
                    </div>
                    <div class="form-group">
                        <input type="password" placeholder="Mật khẩu" class="input-text">
                    </div>

                    <button type="submit" class="submit-button">Tạo tài khoản</button>

                    <button type="button" class="google-button">
                        <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google icon" class="google-icon">
                        Đăng ký với Google
                    </button>

                    <div class="extra-links">
                        <a href="#" class="extra-link">Đã có tài khoản?</a>
                        <a href="{{ route('login') }}" class="extra-link">Đăng nhập</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        * {
            box-sizing: border-box;
        }

        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .register-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f3f4f6;
            padding: 1rem;
        }

        .register-box {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 1120px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 1rem;
            overflow: hidden;
        }

        @media (min-width: 768px) {
            .register-box {
                flex-direction: row;
            }
        }

        .register-image {
            width: 100%;
            min-height: 300px;
            background-image: url('https://e-commerce-website-muzaffar-ali.vercel.app/_next/image?url=%2Fimages%2Fsignup%2Fmobile.png&w=828&q=75');
            background-size: cover;
            background-position: center;
        }

        @media (min-width: 768px) {
            .register-image {
                width: 50%;
                min-height: 600px;
            }
        }

        .register-form {
            width: 100%;
            background-color: #ffffff;
            padding: 1.5rem;
        }

        @media (min-width: 768px) {
            .register-form {
                width: 50%;
                padding: 4rem;
            }
        }

        .title {
            font-size: 1.875rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        @media (min-width: 768px) {
            .title {
                font-size: 2.25rem;
            }
        }

        .subtitle {
            color: #4B5563;
            margin-bottom: 2rem;
            font-size: 1rem;
        }

        @media (min-width: 768px) {
            .subtitle {
                font-size: 1.125rem;
            }
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .input-text {
            width: 100%;
            font-size: 1rem;
            padding: 0.75rem 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
            outline: none;
        }

        @media (min-width: 768px) {
            .input-text {
                font-size: 1.125rem;
            }
        }

        .input-text:focus {
            border-color: #3B82F6;
            box-shadow: 0 0 0 2px #bfdbfe;
        }

        .submit-button {
            width: 100%;
            background-color: #000;
            color: #fff;
            padding: 0.75rem;
            font-size: 1rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-button:hover {
            background-color: #1f2937;
        }

        .google-button {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            border: 1px solid #d1d5db;
            color: #000;
            padding: 0.75rem;
            font-size: 1rem;
            border-radius: 0.5rem;
            margin-top: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .google-button:hover {
            background-color: #f3f4f6;
        }

        .google-icon {
            height: 20px;
            width: 20px;
            margin-right: 0.5rem;
        }

        .extra-links {
            margin-top: 1.5rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            font-size: 0.875rem;
            gap: 0.5rem;
        }

        .extra-link {
            color: #4B5563;
            text-decoration: none;
        }

        .extra-link:hover {
            text-decoration: underline;
        }
    </style>
@endsection
