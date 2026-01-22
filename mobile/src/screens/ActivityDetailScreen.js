import React, { useEffect } from 'react';
import { View, Text, StyleSheet, ActivityIndicator, ScrollView, Image } from 'react-native';
import { useActivityStore } from '../store/activityStore';
import { activityService } from '../services/activity';

export const ActivityDetailScreen = ({ route }) => {
  const { id } = route.params;
  const { selectedActivity, fetchActivityById, isLoading } = useActivityStore();

  useEffect(() => {
    fetchActivityById(id).catch(() => {});
  }, [id]);

  if (isLoading || !selectedActivity) {
    return <ActivityIndicator style={{ marginTop: 20 }} color="#0284c7" />;
  }

  const evidenceUrl = activityService.getStorageUrl(selectedActivity.evidence_path);
  const signatureUrl = activityService.getStorageUrl(selectedActivity.signature_path);

  return (
    <ScrollView style={styles.container}>
      <View style={styles.card}>
        <Text style={styles.title}>{selectedActivity.title}</Text>
        <Text style={styles.status}>{selectedActivity.status.toUpperCase()}</Text>
        <Text style={styles.text}>{selectedActivity.description || '-'}</Text>
        <Text style={styles.meta}>Mitra: {selectedActivity.partner?.name || '-'}</Text>
        <Text style={styles.meta}>Mulai: {selectedActivity.start_time || '-'}</Text>
        <Text style={styles.meta}>Selesai: {selectedActivity.end_time || '-'}</Text>
        <Text style={styles.meta}>PIC: {selectedActivity.signature_name || '-'}</Text>
        {selectedActivity.rejected_reason && (
          <Text style={styles.reject}>Alasan: {selectedActivity.rejected_reason}</Text>
        )}
      </View>

      <View style={styles.card}>
        <Text style={styles.sectionTitle}>Bukti</Text>
        {evidenceUrl ? (
          <Image source={{ uri: evidenceUrl }} style={styles.image} />
        ) : (
          <Text style={styles.text}>Tidak ada bukti.</Text>
        )}
      </View>

      <View style={styles.card}>
        <Text style={styles.sectionTitle}>Tanda Tangan</Text>
        {signatureUrl ? (
          <Image source={{ uri: signatureUrl }} style={styles.image} />
        ) : (
          <Text style={styles.text}>Tidak ada tanda tangan.</Text>
        )}
      </View>
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
    padding: 16,
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
  title: {
    fontSize: 18,
    fontWeight: '700',
    color: '#111827',
  },
  status: {
    fontSize: 12,
    color: '#0284c7',
    marginTop: 4,
    marginBottom: 8,
  },
  text: {
    fontSize: 14,
    color: '#374151',
    marginBottom: 8,
  },
  meta: {
    fontSize: 12,
    color: '#6b7280',
    marginBottom: 4,
  },
  reject: {
    marginTop: 8,
    fontSize: 12,
    color: '#dc2626',
  },
  sectionTitle: {
    fontSize: 14,
    fontWeight: '600',
    marginBottom: 8,
  },
  image: {
    width: '100%',
    height: 220,
    borderRadius: 12,
  },
});
