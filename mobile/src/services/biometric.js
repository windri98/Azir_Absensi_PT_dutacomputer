import * as LocalAuthentication from 'expo-local-authentication';
import AsyncStorage from '@react-native-async-storage/async-storage';

export const biometricService = {
  async isAvailable() {
    try {
      const compatible = await LocalAuthentication.hasHardwareAsync();
      const enrolled = await LocalAuthentication.isEnrolledAsync();
      return compatible && enrolled;
    } catch (error) {
      console.error('Biometric availability check error:', error);
      return false;
    }
  },

  async getSupportedTypes() {
    try {
      return await LocalAuthentication.supportedAuthenticationTypesAsync();
    } catch (error) {
      console.error('Get supported types error:', error);
      return [];
    }
  },

  async authenticate() {
    try {
      const isAvailable = await this.isAvailable();
      if (!isAvailable) {
        throw new Error('Biometric authentication not available');
      }

      const result = await LocalAuthentication.authenticateAsync({
        disableDeviceFallback: false,
        reason: 'Authenticate to access PT Duta  computer',
      });

      return result.success;
    } catch (error) {
      console.error('Biometric authentication error:', error);
      throw error;
    }
  },

  async enableBiometric() {
    try {
      const isAvailable = await this.isAvailable();
      if (!isAvailable) {
        throw new Error('Biometric authentication not available on this device');
      }

      const authenticated = await this.authenticate();
      if (authenticated) {
        await AsyncStorage.setItem('biometric_enabled', 'true');
        return true;
      }
      return false;
    } catch (error) {
      console.error('Enable biometric error:', error);
      throw error;
    }
  },

  async disableBiometric() {
    try {
      await AsyncStorage.removeItem('biometric_enabled');
      return true;
    } catch (error) {
      console.error('Disable biometric error:', error);
      throw error;
    }
  },

  async isBiometricEnabled() {
    try {
      const enabled = await AsyncStorage.getItem('biometric_enabled');
      return enabled === 'true';
    } catch (error) {
      console.error('Check biometric enabled error:', error);
      return false;
    }
  },

  async authenticateIfEnabled() {
    try {
      const enabled = await this.isBiometricEnabled();
      if (!enabled) {
        return true; // Biometric not enabled, allow access
      }

      return await this.authenticate();
    } catch (error) {
      console.error('Authenticate if enabled error:', error);
      return false;
    }
  },
};
