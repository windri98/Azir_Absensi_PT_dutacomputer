import api from './api';
import AsyncStorage from '@react-native-async-storage/async-storage';
import * as SecureStore from 'expo-secure-store';

export const authService = {
  async login(email, password) {
    try {
      const response = await api.post('/auth/login', { email, password });
      const { token, user } = response.data.data;

      // Store token securely using SecureStore
      await SecureStore.setItemAsync('auth_token', token);
      
      // Store user data in AsyncStorage (not sensitive)
      await AsyncStorage.setItem('user', JSON.stringify(user));

      return { token, user };
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  async logout() {
    try {
      await api.post('/auth/logout');
    } catch (error) {
      console.error('Logout error:', error);
    } finally {
      // Clear stored data
      try {
        await SecureStore.deleteItemAsync('auth_token');
      } catch (error) {
        console.warn('Error deleting token from SecureStore:', error);
      }
      await AsyncStorage.removeItem('user');
    }
  },

  async updatePushToken(token) {
    if (!token) return;
    try {
      await api.post('/users/push-token', { token });
    } catch (error) {
      console.error('Update push token error:', error);
      throw error.response?.data || error;
    }
  },

  async getCurrentUser() {
    try {
      const response = await api.get('/auth/me');
      const user = response.data.data.user;
      await AsyncStorage.setItem('user', JSON.stringify(user));
      return user;
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  async getStoredUser() {
    const userJson = await AsyncStorage.getItem('user');
    return userJson ? JSON.parse(userJson) : null;
  },

  async getStoredToken() {
    try {
      return await SecureStore.getItemAsync('auth_token');
    } catch (error) {
      console.warn('Error retrieving token from SecureStore:', error);
      return null;
    }
  },

  async isAuthenticated() {
    const token = await this.getStoredToken();
    return !!token;
  },
};
