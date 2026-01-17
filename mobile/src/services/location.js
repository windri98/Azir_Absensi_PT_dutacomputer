import * as Location from 'expo-location';

export const locationService = {
  async requestPermission() {
    try {
      const { status } = await Location.requestForegroundPermissionsAsync();
      return status === 'granted';
    } catch (error) {
      console.error('Location permission error:', error);
      return false;
    }
  },

  async getCurrentLocation() {
    try {
      const hasPermission = await this.requestPermission();
      if (!hasPermission) {
        throw new Error('Location permission denied');
      }

      const location = await Location.getCurrentPositionAsync({
        accuracy: Location.Accuracy.High,
      });

      return {
        latitude: location.coords.latitude,
        longitude: location.coords.longitude,
        accuracy: location.coords.accuracy,
        altitude: location.coords.altitude,
      };
    } catch (error) {
      console.error('Get location error:', error);
      throw error;
    }
  },

  async getAddressFromCoordinates(latitude, longitude) {
    try {
      const addresses = await Location.reverseGeocodeAsync({
        latitude,
        longitude,
      });

      if (addresses.length > 0) {
        const address = addresses[0];
        return {
          address: `${address.street}, ${address.city}, ${address.region}`,
          street: address.street,
          city: address.city,
          region: address.region,
          country: address.country,
        };
      }

      return {
        address: `${latitude}, ${longitude}`,
        street: null,
        city: null,
        region: null,
        country: null,
      };
    } catch (error) {
      console.error('Reverse geocode error:', error);
      return {
        address: `${latitude}, ${longitude}`,
        street: null,
        city: null,
        region: null,
        country: null,
      };
    }
  },

  async getLocationWithAddress() {
    try {
      const location = await this.getCurrentLocation();
      const address = await this.getAddressFromCoordinates(
        location.latitude,
        location.longitude
      );

      return {
        ...location,
        ...address,
      };
    } catch (error) {
      console.error('Get location with address error:', error);
      throw error;
    }
  },

  // Calculate distance between two coordinates (in meters)
  calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371000; // Earth's radius in meters
    const dLat = ((lat2 - lat1) * Math.PI) / 180;
    const dLon = ((lon2 - lon1) * Math.PI) / 180;
    const a =
      Math.sin(dLat / 2) * Math.sin(dLat / 2) +
      Math.cos((lat1 * Math.PI) / 180) *
        Math.cos((lat2 * Math.PI) / 180) *
        Math.sin(dLon / 2) *
        Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
  },
};
