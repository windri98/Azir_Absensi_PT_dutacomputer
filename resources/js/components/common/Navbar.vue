<template>
  <nav class="bg-white shadow-sm sticky top-0 z-30">
    <div class="px-2 sm:px-4 lg:px-8">
      <div class="flex justify-between items-center h-14 sm:h-16">
        <!-- Hamburger Menu Button (Mobile Only) -->
        <button 
          @click="handleHamburgerClick" 
          class="md:hidden p-2 rounded-lg hover:bg-gray-100"
          aria-label="Toggle sidebar"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>

        <!-- Logo -->
        <div class="flex items-center flex-1 md:flex-none">
          <span class="text-lg sm:text-2xl font-bold text-primary-600 truncate">PT DUTA</span>
        </div>

        <!-- Navigation Links (Hidden on Mobile) -->
        <div class="hidden md:flex items-center space-x-6 lg:space-x-8 mx-auto">
          <router-link to="/" class="text-gray-700 hover:text-primary-600 text-sm lg:text-base">Dashboard</router-link>
          <router-link to="/attendance" class="text-gray-700 hover:text-primary-600 text-sm lg:text-base">Absensi</router-link>
          <router-link to="/reports" class="text-gray-700 hover:text-primary-600 text-sm lg:text-base">Laporan</router-link>
          <router-link to="/profile" class="text-gray-700 hover:text-primary-600 text-sm lg:text-base">Profil</router-link>
        </div>

        <!-- User Menu -->
        <div class="flex items-center space-x-2 sm:space-x-4">
          <button @click="toggleDarkMode" class="p-2 rounded-lg hover:bg-gray-100" aria-label="Toggle dark mode">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
          </button>
          <div class="relative">
            <button @click="toggleUserMenu" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100">
              <img :src="userAvatar" alt="User" class="w-7 h-7 sm:w-8 sm:h-8 rounded-full">
              <span class="text-xs sm:text-sm font-medium hidden sm:inline truncate max-w-[100px]">{{ userName }}</span>
            </button>
            <div v-if="showUserMenu" class="absolute right-0 mt-2 w-40 sm:w-48 bg-white rounded-lg shadow-lg z-50">
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
import { ref, computed, inject } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';

const router = useRouter();
const authStore = useAuthStore();
const showUserMenu = ref(false);

const userName = computed(() => authStore.user?.name || 'User');
const userAvatar = computed(() => authStore.user?.photo || '/assets/image/default-avatar.png');

// Get sidebar ref from parent/provide
const sidebarRef = ref(null);

const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value;
};

const toggleDarkMode = () => {
  // Implement dark mode toggle
};

const handleHamburgerClick = () => {
  // Dispatch custom event that parent can listen to
  const event = new CustomEvent('openSidebar');
  window.dispatchEvent(event);
};

const logout = async () => {
  await authStore.logout();
  router.push('/login');
};

defineExpose({
  handleHamburgerClick,
});
</script>
