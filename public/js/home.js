// Dropdown Toggle with Arrow Rotation
const dropdownToggle = document.getElementById('dropdownToggle');
const dropdownToggleSecond = document.getElementById('dropdownToggleSecond');
const dropdownMenu = document.getElementById('dropdownMenu');
const dropdownMenuSecond = document.getElementById('dropdownMenuSecond');
const arrowIcon = document.querySelector('.arrow-icon');
const arrowIconSecond = document.querySelector('.arrow-icon-second');

dropdownToggle.addEventListener('click', () => {
    dropdownMenu.classList.toggle('show');
    arrowIcon.classList.toggle('rotate');
});

dropdownToggleSecond.addEventListener('click', () => {
    dropdownMenuSecond.classList.toggle('show');
    arrowIconSecond.classList.toggle('rotate');
});

// Countdown Timer
const countdown = () => {
    const endDate = new Date();
    endDate.setDate(endDate.getDate() + 4); // Đặt thời gian kết thúc sau 4 ngày
    const daysEl = document.getElementById('days');
    const hoursEl = document.getElementById('hours');
    const minutesEl = document.getElementById('minutes');
    const secondsEl = document.getElementById('seconds');

    const updateTimer = () => {
        const now = new Date().getTime();
        const distance = endDate.getTime() - now;

        if (distance < 0) {
            clearInterval(timerInterval);
            daysEl.textContent = '00';
            hoursEl.textContent = '00';
            minutesEl.textContent = '00';
            secondsEl.textContent = '00';
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        daysEl.textContent = String(days).padStart(2, '0');
        hoursEl.textContent = String(hours).padStart(2, '0');
        minutesEl.textContent = String(minutes).padStart(2, '0');
        secondsEl.textContent = String(seconds).padStart(2, '0');
    };

    updateTimer();
    const timerInterval = setInterval(updateTimer, 1000);
};
countdown();

// Slider
const slides = document.getElementById('slides');
const paginationButtons = document.querySelectorAll('.pagination-button');
let currentSlide = 0;
const totalSlides = 5;

const goToSlide = (index) => {
    currentSlide = index;
    slides.style.transform = `translateX(-${currentSlide * 100}%)`;
    paginationButtons.forEach((btn, i) => {
        btn.classList.toggle('active', i === currentSlide);
    });
};

const nextSlide = () => {
    currentSlide = (currentSlide + 1) % totalSlides;
    goToSlide(currentSlide);
};

// Tự động chạy slider từ phải sang trái
setInterval(nextSlide, 5000); // Chuyển slide mỗi 3 giây

// Xử lý click vào pagination
paginationButtons.forEach((button, index) => {
    button.addEventListener('click', () => {
        goToSlide(index);
    });
});

// Khởi tạo slide đầu tiên
goToSlide(0);