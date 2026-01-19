# Deployment Checklist - PT DUTA COMPUTER

Checklist lengkap sebelum deploy ke production dengan Docker Compose di AaPanel.

## Pre-Deployment

### Server Preparation
- [ ] VPS/Server sudah running
- [ ] OS adalah Ubuntu 20.04+ (rekomendasi 22.04)
- [ ] AaPanel sudah terinstall
- [ ] Akses SSH dengan root/sudo privileges
- [ ] Domain sudah pointing ke IP server
- [ ] Firewall sudah dikonfigurasi

### Dependency Installation
- [ ] Docker sudah terinstall (`docker --version`)
- [ ] Docker Compose sudah terinstall (`docker-compose --version`)
- [ ] Git sudah terinstall
- [ ] Certbot sudah terinstall (untuk SSL)
- [ ] Docker service running (`systemctl status docker`)

### Code Preparation
- [ ] Repository sudah di-clone ke `/home/wwwroot/dutacomputer`
- [ ] Branch sudah checkout ke `main` atau production branch
- [ ] `.env.example` sudah dicopy ke `.env`
- [ ] `.gitignore` sudah properly configured
- [ ] Tidak ada secret key di repository

---

## Environment Configuration

### .env Configuration
- [ ] `APP_NAME=PT DUTA COMPUTER - Sistem Manajemen Absensi`
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=https://yourdomain.com` (dengan HTTPS)
- [ ] `APP_KEY` tidak kosong (akan digenerate)

### Database Configuration
- [ ] `DB_CONNECTION=mysql`
- [ ] `DB_HOST=db`
- [ ] `DB_PORT=3306`
- [ ] `DB_DATABASE=absensi_db`
- [ ] `DB_USERNAME=absensi_user`
- [ ] `DB_PASSWORD` diubah dari default (strong password!)
- [ ] Database password minimal 12 karakter

### Cache & Session
- [ ] `CACHE_DRIVER=redis`
- [ ] `SESSION_DRIVER=database`
- [ ] `QUEUE_CONNECTION=database`
- [ ] `REDIS_HOST=redis`
- [ ] `REDIS_PORT=6379`

### Mail Configuration (optional)
- [ ] `MAIL_MAILER` dikonfigurasi (smtp/log)
- [ ] `MAIL_HOST` valid (jika pakai SMTP)
- [ ] `MAIL_FROM_ADDRESS` valid
- [ ] `MAIL_FROM_NAME` terisi

### Docker Compose Configuration
- [ ] Docker-compose.yml sudah sesuai environment
- [ ] Container names sudah update
- [ ] Volume paths sudah correct
- [ ] Network configuration sudah proper
- [ ] Environment variables sudah correct

---

## Permissions & File Setup

### Directory Permissions
- [ ] Project directory owned by www-data: `chown -R www-data:www-data /home/wwwroot/dutacomputer`
- [ ] Storage directory writable: `chmod -R 755 storage`
- [ ] Bootstrap cache writable: `chmod -R 755 bootstrap/cache`
- [ ] Public uploads writable: `chmod -R 755 public/uploads`

### File Structure
- [ ] `storage/` directory ada
- [ ] `bootstrap/cache/` directory ada
- [ ] `public/` directory ada dengan `.htaccess`
- [ ] `database/` migrations sudah ready
- [ ] `app/` directory structure complete

---

## Docker Deployment

### Build & Start
- [ ] Run `docker-compose up -d` successfully
- [ ] Verify all containers running: `docker-compose ps`
  - [ ] dutacomputer-app: Up (healthy)
  - [ ] dutacomputer-db: Up (healthy)
  - [ ] dutacomputer-redis: Up (healthy)
  - [ ] dutacomputer-queue-worker: Up
  - [ ] dutacomputer-scheduler: Up

### Database Setup
- [ ] Generate APP_KEY: `docker-compose exec app php artisan key:generate`
- [ ] Run migrations: `docker-compose exec app php artisan migrate --force`
- [ ] Verify database: `docker-compose exec app php artisan tinker`
- [ ] Seed initial data (jika diperlukan): `docker-compose exec app php artisan db:seed --force`

### Application Setup
- [ ] Config cache: `docker-compose exec app php artisan config:cache`
- [ ] Route cache: `docker-compose exec app php artisan route:cache`
- [ ] View cache: `docker-compose exec app php artisan view:cache`
- [ ] Storage link: `docker-compose exec app php artisan storage:link`

### Verify Containers
- [ ] Container logs tidak ada error: `docker-compose logs`
- [ ] Application accessible via localhost test
- [ ] Health endpoint responsive
- [ ] Database connection working
- [ ] Redis connection working

---

## AaPanel & Nginx Configuration

### Website Setup in AaPanel
- [ ] Login ke AaPanel dashboard
- [ ] Add new website dengan domain production
- [ ] Set PHP version ke "Pure Static" (karena pakai Docker)
- [ ] Database section: None (sudah di Docker)
- [ ] Website berhasil dicreate

### Nginx Reverse Proxy Configuration
- [ ] Edit Nginx config file
- [ ] Setup `upstream docker_app` dengan container IP
- [ ] Configure proxy_pass ke Docker container
- [ ] Add proper headers: X-Real-IP, X-Forwarded-For, etc.
- [ ] Set client_max_body_size (min 100M)
- [ ] Configure static files caching
- [ ] Test config: `sudo nginx -t`
- [ ] Restart Nginx: `sudo systemctl restart nginx`

### Docker Container IP
- [ ] Get app container IP: `docker inspect dutacomputer-app | grep IPAddress`
- [ ] Update Nginx config dengan correct IP
- [ ] Test proxy connectivity

---

## SSL Certificate Setup

### Let's Encrypt Certificate
- [ ] Install certbot: `sudo apt install certbot python3-certbot-nginx`
- [ ] Generate certificate: `sudo certbot certonly --nginx -d yourdomain.com`
- [ ] Verify certificate created: `/etc/letsencrypt/live/yourdomain.com/`

### Nginx SSL Configuration
- [ ] Update Nginx config untuk SSL
- [ ] Add SSL certificate paths
- [ ] Configure TLS 1.2 & 1.3
- [ ] Add security headers
- [ ] Redirect HTTP to HTTPS
- [ ] Test config: `sudo nginx -t`
- [ ] Restart Nginx

### SSL Auto-Renewal
- [ ] Enable certbot timer: `sudo systemctl enable certbot.timer`
- [ ] Start certbot timer: `sudo systemctl start certbot.timer`
- [ ] Verify timer status: `sudo systemctl status certbot.timer`
- [ ] Test renewal: `sudo certbot renew --dry-run`

---

## Security Hardening

### Password Security
- [ ] All default passwords diubah
- [ ] Database password strong (12+ chars, mixed case, numbers, symbols)
- [ ] Admin user password strong
- [ ] No passwords in environment variables dari default

### Environment Security
- [ ] APP_DEBUG=false
- [ ] APP_ENV=production
- [ ] No exposed .env file in web root
- [ ] .env file permissions: 640
- [ ] .env owner: www-data

### Firewall Configuration
- [ ] SSH port allowed: `sudo ufw allow 22/tcp`
- [ ] HTTP port allowed: `sudo ufw allow 80/tcp`
- [ ] HTTPS port allowed: `sudo ufw allow 443/tcp`
- [ ] Redis port NOT exposed: port 6379 blocked
- [ ] MySQL port NOT exposed: port 3306 blocked (use docker only)
- [ ] Firewall enabled: `sudo ufw enable`

### Application Security
- [ ] CSRF protection enabled
- [ ] CORS properly configured
- [ ] Rate limiting configured
- [ ] SQL injection protection
- [ ] XSS protection headers
- [ ] Security headers configured in Nginx

---

## Testing & Verification

### Application Testing
- [ ] Homepage loads correctly: `https://yourdomain.com`
- [ ] Login page accessible
- [ ] Authentication working
- [ ] Dashboard loads
- [ ] API endpoints responding
- [ ] Database operations working
- [ ] File upload working
- [ ] Cache working (Redis)
- [ ] Queue working
- [ ] Email notifications (if configured)

### Performance Testing
- [ ] Page load time acceptable
- [ ] Database queries optimized
- [ ] Cache hit rate high
- [ ] Memory usage stable
- [ ] CPU usage normal
- [ ] Disk I/O normal

### Security Testing
- [ ] HTTPS working
- [ ] SSL certificate valid
- [ ] No mixed content warnings
- [ ] Security headers present
- [ ] SQL injection attempts blocked
- [ ] XSS attempts blocked
- [ ] CSRF protection working

---

## Monitoring Setup

### Log Monitoring
- [ ] Application logs accessible: `docker-compose logs app`
- [ ] Error logs monitored
- [ ] Access logs monitored
- [ ] Database logs monitored
- [ ] Log rotation configured

### System Monitoring
- [ ] Disk usage monitoring
- [ ] Memory usage monitoring
- [ ] CPU usage monitoring
- [ ] Container health checks working
- [ ] Database health checks working
- [ ] Redis health checks working

### Alerting (Optional)
- [ ] Email alerts configured
- [ ] Slack notifications (if applicable)
- [ ] Disk space alerts
- [ ] Memory alerts
- [ ] Error rate alerts

---

## Backup & Recovery

### Database Backups
- [ ] Backup schedule defined
- [ ] Backup script created
- [ ] Backup stored in separate location
- [ ] Backup tested & verified
- [ ] Auto-backup enabled (cron job)

### File Backups
- [ ] Uploaded files backup strategy defined
- [ ] Storage backups scheduled
- [ ] Backup retention policy set
- [ ] Recovery procedure documented

### Disaster Recovery
- [ ] Recovery procedure documented
- [ ] RTO (Recovery Time Objective) defined
- [ ] RPO (Recovery Point Objective) defined
- [ ] Recovery testing scheduled

---

## Documentation & Handover

### Documentation
- [ ] Deployment procedure documented
- [ ] Configuration documented
- [ ] Backup procedures documented
- [ ] Monitoring procedures documented
- [ ] Troubleshooting guide prepared
- [ ] Contact information documented

### Knowledge Transfer
- [ ] Team trained on monitoring
- [ ] Team trained on backup/restore
- [ ] Team trained on updating application
- [ ] Team trained on troubleshooting
- [ ] Runbook prepared

---

## Post-Deployment

### 24-Hour Monitoring
- [ ] Monitor application 24 hours
- [ ] Check logs for errors
- [ ] Verify all features working
- [ ] Monitor resource usage
- [ ] Check backup completion

### Performance Baseline
- [ ] Record baseline metrics
- [ ] Document response times
- [ ] Document error rates
- [ ] Document resource usage

### User Testing
- [ ] Communicate with users
- [ ] Gather feedback
- [ ] Monitor for issues
- [ ] Have escalation procedure ready

---

## Final Sign-Off

### Deployment Complete
- [ ] All checklist items completed
- [ ] Application stable
- [ ] All tests passed
- [ ] Monitoring in place
- [ ] Backups working
- [ ] Team trained
- [ ] Documentation complete
- [ ] Go-live approved

### Sign-Off
- **Deployed By**: _________________
- **Date**: _________________
- **Reviewed By**: _________________
- **Approved By**: _________________

---

## Contact Information

**Technical Support**: [contact@yourdomain.com]
**On-Call Engineer**: [phone number]
**Escalation Contact**: [manager contact]

---

**Template Version**: 1.0.0
**Last Updated**: 2024-01-20

---

> **Note**: Sesuaikan checklist ini dengan kebutuhan spesifik project Anda. Jangan skip item yang penting untuk production security.
