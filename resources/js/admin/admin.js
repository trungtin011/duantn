
console.log('admin.js loaded');
// Notification

function showToast(type, message) {
    const toast = document.getElementById(`toast-${type}`);
    const textElement = toast.querySelector(".text-sm.font-normal");

    if (textElement) {
        textElement.textContent = message;
    }

    toast.classList.remove("hidden");

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
