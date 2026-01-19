# SSL/TLS Certificate Setup Guide

## Overview

Panduan lengkap untuk setup HTTPS/SSL dengan Let's Encrypt untuk PT DUTA COMPUTER Sistem Manajemen Absensi.

## Why HTTPS is Critical

1. **Security**: Encrypts data in transit (user credentials, attendance data, etc.)
2. **Authentication**: Proves domain ownership to users
3. **SEO**: Google gives preference to HTTPS sites
4. **Browser Trust**: Modern browsers warn for non-HTTPS sites
5. **Compliance**: Many regulations require HTTPS

## Prerequisites

- Domain pointing to server IP
- Nginx configured and running
- Port 80 and 443 accessible
- SSH access to server
- Email address for Let's Encrypt

## Step 1: Install Certbot

### 1.1 Install Certbot Packages

```bash
# Update package manager
apt update

# Install Certbot and Nginx plugin
apt install -y certbot python3-certbot-nginx

# Verify installation
certbot --version
# Expected: certbot 2.x.x or higher
```

### 1.2 Verify Certbot Installed

```bash
# Check certbot location
which certbot
# Expected: /usr/bin/certbot

# Check version
certbot --version
```

## Step 2: Generate SSL Certificate

### 2.1 Using Nginx Plugin (Recommended)

```bash
# Stop Nginx temporarily (if needed)
# sudo systemctl stop nginx

# Generate certificate (interactive)
sudo certbot certonly --nginx -d absensi.yourdomain.com

# Or non-interactive:
sudo certbot certonly --nginx \
    --agree-tos \
    --non-interactive \
    -m your-email@example.com \
    -d absensi.yourdomain.com

# Expected output:
# Congratulations! Your certificate and chain have been saved at:
# /etc/letsencrypt/live/absensi.yourdomain.com/fullchain.pem
```

### 2.2 Using Standalone Method (if Nginx issues)

```bash
# Stop Nginx first
sudo systemctl stop nginx

# Generate certificate
sudo certbot certonly --standalone \
    --agree-tos \
    -m your-email@example.com \
    -d absensi.yourdomain.com

# Start Nginx after
sudo systemctl start nginx
```

### 2.3 Multiple Domains (Optional)

```bash
# Add multiple domains
sudo certbot certonly --nginx \
    -d absensi.yourdomain.com \
    -d www.absensi.yourdomain.com \
    -d api.absensi.yourdomain.com
```

## Step 3: Verify Certificate

### 3.1 Check Certificate Files

```bash
# List certificate files
ls -la /etc/letsencrypt/live/absensi.yourdomain.com/

# Expected files:
# - cert.pem (your certificate)
# - chain.pem (intermediate certificates)
# - fullchain.pem (complete certificate chain - recommended for Nginx)
# - privkey.pem (private key - keep secret!)
```

### 3.2 Check Certificate Details

```bash
# View certificate information
openssl x509 -in /etc/letsencrypt/live/absensi.yourdomain.com/cert.pem -text -noout

# Check certificate validity dates
openssl x509 -in /etc/letsencrypt/live/absensi.yourdomain.com/cert.pem -noout -dates

# Expected output:
# notBefore=Jan 20 00:00:00 2024 GMT
# notAfter=Apr 19 23:59:59 2024 GMT (3 months validity)
```

### 3.3 Check Certificate Chain

```bash
# Verify certificate chain is complete
openssl verify -CAfile /etc/letsencrypt/live/absensi.yourdomain.com/chain.pem \
    /etc/letsencrypt/live/absensi.yourdomain.com/cert.pem

# Expected output:
# /etc/letsencrypt/live/absensi.yourdomain.com/cert.pem: OK
```

## Step 4: Configure Nginx with SSL

### 4.1 Update Nginx Configuration

In your Nginx server block, add SSL directives:

```nginx
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name absensi.yourdomain.com;

    # SSL certificates - use fullchain.pem (not cert.pem)
    ssl_certificate /etc/letsencrypt/live/absensi.yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/absensi.yourdomain.com/privkey.pem;

    # SSL configuration
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;
    ssl_session_tickets off;

    # HSTS header
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    # Your proxy configuration
    location / {
        proxy_pass http://docker_app;
        # ... rest of proxy config
    }
}

# HTTP to HTTPS redirect
server {
    listen 80;
    listen [::]:80;
    server_name absensi.yourdomain.com;

    location /.well-known/acme-challenge/ {
        root /var/www/certbot;
    }

    location / {
        return 301 https://$server_name$request_uri;
    }
}
```

### 4.2 Test Nginx Configuration

```bash
# Test configuration syntax
sudo nginx -t

# Expected output:
# nginx: the configuration file /etc/nginx/nginx.conf syntax is ok
# nginx: configuration will be tested successfully
```

### 4.3 Reload Nginx

```bash
# Reload Nginx with new configuration
sudo systemctl reload nginx

# Verify status
sudo systemctl status nginx
```

## Step 5: Verify HTTPS Connection

### 5.1 Test from Command Line

```bash
# Test HTTPS connection
curl -I https://absensi.yourdomain.com

# Expected output:
# HTTP/2 200
# Server: nginx
# Strict-Transport-Security: max-age=31536000

# Test with certificate info
curl -vI https://absensi.yourdomain.com 2>&1 | grep -E "SSL|TLS|subject"
```

### 5.2 Test from Browser

```
1. Open https://absensi.yourdomain.com
2. Check for green lock icon
3. Click lock icon to view certificate details
4. Verify certificate is valid and matches domain
```

### 5.3 Check SSL Quality Online

Visit these sites to test SSL quality:

1. **SSL Labs**: https://www.ssllabs.com/ssltest/
   - Enter your domain
   - Grade should be A or A+

2. **Qualys**: https://www.qualys.com/
   - Comprehensive SSL analysis

3. **SSL Checker**: https://www.sslshopper.com/ssl-checker.html
   - Quick verification

4. **Mozilla Observatory**: https://observatory.mozilla.org/
   - Check overall security

## Step 6: Setup Auto-Renewal

### 6.1 Enable Certbot Timer

```bash
# Enable automatic renewal timer
sudo systemctl enable certbot.timer

# Start the timer
sudo systemctl start certbot.timer

# Check timer status
sudo systemctl status certbot.timer

# Expected output:
# ● certbot.timer - Run certbot twice daily
#    Loaded: loaded (...; enabled; ...)
#    Active: active (waiting)
```

### 6.2 Test Renewal (Dry-Run)

```bash
# Test renewal without actually renewing
sudo certbot renew --dry-run

# Expected output:
# Congratulations, all renewals succeeded!
```

### 6.3 Check Renewal Log

```bash
# View renewal attempts
sudo tail -20 /var/log/letsencrypt/renewal.log

# View all renewal logs
sudo ls -la /var/log/letsencrypt/

# Check next renewal date
sudo certbot certificates
```

### 6.4 Force Renewal (if needed)

```bash
# Force immediate renewal (only if < 30 days to expiry)
sudo certbot renew

# Or for specific domain
sudo certbot renew --cert-name absensi.yourdomain.com
```

## Step 7: Monitor Certificate Expiry

### 7.1 Check Expiry Date

```bash
# View all certificates
sudo certbot certificates

# Expected output:
# Found the following certificates:
# - Domains: absensi.yourdomain.com
#   Expiry Date: 2024-04-19 23:59:59+00:00
#   Certificate Path: /etc/letsencrypt/live/absensi.yourdomain.com/fullchain.pem
```

### 7.2 Check via OpenSSL

```bash
# Check certificate expiry
openssl x509 -in /etc/letsencrypt/live/absensi.yourdomain.com/cert.pem -noout -dates

# Extract just expiry date
openssl x509 -in /etc/letsencrypt/live/absensi.yourdomain.com/cert.pem -noout -enddate
```

### 7.3 Setup Expiry Reminder

```bash
# Create reminder script
sudo tee /usr/local/bin/check-ssl-expiry.sh << 'EOF'
#!/bin/bash

DOMAIN="absensi.yourdomain.com"
CERT_FILE="/etc/letsencrypt/live/$DOMAIN/cert.pem"

# Get expiry date
EXPIRY=$(openssl x509 -in $CERT_FILE -noout -enddate | cut -d= -f2)
EXPIRY_EPOCH=$(date -d "$EXPIRY" +%s)
CURRENT_EPOCH=$(date +%s)
DAYS_LEFT=$(( ($EXPIRY_EPOCH - $CURRENT_EPOCH) / 86400 ))

# Alert if less than 7 days
if [ $DAYS_LEFT -lt 7 ]; then
    echo "WARNING: SSL certificate expires in $DAYS_LEFT days!"
    mail -s "SSL Certificate Expiry Warning" admin@example.com << MESSAGE
Domain: $DOMAIN
Days Left: $DAYS_LEFT
Expiry Date: $EXPIRY

Your certificate will expire soon. Check renewal status.
MESSAGE
fi
EOF

# Make executable
sudo chmod +x /usr/local/bin/check-ssl-expiry.sh

# Add to cron (daily check)
sudo crontab -e
# Add: 0 9 * * * /usr/local/bin/check-ssl-expiry.sh
```

## Troubleshooting

### Issue: Certificate Not Found

**Error:** `certbot: error: the following problems were reported: The staple certificate does not match the certificate chain`

**Solution:**
```bash
# Use fullchain.pem (not cert.pem)
ssl_certificate /etc/letsencrypt/live/absensi.yourdomain.com/fullchain.pem;
ssl_certificate_key /etc/letsencrypt/live/absensi.yourdomain.com/privkey.pem;

# Test nginx
sudo nginx -t

# Reload
sudo systemctl reload nginx
```

### Issue: Port 80 Not Accessible

**Error:** `Error in authorization procedure. absensi.yourdomain.com (tls-sni-01): urn:acme:error:connection`

**Solution:**
```bash
# Ensure firewall allows port 80
sudo ufw allow 80/tcp

# Verify port is accessible
curl -I http://absensi.yourdomain.com

# Check DNS is resolving
nslookup absensi.yourdomain.com

# Check domain points to correct IP
dig absensi.yourdomain.com +short
```

### Issue: Renewal Not Working

**Error:** Certificate not renewed automatically

**Solution:**
```bash
# Check timer is enabled
sudo systemctl status certbot.timer

# If disabled, enable it
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer

# Force renewal
sudo certbot renew --force-renewal

# Check logs
sudo tail -50 /var/log/letsencrypt/renewal.log
```

### Issue: Wrong Certificate Showing

**Error:** Browser shows different domain or old certificate

**Solution:**
```bash
# Check which certificate Nginx is using
openssl s_client -connect absensi.yourdomain.com:443

# Should show your certificate, not someone else's

# If wrong certificate:
# 1. Clear browser cache (Ctrl+Shift+Delete)
# 2. Check Nginx config points to correct cert
# 3. Reload nginx: sudo systemctl reload nginx
```

### Issue: Certificate Chain Incomplete

**Error:** SSL Labs shows chain incomplete warning

**Solution:**
```bash
# Always use fullchain.pem (not cert.pem)
grep ssl_certificate /etc/nginx/sites-available/absensi.yourdomain.com

# Should show:
# ssl_certificate .../fullchain.pem;

# If using cert.pem, change to fullchain.pem

# Test chain
openssl s_client -connect absensi.yourdomain.com:443 -showcerts

# Should show complete chain
```

## Security Best Practices

### 1. Use TLS 1.2 & 1.3 Only

```nginx
ssl_protocols TLSv1.2 TLSv1.3;
```

**Why:** Older TLS versions (1.0, 1.1) have known vulnerabilities

### 2. Strong Ciphers

```nginx
ssl_ciphers HIGH:!aNULL:!MD5:!3DES;
ssl_prefer_server_ciphers on;
```

**Why:** Prevents weak cipher attacks

### 3. Enable HSTS

```nginx
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
```

**Why:** Forces HTTPS on all future connections

### 4. Certificate Pinning (Advanced)

```nginx
add_header Public-Key-Pins 'pin-sha256="..."; max-age=31536000';
```

**Warning:** Use carefully - can lock users out if certificate changes

### 5. Keep Certificates Secure

```bash
# Restrict access to private key
ls -la /etc/letsencrypt/live/*/privkey.pem
# Should be readable only by root

# Never share or backup private key
```

## Advanced: Multiple Certificates

### Different Certificate Per Domain

```bash
# Get certificate for another domain
sudo certbot certonly --nginx -d api.yourdomain.com

# Configure in separate Nginx block
server {
    listen 443 ssl http2;
    server_name api.yourdomain.com;
    
    ssl_certificate /etc/letsencrypt/live/api.yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/api.yourdomain.com/privkey.pem;
}
```

### Wildcard Certificate (*.yourdomain.com)

```bash
# Get wildcard certificate
sudo certbot certonly --dns-cloudflare -d yourdomain.com -d '*.yourdomain.com'

# Or with different DNS provider
sudo certbot certonly --dns-route53 -d yourdomain.com -d '*.yourdomain.com'
```

## Monitoring & Alerting

### Setup Expiry Alert

```bash
# Add to cron (weekly check)
sudo crontab -e

# 0 9 * * 0 test $(( $(date -d "$(openssl x509 -in /etc/letsencrypt/live/absensi.yourdomain.com/cert.pem -noout -enddate | cut -d= -f2)" +%s) - $(date +%s) )) -lt 604800) && mail -s "SSL Expiry Alert" admin@example.com
```

## Summary

HTTPS/SSL setup dengan Let's Encrypt:
- ✅ Free SSL certificate
- ✅ Automatic renewal every 90 days
- ✅ Secure encryption (TLS 1.2/1.3)
- ✅ A+ grade security rating
- ✅ Easy to setup and maintain

## References

- [Let's Encrypt Documentation](https://letsencrypt.org/)
- [Certbot Documentation](https://certbot.eff.org/)
- [OWASP TLS Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Transport_Layer_Protection_Cheat_Sheet.html)
- [Mozilla SSL Configuration](https://ssl-config.mozilla.org/)
