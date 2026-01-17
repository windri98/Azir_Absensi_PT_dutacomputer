<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-3xl font-bold text-gray-900">Kelola Karyawan</h1>
      <Button variant="primary" @click="openAddModal">
        + Tambah Karyawan
      </Button>
    </div>

    <Card>
      <div class="text-center py-8 text-gray-500">
        Fitur manajemen karyawan akan segera tersedia
      </div>
    </Card>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Card from '../../components/Card.vue';
import Button from '../../components/Button.vue';

const showModal = ref(false);

const openAddModal = () => {
  showModal.value = true;
};
</script>
