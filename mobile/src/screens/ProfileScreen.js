import React from 'react';
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  StyleSheet,
  Alert,
} from 'react-native';
import { useAuthStore } from '../store/authStore';

export const ProfileScreen = ({ navigation }) => {
  const { user, logout } = useAuthStore();

  const handleLogout = () => {
    Alert.alert('Logout', 'Apakah Anda yakin ingin logout?', [
      { text: 'Batal', onPress: () => {} },
      {
        text: 'Logout',
        onPress: async () => {
          await logout();
          navigation.replace('Login');
        },
      },
    ]);
  };

  return (
    <ScrollView style={styles.container}>
      {/* Profile Header */}
      <View style={styles.header}>
        <View style={styles.avatar}>
          <Text style={styles.avatarText}>{user?.name?.charAt(0)}</Text>
        </View>
        <Text style={styles.name}>{user?.name}</Text>
        <Text style={styles.email}>{user?.email}</Text>
      </View>

      {/* Personal Information */}
      <View style={styles.section}>
        <Text style={styles.sectionTitle}>Informasi Pribadi</Text>

        <View style={styles.infoRow}>
          <Text style={styles.label}>ID Karyawan</Text>
          <Text style={styles.value}>{user?.employee_id}</Text>
        </View>

        <View style={styles.infoRow}>
          <Text style={styles.label}>Nomor Telepon</Text>
          <Text style={styles.value}>{user?.phone || '-'}</Text>
        </View>

        <View style={styles.infoRow}>
          <Text style={styles.label}>Tanggal Lahir</Text>
          <Text style={styles.value}>{user?.birth_date || '-'}</Text>
        </View>

        <View style={styles.infoRow}>
          <Text style={styles.label}>Jenis Kelamin</Text>
          <Text style={styles.value}>{user?.gender || '-'}</Text>
        </View>

        <View style={styles.infoRow}>
          <Text style={styles.label}>Alamat</Text>
          <Text style={styles.value}>{user?.address || '-'}</Text>
        </View>
      </View>

      {/* Leave Balance */}
      <View style={styles.section}>
        <Text style={styles.sectionTitle}>Saldo Cuti</Text>

        <View style={styles.leaveGrid}>
          <View style={styles.leaveBox}>
            <Text style={styles.leaveLabel}>Cuti Tahunan</Text>
            <Text style={styles.leaveValue}>{user?.remaining_annual_leave || 0}</Text>
            <Text style={styles.leaveSubtext}>dari 12 hari</Text>
          </View>

          <View style={styles.leaveBox}>
            <Text style={styles.leaveLabel}>Cuti Sakit</Text>
            <Text style={styles.leaveValue}>{user?.remaining_sick_leave || 0}</Text>
            <Text style={styles.leaveSubtext}>dari 12 hari</Text>
          </View>

          <View style={styles.leaveBox}>
            <Text style={styles.leaveLabel}>Cuti Khusus</Text>
            <Text style={styles.leaveValue}>{user?.remaining_special_leave || 0}</Text>
            <Text style={styles.leaveSubtext}>dari 3 hari</Text>
          </View>
        </View>
      </View>

      {/* Roles & Permissions */}
      <View style={styles.section}>
        <Text style={styles.sectionTitle}>Role & Akses</Text>

        {user?.roles && user.roles.length > 0 ? (
          <View>
            {user.roles.map((role) => (
              <View key={role.id} style={styles.roleTag}>
                <Text style={styles.roleText}>{role.display_name}</Text>
              </View>
            ))}
          </View>
        ) : (
          <Text style={styles.noDataText}>Tidak ada role</Text>
        )}
      </View>

      {/* Actions */}
      <View style={styles.section}>
        <TouchableOpacity style={styles.actionButton}>
          <Text style={styles.actionButtonText}>Edit Profil</Text>
        </TouchableOpacity>

        <TouchableOpacity style={styles.actionButton}>
          <Text style={styles.actionButtonText}>Ubah Password</Text>
        </TouchableOpacity>

        <TouchableOpacity style={[styles.actionButton, styles.logoutButton]} onPress={handleLogout}>
          <Text style={[styles.actionButtonText, styles.logoutButtonText]}>Logout</Text>
        </TouchableOpacity>
      </View>

      <View style={styles.footer} />
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
  },
  header: {
    backgroundColor: '#0284c7',
    padding: 24,
    alignItems: 'center',
    paddingTop: 40,
  },
  avatar: {
    width: 80,
    height: 80,
    borderRadius: 40,
    backgroundColor: '#fff',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 12,
  },
  avatarText: {
    fontSize: 32,
    fontWeight: 'bold',
    color: '#0284c7',
  },
  name: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#fff',
    marginBottom: 4,
  },
  email: {
    fontSize: 14,
    color: '#fff',
    opacity: 0.8,
  },
  section: {
    backgroundColor: '#fff',
    marginTop: 12,
    padding: 16,
  },
  sectionTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: '#333',
    marginBottom: 12,
  },
  infoRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    paddingVertical: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#eee',
  },
  label: {
    fontSize: 13,
    color: '#666',
  },
  value: {
    fontSize: 13,
    fontWeight: '600',
    color: '#333',
  },
  leaveGrid: {
    flexDirection: 'row',
    justifyContent: 'space-between',
  },
  leaveBox: {
    flex: 1,
    backgroundColor: '#f0f9ff',
    borderRadius: 8,
    padding: 12,
    marginHorizontal: 4,
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
  leaveSubtext: {
    fontSize: 10,
    color: '#999',
    marginTop: 4,
  },
  roleTag: {
    backgroundColor: '#dbeafe',
    borderRadius: 6,
    paddingHorizontal: 12,
    paddingVertical: 6,
    marginBottom: 8,
    alignSelf: 'flex-start',
  },
  roleText: {
    color: '#0284c7',
    fontSize: 12,
    fontWeight: '600',
  },
  noDataText: {
    fontSize: 13,
    color: '#999',
  },
  actionButton: {
    backgroundColor: '#0284c7',
    borderRadius: 8,
    padding: 14,
    marginBottom: 10,
    alignItems: 'center',
  },
  actionButtonText: {
    color: '#fff',
    fontSize: 14,
    fontWeight: '600',
  },
  logoutButton: {
    backgroundColor: '#fff',
    borderWidth: 1,
    borderColor: '#dc2626',
  },
  logoutButtonText: {
    color: '#dc2626',
  },
  footer: {
    height: 20,
  },
});
