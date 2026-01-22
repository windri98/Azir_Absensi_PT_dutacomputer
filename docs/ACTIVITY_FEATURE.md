## Dokumentasi Fitur Aktivitas Teknisi

Fitur ini berbeda dari absensi. Aktivitas digunakan untuk mencatat pekerjaan teknisi di lokasi mitra, wajib menyertakan bukti foto dan tanda tangan PIC mitra.

## Alur Pengguna (Web)

### Karyawan/Technician
1. Buka menu `Aktivitas`.
2. Klik `Aktivitas Baru`.
3. Pilih mitra, isi judul, deskripsi, waktu mulai/selesai.
4. Upload foto bukti.
5. Isi nama PIC mitra.
6. Tanda tangan di area canvas.
7. Submit. Status awal: `signed` (sudah ditandatangani PIC, menunggu approval admin).

### Admin/HRD
1. Buka menu admin `Verifikasi Aktivitas`.
2. Buka detail aktivitas.
3. Cek bukti foto + tanda tangan.
4. Approve atau Reject (wajib alasan penolakan minimal 10 karakter).

## Alur Pengguna (Mobile)

1. Tab `Aktivitas` → daftar aktivitas.
2. Klik `+ Aktivitas`.
3. Pilih mitra.
4. Isi judul, deskripsi, waktu mulai/selesai (format ISO).
5. Upload/ambil foto bukti.
6. Isi nama PIC mitra.
7. Tanda tangan via signature pad → `Simpan` (di dalam pad).
8. Submit.

## Status Aktivitas

- `pending`: belum ditandatangani (opsional jika nanti ada step berbeda).
- `signed`: sudah ditandatangani PIC mitra, menunggu admin.
- `approved`: disetujui admin/HRD.
- `rejected`: ditolak admin/HRD.

## Storage & File

- Bukti foto: `storage/app/public/activities/evidence/*`
- Tanda tangan: `storage/app/public/activities/signatures/*`

Pastikan `php artisan storage:link` sudah dijalankan.

## Hak Akses (Permission)

Karyawan:
- `activities.create`
- `activities.view_own`
- `partners.view`

Admin/HRD:
- `activities.view_all`
- `activities.approve`
- `partners.*` (view/create/edit/delete)

## Routes Web

User:
- `GET /activities` (riwayat)
- `GET /activities/create` (form)
- `POST /activities` (submit)
- `GET /activities/{activity}` (detail)

Admin:
- `GET /admin/partners`
- `GET /admin/activities`
- `GET /admin/activities/{activity}`
- `POST /admin/activities/{activity}/approve`
- `POST /admin/activities/{activity}/reject`

## API v1 (Mobile)

Semua endpoint butuh auth `sanctum`.

- `GET /api/v1/partners`
- `GET /api/v1/activities`
- `POST /api/v1/activities` (multipart/form-data)
- `GET /api/v1/activities/{id}`

Payload `POST /activities`:
- `partner_id` (required)
- `title` (required)
- `description` (optional)
- `start_time` (required, ISO date)
- `end_time` (optional, ISO date)
- `signature_data` (required, base64 data URL)
- `signature_name` (required)
- `evidence` (required, file image)

## Mobile Dependencies

Untuk signature pad & upload foto, gunakan:
```
npm install expo-image-picker react-native-webview react-native-signature-canvas --legacy-peer-deps
```

Catatan: `react-native-signature-canvas` butuh `react-native-webview`.
