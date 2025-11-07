# 📁 Struktur Views yang Sudah Diorganisir

Semua file blade telah diorganisir ke dalam folder-folder berdasarkan fungsinya untuk memudahkan maintenance dan pengembangan.

## 📂 Struktur Folder

### 1. `resources/views/auth/` - Autentikasi
File yang berkaitan dengan proses login, register, dan keamanan akun.

- `login.blade.php` - Halaman login
- `register.blade.php` - Halaman registrasi
- `change-password.blade.php` - Halaman ganti password

**Routes:**
```php
Route::get('/login', ...)->name('login');
Route::get('/register', ...)->name('register');
Route::get('/change-password', ...)->name('change-password');
```

---

### 2. `resources/views/profile/` - Profil Pengguna
File yang berkaitan dengan informasi dan pengaturan profil pengguna.

- `profile.blade.php` - Halaman profil (English)
- `profil.blade.php` - Halaman profil (Indonesia)
- `edit-profile.blade.php` - Edit profil
- `profile-detail.blade.php` - Detail profil

**Routes:**
```php
Route::get('/profile', ...)->name('profile');
Route::get('/profil', ...)->name('profil');
Route::get('/edit-profile', ...)->name('edit-profile');
Route::get('/profile-detail', ...)->name('profile-detail');
```

---

### 3. `resources/views/attendance/` - Absensi & Kehadiran
File yang berkaitan dengan sistem absensi dan pencatatan kehadiran.

- `absensi.blade.php` - Halaman utama absensi
- `clock-in.blade.php` - Clock in / masuk kerja
- `clock-out.blade.php` - Clock out / pulang kerja
- `clock-overtime.blade.php` - Clock overtime / lembur
- `qr-scan.blade.php` - Scan QR code untuk absensi
- `riwayat.blade.php` - Riwayat absensi

**Routes:**
```php
Route::get('/absensi', ...)->name('absensi');
Route::get('/clock-in', ...)->name('clock-in');
Route::get('/clock-out', ...)->name('clock-out');
Route::get('/clock-overtime', ...)->name('clock-overtime');
Route::get('/qr-scan', ...)->name('qr-scan');
Route::get('/riwayat', ...)->name('riwayat');
```

---

### 4. `resources/views/reports/` - Laporan
File yang berkaitan dengan berbagai jenis laporan.

- `laporan.blade.php` - Halaman laporan utama
- `report-history.blade.php` - Riwayat laporan
- `customer-report.blade.php` - Laporan customer

**Routes:**
```php
Route::get('/laporan', ...)->name('laporan');
Route::get('/report-history', ...)->name('report-history');
Route::get('/customer-report', ...)->name('customer-report');
```

---

### 5. `resources/views/complaints/` - Keluhan
File yang berkaitan dengan sistem pengaduan dan keluhan.

- `complaint-form.blade.php` - Form pengaduan baru
- `complaint-history.blade.php` - Riwayat pengaduan
- `technician-complaints.blade.php` - Keluhan untuk teknisi

**Routes:**
```php
Route::get('/complaint-form', ...)->name('complaint-form');
Route::get('/complaint-history', ...)->name('complaint-history');
Route::get('/technician-complaints', ...)->name('technician-complaints');
```

---

### 6. `resources/views/management/` - Manajemen
File yang berkaitan dengan pengelolaan sistem dan pengaturan.

- `shift-management.blade.php` - Manajemen shift kerja
- `pengaturan.blade.php` - Pengaturan umum

**Routes:**
```php
Route::get('/shift-management', ...)->name('shift-management');
Route::get('/pengaturan', ...)->name('pengaturan');
```

---

### 7. `resources/views/pages/` - Halaman Umum
File halaman umum dan informasi yang tidak masuk kategori spesifik.

- `welcome.blade.php` - Halaman landing/welcome
- `about.blade.php` - Halaman tentang kami
- `contact.blade.php` - Halaman kontak
- `help.blade.php` - Halaman bantuan
- `dashboard.blade.php` - Dashboard utama

**Routes:**
```php
Route::get('/', ...); // welcome
Route::get('/dashboard', ...)->name('dashboard');
Route::get('/about', ...)->name('about');
Route::get('/contact', ...)->name('contact');
Route::get('/help', ...)->name('help');
```

---

### 8. `resources/views/activities/` - Aktivitas
File yang berkaitan dengan pencatatan aktivitas dan izin.

- `aktifitas.blade.php` - Halaman aktivitas
- `izin.blade.php` - Halaman pengajuan izin

**Routes:**
```php
Route::get('/aktifitas', ...)->name('aktifitas');
Route::get('/izin', ...)->name('izin');
```

---

### 9. `resources/views/settings/` - Pengaturan
File pengaturan aplikasi.

- `notification-settings.blade.php` - Pengaturan notifikasi

**Routes:**
```php
Route::get('/notification-settings', ...)->name('notification-settings');
```

---

## 🔄 Cara Menggunakan View Baru

### Di Routes (`routes/web.php`):
```php
// Sebelum
return view('login');

// Sesudah
return view('auth.login');
```

### Di Controller:
```php
// Sebelum
return view('dashboard');

// Sesudah
return view('pages.dashboard');
```

### Di Blade (redirect):
```php
// Sebelum
return redirect('/login');

// Sesudah (menggunakan named route)
return redirect()->route('login');
```

### Di Blade (route helper):
```blade
{{-- Sebelum --}}
<a href="/dashboard">Dashboard</a>

{{-- Sesudah --}}
<a href="{{ route('dashboard') }}">Dashboard</a>
```

---

## ✅ Keuntungan Struktur Baru

1. **Lebih Terorganisir** - File dikelompokkan berdasarkan fungsi
2. **Mudah Dicari** - Tidak perlu scroll panjang di folder views
3. **Scalable** - Mudah menambah file baru di kategori yang sesuai
4. **Maintainable** - Lebih mudah untuk maintenance dan debugging
5. **Team-Friendly** - Tim developer lebih mudah berkolaborasi

---

## 📝 Catatan Penting

Jika Anda sudah memiliki controller atau routes yang menggunakan view lama, pastikan untuk update semua referensi ke struktur baru.

**Cara cepat untuk mencari referensi view lama:**
```bash
# Di terminal PowerShell
cd "c:\Users\windr\Desktop\laravel kosong\laravel-kosong"
Get-ChildItem -Recurse -Include *.php | Select-String "return view\(" | Select-Object Path, LineNumber, Line
```

---

*Dokumentasi dibuat pada: November 7, 2025*
