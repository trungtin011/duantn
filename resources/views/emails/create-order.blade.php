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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
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
        .success-badge {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border-radius: 25px;
            font-weight: bold;
            margin: 15px 0;
            font-size: 18px;
        }
        .order-details {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin: 25px 0;
            border-left: 4px solid #007bff;
        }
        .product-list {
            background-color: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            margin: 20px 0;
        }
        .product-item {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }
        .product-info {
            flex: 1;
        }
        .product-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .product-price {
            color: #dc3545;
            font-weight: bold;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            margin: 20px 0;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .total-section {
            background-color: #e9ecef;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: right;
        }
        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
        }
        .steps {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .step {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }
        .step-number {
            background-color: #007bff;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Đặt hàng thành công!</h1>
        <p>Cảm ơn bạn đã mua sắm tại STM-WD</p>
    </div>
    
    <div class="content">
        <div class="success-badge">
            ✅ Đơn hàng đã được đặt thành công
        </div>
        
        <p>Xin chào <strong>{{ $user->name }}</strong>,</p>
        
        <p>Cảm ơn bạn đã đặt hàng tại STM-WD. Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.</p>
        
        <div class="order-details">
            <h3>📋 Thông tin đơn hàng:</h3>
            <p><strong>Mã đơn hàng:</strong> <span style="color: #007bff; font-weight: bold;">#{{ $order->order_code }}</span></p>
            <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Trạng thái:</strong> <span style="color: #28a745;">{{ $statusText }}</span></p>
            <p><strong>Phương thức thanh toán:</strong> 
                @if($order->payment_method == 'cod')
                    Thanh toán khi nhận hàng (COD)
                @elseif($order->payment_method == 'momo')
                    Ví MoMo
                @elseif($order->payment_method == 'vnpay')
                    VNPay
                @else
                    {{ ucfirst($order->payment_method) }}
                @endif
            </p>
            <p><strong>Địa chỉ giao hàng:</strong> {{ $order->address->address ?? 'N/A' }}</p>
        </div>

        @if($items && count($items) > 0)
        <h3>🛍️ Sản phẩm đã đặt:</h3>
        <div class="product-list">
            @foreach($items as $item)
            <div class="product-item">
                @if($item->product && $item->product->images && count($item->product->images) > 0)
                    <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                         alt="{{ $item->product->name }}" 
                         class="product-image">
                @else
                    <div style="width: 60px; height: 60px; background-color: #f8f9fa; border-radius: 8px; margin-right: 15px; display: flex; align-items: center; justify-content: center;">
                        📦
                    </div>
                @endif
                <div class="product-info">
                    <div class="product-name">{{ $item->product->name ?? 'Sản phẩm không tồn tại' }}</div>
                    <div>Số lượng: {{ $item->quantity }}</div>
                    @if($item->variant)
                        <div>Phân loại: {{ $item->variant->name }}</div>
                    @endif
                </div>
                <div class="product-price">
                    {{ number_format($item->price) }} VNĐ
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div class="total-section">
            <p><strong>Tổng tiền hàng:</strong> {{ number_format($order->subtotal) }} VNĐ</p>
            @if($order->shipping_fee > 0)
                <p><strong>Phí vận chuyển:</strong> {{ number_format($order->shipping_fee) }} VNĐ</p>
            @endif
            @if($order->discount_amount > 0)
                <p><strong>Giảm giá:</strong> -{{ number_format($order->discount_amount) }} VNĐ</p>
            @endif
            <p class="total-amount">Tổng cộng: {{ number_format($order->total_amount) }} VNĐ</p>
        </div>

        <div class="steps">
            <h3>📋 Quy trình xử lý đơn hàng:</h3>
            <div class="step">
                <div class="step-number">1</div>
                <div>Đơn hàng đã được tiếp nhận</div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div>Người bán xác nhận đơn hàng</div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div>Đơn hàng được chuẩn bị và giao cho đối tác vận chuyển</div>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <div>Đơn hàng được giao đến bạn</div>
            </div>
        </div>
        
        <a href="{{ route('user.order.detail', $order->id) }}" class="btn">Xem chi tiết đơn hàng</a>
        
        <p><strong>Lưu ý quan trọng:</strong></p>
        <ul>
            <li>Đơn hàng sẽ được xử lý trong vòng 24-48 giờ</li>
            <li>Bạn sẽ nhận được thông báo khi đơn hàng được cập nhật trạng thái</li>
            <li>Nếu có thắc mắc, vui lòng liên hệ với chúng tôi</li>
        </ul>
        
        <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua:</p>
        <ul>
            <li>📧 Email: support@stm-wd.com</li>
            <li>📞 Hotline: 1900-xxxx</li>
            <li>💬 Chat trực tuyến: Tại website</li>
        </ul>
    </div>
    
    <div class="footer">
        <p>© {{ date('Y') }} STM-WD. Tất cả quyền được bảo lưu.</p>
        <p>Email này được gửi tự động, vui lòng không trả lời email này.</p>
        <p>Để hủy nhận email, vui lòng vào <a href="{{ route('account.profile') }}">Cài đặt tài khoản</a></p>
    </div>
</body>
</html> 