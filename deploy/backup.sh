#!/bin/bash

################################################################################
# PT DUTA COMPUTER - Database and Files Backup Script
# 
# This script performs automated backup of:
# - MySQL database
# - Uploaded files (public/uploads)
# - Application files (optional)
#
# Usage:
#   ./deploy/backup.sh
#
# Cron job example (daily at 2 AM):
#   0 2 * * * /home/wwwroot/dutacomputer/deploy/backup.sh >> /var/log/dutacomputer-backup.log 2>&1
#
# Requirements:
#   - Docker and Docker Compose running
#   - mysqldump installed (in MySQL container)
#   - Sufficient disk space for backups
#
################################################################################

set -e  # Exit on error

# Configuration
BACKUP_DIR="${BACKUP_PATH:-.}/backups"
BACKUP_RETENTION_DAYS="${BACKUP_RETENTION_DAYS:-14}"
DOCKER_COMPOSE_PROJECT="dutacomputer"
DB_CONTAINER="dutacomputer-db"
DB_DATABASE="${DB_DATABASE:-absensi_db}"
DB_USERNAME="${DB_USERNAME:-absensi_user}"
DB_PASSWORD="${DB_PASSWORD}"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_NAME="backup_${TIMESTAMP}"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Logging function
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1"
}

error() {
    echo -e "${RED}[$(date +'%Y-%m-%d %H:%M:%S')] ERROR:${NC} $1" >&2
}

warning() {
    echo -e "${YELLOW}[$(date +'%Y-%m-%d %H:%M:%S')] WARNING:${NC} $1"
}

# Create backup directory if it doesn't exist
if [ ! -d "$BACKUP_DIR" ]; then
    log "Creating backup directory: $BACKUP_DIR"
    mkdir -p "$BACKUP_DIR"
fi

# Check if Docker containers are running
log "Checking Docker containers status..."
if ! docker-compose ps | grep -q "$DB_CONTAINER.*Up"; then
    error "Database container ($DB_CONTAINER) is not running!"
    exit 1
fi

log "Starting backup process (Backup ID: $BACKUP_NAME)..."

# Create backup subdirectory
BACKUP_FULL_PATH="$BACKUP_DIR/$BACKUP_NAME"
mkdir -p "$BACKUP_FULL_PATH"

# ============================================================================
# 1. DATABASE BACKUP
# ============================================================================
log "Backing up database: $DB_DATABASE"

BACKUP_SQL_FILE="$BACKUP_FULL_PATH/database.sql"

# Check if DB_PASSWORD is set
if [ -z "$DB_PASSWORD" ]; then
    error "DB_PASSWORD environment variable is not set!"
    error "Please set DB_PASSWORD before running backup"
    exit 1
fi

# Dump database
if docker-compose exec -T "$DB_CONTAINER" mysqldump \
    --single-transaction \
    --quick \
    --lock-tables=false \
    -u"$DB_USERNAME" -p"$DB_PASSWORD" \
    "$DB_DATABASE" > "$BACKUP_SQL_FILE" 2>/dev/null; then
    
    log "Database backup completed: $(du -h "$BACKUP_SQL_FILE" | cut -f1)"
else
    error "Failed to backup database"
    exit 1
fi

# ============================================================================
# 2. FILES BACKUP
# ============================================================================
log "Backing up uploaded files..."

BACKUP_FILES_DIR="$BACKUP_FULL_PATH/files"
mkdir -p "$BACKUP_FILES_DIR"

# Backup public/uploads
if [ -d "public/uploads" ]; then
    cp -r public/uploads "$BACKUP_FILES_DIR/" 2>/dev/null || warning "Some files could not be backed up (permissions?)"
    log "Files backup completed"
else
    warning "public/uploads directory not found, skipping..."
fi

# ============================================================================
# 3. COMPRESS BACKUP
# ============================================================================
log "Compressing backup..."

BACKUP_ARCHIVE="$BACKUP_DIR/${BACKUP_NAME}.tar.gz"

if tar -czf "$BACKUP_ARCHIVE" -C "$BACKUP_DIR" "$BACKUP_NAME" 2>/dev/null; then
    ARCHIVE_SIZE=$(du -h "$BACKUP_ARCHIVE" | cut -f1)
    log "Backup compressed: $ARCHIVE_SIZE"
    
    # Remove uncompressed backup directory
    rm -rf "$BACKUP_FULL_PATH"
else
    error "Failed to compress backup"
    exit 1
fi

# ============================================================================
# 4. CLEANUP OLD BACKUPS
# ============================================================================
log "Cleaning up old backups (keeping last $BACKUP_RETENTION_DAYS days)..."

find "$BACKUP_DIR" -maxdepth 1 -name "backup_*.tar.gz" -mtime +$BACKUP_RETENTION_DAYS -delete

# Count remaining backups
BACKUP_COUNT=$(ls -1 "$BACKUP_DIR"/backup_*.tar.gz 2>/dev/null | wc -l)
log "Remaining backups: $BACKUP_COUNT"

# ============================================================================
# 5. BACKUP STATISTICS
# ============================================================================
log "Backup Statistics:"
log "  - Backup ID: $BACKUP_NAME"
log "  - Archive Size: $ARCHIVE_SIZE"
log "  - Location: $BACKUP_ARCHIVE"
log "  - Database: $DB_DATABASE"
log "  - Retention: $BACKUP_RETENTION_DAYS days"

# ============================================================================
# 6. VERIFY BACKUP
# ============================================================================
log "Verifying backup integrity..."

if tar -tzf "$BACKUP_ARCHIVE" > /dev/null 2>&1; then
    log "Backup verification: OK ✓"
else
    error "Backup verification failed! Archive may be corrupted."
    exit 1
fi

# ============================================================================
# 7. SEND NOTIFICATION (Optional)
# ============================================================================
# Uncomment to enable email notifications
#
# if [ ! -z "$ALERT_EMAIL" ]; then
#     SUBJECT="Backup completed: $BACKUP_NAME"
#     MESSAGE="Database backup completed successfully.\n\nDetails:\n- Backup ID: $BACKUP_NAME\n- Size: $ARCHIVE_SIZE\n- Database: $DB_DATABASE\n\nLocation: $BACKUP_ARCHIVE"
#     echo -e "$MESSAGE" | mail -s "$SUBJECT" "$ALERT_EMAIL"
# fi

log "Backup process completed successfully! ✓"
log "To restore this backup, use: ./deploy/restore.sh $BACKUP_ARCHIVE"

exit 0
