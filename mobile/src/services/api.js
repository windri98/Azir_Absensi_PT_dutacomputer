import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import * as SecureStore from 'expo-secure-store';

// Use EXPO_PUBLIC_ prefix for Expo environment variables
export const API_BASE_URL = process.env.EXPO_PUBLIC_API_URL || 'http://localhost:8000/api/v1';

const api = axios.create({
  baseURL: API_BASE_URL,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Request interceptor
api.interceptors.request.use(
  async (config) => {
    try {
      const token = await SecureStore.getItemAsync('auth_token');
      if (token) {
        config.headers.Authorization = `Bearer ${token}`;
      }
    } catch (error) {
      console.warn('Error retrieving token:', error);
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response interceptor
api.interceptors.response.use(
  (response) => response,
  async (error) => {
    if (error.response?.status === 401) {
      // Token expired or invalid - trigger logout
      try {
        await SecureStore.deleteItemAsync('auth_token');
      } catch (e) {
        console.warn('Error deleting token:', e);
      }
      await AsyncStorage.removeItem('user');
      
      // Import and trigger logout in auth store
      try {
        const { useAuthStore } = require('../store/authStore');
        const authStore = useAuthStore.getState();
        if (authStore && authStore.logout) {
          authStore.logout();
        }
      } catch (e) {
        console.warn('Error triggering logout:', e);
      }
    }
    return Promise.reject(error);
  }
);

export default api;
