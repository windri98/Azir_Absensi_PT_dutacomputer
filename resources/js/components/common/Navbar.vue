<template>
  <nav class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <!-- Logo -->
        <div class="flex items-center">
          <div class="flex-shrink-0 flex items-center">
            <span class="text-2xl font-bold text-primary-600">Absensiku</span>
          </div>
        </div>

        <!-- Navigation Links -->
        <div class="hidden md:flex items-center space-x-8">
          <router-link to="/" class="text-gray-700 hover:text-primary-600">Dashboard</router-link>
          <router-link to="/attendance" class="text-gray-700 hover:text-primary-600">Absensi</router-link>
          <router-link to="/reports" class="text-gray-700 hover:text-primary-600">Laporan</router-link>
          <router-link to="/profile" class="text-gray-700 hover:text-primary-600">Profil</router-link>
        </div>

        <!-- User Menu -->
        <div class="flex items-center space-x-4">
          <button @click="toggleDarkMode" class="p-2 rounded-lg hover:bg-gray-100">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
          </button>
          <div class="relative">
            <button @click="toggleUserMenu" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100">
              <img :src="userAvatar" alt="User" class="w-8 h-8 rounded-full">
              <span class="text-sm font-medium hidden sm:inline">{{ userName }}</span>
            </button>
            <div v-if="showUserMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10">
              <router-link to="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</router-link>
              <button @click="logout" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';

const router = useRouter();
const authStore = useAuthStore();
const showUserMenu = ref(false);

const userName = computed(() => authStore.user?.name || 'User');
const userAvatar = computed(() => authStore.user?.photo || '/assets/image/default-avatar.png');

const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value;
};

const toggleDarkMode = () => {
  // Implement dark mode toggle
};

const logout = async () => {
  await authStore.logout();
  router.push('/login');
};
</script>
