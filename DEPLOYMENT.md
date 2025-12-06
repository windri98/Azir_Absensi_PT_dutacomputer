# Deployment Guide - Sistem Absensi Karyawan

Panduan lengkap untuk deploy aplikasi ke production server.

## Prerequisites

- PHP >= 8.2
- MySQL >= 5.7 / MariaDB >= 10.3
- Composer
- Node.js >= 18.x & NPM
- Web Server (Apache/Nginx)
- SSL Certificate (recommended)

## 1. Server Setup

### Ubuntu/Debian Server

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2 and extensions
sudo apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql \
    php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd

# Install MySQL
sudo apt install -y mysql-server

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

## 2. Clone Repository

```bash
cd /var/www
sudo git clone https://github.com/your-username/laravel-absensi.git absensi
cd absensi
sudo chown -R www-data:www-data /var/www/absensi
```

## 3. Install Dependencies

```bash
# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node dependencies
npm install

# Build assets
npm run build
```

## 4. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Edit configuration
nano .env
```

Update `.env` with production values:

```env
APP_NAME="Sistem Absensi Karyawan"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=absensi_production
DB_USERNAME=absensi_user
DB_PASSWORD=your_secure_password

SESSION_DRIVER=database
QUEUE_CONNECTION=database
```
  
## 5. Generate Application Key

```bash
php artisan key:generate
```

## 6. Database Setup

```bash
# Create database
mysql -u root -p
```

```sql
CREATE DATABASE absensi_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'absensi_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON absensi_production.* TO 'absensi_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

```bash
# Run migrations
php artisan migrate --force

# Seed initial data
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=ShiftSeeder
php artisan db:seed --class=UserWithRoleSeeder
```

## 7. Set Permissions

```bash
sudo chown -R www-data:www-data /var/www/absensi
sudo chmod -R 755 /var/www/absensi
sudo chmod -R 775 /var/www/absensi/storage
sudo chmod -R 775 /var/www/absensi/bootstrap/cache
sudo chmod -R 775 /var/www/absensi/public/uploads
```

## 8. Web Server Configuration

### Nginx Configuration

Create `/etc/nginx/sites-available/absensi`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/absensi/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/absensi /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### Apache Configuration

Create `/etc/apache2/sites-available/absensi.conf`:

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/absensi/public

    <Directory /var/www/absensi/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/absensi-error.log
    CustomLog ${APACHE_LOG_DIR}/absensi-access.log combined
</VirtualHost>
```

Enable site:
```bash
sudo a2enmod rewrite
sudo a2ensite absensi.conf
sudo systemctl reload apache2
```

## 9. SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx

# Get certificate (Nginx)
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Get certificate (Apache)
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# Auto-renewal
sudo certbot renew --dry-run
```

## 10. Optimize Application

```bash
# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

## 11. Setup Supervisor (for Queue Workers)

If using queues, create `/etc/supervisor/conf.d/absensi-worker.conf`:

```ini
[program:absensi-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/absensi/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/absensi/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start absensi-worker:*
```

## 12. Setup Cron Jobs

Edit crontab:
```bash
sudo crontab -e -u www-data
```

Add:
```cron
* * * * * cd /var/www/absensi && php artisan schedule:run >> /dev/null 2>&1
```

## 13. Firewall Configuration

```bash
# Enable UFW
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
# or for Apache
sudo ufw allow 'Apache Full'
sudo ufw enable
```

## 14. Monitoring & Logging

```bash
# View Laravel logs
tail -f /var/www/absensi/storage/logs/laravel.log

# View Nginx logs
tail -f /var/log/nginx/error.log

# View Apache logs
tail -f /var/log/apache2/error.log
```

## 15. Post-Deployment Checklist

- [ ] Test all main features
- [ ] Verify SSL certificate
- [ ] Check database connections
- [ ] Test file uploads
- [ ] Verify email sending (if configured)
- [ ] Test login/logout
- [ ] Check all roles access
- [ ] Verify attendance check-in/out
- [ ] Test complaint submission
- [ ] Check reports generation
- [ ] Monitor logs for errors
- [ ] Setup backup strategy
- [ ] Document admin credentials securely

## Backup Strategy

### Database Backup Script

Create `/usr/local/bin/backup-absensi.sh`:

```bash
#!/bin/bash
BACKUP_DIR="/var/backups/absensi"
DATE=$(date +"%Y%m%d_%H%M%S")
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u absensi_user -p'your_secure_password' absensi_production > \
    $BACKUP_DIR/db_backup_$DATE.sql

# Compress
gzip $BACKUP_DIR/db_backup_$DATE.sql

# Keep only last 7 days
find $BACKUP_DIR -name "db_backup_*.sql.gz" -mtime +7 -delete

echo "Backup completed: $DATE"
```

Make executable and add to cron:
```bash
sudo chmod +x /usr/local/bin/backup-absensi.sh
sudo crontab -e
```

Add daily backup at 2 AM:
```cron
0 2 * * * /usr/local/bin/backup-absensi.sh >> /var/log/absensi-backup.log 2>&1
```

## Rollback Procedure

If deployment fails:

```bash
# Restore from backup
gunzip /var/backups/absensi/db_backup_YYYYMMDD_HHMMSS.sql.gz
mysql -u absensi_user -p'password' absensi_production < \
    /var/backups/absensi/db_backup_YYYYMMDD_HHMMSS.sql

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Troubleshooting

### Issue: 500 Internal Server Error
```bash
# Check logs
tail -50 storage/logs/laravel.log

# Clear all caches
php artisan optimize:clear

# Fix permissions
sudo chmod -R 775 storage bootstrap/cache
```

### Issue: Database Connection Error
```bash
# Verify database credentials in .env
# Test connection
php artisan tinker
>>> DB::connection()->getPdo();
```

### Issue: File Upload Not Working
```bash
# Check permissions
ls -la public/uploads
# Should be www-data:www-data with 775

# Create directory if missing
mkdir -p public/uploads
sudo chown -R www-data:www-data public/uploads
sudo chmod -R 775 public/uploads
```

## Support

For issues or questions:
- GitHub Issues: https://github.com/your-username/laravel-absensi/issues
- Documentation: See README.md

## Security

Report security vulnerabilities to: [your-email]

Never commit `.env` file or expose sensitive credentials.
