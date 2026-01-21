# 📱 PT DUTA COMPUTER - Sistem Manajemen Absensi Karyawan

Aplikasi web untuk manajemen absensi karyawan PT DUTA COMPUTER dengan fitur role-based authentication, tracking kehadiran, complaints, dan reporting.

## ✨ Fitur Utama

- 🔐 **Multi-Role Authentication** - Admin, Manager, Employee, Supervisor
- ⏰ **Attendance Management** - Check-in/out, late detection, overtime
- 📍 **Location Tracking** - GPS-based attendance verification
- 📝 **Complaints System** - Employee complaint & response management
- 📊 **Reports & Analytics** - Comprehensive attendance reports
- 📄 **Leave Management** - Sick leave, work leave with document upload
- 👤 **Profile Management** - Photo upload, personal information

## 🚀 Quick Start

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Database setup
php artisan migrate
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=UserWithRoleSeeder

# 4. Run server
php artisan serve
```

## 👥 Default Users

| Email | Password | Role |
|-------|----------|------|
| superadmin@example.com | password123 | Super Admin |
| admin@example.com | password123 | Admin |
| hr@example.com | password123 | HR |
| manager@example.com | password123 | Manager |
| supervisor@example.com | password123 | Supervisor |
| employee1@example.com | password123 | Employee |

## 📋 Main Routes

```
/dashboard              - Main dashboard
/attendance/absensi     - Attendance page
/attendance/clock-in    - Clock in
/attendance/clock-out   - Clock out
/attendance/riwayat     - Attendance history
/complaints/form        - Submit complaint
/reports/history        - Personal reports
/admin/dashboard        - Admin panel (Admin only)
```

## 📁 Struktur Folder & Struktur Kodingan

### Struktur Folder Utama
- `app/` - inti backend (Controllers, Models, Services, Helpers, Traits)
- `routes/` - pemisahan rute per domain (`web.php`, `auth.php`, `attendance.php`, `leave.php`, `profile.php`, `reports.php`, `admin.php`, `api.php`)
- `resources/` - UI web (Blade `views/` + Vue `js/` + `css/`)
- `database/` - migrasi, seeders, factory
- `public/` - asset statis dan entry `index.php`
- `mobile/` - aplikasi mobile (React Native)
- `config/`, `storage/`, `deploy/`, `Dockerfile`, `docker-compose.yml` - konfigurasi & deployment

### Alur Kodingan (Backend)
Alur umum: `routes` → `controllers` → `services` → `models` → `views`

#### Auth
- Routes: `routes/auth.php`
- Controller: `app/Http/Controllers/AuthController.php`
- Models: `app/Models/User.php`, `app/Models/Role.php`
- Views: `resources/views/auth/*`

#### Attendance (Absensi)
- Routes: `routes/attendance.php`
- Controller: `app/Http/Controllers/AttendanceController.php`
- Service: `app/Services/AttendanceService.php`
- Models: `app/Models/Attendance.php`, `app/Models/User.php`
- Views: `resources/views/attendance/*`

#### Leave & Complaints
- Routes: `routes/leave.php`
- Controller: `app/Http/Controllers/ComplaintController.php`
- Services: `app/Services/LeaveService.php`, `app/Services/NotificationService.php`
- Models: `app/Models/Complaint.php`, `app/Models/Notification.php`, `app/Models/User.php`
- Views: `resources/views/complaints/*`, `resources/views/activities/izin.blade.php`

#### Reports
- Routes: `routes/reports.php`
- Controller: `app/Http/Controllers/ReportController.php`
- Service: `app/Services/ReportService.php`
- Models: `app/Models/Attendance.php`, `app/Models/User.php`
- Views: `resources/views/reports/*`
- Admin export: `app/Http/Controllers/Admin/ReportController.php` + `resources/views/admin/reports/*`

#### Admin & Management
- Routes: `routes/admin.php`
- Controllers: `app/Http/Controllers/Admin/*` (Dashboard, User, Role, Shift, Report, Complaint, Permission)
- Service: `app/Services/AdminDashboardService.php`
- Models: `User`, `Role`, `Shift`, `Attendance`, `Complaint`, `Permission`
- Views: `resources/views/admin/*`, `resources/views/management/*`

#### Profile
- Routes: `routes/profile.php`
- Controller: `app/Http/Controllers/ProfileController.php`
- Model: `app/Models/User.php`
- Views: `resources/views/profile/*`

#### API v1 (Mobile/Integrasi)
- Routes: `routes/api.php`
- Controllers: `app/Http/Controllers/Api/V1/*`
- Auth: Sanctum (`auth:sanctum`)
- Output: JSON (auth, user, attendance, report)

### Struktur UI Web (Frontend)
- Blade: `resources/views/*`
- Vue: `resources/js/*` (components, pages, router, stores)

### Struktur Mobile App
- Screens: `mobile/src/screens/*`
- Services: `mobile/src/services/*`
- Store: `mobile/src/store/*`

## 🔧 Configuration

**Timezone**: Set di `config/app.php`
```php
'timezone' => 'Asia/Jakarta',
```

**Work Hours**: Default 08:00 AM di `AttendanceController`

## 📚 Dokumentasi Lanjutan

- **DEPLOYMENT.md** - Panduan deployment ke production
- **CONTRIBUTING.md** - Panduan kontribusi
- **SECURITY.md** - Security policies

## 🛠️ Tech Stack

- Laravel 11.x
- MySQL
- Blade Templates
- JavaScript (Vanilla)
- Tailwind CSS

## 📄 License

[MIT License](LICENSE)

<p align="center">Built with ❤️ using Laravel</p>