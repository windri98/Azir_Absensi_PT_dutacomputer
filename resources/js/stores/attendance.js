import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import api from '../services/api';

export const useAttendanceStore = defineStore('attendance', () => {
  const attendances = ref([]);
  const currentAttendance = ref(null);
  const loading = ref(false);
  const error = ref(null);

  const fetchAttendances = async (filters = {}) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await api.get('/api/v1/attendances', { params: filters });
      attendances.value = response.data.data;
      return response.data;
    } catch (err) {
      error.value = err.message;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const fetchAttendanceById = async (id) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await api.get(`/api/v1/attendances/${id}`);
      currentAttendance.value = response.data.data;
      return response.data;
    } catch (err) {
      error.value = err.message;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const checkIn = async (data) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await api.post('/api/v1/attendances/check-in', data);
      currentAttendance.value = response.data.data;
      return response.data;
    } catch (err) {
      error.value = err.message;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const checkOut = async (data) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await api.post('/api/v1/attendances/check-out', data);
      currentAttendance.value = response.data.data;
      return response.data;
    } catch (err) {
      error.value = err.message;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const updateAttendance = async (id, data) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await api.put(`/api/v1/attendances/${id}`, data);
      const index = attendances.value.findIndex(a => a.id === id);
      if (index !== -1) {
        attendances.value[index] = response.data.data;
      }
      return response.data;
    } catch (err) {
      error.value = err.message;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const getTodayAttendance = computed(() => {
    const today = new Date().toISOString().split('T')[0];
    return attendances.value.find(a => a.date === today);
  });

  return {
    attendances,
    currentAttendance,
    loading,
    error,
    fetchAttendances,
    fetchAttendanceById,
    checkIn,
    checkOut,
    updateAttendance,
    getTodayAttendance,
  };
});
