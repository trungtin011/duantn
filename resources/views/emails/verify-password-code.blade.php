@component('mail::message')
# Xác nhận thay đổi mật khẩu

Xin chào {{ $user->fullname ?? 'bạn' }},

Mã xác nhận thay đổi mật khẩu của bạn là:

@component('mail::panel')
{{ $code }}
@endcomponent

Mã này sẽ hết hạn sau 10 phút.

Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.

Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!

@endcomponent
