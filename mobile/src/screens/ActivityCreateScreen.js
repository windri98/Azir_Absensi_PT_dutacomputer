import React, { useEffect, useRef, useState } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  Alert,
  ActivityIndicator,
  Modal,
  ScrollView,
  Image,
} from 'react-native';
import * as ImagePicker from 'expo-image-picker';
import SignatureCanvas from 'react-native-signature-canvas';
import { useActivityStore } from '../store/activityStore';
import { locationService } from '../services/location';

export const ActivityCreateScreen = ({ navigation }) => {
  const { partners, fetchPartners, createActivity, isLoading } = useActivityStore();
  const signatureRef = useRef(null);
  const [partnerId, setPartnerId] = useState(null);
  const [partnerModalVisible, setPartnerModalVisible] = useState(false);
  const [title, setTitle] = useState('');
  const [description, setDescription] = useState('');
  const [startTime, setStartTime] = useState('');
  const [endTime, setEndTime] = useState('');
  const [signatureData, setSignatureData] = useState('');
  const [signatureName, setSignatureName] = useState('');
  const [evidence, setEvidence] = useState(null);
  const [location, setLocation] = useState(null);
  const [locationLoading, setLocationLoading] = useState(false);

  useEffect(() => {
    fetchPartners().catch(() => {});
    refreshLocation();
  }, []);

  const refreshLocation = async () => {
    setLocationLoading(true);
    try {
      const loc = await locationService.getLocationWithAddress();
      setLocation(loc);
    } catch (error) {
      Alert.alert('Error', 'Gagal mengambil lokasi: ' + error.message);
    } finally {
      setLocationLoading(false);
    }
  };

  const openImagePicker = async (fromCamera = false) => {
    const permissionResult = fromCamera
      ? await ImagePicker.requestCameraPermissionsAsync()
      : await ImagePicker.requestMediaLibraryPermissionsAsync();

    if (permissionResult.status !== 'granted') {
      Alert.alert('Permission required', 'Akses kamera atau galeri diperlukan.');
      return;
    }

    const result = fromCamera
      ? await ImagePicker.launchCameraAsync({ quality: 0.7 })
      : await ImagePicker.launchImageLibraryAsync({ quality: 0.7 });

    if (result.canceled) return;

    const asset = result.assets[0];
    setEvidence({
      uri: asset.uri,
      name: asset.fileName || `evidence-${Date.now()}.jpg`,
      type: asset.mimeType || 'image/jpeg',
    });
  };

  const handleSignature = (data) => {
    setSignatureData(data);
  };

  const handleSubmit = async () => {
    if (!partnerId || !title || !signatureName || !signatureData || !evidence || !location) {
      Alert.alert('Error', 'Lengkapi semua data wajib, termasuk lokasi.');
      return;
    }

    const payload = {
      partnerId,
      title,
      description,
      startTime: startTime || new Date().toISOString(),
      endTime: endTime || null,
      signatureName,
      signatureData,
      evidence,
      latitude: location.latitude,
      longitude: location.longitude,
      locationAddress: location.address,
    };

    try {
      await createActivity(payload);
      Alert.alert('Success', 'Aktivitas berhasil dikirim');
      navigation.navigate('ActivityHistory');
    } catch (error) {
      Alert.alert('Error', error.message || 'Gagal mengirim aktivitas');
    }
  };

  const selectedPartner = partners.find((item) => item.id === partnerId);

  return (
    <ScrollView style={styles.container}>
      <View style={styles.card}>
        <Text style={styles.title}>Aktivitas Baru</Text>

        <Text style={styles.label}>Mitra *</Text>
        <TouchableOpacity style={styles.selectInput} onPress={() => setPartnerModalVisible(true)}>
          <Text style={styles.selectText}>
            {selectedPartner ? selectedPartner.name : 'Pilih Mitra'}
          </Text>
        </TouchableOpacity>

        <Text style={styles.label}>Judul *</Text>
        <TextInput style={styles.input} value={title} onChangeText={setTitle} />

        <Text style={styles.label}>Deskripsi</Text>
        <TextInput
          style={[styles.input, styles.textArea]}
          value={description}
          onChangeText={setDescription}
          multiline
        />

        <Text style={styles.label}>Waktu Mulai</Text>
        <TextInput
          style={styles.input}
          value={startTime}
          onChangeText={setStartTime}
          placeholder="2026-01-22T10:00:00"
        />

        <Text style={styles.label}>Waktu Selesai</Text>
        <TextInput
          style={styles.input}
          value={endTime}
          onChangeText={setEndTime}
          placeholder="2026-01-22T12:00:00"
        />

        <Text style={styles.label}>Foto Bukti *</Text>
        <View style={styles.row}>
          <TouchableOpacity style={styles.secondaryButton} onPress={() => openImagePicker(false)}>
            <Text style={styles.secondaryButtonText}>Pilih Galeri</Text>
          </TouchableOpacity>
          <TouchableOpacity style={styles.secondaryButton} onPress={() => openImagePicker(true)}>
            <Text style={styles.secondaryButtonText}>Ambil Foto</Text>
          </TouchableOpacity>
        </View>
        {evidence && (
          <Image source={{ uri: evidence.uri }} style={styles.previewImage} />
        )}

        <Text style={styles.label}>Nama PIC Mitra *</Text>
        <TextInput style={styles.input} value={signatureName} onChangeText={setSignatureName} />

        <Text style={styles.label}>Lokasi Aktivitas *</Text>
        <View style={styles.locationBox}>
          {locationLoading ? (
            <ActivityIndicator color="#0284c7" />
          ) : location ? (
            <>
              <Text style={styles.locationText}>{location.address}</Text>
              <Text style={styles.locationMeta}>
                {location.latitude.toFixed(5)}, {location.longitude.toFixed(5)}
              </Text>
            </>
          ) : (
            <Text style={styles.locationMeta}>Lokasi belum tersedia</Text>
          )}
        </View>
        <TouchableOpacity style={styles.secondaryButton} onPress={refreshLocation}>
          <Text style={styles.secondaryButtonText}>Perbarui Lokasi</Text>
        </TouchableOpacity>

        <Text style={styles.label}>Tanda Tangan PIC *</Text>
        <View style={styles.signatureContainer}>
          <SignatureCanvas
            ref={signatureRef}
            onOK={handleSignature}
            autoClear={false}
            descriptionText="Tanda tangan di sini"
            clearText="Hapus"
            confirmText="Simpan"
            webStyle=".m-signature-pad--footer {display:flex;}"
          />
        </View>
        <TouchableOpacity
          style={styles.secondaryButton}
          onPress={() => signatureRef.current?.clearSignature()}
        >
          <Text style={styles.secondaryButtonText}>Hapus Tanda Tangan</Text>
        </TouchableOpacity>

        <TouchableOpacity style={styles.submitButton} onPress={handleSubmit} disabled={isLoading}>
          {isLoading ? <ActivityIndicator color="#fff" /> : <Text style={styles.submitText}>Kirim</Text>}
        </TouchableOpacity>
      </View>

      <Modal visible={partnerModalVisible} transparent animationType="slide">
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <Text style={styles.modalTitle}>Pilih Mitra</Text>
            <ScrollView>
              {partners.map((partner) => (
                <TouchableOpacity
                  key={partner.id}
                  style={styles.modalItem}
                  onPress={() => {
                    setPartnerId(partner.id);
                    setPartnerModalVisible(false);
                  }}
                >
                  <Text style={styles.modalItemText}>{partner.name}</Text>
                </TouchableOpacity>
              ))}
            </ScrollView>
            <TouchableOpacity
              style={styles.secondaryButton}
              onPress={() => setPartnerModalVisible(false)}
            >
              <Text style={styles.secondaryButtonText}>Tutup</Text>
            </TouchableOpacity>
          </View>
        </View>
      </Modal>
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
  },
  card: {
    backgroundColor: '#fff',
    margin: 16,
    padding: 16,
    borderRadius: 12,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  title: {
    fontSize: 20,
    fontWeight: '700',
    color: '#111827',
    marginBottom: 16,
  },
  label: {
    fontSize: 12,
    fontWeight: '600',
    color: '#374151',
    marginTop: 12,
    marginBottom: 6,
  },
  input: {
    borderWidth: 1,
    borderColor: '#e5e7eb',
    borderRadius: 8,
    padding: 12,
    fontSize: 14,
    color: '#111827',
    backgroundColor: '#fff',
  },
  textArea: {
    height: 90,
    textAlignVertical: 'top',
  },
  selectInput: {
    borderWidth: 1,
    borderColor: '#e5e7eb',
    borderRadius: 8,
    padding: 12,
    backgroundColor: '#f9fafb',
  },
  selectText: {
    color: '#374151',
  },
  row: {
    flexDirection: 'row',
    gap: 8,
  },
  secondaryButton: {
    backgroundColor: '#f0f9ff',
    borderRadius: 8,
    paddingVertical: 10,
    paddingHorizontal: 14,
    borderWidth: 1,
    borderColor: '#0284c7',
    marginTop: 8,
  },
  secondaryButtonText: {
    color: '#0284c7',
    fontSize: 12,
    fontWeight: '600',
  },
  submitButton: {
    backgroundColor: '#0284c7',
    borderRadius: 8,
    paddingVertical: 14,
    alignItems: 'center',
    marginTop: 16,
  },
  submitText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: '600',
  },
  signatureContainer: {
    height: 220,
    borderWidth: 1,
    borderColor: '#e5e7eb',
    borderRadius: 8,
    overflow: 'hidden',
    backgroundColor: '#fff',
  },
  locationBox: {
    borderWidth: 1,
    borderColor: '#e5e7eb',
    borderRadius: 8,
    padding: 12,
    backgroundColor: '#f9fafb',
  },
  locationText: {
    fontSize: 12,
    color: '#111827',
    marginBottom: 6,
  },
  locationMeta: {
    fontSize: 11,
    color: '#6b7280',
  },
  previewImage: {
    width: '100%',
    height: 200,
    marginTop: 10,
    borderRadius: 8,
  },
  modalOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.4)',
    justifyContent: 'flex-end',
  },
  modalContent: {
    backgroundColor: '#fff',
    padding: 16,
    borderTopLeftRadius: 16,
    borderTopRightRadius: 16,
    maxHeight: '70%',
  },
  modalTitle: {
    fontSize: 16,
    fontWeight: '700',
    marginBottom: 12,
  },
  modalItem: {
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#f3f4f6',
  },
  modalItemText: {
    fontSize: 14,
    color: '#111827',
  },
});
