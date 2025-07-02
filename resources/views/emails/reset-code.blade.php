<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Mã xác nhận</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <h2>Xin chào,</h2>
    <p>Bạn đã yêu cầu đặt lại mật khẩu. Đây là mã xác nhận của bạn:</p>
    <div style="font-size: 24px; font-weight: bold; color: #ef4444; margin: 20px 0;">{{ $code }}</div>

    <p>Bạn cũng có thể nhấn vào nút bên dưới để đặt lại mật khẩu nhanh chóng:</p>

    <p style="margin: 30px 0;">
        <a href="{{ $link }}" style="background-color: #ef4444; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            Đổi mật khẩu ngay
        </a>
    </p>

    <p>Nếu bạn không yêu cầu đặt lại mật khẩu, hãy bỏ qua email này.</p>

    <p>Trân trọng,<br>Đội ngũ hỗ trợ</p>
</body>
</html>
