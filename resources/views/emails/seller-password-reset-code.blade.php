<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mã xác nhận đổi mật khẩu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .code {
            background: #fff;
            border: 2px dashed #667eea;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            border-radius: 10px;
        }
        .code-number {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            letter-spacing: 5px;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Mã xác nhận đổi mật khẩu</h1>
        <p>ZynoxMall - Hệ thống quản lý Shop</p>
    </div>
    
    <div class="content">
        <p>Xin chào <strong>{{ $name }}</strong>,</p>
        
        <p>Bạn đã yêu cầu đổi mật khẩu cho tài khoản seller. Vui lòng sử dụng mã xác nhận dưới đây để tiếp tục:</p>
        
        <div class="code">
            <div class="code-number">{{ $code }}</div>
        </div>
        
        <div class="warning">
            <strong>Lưu ý:</strong>
            <ul>
                <li>Mã xác nhận này có hiệu lực trong 10 phút</li>
                <li>Không chia sẻ mã này với bất kỳ ai</li>
                <li>Nếu bạn không yêu cầu đổi mật khẩu, vui lòng bỏ qua email này</li>
            </ul>
        </div>
        
        <p>Nếu bạn gặp vấn đề, vui lòng liên hệ với đội ngũ hỗ trợ.</p>
        
        <p>Trân trọng,<br>
        <strong>Đội ngũ ZynoxMall</strong></p>
    </div>
    
    <div class="footer">
        <p>Email này được gửi tự động, vui lòng không trả lời.</p>
        <p>&copy; {{ date('Y') }} ZynoxMall. Tất cả quyền được bảo lưu.</p>
    </div>
</body>
</html>
