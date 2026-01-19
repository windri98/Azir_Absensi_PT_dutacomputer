#!/bin/bash

################################################################################
# PT DUTA COMPUTER - Database Restore Script
#
# This script restores a backup created by backup.sh
#
# Usage:
#   ./deploy/restore.sh backup_20231215_120000.tar.gz
#   ./deploy/restore.sh ./backups/backup_20231215_120000.tar.gz
#
# WARNING:
#   This will overwrite the current database!
#   Please ensure you have a fresh backup before running restore.
#
################################################################################

set -e

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

# Configuration
BACKUP_DIR="${BACKUP_PATH:-.}/backups"
DB_CONTAINER="dutacomputer-db"
DB_DATABASE="${DB_DATABASE:-absensi_db}"
DB_USERNAME="${DB_USERNAME:-absensi_user}"
DB_PASSWORD="${DB_PASSWORD}"

# Check arguments
if [ $# -eq 0 ]; then
    error "No backup file specified!"
    echo "Usage: $0 <backup_file>"
    echo "Example: $0 backup_20231215_120000.tar.gz"
    echo ""
    echo "Available backups in $BACKUP_DIR:"
    ls -lh "$BACKUP_DIR"/backup_*.tar.gz 2>/dev/null | tail -5 || echo "  (none found)"
    exit 1
fi

BACKUP_FILE="$1"

# Handle relative paths
if [[ ! "$BACKUP_FILE" = /* ]]; then
    if [ -f "$BACKUP_DIR/$BACKUP_FILE" ]; then
        BACKUP_FILE="$BACKUP_DIR/$BACKUP_FILE"
    fi
fi

# Verify backup file exists
if [ ! -f "$BACKUP_FILE" ]; then
    error "Backup file not found: $BACKUP_FILE"
    exit 1
fi

log "Starting restore process..."
log "Backup file: $BACKUP_FILE"
log "Database: $DB_DATABASE"

# Confirmation
warning "WARNING: This will overwrite the current database!"
warning "Please ensure you have backed up the current database."
echo -n "Are you sure you want to continue? (yes/no): "
read -r CONFIRM

if [ "$CONFIRM" != "yes" ]; then
    log "Restore cancelled."
    exit 0
fi

# ============================================================================
# 1. VERIFY BACKUP
# ============================================================================
log "Verifying backup integrity..."

if ! tar -tzf "$BACKUP_FILE" > /dev/null 2>&1; then
    error "Backup file is corrupted or invalid!"
    exit 1
fi

log "Backup verification: OK ✓"

# ============================================================================
# 2. EXTRACT BACKUP
# ============================================================================
log "Extracting backup..."

TEMP_EXTRACT_DIR=$(mktemp -d)
trap "rm -rf $TEMP_EXTRACT_DIR" EXIT

if ! tar -xzf "$BACKUP_FILE" -C "$TEMP_EXTRACT_DIR"; then
    error "Failed to extract backup"
    exit 1
fi

# Find the backup directory (should be only one)
BACKUP_SUBDIR=$(ls "$TEMP_EXTRACT_DIR" | head -1)
EXTRACTED_PATH="$TEMP_EXTRACT_DIR/$BACKUP_SUBDIR"

if [ ! -f "$EXTRACTED_PATH/database.sql" ]; then
    error "database.sql not found in backup!"
    exit 1
fi

log "Backup extracted successfully"

# ============================================================================
# 3. CHECK DOCKER CONTAINERS
# ============================================================================
log "Checking Docker containers..."

if ! docker-compose ps | grep -q "$DB_CONTAINER.*Up"; then
    error "Database container ($DB_CONTAINER) is not running!"
    error "Please start containers: docker-compose up -d"
    exit 1
fi

# ============================================================================
# 4. DROP AND RECREATE DATABASE
# ============================================================================
log "Preparing database for restore..."
warning "Dropping existing database: $DB_DATABASE"

docker-compose exec -T "$DB_CONTAINER" mysql \
    -u"$DB_USERNAME" -p"$DB_PASSWORD" \
    -e "DROP DATABASE IF EXISTS $DB_DATABASE;" 2>/dev/null || true

docker-compose exec -T "$DB_CONTAINER" mysql \
    -u"$DB_USERNAME" -p"$DB_PASSWORD" \
    -e "CREATE DATABASE $DB_DATABASE CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null

log "Database prepared"

# ============================================================================
# 5. RESTORE DATABASE
# ============================================================================
log "Restoring database from backup..."

if docker-compose exec -T "$DB_CONTAINER" mysql \
    -u"$DB_USERNAME" -p"$DB_PASSWORD" \
    "$DB_DATABASE" < "$EXTRACTED_PATH/database.sql" 2>/dev/null; then
    log "Database restore completed ✓"
else
    error "Failed to restore database"
    exit 1
fi

# ============================================================================
# 6. RESTORE FILES
# ============================================================================
if [ -d "$EXTRACTED_PATH/files" ]; then
    log "Restoring files..."
    
    # Backup current files
    if [ -d "public/uploads" ]; then
        CURRENT_BACKUP="public/uploads.backup_$(date +%s)"
        log "Backing up current files to: $CURRENT_BACKUP"
        mv public/uploads "$CURRENT_BACKUP"
    fi
    
    # Restore files
    if cp -r "$EXTRACTED_PATH/files/uploads" public/uploads 2>/dev/null; then
        log "Files restore completed ✓"
    else
        error "Failed to restore files"
        exit 1
    fi
else
    warning "No files found in backup to restore"
fi

# ============================================================================
# 7. VERIFY RESTORE
# ============================================================================
log "Verifying restore..."

TABLE_COUNT=$(docker-compose exec -T "$DB_CONTAINER" mysql \
    -u"$DB_USERNAME" -p"$DB_PASSWORD" \
    -N -e "SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='$DB_DATABASE';" 2>/dev/null)

if [ "$TABLE_COUNT" -gt 0 ]; then
    log "Database verification: OK ($TABLE_COUNT tables)"
else
    warning "No tables found in database"
fi

# ============================================================================
# 8. RUN POST-RESTORE TASKS
# ============================================================================
log "Running post-restore tasks..."

# Clear caches
docker-compose exec -T app php artisan config:cache 2>/dev/null || true
docker-compose exec -T app php artisan cache:clear 2>/dev/null || true

log "Post-restore tasks completed"

# ============================================================================
# 9. COMPLETION
# ============================================================================
log "Restore process completed successfully! ✓"
log ""
log "Next steps:"
log "  1. Verify the restored data"
log "  2. Check application logs: docker-compose logs -f app"
log "  3. Test critical features"
log "  4. Remove old backup if everything is working:"
log "     rm ${CURRENT_BACKUP:-public/uploads.backup_*}"

exit 0
