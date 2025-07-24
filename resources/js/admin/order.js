document.getElementById('update-status-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const form = this;
    const statusSelect = document.getElementById('status');
    const selectedStatus = statusSelect.value;
    const selectedStatusText = statusSelect.options[statusSelect.selectedIndex].text;

    // Xây dựng HTML cho modal
    let modalHtml = `
        <div class="text-left">
            <div class="mb-4">
                <label for="description" class="text-xs text-gray-500">Mô tả</label>
                <input type="text" id="modal-description" class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none" placeholder="Nhập mô tả">
            </div>
            <div class="mb-4">
                <label for="cancel_reason" class="text-xs text-gray-500">Lý do hủy (nếu có)</label>
                <input type="text" id="modal-cancel_reason" class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none" placeholder="Nhập lý do hủy">
            </div>
            <div class="mb-4">
                <label for="note" class="text-xs text-gray-500">Ghi chú</label>
                <textarea id="modal-note" class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none" placeholder="Nhập ghi chú" rows="3"></textarea>
            </div>
    `;

    if (selectedStatus === 'shipped') {
        modalHtml += `
            <div class="mb-4">
                <label for="shipping_provider" class="text-xs text-gray-500">Nhà vận chuyển</label>
                <input type="text" id="modal-shipping_provider" class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none" placeholder="Nhập nhà vận chuyển">
            </div>
        `;
    }

    modalHtml += `</div>`;

    Swal.fire({
        title: `Cập nhật trạng thái: ${selectedStatusText}`,
        html: modalHtml,
        showCancelButton: true,
        confirmButtonText: 'Xác nhận',
        cancelButtonText: 'Hủy',
        confirmButtonColor: '#0989ff',
        cancelButtonColor: '#d33',
        width: '500px',
        preConfirm: () => {
            // Lấy giá trị từ các input trong modal
            const description = document.getElementById('modal-description').value;
            const shippingProvider = document.getElementById('modal-shipping_provider')?.value || null;
            let cancelReason = document.getElementById('modal-cancel_reason').value;
            const note = document.getElementById('modal-note').value;

            // Debug giá trị description
            console.log('Modal Description:', description);

            if (selectedStatus === 'cancelled' && !cancelReason) {
                Swal.showValidationMessage('Vui lòng nhập lý do hủy khi hủy đơn hàng');
                return false;
            }

            return {
                description: description || null,
                shipping_provider: shippingProvider,
                cancel_reason: cancelReason || null,
                note: note || null
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Gán giá trị từ modal vào form
            const descriptionInput = document.getElementById('description');
            descriptionInput.value = result.value.description || '';

            document.getElementById('shipping_provider').value = result.value.shipping_provider || '';
            document.getElementById('cancel_reason').value = result.value.cancel_reason || '';
            document.getElementById('status-note').value = result.value.note || '';

            // Debug giá trị sau khi gán vào input ẩn
            console.log('Hidden Input Description:', descriptionInput.value);
            console.log('Form Data:', [...new FormData(form)]);

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: data.message,
                            confirmButtonColor: '#0989ff',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: data.message || 'Có lỗi xảy ra khi cập nhật trạng thái.',
                            confirmButtonColor: '#d33',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Có lỗi xảy ra khi gửi yêu cầu.',
                        confirmButtonColor: '#d33',
                        timer: 3000,
                        showConfirmButton: false
                    });
                });
        }
    });
});