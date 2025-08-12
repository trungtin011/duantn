<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đặt lại mật khẩu</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
            background: #f7f7f8;
            margin: 0;
            padding: 24px;
            color: #111827;
        }

        .container {
            max-width: 560px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .header {
            padding: 20px 24px;
            background: #111827;
            color: #fff;
            font-weight: 600;
            font-size: 18px;
        }

        .content {
            padding: 24px;
        }

        .code-box {
            display: inline-block;
            background: #111827;
            color: #fff;
            border-radius: 8px;
            letter-spacing: 6px;
            font-weight: 700;
            font-size: 22px;
            padding: 10px 14px;
            margin: 12px 0 18px;
        }

        .button {
            display: inline-block;
            background: #000000;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 8px;
            font-weight: 600;
        }

        .footer {
            padding: 20px 24px;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #f0f0f0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">Xác nhận đặt lại mật khẩu</div>
        <div class="content">
            <p>Xin chào,</p>
            <p>Vui lòng sử dụng mã bên dưới để xác nhận đặt lại mật khẩu của bạn:</p>
            <div class="code-box">{{ $code }}</div>
            <p>Hoặc bạn có thể nhấn nút dưới đây để đổi mật khẩu trực tiếp:</p>
            <p>
                <a class="button" href="{{ $link }}" target="_blank" rel="noopener">Đặt lại mật khẩu</a>
            </p>
            <p>Nếu bạn không yêu cầu thao tác này, vui lòng bỏ qua email.</p>
        </div>
        <div class="footer">Email được gửi tự động, vui lòng không phản hồi.</div>
    </div>
</body>

</html>
