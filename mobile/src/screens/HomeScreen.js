import React, { useEffect, useState } from 'react';
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  StyleSheet,
  ActivityIndicator,
  RefreshControl,
} from 'react-native';
import { useAuthStore } from '../store/authStore';
import { useAttendanceStore } from '../store/attendanceStore';

export const HomeScreen = ({ navigation }) => {
  const { user } = useAuthStore();
  const { todayAttendance, fetchTodayAttendance, isLoading } = useAttendanceStore();
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    try {
      await fetchTodayAttendance();
    } catch (error) {
      console.error('Load data error:', error);
    }
  };

  const onRefresh = async () => {
    setRefreshing(true);
    try {
      await loadData();
    } finally {
      setRefreshing(false);
    }
  };

  return (
    <ScrollView
      style={styles.container}
      refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}
    >
      {/* Header */}
      <View style={styles.header}>
        <View>
          <Text style={styles.greeting}>Selamat datang,</Text>
          <Text style={styles.userName}>{user?.name}</Text>
        </View>
        <TouchableOpacity onPress={() => navigation.navigate('Profile')}>
          <View style={styles.avatar}>
            <Text style={styles.avatarText}>{user?.name?.charAt(0)}</Text>
          </View>
        </TouchableOpacity>
      </View>

      {/* Today's Status */}
      <View style={styles.statusCard}>
        <Text style={styles.statusTitle}>Status Hari Ini</Text>
        {isLoading ? (
          <ActivityIndicator color="#0284c7" />
        ) : todayAttendance ? (
          <View>
            <View style={styles.statusRow}>
              <Text style={styles.statusLabel}>Check-in:</Text>
              <Text style={styles.statusValue}>{todayAttendance.check_in || '-'}</Text>
            </View>
            <View style={styles.statusRow}>
              <Text style={styles.statusLabel}>Check-out:</Text>
              <Text style={styles.statusValue}>{todayAttendance.check_out || '-'}</Text>
            </View>
            <View style={styles.statusRow}>
              <Text style={styles.statusLabel}>Status:</Text>
              <Text style={[styles.statusValue, styles.statusBadge]}>
                {todayAttendance.status}
              </Text>
            </View>
          </View>
        ) : (
          <Text style={styles.noDataText}>Belum ada data absensi hari ini</Text>
        )}
      </View>

      {/* Quick Actions */}
      <View style={styles.actionsContainer}>
        <TouchableOpacity
          style={styles.actionButton}
          onPress={() => navigation.navigate('Attendance')}
        >
          <Text style={styles.actionIcon}>⏰</Text>
          <Text style={styles.actionLabel}>Absensi</Text>
        </TouchableOpacity>

        <TouchableOpacity
          style={styles.actionButton}
          onPress={() => navigation.navigate('History')}
        >
          <Text style={styles.actionIcon}>📋</Text>
          <Text style={styles.actionLabel}>Riwayat</Text>
        </TouchableOpacity>

        <TouchableOpacity
          style={styles.actionButton}
          onPress={() => navigation.navigate('Activities')}
        >
          <Text style={styles.actionIcon}>🛠️</Text>
          <Text style={styles.actionLabel}>Aktivitas</Text>
        </TouchableOpacity>

        <TouchableOpacity
          style={styles.actionButton}
          onPress={() => navigation.navigate('Reports')}
        >
          <Text style={styles.actionIcon}>📊</Text>
          <Text style={styles.actionLabel}>Laporan</Text>
        </TouchableOpacity>

        <TouchableOpacity
          style={styles.actionButton}
          onPress={() => navigation.navigate('Profile')}
        >
          <Text style={styles.actionIcon}>👤</Text>
          <Text style={styles.actionLabel}>Profil</Text>
        </TouchableOpacity>
      </View>

      {/* Leave Balance */}
      <View style={styles.leaveCard}>
        <Text style={styles.leaveTitle}>Saldo Cuti</Text>
        <View style={styles.leaveRow}>
          <View style={styles.leaveItem}>
            <Text style={styles.leaveLabel}>Tahunan</Text>
            <Text style={styles.leaveValue}>{user?.remaining_annual_leave || 0}</Text>
          </View>
          <View style={styles.leaveItem}>
            <Text style={styles.leaveLabel}>Sakit</Text>
            <Text style={styles.leaveValue}>{user?.remaining_sick_leave || 0}</Text>
          </View>
          <View style={styles.leaveItem}>
            <Text style={styles.leaveLabel}>Khusus</Text>
            <Text style={styles.leaveValue}>{user?.remaining_special_leave || 0}</Text>
          </View>
        </View>
      </View>
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: 20,
    backgroundColor: '#0284c7',
  },
  greeting: {
    fontSize: 14,
    color: '#fff',
    opacity: 0.8,
  },
  userName: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#fff',
    marginTop: 4,
  },
  avatar: {
    width: 50,
    height: 50,
    borderRadius: 25,
    backgroundColor: '#fff',
    justifyContent: 'center',
    alignItems: 'center',
  },
  avatarText: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#0284c7',
  },
  statusCard: {
    margin: 16,
    padding: 16,
    backgroundColor: '#fff',
    borderRadius: 12,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  statusTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: '#333',
    marginBottom: 12,
  },
  statusRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    paddingVertical: 8,
    borderBottomWidth: 1,
    borderBottomColor: '#eee',
  },
  statusLabel: {
    fontSize: 14,
    color: '#666',
  },
  statusValue: {
    fontSize: 14,
    fontWeight: '600',
    color: '#333',
  },
  statusBadge: {
    backgroundColor: '#dcfce7',
    color: '#166534',
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 4,
  },
  noDataText: {
    fontSize: 14,
    color: '#999',
    textAlign: 'center',
    paddingVertical: 20,
  },
  actionsContainer: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    padding: 8,
    justifyContent: 'space-around',
  },
  actionButton: {
    width: '22%',
    aspectRatio: 1,
    backgroundColor: '#fff',
    borderRadius: 12,
    justifyContent: 'center',
    alignItems: 'center',
    margin: 8,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  actionIcon: {
    fontSize: 32,
    marginBottom: 8,
  },
  actionLabel: {
    fontSize: 12,
    color: '#333',
    textAlign: 'center',
    fontWeight: '500',
  },
  leaveCard: {
    margin: 16,
    padding: 16,
    backgroundColor: '#fff',
    borderRadius: 12,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  leaveTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: '#333',
    marginBottom: 12,
  },
  leaveRow: {
    flexDirection: 'row',
    justifyContent: 'space-around',
  },
  leaveItem: {
    alignItems: 'center',
  },
  leaveLabel: {
    fontSize: 12,
    color: '#666',
    marginBottom: 4,
  },
  leaveValue: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#0284c7',
  },
});
