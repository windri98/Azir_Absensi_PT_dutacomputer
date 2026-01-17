import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  StyleSheet,
  ActivityIndicator,
} from 'react-native';
import { useAttendanceStore } from '../store/attendanceStore';

export const ReportsScreen = () => {
  const { statistics, fetchStatistics, isLoading } = useAttendanceStore();
  const [month, setMonth] = useState(new Date().getMonth() + 1);
  const [year, setYear] = useState(new Date().getFullYear());

  useEffect(() => {
    loadStatistics();
  }, [month, year]);

  const loadStatistics = async () => {
    try {
      await fetchStatistics(month, year);
    } catch (error) {
      console.error('Load statistics error:', error);
    }
  };

  const handlePreviousMonth = () => {
    if (month === 1) {
      setMonth(12);
      setYear(year - 1);
    } else {
      setMonth(month - 1);
    }
  };

  const handleNextMonth = () => {
    if (month === 12) {
      setMonth(1);
      setYear(year + 1);
    } else {
      setMonth(month + 1);
    }
  };

  const monthName = new Date(year, month - 1).toLocaleDateString('id-ID', {
    month: 'long',
    year: 'numeric',
  });

  return (
    <ScrollView style={styles.container}>
      {/* Month Selector */}
      <View style={styles.monthSelector}>
        <TouchableOpacity onPress={handlePreviousMonth}>
          <Text style={styles.arrowButton}>‹</Text>
        </TouchableOpacity>
        <Text style={styles.monthText}>{monthName}</Text>
        <TouchableOpacity onPress={handleNextMonth}>
          <Text style={styles.arrowButton}>›</Text>
        </TouchableOpacity>
      </View>

      {/* Statistics */}
      {isLoading ? (
        <View style={styles.centerContainer}>
          <ActivityIndicator size="large" color="#0284c7" />
        </View>
      ) : statistics ? (
        <View>
          {/* Summary Cards */}
          <View style={styles.summaryGrid}>
            <View style={styles.summaryCard}>
              <Text style={styles.summaryLabel}>Total Hari</Text>
              <Text style={styles.summaryValue}>{statistics.total_days}</Text>
            </View>

            <View style={styles.summaryCard}>
              <Text style={styles.summaryLabel}>Hadir</Text>
              <Text style={[styles.summaryValue, styles.presentColor]}>
                {statistics.present}
              </Text>
            </View>

            <View style={styles.summaryCard}>
              <Text style={styles.summaryLabel}>Terlambat</Text>
              <Text style={[styles.summaryValue, styles.lateColor]}>
                {statistics.late}
              </Text>
            </View>

            <View style={styles.summaryCard}>
              <Text style={styles.summaryLabel}>Tidak Hadir</Text>
              <Text style={[styles.summaryValue, styles.absentColor]}>
                {statistics.absent}
              </Text>
            </View>
          </View>

          {/* Work Hours */}
          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Jam Kerja</Text>

            <View style={styles.infoRow}>
              <Text style={styles.label}>Total Jam Kerja</Text>
              <Text style={styles.value}>{statistics.total_work_hours} jam</Text>
            </View>

            <View style={styles.infoRow}>
              <Text style={styles.label}>Total Overtime</Text>
              <Text style={styles.value}>{statistics.total_overtime_hours} jam</Text>
            </View>
          </View>

          {/* Details */}
          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Detail</Text>

            <View style={styles.detailRow}>
              <View style={styles.detailItem}>
                <Text style={styles.detailLabel}>Cuti Kerja</Text>
                <Text style={styles.detailValue}>{statistics.work_leave}</Text>
              </View>
            </View>
          </View>
        </View>
      ) : (
        <View style={styles.centerContainer}>
          <Text style={styles.emptyText}>Belum ada data laporan</Text>
        </View>
      )}
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
  },
  centerContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    minHeight: 300,
  },
  monthSelector: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: '#0284c7',
    padding: 16,
  },
  arrowButton: {
    fontSize: 28,
    color: '#fff',
    fontWeight: 'bold',
  },
  monthText: {
    fontSize: 16,
    fontWeight: '600',
    color: '#fff',
  },
  summaryGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    padding: 8,
    justifyContent: 'space-between',
  },
  summaryCard: {
    width: '48%',
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 16,
    margin: 8,
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  summaryLabel: {
    fontSize: 12,
    color: '#666',
    marginBottom: 8,
  },
  summaryValue: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#333',
  },
  presentColor: {
    color: '#16a34a',
  },
  lateColor: {
    color: '#ea580c',
  },
  absentColor: {
    color: '#dc2626',
  },
  section: {
    backgroundColor: '#fff',
    marginHorizontal: 8,
    marginVertical: 8,
    borderRadius: 12,
    padding: 16,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
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
  detailRow: {
    flexDirection: 'row',
    justifyContent: 'space-around',
  },
  detailItem: {
    alignItems: 'center',
  },
  detailLabel: {
    fontSize: 12,
    color: '#666',
    marginBottom: 4,
  },
  detailValue: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#0284c7',
  },
  emptyText: {
    fontSize: 16,
    color: '#999',
  },
});
