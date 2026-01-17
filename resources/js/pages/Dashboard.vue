<template>
  <div class="space-y-6">
    <!-- Header -->
    <div>
      <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
      <p class="text-gray-600 mt-2">Selamat datang, {{ authStore.user?.name }}!</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <Card title="Kehadiran Hari Ini" subtitle="Status absensi">
        <div class="text-3xl font-bold text-primary-600">
          {{ todayAttendance?.status || 'Belum absen' }}
        </div>
        <p class="text-sm text-gray-600 mt-2">
          Check-in: {{ todayAttendance?.check_in || '-' }}
        </p>
      </Card>

      <Card title="Cuti Tahunan" subtitle="Sisa cuti">
        <div class="text-3xl font-bold text-blue-600">
          {{ authStore.user?.remaining_annual_leave || 0 }}
        </div>
        <p class="text-sm text-gray-600 mt-2">dari 12 hari</p>
      </Card>

      <Card title="Cuti Sakit" subtitle="Sisa cuti">
        <div class="text-3xl font-bold text-yellow-600">
          {{ authStore.user?.remaining_sick_leave || 0 }}
        </div>
        <p class="text-sm text-gray-600 mt-2">dari 12 hari</p>
      </Card>

      <Card title="Cuti Khusus" subtitle="Sisa cuti">
        <div class="text-3xl font-bold text-green-600">
          {{ authStore.user?.remaining_special_leave || 0 }}
        </div>
        <p class="text-sm text-gray-600 mt-2">dari 3 hari</p>
      </Card>
    </div>

    <!-- Quick Actions -->
    <Card title="Aksi Cepat">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <router-link to="/attendance" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
          <div class="text-2xl mb-2">⏰</div>
          <h3 class="font-semibold text-gray-900">Absensi</h3>
          <p class="text-sm text-gray-600">Check-in / Check-out</p>
        </router-link>

        <router-link to="/attendance/history" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
          <div class="text-2xl mb-2">📋</div>
          <h3 class="font-semibold text-gray-900">Riwayat</h3>
          <p class="text-sm text-gray-600">Lihat riwayat absensi</p>
        </router-link>

        <router-link to="/profile" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
          <div class="text-2xl mb-2">👤</div>
          <h3 class="font-semibold text-gray-900">Profil</h3>
          <p class="text-sm text-gray-600">Edit data pribadi</p>
        </router-link>
      </div>
    </Card>

    <!-- Recent Attendance -->
    <Card title="Absensi Terbaru" subtitle="7 hari terakhir">
      <div v-if="recentAttendances.length > 0" class="space-y-3">
        <div v-for="attendance in recentAttendances" :key="attendance.id" class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
          <div>
            <p class="font-medium text-gray-900">{{ formatDate(attendance.date) }}</p>
            <p class="text-sm text-gray-600">{{ attendance.status }}</p>
          </div>
          <div class="text-right">
            <p class="font-medium text-gray-900">{{ attendance.check_in }} - {{ attendance.check_out || '-' }}</p>
            <p class="text-sm text-gray-600">{{ attendance.work_hours }} jam</p>
          </div>
        </div>
      </div>
      <div v-else class="text-center py-8 text-gray-500">
        Belum ada data absensi
      </div>
    </Card>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useAttendanceStore } from '../stores/attendance';
import Card from '../components/Card.vue';

const authStore = useAuthStore();
const attendanceStore = useAttendanceStore();

const todayAttendance = computed(() => attendanceStore.getTodayAttendance);
const recentAttendances = computed(() => attendanceStore.attendances.slice(0, 7));

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};

onMounted(async () => {
  try {
    await attendanceStore.fetchAttendances({ limit: 7 });
  } catch (error) {
    console.error('Failed to fetch attendances:', error);
  }
});
</script>
