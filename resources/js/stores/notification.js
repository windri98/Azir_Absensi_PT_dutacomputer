import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useNotificationStore = defineStore('notification', () => {
  const notifications = ref([]);
  let notificationId = 0;

  const addNotification = (message, type = 'info', duration = 3000) => {
    const id = notificationId++;
    const notification = { id, message, type };
    
    notifications.value.push(notification);

    if (duration > 0) {
      setTimeout(() => {
        removeNotification(id);
      }, duration);
    }

    return id;
  };

  const removeNotification = (id) => {
    notifications.value = notifications.value.filter(n => n.id !== id);
  };

  const success = (message, duration = 3000) => {
    return addNotification(message, 'success', duration);
  };

  const error = (message, duration = 5000) => {
    return addNotification(message, 'error', duration);
  };

  const warning = (message, duration = 4000) => {
    return addNotification(message, 'warning', duration);
  };

  const info = (message, duration = 3000) => {
    return addNotification(message, 'info', duration);
  };

  return {
    notifications,
    addNotification,
    removeNotification,
    success,
    error,
    warning,
    info,
  };
});
