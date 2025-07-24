$(document).ready(function() {
    // Lấy trạng thái ban đầu từ server (nên render từ backend, demo hardcode false)
    var autoReplyEnabled = {{ Auth::user()->seller->auto_reply_enabled ?? 0 }};
    var $checkbox = $('#auto-reply-checkbox');
    $checkbox.prop('checked', autoReplyEnabled == 1);
    $checkbox.prop('disabled', false);

    $checkbox.on('change', function() {
        var enabled = $(this).is(':checked') ? 1 : 0;
        $.ajax({
            url: "{{ route('seller.chat.auto_reply_toggle') }}",
            method: 'POST',
            data: {
                enabled: enabled,
                _token: "{{ csrf_token() }}"
            },
            success: function(res) {
                if(res.success) {
                    // Có thể show thông báo thành công
                }
            },
            error: function() {
                alert('Có lỗi xảy ra, vui lòng thử lại!');
                $checkbox.prop('checked', !enabled);
            }
        });
    });
});
