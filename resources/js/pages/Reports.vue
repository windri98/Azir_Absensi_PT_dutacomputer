<template>
  <div class="space-y-6">
    <h1 class="text-3xl font-bold text-gray-900">Laporan</h1>

    <Card title="Filter Laporan">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <FormInput
          v-model="filters.start_date"
          type="date"
          label="Tanggal Mulai"
        />
        <FormInput
          v-model="filters.end_date"
          type="date"
          label="Tanggal Akhir"
        />
        <div class="flex items-end gap-2">
          <Button variant="primary" @click="applyFilters">
            Terapkan Filter
          </Button>
          <Button variant="secondary" @click="exportReport">
            Export
          </Button>
        </div>
      </div>
    </Card>

    <Card title="Data Laporan">
      <div class="text-center py-8 text-gray-500">
        Fitur laporan akan segera tersedia
      </div>
    </Card>
  </div>
</template>

<script setup>
import { reactive } from 'vue';
import { useNotificationStore } from '../stores/notification';
import Card from '../components/Card.vue';
import FormInput from '../components/FormInput.vue';
import Button from '../components/Button.vue';

const notificationStore = useNotificationStore();

const filters = reactive({
  start_date: '',
  end_date: '',
});

const applyFilters = () => {
  notificationStore.info('Filter diterapkan');
};

const exportReport = () => {
  notificationStore.info('Mengekspor laporan...');
};
</script>
