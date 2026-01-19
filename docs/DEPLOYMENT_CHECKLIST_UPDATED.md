# Production Deployment Checklist - Updated

Checklist komprehensif untuk deploy PT DUTA COMPUTER Sistem Manajemen Absensi ke production dengan Docker Compose.

## Overview

Checklist ini mencakup semua aspek deployment:
- Server preparation
- Security configuration
- Docker setup
- Environment configuration
- Database migration
- Application verification
- Backup & monitoring
- Post-deployment tasks

---

## Phase 1: Server Preparation

### Infrastructure Setup
- [ ] VPS/Server running (Ubuntu 20.04+ recommended)
- [ ] SSH access with sudo privileges
- [ ] Domain pointing to server IP (DNS verified)
- [ ] Firewall rules configured
- [ ] System packages updated: `apt update && apt upgrade -y`

### Docker Installation
- [ ] Docker installed: `docker --version` shows 20.10+
- [ ] Docker Compose installed: `docker-compose --version` shows 1.29+
- [ ] Docker service running: `systemctl status docker`
- [ ] Docker service enabled: `systemctl enable docker`

### Directory Structure
- [ ] Repository cloned to `/home/wwwroot/dutacomputer`
- [ ] Correct branch checked out: `git checkout main`
- [ ] Directory permissions set: `chown -R www-data:www-data .`
- [ ] Backups directory created: `mkdir -p backups && chmod 755 backups`

---

## Phase 2: Environment Configuration

### .env File Setup
- [ ] `.env` file created from `.env example`
- [ ] `APP_ENV=production` set
- [ ] `APP_DEBUG=false` set (CRITICAL!)
- [ ] `APP_URL=https://yourdomain.com` uses HTTPS
- [ ] `.env` permissions correct: `chmod 640 .env`
- [ ] `.env` owner correct: `chown www-data:www-data .env`

### Database Configuration
- [ ] `DB_CONNECTION=mysql` set
- [ ] `DB_HOST=db` set (Docker service name)
- [ ] `DB_DATABASE=absensi_db` set
- [ ] `DB_USERNAME=absensi_user` set
- [ ] `DB_PASSWORD` is strong (12+ chars, mixed case, numbers, symbols)
- [ ] `DB_ROOT_PASSWORD` is strong (different from DB_PASSWORD)
- [ ] `DB_SEED=false` set (automatic seeding disabled)

### Redis Configuration
- [ ] `REDIS_HOST=redis` set
- [ ] `REDIS_PASSWORD` is strong (12+ chars)
- [ ] Redis port NOT exposed in docker-compose.yml
- [ ] Redis only accessible internally

### Cache & Session
- [ ] `CACHE_DRIVER=redis` set
- [ ] `SESSION_DRIVER=database` set
- [ ] `QUEUE_CONNECTION=database` set
- [ ] Session encryption enabled: `SESSION_ENCRYPT=true`

### Mail Configuration (if using SMTP)
- [ ] `MAIL_MAILER=smtp` (or provider)
- [ ] `MAIL_HOST` configured (SMTP server)
- [ ] `MAIL_PORT` set (usually 587 or 465)
- [ ] `MAIL_USERNAME` and `MAIL_PASSWORD` set
- [ ] `MAIL_FROM_ADDRESS` is valid domain address
- [ ] `MAIL_FROM_NAME` set
- [ ] Mail tested in staging environment

### Logging Configuration
- [ ] `LOG_CHANNEL=daily` set (production)
- [ ] `LOG_LEVEL=warning` set (production, not debug)
- [ ] `LOG_DAILY_DAYS=14` set (retention period)

### Security Configuration
- [ ] `CORS_ALLOWED_ORIGINS` set to production domain
- [ ] No hardcoded passwords in docker-compose.yml
- [ ] All passwords use environment variables
- [ ] `.env` file NOT in version control (.gitignore verified)

---

## Phase 3: Docker Configuration

### docker-compose.yml
- [ ] Redis port 6379 removed from ports section
- [ ] All passwords use `${DB_PASSWORD}` syntax (not hardcoded)
- [ ] `CACHE_DRIVER` set to redis for all services
- [ ] Logging driver configured with rotation limits
- [ ] `depends_on` includes redis for services needing cache
- [ ] Network properly configured (absensi-network)

### Dockerfile
- [ ] Entrypoint script checks `APP_ENV` before auto-migration
- [ ] Auto-migration disabled in production (only if APP_ENV != production)
- [ ] Auto-seeding disabled in production
- [ ] HEALTHCHECK directive added for container monitoring
- [ ] Cleanup commands remove cache and temp files

### Docker Build & Startup
- [ ] Docker images built successfully: `docker-compose build`
- [ ] No build errors or warnings in output
- [ ] Container startup log reviewed for warnings
- [ ] Containers started: `docker-compose up -d`
- [ ] All containers status Up: `docker-compose ps`
  - [ ] dutacomputer-app: Up (healthy)
  - [ ] dutacomputer-db: Up (healthy)
  - [ ] dutacomputer-redis: Up
  - [ ] dutacomputer-queue-worker: Up
  - [ ] dutacomputer-scheduler: Up

---

## Phase 4: Database Initialization

### Database Setup
- [ ] Database is ready (wait up to 60 seconds)
- [ ] Database connection tested: `docker-compose exec app php artisan tinker`
- [ ] `APP_KEY` generated: `docker-compose exec app php artisan key:generate`
- [ ] Migrations run successfully: `docker-compose exec app php artisan migrate --force`
- [ ] Migration output reviewed for errors

### Initial Data (First Deployment Only)
- [ ] Initial seeding completed: `docker-compose exec app php artisan db:seed --force`
- [ ] Default admin user created with secure password
- [ ] Admin credentials saved securely
- [ ] Roles and permissions seeded
- [ ] Initial data verified in database

### Cache Warming
- [ ] Config cached: `docker-compose exec app php artisan config:cache`
- [ ] Routes cached: `docker-compose exec app php artisan route:cache`
- [ ] Views cached: `docker-compose exec app php artisan view:cache`
- [ ] Storage symlink created: `docker-compose exec app php artisan storage:link`

---

## Phase 5: Application Verification

### Health Check Endpoint
- [ ] Health check endpoint implemented: `/app/Http/Controllers/HealthController.php`
- [ ] Routes registered in `routes/api.php`
- [ ] Health endpoint accessible: `curl http://localhost:80/api/health`
- [ ] Returns JSON with status "healthy" or "degraded"

### Basic Application Tests
- [ ] Homepage loads: `https://yourdomain.com`
- [ ] Login page accessible
- [ ] API login endpoint responds: `POST /api/v1/auth/login`
- [ ] Dashboard loads after login
- [ ] Database operations working (can query data)
- [ ] File upload working: `public/uploads/` writable
- [ ] Authentication working (can generate API tokens)
- [ ] Cache working (Redis accessible)

### Container Logs Review
- [ ] No critical errors in logs: `docker-compose logs app`
- [ ] Database connection success confirmed
- [ ] Redis connection success confirmed
- [ ] Queue worker running without errors
- [ ] Scheduler running without errors

---

## Phase 6: Security Hardening

### Environment Security
- [ ] `APP_DEBUG=false` (absolutely critical!)
- [ ] `APP_ENV=production`
- [ ] No `.env` file accessible from web
- [ ] `.env` file permissions: 640
- [ ] `.env` file owner: www-data
- [ ] Sensitive files not in public directory

### Database Security
- [ ] MySQL root password changed from default
- [ ] Application database user has limited privileges
- [ ] Remote MySQL access disabled
- [ ] Database port 3306 NOT exposed
- [ ] Database backups encrypted (if applicable)

### Redis Security
- [ ] Redis password set (strong password)
- [ ] Redis port 6379 NOT exposed
- [ ] Redis only accessible from internal network
- [ ] Redis requires authentication

### Application Security
- [ ] CSRF protection enabled (Laravel default)
- [ ] SQL injection protection via Eloquent ORM
- [ ] XSS protection via Blade templating
- [ ] Password hashing: bcrypt rounds set to 12
- [ ] Rate limiting configured for auth endpoints
- [ ] Health check endpoint throttled appropriately

### Firewall Configuration
- [ ] Firewall enabled: `sudo ufw enable`
- [ ] SSH allowed: `sudo ufw allow 22/tcp`
- [ ] HTTP allowed: `sudo ufw allow 80/tcp`
- [ ] HTTPS allowed: `sudo ufw allow 443/tcp`
- [ ] No other ports exposed
- [ ] Firewall status verified: `sudo ufw status`

---

## Phase 7: Nginx & Reverse Proxy

### Nginx Installation
- [ ] Nginx installed: `sudo systemctl status nginx`
- [ ] Nginx service enabled: `sudo systemctl enable nginx`
- [ ] Nginx running properly

### Reverse Proxy Configuration
- [ ] Docker app container IP identified: `docker inspect dutacomputer-app | grep IPAddress`
- [ ] Nginx config updated with correct container IP
- [ ] Proxy configuration includes:
  - [ ] X-Real-IP header
  - [ ] X-Forwarded-For header
  - [ ] X-Forwarded-Proto header (https)
  - [ ] Upgrade header (WebSocket support)
  - [ ] Connection keep-alive
  - [ ] Proper timeouts (connect, read, send)

### Nginx Testing
- [ ] Nginx config syntax valid: `sudo nginx -t`
- [ ] No config errors
- [ ] Nginx reloaded: `sudo systemctl reload nginx`
- [ ] Nginx service healthy: `sudo systemctl status nginx`

### HTTP Headers
- [ ] Security headers configured:
  - [ ] Strict-Transport-Security (HSTS)
  - [ ] X-Frame-Options: SAMEORIGIN
  - [ ] X-Content-Type-Options: nosniff
  - [ ] X-XSS-Protection
  - [ ] Referrer-Policy
- [ ] Headers verified in response: `curl -I https://yourdomain.com`

---

## Phase 8: SSL/TLS Certificate

### Let's Encrypt Certificate
- [ ] Certbot installed: `certbot --version`
- [ ] Certificate generated: `sudo certbot certonly --nginx -d yourdomain.com`
- [ ] Certificate files exist:
  - [ ] `/etc/letsencrypt/live/yourdomain.com/fullchain.pem`
  - [ ] `/etc/letsencrypt/live/yourdomain.com/privkey.pem`

### Nginx SSL Configuration
- [ ] Nginx config uses fullchain.pem (not cert.pem)
- [ ] SSL certificate paths correct
- [ ] SSL protocols: TLSv1.2 & TLSv1.3 only
- [ ] Strong ciphers configured
- [ ] Session cache configured
- [ ] HSTS header configured (max-age: 31536000 or higher)

### SSL Verification
- [ ] HTTPS access works: `https://yourdomain.com`
- [ ] Certificate is valid (green lock in browser)
- [ ] No certificate warnings
- [ ] HTTP redirects to HTTPS: `curl -I http://yourdomain.com` → 301 HTTPS
- [ ] SSL Labs grade: A or A+
- [ ] Certificate chain complete

### SSL Auto-Renewal
- [ ] Certbot timer enabled: `sudo systemctl enable certbot.timer`
- [ ] Certbot timer running: `sudo systemctl start certbot.timer`
- [ ] Renewal dry-run successful: `sudo certbot renew --dry-run`
- [ ] Renewal logs accessible: `/var/log/letsencrypt/renewal.log`

---

## Phase 9: Backup & Disaster Recovery

### Backup Scripts
- [ ] `deploy/backup.sh` created and executable
- [ ] `deploy/restore.sh` created and executable
- [ ] Scripts tested manually: `./deploy/backup.sh`
- [ ] First backup created successfully
- [ ] Backup verification passed (tar integrity check)

### Automated Backups
- [ ] Cron job configured for daily backup (2 AM recommended)
- [ ] Crontab entry: `0 2 * * * cd /home/wwwroot/dutacomputer && ./deploy/backup.sh ...`
- [ ] Cron job verified: `sudo crontab -l`
- [ ] Backup log file location: `/var/log/dutacomputer-backup.log`
- [ ] Log rotation configured

### Backup Retention Policy
- [ ] `BACKUP_RETENTION_DAYS` set (recommended: 14 days)
- [ ] Old backups automatically deleted
- [ ] Sufficient disk space available for backups
- [ ] Backup size estimated and monitored

### Restore Procedure
- [ ] Restore script tested (on staging/test environment)
- [ ] Restore from backup verified working
- [ ] Recovery time objective (RTO) documented
- [ ] Recovery point objective (RPO) documented

### Off-Site Backup (Recommended)
- [ ] Backups copied to remote location (S3, cloud storage, etc.)
- [ ] Or: Remote backup strategy documented
- [ ] Backup encryption enabled (if applicable)

---

## Phase 10: Logging & Monitoring

### Application Logging
- [ ] `LOG_CHANNEL=daily` set
- [ ] `LOG_LEVEL=warning` set (production)
- [ ] Log directory writable: `storage/logs/`
- [ ] Log files being created: `ls -la storage/logs/`
- [ ] Log rotation working (daily logs)

### Docker Container Logging
- [ ] Logging driver configured: json-file
- [ ] Max log size set: 10m per file
- [ ] Max log files set: 3 files (30MB total)
- [ ] Container logs accessible: `docker-compose logs app`

### Log Monitoring
- [ ] Logs reviewed for errors: `docker-compose logs app | grep ERROR`
- [ ] Error patterns identified and addressed
- [ ] Slow query logs monitored (if enabled)
- [ ] Unusual activity patterns reviewed

### Monitoring Tools (Optional)
- [ ] Application monitoring setup (New Relic, Datadog, etc.) - optional
- [ ] Error tracking service setup (Sentry, Rollbar) - optional
- [ ] Uptime monitoring setup (Uptime Robot, etc.) - optional
- [ ] Alert rules configured (if applicable)

---

## Phase 11: Rate Limiting & Security

### Rate Limiting Configuration
- [ ] `AppServiceProvider.php` updated with rate limiting rules
- [ ] Health check endpoint throttled: 120 req/min per IP
- [ ] Auth endpoints throttled: 5 attempts/min per IP
- [ ] API endpoints throttled: 60 req/min per user/IP
- [ ] Rate limiting uses Redis (configured)

### CORS Configuration
- [ ] `CORS_ALLOWED_ORIGINS` environment variable set
- [ ] Only allow production domain and known clients
- [ ] Preflight requests handled correctly

---

## Phase 12: Performance Optimization

### Database Optimization
- [ ] Indexes created (attendances, complaints, users)
- [ ] Query optimization verified in code (eager loading)
- [ ] N+1 query problems prevented (with/without)
- [ ] Slow query monitoring enabled
- [ ] Database connection pooling working

### Caching Strategy
- [ ] Redis cache working: `docker-compose exec app php artisan tinker`
- [ ] Config cached: `config:cache`
- [ ] Routes cached: `route:cache`
- [ ] Views cached: `view:cache`
- [ ] Cache invalidation strategy implemented

### Response Time Targets
- [ ] Page load time: < 2 seconds
- [ ] API response time (p95): < 200ms
- [ ] Database query time (p95): < 100ms
- [ ] Performance baseline documented

---

## Phase 13: Testing & Verification

### Functional Testing
- [ ] User registration/login works
- [ ] Attendance check-in/check-out works
- [ ] Leave request creation works
- [ ] Complaint submission works
- [ ] Report generation works (PDF/CSV)
- [ ] File upload/download works
- [ ] Email notifications sent (if configured)
- [ ] Two-factor authentication works (if enabled)

### API Testing
- [ ] Login endpoint responds with token
- [ ] Protected endpoints require valid token
- [ ] Invalid tokens rejected (401)
- [ ] Expired tokens rejected
- [ ] Rate limits enforced (429)
- [ ] CORS headers present in responses

### Performance Testing
- [ ] Load test with 100+ concurrent users
- [ ] Page load time acceptable under load
- [ ] No memory leaks (container memory stable)
- [ ] CPU usage normal
- [ ] Database query times acceptable

### Security Testing
- [ ] HTTPS working (no mixed content)
- [ ] SSL certificate valid (browser trust)
- [ ] SQL injection attempts blocked
- [ ] XSS attempts blocked
- [ ] CSRF protection working

---

## Phase 14: Monitoring & Alerting Setup

### System Monitoring
- [ ] Disk space monitored: `df -h /`
- [ ] Memory usage monitored: `free -m`
- [ ] CPU usage monitored
- [ ] Container health monitored: `docker-compose ps`
- [ ] Service uptime monitored

### Alert Configuration
- [ ] Disk space alert (> 80% full)
- [ ] Memory alert (> 85% used)
- [ ] Backup failure alert
- [ ] Certificate expiry alert
- [ ] Error rate alert

### Monitoring Commands
- [ ] Real-time monitoring script: `watch 'docker-compose ps'`
- [ ] Log monitoring: `tail -f storage/logs/laravel-*.log`
- [ ] System monitoring: `htop` or `top`

---

## Phase 15: Post-Deployment Tasks

### Admin Setup
- [ ] Default admin password changed
- [ ] Admin account setup with secure password (20+ chars)
- [ ] Second admin account created (for redundancy)
- [ ] Admin credentials stored securely (password manager)

### User Communication
- [ ] Team notified of deployment
- [ ] Users informed of access URL
- [ ] Documentation provided
- [ ] Support contact information shared

### Documentation & Handover
- [ ] Deployment procedure documented
- [ ] Configuration documented
- [ ] Backup/restore procedures documented
- [ ] Monitoring procedures documented
- [ ] Troubleshooting guide prepared
- [ ] On-call engineer assigned

### Team Training
- [ ] Team trained on deployment process
- [ ] Team trained on backup/restore
- [ ] Team trained on monitoring
- [ ] Team trained on troubleshooting
- [ ] Runbook available

---

## Phase 16: 24-Hour Monitoring

### Initial Monitoring (First 24 Hours)
- [ ] Monitor logs continuously
- [ ] Check for errors: `docker-compose logs -f app | grep ERROR`
- [ ] Verify all features working
- [ ] Monitor resource usage
- [ ] Monitor backup completion
- [ ] Collect baseline metrics

### Issue Resolution
- [ ] Any errors documented
- [ ] Issues resolved immediately
- [ ] Root cause analysis performed
- [ ] Fixes tested in staging first

### Performance Baseline
- [ ] Average response time recorded
- [ ] Error rate recorded
- [ ] Resource usage recorded
- [ ] User feedback collected

---

## Phase 17: Final Sign-Off

### Production Ready Confirmation
- [ ] All checklist items completed
- [ ] Application stable (no crashes in 24h)
- [ ] All tests passed
- [ ] Monitoring in place
- [ ] Backups verified working
- [ ] Team trained
- [ ] Documentation complete
- [ ] Go-live approved

### Sign-Off
- **Deployed By**: ___________________________
- **Date**: ___________________________
- **Time**: ___________________________
- **Reviewed By**: ___________________________
- **Approved By**: ___________________________
- **Notes**: ___________________________

---

## Emergency Contacts

| Role | Name | Contact |
|------|------|---------|
| Technical Lead | _________________ | _________________ |
| Database Admin | _________________ | _________________ |
| System Admin | _________________ | _________________ |
| On-Call Engineer | _________________ | _________________ |

---

## Escalation Procedure

1. **Level 1**: On-call engineer (respond within 15 min)
2. **Level 2**: Technical lead (respond within 30 min)
3. **Level 3**: System administrator (respond within 1 hour)

---

## Post-Deployment Support

### Weekly Tasks
- [ ] Review logs for errors
- [ ] Check disk usage
- [ ] Verify backup completion
- [ ] Monitor performance metrics
- [ ] Check for security updates

### Monthly Tasks
- [ ] Test backup restore procedure
- [ ] Review security logs
- [ ] Update dependencies (if applicable)
- [ ] Performance review
- [ ] Team training refresher

### Quarterly Tasks
- [ ] Disaster recovery drill
- [ ] Security audit
- [ ] Performance optimization review
- [ ] Capacity planning

---

## Summary

✅ **Deployment Checklist Complete**

Aplikasi PT DUTA COMPUTER Sistem Manajemen Absensi sudah siap untuk production dengan:
- Secure Docker environment
- HTTPS/SSL configured
- Automated backups
- Monitoring & logging
- Rate limiting
- Health checks
- Database optimization
- Team training & documentation

---

## Additional Resources

- [docs/PRODUCTION_DEPLOYMENT.md](PRODUCTION_DEPLOYMENT.md) - Step-by-step deployment guide
- [docs/NGINX_AAPANEL_SETUP.md](NGINX_AAPANEL_SETUP.md) - Nginx configuration guide
- [docs/SSL_SETUP.md](SSL_SETUP.md) - SSL/TLS setup guide
- [docs/ENVIRONMENT_PRODUCTION.md](ENVIRONMENT_PRODUCTION.md) - Environment configuration
- [docs/LOGGING_MONITORING.md](LOGGING_MONITORING.md) - Logging & monitoring guide
- [docs/DATABASE_OPTIMIZATION.md](DATABASE_OPTIMIZATION.md) - Database optimization
- [docs/RATE_LIMITING.md](RATE_LIMITING.md) - Rate limiting configuration
- [deploy/BACKUP_SETUP.md](../deploy/BACKUP_SETUP.md) - Backup setup guide

---

**Template Version**: 2.0.0  
**Last Updated**: January 20, 2024
