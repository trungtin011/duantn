# Hướng dẫn cấu hình Email

## Vấn đề hiện tại
Hệ thống đang sử dụng `log` driver mặc định, có nghĩa là email sẽ được ghi vào log thay vì gửi thực sự.

## Cách khắc phục

### 1. Cấu hình SMTP (Khuyến nghị)

Thêm các cấu hình sau vào file `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Sử dụng Gmail SMTP

1. Bật 2FA trên tài khoản Gmail
2. Tạo App Password:
   - Vào Google Account Settings
   - Security > 2-Step Verification > App passwords
   - Tạo password cho "Mail"
3. Sử dụng App Password thay vì mật khẩu Gmail

### 3. Test Email

Sau khi cấu hình, sử dụng nút "Test Email" trong trang đổi mật khẩu để kiểm tra.

### 4. Xem Log

Nếu vẫn có lỗi, kiểm tra log tại:
```
storage/logs/laravel.log
```

### 5. Các driver khác

- **Mailgun**: `MAIL_MAILER=mailgun`
- **SendGrid**: `MAIL_MAILER=smtp` với host `smtp.sendgrid.net`
- **Amazon SES**: `MAIL_MAILER=ses`

## Lưu ý

- Đảm bảo port 587 hoặc 465 không bị chặn
- Kiểm tra firewall và antivirus
- Với Gmail, sử dụng App Password thay vì mật khẩu thường 