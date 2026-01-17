import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import api from '../services/api';

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null);
  const token = ref(localStorage.getItem('auth_token'));
  const isAuthenticated = computed(() => !!token.value && !!user.value);

  const login = async (email, password) => {
    try {
      const response = await api.post('/api/v1/auth/login', { email, password });
      token.value = response.data.token;
      user.value = response.data.user;
      localStorage.setItem('auth_token', token.value);
      api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;
      return response.data;
    } catch (error) {
      throw error;
    }
  };

  const logout = async () => {
    try {
      await api.post('/api/v1/auth/logout');
    } catch (error) {
      console.error('Logout error:', error);
    } finally {
      token.value = null;
      user.value = null;
      localStorage.removeItem('auth_token');
      delete api.defaults.headers.common['Authorization'];
    }
  };

  const checkAuth = async () => {
    if (token.value) {
      api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;
      try {
        const response = await api.get('/api/v1/auth/me');
        user.value = response.data.user;
      } catch (error) {
        logout();
      }
    }
  };

  const hasRole = (role) => {
    return user.value?.roles?.some(r => r.name === role) || false;
  };

  const hasAnyRole = (roles) => {
    return roles.some(role => hasRole(role));
  };

  const hasPermission = (permission) => {
    return user.value?.permissions?.some(p => p.name === permission) || false;
  };

  return {
    user,
    token,
    isAuthenticated,
    login,
    logout,
    checkAuth,
    hasRole,
    hasAnyRole,
    hasPermission,
  };
});
