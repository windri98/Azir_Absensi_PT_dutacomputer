# 📱 Sistem Absensi Karyawan

Aplikasi web untuk manajemen absensi karyawan dengan fitur role-based authentication, tracking kehadiran, complaints, dan reporting.

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