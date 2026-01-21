## Panduan Deploy (AaPanel + Docker Compose)

Dokumen ini fokus untuk deploy aplikasi di server AaPanel menggunakan Docker
Compose sesuai konfigurasi repo ini.

### 1) Prasyarat
- AaPanel terpasang di server.
- Docker dan Docker Compose terpasang (via App Store AaPanel).
- Domain sudah dibuat di AaPanel (Website -> Add site).
- Folder deploy contoh: `/home/wwwroot/dutacomputer`.

### 2) Upload project ke server
Upload semua file repo ke folder deploy, contoh:
```
/home/wwwroot/dutacomputer
```

### 3) Siapkan file `.env`
Copy dari `.env example` lalu sesuaikan nilainya:
```
cp .env\ example .env
```

Pastikan minimal variabel ini terisi:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://domain-kamu`
- `APP_KEY=base64:...` (WAJIB, kalau kosong akan error 500)
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `DB_ROOT_PASSWORD`
- `REDIS_PASSWORD`

Jika `APP_KEY` belum ada, generate di server:
```
docker-compose exec app php artisan key:generate
```

### 4) Jalankan container
Masuk ke folder project lalu build dan jalankan:
```
cd /home/wwwroot/dutacomputer
docker-compose up -d --build
```

### 5) Migrasi database dan cache
Jalankan perintah ini setelah container berjalan:
```
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

### 6) Konfigurasi Nginx di AaPanel
Gunakan template di `nginx.conf.template`:
1. Ganti `YOUR_DOMAIN` dengan domain kamu.
2. Ganti `CONTAINER_IP` dengan IP container `app`.

Cara cek IP container:
```
docker inspect dutacomputer-app | grep IPAddress
```

Letakkan konfigurasi di Website -> Config (AaPanel), lalu reload Nginx.

### 7) Cek status & log
Cek status container:
```
docker-compose ps
```

Lihat log error aplikasi:
```
docker-compose logs --tail=100 app
```

### 8) Update aplikasi
Untuk update versi terbaru:
```
cd /home/wwwroot/dutacomputer
git pull origin main
docker-compose up -d --build
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

### Catatan penting
- Jika masih 500, cek log container dan file log Laravel.
- Pastikan folder `storage` dan `bootstrap/cache` writable.
- Jangan lupa isi `APP_KEY` dan `DB_PASSWORD` dengan benar.
