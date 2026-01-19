# Nginx & AaPanel Setup Guide

## Overview

Panduan lengkap untuk mengkonfigurasi Nginx di AaPanel sebagai reverse proxy untuk Docker containers.

## Prerequisites

- AaPanel installed and running
- Docker dan Docker Compose running
- Domain pointing to server IP
- SSH access ke server

## Step 1: Create Site in AaPanel

### 1.1 Access AaPanel Dashboard

```
https://your.server.ip:7800
Login dengan credentials AaPanel Anda
```

### 1.2 Add New Site

1. Navigate to **Site Management**
2. Click **Add Site**
3. Configure:
   - **Domain**: `absensi.yourdomain.com`
   - **Root**: `/home/wwwroot/dutacomputer/public`
   - **PHP Version**: Select **Pure Static** (karena menggunakan Docker)
   - **Database**: Select **None** (menggunakan Docker MySQL)
   - **FTP**: Yes (optional)
   - Click **Submit**

### 1.3 Verify Site Created

After creation, site directory structure should be:
```
/home/wwwroot/dutacomputer/
├── app/
├── public/
├── docker-compose.yml
├── Dockerfile
├── nginx.conf.template
└── ...
```

## Step 2: Get Docker Container IP

Before configuring Nginx, kita perlu tahu IP address dari app container.

### 2.1 Find Container IP

```bash
# SSH ke server
ssh root@your.server.ip

# Get app container IP
docker inspect dutacomputer-app | grep -A 2 "IPAddress"

# Example output:
# "IPAddress": "172.18.0.2"
# Copy this IP address
```

### 2.2 Verify Container Accessibility

```bash
# Test direct access to container
curl http://172.18.0.2:80/api/health

# Should return JSON response
```

## Step 3: Configure Nginx in AaPanel

### 3.1 Edit Site Configuration

In AaPanel Dashboard:

1. Go to **Site Management**
2. Find site `absensi.yourdomain.com`
3. Click **Config** button
4. Edit Nginx configuration

### 3.2 Replace with Custom Configuration

Delete all content and paste this configuration:

```nginx
# Replace CONTAINER_IP with actual IP (e.g., 172.18.0.2)
upstream docker_app {
    server CONTAINER_IP:80;
}

# HTTP Server - Redirect to HTTPS
server {
    listen 80;
    listen [::]:80;
    server_name absensi.yourdomain.com www.absensi.yourdomain.com;

    # Let's Encrypt verification
    location /.well-known/acme-challenge/ {
        root /var/www/certbot;
    }

    # Redirect all traffic to HTTPS
    location / {
        return 301 https://$server_name$request_uri;
    }
}

# HTTPS Server
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name absensi.yourdomain.com www.absensi.yourdomain.com;

    # Root directory (not used but required)
    root /home/wwwroot/dutacomputer/public;

    # SSL Certificate - Update after Let's Encrypt setup
    ssl_certificate /etc/letsencrypt/live/absensi.yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/absensi.yourdomain.com/privkey.pem;

    # SSL Configuration - TLS 1.2 & 1.3
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5:!3DES;
    ssl_prefer_server_ciphers on;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;
    ssl_session_tickets off;

    # HSTS - Force HTTPS
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;

    # Logging
    access_log /var/log/nginx/absensi.yourdomain.com.access.log;
    error_log /var/log/nginx/absensi.yourdomain.com.error.log;

    # Client upload size limit
    client_max_body_size 100M;
    client_body_buffer_size 128k;

    # Timeouts
    client_body_timeout 12;
    client_header_timeout 12;
    keepalive_timeout 15;
    send_timeout 10;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml text/javascript application/json application/javascript application/xml+rss application/atom+xml image/svg+xml;
    gzip_disable "msie6";

    # Cache static files (7 days)
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot|webmanifest)$ {
        expires 7d;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    # Deny access to hidden files/directories
    location ~ /\. {
        deny all;
        access_log off;
        log_not_found off;
    }

    # Deny access to sensitive files
    location ~ ~$ {
        deny all;
        access_log off;
        log_not_found off;
    }

    # Health check endpoint (no logging)
    location /api/health {
        proxy_pass http://docker_app;
        proxy_http_version 1.1;
        proxy_set_header Host $host;
        access_log off;
    }

    # Proxy all requests to Docker container
    location / {
        proxy_pass http://docker_app;
        proxy_http_version 1.1;

        # WebSocket support
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";

        # Headers
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_set_header X-Forwarded-Host $host;
        proxy_set_header X-Forwarded-Port $server_port;

        # Timeouts
        proxy_read_timeout 90;
        proxy_connect_timeout 90;
        proxy_send_timeout 90;

        # Buffering
        proxy_buffering on;
        proxy_buffer_size 4k;
        proxy_buffers 24 4k;
        proxy_busy_buffers_size 8k;
        proxy_max_temp_file_size 2048m;
        proxy_temp_file_write_size 32k;

        # Don't modify redirects
        proxy_redirect off;
    }
}

# Optional: Redirect www to non-www
# server {
#     listen 443 ssl http2;
#     listen [::]:443 ssl http2;
#     server_name www.absensi.yourdomain.com;
# 
#     ssl_certificate /etc/letsencrypt/live/absensi.yourdomain.com/fullchain.pem;
#     ssl_certificate_key /etc/letsencrypt/live/absensi.yourdomain.com/privkey.pem;
# 
#     return 301 https://absensi.yourdomain.com$request_uri;
# }
```

### 3.3 Update Container IP in Configuration

Replace `CONTAINER_IP` dengan IP address dari langkah 2.1:

```nginx
upstream docker_app {
    server 172.18.0.2:80;  # Replace with actual IP
}
```

### 3.4 Test Configuration

```bash
# SSH ke server
ssh root@your.server.ip

# Test Nginx configuration
sudo nginx -t

# Expected output:
# nginx: the configuration file /etc/nginx/nginx.conf syntax is ok
# nginx: configuration will be tested successfully
```

### 3.5 Reload Nginx

```bash
# Reload Nginx (graceful)
sudo systemctl reload nginx

# Or restart
sudo systemctl restart nginx

# Verify status
sudo systemctl status nginx
```

## Step 4: SSL Certificate Setup

### 4.1 Install Certbot

```bash
# Install Let's Encrypt Certbot
apt install -y certbot python3-certbot-nginx

# Verify installation
certbot --version
```

### 4.2 Generate SSL Certificate

**Option A: Interactive (Recommended)**
```bash
# Run certbot with Nginx plugin
sudo certbot certonly --nginx -d absensi.yourdomain.com

# Follow the prompts
# Choose "standalone" if Nginx method fails
# Email: your-email@example.com
# Terms: Agree
```

**Option B: Non-interactive**
```bash
# Non-interactive method
sudo certbot certonly --nginx \
    --non-interactive \
    --agree-tos \
    -m your-email@example.com \
    -d absensi.yourdomain.com
```

### 4.3 Verify Certificate Generated

```bash
# List generated certificates
sudo ls -la /etc/letsencrypt/live/absensi.yourdomain.com/

# Expected files:
# - fullchain.pem (server certificate)
# - privkey.pem (private key)
# - chain.pem
# - cert.pem
```

### 4.4 Update Nginx with SSL Paths

In AaPanel, update the Nginx config to use actual certificate paths:

```nginx
ssl_certificate /etc/letsencrypt/live/absensi.yourdomain.com/fullchain.pem;
ssl_certificate_key /etc/letsencrypt/live/absensi.yourdomain.com/privkey.pem;
```

### 4.5 Setup Auto-Renewal

```bash
# Enable Certbot auto-renewal timer
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer

# Test renewal (dry-run)
sudo certbot renew --dry-run

# Expected output: "The following certificates will be renewed"
```

### 4.6 Test HTTPS

```bash
# Test from command line
curl -I https://absensi.yourdomain.com

# Or from browser
https://absensi.yourdomain.com
```

## Step 5: Testing & Verification

### 5.1 Test HTTP to HTTPS Redirect

```bash
# Should redirect to HTTPS
curl -I http://absensi.yourdomain.com

# Expected: 301 Moved Permanently with Location header
```

### 5.2 Test HTTPS Connection

```bash
# Test HTTPS
curl -I https://absensi.yourdomain.com

# Expected: 200 OK

# Test with detailed info
curl -vI https://absensi.yourdomain.com | grep -E "SSL|TLS"
```

### 5.3 Test Health Endpoint

```bash
# Test health check
curl https://absensi.yourdomain.com/api/health

# Expected: JSON response with status
```

### 5.4 Test SSL Quality

```bash
# Check SSL certificate info
openssl s_client -connect absensi.yourdomain.com:443 -servername absensi.yourdomain.com

# Or visit SSL checker online
# https://www.sslshopper.com/ssl-checker.html
# https://www.digicert.com/ssltools/
```

### 5.5 Check Security Headers

```bash
# View response headers
curl -I https://absensi.yourdomain.com | grep -i "^[a-z]"

# Should include:
# Strict-Transport-Security
# X-Frame-Options: SAMEORIGIN
# X-Content-Type-Options: nosniff
```

## Step 6: Monitoring & Maintenance

### 6.1 View Access Logs

```bash
# Real-time access logs
tail -f /var/log/nginx/absensi.yourdomain.com.access.log

# View recent requests (last 50)
tail -50 /var/log/nginx/absensi.yourdomain.com.access.log

# Search for errors
tail -f /var/log/nginx/absensi.yourdomain.com.error.log
```

### 6.2 Monitor Nginx Process

```bash
# Check Nginx status
systemctl status nginx

# View Nginx processes
ps aux | grep nginx

# Check listening ports
netstat -tulpn | grep nginx
```

### 6.3 Certificate Renewal Monitor

```bash
# Check certificate expiry date
echo | openssl s_client -servername absensi.yourdomain.com -connect absensi.yourdomain.com:443 2>/dev/null | openssl x509 -noout -dates

# View Certbot renewal log
sudo cat /var/log/letsencrypt/renewal.log

# List all certificates
sudo certbot certificates
```

## Troubleshooting

### Issue: "Bad Gateway" / 502

**Symptoms:** Browser shows 502 Bad Gateway

**Solution:**
```bash
# 1. Verify Docker container is running
docker-compose ps

# 2. Check container IP is correct
docker inspect dutacomputer-app | grep IPAddress

# 3. Test direct access to container
curl http://172.18.0.2:80/api/health

# 4. Check Nginx error log
tail -50 /var/log/nginx/absensi.yourdomain.com.error.log

# 5. Verify Nginx config
sudo nginx -t
```

### Issue: SSL Certificate Not Working

**Symptoms:** Browser shows insecure/invalid certificate

**Solution:**
```bash
# 1. Verify certificate exists
ls -la /etc/letsencrypt/live/absensi.yourdomain.com/

# 2. Check certificate is valid
openssl x509 -in /etc/letsencrypt/live/absensi.yourdomain.com/fullchain.pem -text -noout | grep -A2 "Not"

# 3. Verify Nginx config has correct paths
grep ssl_certificate /etc/nginx/sites-available/absensi.yourdomain.com

# 4. Reload Nginx
sudo systemctl reload nginx
```

### Issue: Connection Timeout

**Symptoms:** curl/browser hangs then times out

**Solution:**
```bash
# 1. Check firewall
sudo ufw status

# Ensure 80 and 443 are allowed
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# 2. Check Nginx is listening
netstat -tulpn | grep nginx

# 3. Check Docker network
docker network inspect absensi-network

# 4. Test connectivity
curl -v http://172.18.0.2:80/
```

### Issue: Logs Growing Too Large

**Symptoms:** `/var/log/nginx/` using lots of disk space

**Solution:**
```bash
# 1. Setup log rotation
sudo tee /etc/logrotate.d/nginx << 'EOF'
/var/log/nginx/*.log {
    daily
    rotate 7
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    postrotate
        systemctl reload nginx > /dev/null 2>&1 || true
    endscript
}
EOF

# 2. Force rotation
sudo logrotate -f /etc/logrotate.d/nginx

# 3. Check log size
du -sh /var/log/nginx/
```

## Performance Optimization

### 1. Enable Response Compression

Already configured in nginx.conf:
```nginx
gzip on;
gzip_comp_level 6;
```

### 2. Browser Caching

Already configured:
```nginx
location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2)$ {
    expires 7d;
    add_header Cache-Control "public, immutable";
}
```

### 3. Connection Keep-Alive

Already configured:
```nginx
keepalive_timeout 15;
```

### 4. Monitor Performance

```bash
# Check response times
curl -w "@curl-format.txt" https://absensi.yourdomain.com/api/health

# Or create curl-format.txt:
# time_namelookup: %{time_namelookup}
# time_connect: %{time_connect}
# time_total: %{time_total}\n
```

## Advanced Configuration

### Rate Limiting (Optional)

Add to Nginx config:

```nginx
# Define rate limit zones
limit_req_zone $binary_remote_addr zone=api_limit:10m rate=60r/m;
limit_req_zone $binary_remote_addr zone=health_limit:10m rate=120r/m;

# Apply to specific locations
location /api/v1/ {
    limit_req zone=api_limit burst=10 nodelay;
    proxy_pass http://docker_app;
}

location /api/health {
    limit_req zone=health_limit burst=20 nodelay;
    proxy_pass http://docker_app;
}
```

### WAF (Web Application Firewall)

For additional security, consider:
- ModSecurity for Nginx
- Cloudflare WAF
- AWS WAF

### Load Balancing (Multiple Containers)

If scaling to multiple app containers:

```nginx
upstream docker_app {
    server 172.18.0.2:80;
    server 172.18.0.3:80;
    server 172.18.0.4:80;
}
```

## Summary

Nginx di AaPanel sekarang:
- ✅ Reverse proxy ke Docker container
- ✅ HTTPS dengan Let's Encrypt
- ✅ Security headers configured
- ✅ Compression enabled
- ✅ Caching configured
- ✅ Auto-renewal setup
- ✅ Logging configured

## References

- [Nginx Documentation](https://nginx.org/en/docs/)
- [Let's Encrypt Documentation](https://letsencrypt.org/docs/)
- [Certbot Documentation](https://certbot.eff.org/)
- [AaPanel Documentation](https://www.aapanel.com/docs/)
