function initializePointsHandler(user_points, subtotal) {
    const togglePointsBtn = document.getElementById('toggle-points-btn');
    const totalPoints = document.getElementById('user_points');
    const usedPoints = document.getElementById('used_points');

    validatePoints();
    
    function validatePoints() {
        let value = parseInt(usedPoints.value, 10) || 0;

        if (value > user_points) {
            value = user_points;
            window.showError('Số điểm tích lũy không đủ để sử dụng');
        }
        const maxPointsAllowed = Math.floor(subtotal * 0.2);
        if (value > maxPointsAllowed) {
            value = maxPointsAllowed;
            window.showError('Bạn chỉ có thể sử dụng tối đa 20% giá trị đơn hàng để đổi điểm');
        }
        if (value < 0) {
            value = 0;
            window.showError('Số điểm không được nhỏ hơn 0');
        }

        usedPoints.value = value;

        if (value === 0) {
            togglePointsBtn.checked = false;
        } else {
            togglePointsBtn.checked = true;
        }
    }

    usedPoints.addEventListener('input', validatePoints);

    togglePointsBtn.addEventListener('change', function() {
        validatePoints();
    });

    function updatePointsAmount() {
        let value = parseInt(usedPoints.value, 10) || 0;
        document.getElementById('points_amount').textContent = Number(value).toLocaleString('vi-VN') + '₫';
        window.updateTotal();
    }

    usedPoints.addEventListener('input', updatePointsAmount);
    usedPoints.addEventListener('change', updatePointsAmount);
}

export { initializePointsHandler };