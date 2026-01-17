<template>
  <div id="app" class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <Navbar v-if="isAuthenticated" />

    <!-- Main Layout -->
    <div class="flex" v-if="isAuthenticated">
      <!-- Sidebar -->
      <Sidebar />

      <!-- Main Content -->
      <main class="flex-1 p-8">
        <router-view />
      </main>
    </div>

    <!-- Login Layout -->
    <div v-else>
      <router-view />
    </div>

    <!-- Toast Notifications -->
    <div class="fixed bottom-4 right-4 space-y-2 z-50">
      <div v-for="notification in notifications" :key="notification.id" 
           :class="['px-4 py-3 rounded-lg text-white shadow-lg', notificationClass(notification.type)]">
        {{ notification.message }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from './stores/auth';
import { useNotificationStore } from './stores/notification';
import Navbar from './components/common/Navbar.vue';
import Sidebar from './components/common/Sidebar.vue';

const router = useRouter();
const authStore = useAuthStore();
const notificationStore = useNotificationStore();

const isAuthenticated = computed(() => authStore.isAuthenticated);
const notifications = computed(() => notificationStore.notifications);

const notificationClass = (type) => {
  const classes = {
    success: 'bg-green-500',
    error: 'bg-red-500',
    warning: 'bg-yellow-500',
    info: 'bg-blue-500',
  };
  return classes[type] || classes.info;
};

onMounted(() => {
  authStore.checkAuth();
});
</script>

<style scoped>
/* Component styles */
</style>
