@extends('layouts.app')

@section('content')
<<<<<<< HEAD
<div class="login-container">
    <div class="login-box">
        <div class="login-image"></div>
        <div class="login-form">
            @if(session('success'))
            <div style="color: green; margin-bottom: 20px;">
                {{ session('success') }}
=======
    <div class="login-container">
        <div class="login-box">
            <div class="login-image"></div>
            <div class="login-form">
                @if (session('success'))
                    <div style="color: green; margin-bottom: 20px;">
                        {{ session('success') }}
                    </div>
                @endif
                <h2 class="title">Đăng nhập</h2>
                <p class="subtitle">Nhập thông tin của bạn bên dưới</p>
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="login" placeholder="Email hoặc số điện thoại" class="input-text"
                            value="{{ old('login') }}">
                        @error('login')
                            <p style="color:red; font-size: 14px">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input type="password" name="password" placeholder="Mật khẩu" class="input-text">
                        @error('password')
                            <p style="color:red; font-size: 14px">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-remember">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Nhớ mật khẩu</label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="login-button">Đăng nhập</button>
                        <a href="#" class="forgot-password">Quên mật khẩu?</a>
                    </div>

                    <div class="signup-wrap">
                        <span>Bạn chưa có tài khoản?
                            <a href="{{ route('signup') }}">Đăng ký</a>
                        </span>
                    </div>
                </form>
>>>>>>> tin
            </div>
            @endif
            <h2 class="title">Đăng nhập</h2>
            <p class="subtitle">Nhập thông tin của bạn bên dưới</p>
            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="form-group">
                    <input type="text" name="login" placeholder="Email hoặc số điện thoại" class="input-text" value="{{ old('login') }}">
                    @error('login')
                    <p style="color:red; font-size: 14px">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="password" name="password" placeholder="Mật khẩu" class="input-text">
                    @error('password')
                    <p style="color:red; font-size: 14px">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-remember">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Nhớ mật khẩu</label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="login-button">Đăng nhập</button>
                    <a href="#" class="forgot-password">Quên mật khẩu?</a>
                </div>

                <div class="signup-wrap">
                    <span>Bạn chưa có tài khoản?
                        <a href="{{ route('signup') }}">Đăng ký</a>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    * {
        box-sizing: border-box;
    }

<<<<<<< HEAD
    body,
    html {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }
=======
        body,
        html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
>>>>>>> tin

    .login-container {
        min-height: 100vh;
        background-color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-box {
        width: 90%;
        max-width: 1120px;
        display: flex;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .login-image {
        width: 50%;
        background-image: url('https://e-commerce-website-muzaffar-ali.vercel.app/_next/image?url=%2Fimages%2Fsignup%2Fmobile.png&w=828&q=75');
        background-size: cover;
        background-position: center;
        min-height: 600px;
    }

    .login-form {
        width: 50%;
        background-color: #ffffff;
        padding: 64px;
    }

    .title {
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 16px;
    }

    .subtitle {
        font-size: 18px;
        color: #666666;
        margin-bottom: 32px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .input-text {
        width: 100%;
        padding: 12px 8px;
        font-size: 16px;
        border: none;
        border-bottom: 1px solid #ccc;
        outline: none;
    }

    .input-text:focus {
        border-color: #000;
    }

    .form-remember {
        display: flex;
        align-items: center;
        margin-bottom: 32px;
        font-size: 14px;
        color: #555;
    }

    .form-remember input {
        margin-right: 8px;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .login-button {
        background-color: #000;
        color: #fff;
        padding: 12px 32px;
        font-size: 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    .login-button:hover {
        background-color: #333;
    }

    .forgot-password {
        font-size: 14px;
        color: #999;
        text-decoration: none;
    }

    .forgot-password:hover {
        text-decoration: underline;
    }

    .signup-wrap {
        margin-top: 24px;
        font-size: 14px;
    }

    .signup-wrap a {
        color: #444;
        text-decoration: none;
        margin-left: 4px;
    }

    .signup-wrap a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .login-box {
            flex-direction: column;
        }

        .login-image,
        .login-form {
            width: 100%;
        }

        .login-form {
            padding: 40px;
        }
<<<<<<< HEAD
    }
</style>
=======

        .title {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 16px;
        }

        .subtitle {
            font-size: 18px;
            color: #666666;
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .input-text {
            width: 100%;
            padding: 12px 8px;
            font-size: 16px;
            border: none;
            border-bottom: 1px solid #ccc;
            outline: none;
        }

        .input-text:focus {
            border-color: #000;
        }

        .form-remember {
            display: flex;
            align-items: center;
            margin-bottom: 32px;
            font-size: 14px;
            color: #555;
        }

        .form-remember input {
            margin-right: 8px;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .login-button {
            background-color: #000;
            color: #fff;
            padding: 12px 32px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .login-button:hover {
            background-color: #333;
        }

        .forgot-password {
            font-size: 14px;
            color: #999;
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .signup-wrap {
            margin-top: 24px;
            font-size: 14px;
        }

        .signup-wrap a {
            color: #444;
            text-decoration: none;
            margin-left: 4px;
        }

        .signup-wrap a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .login-box {
                flex-direction: column;
            }

            .login-image,
            .login-form {
                width: 100%;
            }

            .login-form {
                padding: 40px;
            }
        }
    </style>
>>>>>>> tin
@endsection
