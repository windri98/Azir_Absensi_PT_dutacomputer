<template>
  <aside class="w-64 bg-gray-900 text-white">
    <div class="p-6">
      <h1 class="text-2xl font-bold">PT DUTA COMPUTER</h1>
    </div>

    <nav class="mt-6">
      <router-link
        v-for="item in menuItems"
        :key="item.path"
        :to="item.path"
        class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition"
        :class="{ 'bg-gray-800 text-white': isActive(item.path) }"
      >
        <span class="mr-3">{{ item.icon }}</span>
        <span>{{ item.label }}</span>
      </router-link>
    </nav>

    <!-- Admin Section -->
    <div v-if="isAdmin" class="mt-8 border-t border-gray-700 pt-6">
      <h3 class="px-6 text-xs font-semibold text-gray-400 uppercase">Admin</h3>
      <router-link
        v-for="item in adminMenuItems"
        :key="item.path"
        :to="item.path"
        class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition"
        :class="{ 'bg-gray-800 text-white': isActive(item.path) }"
      >
        <span class="mr-3">{{ item.icon }}</span>
        <span>{{ item.label }}</span>
      </router-link>
    </div>
  </aside>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import { useAuthStore } from '../../stores/auth';

const route = useRoute();
const authStore = useAuthStore();

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
</script>
