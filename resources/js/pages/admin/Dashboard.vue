<template>
  <div class="space-y-6">
    <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <Card title="Total Karyawan" subtitle="Aktif">
        <div class="text-3xl font-bold text-primary-600">0</div>
      </Card>
      <Card title="Hadir Hari Ini" subtitle="Kehadiran">
        <div class="text-3xl font-bold text-green-600">0</div>
      </Card>
      <Card title="Terlambat" subtitle="Hari ini">
        <div class="text-3xl font-bold text-yellow-600">0</div>
      </Card>
      <Card title="Tidak Hadir" subtitle="Hari ini">
        <div class="text-3xl font-bold text-red-600">0</div>
      </Card>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <Card title="Manajemen">
        <div class="space-y-2">
          <router-link to="/admin/users" class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
            👥 Kelola Karyawan
          </router-link>
          <router-link to="/admin/roles" class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
            🔐 Kelola Role & Permission
          </router-link>
          <router-link to="/admin/shifts" class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
            ⏰ Kelola Shift
          </router-link>
        </div>
      </Card>

      <Card title="Laporan">
        <div class="space-y-2">
          <router-link to="/admin/reports" class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
            📊 Laporan Absensi
          </router-link>
          <button class="w-full text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
            📈 Analitik
          </button>
        </div>
      </Card>
    </div>
  </div>
</template>

<script setup>
import Card from '../../components/Card.vue';
</script>
