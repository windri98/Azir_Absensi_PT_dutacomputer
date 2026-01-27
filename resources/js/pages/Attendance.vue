<template>
  <div class="space-y-4 sm:space-y-6">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Absensi</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
      <!-- Check-in Card -->
      <Card title="Check-in" subtitle="Mulai hari kerja Anda">
        <div class="text-center py-6 sm:py-8">
          <div class="text-4xl sm:text-5xl md:text-6xl font-bold text-primary-600 mb-3 sm:mb-4">
            {{ currentTime }}
          </div>
          <p class="text-sm sm:text-base text-gray-600 mb-4 sm:mb-6">{{ currentDate }}</p>
          <Button
            variant="success"
            size="lg"
            @click="handleCheckIn"
            :loading="loading"
            class="w-full"
          >
            Check-in Sekarang
          </Button>
          <p v-if="todayAttendance?.check_in" class="text-xs sm:text-sm text-gray-600 mt-4">
            Sudah check-in pada {{ todayAttendance.check_in }}
          </p>
        </div>
      </Card>

      <!-- Check-out Card -->
      <Card title="Check-out" subtitle="Akhiri hari kerja Anda">
        <div class="text-center py-6 sm:py-8">
          <div class="text-4xl sm:text-5xl md:text-6xl font-bold text-red-600 mb-3 sm:mb-4">
            {{ currentTime }}
          </div>
          <p class="text-sm sm:text-base text-gray-600 mb-4 sm:mb-6">{{ currentDate }}</p>
          <Button
            variant="danger"
            size="lg"
            @click="handleCheckOut"
            :loading="loading"
            class="w-full"
            :disabled="!todayAttendance?.check_in"
          >
            Check-out Sekarang
          </Button>
          <p v-if="todayAttendance?.check_out" class="text-xs sm:text-sm text-gray-600 mt-4">
            Sudah check-out pada {{ todayAttendance.check_out }}
          </p>
          <p v-else-if="!todayAttendance?.check_in" class="text-xs sm:text-sm text-gray-600 mt-4">
            Lakukan check-in terlebih dahulu
          </p>
        </div>
      </Card>
    </div>

    <!-- Location Info -->
    <Card title="Informasi Lokasi">
      <div class="space-y-4">
        <div>
          <p class="text-sm text-gray-600">Lokasi Anda</p>
          <p class="font-medium text-gray-900">{{ location || 'Mengambil lokasi...' }}</p>
        </div>
        <Button variant="secondary" @click="getLocation">
          Perbarui Lokasi
        </Button>
      </div>
    </Card>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useAttendanceStore } from '../stores/attendance';
import { useNotificationStore } from '../stores/notification';
import Card from '../components/Card.vue';
import Button from '../components/Button.vue';

const attendanceStore = useAttendanceStore();
const notificationStore = useNotificationStore();

const currentTime = ref('00:00:00');
const currentDate = ref('');
const location = ref('');
const loading = ref(false);

const todayAttendance = computed(() => attendanceStore.getTodayAttendance);

const updateTime = () => {
  const now = new Date();
  currentTime.value = now.toLocaleTimeString('id-ID');
  currentDate.value = now.toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};

const getLocation = () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const { latitude, longitude } = position.coords;
        location.value = `${latitude.toFixed(4)}, ${longitude.toFixed(4)}`;
      },
      (error) => {
        notificationStore.error('Gagal mendapatkan lokasi: ' + error.message);
      }
    );
  } else {
    notificationStore.error('Geolocation tidak didukung oleh browser Anda');
  }
};

const handleCheckIn = async () => {
  loading.value = true;
  try {
    await attendanceStore.checkIn({
      location: location.value,
    });
    notificationStore.success('Check-in berhasil!');
  } catch (error) {
    notificationStore.error('Gagal check-in: ' + error.message);
  } finally {
    loading.value = false;
  }
};

const handleCheckOut = async () => {
  loading.value = true;
  try {
    await attendanceStore.checkOut({
      location: location.value,
    });
    notificationStore.success('Check-out berhasil!');
  } catch (error) {
    notificationStore.error('Gagal check-out: ' + error.message);
  } finally {
    loading.value = false;
  }
};

let timeInterval;

onMounted(() => {
  updateTime();
  timeInterval = setInterval(updateTime, 1000);
  getLocation();
});

onUnmounted(() => {
  clearInterval(timeInterval);
});
</script>
