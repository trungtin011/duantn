// Hamburger menu
// document.addEventListener('DOMContentLoaded', function () {
//     const hamburger = document.getElementById('hamburger');
//     const sidebar = document.getElementById('sidebar');

//     hamburger.addEventListener('click', function () {
//         sidebar.classList.toggle('translate-x-0');
//         sidebar.classList.toggle('-translate-x-full');
//         sidebar.classList.toggle('hidden');
//     });

//     // Đóng sidebar khi nhấn ngoài trên mobile
//     document.addEventListener('click', function (event) {
//         if (!sidebar.contains(event.target) && !hamburger.contains(event.target) && !sidebar
//             .classList.contains('-translate-x-full')) {
//             sidebar.classList.add('-translate-x-full');
//             sidebar.classList.add('hidden');
//             sidebar.classList.remove('translate-x-0');
//         }
//     });
// });
console.log('admin.js loaded');
// Notification
document.getElementById('notification-btn').addEventListener('click', function () {
    const dropdown = document.getElementById('notification-dropdown');
    dropdown.classList.toggle('hidden');
});

// Đóng dropdown khi nhấp ra ngoài
document.addEventListener('click', function (event) {
    const dropdown = document.getElementById('notification-dropdown');
    const button = document.getElementById('notification-btn');
    if (!button.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});

function showToast(type, message) {
    const toast = document.getElementById(`toast-${type}`);
    const textElement = toast.querySelector(".text-sm.font-normal");

    if (textElement) {
        textElement.textContent = message;
    }

    toast.classList.remove("hidden");

    // Tự động ẩn sau 5 giây
    setTimeout(() => {
        toast.classList.add("hidden");
    }, 5000);
}

// Đóng thông báo khi nhấn vào nút đóng
document.querySelectorAll("[data-dismiss-target]").forEach((button) => {
    button.addEventListener("click", function () {
        const target = document.querySelector(this.getAttribute("data-dismiss-target"));
        if (target) {
            target.classList.add("hidden");
        }
    });
});
