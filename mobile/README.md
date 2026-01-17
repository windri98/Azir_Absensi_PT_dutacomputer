# Azir Absensi - Mobile App

Aplikasi mobile untuk Sistem Manajemen Absensi Karyawan PT Duta Computer.

## Fitur

- ✅ Login dengan email dan password
- ✅ Biometric authentication (Fingerprint/Face ID)
- ✅ Check-in dan Check-out dengan GPS location
- ✅ Riwayat absensi
- ✅ Statistik kehadiran
- ✅ Offline-first architecture
- ✅ Automatic data sync
- ✅ Push notifications
- ✅ QR code scanning

## Tech Stack

- **Framework**: React Native (Expo)
- **State Management**: Zustand
- **Database**: SQLite (Offline)
- **API Client**: Axios
- **Location**: Expo Location
- **Biometric**: Expo Local Authentication
- **Notifications**: Expo Notifications

## Setup

### Prerequisites

- Node.js 16+
- npm atau yarn
- Expo CLI: `npm install -g expo-cli`

### Installation

```bash
cd mobile
npm install
```

### Environment Variables

Buat file `.env` di folder `mobile`:

```
REACT_APP_API_URL=http://your-api-url/api/v1
```

### Running

```bash
# Start development server
npm start

# Run on Android
npm run android

# Run on iOS
npm run ios

# Run on web
npm run web
```

## Project Structure

```
mobile/
├── src/
│   ├── screens/          # Screen components
│   ├── components/       # Reusable components
│   ├── services/         # API dan business logic
│   ├── store/           # Zustand stores
│   ├── utils/           # Utility functions
│   ├── hooks/           # Custom hooks
│   └── App.js           # Main app component
├── app.json             # Expo configuration
├── package.json         # Dependencies
└── README.md
```

## Services

### Authentication Service
- Login/Logout
- Token management
- User data persistence

### Attendance Service
- Check-in/Check-out
- Fetch attendance history
- Get statistics

### Location Service
- Get current location
- Reverse geocoding
- Distance calculation

### Biometric Service
- Fingerprint authentication
- Face ID authentication
- Biometric settings

### Database Service
- SQLite operations
- Offline data storage
- Sync queue management

### Sync Service
- Automatic data synchronization
- Offline action queuing
- Conflict resolution

## State Management

### Auth Store
- User authentication state
- Token management
- User profile

### Attendance Store
- Attendance data
- Today's attendance
- Statistics

## API Integration

All API calls go through the Axios instance with automatic token injection and error handling.

### Base URL
```
http://your-api-url/api/v1
```

### Authentication
```
Authorization: Bearer {token}
```

## Offline Support

Aplikasi mendukung offline-first architecture:

1. Data disimpan secara lokal di SQLite
2. Aksi offline disimpan di sync queue
3. Ketika online, data otomatis disinkronisasi
4. Conflict resolution dilakukan secara otomatis

## Permissions

### Android
- ACCESS_FINE_LOCATION
- ACCESS_COARSE_LOCATION
- CAMERA
- READ_EXTERNAL_STORAGE
- WRITE_EXTERNAL_STORAGE

### iOS
- Location (NSLocationWhenInUseUsageDescription)
- Camera (NSCameraUsageDescription)
- Face ID (NSFaceIDUsageDescription)

## Building for Production

### Android

```bash
eas build --platform android
```

### iOS

```bash
eas build --platform ios
```

## Troubleshooting

### Location not working
- Pastikan permission sudah diberikan
- Cek GPS di device settings
- Restart aplikasi

### Biometric not working
- Pastikan device memiliki biometric sensor
- Daftarkan fingerprint/face di device settings
- Restart aplikasi

### Sync not working
- Cek koneksi internet
- Cek API URL di .env
- Cek token validity

## Contributing

Silakan buat pull request untuk kontribusi.

## License

MIT License
