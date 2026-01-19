# Panduan Deploy PT DUTA COMPUTER ke AaPanel dengan Docker Compose

Dokumentasi lengkap untuk deploy aplikasi PT DUTA COMPUTER menggunakan Docker Compose di AaPanel.

## 📋 Daftar Isi

1. [Prerequisites](#prerequisites)
2. [Setup Docker di AaPanel](#setup-docker-di-aapanel)
3. [Persiapan Project](#persiapan-project)
4. [Deploy dengan Docker Compose](#deploy-dengan-docker-compose)
5. [Konfigurasi Nginx Reverse Proxy](#konfigurasi-nginx-reverse-proxy)
6. [SSL Certificate dengan Certbot](#ssl-certificate-dengan-certbot)
7. [Troubleshooting](#troubleshooting)
8. [Monitoring & Maintenance](#monitoring--maintenance)

---

## Prerequisites

### Persyaratan Server
- Ubuntu 20.04 LTS atau lebih baru (Rekomendasi: Ubuntu 22.04)
- Minimal 2GB RAM (Rekomendasi: 4GB untuk production)
- Minimal 20GB Storage (untuk database + logs)
- IP address publik (untuk akses dari internet)
- Root atau sudo access ke server

### Software yang Diperlukan
- AaPanel sudah terinstall
- Docker (akan diinstall)
- Docker Compose (akan diinstall)
- Git (untuk clone repository)

---

## Setup Docker di AaPanel

### Step 1: Install Docker

Jalankan perintah berikut via terminal SSH:

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Tambah user ke docker group
sudo usermod -aG docker $USER
newgrp docker

# Verifikasi instalasi
docker --version
docker run hello-world
```

### Step 2: Install Docker Compose

```bash
# Download Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose

# Buat executable
sudo chmod +x /usr/local/bin/docker-compose

# Verifikasi instalasi
docker-compose --version
```

### Step 3: Start Docker Service

```bash
# Enable Docker service to start on boot
sudo systemctl enable docker

# Start Docker service
sudo systemctl start docker

# Check status
sudo systemctl status docker
```

---

## Persiapan Project

### Step 1: Clone Repository

```bash
# Navigate ke direktori deploy (misal: /home/wwwroot)
cd /home/wwwroot

# Clone repository
git clone https://github.com/your-username/pt-duta-computer.git dutacomputer
cd dutacomputer

# Checkout ke branch yang tepat (misal: main)
git checkout main
```

### Step 2: Setup Environment Variables

```bash
# Copy .env.example ke .env
cp .env.example .env

# Edit .env dengan konfigurasi production
nano .env
```

**Pastikan konfigurasi berikut di .env:**

```env
APP_NAME="PT DUTA COMPUTER - Sistem Manajemen Absensi"
APP_ENV=production
APP_DEBUG=false
APP_KEY=                    # Akan digenerate otomatis
APP_URL=https://yourdomain.com  # Ubah ke domain Anda

# Database (gunakan default docker-compose)
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=absensi_db
DB_USERNAME=absensi_user
DB_PASSWORD=changeMe123!           # UBAH PASSWORD INI!

# Mail Configuration (optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@yourdomain.com

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=database
QUEUE_CONNECTION=database

# Redis Configuration
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Step 3: Siapkan docker-compose.yml untuk Production

Edit file `docker-compose.yml` dan lakukan penyesuaian berikut:

```yaml
# Uncomment ports jika ingin expose langsung (tidak recommended untuk production)
# ports:
#   - "8000:80"

# Update APP_URL
environment:
  - APP_URL=https://yourdomain.com  # Ubah ke domain Anda
  - APP_ENV=production
  - APP_DEBUG=false
  - DB_PASSWORD=changeMe123!       # Gunakan password yang aman
```

### Step 4: Setup Permissions

```bash
# Berikan permission ke folder project
sudo chown -R www-data:www-data /home/wwwroot/dutacomputer

# Set permission untuk storage dan bootstrap
sudo chmod -R 755 /home/wwwroot/dutacomputer/storage
sudo chmod -R 755 /home/wwwroot/dutacomputer/bootstrap/cache
```

---

## Deploy dengan Docker Compose

### Step 1: Build & Start Containers

```bash
# Navigate ke project directory
cd /home/wwwroot/dutacomputer

# Build dan start semua services
docker-compose up -d

# Verify semua container running
docker-compose ps
```

**Expected Output:**
```
NAME                        STATUS
dutacomputer-app            Up (healthy)
dutacomputer-db             Up (healthy)
dutacomputer-redis          Up (healthy)
dutacomputer-queue-worker   Up
dutacomputer-scheduler      Up
```

### Step 2: Generate Application Key

```bash
# Generate APP_KEY
docker-compose exec app php artisan key:generate

# Atau jika sudah ada di .env
docker-compose exec app php artisan config:cache
```

### Step 3: Run Database Migrations

```bash
# Run migrations
docker-compose exec app php artisan migrate --force

# Seed data (jika ingin)
docker-compose exec app php artisan db:seed --force
```

### Step 4: Setup Storage & Permissions

```bash
# Create necessary directories
docker-compose exec app mkdir -p storage/app/public
docker-compose exec app mkdir -p public/uploads

# Create symlink untuk storage
docker-compose exec app php artisan storage:link

# Set permissions
docker-compose exec app chown -R www-data:www-data storage
docker-compose exec app chown -R www-data:www-data bootstrap/cache
```

### Step 5: Verify Aplikasi

```bash
# Check logs
docker-compose logs -f app

# Test health endpoint
curl http://localhost/api/health

# atau gunakan container IP
docker inspect dutacomputer-app | grep IPAddress
```

---

## Konfigurasi Nginx Reverse Proxy

AaPanel menggunakan Nginx. Kita perlu setup reverse proxy untuk routing ke Docker container.

### Step 1: Dapatkan IP Address Container

```bash
# Get app container IP
docker inspect dutacomputer-app | grep IPAddress

# Catat IP address-nya (biasanya 172.xxx.xxx.xxx)
```

### Step 2: Add Website di AaPanel

1. Login ke AaPanel dashboard
2. Ke menu **Websites** → **Add Site**
3. Isi form:
   - **Domain**: yourdomain.com (dan www.yourdomain.com jika perlu)
   - **Path**: /home/wwwroot/dutacomputer
   - **PHP Version**: Pure Static (karena pakai Docker)
   - **Database**: None (sudah ada di Docker)
4. Click **Add**

### Step 3: Edit Nginx Config

Di AaPanel:
1. Pilih website yang baru dibuat
2. Click **Config** (nginx config)
3. Replace dengan konfigurasi berikut:

```nginx
# Dapatkan IP dari langkah sebelumnya (misal: 172.18.0.2)
upstream docker_app {
    server 172.18.0.2:80;  # Ganti dengan IP container app
}

server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /home/wwwroot/dutacomputer/public;
    
    # Redirect HTTP ke HTTPS (setelah SSL disetup)
    # return 301 https://$server_name$request_uri;
    
    access_log /var/log/aapanel/access/yourdomain.com.log;
    error_log /var/log/aapanel/error/yourdomain.com.log;
    
    client_max_body_size 100M;
    
    location / {
        proxy_pass http://docker_app;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_read_timeout 90;
        proxy_connect_timeout 90;
        proxy_send_timeout 90;
    }
    
    # Static files caching (opsional)
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 7d;
        add_header Cache-Control "public, immutable";
    }
}
```

4. **Save** konfigurasi

### Step 4: Restart Nginx

Di AaPanel atau via command:

```bash
# Restart Nginx
sudo systemctl restart nginx

# Test Nginx config
sudo nginx -t
```

### Step 5: Update Docker Container IP di .env (jika perlu)

Jika ada perubahan IP container, update di:
```bash
# Edit .env
nano .env

# Update jika ada service internal yang perlu diakses
APP_URL=https://yourdomain.com
```

---

## SSL Certificate dengan Certbot

### Step 1: Install Certbot (jika belum ada)

```bash
sudo apt install certbot python3-certbot-nginx -y
```

### Step 2: Generate SSL Certificate

```bash
# Generate certificate
sudo certbot certonly --nginx -d yourdomain.com -d www.yourdomain.com

# Atau manual (recommended):
sudo certbot certonly --manual -d yourdomain.com -d www.yourdomain.com
```

### Step 3: Update Nginx Config untuk HTTPS

Edit nginx config lagi dan tambahkan SSL:

```nginx
upstream docker_app {
    server 172.18.0.2:80;
}

# Redirect HTTP ke HTTPS
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

# HTTPS Server
server {
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /home/wwwroot/dutacomputer/public;
    
    # SSL Certificates
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
    
    # SSL Configuration
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;
    
    access_log /var/log/aapanel/access/yourdomain.com.log;
    error_log /var/log/aapanel/error/yourdomain.com.log;
    
    client_max_body_size 100M;
    
    location / {
        proxy_pass http://docker_app;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_read_timeout 90;
        proxy_connect_timeout 90;
        proxy_send_timeout 90;
    }
    
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 7d;
        add_header Cache-Control "public, immutable";
    }
}
```

### Step 4: Setup Auto-Renewal

```bash
# Test renewal
sudo certbot renew --dry-run

# Setup cron untuk auto-renewal
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer

# Verify
sudo systemctl status certbot.timer
```

---

## Troubleshooting

### Problem: Container tidak start

```bash
# Check logs
docker-compose logs app

# Check specific container
docker logs dutacomputer-app

# Restart services
docker-compose restart

# Full rebuild
docker-compose down
docker-compose up -d
```

### Problem: Database connection error

```bash
# Check database container
docker-compose logs db

# Verify database password di .env
cat .env | grep DB_PASSWORD

# Reset database
docker-compose exec db mysql -u absensi_user -p -e "SHOW DATABASES;"

# Recreate database
docker-compose down
docker volume rm dutacomputer_db_data
docker-compose up -d
```

### Problem: File permissions error

```bash
# Fix permissions
docker-compose exec app chown -R www-data:www-data /var/www/html
docker-compose exec app chmod -R 755 storage
docker-compose exec app chmod -R 755 bootstrap/cache
```

### Problem: Redis connection error

```bash
# Check Redis
docker-compose exec redis redis-cli ping

# Clear Redis
docker-compose exec redis redis-cli FLUSHALL
```

### Problem: Aplikasi tidak bisa akses file uploads

```bash
# Buat directory uploads
docker-compose exec app mkdir -p public/uploads

# Set permissions
docker-compose exec app chown -R www-data:www-data public/uploads
docker-compose exec app chmod -R 755 public/uploads
```

---

## Monitoring & Maintenance

### Daily Monitoring

```bash
# Check container status
docker-compose ps

# View logs (last 50 lines)
docker-compose logs --tail=50 app

# Check disk usage
docker system df

# Monitor resource usage
docker stats
```

### Backup Database

```bash
# Backup MySQL
docker-compose exec db mysqldump -u absensi_user -p absensi_db > backup_$(date +%Y%m%d_%H%M%S).sql

# Backup dengan password prompt
docker-compose exec -T db mysqldump -u absensi_user -p'changeMe123!' absensi_db > backup_$(date +%Y%m%d_%H%M%S).sql
```

### Restore Database

```bash
# Restore dari backup
docker-compose exec -T db mysql -u absensi_user -p'changeMe123!' absensi_db < backup_20240115_120000.sql
```

### Update Application

```bash
# Pull latest changes
cd /home/wwwroot/dutacomputer
git pull origin main

# Rebuild containers
docker-compose up -d --build

# Run migrations jika ada
docker-compose exec app php artisan migrate --force
```

### Clean Up

```bash
# Remove unused images
docker image prune -a

# Remove unused volumes
docker volume prune

# Remove unused networks
docker network prune

# Full cleanup (hati-hati!)
docker system prune -a
```

### View Logs

```bash
# Real-time logs
docker-compose logs -f app

# Specific service logs
docker-compose logs -f db
docker-compose logs -f redis

# Last N lines
docker-compose logs --tail=100 app
```

---

## Useful Commands

```bash
# SSH ke container
docker-compose exec app bash

# Run Artisan commands
docker-compose exec app php artisan tinker
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# Check Docker network
docker network inspect absensi-network

# Remove all containers (careful!)
docker-compose down

# Rebuild specific service
docker-compose up -d --build app
```

---

## Performance Tips

1. **Enable Redis Caching**: Sudah dikonfigurasi dalam docker-compose.yml
2. **Enable Query Caching**: Setup di database configuration
3. **Enable Route & Config Caching**: 
   ```bash
   docker-compose exec app php artisan route:cache
   docker-compose exec app php artisan config:cache
   ```

4. **Optimize Autoloader**:
   ```bash
   docker-compose exec app composer dump-autoload --optimize
   ```

5. **Monitor Resource Usage**:
   ```bash
   docker stats
   ```

---

## Security Notes

⚠️ **PENTING:**

1. **Ganti Default Password**: Update semua password di .env
   - DB_PASSWORD
   - Semua service credentials

2. **Setup Firewall**:
   ```bash
   sudo ufw allow 22/tcp
   sudo ufw allow 80/tcp
   sudo ufw allow 443/tcp
   sudo ufw enable
   ```

3. **Disable Debug Mode**: Pastikan `APP_DEBUG=false` di production

4. **Setup HTTPS**: Gunakan Let's Encrypt (sudah dijelaskan di atas)

5. **Regular Backups**: Backup database dan uploaded files secara reguler

6. **Update Security**: Jalankan `composer update` secara berkala

---

## Support & Resources

- **AaPanel Documentation**: https://aapanel.com/docs
- **Docker Documentation**: https://docs.docker.com
- **Laravel Documentation**: https://laravel.com/docs
- **Nginx Documentation**: https://nginx.org/en/docs

---

**Last Updated**: 2024-01-20
**Version**: 1.0.0
