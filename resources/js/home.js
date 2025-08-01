'use strict';

// modal variables
const modal = document.querySelector('[data-modal]');
const modalCloseBtn = document.querySelector('[data-modal-close]');
const modalCloseOverlay = document.querySelector('[data-modal-overlay]');

// modal function
const modalCloseFunc = function () { modal.classList.add('closed') }

// modal eventListener
modalCloseOverlay.addEventListener('click', modalCloseFunc);
modalCloseBtn.addEventListener('click', modalCloseFunc);





// notification toast variables
const notificationToast = document.querySelector('[data-toast]');
const toastCloseBtn = document.querySelector('[data-toast-close]');

// notification toast eventListener
toastCloseBtn.addEventListener('click', function () {
  notificationToast.classList.add('closed');
});


// mobile menu variables
const mobileMenuOpenBtn = document.querySelectorAll('[data-mobile-menu-open-btn]');
const mobileMenu = document.querySelectorAll('[data-mobile-menu]');
const mobileMenuCloseBtn = document.querySelectorAll('[data-mobile-menu-close-btn]');
const overlay = document.querySelector('[data-overlay]');

for (let i = 0; i < mobileMenuOpenBtn.length; i++) {

  // mobile menu function
  const mobileMenuCloseFunc = function () {
    mobileMenu[i].classList.remove('active');
    overlay.classList.remove('active');
  }

  mobileMenuOpenBtn[i].addEventListener('click', function () {
    mobileMenu[i].classList.add('active');
    overlay.classList.add('active');
  });

  mobileMenuCloseBtn[i].addEventListener('click', mobileMenuCloseFunc);
  overlay.addEventListener('click', mobileMenuCloseFunc);

}





// accordion variables
const accordionBtn = document.querySelectorAll('[data-accordion-btn]');
const accordion = document.querySelectorAll('[data-accordion]');

for (let i = 0; i < accordionBtn.length; i++) {

  accordionBtn[i].addEventListener('click', function () {

    const clickedBtn = this.nextElementSibling.classList.contains('active');

    for (let i = 0; i < accordion.length; i++) {

      if (clickedBtn) break;

      if (accordion[i].classList.contains('active')) {

        accordion[i].classList.remove('active');
        accordionBtn[i].classList.remove('active');

      }

    }

    this.nextElementSibling.classList.toggle('active');
    this.classList.toggle('active');

  });

}





document.querySelectorAll('.countdown').forEach(function (el) {
  const end = parseInt(el.dataset.endTime) * 1000;

  function updateCountdown() {
    const now = new Date().getTime();
    let diff = end - now;

    if (diff < 0) diff = 0;

    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
    const minutes = Math.floor((diff / (1000 * 60)) % 60);
    const seconds = Math.floor((diff / 1000) % 60);

    const spans = el.querySelectorAll('.display-number');
    spans[0].innerText = days;
    spans[1].innerText = hours;
    spans[2].innerText = minutes;
    spans[3].innerText = seconds;
  }

  updateCountdown();
  setInterval(updateCountdown, 1000);
});

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.countdown-timer').forEach(timer => {
    const endTime = parseInt(timer.getAttribute('data-end-time')) * 1000; // Chuyển sang milliseconds
    const timerId = timer.id;

    const updateCountdown = () => {
      const now = new Date().getTime();
      const distance = endTime - now;

      if (distance < 0) {
        document.getElementById(timerId).innerHTML = "HẾT HẠN";
        return;
      }

      const days = Math.floor(distance / (1000 * 60 * 60 * 24));
      const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((distance % (1000 * 60)) / 1000);

      const displayNumbers = document.getElementById(timerId).querySelectorAll('.display-number');

      displayNumbers[0].textContent = days.toString().padStart(2, '0');
      displayNumbers[1].textContent = hours.toString().padStart(2, '0');
      displayNumbers[2].textContent = minutes.toString().padStart(2, '0');
    };

    updateCountdown(); // Cập nhật ngay lập tức
    setInterval(updateCountdown, 1000); // Cập nhật mỗi giây
  });
});