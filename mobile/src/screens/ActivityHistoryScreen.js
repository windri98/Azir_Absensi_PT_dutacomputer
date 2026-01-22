import React, { useEffect } from 'react';
import { View, Text, FlatList, StyleSheet, TouchableOpacity, ActivityIndicator } from 'react-native';
import { useIsFocused } from '@react-navigation/native';
import { useActivityStore } from '../store/activityStore';

export const ActivityHistoryScreen = ({ navigation }) => {
  const isFocused = useIsFocused();
  const { activities, fetchActivities, isLoading } = useActivityStore();

  useEffect(() => {
    if (isFocused) {
      fetchActivities().catch(() => {});
    }
  }, [isFocused]);

  const renderItem = ({ item }) => (
    <TouchableOpacity
      style={styles.card}
      onPress={() => navigation.navigate('ActivityDetail', { id: item.id })}
    >
      <View style={styles.cardHeader}>
        <Text style={styles.title}>{item.title}</Text>
        <Text style={[styles.badge, styles[`badge_${item.status}`]]}>
          {item.status.toUpperCase()}
        </Text>
      </View>
      <Text style={styles.partner}>{item.partner?.name || '-'}</Text>
      <Text style={styles.meta}>Mulai: {item.start_time || '-'}</Text>
    </TouchableOpacity>
  );

  return (
    <View style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.headerTitle}>Riwayat Aktivitas</Text>
        <TouchableOpacity style={styles.newButton} onPress={() => navigation.navigate('ActivityCreate')}>
          <Text style={styles.newButtonText}>+ Aktivitas</Text>
        </TouchableOpacity>
      </View>

      {isLoading ? (
        <ActivityIndicator color="#0284c7" style={{ marginTop: 20 }} />
      ) : (
        <FlatList
          data={activities}
          keyExtractor={(item) => String(item.id)}
          renderItem={renderItem}
          contentContainerStyle={{ paddingBottom: 20 }}
          ListEmptyComponent={
            <Text style={styles.emptyText}>Belum ada aktivitas.</Text>
          }
        />
      )}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
    padding: 16,
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 16,
  },
  headerTitle: {
    fontSize: 18,
    fontWeight: '700',
    color: '#111827',
  },
  newButton: {
    backgroundColor: '#0284c7',
    paddingHorizontal: 12,
    paddingVertical: 8,
    borderRadius: 8,
  },
  newButtonText: {
    color: '#fff',
    fontWeight: '600',
  },
  card: {
    backgroundColor: '#fff',
    padding: 16,
    borderRadius: 12,
    marginBottom: 12,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 2,
  },
  cardHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 6,
  },
  title: {
    fontSize: 16,
    fontWeight: '700',
    color: '#111827',
  },
  partner: {
    fontSize: 13,
    color: '#6b7280',
    marginBottom: 6,
  },
  meta: {
    fontSize: 12,
    color: '#9ca3af',
  },
  badge: {
    fontSize: 10,
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 12,
    overflow: 'hidden',
    color: '#111827',
  },
  badge_pending: {
    backgroundColor: '#fef3c7',
  },
  badge_signed: {
    backgroundColor: '#dbeafe',
  },
  badge_approved: {
    backgroundColor: '#dcfce7',
  },
  badge_rejected: {
    backgroundColor: '#fee2e2',
  },
  emptyText: {
    textAlign: 'center',
    color: '#9ca3af',
    marginTop: 20,
  },
});
