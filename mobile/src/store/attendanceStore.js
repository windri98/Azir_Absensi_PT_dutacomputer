import { create } from 'zustand';
import { attendanceService } from '../services/attendance';

export const useAttendanceStore = create((set) => ({
  attendances: [],
  todayAttendance: null,
  statistics: null,
  isLoading: false,
  error: null,

  // Fetch all attendances
  fetchAttendances: async (filters = {}) => {
    set({ isLoading: true, error: null });
    try {
      const attendances = await attendanceService.getAttendances(filters);
      set({ attendances, isLoading: false });
      return attendances;
    } catch (error) {
      set({ error: error.message, isLoading: false });
      throw error;
    }
  },

  // Fetch today's attendance
  fetchTodayAttendance: async () => {
    set({ isLoading: true, error: null });
    try {
      const todayAttendance = await attendanceService.getTodayAttendance();
      set({ todayAttendance, isLoading: false });
      return todayAttendance;
    } catch (error) {
      set({ error: error.message, isLoading: false });
      throw error;
    }
  },

  // Fetch statistics
  fetchStatistics: async (month, year) => {
    set({ isLoading: true, error: null });
    try {
      const statistics = await attendanceService.getStatistics(month, year);
      set({ statistics, isLoading: false });
      return statistics;
    } catch (error) {
      set({ error: error.message, isLoading: false });
      throw error;
    }
  },

  // Check in
  checkIn: async (location) => {
    set({ isLoading: true, error: null });
    try {
      const attendance = await attendanceService.checkIn(location);
      set({ todayAttendance: attendance, isLoading: false });
      return attendance;
    } catch (error) {
      set({ error: error.message, isLoading: false });
      throw error;
    }
  },

  // Check out
  checkOut: async (location) => {
    set({ isLoading: true, error: null });
    try {
      const attendance = await attendanceService.checkOut(location);
      set({ todayAttendance: attendance, isLoading: false });
      return attendance;
    } catch (error) {
      set({ error: error.message, isLoading: false });
      throw error;
    }
  },

  // Clear error
  clearError: () => set({ error: null }),
}));
