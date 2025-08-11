<div id="global-popup-overlay" style="display:none; position:fixed; z-index:9999; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4);">
    <div id="global-popup-content" style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; border-radius:8px; min-width:320px; min-height:100px; box-shadow:0 2px 16px rgba(0,0,0,0.2); padding:24px;">
        <button onclick="closeGlobalPopup()" style="position:absolute; top:8px; right:12px; background:none; border:none; font-size:20px; cursor:pointer;">&times;</button>
        <div id="global-popup-body">
            <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:18px;">Chọn phương thức thanh toán</h3>
            <form id="repay-method-form">
                <div style="display:flex; flex-direction:column; gap:16px;">
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                        <input type="radio" name="payment_method" value="vnpay" style="accent-color:#1a73e8;">
                        <img src="{{ asset('images/payments/vnpay.png') }}" alt="VNPAY" style="height:28px;"> VNPAY
                    </label>
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                        <input type="radio" name="payment_method" value="momo" style="accent-color:#a50064;">
                        <img src="{{ asset('images/payments/momo.png') }}" alt="Momo" style="height:28px;"> Momo
                    </label>
                </div>
                <div style="margin-top:24px; display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" onclick="closeGlobalPopup()" style="padding:8px 18px; border-radius:6px; border:1px solid #ccc; background:#f3f4f6; color:#333; font-weight:500; cursor:pointer;">Hủy</button>
                    <button type="submit" style="padding:8px 18px; border-radius:6px; background:linear-gradient(90deg,#22c55e,#16a34a); color:#fff; font-weight:600; border:none; cursor:pointer;">Xác nhận</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('repay-method-form');
        if(form){
            form.onsubmit = function(e){
                e.preventDefault();
                var method = form.querySelector('input[name="payment_method"]:checked');
                if(!method){
                    closeGlobalPopup();
                    Swal.fire({
                        icon: 'error',
                        title: '😢 Có gì đó không đúng!',
                        text: 'Vui lòng chọn phương thức thanh toán!'
                    });
                    return false;
                }else{
                    let order_code = window.repay_order_code ;
                    if(method.value === 'cod'){
                        closeGlobalPopup();
                        Swal.fire({
                            icon: 'error',
                            title: '😢 Có gì đó không đúng!',
                            text: 'Vui lòng chọn phương thức thanh toán!'
                        });
                    }
                    else if(method.value === 'momo'){   
                        window.location.href = "{{ route('checkout.momo.payment', ['order_code' => ':order_code']) }}".replace(':order_code', order_code);
                    }
                    else if(method.value === 'vnpay'){
                        window.location.href = "{{ route('checkout.vnpay.payment', ['order_code' => ':order_code']) }}".replace(':order_code', order_code);
                    }
                }
                closeGlobalPopup();
            }
        }
    });
</script>
