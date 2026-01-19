# Production Environment Configuration

## Overview

Dokumen ini menjelaskan cara mengkonfigurasi environment variables untuk production deployment PT DUTA COMPUTER Sistem Manajemen Absensi.

## Environment Variables untuk Production

### 1. Application Configuration

```env
APP_NAME="PT DUTA COMPUTER - Sistem Manajemen Absensi"
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_LOCALE=id
APP_URL=https://[YOUR_DOMAIN_HERE]
APP_KEY=[GENERATED_VIA_ARTISAN]
BCRYPT_ROUNDS=12
```

**Critical Notes:**
- `APP_DEBUG=false` - MUST be false untuk production security
- `APP_URL` - HARUS menggunakan HTTPS, bukan HTTP
- `APP_KEY` - Generate dengan: `php artisan key:generate`

### 2. Database Configuration

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=absensi_db
DB_USERNAME=absensi_user
DB_PASSWORD=[STRONG_PASSWORD_MIN_12_CHARS]
DB_ROOT_PASSWORD=[STRONG_PASSWORD_MIN_12_CHARS]
DB_SEED=false
```

**Password Requirements:**
- Minimum 12 characters
- Mix of uppercase, lowercase, numbers, symbols
- Generate dengan: `openssl rand -base64 32`

**Example:**
```bash
# Generate strong password
openssl rand -base64 32
# Output: Nx8K3pL9mQ2vJ7wR5sT1uY4xZ6aB0cD/e+fG8hI9jK0=
```

### 3. Cache & Redis Configuration

```env
CACHE_STORE=redis
REDIS_CLIENT=phpredis
REDIS_HOST=redis
REDIS_PORT=6379
REDIS_PASSWORD=[STRONG_PASSWORD_MIN_12_CHARS]
```

**Note:** Redis password is critical for security. DO NOT use weak passwords!

### 4. Session Configuration

```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
```

**Important:** For production clustering, `database` driver is recommended.

### 5. Queue Configuration

```env
QUEUE_CONNECTION=database
```

### 6. Mail Configuration

For production, SMTP adalah recommended. Jangan gunakan `log` driver!

```env
MAIL_MAILER=smtp
MAIL_HOST=[YOUR_SMTP_HOST]
MAIL_PORT=587
MAIL_USERNAME=[YOUR_SMTP_USERNAME]
MAIL_PASSWORD=[YOUR_SMTP_PASSWORD]
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@[YOUR_DOMAIN]
MAIL_FROM_NAME="PT DUTA COMPUTER"
```

**Providers:**
- Sendgrid
- Mailgun
- AWS SES
- Your own SMTP server

### 7. Logging Configuration

```env
LOG_CHANNEL=daily
LOG_LEVEL=warning
LOG_DAILY_DAYS=14
```

**Log Levels:**
- `error` - Only errors
- `warning` - Errors and warnings
- `info` - Errors, warnings, and informational
- `debug` - ALL messages (use only for debugging)

### 8. Security & CORS

```env
CORS_ALLOWED_ORIGINS=https://[YOUR_DOMAIN]
```

### 9. Optional: Slack/Email Alerts

```env
LOG_SLACK_WEBHOOK_URL=[SLACK_WEBHOOK_URL]
ALERT_EMAIL=[ADMIN_EMAIL]
```

---

## Setup Instructions for AaPanel + Docker

### Step 1: Create Environment File

```bash
# SSH ke server
cd /home/wwwroot/dutacomputer

# Copy template dan edit
cp .env example .env
nano .env
```

### Step 2: Generate Passwords

```bash
# Generate Database Password
openssl rand -base64 32

# Generate Redis Password
openssl rand -base64 32

# Copy passwords ke .env file
```

### Step 3: Set File Permissions

```bash
# Pastikan .env tidak accessible dari web
chmod 640 .env
chown www-data:www-data .env

# Verify permissions
ls -la .env
# Output: -rw-r----- 1 www-data www-data ...
```

### Step 4: Update docker-compose.yml

Ensure docker-compose.yml uses environment variables:

```bash
# Verify docker-compose.yml sudah updated
grep "DB_PASSWORD" docker-compose.yml
grep "REDIS_PASSWORD" docker-compose.yml
```

### Step 5: First-Time Database Setup

```bash
# Load .env variables
export $(cat .env | xargs)

# Build dan start containers
docker-compose up -d

# Wait for database to be ready
sleep 10

# Generate APP_KEY
docker-compose exec app php artisan key:generate

# Run migrations (first time only)
docker-compose exec app php artisan migrate --force

# Seed initial data (if needed)
docker-compose exec app php artisan db:seed --force

# Clear caches
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

### Step 6: Verify Setup

```bash
# Check container status
docker-compose ps

# Check logs
docker-compose logs -f app

# Test health endpoint
curl http://[CONTAINER_IP]:80/api/health
```

---

## Common Issues & Solutions

### Issue: "Specified key was too short"

**Error:** `Specified key was too short; the maximum key length is 767 bytes`

**Solution:** Update database configuration in `config/database.php`:

```php
'mysql' => [
    'collation' => 'utf8mb4_unicode_ci',
    'charset' => 'utf8mb4',
    // ...
]
```

### Issue: "SQLSTATE[HY000]: General error: 2006 MySQL server has gone away"

**Solution:** Increase MySQL connection timeout in docker-compose.yml:

```yaml
command: --max_connections=1000 --wait_timeout=28800
```

### Issue: Redis Connection Refused

**Solution:** Verify REDIS_PASSWORD is set correctly:

```bash
# Test Redis connection
docker-compose exec redis redis-cli -a $REDIS_PASSWORD ping
# Should return: PONG
```

### Issue: Email Not Sending

**Solution:** Verify SMTP credentials:

```bash
# Test SMTP connection
docker-compose exec app php artisan tinker
Mail::raw('Test', function ($m) { $m->to('test@example.com'); });
```

---

## Security Checklist

Before going to production, ensure:

- [ ] `APP_DEBUG=false`
- [ ] Strong passwords for DB & Redis (min 12 chars)
- [ ] `.env` file permissions set to 640
- [ ] `.env` file owner is www-data
- [ ] `APP_URL` uses HTTPS
- [ ] All default credentials changed
- [ ] Mail configuration tested
- [ ] SSL certificate installed
- [ ] Firewall configured
- [ ] Backup strategy in place
- [ ] Monitoring configured

---

## Environment Templates

See also:
- `.env example` - Development environment
- `.env.production.example` - Production environment template (in docs folder)

---

## Additional Resources

- [Laravel Environment Configuration](https://laravel.com/docs/configuration#environment-configuration)
- [Docker Compose Environment Variables](https://docs.docker.com/compose/environment-variables/)
- [Twelve-Factor App](https://12factor.net/config)
