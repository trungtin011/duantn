import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: false
});


if(window.Laravel.user.role === 'seller') {
    console.log('Seller notification:', window.Laravel.user.role);

    window.Echo.private(`order.created.${window.Laravel.shop}`)
    .listen('.create-order.event', (e) => {
        console.log('Order created:', e);
        addNotificationToList(e);
    });

    window.Echo.channel(`shop.${window.Laravel.user.role}`)
    .listen('.seller-notification.event', (e) => {
        console.log('Seller notification:', e);
        addNotificationToList(e);
    });
    
    window.Echo.channel('notifications.all')
        .listen('.new-notification.event', (e) => { 
            console.log('Global notification:', e);
            addNotificationToList(e);
        });
}

if (window.Laravel.user.role === 'customer') {
    console.log('Customer notification:', window.Laravel.user.role);
    window.Echo.channel(`user.${window.Laravel.user.role}`)
    .listen('.customer-notification.event', (e) => {
        console.log('User notification:', e);
        addNotificationToList(e);
    });

    window.Echo.channel('notifications.all')
        .listen('.new-notification.event', (e) => { 
            console.log('Global notification:', e);
            addNotificationToList(e);
        });
}


function addNotificationToList(notification) {
    const dropdownContent = document.querySelector('.dropdown-notification-content');
    if (!dropdownContent) return;

    // Xóa thông báo "Không có thông báo mới" nếu có
    const emptyMessage = dropdownContent.querySelector('.text-center');
    if (emptyMessage) {
        emptyMessage.remove();
    }

    // Xóa thông báo trùng lặp nếu có
    const existing = dropdownContent.querySelector(
        `[data-notification-title="${escapeHtml(notification.title)}"][data-notification-type="${notification.type}"][data-notification-receiver-type="${notification.receiver_type}"]`
    );
    if (existing) {
        existing.remove();
    }

    // Tìm container chính chứa notifications
    let notificationsContainer = dropdownContent.querySelector('.p-4');
    if (!notificationsContainer) {
        // Tạo container mới nếu chưa có
        notificationsContainer = document.createElement('div');
        notificationsContainer.className = 'p-4';
        notificationsContainer.innerHTML = `
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-semibold text-gray-700">Thông báo mới</span>
                <a href="/notifications" class="text-xs text-blue-600 hover:text-blue-800">Xem tất cả</a>
            </div>
        `;
        dropdownContent.appendChild(notificationsContainer);
    }

    // Tìm hoặc tạo section cho loại thông báo
    let typeSection = notificationsContainer.querySelector(`[data-type="${notification.type}"]`);
    if (!typeSection) {
        typeSection = createNotificationTypeSection(notification.type);
        notificationsContainer.appendChild(typeSection);
    }

    // Tạo thông báo mới
    const notificationItem = createNotificationItem(notification);

    // Thêm thông báo vào đầu section
    const notificationsList = typeSection.querySelector('.notifications-list');
    if (notificationsList) {
        notificationsList.insertBefore(notificationItem, notificationsList.firstChild);
    }

    // Giới hạn số lượng thông báo hiển thị (3 thông báo mỗi loại)
    const allNotifications = typeSection.querySelectorAll('.notification-item');
    if (allNotifications.length > 3) {
        allNotifications[allNotifications.length - 1].remove();
    }

    // Cập nhật số đếm thông báo
    updateNotificationCount();
    
    // Hiển thị dropdown nếu đang ẩn
    if (dropdownContent.classList.contains('hidden')) {
        dropdownContent.classList.remove('hidden');
    }
}

function createNotificationTypeSection(type) {
    const section = document.createElement('div');
    section.className = 'mb-3';
    section.setAttribute('data-type', type);
    
    const typeInfo = getTypeInfo(type);
    
    section.innerHTML = `
        <div class="flex items-center gap-2 mb-2">
            <div class="w-2 h-2 rounded-full ${typeInfo.color}"></div>
            <h4 class="text-xs font-medium text-gray-500 uppercase">${typeInfo.label}</h4>
            <span class="text-xs text-gray-400">(1)</span>
        </div>
        <div class="notifications-list"></div>
    `;
    
    return section;
}

function createNotificationItem(notification) {
    const typeInfo = getTypeInfo(notification.type);
    const priorityBadge = notification.priority === 'high' ? 
        '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Quan trọng</span>' : '';
    
    const notificationItem = document.createElement('div');
    notificationItem.className = 'flex items-start gap-3 p-3 hover:bg-gray-50 rounded-lg cursor-pointer mb-2 bg-blue-50 border-l-4 border-blue-500 notification-item';
    notificationItem.setAttribute('data-notification-title', escapeHtml(notification.title));
    notificationItem.setAttribute('data-notification-type', notification.type);
    notificationItem.setAttribute('data-notification-receiver-type', notification.receiver_type);
    
    notificationItem.innerHTML = `
        <div class="flex-shrink-0">
            <div class="w-10 h-10 rounded-full flex items-center justify-center ${typeInfo.bgColor}">
                ${typeInfo.icon}
            </div>
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-900 truncate">${escapeHtml(notification.title)}</p>
                ${priorityBadge}
            </div>
            <p class="text-xs text-gray-500 mt-1 line-clamp-2">${escapeHtml(notification.content)}</p>
            <div class="flex items-center justify-between mt-2">
                <p class="text-xs text-gray-400">${formatDate(notification.created_at)}</p>
                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
            </div>
        </div>
    `;
    
    return notificationItem;
}

function getTypeInfo(type) {
    const typeMap = {
        'order': {
            label: 'Đơn hàng',
            color: 'bg-blue-500',
            bgColor: 'bg-blue-100',
            icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-600"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>'
        },
        'promotion': {
            label: 'Khuyến mãi',
            color: 'bg-green-500',
            bgColor: 'bg-green-100',
            icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-600"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.732.699 2.431 0l4.318-4.318c.699-.699.699-1.732 0-2.431L9.568 3z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" /></svg>'
        },
        'system': {
            label: 'Hệ thống',
            color: 'bg-purple-500',
            bgColor: 'bg-purple-100',
            icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-purple-600"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" /></svg>'
        },
        'security': {
            label: 'Bảo mật',
            color: 'bg-red-500',
            bgColor: 'bg-red-100',
            icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-600"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>'
        }
    };
    
    return typeMap[type] || {
        label: type.charAt(0).toUpperCase() + type.slice(1),
        color: 'bg-gray-500',
        bgColor: 'bg-gray-100',
        icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>'
    };
}

function updateNotificationCount() {
    const dropdownContent = document.querySelector('.dropdown-notification-content');
    if (!dropdownContent) return;

    const unreadNotifications = dropdownContent.querySelectorAll('.bg-blue-50');
    const count = unreadNotifications.length;
    
    // Tìm hoặc tạo badge số đếm
    let badge = document.querySelector('.bg-red-500.text-white.text-xs.rounded-full');
    if (count > 0) {
        if (!badge) {
            const notificationButton = document.querySelector('.dropdown-notification .flex.items-center.gap-1');
            if (notificationButton) {
                badge = document.createElement('span');
                badge.className = 'bg-red-500 text-white text-xs rounded-full px-2 py-1 min-w-[20px] text-center';
                notificationButton.appendChild(badge);
            }
        }
        if (badge) {
            badge.textContent = count > 99 ? '99+' : count.toString();
        }
    } else if (badge) {
        badge.remove();
    }
}

function escapeHtml(text) {
  return text
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
}

function formatDate(dateString) {
  const date = new Date(dateString);
  const now = new Date();
  const diffInSeconds = Math.floor((now - date) / 1000);
  
  if (diffInSeconds < 60) return 'Vừa xong';
  if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} phút trước`;
  if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} giờ trước`;
  return date.toLocaleString('vi-VN');
}

document.addEventListener('DOMContentLoaded', function() {
    const notificationButton = document.querySelector('.dropdown-notification');
    const dropdownContent = document.querySelector('.dropdown-notification-content');
    
    if (notificationButton && dropdownContent) {
        notificationButton.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownContent.classList.toggle('hidden');
        });
        
        // Đóng dropdown khi click bên ngoài
        document.addEventListener('click', function(e) {
            if (!notificationButton.contains(e.target)) {
                dropdownContent.classList.add('hidden');
            }
        });
    }
});