<template>
  <div class="space-y-6">
    <h1 class="text-3xl font-bold text-gray-900">Profil Saya</h1>

    <Card title="Informasi Pribadi">
      <div class="space-y-4">
        <div class="flex items-center space-x-4">
          <img :src="authStore.user?.photo || '/assets/image/default-avatar.png'" alt="Profile" class="w-20 h-20 rounded-full">
          <div>
            <h2 class="text-xl font-semibold text-gray-900">{{ authStore.user?.name }}</h2>
            <p class="text-gray-600">{{ authStore.user?.email }}</p>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
          <div>
            <p class="text-sm text-gray-600">ID Karyawan</p>
            <p class="font-medium text-gray-900">{{ authStore.user?.employee_id }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-600">Nomor Telepon</p>
            <p class="font-medium text-gray-900">{{ authStore.user?.phone || '-' }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-600">Tanggal Lahir</p>
            <p class="font-medium text-gray-900">{{ authStore.user?.birth_date || '-' }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-600">Jenis Kelamin</p>
            <p class="font-medium text-gray-900">{{ authStore.user?.gender || '-' }}</p>
          </div>
        </div>
      </div>
    </Card>

    <Card title="Alamat">
      <p class="text-gray-900">{{ authStore.user?.address || 'Belum diisi' }}</p>
    </Card>

    <Card title="Shift Kerja">
      <p class="text-gray-900">{{ authStore.user?.shift?.name || 'Belum ditentukan' }}</p>
    </Card>

    <div class="flex gap-4">
      <Button variant="primary" @click="editProfile">
        Edit Profil
      </Button>
      <Button variant="secondary" @click="changePassword">
        Ubah Password
      </Button>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '../stores/auth';
import { useNotificationStore } from '../stores/notification';
import Card from '../components/Card.vue';
import Button from '../components/Button.vue';

const authStore = useAuthStore();
const notificationStore = useNotificationStore();

const editProfile = () => {
  notificationStore.info('Fitur edit profil akan segera tersedia');
};

const changePassword = () => {
  notificationStore.info('Fitur ubah password akan segera tersedia');
};
</script>
