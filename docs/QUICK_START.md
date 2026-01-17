# Quick Start Guide - Sistem Absensi Karyawan

## 🚀 Mulai dalam 5 Menit

### Prerequisites
- PHP 8.2+
- Composer
- MySQL 8.0+
- Redis (optional, untuk caching)
- Node.js & npm (untuk frontend)

---

## 1️⃣ Setup Backend

### Step 1: Install Dependencies
```bash
cd C:\Users\windr\Desktop\Azir_Absensi_PT_dutacomputer
composer install
```

### Step 2: Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### Step 3: Configure Database
Edit `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=azir_absensi
DB_USERNAME=root
DB_PASSWORD=
```

### Step 4: Run Migrations
```bash
php artisan migrate
```

### Step 5: Seed Data
```bash
php artisan db:seed
```

### Step 6: Start Server
```bash
php artisan serve
```

Server akan berjalan di: `http://localhost:8000`

---

## 2️⃣ Setup Frontend

### Step 1: Install Dependencies
```bash
npm install
```

### Step 2: Build Frontend
```bash
npm run build
```

### Step 3: Development Mode (Optional)
```bash
npm run dev
```

---

## 3️⃣ Setup Mobile (Optional)

### Step 1: Install Dependencies
```bash
cd mobile
npm install
```

### Step 2: Start Expo
```bash
npm start
```

### Step 3: Run on Device
- Scan QR code dengan Expo Go app
- Atau: `npm run android` / `npm run ios`

---

## 🔐 Default Login Credentials

| Email | Password | Role |
|-------|----------|------|
| superadmin@example.com | password123 | Super Admin |
| admin@example.com | password123 | Admin |
| hr@example.com | password123 | HR |
| manager@example.com | password123 | Manager |
| supervisor@example.com | password123 | Supervisor |
| employee1@example.com | password123 | Employee |

---

## 📍 Main Routes

### Web Application
- **Dashboard**: http://localhost:8000/dashboard
- **Attendance**: http://localhost:8000/attendance/absensi
- **History**: http://localhost:8000/attendance/riwayat
- **Reports**: http://localhost:8000/reports/history
- **Profile**: http://localhost:8000/profile
- **Admin**: http://localhost:8000/admin/dashboard

### API Endpoints
- **Login**: `POST /api/v1/auth/login`
- **Check-in**: `POST /api/v1/attendances/check-in`
- **Check-out**: `POST /api/v1/attendances/check-out`
- **Reports**: `GET /api/v1/reports/personal`

---

## 🧪 Testing

### Run Tests
```bash
php artisan test
```

### Run Specific Test
```bash
php artisan test tests/Feature/AttendanceApiTest.php
```

### With Coverage
```bash
php artisan test --coverage
```

---

## 🔧 Useful Commands

### Cache Management
```bash
# Clear cache
php artisan cache:clear

# Warm cache
php artisan cache:warm

# Clear config
php artisan config:clear
```

### Database
```bash
# Fresh migration
php artisan migrate:fresh

# Rollback
php artisan migrate:rollback

# Seed specific seeder
php artisan db:seed --class=UserWithRoleSeeder
```

### Routes
```bash
# List all routes
php artisan route:list

# List API routes
php artisan route:list | grep api
```

---

## 📚 Documentation

- **API Docs**: `docs/api-documentation.md`
- **User Guide**: `docs/USER_GUIDE.md`
- **Security**: `docs/SECURITY.md`
- **Deployment**: `docs/DEPLOYMENT.md`
- **Testing**: `docs/TESTING.md`

---

## 🐛 Troubleshooting

### Database Connection Error
```
Error: SQLSTATE[HY000] [2002] No such file or directory
```
**Solution**: Check MySQL is running and credentials in `.env`

### Permission Denied
```
Error: Permission denied for storage/logs
```
**Solution**: 
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### Composer Error
```
Error: Your requirements could not be resolved
```
**Solution**:
```bash
composer update
composer install --no-dev
```

### Frontend Build Error
```
Error: Cannot find module
```
**Solution**:
```bash
rm -rf node_modules package-lock.json
npm install
npm run build
```

---

## 📞 Support

- **Documentation**: Check `docs/` folder
- **API Docs**: `docs/api-documentation.md`
- **User Guide**: `docs/USER_GUIDE.md`
- **Issues**: Check GitHub issues or contact support

---

## ✅ Verification Checklist

- [ ] PHP 8.2+ installed
- [ ] Composer installed
- [ ] MySQL running
- [ ] Dependencies installed (`composer install`)
- [ ] `.env` configured
- [ ] Database migrated (`php artisan migrate`)
- [ ] Data seeded (`php artisan db:seed`)
- [ ] Server running (`php artisan serve`)
- [ ] Can login with default credentials
- [ ] API endpoints responding

---

## 🎉 You're Ready!

Sistem Absensi Karyawan siap digunakan. Selamat bekerja! 🚀

---

**Last Updated**: 2026-01-18
**Version**: 1.0
