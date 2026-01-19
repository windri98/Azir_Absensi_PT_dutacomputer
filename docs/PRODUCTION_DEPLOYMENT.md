# Production Deployment Guide

## Overview

Panduan lengkap untuk deploy PT DUTA COMPUTER Sistem Manajemen Absensi ke production di AaPanel dengan Docker Compose.

## Prerequisites

- VPS/Server dengan Ubuntu 20.04+ (rekomendasi 22.04 LTS)
- AaPanel sudah terinstall
- Root atau sudo access
- Domain sudah pointing ke server IP
- SSH access ke server

## Phase 1: Server Preparation

### 1.1 Update System

```bash
# SSH ke server
ssh root@your.server.ip

# Update system packages
apt update && apt upgrade -y

# Install required packages
apt install -y docker.io docker-compose git curl wget nginx
```

### 1.2 Verify Docker Installation

```bash
# Check Docker version
docker --version
# Expected: Docker version 20.10+

# Check Docker Compose version
docker-compose --version
# Expected: Docker Compose version 1.29+

# Start Docker service
systemctl start docker
systemctl enable docker

# Verify Docker running
systemctl status docker
```

### 1.3 Prepare Directory Structure

```bash
# Navigate to web root
cd /home/wwwroot

# Clone repository
git clone https://github.com/yourusername/dutacomputer.git

cd dutacomputer

# Set proper permissions
chown -R www-data:www-data .
chmod -R 755 .

# Create backups directory
mkdir -p backups
chmod 755 backups
```

## Phase 2: Environment Configuration

### 2.1 Create .env File

```bash
# Copy environment template
cp .env example .env

# Edit configuration
nano .env
```

### 2.2 Generate Strong Passwords

```bash
# Generate passwords (use openssl or online generator)
openssl rand -base64 32

# Example output:
# Nx8K3pL9mQ2vJ7wR5sT1uY4xZ6aB0cD/e+fG8hI9jK0=
```

### 2.3 Configure .env

Set these critical values:

```env
# ===== APPLICATION =====
APP_NAME="PT DUTA COMPUTER - Sistem Manajemen Absensi"
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_URL=https://absensi.yourdomain.com

# ===== DATABASE =====
DB_HOST=db
DB_DATABASE=absensi_db
DB_USERNAME=absensi_user
DB_PASSWORD=[GENERATED_PASSWORD_1]
DB_ROOT_PASSWORD=[GENERATED_PASSWORD_2]
DB_SEED=false

# ===== REDIS =====
REDIS_HOST=redis
REDIS_PASSWORD=[GENERATED_PASSWORD_3]

# ===== MAIL =====
MAIL_MAILER=smtp
MAIL_HOST=smtp.your-mail-provider.com
MAIL_PORT=587
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-mail-password
MAIL_FROM_ADDRESS=noreply@yourdomain.com

# ===== LOGGING =====
LOG_CHANNEL=daily
LOG_LEVEL=warning

# ===== CORS =====
CORS_ALLOWED_ORIGINS=https://yourdomain.com
```

### 2.4 Set File Permissions

```bash
# Set correct permissions for .env
chmod 640 .env
chown www-data:www-data .env

# Create logs directory
mkdir -p storage/logs
chmod 755 storage/logs

# Create cache directories
mkdir -p bootstrap/cache storage/app storage/framework
chmod 755 bootstrap/cache storage/app storage/framework
```

## Phase 3: Docker Build & Deployment

### 3.1 Build Docker Images

```bash
# Build all containers
docker-compose build

# This will take 5-10 minutes
# Monitor output for any errors
```

### 3.2 Start Containers

```bash
# Start all containers in background
docker-compose up -d

# Check container status
docker-compose ps

# Expected output:
# NAME                         STATUS
# dutacomputer-app             Up (healthy)
# dutacomputer-db              Up (healthy)
# dutacomputer-redis           Up
# dutacomputer-queue-worker    Up
# dutacomputer-scheduler       Up
```

### 3.3 Wait for Database

```bash
# Wait for database to be ready (up to 1 minute)
sleep 30

# Verify database connection
docker-compose exec app php artisan tinker

# In tinker, type:
>> DB::connection()->getPdo();
>> exit
```

### 3.4 Generate Application Key

```bash
# Generate unique APP_KEY (required for Laravel)
docker-compose exec app php artisan key:generate

# Output: Application key [base64:xxxxx...] set successfully.
```

### 3.5 Run Database Migrations

```bash
# Run migrations
docker-compose exec app php artisan migrate --force

# Expected: several tables created
# If no output, migrations already ran
```

### 3.6 Seed Initial Data (Optional)

```bash
# Only run on first deployment
# This creates admin user, roles, permissions, etc.
docker-compose exec app php artisan db:seed --force

# Admin credentials will be shown in output
# Save them securely!
```

### 3.7 Cache Configuration

```bash
# Cache application configuration (important for performance)
docker-compose exec app php artisan config:cache

# Cache routes
docker-compose exec app php artisan route:cache

# Cache views
docker-compose exec app php artisan view:cache

# Verify caches created
ls -la bootstrap/cache/
```

## Phase 4: Nginx Configuration

### 4.1 Get App Container IP

```bash
# Get IP address of app container
docker inspect dutacomputer-app | grep IPAddress | grep -o '[0-9]\{1,3\}\.[0-9]\{1,3\}\.[0-9]\{1,3\}\.[0-9]\{1,3\}'

# Copy this IP (example: 172.18.0.2)
```

### 4.2 Configure Nginx via AaPanel

```bash
# Access AaPanel
# https://your.server.ip:7800

# Login with credentials

# Go to: Site Management > Add Site

# Configure:
# - Domain: absensi.yourdomain.com
# - Root: /home/wwwroot/dutacomputer/public
# - PHP Version: Pure Static (since using Docker)
# - Database: None (using Docker)
# - FTP: Yes (optional)
```

### 4.3 Update Nginx Configuration

In AaPanel, go to Site > Config and replace with:

```nginx
# Copy from nginx.conf.template and update:
# 1. Replace YOUR_DOMAIN with your actual domain
# 2. Replace CONTAINER_IP with IP from step 4.1
# 3. Update SSL certificate paths after Let's Encrypt setup
```

Or via SSH:

```bash
# Edit Nginx config
sudo nano /etc/nginx/sites-available/absensi.yourdomain.com

# Paste nginx configuration from template
# Update placeholder values
```

### 4.4 Test Nginx Configuration

```bash
# Test syntax
sudo nginx -t

# Expected output: successful

# Reload Nginx
sudo systemctl reload nginx
```

### 4.5 Verify Application Access

```bash
# Test local access to app
curl http://127.0.0.1:80/api/health

# Should return JSON health status
```

## Phase 5: SSL/TLS Setup

### 5.1 Install Certbot

```bash
# Install Let's Encrypt Certbot
apt install -y certbot python3-certbot-nginx

# Verify installation
certbot --version
```

### 5.2 Generate SSL Certificate

```bash
# Generate certificate (interactive)
certbot certonly --nginx -d absensi.yourdomain.com

# Or non-interactive:
certbot certonly --nginx --non-interactive --agree-tos -m your-email@example.com -d absensi.yourdomain.com
```

### 5.3 Update Nginx SSL Configuration

```bash
# Edit Nginx config
sudo nano /etc/nginx/sites-available/absensi.yourdomain.com

# Update SSL certificate paths:
ssl_certificate /etc/letsencrypt/live/absensi.yourdomain.com/fullchain.pem;
ssl_certificate_key /etc/letsencrypt/live/absensi.yourdomain.com/privkey.pem;

# Test and reload
sudo nginx -t
sudo systemctl reload nginx
```

### 5.4 Setup Auto-Renewal

```bash
# Enable Certbot auto-renewal
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer

# Test renewal
sudo certbot renew --dry-run
```

### 5.5 Test HTTPS

```bash
# Test from browser
https://absensi.yourdomain.com

# Or via curl
curl -I https://absensi.yourdomain.com

# Should return 200 OK
```

## Phase 6: Security Hardening

### 6.1 Firewall Configuration

```bash
# Install UFW
apt install -y ufw

# Enable firewall
ufw enable

# Allow SSH
ufw allow 22/tcp

# Allow HTTP & HTTPS
ufw allow 80/tcp
ufw allow 443/tcp

# Check status
ufw status numbered

# Verify firewall enabled
systemctl status ufw
```

### 6.2 Security Headers

Already configured in nginx.conf.template:
- HSTS (HTTP Strict Transport Security)
- X-Frame-Options
- X-Content-Type-Options
- X-XSS-Protection
- Referrer-Policy

Verify in browser DevTools > Network:
```
Strict-Transport-Security: max-age=31536000
X-Frame-Options: SAMEORIGIN
```

### 6.3 Disable Dangerous Ports

```bash
# Ensure MySQL port 3306 is NOT exposed
docker-compose ps
# Verify port 3306 is NOT showing

# Ensure Redis port 6379 is internal only
docker-compose ps
# Verify port 6379 is NOT showing
```

## Phase 7: Backup Setup

### 7.1 Make Scripts Executable

```bash
# Make backup/restore scripts executable
chmod +x deploy/backup.sh
chmod +x deploy/restore.sh

# Test backup
./deploy/backup.sh

# Verify backup created
ls -lh backups/
```

### 7.2 Setup Automated Backups

```bash
# Open crontab
sudo crontab -e

# Add cron job for daily backup at 2 AM
0 2 * * * cd /home/wwwroot/dutacomputer && ./deploy/backup.sh >> /var/log/dutacomputer-backup.log 2>&1

# Verify
sudo crontab -l
```

### 7.3 Setup Log Rotation

```bash
# Create logrotate config
sudo tee /etc/logrotate.d/dutacomputer << 'EOF'
/var/log/dutacomputer-backup.log {
    daily
    rotate 7
    compress
    delaycompress
    notifempty
}
EOF
```

## Phase 8: Verification & Testing

### 8.1 Health Check

```bash
# Test health endpoint
curl https://absensi.yourdomain.com/api/health

# Should return:
# {"status":"healthy","timestamp":"2024-01-20T...","environment":"production",...}
```

### 8.2 Database Connection

```bash
# Verify database from container
docker-compose exec app php artisan tinker

# Test query
>> DB::table('users')->count();
>> exit
```

### 8.3 API Endpoints

```bash
# Test login endpoint
curl -X POST https://absensi.yourdomain.com/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```

### 8.4 Application Features

1. ✅ Navigate to https://absensi.yourdomain.com
2. ✅ Test login with seeded credentials
3. ✅ Check dashboard loads
4. ✅ Test attendance check-in/check-out
5. ✅ Test report generation
6. ✅ Test file upload
7. ✅ Check notifications

### 8.5 Monitor Logs

```bash
# Real-time logs
docker-compose logs -f app

# Check for errors
docker-compose logs app | grep ERROR

# Check database logs
docker-compose logs db
```

## Phase 9: Post-Deployment

### 9.1 Change Default Admin Password

```bash
# Access application
# Login with seeded admin account
# Navigate to Profile > Change Password
# Set strong password
```

### 9.2 Configure Mail (if using SMTP)

```bash
# Test mail configuration
docker-compose exec app php artisan mail:test your-email@example.com

# Check logs for success
docker-compose logs app | tail -20
```

### 9.3 Monitor Initial Hours

```bash
# Watch logs for errors
watch -n 10 'docker-compose logs app | tail -20'

# Check container health
watch -n 10 'docker-compose ps'

# Monitor system resources
watch -n 10 'free -m && df -h'
```

### 9.4 Create Monitoring Alert (Optional)

```bash
# Check backup completed
./deploy/check-backup.sh

# Check disk space
df -h /

# Check memory usage
free -m
```

## Phase 10: Maintenance & Updates

### 10.1 Regular Backups

```bash
# Manual backup anytime
./deploy/backup.sh

# Restore if needed
./deploy/restore.sh backups/backup_2024xxxx.tar.gz

# Automated daily at 2 AM (via cron)
```

### 10.2 Container Updates

```bash
# Pull latest Docker images
docker-compose pull

# Rebuild if needed
docker-compose build

# Update containers (zero downtime)
docker-compose up -d
```

### 10.3 Application Updates

```bash
# Update source code
cd /home/wwwroot/dutacomputer
git pull origin main

# Run migrations if any
docker-compose exec app php artisan migrate --force

# Clear caches
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:cache
```

### 10.4 Restart Containers

```bash
# Graceful restart (preserves connections)
docker-compose restart

# Or full cycle
docker-compose stop
docker-compose start

# Check all containers running
docker-compose ps
```

## Troubleshooting

### Problem: Application Won't Start

```bash
# Check logs
docker-compose logs app

# Check database is ready
docker-compose logs db

# Verify .env variables
grep -i "^[A-Z]" .env | head -20

# Manually test database
docker-compose exec db mysql -u $DB_USERNAME -p $DB_DATABASE -e "SELECT 1;"
```

### Problem: HTTPS Not Working

```bash
# Verify certificate
sudo ls -la /etc/letsencrypt/live/absensi.yourdomain.com/

# Test certificate
openssl s_client -connect absensi.yourdomain.com:443

# Check Nginx config
sudo nginx -T | grep -A5 "ssl_"

# Reload Nginx
sudo systemctl reload nginx
```

### Problem: Database Connection Failed

```bash
# Check MySQL is running
docker-compose ps db

# Verify credentials in .env
grep "DB_" .env

# Test directly
docker-compose exec db mysql -uroot -p$DB_ROOT_PASSWORD -e "SHOW DATABASES;"
```

### Problem: Health Check Failing

```bash
# Check if container is running
docker-compose exec app curl localhost/api/health

# Check app logs
docker-compose logs app | tail -50

# Verify PHP is running
docker-compose exec app php -v
```

## Performance Optimization

### 1. Enable Caching

Already configured in docker-compose.yml:
- `CACHE_DRIVER=redis`
- `REDIS_HOST=redis`

### 2. Database Connection Pooling

Configured in `config/database.php` via PDO options

### 3. Query Optimization

Verify with:
```bash
docker-compose exec app php artisan tinker
DB::enableQueryLog();
// Run your query
dd(DB::getQueryLog());
```

### 4. Monitor Performance

```bash
# Check response times
curl -w "@curl-format.txt" https://absensi.yourdomain.com/api/health

# Monitor CPU/Memory
docker stats dutacomputer-app
```

## Rollback Procedure

If something goes wrong:

```bash
# Stop application
docker-compose stop

# Restore backup
./deploy/restore.sh backups/backup_XXXXXXXX.tar.gz

# Start containers
docker-compose up -d

# Verify
docker-compose ps
curl https://absensi.yourdomain.com/api/health
```

## Production Checklist

Before going live:

- [ ] All .env variables configured
- [ ] Strong passwords for DB, Redis, Admin
- [ ] SSL certificate installed
- [ ] Firewall configured
- [ ] Backups running (test restore)
- [ ] Nginx proxying correctly
- [ ] Health check endpoint responding
- [ ] Logs configured and rotating
- [ ] Rate limiting configured
- [ ] Admin password changed from default
- [ ] SMTP mail configured
- [ ] Performance tested
- [ ] Monitoring setup (optional)
- [ ] Documentation reviewed
- [ ] Team trained on backup/restore

## Support & Escalation

For issues:
1. Check logs: `docker-compose logs -f app`
2. Review documentation in `docs/` folder
3. Test with manual commands
4. Check health endpoint: `/api/health`
5. Verify environment variables
6. Test database connection
7. Review Nginx configuration

## Summary

Selamat! Aplikasi PT DUTA COMPUTER sudah siap di production dengan:
- ✅ Secure Docker setup
- ✅ HTTPS/SSL configured
- ✅ Automated backups
- ✅ Monitoring & logging
- ✅ Security hardening
- ✅ Rate limiting
- ✅ Health checks

Harap pantau aplikasi untuk 24 jam pertama untuk memastikan semuanya berjalan lancar.
