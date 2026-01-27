<template>
  <!-- Desktop Sidebar (hidden on mobile) -->
  <aside class="hidden md:block w-64 bg-gray-900 text-white flex-shrink-0">
    <div class="p-4 md:p-6">
      <h1 class="text-xl md:text-2xl font-bold">PT DUTA COMPUTER</h1>
    </div>

    <nav class="mt-6">
      <router-link
        v-for="item in menuItems"
        :key="item.path"
        :to="item.path"
        class="flex items-center px-4 md:px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition"
        :class="{ 'bg-gray-800 text-white': isActive(item.path) }"
      >
        <span class="mr-3">{{ item.icon }}</span>
        <span>{{ item.label }}</span>
      </router-link>
    </nav>

    <!-- Admin Section -->
    <div v-if="isAdmin" class="mt-8 border-t border-gray-700 pt-6">
      <h3 class="px-4 md:px-6 text-xs font-semibold text-gray-400 uppercase">Admin</h3>
      <router-link
        v-for="item in adminMenuItems"
        :key="item.path"
        :to="item.path"
        class="flex items-center px-4 md:px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition"
        :class="{ 'bg-gray-800 text-white': isActive(item.path) }"
      >
        <span class="mr-3">{{ item.icon }}</span>
        <span>{{ item.label }}</span>
      </router-link>
    </div>
  </aside>

  <!-- Mobile Sidebar Drawer (visible when toggled) -->
  <div v-if="isMobileSidebarOpen" class="fixed inset-0 z-40 md:hidden">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/50" @click="closeMobileSidebar"></div>
    
    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 w-64 bg-gray-900 text-white overflow-y-auto">
      <div class="p-4 flex items-center justify-between">
        <h1 class="text-xl font-bold">PT DUTA COMPUTER</h1>
        <button @click="closeMobileSidebar" class="p-2 rounded-lg hover:bg-gray-800">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <nav class="mt-6">
        <router-link
          v-for="item in menuItems"
          :key="item.path"
          :to="item.path"
          @click="closeMobileSidebar"
          class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition"
          :class="{ 'bg-gray-800 text-white': isActive(item.path) }"
        >
          <span class="mr-3">{{ item.icon }}</span>
          <span>{{ item.label }}</span>
        </router-link>
      </nav>

      <!-- Admin Section -->
      <div v-if="isAdmin" class="mt-8 border-t border-gray-700 pt-6">
        <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase">Admin</h3>
        <router-link
          v-for="item in adminMenuItems"
          :key="item.path"
          :to="item.path"
          @click="closeMobileSidebar"
          class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition"
          :class="{ 'bg-gray-800 text-white': isActive(item.path) }"
        >
          <span class="mr-3">{{ item.icon }}</span>
          <span>{{ item.label }}</span>
        </router-link>
      </div>
    </aside>
  </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useAuthStore } from '../../stores/auth';

const route = useRoute();
const authStore = useAuthStore();
const isMobileSidebarOpen = ref(false);

const menuItems = [
  { path: '/', label: 'Dashboard', icon: '📊' },
  { path: '/attendance', label: 'Absensi', icon: '⏰' },
  { path: '/attendance/history', label: 'Riwayat', icon: '📋' },
  { path: '/reports', label: 'Laporan', icon: '📈' },
  { path: '/profile', label: 'Profil', icon: '👤' },
];

const adminMenuItems = [
  { path: '/admin', label: 'Dashboard', icon: '🏠' },
  { path: '/admin/users', label: 'Karyawan', icon: '👥' },
  { path: '/admin/roles', label: 'Role & Permission', icon: '🔐' },
  { path: '/admin/shifts', label: 'Shift', icon: '⏱️' },
  { path: '/admin/reports', label: 'Laporan', icon: '📊' },
];

const isAdmin = computed(() => {
  return authStore.user?.roles?.some(r => ['admin', 'superadmin'].includes(r.name));
});

const isActive = (path) => {
  return route.path === path || route.path.startsWith(path + '/');
};

const closeMobileSidebar = () => {
  isMobileSidebarOpen.value = false;
};

// Expose for Navbar to toggle
defineExpose({
  openMobileSidebar: () => {
    isMobileSidebarOpen.value = true;
  },
  closeMobileSidebar,
  isMobileSidebarOpen,
});

// Close sidebar when route changes
watch(() => route.path, () => {
  isMobileSidebarOpen.value = false;
});
</script>
