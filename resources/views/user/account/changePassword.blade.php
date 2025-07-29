@extends('user.account.layout')

@section('account-content')
    <div class="bg-white p-6 rounded shadow max-w-md mx-auto relative z-10">
        <h2 class="text-xl font-semibold mb-4">Đổi mật khẩu</h2>

        @if (session('password_success'))
            <div class="text-green-600 mb-4">{{ session('password_success') }}</div>
        @endif

        <form method="POST" action="{{ route('account.password.request.confirm') }}">
            @csrf
            <p class="mb-4">Chúng tôi sẽ gửi mã xác nhận tới email của bạn để tiếp tục đổi mật khẩu.</p>

            <button type="submit" class="bg-black text-white px-4 py-2 rounded w-full">
                Gửi mã xác nhận
            </button>
        </form>
    </div>

    {{-- Loading overlay chỉ là vòng xoay --}}
    <div id="loading-spinner" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="loader">
            <div class="inner one"></div>
            <div class="inner two"></div>
            <div class="inner three"></div>
        </div>
    </div>

    {{-- CSS cho vòng xoay --}}
    <style>
        .loader {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            perspective: 800px;
            position: relative;
        }

        .inner {
            position: absolute;
            box-sizing: border-box;
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }

        .inner.one {
            left: 0%;
            top: 0%;
            animation: rotate-one 1s linear infinite;
            border-bottom: 3px solid #000;
        }

        .inner.two {
            right: 0%;
            top: 0%;
            animation: rotate-two 1s linear infinite;
            border-right: 3px solid #000;
        }

        .inner.three {
            right: 0%;
            bottom: 0%;
            animation: rotate-three 1s linear infinite;
            border-top: 3px solid #000;
        }

        @keyframes rotate-one {
            0% { transform: rotateX(35deg) rotateY(-45deg) rotateZ(0deg); }
            100% { transform: rotateX(35deg) rotateY(-45deg) rotateZ(360deg); }
        }

        @keyframes rotate-two {
            0% { transform: rotateX(50deg) rotateY(10deg) rotateZ(0deg); }
            100% { transform: rotateX(50deg) rotateY(10deg) rotateZ(360deg); }
        }

        @keyframes rotate-three {
            0% { transform: rotateX(35deg) rotateY(55deg) rotateZ(0deg); }
            100% { transform: rotateX(35deg) rotateY(55deg) rotateZ(360deg); }
        }
    </style>

    {{-- JS hiển thị vòng xoay khi submit --}}
    <script>
        document.getElementById('sendCodeForm').addEventListener('submit', function () {
            document.getElementById('loading-spinner').classList.remove('hidden');
        });
    </script>
@endsection
