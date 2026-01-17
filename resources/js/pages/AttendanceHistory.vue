<template>
  <div class="space-y-6">
    <h1 class="text-3xl font-bold text-gray-900">Riwayat Absensi</h1>

    <Card>
      <div class="space-y-4">
        <div v-if="attendanceStore.loading" class="text-center py-8">
          <p class="text-gray-600">Memuat data...</p>
        </div>
        <div v-else-if="attendanceStore.attendances.length > 0" class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Tanggal</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Check-in</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Check-out</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Status</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-900">Jam Kerja</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="attendance in attendanceStore.attendances" :key="attendance.id" class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-900">{{ formatDate(attendance.date) }}</td>
                <td class="px-4 py-3 text-sm text-gray-600">{{ attendance.check_in || '-' }}</td>
                <td class="px-4 py-3 text-sm text-gray-600">{{ attendance.check_out || '-' }}</td>
                <td class="px-4 py-3 text-sm">
                  <span :class="['px-2 py-1 rounded-full text-xs font-medium', statusClass(attendance.status)]">
                    {{ attendance.status }}
                  </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">{{ attendance.work_hours }} jam</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-else class="text-center py-8 text-gray-500">
          Belum ada data absensi
        </div>
      </div>
    </Card>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useAttendanceStore } from '../stores/attendance';
import Card from '../components/Card.vue';

const attendanceStore = useAttendanceStore();

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID', {
    weekday: 'short',
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
};

const statusClass = (status) => {
  const classes = {
    present: 'bg-green-100 text-green-800',
    late: 'bg-yellow-100 text-yellow-800',
    absent: 'bg-red-100 text-red-800',
    work_leave: 'bg-blue-100 text-blue-800',
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

onMounted(async () => {
  try {
    await attendanceStore.fetchAttendances();
  } catch (error) {
    console.error('Failed to fetch attendances:', error);
  }
});
</script>
