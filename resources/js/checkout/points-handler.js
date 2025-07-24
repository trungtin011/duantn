function initializePointsHandler(user_points) {
    const togglePointsBtn = document.getElementById('toggle-points-btn');
    const totalPoints = document.getElementById('user_points');
    const usedPoints = document.getElementById('used_points');


    validatePoints();

    function validatePoints() {
        let value = parseInt(usedPoints.value, 10) || 0;

        if (value > user_points) {
            value = user_points;
        }
        if (value < 0) {
            value = 0;
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