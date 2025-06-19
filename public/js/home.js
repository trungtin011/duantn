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
    endDate.setDate(endDate.getDate() + 4);
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


const swiper = new Swiper('.swiper-container', {
    slidesPerView: 4,
    spaceBetween: 16,
    navigation: {
        nextEl: '.next-slide',
        prevEl: '.prev-slide',
    },
});

const swiperBestSeller = new Swiper('.swiper-container-best-seller', {
    slidesPerView: 4,
    spaceBetween: 16,
    navigation: {
        nextEl: '.next-slide-best-seller',
        prevEl: '.prev-slide-best-seller',
    },
});

const swiperExplore = new Swiper('.swiper-container-explore', {
    slidesPerView: 2,
    slidesPerGroup: 1,
    // spaceBetween: 50,
    direction: 'vertical',
    loop: true,
    navigation: {
        nextEl: '.next-slide-explore',
        prevEl: '.prev-slide-explore',
    },
});

// Slider Danh mục
const slider = document.getElementById('category-slider');
const prevButton = document.getElementById('prev-slide-category');
const nextButton = document.getElementById('next-slide-category');
let currentIndex = 0;
const itemsPerPage = 6; // Hiển thị 6 danh mục mỗi lần
const totalItems = 10; // Tổng số danh mục (có thể thay đổi nếu bạn thêm danh mục)

nextButton.addEventListener('click', () => {
    if (currentIndex < totalItems - itemsPerPage) {
        currentIndex += itemsPerPage;
        slider.style.transform = `translateX(-${(currentIndex * (223 + 30)) / itemsPerPage}px)`;
    }
});

prevButton.addEventListener('click', () => {
    if (currentIndex > 0) {
        currentIndex -= itemsPerPage;
        slider.style.transform = `translateX(-${(currentIndex * (223 + 30)) / itemsPerPage}px)`;
    }
});

