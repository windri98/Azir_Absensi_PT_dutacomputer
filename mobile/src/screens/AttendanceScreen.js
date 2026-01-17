import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
  ActivityIndicator,
  Alert,
} from 'react-native';
import { useAttendanceStore } from '../store/attendanceStore';
import { locationService } from '../services/location';
import { notificationService } from '../services/notification';

export const AttendanceScreen = () => {
  const { todayAttendance, checkIn, checkOut, isLoading } = useAttendanceStore();
  const [currentTime, setCurrentTime] = useState(new Date());
  const [location, setLocation] = useState(null);
  const [locationLoading, setLocationLoading] = useState(false);

  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentTime(new Date());
    }, 1000);

    getLocation();

    return () => clearInterval(timer);
  }, []);

  const getLocation = async () => {
    setLocationLoading(true);
    try {
      const loc = await locationService.getLocationWithAddress();
      setLocation(loc);
    } catch (error) {
      Alert.alert('Error', 'Failed to get location: ' + error.message);
    } finally {
      setLocationLoading(false);
    }
  };

  const handleCheckIn = async () => {
    if (!location) {
      Alert.alert('Error', 'Location not available');
      return;
    }

    try {
      await checkIn(location);
      await notificationService.sendLocalNotification(
        'Check-in Berhasil',
        `Anda telah check-in pada ${currentTime.toLocaleTimeString('id-ID')}`
      );
      Alert.alert('Success', 'Check-in berhasil!');
    } catch (error) {
      Alert.alert('Error', error.message || 'Check-in failed');
    }
  };

  const handleCheckOut = async () => {
    if (!location) {
      Alert.alert('Error', 'Location not available');
      return;
    }

    try {
      await checkOut(location);
      await notificationService.sendLocalNotification(
        'Check-out Berhasil',
        `Anda telah check-out pada ${currentTime.toLocaleTimeString('id-ID')}`
      );
      Alert.alert('Success', 'Check-out berhasil!');
    } catch (error) {
      Alert.alert('Error', error.message || 'Check-out failed');
    }
  };

  const timeString = currentTime.toLocaleTimeString('id-ID');
  const dateString = currentTime.toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });

  return (
    <View style={styles.container}>
      {/* Time Display */}
      <View style={styles.timeCard}>
        <Text style={styles.time}>{timeString}</Text>
        <Text style={styles.date}>{dateString}</Text>
      </View>

      {/* Check-in Card */}
      <View style={styles.actionCard}>
        <Text style={styles.cardTitle}>Check-in</Text>
        {todayAttendance?.check_in ? (
          <View style={styles.completedBox}>
            <Text style={styles.completedText}>✓ Sudah check-in</Text>
            <Text style={styles.completedTime}>{todayAttendance.check_in}</Text>
          </View>
        ) : (
          <TouchableOpacity
            style={[styles.button, styles.checkInButton, isLoading && styles.disabledButton]}
            onPress={handleCheckIn}
            disabled={isLoading}
          >
            {isLoading ? (
              <ActivityIndicator color="#fff" />
            ) : (
              <Text style={styles.buttonText}>Check-in Sekarang</Text>
            )}
          </TouchableOpacity>
        )}
      </View>

      {/* Check-out Card */}
      <View style={styles.actionCard}>
        <Text style={styles.cardTitle}>Check-out</Text>
        {todayAttendance?.check_out ? (
          <View style={styles.completedBox}>
            <Text style={styles.completedText}>✓ Sudah check-out</Text>
            <Text style={styles.completedTime}>{todayAttendance.check_out}</Text>
          </View>
        ) : (
          <TouchableOpacity
            style={[
              styles.button,
              styles.checkOutButton,
              (!todayAttendance?.check_in || isLoading) && styles.disabledButton,
            ]}
            onPress={handleCheckOut}
            disabled={!todayAttendance?.check_in || isLoading}
          >
            {isLoading ? (
              <ActivityIndicator color="#fff" />
            ) : (
              <Text style={styles.buttonText}>Check-out Sekarang</Text>
            )}
          </TouchableOpacity>
        )}
      </View>

      {/* Location Info */}
      <View style={styles.locationCard}>
        <Text style={styles.cardTitle}>Lokasi</Text>
        {locationLoading ? (
          <ActivityIndicator color="#0284c7" />
        ) : location ? (
          <View>
            <Text style={styles.locationText}>{location.address}</Text>
            <Text style={styles.coordinatesText}>
              {location.latitude.toFixed(4)}, {location.longitude.toFixed(4)}
            </Text>
            <TouchableOpacity style={styles.refreshButton} onPress={getLocation}>
              <Text style={styles.refreshButtonText}>Perbarui Lokasi</Text>
            </TouchableOpacity>
          </View>
        ) : (
          <Text style={styles.errorText}>Lokasi tidak tersedia</Text>
        )}
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
    padding: 16,
  },
  timeCard: {
    backgroundColor: '#0284c7',
    borderRadius: 12,
    padding: 24,
    alignItems: 'center',
    marginBottom: 20,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  time: {
    fontSize: 48,
    fontWeight: 'bold',
    color: '#fff',
  },
  date: {
    fontSize: 14,
    color: '#fff',
    marginTop: 8,
    opacity: 0.9,
  },
  actionCard: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 16,
    marginBottom: 16,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  cardTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: '#333',
    marginBottom: 12,
  },
  button: {
    padding: 14,
    borderRadius: 8,
    alignItems: 'center',
  },
  checkInButton: {
    backgroundColor: '#16a34a',
  },
  checkOutButton: {
    backgroundColor: '#dc2626',
  },
  disabledButton: {
    opacity: 0.5,
  },
  buttonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: '600',
  },
  completedBox: {
    backgroundColor: '#dcfce7',
    borderRadius: 8,
    padding: 12,
    alignItems: 'center',
  },
  completedText: {
    color: '#166534',
    fontSize: 14,
    fontWeight: '600',
  },
  completedTime: {
    color: '#166534',
    fontSize: 12,
    marginTop: 4,
  },
  locationCard: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 16,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  locationText: {
    fontSize: 14,
    color: '#333',
    marginBottom: 8,
  },
  coordinatesText: {
    fontSize: 12,
    color: '#666',
    fontFamily: 'monospace',
    marginBottom: 12,
  },
  refreshButton: {
    backgroundColor: '#f0f9ff',
    borderColor: '#0284c7',
    borderWidth: 1,
    borderRadius: 8,
    padding: 10,
    alignItems: 'center',
  },
  refreshButtonText: {
    color: '#0284c7',
    fontSize: 14,
    fontWeight: '600',
  },
  errorText: {
    color: '#dc2626',
    fontSize: 14,
    textAlign: 'center',
  },
});
