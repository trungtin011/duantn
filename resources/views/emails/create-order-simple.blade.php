<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công</title>
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
            background-color: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #ffffff;
            padding: 30px;
            border: 1px solid #e9ecef;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            font-size: 14px;
            color: #6c757d;
        }
        .order-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Đặt hàng thành công!</h1>
    </div>
    
    <div class="content">
        <p>Xin chào,</p>
        
        <p>Cảm ơn bạn đã đặt hàng tại STM-WD. Đơn hàng của bạn đã được tiếp nhận thành công!</p>
        
        <div class="order-info">
            <h3>Thông tin đơn hàng:</h3>
            <p><strong>Mã đơn hàng:</strong> #{{ $order_code }}</p>
            <p><strong>Ngày đặt:</strong> {{ $order_date }}</p>
        </div>
        
        <p>Chúng tôi sẽ xử lý đơn hàng của bạn trong thời gian sớm nhất và gửi thông báo khi có cập nhật.</p>
        
        <a href="{{ route('order_history') }}" class="btn">Xem đơn hàng của tôi</a>
        
        <p>Nếu bạn có thắc mắc, vui lòng liên hệ với chúng tôi qua:</p>
        <ul>
            <li>Email: support@stm-wd.com</li>
            <li>Hotline: 1900-xxxx</li>
        </ul>
    </div>
    
    <div class="footer">
        <p>© {{ date('Y') }} STM-WD. Tất cả quyền được bảo lưu.</p>
        <p>Email này được gửi tự động, vui lòng không trả lời email này.</p>
    </div>
</body>
</html> 