import api from './api';

export const attendanceService = {
  async getAttendances(filters = {}) {
    try {
      const response = await api.get('/attendances', { params: filters });
      return response.data.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  async getTodayAttendance() {
    try {
      const response = await api.get('/attendances/today');
      return response.data.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  async getStatistics(month, year) {
    try {
      const response = await api.get('/attendances/statistics', {
        params: { month, year },
      });
      return response.data.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  async checkIn(location) {
    try {
      const response = await api.post('/attendances/check-in', {
        location: location.address,
        latitude: location.latitude,
        longitude: location.longitude,
      });
      return response.data.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  async checkOut(location) {
    try {
      const response = await api.post('/attendances/check-out', {
        location: location.address,
        latitude: location.latitude,
        longitude: location.longitude,
      });
      return response.data.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  async getAttendanceById(id) {
    try {
      const response = await api.get(`/attendances/${id}`);
      return response.data.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  },
};
