# 📱 Sistem Absensi Karyawan# 📱 Sistem Absensi Karyawan



Aplikasi web untuk manajemen absensi karyawan dengan fitur role-based authentication, tracking kehadiran, complaints, dan reporting.Aplikasi web untuk manajemen absensi karyawan dengan fitur role-based authentication, tracking kehadiran, complaints, dan reporting.



## ✨ Fitur Utama## ✨ Fitur Utama



- 🔐 **Multi-Role Authentication** - Admin, Manager, Employee, Supervisor- 🔐 **Multi-Role Authentication** - Admin, Manager, Employee, Supervisor

- ⏰ **Attendance Management** - Check-in/out, late detection, overtime- ⏰ **Attendance Management** - Check-in/out, late detection, overtime

- 📍 **Location Tracking** - GPS-based attendance verification- 📍 **Location Tracking** - GPS-based attendance verification

- 📝 **Complaints System** - Employee complaint & response management- 📝 **Complaints System** - Employee complaint & response management

- 📊 **Reports & Analytics** - Comprehensive attendance reports- 📊 **Reports & Analytics** - Comprehensive attendance reports

- 📄 **Leave Management** - Sick leave, work leave with document upload- 📄 **Leave Management** - Sick leave, work leave with document upload

- 👤 **Profile Management** - Photo upload, personal information- 👤 **Profile Management** - Photo upload, personal information



## 🚀 Quick Start## 🚀 Quick Start



```bash```bash

# 1. Install dependencies# 1. Install dependencies

composer installcomposer install

npm installnpm install



# 2. Setup environment# 2. Setup environment

cp .env.example .envcp .env.example .env

php artisan key:generatephp artisan key:generate



# 3. Database setup# 3. Database setup

php artisan migratephp artisan migrate

php artisan db:seed --class=RoleSeederphp artisan db:seed --class=RoleSeeder

php artisan db:seed --class=PermissionSeederphp artisan db:seed --class=PermissionSeeder

php artisan db:seed --class=UserWithRoleSeederphp artisan db:seed --class=UserWithRoleSeeder



# 4. Run server# 4. Run server

php artisan servephp artisan serve

``````



## 👥 Default Users## 👥 Default Users



| Email | Password | Role || Email | Password | Role |

|-------|----------|------||-------|----------|------|

| admin@example.com | password123 | Admin || admin@example.com | password123 | Admin |

| manager@example.com | password123 | Manager || manager@example.com | password123 | Manager |

| employee1@example.com | password123 | Employee || employee1@example.com | password123 | Employee |



## 📋 Main Routes## 📋 Main Routes



``````

/dashboard              - Main dashboard/dashboard              - Main dashboard

/attendance/absensi     - Attendance page/attendance/absensi     - Attendance page

/attendance/clock-in    - Clock in/attendance/clock-in    - Clock in

/attendance/clock-out   - Clock out/attendance/clock-out   - Clock out

/attendance/riwayat     - Attendance history/attendance/riwayat     - Attendance history

/complaints/form        - Submit complaint/complaints/form        - Submit complaint

/reports/history        - Personal reports/reports/history        - Personal reports

/admin/dashboard        - Admin panel (Admin only)/admin/dashboard        - Admin panel (Admin only)

``````



## 🔧 Configuration## 🔧 Configuration



**Timezone**: Set di `config/app.php`**Timezone**: Set di `config/app.php`

```php```php

'timezone' => 'Asia/Jakarta','timezone' => 'Asia/Jakarta',

``````



**Work Hours**: Default 08:00 AM di `AttendanceController`**Work Hours**: Default 08:00 AM di `AttendanceController`



## 📚 Dokumentasi Lanjutan## 📚 Dokumentasi Lanjutan



- **DEPLOYMENT.md** - Panduan deployment ke production- **DEPLOYMENT.md** - Panduan deployment ke production

- **CONTRIBUTING.md** - Panduan kontribusi- **CONTRIBUTING.md** - Panduan kontribusi

- **SECURITY.md** - Security policies- **SECURITY.md** - Security policies



## 🛠️ Tech Stack## 🛠️ Tech Stack



- Laravel 11.x- Laravel 11.x

- MySQL- MySQL

- Blade Templates- Blade Templates

- JavaScript (Vanilla)- JavaScript (Vanilla)

- Tailwind CSS- Tailwind CSS



## 📄 License## 📄 License



[MIT License](LICENSE)[MIT License](LICENSE)



------



<p align="center">Built with ❤️ using Laravel</p><p align="center">Built with ❤️ using Laravel</p>


## 📚 Dokumentasi Lanjutan

- **DEPLOYMENT.md** - Panduan deployment ke production
- **CONTRIBUTING.md** - Panduan kontribusi
- **SECURITY.md** - Security policies

## �️ Tech Stack

- Laravel 11.x
- MySQL
- Blade Templates
- JavaScript (Vanilla)
- Tailwind CSS

## 📄 License

[MIT License](LICENSE)

---

<p align="center">Built with ❤️ using Laravel</p>

```bash
# Via custom command (termudah)
php artisan user:assign-role

# Atau create demo users
php artisan db:seed --class=UserWithRoleSeeder
```

### 4. Setup Web Server
```bash
# Development
php artisan serve

# Production - lihat DEPLOYMENT.md untuk konfigurasi Apache/Nginx
```

## 📚 Dokumentasi Lengkap

**Baca dokumentasi lengkap di folder root:**

- 📖 **[INDEX.md](INDEX.md)** - Daftar semua dokumentasi
- ⭐ **[RINGKASAN.md](RINGKASAN.md)** - **START HERE!** Overview singkat
- 📘 **[SETUP_GUIDE.md](SETUP_GUIDE.md)** - Panduan lengkap & API docs
- ⚡ **[QUICK_REFERENCE.md](QUICK_REFERENCE.md)** - Cheatsheet
- 💻 **[COMMANDS.md](COMMANDS.md)** - Artisan commands reference

## 🗂️ Struktur Database

### Tables
- `roles` - Role management (admin, manager, employee, supervisor)
- `role_user` - Pivot table untuk user-role relationship
- `attendances` - Data absensi dengan location tracking

### Key Features
- Many-to-many relationship: User ↔ Role
- One-to-many relationship: User → Attendance
- Auto work hours calculation
- Index optimization untuk query performance

## 🎯 Roles & Permissions

| Role | Access Level | Description |
|------|-------------|-------------|
| **Admin** | Full Access | Manage semua data sistem |
| **Manager** | High Access | View & manage data karyawan |
| **Employee** | Limited | View & manage absensi sendiri |
| **Supervisor** | Medium | View team data |

## 🛣️ API Endpoints

### Attendance API
```
POST /attendance/check-in       - Check-in absensi
POST /attendance/check-out      - Check-out absensi
GET  /attendance/today-status   - Status absensi hari ini
POST /attendance/submit-leave   - Submit izin/sakit
GET  /attendance/statistics     - Statistik bulanan
```

### View Routes
```
GET /riwayat - Riwayat absensi dengan filter & pagination
```

## 💻 Usage Examples

### Check User Role in Blade
```blade
@if(Auth::user()->hasRole('admin'))
    <a href="/admin">Admin Panel</a>
@endif

@if(Auth::user()->hasAnyRole(['admin', 'manager']))
    <a href="/reports">View Reports</a>
@endif
```

### Check-In via JavaScript
```javascript
// Include attendance-helper.js
<script src="{{ asset('assets/js/attendance-helper.js') }}"></script>

// Check in
checkIn();

// Check out
checkOut();

// Get today status
getTodayStatus().then(status => {
    console.log(status);
});
```

### Protect Routes
```php
// Single role
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', ...);
});

// Multiple roles
Route::middleware(['auth', 'role:admin,manager'])->group(function () {
    Route::get('/reports', ...);
});
```

## 🛠️ Tech Stack

- **Backend:** Laravel 11.x
- **Database:** MySQL/PostgreSQL
- **Frontend:** Blade Templates
- **JavaScript:** Vanilla JS (attendance-helper.js)
- **CSS:** Custom styling

## 📦 What's Included

### Backend
- ✅ Migrations (roles, role_user, attendances)
- ✅ Models (Role, User, Attendance) with relationships
- ✅ Controller (AttendanceController) with 6 methods
- ✅ Middleware (CheckRole) for authorization
- ✅ Seeders (RoleSeeder, UserWithRoleSeeder)
- ✅ Custom Command (AssignRoleCommand)

### Frontend
- ✅ JavaScript helpers (attendance-helper.js)
- ✅ Example implementations
- ✅ Blade template examples

### Documentation
- ✅ Complete setup guide
- ✅ API documentation
- ✅ Quick reference
- ✅ Command reference

## 🔧 Configuration

### Timezone
Set timezone di `config/app.php`:
```php
'timezone' => 'Asia/Jakarta',
```

### Work Hours
Default work start time: 08:00 AM
Modify di `AttendanceController.php`:
```php
$workStartTime = Carbon::createFromTime(8, 0, 0);
```

## 🧪 Testing

### Test Check-In
```bash
# Via tinker
php artisan tinker
>>> use App\Models\Attendance;
>>> Attendance::create([
...     'user_id' => 1,
...     'date' => today(),
...     'check_in' => now(),
...     'status' => 'present'
... ]);
```

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

Contributions, issues, and feature requests are welcome!

## 👥 Author

Developed with ❤️ for efficient attendance management.

---

## 📞 Support

Baca dokumentasi lengkap:
- [INDEX.md](INDEX.md) - Daftar dokumentasi
- [SETUP_GUIDE.md](SETUP_GUIDE.md) - Panduan lengkap

---

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
