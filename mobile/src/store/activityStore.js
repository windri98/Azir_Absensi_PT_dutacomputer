import { create } from 'zustand';
import { activityService } from '../services/activity';

export const useActivityStore = create((set) => ({
  partners: [],
  activities: [],
  pagination: null,
  selectedActivity: null,
  isLoading: false,
  error: null,

  fetchPartners: async (search = '') => {
    set({ isLoading: true, error: null });
    try {
      const partners = await activityService.getPartners(search);
      set({ partners, isLoading: false });
      return partners;
    } catch (error) {
      set({ error: error.message, isLoading: false });
      throw error;
    }
  },

  fetchActivities: async (filters = {}) => {
    set({ isLoading: true, error: null });
    try {
      const result = await activityService.getActivities(filters);
      set({ activities: result.data, pagination: result.pagination, isLoading: false });
      return result.data;
    } catch (error) {
      set({ error: error.message, isLoading: false });
      throw error;
    }
  },

  fetchActivityById: async (id) => {
    set({ isLoading: true, error: null });
    try {
      const activity = await activityService.getActivityById(id);
      set({ selectedActivity: activity, isLoading: false });
      return activity;
    } catch (error) {
      set({ error: error.message, isLoading: false });
      throw error;
    }
  },

  createActivity: async (payload) => {
    set({ isLoading: true, error: null });
    try {
      const activity = await activityService.createActivity(payload);
      set((state) => ({
        activities: [activity, ...state.activities],
        isLoading: false,
      }));
      return activity;
    } catch (error) {
      set({ error: error.message, isLoading: false });
      throw error;
    }
  },

  clearError: () => set({ error: null }),
}));
