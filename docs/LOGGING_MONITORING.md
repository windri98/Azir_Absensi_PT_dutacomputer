# Logging & Monitoring Guide

## Overview

Comprehensive logging setup untuk PT DUTA COMPUTER Sistem Manajemen Absensi, dengan fokus pada production reliability dan debugging.

## Logging Configuration

### Default Behavior

**Production Environment:**
- Log Channel: `daily` (daily rotating logs)
- Log Level: `warning` (errors dan warnings)
- Retention: 14 days
- Path: `storage/logs/laravel.log`

**Development Environment:**
- Log Channel: `stack` (single file)
- Log Level: `debug` (all messages)
- Path: `storage/logs/laravel.log`

### Environment Variables

Configure logging in `.env`:

```env
# Log channel: single, daily, stack, slack, stderr, syslog, papertrail
LOG_CHANNEL=daily

# Log level: emergency, alert, critical, error, warning, notice, info, debug
LOG_LEVEL=warning

# Number of days to keep daily logs
LOG_DAILY_DAYS=14

# Slack webhook for critical errors
LOG_SLACK_WEBHOOK_URL=
```

## Logging Channels

### 1. Daily Logs (Recommended for Production)

```env
LOG_CHANNEL=daily
LOG_LEVEL=warning
LOG_DAILY_DAYS=14
```

**Benefits:**
- Automatic log rotation
- Organized by date
- Easy to find logs for specific date

**File Structure:**
```
storage/logs/
├── laravel-2024-01-20.log
├── laravel-2024-01-19.log
├── laravel-2024-01-18.log
└── ...
```

### 2. Single File Logs (Development)

```env
LOG_CHANNEL=single
LOG_LEVEL=debug
```

**Benefits:**
- Simple, all logs in one file
- Good for development

**Downside:**
- File can grow very large
- Difficult to search

### 3. Stack (Multiple Channels)

```env
LOG_CHANNEL=stack
LOG_STACK=single,slack
```

Send logs to multiple channels simultaneously.

### 4. Slack Integration

Send critical errors to Slack:

```env
LOG_CHANNEL=stack
LOG_STACK=daily,slack
LOG_SLACK_WEBHOOK_URL=https://hooks.slack.com/services/YOUR/WEBHOOK/URL
```

## Docker Container Logging

### View Application Logs

```bash
# Real-time logs (all containers)
docker-compose logs -f

# Application logs only
docker-compose logs -f app

# Database logs
docker-compose logs -f db

# Last 100 lines
docker-compose logs --tail=100 app

# Logs from last hour
docker-compose logs --since 1h app
```

### Log Rotation Configuration

Configured in `docker-compose.yml`:

```yaml
logging:
  driver: "json-file"
  options:
    max-size: "10m"
    max-file: "3"
```

This keeps maximum 30MB of logs (3 files × 10MB).

Adjust as needed:

```yaml
logging:
  driver: "json-file"
  options:
    max-size: "100m"      # Per file size
    max-file: "5"         # Number of files to keep
```

## Viewing Logs

### Real-Time Monitoring

```bash
# Follow application logs
docker-compose logs -f app

# Follow with specific service
docker-compose logs -f app db

# Press Ctrl+C to stop
```

### Search Logs

```bash
# Find error logs
docker-compose logs app | grep ERROR

# Find specific string
docker-compose logs app | grep "specific text"

# Find SQL errors
docker-compose logs app | grep -i "sql"

# Find authentication errors
docker-compose logs app | grep -i "auth"
```

### Inside Container

```bash
# SSH into app container
docker-compose exec app bash

# View logs
cat storage/logs/laravel-*.log

# View latest logs
tail -f storage/logs/laravel-*.log

# Search logs
grep ERROR storage/logs/laravel-*.log
```

## Log Levels Explained

### Levels (Most to Least Critical)

1. **emergency** - System is unusable
2. **alert** - Action must be taken immediately
3. **critical** - Critical conditions
4. **error** - Runtime errors
5. **warning** - Warnings (default for production)
6. **notice** - Normal but significant
7. **info** - Informational message
8. **debug** - Detailed debugging (development only)

## Custom Logging

### Log in Controller

```php
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function checkIn(Request $request)
    {
        Log::info('User checked in', [
            'user_id' => auth()->id(),
            'timestamp' => now(),
            'location' => $request->ip(),
        ]);

        Log::warning('Attendance check-in without location', [
            'user_id' => auth()->id(),
        ]);

        Log::error('Check-in failed', [
            'user_id' => auth()->id(),
            'error' => 'GPS location required',
        ]);
    }
}
```

### Log Channels for Different Concerns

Create separate channels:

```php
// config/logging.php
'channels' => [
    'attendance' => [
        'driver' => 'daily',
        'path' => storage_path('logs/attendance.log'),
        'level' => 'info',
    ],
    'complaints' => [
        'driver' => 'daily',
        'path' => storage_path('logs/complaints.log'),
        'level' => 'info',
    ],
],
```

Use in code:

```php
Log::channel('attendance')->info('Check-in recorded', $data);
Log::channel('complaints')->info('Complaint created', $data);
```

## Audit Logging

Application already implements audit logging via `AuditLog` model:

```bash
# View recent audits
docker-compose exec app php artisan tinker

# In tinker
>> App\Models\AuditLog::latest()->take(10)->get()
```

Audit logs track:
- User actions (create, update, delete)
- IP addresses
- User agents
- Timestamps

## Performance Monitoring

### Monitor Long Queries

Add to `.env`:

```env
# Log queries slower than 1000ms (1 second)
LOG_SLOW_QUERIES=true
LOG_SLOW_QUERY_TIME=1000
```

### Memory Usage

```bash
# Check memory usage
docker-compose exec app php -r "echo memory_get_usage(true) / 1024 / 1024 . ' MB';"

# Monitor over time
watch -n 1 'docker-compose exec -T app php -r "echo memory_get_usage(true) / 1024 / 1024 . \" MB\n\";"'
```

## Error Tracking

### Popular Services

1. **Sentry** - Error tracking and performance monitoring
2. **Rollbar** - Error tracking
3. **Bugsnag** - Error management
4. **New Relic** - Full observability

### Setup Sentry

```bash
composer require sentry/sentry-laravel
```

Configure in `.env`:

```env
SENTRY_LARAVEL_DSN=https://[KEY]@[ID].ingest.sentry.io/[PROJECT_ID]
```

## Log Aggregation (Production)

### Centralized Logging Stack

For production, consider centralized logging:

1. **ELK Stack** (Elasticsearch, Logstash, Kibana)
2. **Splunk**
3. **Datadog**
4. **Cloud Logging** (Google Cloud, AWS CloudWatch, Azure)

### Docker Log Driver Options

```yaml
# Send to CloudWatch
logging:
  driver: awslogs
  options:
    awslogs-group: dutacomputer
    awslogs-region: us-east-1

# Send to Google Cloud
logging:
  driver: gcplogs
  options:
    gcp-project: my-project
```

## Log Archival

### Auto-Archive Old Logs

```bash
#!/bin/bash
# deploy/archive-logs.sh

ARCHIVE_DIR="storage/logs/archive"
mkdir -p "$ARCHIVE_DIR"

# Move logs older than 30 days
find storage/logs -name "laravel-*.log" -mtime +30 -exec mv {} "$ARCHIVE_DIR" \;

# Compress archived logs
gzip "$ARCHIVE_DIR"/*.log 2>/dev/null || true
```

Add to cron:

```bash
0 3 * * 0 cd /home/wwwroot/dutacomputer && ./deploy/archive-logs.sh
```

## Troubleshooting

### Issue: Logs Not Being Written

**Symptoms:** `storage/logs/` is empty

**Solution:**

```bash
# Check permissions
ls -la storage/logs/

# Fix permissions
chmod -R 755 storage/logs/
chown -R www-data:www-data storage/logs/

# Test logging
docker-compose exec app php artisan tinker
>> \Illuminate\Support\Facades\Log::info('Test');
```

### Issue: Logs Growing Too Large

**Symptoms:** Disk filling up quickly

**Solution:**

1. Change log level to `warning` (ignore `info` logs)
2. Reduce daily log retention:
   ```env
   LOG_DAILY_DAYS=7
   ```
3. Adjust Docker log driver limits
4. Archive old logs regularly

### Issue: Can't Find Specific Error

**Solution:**

```bash
# Search all logs
grep -r "error message" storage/logs/

# Search with context
grep -r -B2 -A2 "error message" storage/logs/

# Search across all containers
docker-compose logs | grep "error message"
```

## Best Practices

1. **Use Appropriate Levels**
   - Use `error` for actual errors
   - Use `warning` for suspicious activity
   - Use `info` sparingly in production

2. **Include Context**
   ```php
   Log::info('User action', [
       'user_id' => $user->id,
       'action' => 'attendance_check_in',
       'timestamp' => now(),
   ]);
   ```

3. **Don't Log Sensitive Data**
   ```php
   // Bad
   Log::info('User login', ['password' => $password]);

   // Good
   Log::info('User login', ['user_id' => $user->id]);
   ```

4. **Monitor Logs Regularly**
   - Review logs daily
   - Set up alerts for critical errors
   - Track trends over time

5. **Clean Up Regularly**
   - Archive old logs
   - Delete unnecessary logs
   - Manage disk space

## Summary

- ✅ Daily rotating logs (production)
- ✅ Appropriate log levels
- ✅ Docker log rotation
- ✅ Audit logging implemented
- ✅ Easy searching and filtering
- ✅ Container log management

## References

- [Laravel Logging Documentation](https://laravel.com/docs/logging)
- [Docker Logging Drivers](https://docs.docker.com/config/containers/logging/configure/)
- [Twelve-Factor App Logging](https://12factor.net/logs)
