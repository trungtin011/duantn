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
    console.log(window.Laravel.shop);

    window.Echo.private(`order.created.${window.Laravel.shop}`)
    .listen('.create-order.event', (e) => {
        console.log('Order created:', e);
        addNotificationToList(e);
    });
    
    window.Echo.channel('notifications.all')
        .listen('.new-notification.event', (e) => { 
            console.log('Global notification:', e);
            addNotificationToList(e);
        });
}

if (window.Laravel.user.role === 'customer') {
    console.log('Customer notification:', window.Laravel.user.id);
    window.Echo.private(`user.${window.Laravel.user.id}`)
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
  const notificationList = document.getElementById('notification-list');
  if (!notificationList) return;

  // Xóa thông báo "Không có thông báo mới" nếu có
  const emptyMessage = notificationList.querySelector('.text-center');
  if (emptyMessage) {
      emptyMessage.remove();
  }

  // Kiểm tra xem thông báo đã tồn tại chưa
  const existing = notificationList.querySelector(
      `[data-notification-title="${escapeHtml(notification.title)}"][data-notification-type="${notification.type}"][data-notification-receiver-type="${notification.receiver_type}"]`
  );
  if (existing) {
      existing.remove();
  }

  // Tìm hoặc tạo nhóm type
  let typeGroup = notificationList.querySelector(`.notification-type[data-type="${notification.type}"]`);
  let itemsContainer;
  if (!typeGroup) {
      typeGroup = document.createElement('div');
      typeGroup.className = 'notification-type';
      typeGroup.setAttribute('data-type', notification.type);
      typeGroup.innerHTML = `
          <div class="px-4 py-2 bg-gray-50">
              <h4 class="text-xs font-medium text-gray-500 uppercase">
                  ${getTypeLabel(notification.type)}
              </h4>
          </div>
          <div class="notification-items"></div>
      `;
      notificationList.prepend(typeGroup);
      itemsContainer = typeGroup.querySelector('.notification-items');
  } else {
      itemsContainer = typeGroup.querySelector('.notification-items');
  }

  // Thêm thông báo mới
  const notificationItem = document.createElement('a');
  notificationItem.href = notification.link || '#';
  notificationItem.className = 'block px-4 py-3 hover:bg-gray-50 border-b border-gray-100';
  notificationItem.setAttribute('data-notification-id', notification.id);
  notificationItem.setAttribute('data-notification-title', escapeHtml(notification.title));
  notificationItem.setAttribute('data-notification-type', notification.type);
  notificationItem.setAttribute('data-notification-receiver-type', notification.receiver_type);
  notificationItem.innerHTML = `
      <div class="flex items-start">
          <div class="flex-shrink-0">
              <span class="inline-block h-2 w-2 rounded-full ${notification.read_at ? 'bg-gray-300' : 'bg-red-500'}"></span>
          </div>
          <div class="ml-3 w-0 flex-1">
              <p class="text-sm font-medium text-gray-900">${escapeHtml(notification.title)}</p>
              <p class="text-sm text-gray-500">${escapeHtml(notification.content)}</p>
              <p class="text-xs text-gray-400 mt-1">${formatDate(notification.created_at)}</p>
          </div>
      </div>
  `;
  itemsContainer.prepend(notificationItem);

  // Giới hạn 10 thông báo mỗi nhóm
  const items = itemsContainer.querySelectorAll('a');
  if (items.length > 10) {
      items[items.length - 1].remove();
  }

  // Cập nhật số đếm thông báo
  const notificationCount = document.getElementById('notification-count');
  if (notificationCount) {
      const totalCount = notificationList.querySelectorAll('.notification-items a').length;
      notificationCount.textContent = totalCount;
  }
}

function getTypeLabel(type) {
  switch (type) {
      case 'order':
          return 'Đơn hàng';
      case 'promotion':
          return 'Khuyến mãi';
      case 'system':
          return 'Hệ thống';
      default:
          return type;
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