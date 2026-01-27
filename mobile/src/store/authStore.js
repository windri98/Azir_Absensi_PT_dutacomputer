import { create } from 'zustand';
import { persist, createJSONStorage } from 'zustand/middleware';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { authService } from '../services/auth';
import { notificationService } from '../services/notification';

export const useAuthStore = create(
  persist(
    (set) => ({
  user: null,
  token: null,
  isLoading: false,
  error: null,

  // Initialize auth state
  initialize: async () => {
    try {
      const token = await authService.getStoredToken();
      const user = await authService.getStoredUser();
      set({ token, user });
    } catch (error) {
      console.error('Auth initialization error:', error);
    }
  },

  // Login
  login: async (email, password) => {
    set({ isLoading: true, error: null });
    try {
      const { token, user } = await authService.login(email, password);
      set({ token, user, isLoading: false });
      try {
        const permissionGranted = await notificationService.requestPermission();
        if (permissionGranted) {
          const pushToken = await notificationService.getDevicePushToken();
          if (pushToken) {
            await authService.updatePushToken(pushToken);
          }
        }
      } catch (error) {
        console.warn('Push token registration failed:', error);
      }
      return { token, user };
    } catch (error) {
      const errorMessage = error.message || 'Login failed';
      set({ error: errorMessage, isLoading: false });
      throw error;
    }
  },

  // Logout
  logout: async () => {
    set({ isLoading: true });
    try {
      await authService.logout();
      set({ user: null, token: null, isLoading: false });
    } catch (error) {
      console.error('Logout error:', error);
      set({ isLoading: false });
    }
  },

  // Get current user
  getCurrentUser: async () => {
    set({ isLoading: true });
    try {
      const user = await authService.getCurrentUser();
      set({ user, isLoading: false });
      return user;
    } catch (error) {
      set({ error: error.message, isLoading: false });
      throw error;
    }
  },

  // Check if authenticated
  isAuthenticated: () => {
    const { token } = useAuthStore.getState();
    return !!token;
  },

  // Clear error
  clearError: () => set({ error: null }),
    }),
    {
      name: 'auth-store',
      storage: createJSONStorage(() => AsyncStorage),
      partialize: (state) => ({
        user: state.user,
        // Don't persist token - keep it in SecureStore only
      }),
    }
  )
);
