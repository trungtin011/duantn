<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật trạng thái đơn hàng</title>
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
            background-color: #f8f9fa;
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
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            background-color: #28a745;
            color: white;
            border-radius: 20px;
            font-weight: bold;
            margin: 10px 0;
        }
        .order-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
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
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🛒 Cập nhật trạng thái đơn hàng</h1>
    </div>
    
    <div class="content">
        <p>Xin chào <strong>{{ $user->name }}</strong>,</p>
        
        <p>Đơn hàng của bạn đã được cập nhật trạng thái:</p>
        
        <div class="status-badge">
            {{ $statusText }}
        </div>
        
        <div class="order-details">
            <h3>Thông tin đơn hàng:</h3>
            <p><strong>Mã đơn hàng:</strong> #{{ $shopOrder->order_code }}</p>
            <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Tổng tiền:</strong> {{ number_format($order->total_amount) }} VNĐ</p>
            <p><strong>Địa chỉ giao hàng:</strong> {{ $order->address->address ?? 'N/A' }}</p>
        </div>
        
        @if($status === 'confirmed')
            <p>🎉 Đơn hàng của bạn đã được xác nhận và đang được chuẩn bị. Chúng tôi sẽ thông báo khi đơn hàng sẵn sàng để vận chuyển.</p>
        @elseif($status === 'ready_to_pick')
            <p>📦 Đơn hàng của bạn đã sẵn sàng để lấy hàng. Đối tác vận chuyển sẽ liên hệ với bạn trong thời gian sớm nhất.</p>
        @elseif($status === 'shipping')
            <p>🚚 Đơn hàng của bạn đang được vận chuyển. Bạn có thể theo dõi trạng thái vận chuyển qua link bên dưới.</p>
        @elseif($status === 'delivered')
            <p>✅ Đơn hàng của bạn đã được giao thành công. Cảm ơn bạn đã mua sắm tại cửa hàng chúng tôi!</p>
        @elseif($status === 'cancelled')
            <p>❌ Đơn hàng của bạn đã bị hủy. Nếu bạn có thắc mắc, vui lòng liên hệ với chúng tôi.</p>
        @else
            <p>Trạng thái đơn hàng của bạn đã được cập nhật thành <strong>{{ $statusText }}</strong>.</p>
        @endif
        
        <a href="{{ route('user.order.detail', $order->id) }}" class="btn">Xem chi tiết đơn hàng</a>
        
        <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua:</p>
        <ul>
            <li>Email: support@example.com</li>
            <li>Hotline: 1900-xxxx</li>
        </ul>
    </div>
    
    <div class="footer">
        <p>© {{ date('Y') }} STM-WD. Tất cả quyền được bảo lưu.</p>
        <p>Email này được gửi tự động, vui lòng không trả lời email này.</p>
    </div>
</body>
</html> 