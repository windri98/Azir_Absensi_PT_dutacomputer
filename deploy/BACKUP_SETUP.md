# Backup & Restore Setup Guide

## Overview

Panduan lengkap untuk mengatur automated backup dan restore untuk PT DUTA COMPUTER Sistem Manajemen Absensi.

## Scripts Provided

### 1. `backup.sh` - Automated Backup Script
Melakukan backup dari:
- MySQL database (`$DB_DATABASE`)
- Uploaded files (`public/uploads`)
- Compress dengan gzip untuk efficient storage

**Features:**
- Automatic backup compression
- Automatic cleanup of old backups (default: 14 days)
- Backup verification
- Detailed logging

### 2. `restore.sh` - Restore Script
Restore database dan files dari backup yang sudah dibuat.

**Features:**
- Backup integrity verification
- Database preparation (drop & recreate)
- Files restore dengan backup of current files
- Post-restore verification

## Quick Start

### 1. Make Scripts Executable

```bash
chmod +x deploy/backup.sh
chmod +x deploy/restore.sh
```

### 2. Manual Backup Test

```bash
# Test backup
./deploy/backup.sh

# Check output
ls -lh backups/
```

### 3. Manual Restore Test (Optional - Production)

```bash
# List available backups
ls -lh backups/backup_*.tar.gz

# Restore dari backup
./deploy/restore.sh backups/backup_20240120_140000.tar.gz
```

## Automated Backup Setup (Cron)

### 1. Create Backup Directory

```bash
mkdir -p /home/wwwroot/dutacomputer/backups
chmod 755 /home/wwwroot/dutacomputer/backups
```

### 2. Setup Cron Job

Open crontab editor:

```bash
sudo crontab -e
```

Or jika menggunakan user www-data:

```bash
sudo -u www-data crontab -e
```

Add this line untuk daily backup at 2 AM:

```bash
0 2 * * * cd /home/wwwroot/dutacomputer && ./deploy/backup.sh >> /var/log/dutacomputer-backup.log 2>&1
```

### 3. Verify Cron Job

```bash
# View scheduled jobs
sudo crontab -l

# Or for www-data user
sudo -u www-data crontab -l
```

## Advanced Cron Configurations

### Multiple Backups Per Day

```bash
# Backup every 6 hours
0 0,6,12,18 * * * cd /home/wwwroot/dutacomputer && ./deploy/backup.sh >> /var/log/dutacomputer-backup.log 2>&1
```

### Weekly Full Backup + Daily Incremental

```bash
# Daily backup at 2 AM
0 2 * * * cd /home/wwwroot/dutacomputer && ./deploy/backup.sh >> /var/log/dutacomputer-backup.log 2>&1

# Full backup every Sunday at 3 AM (with longer retention)
0 3 * * 0 cd /home/wwwroot/dutacomputer && BACKUP_RETENTION_DAYS=30 ./deploy/backup.sh >> /var/log/dutacomputer-backup.log 2>&1
```

### Different Retention Policies

```bash
# 7-day retention
0 2 * * * cd /home/wwwroot/dutacomputer && BACKUP_RETENTION_DAYS=7 ./deploy/backup.sh >> /var/log/dutacomputer-backup.log 2>&1
```

## Log Monitoring

### View Backup Logs

```bash
# Real-time
tail -f /var/log/dutacomputer-backup.log

# Last 20 lines
tail -20 /var/log/dutacomputer-backup.log

# Search for errors
grep ERROR /var/log/dutacomputer-backup.log
```

### Setup Log Rotation

Create `/etc/logrotate.d/dutacomputer`:

```bash
sudo tee /etc/logrotate.d/dutacomputer << 'EOF'
/var/log/dutacomputer-backup.log {
    daily
    rotate 7
    compress
    delaycompress
    notifempty
    missingok
    postrotate
        systemctl reload rsyslog > /dev/null 2>&1 || true
    endscript
}
EOF
```

## Environment Variables

Scripts menggunakan environment variables dari `.env` file:

```env
# Backup directory
BACKUP_PATH=/home/wwwroot/dutacomputer/backups

# How many days to keep backups
BACKUP_RETENTION_DAYS=14

# Database credentials (required for backup)
DB_DATABASE=absensi_db
DB_USERNAME=absensi_user
DB_PASSWORD=[YOUR_PASSWORD]

# Alert email (optional)
ALERT_EMAIL=admin@example.com
```

## Monitoring & Alerts

### Email Notification on Backup Failure

Uncomment the email notification section in `backup.sh` and configure mail:

```bash
# In backup.sh, uncomment:
if [ ! -z "$ALERT_EMAIL" ]; then
    SUBJECT="Backup completed: $BACKUP_NAME"
    echo -e "$MESSAGE" | mail -s "$SUBJECT" "$ALERT_EMAIL"
fi
```

Install mail utility:

```bash
apt-get install mailutils
```

### Backup Status Monitoring

Create monitoring script `deploy/check-backup.sh`:

```bash
#!/bin/bash

BACKUP_DIR="/home/wwwroot/dutacomputer/backups"
MAX_AGE_HOURS=25  # Alert if no backup in last 25 hours

# Find most recent backup
LATEST_BACKUP=$(ls -t "$BACKUP_DIR"/backup_*.tar.gz 2>/dev/null | head -1)

if [ -z "$LATEST_BACKUP" ]; then
    echo "ERROR: No backup found!"
    exit 1
fi

# Check age
LAST_MODIFIED=$(stat -f %m "$LATEST_BACKUP" 2>/dev/null || stat -c %Y "$LATEST_BACKUP")
CURRENT_TIME=$(date +%s)
AGE_HOURS=$(( ($CURRENT_TIME - $LAST_MODIFIED) / 3600 ))

if [ $AGE_HOURS -gt $MAX_AGE_HOURS ]; then
    echo "WARNING: Latest backup is $AGE_HOURS hours old!"
    exit 1
fi

echo "OK: Latest backup is $AGE_HOURS hours old"
exit 0
```

Make executable:

```bash
chmod +x deploy/check-backup.sh
```

Monitor with cron:

```bash
# Run every 6 hours
0 0,6,12,18 * * * /home/wwwroot/dutacomputer/deploy/check-backup.sh || mail -s "Backup check failed" admin@example.com
```

## Disaster Recovery Testing

### Test Restore Procedure Monthly

Schedule recovery test:

```bash
# First Sunday of month at 4 AM
0 4 * * 0 [ $(date +\%d) -le 07 ] && /home/wwwroot/dutacomputer/deploy/test-restore.sh
```

Create test script `deploy/test-restore.sh`:

```bash
#!/bin/bash

# This script tests restore on a staging environment
# DO NOT RUN ON PRODUCTION!

BACKUP_DIR="/home/wwwroot/dutacomputer/backups"
LATEST_BACKUP=$(ls -t "$BACKUP_DIR"/backup_*.tar.gz | head -1)

if [ -z "$LATEST_BACKUP" ]; then
    echo "No backup found for testing"
    exit 1
fi

echo "Testing restore from: $LATEST_BACKUP"
# Test restore procedure here
```

## Common Issues

### Issue: "DB_PASSWORD not set"

**Error:** `DB_PASSWORD environment variable is not set!`

**Solution:** Ensure `.env` file exists with DB_PASSWORD:

```bash
# Check .env exists
test -f .env && echo "OK" || echo "Missing .env"

# Set permissions
chmod 640 .env
chown www-data:www-data .env

# Verify password is set
grep DB_PASSWORD .env
```

### Issue: "Container not running"

**Error:** `Database container is not running!`

**Solution:** Start containers:

```bash
docker-compose up -d
docker-compose ps  # Verify all are Up
```

### Issue: "Permission denied"

**Error:** `Permission denied` when creating backup directory

**Solution:** Create with proper permissions:

```bash
sudo mkdir -p /home/wwwroot/dutacomputer/backups
sudo chmod 755 /home/wwwroot/dutacomputer/backups
sudo chown www-data:www-data /home/wwwroot/dutacomputer/backups
```

### Issue: Cron job not running

**Debug:** Check cron execution:

```bash
# Check if cron service is running
sudo systemctl status cron

# View cron logs
sudo grep CRON /var/log/syslog | tail -20

# Check crontab syntax
sudo crontab -l
```

## Backup File Structure

```
backups/
├── backup_20240120_020000.tar.gz
│   ├── database.sql              # MySQL dump
│   └── files/
│       └── uploads/              # Uploaded files
├── backup_20240119_020000.tar.gz
└── ...
```

## Restore Procedure

### Step 1: Verify Backup Exists

```bash
ls -lh backups/backup_*.tar.gz
```

### Step 2: Stop Application (Optional)

```bash
docker-compose stop app
```

### Step 3: Restore

```bash
./deploy/restore.sh backups/backup_20240120_020000.tar.gz
```

### Step 4: Verify Restore

```bash
# Check application
curl http://localhost:80/api/health

# Check logs
docker-compose logs app
```

### Step 5: Start Application

```bash
docker-compose start app
```

## Retention Policy

Default: Keep last 14 days of backups

To change retention:

```bash
# Keep 30 days
BACKUP_RETENTION_DAYS=30 ./deploy/backup.sh

# Keep only 7 days
BACKUP_RETENTION_DAYS=7 ./deploy/backup.sh
```

## Storage Calculation

Estimate backup size:

```bash
# Database size
docker-compose exec -T db mysql -u$DB_USERNAME -p$DB_PASSWORD -e "SELECT SUM(data_length + index_length) FROM information_schema.TABLES WHERE table_schema='$DB_DATABASE';"

# Files size
du -sh public/uploads/

# Compressed backup (typically 30-40% of original)
```

## Off-Site Backup (Recommended for Production)

### Copy Backups to Remote Server

```bash
# Rsync to remote server
0 3 * * * rsync -av --delete /home/wwwroot/dutacomputer/backups/ user@backup-server:/backups/dutacomputer/

# S3 upload
0 3 * * * aws s3 sync /home/wwwroot/dutacomputer/backups/ s3://my-bucket/dutacomputer-backups/ --delete
```

## Summary

- ✅ Automated daily backups at 2 AM
- ✅ Automatic cleanup after 14 days
- ✅ Compression reduces storage by 60-70%
- ✅ Easy restore procedure
- ✅ Comprehensive logging
- ✅ Off-site backup recommended for production

## Support

For issues or questions:
1. Check logs: `/var/log/dutacomputer-backup.log`
2. Review script comments
3. Test manual backup: `./deploy/backup.sh`
